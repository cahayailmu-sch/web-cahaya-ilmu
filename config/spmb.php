<?php

/*
|--------------------------------------------------------------------------
| SPMB Two-Stage Registration
|--------------------------------------------------------------------------
|
| Schema for the stage-two "Formulir Lengkap" (biodata + Formulir Wawancara
| Orang Tua/Wali) and the "Surat Kesanggupan Biaya" agreement. The public
| form, server validation, admin detail page, and printable PDF all read
| from this file, so adding or renaming a field only needs to happen here.
|
| Field types: text, tel, number, date, textarea, select, radio.
| The token "{sekolah}" inside labels is replaced with the level name
| (e.g. "SD Islam Cahaya Ilmu").
|
*/

$yaTidak = ['Ya' => 'Ya', 'Tidak' => 'Tidak'];
$adaTidak = ['Ada' => 'Ada', 'Tidak Ada' => 'Tidak Ada'];
$pendidikan = [
    'Tidak Sekolah' => 'Tidak Sekolah', 'SD/Sederajat' => 'SD/Sederajat', 'SMP/Sederajat' => 'SMP/Sederajat',
    'SMA/Sederajat' => 'SMA/Sederajat', 'D1/D2/D3' => 'D1/D2/D3', 'S1/D4' => 'S1/D4', 'S2' => 'S2', 'S3' => 'S3',
];
$penghasilan = [
    '< Rp 2.000.000' => '< Rp 2.000.000', 'Rp 2.000.000 - Rp 5.000.000' => 'Rp 2.000.000 - Rp 5.000.000',
    'Rp 5.000.000 - Rp 10.000.000' => 'Rp 5.000.000 - Rp 10.000.000', '> Rp 10.000.000' => '> Rp 10.000.000',
    'Tidak Berpenghasilan' => 'Tidak Berpenghasilan',
];

$parentFields = fn (string $prefix, string $who, bool $nameRequired, bool $phoneRequired) => [
    ['key' => "{$prefix}_nama", 'label' => "Nama Lengkap {$who}", 'type' => 'text', 'required' => $nameRequired],
    ['key' => "{$prefix}_nik", 'label' => "NIK {$who}", 'type' => 'text', 'required' => false, 'max' => 20],
    ['key' => "{$prefix}_tempat_lahir", 'label' => 'Tempat Lahir', 'type' => 'text', 'required' => false],
    ['key' => "{$prefix}_tanggal_lahir", 'label' => 'Tanggal Lahir', 'type' => 'date', 'required' => false],
    ['key' => "{$prefix}_pendidikan", 'label' => 'Pendidikan Terakhir', 'type' => 'select', 'options' => [], 'required' => false],
    ['key' => "{$prefix}_pekerjaan", 'label' => 'Pekerjaan', 'type' => 'text', 'required' => false],
    ['key' => "{$prefix}_penghasilan", 'label' => 'Penghasilan per Bulan', 'type' => 'select', 'options' => [], 'required' => false],
    ['key' => "{$prefix}_hp", 'label' => 'No. HP / WhatsApp', 'type' => 'tel', 'required' => $phoneRequired, 'max' => 20],
];

$withOptions = function (array $fields) use ($pendidikan, $penghasilan): array {
    return array_map(function (array $field) use ($pendidikan, $penghasilan): array {
        if (str_ends_with($field['key'], '_pendidikan')) {
            $field['options'] = $pendidikan;
        }
        if (str_ends_with($field['key'], '_penghasilan')) {
            $field['options'] = $penghasilan;
        }

        return $field;
    }, $fields);
};

