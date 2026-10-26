<?php

namespace Tests\Feature;

use App\Models\PpdbRegistration;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DatabaseBackupTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Create a sample SPMB registration for the dump.
     */
    private function createRegistration(): PpdbRegistration
    {
        return PpdbRegistration::create([
            'registration_number' => 'SPMB-2026-001',
            'full_name' => 'Ahmad Fauzan',
            'gender' => 'L',
            'birth_place' => 'Balikpapan',
            'birth_date' => now()->subYears(8)->toDateString(),
            'level' => 'SD',
            'previous_school' => 'TK Cahaya Ilmu',
            'parent_name' => 'Budi Santoso',
            'whatsapp' => '081250000001',
            'address' => 'Balikpapan',
            'payment_proof' => 'payment-proofs/bukti-transfer.jpg',
            'status' => 'Baru',
        ]);
    }

    public function test_guest_is_redirected_from_backup_download(): void
    {
        $this->get(route('admin.backup.download'))
            ->assertRedirect(route('admin.login'));
    }

    public function test_level_admin_cannot_download_database_backup(): void
    {
        $levelAdmin = User::factory()->forLevel('SD')->create();

        $this->actingAs($levelAdmin)
            ->get(route('admin.backup.download'))
            ->assertForbidden();
    }

    public function test_superadmin_can_download_sql_database_backup(): void
    {
        $superadmin = User::factory()->create([
            'name' => 'Super Admin',
            'email' => 'superadmin@cahayailmu.sch.id',
        ]);

        $this->createRegistration();

        $response = $this->actingAs($superadmin)->get(route('admin.backup.download'));

        $response->assertOk();
        $response->assertDownload();
        $response->assertHeader('content-type', 'application/sql; charset=UTF-8');

        $sql = $response->streamedContent();

        $this->assertStringContainsString('Cahaya Ilmu Islamic School', $sql);
        $this->assertStringContainsString('WITA', $sql);
        $this->assertStringContainsString('CREATE TABLE IF NOT EXISTS', $sql);
        $this->assertStringContainsString('INSERT INTO', $sql);
        $this->assertStringContainsString('PRAGMA foreign_keys=OFF;', $sql);
        $this->assertStringContainsString('PRAGMA foreign_keys=ON;', $sql);
        $this->assertStringContainsString('superadmin@cahayailmu.sch.id', $sql);
        $this->assertStringContainsString('Ahmad Fauzan', $sql);

        $this->assertDatabaseHas('activity_logs', [
            'action' => 'backup',
            'module' => 'Database',
        ]);
    }

    public function test_settings_page_shows_backup_card_only_for_superadmin(): void
    {
        $superadmin = User::factory()->create();
        $levelAdmin = User::factory()->forLevel('SD')->create();

        $this->actingAs($superadmin)
            ->get(route('admin.settings.index'))
            ->assertOk()
            ->assertSee('Cadangan &amp; Keamanan Data', false)
            ->assertSee('Download Backup Database (.sql)', false);

        $this->actingAs($levelAdmin)
            ->get(route('admin.settings.index'))
            ->assertOk()
            ->assertDontSee('Download Backup Database (.sql)', false);
    }

    public function test_dashboard_shows_backup_action_only_for_superadmin(): void
    {
        $superadmin = User::factory()->create();
        $levelAdmin = User::factory()->forLevel('SD')->create();

        $this->actingAs($superadmin)
            ->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSee('Backup Database', false);

        $this->actingAs($levelAdmin)
            ->get(route('admin.dashboard'))
            ->assertOk()
            ->assertDontSee('Backup Database', false);
    }
}
