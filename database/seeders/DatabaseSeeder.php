<?php

namespace Database\Seeders;

use App\Models\SchoolSetting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(AdminUserSeeder::class);
        $this->call(PpdbQuotaSeeder::class);
        $this->call(PpdbTkGroupQuotaSeeder::class);
        $this->call(ReferralCodeSeeder::class);

        $settings = [
            'foundation_name' => 'Yayasan Ath-Thoifah Al-Manshuroh Balikpapan',
            'status_ppdb' => 'Buka',
            'tahun_ajaran' => '2027/2028',
            'wa_admin_tk' => '628115917772',
            'wa_admin_sd' => '6285796135987',
            'wa_admin_smp' => '6282254424312',
            'wa_admin_sma' => '6285394999283',
            'biaya_tk' => '250000',
            'bank_tk' => 'Bank Syariah Indonesia (BSI)',
            'rek_tk' => '3115917772',
            'rek_nama_tk' => 'TK Islam Cahaya Ilmu',
            'biaya_sma' => '300000',
            'bank_sma' => 'Bank Syariah Indonesia (BSI)',
            'rek_sma' => '7127822446',
            'rek_nama_sma' => 'SMA Islam Cahaya Ilmu',
            'instagram_username_tk' => 'tkislam_cahayailmu',
            'instagram_url_tk' => 'https://www.instagram.com/tkislam_cahayailmu',
            'instagram_username_sd' => 'sdislam_cahayailmu',
            'instagram_url_sd' => 'https://www.instagram.com/sdislam_cahayailmu',
            'instagram_username_smp' => 'smpislam_cahayailmu',
            'instagram_url_smp' => 'https://www.instagram.com/smpislam_cahayailmu',
            'instagram_username_sma' => 'smaislam_cahayailmu',
            'instagram_url_sma' => 'https://www.instagram.com/smaislam_cahayailmu',
            'youtube_tk' => 'https://www.youtube.com/@tkislam_cahayailmu',
            'youtube_sd' => 'https://www.youtube.com/@sdislamcahayailmubalikpapan',
            'youtube_smp' => 'https://www.youtube.com/@SMPIslamCahayaIlmu',
            'youtube_sma' => 'https://www.youtube.com/@SMAIslamCahayaIlmu',
            'alamat_sekolah' => "Jl. Syarifuddin Yoes RT. 41 komp. Masjid Al-Imam An-Nasa'i Kel. Gunung bahagia Kec. Balikpapan Selatan 76114",
            'jumlah_siswa' => '850',
            'jumlah_guru' => '65',
        ];

        foreach ($settings as $key => $value) {
            SchoolSetting::updateOrCreate(
                ['key' => $key],
                ['value' => $value],
            );
        }
    }
}
