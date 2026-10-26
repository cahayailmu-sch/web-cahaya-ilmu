<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Dokumen SPMB — {{ $registration->registration_number }} — {{ $registration->full_name }}</title>
  <style>
    @page {
      size: A4 portrait;
      margin: 15mm 15mm 15mm 15mm;
    }
    * {
      box-sizing: border-box;
      -webkit-print-color-adjust: exact;
      print-color-adjust: exact;
    }
    body {
      font-family: Arial, Helvetica, sans-serif;
      font-size: 10pt;
      line-height: 1.35;
      color: #111827;
      margin: 0;
      padding: 0;
      background: {{ $isPdf ? '#ffffff' : '#f8fafc' }};
    }
    .no-print {
      display: {{ $isPdf ? 'none' : 'block' }};
    }
    @media print {
      .no-print {
        display: none !important;
      }
      body {
        background: #ffffff !important;
      }
      .page-container {
        padding: 0 !important;
        box-shadow: none !important;
        border: none !important;
        width: 100% !important;
        max-width: 100% !important;
      }
    }
    .top-bar {
      background: #0f172a;
      color: #ffffff;
      padding: 12px 20px;
      margin-bottom: 24px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);
    }
    .top-bar-buttons {
      display: flex;
      gap: 10px;
    }
    .btn {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      padding: 8px 16px;
      border-radius: 8px;
      font-size: 9pt;
      font-weight: bold;
      text-decoration: none;
      cursor: pointer;
      border: none;
    }
    .btn-primary {
      background: #dc2626;
      color: #ffffff;
    }
    .btn-primary:hover {
      background: #b91c1c;
    }
    .btn-secondary {
      background: #334155;
      color: #ffffff;
    }
    .page-container {
      width: 100%;
      max-width: 210mm;
      margin: 0 auto;
      background: #ffffff;
      padding: {{ $isPdf ? '0' : '20mm' }};
      box-shadow: {{ $isPdf ? 'none' : '0 10px 25px -5px rgba(0,0,0,0.1)' }};
    }
    .page-break {
      page-break-before: always;
      break-before: page;
      margin-top: 20px;
      padding-top: 10px;
    }
    /* Kop Surat */
    .kop-table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 8px;
    }
    .kop-logo {
      width: 75px;
      vertical-align: middle;
      text-align: center;
    }
    .kop-logo img {
      max-width: 70px;
      max-height: 70px;
    }
    .kop-text {
      text-align: center;
      vertical-align: middle;
      padding: 0 10px;
    }
    .kop-foundation {
      font-size: 11pt;
      font-weight: bold;
      letter-spacing: 0.5px;
      color: #1e293b;
      margin: 0;
      text-transform: uppercase;
    }
    .kop-school {
      font-size: 14pt;
      font-weight: 800;
      color: #991b1b;
      margin: 2px 0;
      letter-spacing: 1px;
      text-transform: uppercase;
    }
    .kop-meta {
      font-size: 8pt;
      color: #475569;
      margin: 0;
    }
    .kop-line {
      border-top: 2px solid #0f172a;
      border-bottom: 1px solid #0f172a;
      height: 3px;
      margin: 4px 0 12px 0;
    }
    /* Title */
    .doc-title-box {
      text-align: center;
      margin-bottom: 14px;
    }
    .doc-title {
      font-size: 12pt;
      font-weight: 800;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      color: #0f172a;
      margin: 0;
      text-decoration: underline;
    }
    .doc-subtitle {
      font-size: 8.5pt;
      color: #475569;
      margin-top: 3px;
      font-weight: 600;
    }
    /* Pas Foto 3x4 (KB-TK) */
    .pas-foto-table {
      width: 100%;
      border-collapse: collapse;
      margin: 0 0 8px 0;
    }
    .pas-foto-table-cell {
      width: 3.4cm;
      text-align: center;
      vertical-align: top;
      padding-left: 8px;
    }
    .pas-foto-box {
      width: 3cm;
      height: 4cm;
      border: 1.5px solid #334155;
      background: #f8fafc;
      overflow: hidden;
      margin: 0 auto;
    }
    .pas-foto-box img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      display: block;
    }
    .pas-foto-empty {
      display: block;
      padding-top: 1.55cm;
      font-size: 7.5pt;
      font-weight: bold;
      color: #94a3b8;
      text-align: center;
      line-height: 1.3;
    }
    .pas-foto-caption {
      margin: 2px 0 0;
      font-size: 7pt;
      font-weight: bold;
      color: #475569;
    }
    /* Section & Tables */
    .section-heading {
      background: #f1f5f9;
      padding: 4px 8px;
      font-size: 9.5pt;
      font-weight: bold;
      color: #0f172a;
      border-left: 3px solid #991b1b;
      margin: 10px 0 6px 0;
      text-transform: uppercase;
    }
    table.data-table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 8px;
      font-size: 8.5pt;
    }
    table.data-table td {
      padding: 3px 6px;
      vertical-align: top;
    }
    table.data-table td.label-col {
      width: 28%;
      color: #475569;
      font-weight: 600;
    }
    table.data-table td.sep-col {
      width: 2%;
      text-align: center;
    }
    table.data-table td.val-col {
      width: 70%;
      color: #0f172a;
      font-weight: 500;
    }
    table.compact-table {
      font-size: 7.5pt;
      margin-bottom: 6px;
    }
    table.compact-table td {
      padding: 1.5px 5px;
      line-height: 1.25;
    }
    table.compact-table td.label-col {
      width: 34%;
    }
    table.compact-table td.val-col {
      width: 64%;
    }
    /* Grid 2 Column */
    .columns-2 {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 6px;
    }
    .columns-2 td {
      width: 50%;
      vertical-align: top;
      padding: 0 4px;
    }
    /* Bordered Table */
    table.bordered-table {
      width: 100%;
      border-collapse: collapse;
      margin: 6px 0 10px 0;
      font-size: 8pt;
    }
    table.bordered-table th, table.bordered-table td {
      border: 1px solid #cbd5e1;
      padding: 4px 6px;
      text-align: left;
    }
    table.bordered-table th {
      background: #f8fafc;
      font-weight: bold;
      color: #334155;
    }
    /* QA Block */
    .qa-item {
      margin-bottom: 6px;
      font-size: 8.5pt;
    }
    .qa-q {
      font-weight: bold;
      color: #334155;
    }
    .qa-a {
      margin-left: 12px;
      color: #0f172a;
      margin-top: 1px;
    }
    /* Agreements Box */
    .agreement-list {
      margin: 8px 0;
      padding-left: 20px;
      font-size: 8.5pt;
      line-height: 1.4;
      color: #1e293b;
    }
    .agreement-list li {
      margin-bottom: 5px;
      text-align: justify;
    }
    /* Signatures */
    .sig-table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 16px;
      font-size: 8.5pt;
      text-align: center;
    }
    .sig-table td {
      vertical-align: top;
      width: 33.33%;
      padding: 0 10px;
    }
    .sig-space {
      height: 55px;
    }
    .sig-name {
      font-weight: bold;
      text-decoration: underline;
      color: #0f172a;
    }
    .sig-title {
      font-size: 8pt;
      color: #475569;
      margin-top: 2px;
    }
  </style>
