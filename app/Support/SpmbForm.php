<?php

namespace App\Support;

use Illuminate\Validation\Rule;

/**
 * Resolves the stage-two SPMB form schema from config/spmb.php into
 * level-specific sections and matching validation rules.
 */
class SpmbForm
{
    /**
     * Optional supporting document upload field keys.
     *
     * @var list<string>
     */
    public const DOCUMENT_KEYS = [
        'berkas_kk',
        'berkas_akta',
        'berkas_lainnya',
        'berkas_foto_ananda',
    ];

    /**
     * Get the upload field labels for a level, keyed by field name.
     *
     * @return array<string, string>
     */
    public static function documentFields(string $level): array
    {
        if ($level === 'TK') {
            return [
                'berkas_foto_ananda' => 'Foto Ananda (Pakaian Bebas Muslim/Muslimah)',
                'berkas_akta' => 'Akta Kelahiran',
                'berkas_lainnya' => 'Dokumen Pendukung Lainnya',
            ];
        }

        return [
            'berkas_kk' => 'Kartu Keluarga (KK)',
            'berkas_akta' => 'Akta Kelahiran',
            'berkas_lainnya' => 'Dokumen Pendukung Lainnya',
        ];
    }

    /**
     * Get the "Formulir Lengkap" sections for a level.
     *
     * @return list<array{key: string, title: string, fields: list<array<string, mixed>>}>
     */
    public static function fullFormSections(string $level): array
    {
        return self::resolve(config('spmb.full_form', []), $level);
    }

    /**
     * Get the "Biaya Pendidikan & Kesanggupan Biaya" sections for a level.
     *
     * @return list<array{key: string, title: string, fields: list<array<string, mixed>>}>
     */
    public static function feeSections(string $level): array
    {
        $sections = self::resolve(config('spmb.fee_form', []), $level);

        if ($level === 'TK') {
            // KB-TK uses a single official fee scheme, so the old
            // "Pilihan 1/2/3" fee radios are no longer shown.
            $sections = array_map(function (array $section): array {
                $section['fields'] = array_values(array_filter(
                    $section['fields'],
                    fn (array $field): bool => ! in_array($field['key'], ['pilihan_uang_masuk', 'pilihan_spp'], true),
                ));

                return $section;
            }, $sections);
        }

        if ($level !== 'SMA') {
            return $sections;
        }

        $fees = config('spmb.fees.SMA', []);

        return array_map(function (array $section) use ($fees): array {
            $section['fields'] = array_map(function (array $field) use ($fees): array {
                $nominal = match ($field['key']) {
                    'pilihan_uang_masuk' => $fees['uang_masuk'] ?? [],
                    'pilihan_spp' => $fees['spp'] ?? [],
                    default => [],
                };

                if ($nominal !== []) {
                    $field['options'] = self::nominalOptions(
                        $nominal,
                        $field['key'] === 'pilihan_spp' ? ' / bulan' : '',
                    );
                }

                return $field;
            }, $section['fields']);

            return $section;
        }, $sections);
    }

    /**
     * Get the parent statements for a level.
     *
     * @return array<string, string>
     */
    public static function agreements(string $level): array
    {
        return array_map(
            fn (string $text): string => self::schoolize($text, $level),
            config('spmb.agreements', []),
        );
    }

    /**
     * Build the validation rules for the stage-two submission.
     *
     * @return array<string, list<mixed>>
     */
    public static function rules(string $level): array
    {
        $rules = [];

        foreach (self::fullFormSections($level) as $section) {
            foreach ($section['fields'] as $field) {
                $rules['form.'.$field['key']] = self::fieldRules($field);
            }
        }

        foreach (self::feeSections($level) as $section) {
            foreach ($section['fields'] as $field) {
                $rules['fee.'.$field['key']] = self::fieldRules($field);
            }
        }

        $rules['fee.cicilan_kali'] = [
            'nullable',
            'required_if:fee.cara_bayar,Dicicil',
            'integer',
            Rule::in(range(2, 12)),
        ];

        if ($level === 'TK') {
            $rules['form.has_alumni_siblings'] = [
                'required',
                Rule::in(['YA, ADA', 'TIDAK ADA']),
            ];
            $rules['form.alumni_sibling_names'] = [
                'nullable',
                'string',
                'max:1000',
                Rule::requiredIf(fn (): bool => request()->input('form.has_alumni_siblings') === 'YA, ADA'),
            ];
        }

        $rules['pickup'] = ['nullable', 'array', 'max:'.(int) config('spmb.pickup_rows', 3)];

        foreach (array_keys(config('spmb.pickup_columns', [])) as $column) {
            $rules["pickup.*.{$column}"] = ['nullable', 'string', 'max:100'];
        }

        foreach (self::DOCUMENT_KEYS as $docKey) {
            $rules[$docKey] = ['nullable', 'file', 'mimes:jpg,jpeg,png,webp,pdf', 'max:5120'];
        }

        if ($level === 'TK') {
            $rules['pas_foto_3x4'] = ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'];
        }

        foreach (array_keys(config('spmb.agreements', [])) as $key) {
            $rules["agreements.{$key}"] = ['required', 'accepted'];
        }

        return $rules;
    }

