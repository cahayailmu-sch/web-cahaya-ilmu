<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use PDO;
use RuntimeException;

/**
 * Build a portable SQL dump of the active database without shelling out.
 *
 * The dump is streamed straight to the output buffer so it keeps working on
 * shared/cloud hosting where exec(), shell_exec(), and mysqldump are disabled.
 */
class DatabaseBackupService
{
    public const SCHOOL_NAME = 'Cahaya Ilmu Islamic School';

    public const TIMEZONE = 'Asia/Makassar';

    /**
     * Number of rows fetched per query while walking a table.
     */
    private const ROW_CHUNK_SIZE = 500;

    /**
     * Number of rows combined into a single INSERT statement.
     */
    private const INSERT_BATCH_SIZE = 100;

    /**
     * @var list<string>|null
     */
    private ?array $cachedTables = null;

    private ?int $cachedSize = null;

    private ?PDO $pdo = null;

    /**
     * Build the automatic download filename (WITA based).
     */
    public function fileName(): string
    {
        return 'backup-cahayailmu-'.now(self::TIMEZONE)->format('Y-m-d_H-i').'.sql';
    }

    /**
     * Get the active database driver name.
     */
    public function driver(): string
    {
        return DB::connection()->getDriverName();
    }

    /**
     * Get the active database name.
     */
    public function databaseName(): string
    {
        return (string) DB::connection()->getDatabaseName();
    }

    /**
     * Get every user table of the active database.
     *
     * @return list<string>
     */
    public function tables(): array
    {
        if ($this->cachedTables !== null) {
            return $this->cachedTables;
        }

        $driver = $this->driver();

        $tables = match ($driver) {
            'mysql', 'mariadb' => array_map(
                static fn (object $row): string => (string) array_values((array) $row)[0],
                DB::select("SHOW FULL TABLES WHERE Table_type = 'BASE TABLE'"),
            ),
            'sqlite' => array_map(
                static fn (object $row): string => (string) $row->name,
                DB::select("SELECT name FROM sqlite_master WHERE type = 'table' AND name NOT LIKE 'sqlite_%' ORDER BY name"),
            ),
            default => throw new RuntimeException('Driver database "'.$driver.'" belum didukung untuk backup SQL.'),
        };

        sort($tables);

        return $this->cachedTables = $tables;
    }

    /**
     * Count the tables included in the dump.
     */
    public function tableCount(): int
    {
        return count($this->tables());
    }

    /**
     * Estimate the on-disk size of the active database in bytes.
     */
    public function estimatedSize(): int
    {
        if ($this->cachedSize !== null) {
            return $this->cachedSize;
        }

        $size = match ($this->driver()) {
            'mysql', 'mariadb' => (int) (DB::selectOne(
                'SELECT COALESCE(SUM(data_length + index_length), 0) AS size FROM information_schema.TABLES WHERE table_schema = DATABASE()',
            )?->size ?? 0),
            'sqlite' => ((int) (DB::selectOne('PRAGMA page_count')?->page_count ?? 0))
                * ((int) (DB::selectOne('PRAGMA page_size')?->page_size ?? 0)),
            default => 0,
        };

        return $this->cachedSize = $size;
    }

    /**
     * Stream the dump directly to the response output.
     */
    public function stream(?User $actor = null): void
    {
        $output = fopen('php://output', 'wb');

        if ($output === false) {
            throw new RuntimeException('Tidak dapat membuka output untuk file backup.');
        }

        try {
            $this->write($output, $actor);
        } finally {
            fclose($output);
        }
    }

    /**
     * Write the complete SQL dump to the given stream.
     *
     * @param  resource  $output
     */
    public function write($output, ?User $actor = null): void
    {
        $tables = $this->tables();

        $this->writeHeader($output, count($tables), $actor);
        $this->writePreamble($output);

        foreach ($tables as $table) {
            $this->writeTable($output, $table);
        }

        $this->writeFooter($output);
    }

    /**
     * @param  resource  $output
     */
    private function writeHeader($output, int $tableCount, ?User $actor): void
    {
        $timestamp = now(self::TIMEZONE);

        $lines = [
            '-- ============================================================',
            '-- Backup Database Website',
            '-- Sekolah      : '.self::SCHOOL_NAME,
            '-- Waktu backup : '.$timestamp->locale('id')->translatedFormat('d F Y, H:i:s').' WITA',
            '-- Zona waktu   : '.self::TIMEZONE.' (WITA / UTC+8)',
            '-- Driver       : '.$this->driver(),
            '-- Database     : '.$this->databaseName(),
            '-- Total tabel  : '.$tableCount,
            '-- Dibuat oleh  : '.($actor?->name ?? 'Sistem').' <'.($actor?->email ?? '-').'>',
            '--',
            '-- PERINGATAN: file ini berisi data pribadi. Simpan di tempat aman.',
            '-- ============================================================',
            '',
        ];

        foreach ($lines as $line) {
            $this->writeln($output, $line);
        }
    }

    /**
     * @param  resource  $output
     */
    private function writePreamble($output): void
    {
        if ($this->isSqlite()) {
            $this->writeln($output, 'PRAGMA foreign_keys=OFF;');
            $this->writeln($output, 'BEGIN TRANSACTION;');
            $this->writeln($output);

            return;
        }

        $this->writeln($output, 'SET NAMES utf8mb4;');
        $this->writeln($output, 'SET FOREIGN_KEY_CHECKS=0;');
        $this->writeln($output, "SET SQL_MODE='NO_AUTO_VALUE_ON_ZERO';");
        $this->writeln($output, "SET time_zone='+08:00';");
        $this->writeln($output);
    }