return [

    /*
    | Registration statuses: key => [label, description].
    */
    'statuses' => [
        'pending' => 'Menunggu Verifikasi',
        'verified' => 'Terverifikasi',
        'completed' => 'Formulir Lengkap',
        'accepted' => 'Diterima',
        'rejected' => 'Ditolak',
    ],

    /*
    | Education fee options shown on the "Biaya Pendidikan" section and the
    | Surat Kesanggupan Biaya. SD and SMA follow their official documents;
    | adjust the other levels here when their fee tables are finalised.
    */
    'fees' => [
        'SD' => [
            'uang_masuk' => [1 => 10000000, 2 => 11000000, 3 => 12000000],
            'spp' => [1 => 550000, 2 => 650000, 3 => 750000],
            'konsumsi' => 170000,
        ],
        'TK' => [
            'uang_masuk' => [1 => 8000000],
            'spp' => [1 => 550000],
            'konsumsi' => 150000,
        ],
        'SMP' => [
            'uang_masuk' => [1 => 10000000, 2 => 11000000, 3 => 12000000],
            'spp' => [1 => 550000, 2 => 650000, 3 => 750000],
            'konsumsi' => 170000,
        ],
        'SMA' => [
            'uang_masuk' => [1 => 7500000, 2 => 8500000, 3 => 9500000],
            'spp' => [1 => 550000, 2 => 650000, 3 => 750000],
            'konsumsi' => 200000,
        ],
    ],

    /*
    | Stage-two "Formulir Lengkap" sections (stored in full_form_data).
    */
    'full_form' => [
        [
            'key' => 'siswa',
            'title' => 'A. Data Lengkap Calon Peserta Didik',
            'fields' => [
                ['key' => 'nama_panggilan', 'label' => 'Nama Panggilan', 'type' => 'text', 'required' => false],
                ['key' => 'nik', 'label' => 'NIK Calon Peserta Didik (sesuai KK)', 'type' => 'text', 'required' => true, 'max' => 20],
                ['key' => 'nisn', 'label' => 'NISN (jika ada)', 'type' => 'text', 'required' => false, 'max' => 20],
                ['key' => 'no_kk', 'label' => 'Nomor Kartu Keluarga', 'type' => 'text', 'required' => false, 'max' => 20],
                ['key' => 'agama', 'label' => 'Agama', 'type' => 'select', 'options' => ['Islam' => 'Islam'], 'required' => false],
                ['key' => 'anak_ke', 'label' => 'Anak ke-', 'type' => 'number', 'required' => false],
                ['key' => 'jumlah_saudara', 'label' => 'Jumlah Saudara Kandung', 'type' => 'number', 'required' => false],
                ['key' => 'tinggi_badan', 'label' => 'Tinggi Badan (cm)', 'type' => 'number', 'required' => false],
                ['key' => 'berat_badan', 'label' => 'Berat Badan (kg)', 'type' => 'number', 'required' => false],
                ['key' => 'golongan_darah', 'label' => 'Golongan Darah', 'type' => 'select', 'options' => ['A' => 'A', 'B' => 'B', 'AB' => 'AB', 'O' => 'O', 'Belum Tahu' => 'Belum Tahu'], 'required' => false],
                ['key' => 'riwayat_sekolah', 'label' => 'Di mana saja putra/putri Bapak/Ibu bersekolah sebelumnya?', 'type' => 'textarea', 'required' => false],
            ],
        ],
        [
            'key' => 'alamat',
            'title' => 'B. Alamat Tempat Tinggal',
            'fields' => [
                ['key' => 'alamat_lengkap', 'label' => 'Alamat Lengkap (Jalan, No. Rumah, Komplek/Perumahan)', 'type' => 'textarea', 'required' => false],
                ['key' => 'rt_rw', 'label' => 'RT / RW', 'type' => 'text', 'required' => false, 'max' => 20],
                ['key' => 'kelurahan', 'label' => 'Kelurahan', 'type' => 'text', 'required' => false],
                ['key' => 'kecamatan', 'label' => 'Kecamatan', 'type' => 'text', 'required' => false],
                ['key' => 'kota', 'label' => 'Kota / Kabupaten', 'type' => 'text', 'required' => false],
                ['key' => 'kode_pos', 'label' => 'Kode Pos', 'type' => 'text', 'required' => false, 'max' => 10],
                ['key' => 'tinggal_bersama', 'label' => 'Tinggal Bersama', 'type' => 'select', 'options' => ['Orang Tua' => 'Orang Tua', 'Ayah' => 'Ayah', 'Ibu' => 'Ibu', 'Wali' => 'Wali', 'Lainnya' => 'Lainnya'], 'required' => false],
                ['key' => 'transportasi', 'label' => 'Moda Transportasi ke Sekolah', 'type' => 'text', 'required' => false],
            ],
        ],
        [
            'key' => 'ayah',
            'title' => 'C. Data Ayah Kandung',
            'fields' => $withOptions($parentFields('ayah', 'Ayah', true, true)),
        ],
        [
            'key' => 'ibu',
            'title' => 'D. Data Ibu Kandung',
            'fields' => $withOptions($parentFields('ibu', 'Ibu', true, false)),
        ],
        [
            'key' => 'wali',
            'title' => 'E. Data Wali (diisi jika tinggal bersama wali)',
            'fields' => [
                ['key' => 'wali_nama', 'label' => 'Nama Lengkap Wali', 'type' => 'text', 'required' => false],
                ['key' => 'wali_hubungan', 'label' => 'Hubungan dengan Calon Peserta Didik', 'type' => 'text', 'required' => false],
                ['key' => 'wali_pekerjaan', 'label' => 'Pekerjaan Wali', 'type' => 'text', 'required' => false],
                ['key' => 'wali_hp', 'label' => 'No. HP / WhatsApp Wali', 'type' => 'tel', 'required' => false, 'max' => 20],
            ],
        ],
        [
            'key' => 'motivasi',
            'title' => 'F. Motivasi Memilih {sekolah}',
            'fields' => [
                ['key' => 'kriteria_sekolah', 'label' => 'Apa saja kriteria sekolah idaman yang Bapak/Ibu dambakan?', 'type' => 'textarea', 'required' => false],
                ['key' => 'sumber_informasi', 'label' => 'Dari mana Bapak/Ibu mendapatkan informasi tentang {sekolah}?', 'type' => 'textarea', 'required' => false],
                ['key' => 'alasan_memilih', 'label' => '{sekolah} masih memiliki banyak kekurangan. Apa yang mendorong Bapak/Ibu untuk menyekolahkan Ananda di {sekolah}?', 'type' => 'textarea', 'required' => false],
                ['key' => 'pandangan_paradigma', 'label' => 'Apa pandangan Bapak/Ibu terhadap perubahan paradigma (model/kerangka berpikir) pendidikan secara umum saat ini?', 'type' => 'textarea', 'required' => false],
                ['key' => 'harapan_target', 'label' => 'Apakah harapan dan target ke depan Bapak/Ibu tentang {sekolah}?', 'type' => 'textarea', 'required' => false],
                ['key' => 'kesiapan_program', 'label' => 'Apakah Bapak/Ibu siap untuk mengikuti program yang diadakan sekolah, seperti: Parenting, kajian, dan sebagainya?', 'type' => 'textarea', 'required' => false],
            ],
        ],
        [
            'key' => 'pola_asuh',
            'title' => 'G. Pola Asuh Orang Tua/Wali di Rumah dan Riwayat Perilaku Anak',
            'fields' => [
                ['key' => 'peraturan_rumah', 'label' => 'Peraturan-peraturan apa saja yang diberlakukan oleh Bapak/Ibu di rumah kepada Ananda?', 'type' => 'textarea', 'required' => false],
                ['key' => 'pernah_melanggar', 'label' => 'Pernahkah Ananda berperilaku yang menurut Bapak/Ibu tidak sesuai dengan peraturan yang diberlakukan di rumah?', 'type' => 'radio', 'options' => $yaTidak, 'required' => false],
                ['key' => 'pernah_melanggar_detail', 'label' => 'Jika ya, mohon sebutkan perilaku apa saja', 'type' => 'textarea', 'required' => false],
                ['key' => 'tindakan_saat_salah', 'label' => 'Apakah yang dilakukan oleh Bapak/Ibu ketika Ananda melakukan kesalahan?', 'type' => 'textarea', 'required' => false],
                ['key' => 'hukuman_fisik', 'label' => 'Apakah Bapak/Ibu pernah menghukum Ananda dengan hukuman fisik ketika melakukan kesalahan?', 'type' => 'radio', 'options' => $yaTidak, 'required' => false],
                ['key' => 'hukuman_fisik_detail', 'label' => 'Jika ya, mohon sebutkan bentuk hukumannya', 'type' => 'textarea', 'required' => false],
                ['key' => 'pendapat_sanksi', 'label' => 'Bagaimana pendapat Bapak/Ibu jika di kemudian hari Ananda melakukan pelanggaran terhadap peraturan {sekolah} sehingga mendapat sanksi (baik yang ringan, berat, dan khususnya yang berat)?', 'type' => 'textarea', 'required' => false],
                ['key' => 'mengambil_uang', 'label' => 'Pernahkah Ananda mengambil uang tanpa izin?', 'type' => 'radio', 'options' => ['Pernah' => 'Pernah', 'Tidak Pernah' => 'Tidak Pernah'], 'required' => false],
                ['key' => 'mengambil_uang_detail', 'label' => 'Jika pernah, untuk keperluan apa? Dan apa yang Bapak/Ibu lakukan kepada Ananda?', 'type' => 'textarea', 'required' => false],
                ['key' => 'game_musik', 'label' => 'Umumnya anak-anak memiliki hobi game online dan mendengarkan musik. Game dan musik apa saja yang digemari oleh Ananda?', 'type' => 'textarea', 'required' => false],
                ['key' => 'suka_tv', 'label' => 'Apakah Ananda suka menonton TV?', 'type' => 'radio', 'options' => $yaTidak, 'required' => false],
                ['key' => 'acara_tv', 'label' => 'Apa acara kegemarannya?', 'type' => 'text', 'required' => false],
                ['key' => 'tidur_ditemani', 'label' => 'Apakah tidur Ananda masih ditemani?', 'type' => 'radio', 'options' => $yaTidak, 'required' => false],
                ['key' => 'tidur_ditemani_alasan', 'label' => 'Apa alasannya?', 'type' => 'text', 'required' => false],
                ['key' => 'mandiri_makan', 'label' => 'Apakah Ananda sudah mandiri saat makan?', 'type' => 'radio', 'options' => $yaTidak, 'required' => false],
                ['key' => 'alergi', 'label' => 'Apakah Ananda memiliki alergi?', 'type' => 'radio', 'options' => $yaTidak, 'required' => false],
                ['key' => 'alergi_detail', 'label' => 'Jika ya, mohon sebutkan', 'type' => 'text', 'required' => false],
                ['key' => 'pengaruh_terbesar', 'label' => 'Apa yang paling bisa memberi pengaruh kepada Ananda di rumah?', 'type' => 'radio', 'options' => ['Gadget (HP, Laptop, dll)' => 'Gadget (HP, Laptop, dll)', 'TV' => 'TV', 'Lainnya' => 'Lainnya'], 'required' => false],
                ['key' => 'pengaruh_terbesar_lainnya', 'label' => 'Jika lainnya, sebutkan', 'type' => 'text', 'required' => false],
                ['key' => 'sosok_berpengaruh', 'label' => 'Siapa yang paling bisa dan efektif dalam memberi pengaruh kepada Ananda di rumah?', 'type' => 'radio', 'options' => ['Ayah' => 'Ayah', 'Ibu' => 'Ibu', 'Kakek' => 'Kakek', 'Nenek' => 'Nenek', 'Lainnya' => 'Lainnya'], 'required' => false],
                ['key' => 'sosok_berpengaruh_lainnya', 'label' => 'Jika lainnya, sebutkan', 'type' => 'text', 'required' => false],
                ['key' => 'teman_dekat', 'label' => 'Siapa saja teman main terdekat Ananda? Dan mengapa Ananda sangat dekat dengannya?', 'type' => 'textarea', 'required' => false],
                ['key' => 'kegemaran', 'label' => 'Kegemaran Ananda yang paling dominan saat di rumah adalah?', 'type' => 'textarea', 'required' => false],
                ['key' => 'kelebihan_akademik', 'label' => 'Kelebihan Ananda — Akademik', 'type' => 'textarea', 'required' => false],
                ['key' => 'kelebihan_sikap', 'label' => 'Kelebihan Ananda — Sikap', 'type' => 'textarea', 'required' => false],
                ['key' => 'kelebihan_kreativitas', 'label' => 'Kelebihan Ananda — Kreativitas', 'type' => 'textarea', 'required' => false],
                ['key' => 'perilaku_diperbaiki', 'label' => 'Adakah perilaku Ananda yang Bapak/Ibu harapkan agar dapat menjadi lebih baik setelah belajar di {sekolah}?', 'type' => 'radio', 'options' => $adaTidak, 'required' => false],
                ['key' => 'perilaku_diperbaiki_detail', 'label' => 'Mohon jelaskan bila ada', 'type' => 'textarea', 'required' => false],
            ],
        ],
        [
            'key' => 'hubungan',
            'title' => 'H. Hubungan Orang Tua',
            'fields' => [
                ['key' => 'hubungan_ortu', 'label' => 'Bagaimana hubungan antara ayah dan ibu?', 'type' => 'radio', 'options' => ['Bercerai' => 'Bercerai', 'Rukun/Serumah' => 'Rukun/Serumah', 'Rukun namun tidak serumah/Commuter (faktor pekerjaan)' => 'Rukun namun tidak serumah/Commuter (faktor pekerjaan)'], 'required' => false],
                ['key' => 'penanggung_jawab', 'label' => 'Siapakah yang bertanggung jawab atas Ananda? (pembiayaan, perizinan, kebijakan wali, dan lainnya)', 'type' => 'radio', 'options' => ['Ayah' => 'Ayah', 'Ibu' => 'Ibu', 'Lainnya' => 'Lainnya'], 'required' => false],
                ['key' => 'penanggung_jawab_lainnya', 'label' => 'Jika lainnya, sebutkan', 'type' => 'text', 'required' => false],
            ],
        ],
        [
            'key' => 'kesehatan',
            'title' => 'J. Riwayat Penyakit Ananda',
            'fields' => [
                ['key' => 'riwayat_penyakit', 'label' => 'Apakah Ananda memiliki riwayat penyakit yang memerlukan kontrol rutin?', 'type' => 'radio', 'options' => $adaTidak, 'required' => false],
                ['key' => 'jenis_penyakit', 'label' => 'Jika ada, sebutkan jenis-jenis penyakitnya', 'type' => 'textarea', 'required' => false],
                ['key' => 'frekuensi_kontrol', 'label' => 'Sebutkan frekuensi kontrolnya', 'type' => 'textarea', 'required' => false],
            ],
        ],
    ],

    /*
    | Persons allowed to pick up the student (section I, max 3 rows).
    */
    'pickup_rows' => 3,
    'pickup_columns' => [
        'nama' => 'Nama',
        'usia' => 'Usia',
        'hubungan' => 'Hubungan',
        'no_identitas' => 'No. Identitas',
        'telp' => 'Telp/HP',
    ],

    /*
    | Biaya Pendidikan & Surat Kesanggupan Biaya (stored in fee_agreement_data).
    */
    'fee_form' => [
        [
            'key' => 'biaya',
            'title' => 'K. Biaya Pendidikan',
            'fields' => [
                ['key' => 'penilaian_biaya', 'label' => 'Dari perincian biaya pendidikan di atas, menurut Bapak/Ibu, biaya masuk {sekolah} termasuk', 'type' => 'radio', 'options' => ['Murah' => 'Murah', 'Sedang' => 'Sedang', 'Mahal' => 'Mahal'], 'required' => false],
                ['key' => 'alasan_penilaian', 'label' => 'Apa alasannya?', 'type' => 'textarea', 'required' => false],
                ['key' => 'pilihan_uang_masuk', 'label' => 'Pilihan biaya uang masuk sekolah', 'type' => 'radio', 'options' => ['1' => 'Pilihan 1 (satu)', '2' => 'Pilihan 2 (dua)', '3' => 'Pilihan 3 (tiga)'], 'required' => true],
                ['key' => 'cara_bayar', 'label' => 'Dengan cara', 'type' => 'radio', 'options' => ['Cash' => 'Cash', 'Dicicil' => 'Dicicil'], 'required' => true],
                [
                    'key' => 'cicilan_kali',
                    'label' => 'Jika dicicil, berapa kali mencicil?',
                    'type' => 'select',
                    'options' => [
                        '2' => '2 kali',
                        '3' => '3 kali',
                        '4' => '4 kali',
                        '5' => '5 kali',
                        '6' => '6 kali',
                        '7' => '7 kali',
                        '8' => '8 kali',
                        '9' => '9 kali',
                        '10' => '10 kali',
                        '11' => '11 kali',
                        '12' => '12 kali',
                    ],
                    'required' => false,
                ],
                ['key' => 'pilihan_spp', 'label' => 'Pilihan biaya iuran SPP', 'type' => 'radio', 'options' => ['1' => 'Pilihan 1 (satu)', '2' => 'Pilihan 2 (dua)', '3' => 'Pilihan 3 (tiga)'], 'required' => true],
            ],
        ],
        [
            'key' => 'penanggung_jawab_biaya',
            'title' => 'L. Surat Pernyataan Kesanggupan Membayar Biaya Pendidikan',
            'fields' => [
                ['key' => 'pj_nama', 'label' => 'Nama Penanggung Jawab Biaya', 'type' => 'text', 'required' => false],
                ['key' => 'pj_pekerjaan', 'label' => 'Pekerjaan', 'type' => 'text', 'required' => false],
                ['key' => 'pj_alamat', 'label' => 'Alamat', 'type' => 'textarea', 'required' => false],
                ['key' => 'pj_telp', 'label' => 'Telp.', 'type' => 'tel', 'required' => false, 'max' => 20],
                ['key' => 'pj_hp', 'label' => 'HP / WA', 'type' => 'tel', 'required' => false, 'max' => 20],
            ],
        ],
        [
            'key' => 'tanda_tangan',
            'title' => 'M. Tanda Tangan Orang Tua',
            'fields' => [
                ['key' => 'ttd_ayah', 'label' => 'Nama Jelas Ayahanda (penandatangan)', 'type' => 'text', 'required' => false],
                ['key' => 'ttd_ibu', 'label' => 'Nama Jelas Ibunda (penandatangan)', 'type' => 'text', 'required' => false],
                ['key' => 'kota_ttd', 'label' => 'Kota Penandatanganan', 'type' => 'text', 'required' => false],
            ],
        ],
    ],

    /*
    | KB-TK Islam Cahaya Ilmu (Tahun Ajaran 2027/2028) registration details.
    | This block is the single source of truth for the jenjang TK page, the
    | SPMB page, the stage-one form, and the printable documents.
    */
    'tk' => [
        'name' => 'KB-TK Islam Cahaya Ilmu',
        'school_year' => '2027/2028',
        'curriculum' => 'Kurikulum Merdeka & Deep Learning',
        'whatsapp' => '628115917772',
        'whatsapp_display' => '0811-5917-772',
        'social_handle' => '@tkislam_cahayailmu',
        'social_links' => [
            'instagram' => 'https://www.instagram.com/tkislam_cahayailmu',
            'tiktok' => 'https://www.tiktok.com/@tkislam_cahayailmu',
            'youtube' => 'https://www.youtube.com/@tkislam_cahayailmu',
        ],
        'registration' => [
            'start_label' => 'Sabtu, 31 Oktober 2026',
            'hours' => '08.00–14.00 WITA',
            'event' => 'Khusus Event Muslim Vibes 31 Oktober – 1 November 2026 dibuka sampai pukul 16.00 WITA.',
            'note' => 'Pendaftaran dibuka sampai kuota terpenuhi.',
        ],
        'fee' => 250000,
        'bank' => 'Bank Syariah Indonesia (BSI)',
        'account_number' => '3115917772',
        'account_name' => 'TK Islam Cahaya Ilmu',
        'fee_detail' => [
            'registration_fee' => 250000,
            'biaya_masuk' => [
                'uang_pangkal' => 4500000,
                'perlengkapan' => 1600000,
                'perlengkapan_items' => [
                    'Seragam (4 Stel)',
                    'Tas Sekolah',
                    'Pin Nama',
                    'Buku Rapot',
                    'Buku Panduan',
                    'Buku Jurnal 7 KAIH',
                ],
                'pertahun' => 1900000,
                'total' => 8000000,
            ],
            'biaya_bulanan' => [
                'spp' => 550000,
                'konsumsi' => 150000,
                'total' => 700000,
            ],
            'note' => 'Total biaya masuk belum termasuk SPP dan konsumsi.',
            'payment' => [
                'transfer' => 'Transfer (Rek. BSI 3115917772 an. TK Islam Cahaya Ilmu)',
                'installment_month' => 'Juni',
            ],
        ],
        'groups' => [
            'KB' => [
                'label' => 'KB (Kelompok Bermain)',
                'min_age_months' => 42,
                'min_age_label' => '3 tahun 6 bulan',
                'cutoff' => '2023-12-31',
                'cutoff_label' => '31 Desember 2023',
            ],
            'TK A' => [
                'label' => 'TK A',
                'min_age_months' => 54,
                'min_age_label' => '4 tahun 6 bulan',
                'cutoff' => '2022-12-31',
                'cutoff_label' => '31 Desember 2022',
            ],
            'TK B' => [
                'label' => 'TK B',
                'min_age_months' => 66,
                'min_age_label' => '5 tahun 6 bulan',
                'cutoff' => '2021-12-31',
                'cutoff_label' => '31 Desember 2021',
            ],
        ],
        'documents' => [
            'Bukti transfer biaya pendaftaran Rp 250.000 ke BSI 3115917772 a.n. TK Islam Cahaya Ilmu.',
            'Foto ananda memakai pakaian bebas muslim/muslimah (background bebas).',
            'Foto / scan / PDF Akta Kelahiran ananda.',
        ],
        'steps' => [
            'Mendaftar online mulai Sabtu, 31 Oktober 2026 pukul 08.00–14.00 WITA (khusus Event Muslim Vibes 31 Oktober – 1 November 2026 sampai pukul 16.00 WITA).',
            'Memastikan usia ananda sesuai ketentuan: KB minimal 3 tahun 6 bulan, TK A minimal 4 tahun 6 bulan, dan TK B minimal 5 tahun 6 bulan pada bulan Juni 2027.',
            'Menghubungi WhatsApp Admin KB-TK melalui 0811-5917-772.',
            'Menyiapkan bukti transfer biaya pendaftaran Rp 250.000 ke BSI 3115917772 a.n. TK Islam Cahaya Ilmu.',
            'Menyiapkan foto ananda memakai pakaian bebas muslim/muslimah (background bebas).',
            'Menyiapkan foto / scan / PDF Akta Kelahiran ananda.',
            'Menerima link pendaftaran. Khusus pendaftar di tenant Event Muslim Vibes dapat langsung memindai barcode tanpa menghubungi WA Admin.',
            'Bergabung dengan grup WhatsApp SPMB di akhir pendaftaran online untuk informasi selanjutnya.',
        ],
        'promos' => [
            'muslim_vibes' => [
                'label' => 'Promo Spesial Event Muslim Vibes — Gratis biaya pendaftaran (diskon Rp 250.000)',
                'summary' => 'Gratis biaya pendaftaran (diskon Rp 250.000) khusus 10 pendaftar pertama di Tenant CI pada Sabtu, 31 Oktober 2026.',
                'discount_amount' => 250000,
                'waives_registration_fee' => true,
                'limit' => 10,
            ],
        ],
    ],

    /*
    | Statements the parent must agree to (from the official documents).
    */
    'agreements' => [
        'setuju_biaya' => 'Saya bertanggung jawab dan siap untuk menanggung biaya pendidikan calon peserta didik tersebut, yang meliputi uang daftar ulang/pangkal dan SPP setiap bulan. Apabila di kemudian hari Ananda mengundurkan diri atau pindah sekolah maka biaya yang sudah dibayarkan tidak dapat dikembalikan.',
        'setuju_keputusan' => 'Saya akan menerima dengan baik dan lapang dada terhadap segala keputusan Panitia Penerimaan Siswa Baru apabila dinyatakan diterima atau tidak diterimanya Ananda sebagai siswa/siswi {sekolah}.',
        'setuju_parenting' => 'Saya akan mendukung dan menghadiri kegiatan-kegiatan pembinaan orang tua dan seminar parenting Tarbiyatul Aulad serta kajian umum yang diadakan oleh {sekolah} dan Yayasan Ath-Thoifah Al-Manshuroh dengan baik dan bersungguh-sungguh.',
        'setuju_data_benar' => 'Saya menyatakan bahwa seluruh data yang saya isikan pada formulir ini adalah benar dan dapat dipertanggungjawabkan.',
    ],

];