    /**
     * Build human-friendly attribute names for validation messages.
     *
     * @return array<string, string>
     */
    public static function attributes(string $level): array
    {
        $attributes = [];

        foreach (self::fullFormSections($level) as $section) {
            foreach ($section['fields'] as $field) {
                $attributes['form.'.$field['key']] = $field['label'];
            }
        }

        foreach (self::feeSections($level) as $section) {
            foreach ($section['fields'] as $field) {
                $attributes['fee.'.$field['key']] = $field['label'];
            }
        }

        $attributes['pickup.*.nama'] = 'Nama penjemput';
        $attributes['pickup.*.hubungan'] = 'Hubungan penjemput';
        $attributes['pickup.*.telp'] = 'Telp/HP penjemput';

        foreach (self::documentFields($level) as $docKey => $docLabel) {
            $attributes[$docKey] = $docLabel;
        }

        if ($level === 'TK') {
            $attributes['pas_foto_3x4'] = 'Pas Foto Ananda Ukuran 3×4';
            $attributes['form.has_alumni_siblings'] = 'Status saudara kandung alumni TK Islam Cahaya Ilmu';
            $attributes['form.alumni_sibling_names'] = 'Nama saudara kandung yang merupakan alumni TK Islam Cahaya Ilmu';
        }

        foreach (array_keys(config('spmb.agreements', [])) as $index => $key) {
            $attributes["agreements.{$key}"] = 'Pernyataan '.($index + 1);
        }

        return $attributes;
    }

    /**
     * Build the validation rules for a single schema field.
     *
     * @param  array<string, mixed>  $field
     * @return list<mixed>
     */
    private static function fieldRules(array $field): array
    {
        $rules = [($field['required'] ?? false) ? 'required' : 'nullable'];

        return match ($field['type']) {
            'number' => [...$rules, 'integer', 'min:0', 'max:1000'],
            'date' => [...$rules, 'date', 'before:today'],
            'select', 'radio' => [...$rules, Rule::in(array_map('strval', array_keys($field['options'] ?? [])))],
            'textarea' => [...$rules, 'string', 'max:2000'],
            default => [...$rules, 'string', 'max:'.($field['max'] ?? 255)],
        };
    }

    /**
     * Replace the {sekolah} token in every section title, label, and option,
     * and compute layout metadata for each field.
     *
     * @param  list<array<string, mixed>>  $sections
     * @return list<array{key: string, title: string, fields: list<array<string, mixed>>}>
     */
    private static function resolve(array $sections, string $level): array
    {
        return array_map(function (array $section) use ($level): array {
            $section['title'] = self::schoolize($section['title'], $level);
            $section['fields'] = array_map(function (array $field) use ($level): array {
                $field['label'] = self::schoolize($field['label'], $level);
                $field['full_width'] = self::isFullWidthField($field);

                return $field;
            }, $section['fields']);

            return $section;
        }, $sections);
    }

    /**
     * Build "Pilihan N — Rp x" labels from a fee table.
     *
     * @param  array<int, int>  $nominal
     * @return array<string, string>
     */
    private static function nominalOptions(array $nominal, string $suffix = ''): array
    {
        $options = [];

        foreach ($nominal as $choice => $amount) {
            $options[(string) $choice] = 'Pilihan '.$choice.' — Rp '.number_format((int) $amount, 0, ',', '.').$suffix;
        }

        return $options;
    }

    /**
     * Determine whether a field should span both columns in the grid layout.
     *
     * @param  array<string, mixed>  $field
     */
    private static function isFullWidthField(array $field): bool
    {
        if (in_array($field['type'], ['textarea', 'radio'], true)) {
            return true;
        }

        foreach (['alamat', 'kriteria', 'informasi', 'alasan', 'peraturan', 'kelebihan', 'teman'] as $keyword) {
            if (str_contains((string) $field['key'], $keyword)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Replace the {sekolah} token with the level's school name.
     */
    private static function schoolize(string $text, string $level): string
    {
        $schoolName = $level === 'TK'
            ? (string) config('spmb.tk.name', 'KB-TK Islam Cahaya Ilmu')
            : $level.' Islam Cahaya Ilmu';

        return str_replace('{sekolah}', $schoolName, $text);
    }
}