    /**
     * @param  resource  $output
     */
    private function writeFooter($output): void
    {
        if ($this->isSqlite()) {
            $this->writeln($output, 'COMMIT;');
            $this->writeln($output, 'PRAGMA foreign_keys=ON;');
        } else {
            $this->writeln($output, 'SET FOREIGN_KEY_CHECKS=1;');
        }

        $this->writeln($output);
        $this->writeln($output, '-- Backup selesai: '.now(self::TIMEZONE)->locale('id')->translatedFormat('d F Y, H:i:s').' WITA');
    }

    /**
     * @param  resource  $output
     */
    private function writeTable($output, string $table): void
    {
        $this->writeln($output, '-- ------------------------------------------------------------');
        $this->writeln($output, '-- Tabel: '.$table);
        $this->writeln($output, '-- ------------------------------------------------------------');
        $this->writeln($output, $this->tableSchema($table).';');

        if ($this->isSqlite()) {
            foreach ($this->sqliteIndexes($table) as $index) {
                $this->writeln($output, $index.';');
            }
        }

        $this->writeln($output);

        $rowCount = $this->writeRows($output, $table);

        $this->writeln($output, '-- '.$rowCount.' baris data');
        $this->writeln($output);
    }

    /**
     * @param  resource  $output
     */
    private function writeRows($output, string $table): int
    {
        $offset = 0;
        $rowCount = 0;
        $columns = null;
        $buffer = [];

        do {
            $rows = DB::table($table)->offset($offset)->limit(self::ROW_CHUNK_SIZE)->get();

            foreach ($rows as $row) {
                $values = (array) $row;

                if ($columns === null) {
                    $columns = array_keys($values);
                }

                $buffer[] = '('.implode(', ', array_map(
                    fn (string $column): string => $this->quoteValue($values[$column] ?? null),
                    $columns,
                )).')';

                $rowCount++;

                if (count($buffer) >= self::INSERT_BATCH_SIZE) {
                    $this->writeInsert($output, $table, $columns, $buffer);
                    $buffer = [];
                }
            }

            $offset += $rows->count();
        } while ($rows->count() === self::ROW_CHUNK_SIZE);

        if ($columns !== null && $buffer !== []) {
            $this->writeInsert($output, $table, $columns, $buffer);
        }

        return $rowCount;
    }

    /**
     * @param  resource  $output
     * @param  list<string>  $columns
     * @param  list<string>  $rows
     */
    private function writeInsert($output, string $table, array $columns, array $rows): void
    {
        $wrappedColumns = array_map(
            fn (string $column): string => $this->wrapIdentifier($column),
            $columns,
        );

        $this->writeln($output, 'INSERT INTO '.$this->wrapIdentifier($table).' ('.implode(', ', $wrappedColumns).') VALUES');

        $lastIndex = count($rows) - 1;

        foreach ($rows as $index => $values) {
            $this->writeln($output, $values.($index === $lastIndex ? ';' : ','));
        }
    }

    /**
     * Read the CREATE TABLE statement for the given table.
     */
    private function tableSchema(string $table): string
    {
        if ($this->isSqlite()) {
            $row = DB::selectOne("SELECT sql FROM sqlite_master WHERE type = 'table' AND name = ?", [$table]);
            $sql = is_object($row) ? ($row->sql ?? null) : null;
        } else {
            $row = DB::selectOne('SHOW CREATE TABLE '.$this->wrapIdentifier($table));
            $data = is_object($row) ? (array) $row : [];
            $sql = $data['Create Table'] ?? null;
        }

        if (! is_string($sql) || $sql === '') {
            throw new RuntimeException('Struktur tabel "'.$table.'" tidak dapat dibaca.');
        }

        return preg_replace('/^CREATE TABLE\s+/i', 'CREATE TABLE IF NOT EXISTS ', $sql, 1) ?? $sql;
    }

    /**
     * Get the standalone indexes of a SQLite table.
     *
     * @return list<string>
     */
    private function sqliteIndexes(string $table): array
    {
        return array_map(
            static fn (object $row): string => (string) $row->sql,
            DB::select(
                "SELECT sql FROM sqlite_master WHERE type = 'index' AND tbl_name = ? AND sql IS NOT NULL ORDER BY name",
                [$table],
            ),
        );
    }

    /**
     * Quote a single database value for a portable INSERT statement.
     */
    private function quoteValue(mixed $value): string
    {
        if ($value === null) {
            return 'NULL';
        }

        if (is_bool($value)) {
            return $value ? '1' : '0';
        }

        if (is_int($value)) {
            return (string) $value;
        }

        if (is_float($value)) {
            if (is_nan($value) || is_infinite($value)) {
                return 'NULL';
            }

            return var_export($value, true);
        }

        if (is_resource($value)) {
            $value = stream_get_contents($value);
        }

        $string = (string) $value;
        $quoted = $this->pdo()->quote($string);

        return $quoted !== false ? $quoted : "'".addslashes($string)."'";
    }

    /**
     * Wrap a table or column identifier for the active driver.
     */
    private function wrapIdentifier(string $identifier): string
    {
        if ($this->isSqlite()) {
            return '"'.str_replace('"', '""', $identifier).'"';
        }

        return '`'.str_replace('`', '``', $identifier).'`';
    }

    private function isSqlite(): bool
    {
        return $this->driver() === 'sqlite';
    }

    private function pdo(): PDO
    {
        return $this->pdo ??= DB::connection()->getPdo();
    }

    /**
     * @param  resource  $output
     */
    private function writeln($output, string $line = ''): void
    {
        if (fwrite($output, $line."\n") === false) {
            throw new RuntimeException('Gagal menulis file backup database.');
        }
    }
}