</head>
<body>

@if (! $isPdf)
  <div class="top-bar no-print">
    <div>
      <strong style="font-size: 11pt;">Dokumen Resmi SPMB</strong> &middot;
      <span>{{ $registration->registration_number }} — {{ $registration->full_name }}</span>
    </div>
    <div class="top-bar-buttons">
      @if ($backUrl)
        <a href="{{ $backUrl }}" class="btn btn-secondary">&larr; Kembali</a>
      @endif
      @if ($pdfUrl)
        <a href="{{ $pdfUrl }}" class="btn btn-secondary">Download PDF</a>
      @endif
      <button onclick="window.print()" class="btn btn-primary">
        Cetak Sekarang (Print A4)
      </button>
    </div>
  </div>
@endif

<div class="page-container">
  <!-- ================= HALAMAN 1: FORMULIR PENDAFTARAN LENGKAP ================= -->
  <table class="kop-table">
    <tr>
      @if ($logoSrc)
        <td class="kop-logo">
          <img src="{{ $logoSrc }}" alt="Logo">
        </td>
      @endif
      <td class="kop-text">
        <p class="kop-foundation">{{ $foundationName }}</p>
        <p class="kop-school">{{ strtoupper($registration->schoolName()) }}</p>
        <p class="kop-meta">{{ $schoolAddress }} &middot; Tahun Ajaran {{ $tahunAjaran }}</p>
      </td>
    </tr>
  </table>
  <div class="kop-line"></div>

  <div class="doc-title-box">
    <h1 class="doc-title">FORMULIR PENDAFTARAN PESERTA DIDIK BARU</h1>
    <p class="doc-subtitle">Nomor Pendaftaran: <strong>{{ $registration->registration_number }}</strong> &middot; Jenjang: <strong>{{ $registration->schoolName() }}</strong>@if (filled($registration->subLevelLabel())) &middot; Kelompok: <strong>{{ $registration->subLevelLabel() }}</strong>@endif ({{ $registration->genderLabel() }})</p>
  </div>

  @if ($registration->level === 'TK')
    @php
      $pasFotoPath = $registration->passportPhotoPath();
      $pasFotoSrc = null;

      if ($pasFotoPath !== null) {
          $pasFotoSrc = $isPdf
              ? $registration->passportPhotoAbsolutePath()
              : asset('storage/'.$pasFotoPath);
      }
    @endphp
    <table class="pas-foto-table">
      <tr>
        <td></td>
        <td class="pas-foto-table-cell">
          <div class="pas-foto-box">
            @if ($pasFotoSrc)
              <img src="{{ $pasFotoSrc }}" alt="Pas Foto 3x4 {{ $registration->full_name }}">
            @else
              <span class="pas-foto-empty">PAS FOTO<br>3 &times; 4</span>
            @endif
          </div>
          <p class="pas-foto-caption">Pas Foto 3 &times; 4</p>
        </td>
      </tr>
    </table>
  @endif

  @php
    $full = $registration->full_form_data ?? [];
    $fee = $registration->fee_agreement_data ?? [];
    $penjemput = $full['penjemput'] ?? [];
  @endphp

  <!-- A. Data Calon Siswa -->
  <div class="section-heading">A. Data Calon Peserta Didik</div>
  <table class="data-table">
    <tr>
      <td class="label-col">Nama Lengkap</td>
      <td class="sep-col">:</td>
      <td class="val-col"><strong>{{ $registration->full_name }}</strong> (Panggilan: {{ $registration->formValue('nama_panggilan', '-') }})</td>
    </tr>
    <tr>
      <td class="label-col">Jenis Kelamin / NIK / NISN</td>
      <td class="sep-col">:</td>
      <td class="val-col">{{ $registration->genderLabel() }} &middot; NIK: {{ $registration->formValue('nik', '-') }} &middot; NISN: {{ $registration->formValue('nisn', '-') }} &middot; No. KK: {{ $registration->formValue('no_kk', '-') }}</td>
    </tr>
    <tr>
      <td class="label-col">Tempat, Tanggal Lahir</td>
      <td class="sep-col">:</td>
      <td class="val-col">{{ $registration->birth_place ?: '-' }}, {{ $registration->birth_date?->translatedFormat('d F Y') }} (Usia: {{ $registration->age() }} tahun)</td>
    </tr>
    <tr>
      <td class="label-col">Agama / Anak Ke-</td>
      <td class="sep-col">:</td>
      <td class="val-col">{{ $registration->formValue('agama', 'Islam') }} &middot; Anak ke- {{ $registration->formValue('anak_ke', '-') }} dari {{ $registration->formValue('jumlah_saudara', '-') }} bersaudara</td>
    </tr>
    <tr>
      <td class="label-col">Gol. Darah / Fisik</td>
      <td class="sep-col">:</td>
      <td class="val-col">Gol: {{ $registration->formValue('golongan_darah', '-') }} &middot; TB: {{ $registration->formValue('tinggi_badan', '-') }} cm &middot; BB: {{ $registration->formValue('berat_badan', '-') }} kg</td>
    </tr>
    <tr>
      <td class="label-col">Asal Sekolah Sebelumnya</td>
      <td class="sep-col">:</td>
      <td class="val-col">{{ $registration->previous_school ?: $registration->formValue('riwayat_sekolah', '-') }}</td>
    </tr>
    <tr>
      <td class="label-col">No. WhatsApp / Status</td>
      <td class="sep-col">:</td>
      <td class="val-col">{{ $registration->whatsapp }} &middot; {{ $registration->statusLabel() }} &middot; Terdaftar {{ $registration->created_at?->translatedFormat('d F Y') }}</td>
    </tr>
    <tr>
      <td class="label-col">Bukti Pembayaran Tahap 1</td>
      <td class="sep-col">:</td>
      <td class="val-col">
        @if ($registration->paymentProofIsFree())
          <strong>GRATIS &mdash; {{ $registration->payment_proof }}</strong>
        @else
          {{ $registration->payment_proof ? 'Terlampir: '.basename((string) $registration->payment_proof) : '-' }}
        @endif
      </td>
    </tr>
    @if (filled($registration->referral_code))
      <tr>
        <td class="label-col">Kode Referral / Diskon</td>
        <td class="sep-col">:</td>
        <td class="val-col">
          <strong>{{ $registration->referral_code }}</strong>
          @if ($registration->discount_amount > 0)
            &middot; Diskon Rp {{ number_format($registration->discount_amount, 0, ',', '.') }}
          @endif
          @if (filled($registration->promoLabel()))
            &middot; {{ $registration->promoLabel() }}
          @endif
        </td>
      </tr>
    @endif
    @if (filled($registration->formValue('wali_nama')))
      <tr>
        <td class="label-col">Data Wali</td>
        <td class="sep-col">:</td>
        <td class="val-col">{{ $registration->formValue('wali_nama') }} ({{ $registration->formValue('wali_hubungan', '-') }}) &middot; Pekerjaan: {{ $registration->formValue('wali_pekerjaan', '-') }} &middot; HP: {{ $registration->formValue('wali_hp', '-') }}</td>
      </tr>
    @endif
  </table>

  <!-- B. Alamat Tempat Tinggal -->
  <div class="section-heading">B. Alamat Tempat Tinggal</div>
  <table class="data-table">
    <tr>
      <td class="label-col">Alamat Lengkap</td>
      <td class="sep-col">:</td>
      <td class="val-col">{{ $registration->formValue('alamat_lengkap', $registration->address) }} (RT/RW: {{ $registration->formValue('rt_rw', '-') }})</td>
    </tr>
    <tr>
      <td class="label-col">Kelurahan / Kecamatan</td>
      <td class="sep-col">:</td>
      <td class="val-col">{{ $registration->formValue('kelurahan', '-') }}, Kec. {{ $registration->formValue('kecamatan', '-') }}, Kota {{ $registration->formValue('kota', 'Balikpapan') }} (Kode Pos: {{ $registration->formValue('kode_pos', '-') }})</td>
    </tr>
    <tr>
      <td class="label-col">Tinggal Bersama / Transportasi</td>
      <td class="sep-col">:</td>
      <td class="val-col">{{ $registration->formValue('tinggal_bersama', 'Orang Tua') }} &middot; Moda Transportasi: {{ $registration->formValue('transportasi', '-') }}</td>
    </tr>
  </table>

  <!-- C & D. Data Orang Tua -->
  <table class="columns-2">
    <tr>
      <td>
        <div class="section-heading">C. Data Ayah Kandung</div>
        <table class="data-table">
          <tr><td class="label-col" style="width: 35%;">Nama</td><td class="sep-col">:</td><td class="val-col"><strong>{{ $registration->formValue('ayah_nama', $registration->parent_name) }}</strong></td></tr>
          <tr><td class="label-col">NIK</td><td class="sep-col">:</td><td class="val-col">{{ $registration->formValue('ayah_nik', '-') }}</td></tr>
          <tr><td class="label-col">Tempat, Tanggal Lahir</td><td class="sep-col">:</td><td class="val-col">{{ $registration->formValue('ayah_tempat_lahir', '-') }}, {{ filled($registration->formValue('ayah_tanggal_lahir')) ? \Carbon\Carbon::parse($registration->formValue('ayah_tanggal_lahir'))->translatedFormat('d F Y') : '-' }}</td></tr>
          <tr><td class="label-col">Pendidikan</td><td class="sep-col">:</td><td class="val-col">{{ $registration->formValue('ayah_pendidikan', '-') }}</td></tr>
          <tr><td class="label-col">Pekerjaan</td><td class="sep-col">:</td><td class="val-col">{{ $registration->formValue('ayah_pekerjaan', '-') }}</td></tr>
          <tr><td class="label-col">Penghasilan</td><td class="sep-col">:</td><td class="val-col">{{ $registration->formValue('ayah_penghasilan', '-') }}</td></tr>
          <tr><td class="label-col">No. HP / WA</td><td class="sep-col">:</td><td class="val-col">{{ $registration->formValue('ayah_hp', $registration->whatsapp) }}</td></tr>
        </table>
      </td>
      <td>
        <div class="section-heading">D. Data Ibu Kandung</div>
        <table class="data-table">
          <tr><td class="label-col" style="width: 35%;">Nama</td><td class="sep-col">:</td><td class="val-col"><strong>{{ $registration->formValue('ibu_nama', '-') }}</strong></td></tr>
          <tr><td class="label-col">NIK</td><td class="sep-col">:</td><td class="val-col">{{ $registration->formValue('ibu_nik', '-') }}</td></tr>
          <tr><td class="label-col">Tempat, Tanggal Lahir</td><td class="sep-col">:</td><td class="val-col">{{ $registration->formValue('ibu_tempat_lahir', '-') }}, {{ filled($registration->formValue('ibu_tanggal_lahir')) ? \Carbon\Carbon::parse($registration->formValue('ibu_tanggal_lahir'))->translatedFormat('d F Y') : '-' }}</td></tr>
          <tr><td class="label-col">Pendidikan</td><td class="sep-col">:</td><td class="val-col">{{ $registration->formValue('ibu_pendidikan', '-') }}</td></tr>
          <tr><td class="label-col">Pekerjaan</td><td class="sep-col">:</td><td class="val-col">{{ $registration->formValue('ibu_pekerjaan', '-') }}</td></tr>
          <tr><td class="label-col">Penghasilan</td><td class="sep-col">:</td><td class="val-col">{{ $registration->formValue('ibu_penghasilan', '-') }}</td></tr>
          <tr><td class="label-col">No. HP / WA</td><td class="sep-col">:</td><td class="val-col">{{ $registration->formValue('ibu_hp', '-') }}</td></tr>
        </table>
      </td>
    </tr>
  </table>

  <!-- E. Orang Yang Diberi Wewenang Menjemput -->
  <div class="section-heading">E. Orang Yang Diberi Wewenang Menjemput (Maksimal 3 Orang)</div>
  <table class="bordered-table">
    <thead>
      <tr>
        <th style="width: 5%; text-align: center;">No</th>
        <th style="width: 32%;">Nama Lengkap</th>
        <th style="width: 10%; text-align: center;">Usia</th>
        <th style="width: 18%;">Hubungan</th>
        <th style="width: 17%;">No. Identitas</th>
        <th style="width: 18%;">Telp / HP</th>
      </tr>
    </thead>
    <tbody>
      @for ($i = 0; $i < 3; $i++)
        @php $p = $penjemput[$i] ?? []; @endphp
        <tr>
          <td style="text-align: center;">{{ $i + 1 }}</td>
          <td><strong>{{ $p['nama'] ?? '-' }}</strong></td>
          <td style="text-align: center;">{{ !empty($p['usia']) ? $p['usia'].' thn' : '-' }}</td>
          <td>{{ $p['hubungan'] ?? '-' }}</td>
          <td>{{ $p['no_identitas'] ?? '-' }}</td>
          <td>{{ $p['telp'] ?? '-' }}</td>
        </tr>
      @endfor
    </tbody>
  </table>

  <!-- Berkas Pendukung Tahap 2 -->
  <div class="section-heading">Berkas Pendukung yang Diunggah (Tahap 2)</div>
  <table class="data-table compact-table">
    @foreach (\App\Support\SpmbForm::documentFields($registration->level) as $docKey => $docLabel)
      <tr>
        <td class="label-col">{{ $docLabel }}</td>
        <td class="sep-col">:</td>
        <td class="val-col">{{ $registration->documentUrl($docKey) ? 'Terlampir' : '-' }}</td>
      </tr>
    @endforeach
  </table>

  <!-- F. Kuesioner Wawancara, Pola Asuh, Hubungan Orang Tua & Riwayat Kesehatan -->
  @php
    $questionSections = collect(\App\Support\SpmbForm::fullFormSections($registration->level))
        ->filter(fn (array $section): bool => in_array($section['key'], ['motivasi', 'pola_asuh', 'hubungan', 'kesehatan'], true));
  @endphp
  @foreach ($questionSections as $section)
    <div class="section-heading">{{ $section['title'] }}</div>
    <table class="data-table compact-table">
      @foreach ($section['fields'] as $field)
        @php
          $answer = $registration->formValue($field['key']);
          $answerText = is_scalar($answer) ? trim((string) $answer) : '';
        @endphp
        @if ($answerText !== '')
          <tr>
            <td class="label-col">{{ $field['label'] }}</td>
            <td class="sep-col">:</td>
            <td class="val-col">{{ $answerText }}</td>
          </tr>
        @endif
      @endforeach
    </table>
  @endforeach

  <!-- ================= HALAMAN 2: SURAT KESANGGUPAN BIAYA & PERNYATAAN ================= -->
  <div class="page-break"></div>

  <table class="kop-table">
    <tr>
      @if ($logoSrc)
        <td class="kop-logo">
          <img src="{{ $logoSrc }}" alt="Logo">
        </td>
      @endif
      <td class="kop-text">
        <p class="kop-foundation">{{ $foundationName }}</p>
        <p class="kop-school">{{ strtoupper($registration->schoolName()) }}</p>
        <p class="kop-meta">{{ $schoolAddress }} &middot; Tahun Ajaran {{ $tahunAjaran }}</p>
      </td>
    </tr>
  </table>
  <div class="kop-line"></div>

  <div class="doc-title-box">
    <h1 class="doc-title">SURAT PERNYATAAN KESANGGUPAN BIAYA PENDIDIKAN</h1>
    <p class="doc-subtitle">Lampiran Berkas Pendaftaran SPMB No: <strong>{{ $registration->registration_number }}</strong></p>
  </div>

  <p style="font-size: 8.5pt; margin-bottom: 8px;">Yang bertanda tangan di bawah ini:</p>

  <table class="data-table" style="margin-bottom: 12px;">
    <tr>
      <td class="label-col">Nama Penanggung Jawab</td>
      <td class="sep-col">:</td>
      <td class="val-col"><strong>{{ $registration->formValue('pj_nama', $registration->parent_name) }}</strong></td>
    </tr>
    <tr>
      <td class="label-col">Pekerjaan / Alamat</td>
      <td class="sep-col">:</td>
      <td class="val-col">{{ $registration->formValue('pj_pekerjaan', '-') }} &middot; {{ $registration->formValue('pj_alamat', $registration->address) }}</td>
    </tr>
    <tr>
      <td class="label-col">Telp. / HP / WhatsApp</td>
      <td class="sep-col">:</td>
      <td class="val-col">{{ $registration->formValue('pj_telp', '-') }} &middot; {{ $registration->formValue('pj_hp', $registration->whatsapp) }}</td>
    </tr>
    <tr>
      <td class="label-col">Nama Calon Peserta Didik</td>
      <td class="sep-col">:</td>
      <td class="val-col"><strong>{{ $registration->full_name }}</strong> (Jenjang: {{ $registration->schoolName() }})</td>
    </tr>
  </table>

  <div class="section-heading">Rincian Kesanggupan Biaya Pendidikan Yang Disetujui</div>
  @php
    $pilihanUangMasuk = (int) ($registration->formValue('pilihan_uang_masuk', 1));
    $nominalUangMasuk = $fees['uang_masuk'][$pilihanUangMasuk] ?? 0;
    $pilihanSpp = (int) ($registration->formValue('pilihan_spp', 1));
    $nominalSpp = $fees['spp'][$pilihanSpp] ?? 0;
    $caraBayar = $registration->formValue('cara_bayar', 'Cash');
  @endphp

  <table class="bordered-table" style="margin-bottom: 12px;">
    <thead>
      <tr>
        <th style="width: 40%;">Jenis Komponen Biaya</th>
        <th style="width: 25%;">Pilihan</th>
        <th style="width: 35%;">Nominal Yang Disanggupi</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td><strong>Uang Masuk / Sarana &amp; Gedung</strong></td>
        <td>Pilihan {{ $pilihanUangMasuk }}</td>
        <td><strong>Rp {{ number_format($nominalUangMasuk, 0, ',', '.') }}</strong></td>
      </tr>
      <tr>
        <td><strong>Iuran SPP Bulanan</strong></td>
        <td>Pilihan {{ $pilihanSpp }}</td>
        <td><strong>Rp {{ number_format($nominalSpp, 0, ',', '.') }}</strong> / bulan</td>
      </tr>
      @if (! empty($fees['konsumsi']))
        <tr>
          <td><strong>+ Konsumsi : Iuran Makan Siang ({{ $registration->level }})</strong></td>
          <td>Wajib Siswa {{ $registration->level }}</td>
          <td><strong>Rp {{ number_format($fees['konsumsi'], 0, ',', '.') }}</strong> / bulan</td>
        </tr>
      @endif
      <tr>
        <td><strong>Metode Pembayaran Uang Masuk</strong></td>
        <td colspan="2">
          <strong>{{ $caraBayar }}</strong>
          @if ($caraBayar === 'Dicicil')
            (Selama {{ $fee['cicilan_bulan'] ?? '-' }} bulan, mulai: {{ $fee['cicilan_mulai'] ?? '-' }})
          @else
            (Lunas pada saat daftar ulang)
          @endif
        </td>
      </tr>
      @if (filled($registration->referral_code))
        <tr>
          <td><strong>Kode Referral / Voucher Promo</strong></td>
          <td><strong>{{ $registration->referral_code }}</strong></td>
          <td>
            <strong>Diskon Rp {{ number_format($registration->discount_amount, 0, ',', '.') }}</strong>
            @if ($registration->paymentProofIsFree())
              <br><span>Biaya pendaftaran: GRATIS</span>
            @endif
          </td>
        </tr>
      @endif
    </tbody>
  </table>

  <table class="data-table compact-table" style="margin-bottom: 10px;">
    <tr>
      <td class="label-col">Penilaian Biaya Pendidikan</td>
      <td class="sep-col">:</td>
      <td class="val-col">{{ $registration->formValue('penilaian_biaya', '-') }}</td>
    </tr>
    <tr>
      <td class="label-col">Alasan Penilaian</td>
      <td class="sep-col">:</td>
      <td class="val-col">{{ $registration->formValue('alasan_penilaian', '-') }}</td>
    </tr>
  </table>

  <div class="section-heading">Surat Pernyataan &amp; Komitmen Orang Tua / Wali</div>
  <ol class="agreement-list">
    @foreach ($agreements as $agreementText)
      <li>{{ $agreementText }}</li>
    @endforeach
  </ol>

  <p style="font-size: 8pt; color: #475569; margin: 10px 0 16px 0;">
    Demikian surat pernyataan dan formulir pendaftaran ini saya buat dengan sebenarnya dalam keadaan sadar, sehat jasmani dan rohani, tanpa ada paksaan dari pihak manapun.
  </p>

  <table class="sig-table">
    <tr>
      <td colspan="3" style="text-align: right; padding-bottom: 8px; font-size: 8.5pt;">
        {{ $registration->formValue('kota_ttd', 'Balikpapan') }}, {{ !empty($fee['signed_at']) ? \Carbon\Carbon::parse($fee['signed_at'])->translatedFormat('d F Y') : now()->translatedFormat('d F Y') }}
      </td>
    </tr>
    <tr>
      <td>
        <div>Orang Tua / Wali (Ayah),</div>
        <div class="sig-space"></div>
        <div class="sig-name">({{ $registration->formValue('ttd_ayah', $registration->parent_name) }})</div>
        <div class="sig-title">Tanda Tangan &amp; Nama Jelas</div>
      </td>
      <td>
        <div>Orang Tua / Wali (Ibu),</div>
        <div class="sig-space"></div>
        <div class="sig-name">({{ $registration->formValue('ttd_ibu', '..................................') }})</div>
        <div class="sig-title">Tanda Tangan &amp; Nama Jelas</div>
      </td>
      <td>
        <div>Mengetahui,<br>Panitia SPMB Cahaya Ilmu,</div>
        <div class="sig-space"></div>
        <div class="sig-name">( Panitia SPMB )</div>
        <div class="sig-title">Tanda Tangan &amp; Stempel Resmi</div>
      </td>
    </tr>
  </table>
</div>

</body>
</html>
