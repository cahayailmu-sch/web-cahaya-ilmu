<!DOCTYPE html>
<html lang="id" class="overflow-x-clip max-w-[100vw]" style="overflow-x: clip;">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Kartu Bukti Pendaftaran {{ $registration->registration_number }} — Cahaya Ilmu Islamic School</title>
  <link rel="icon" type="image/png" href="/assets/images/logo-utama.png">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" referrerpolicy="no-referrer">
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    body {
      font-family: 'Plus Jakarta Sans', sans-serif;
    }
    @media print {
      .no-print {
        display: none !important;
      }
      body {
        background: #ffffff !important;
        padding: 0 !important;
      }
      .print-card {
        box-shadow: none !important;
        border: 2px solid #0f172a !important;
      }
    }
  </style>
</head>
<body class="overflow-x-clip max-w-[100vw] bg-slate-100 text-slate-900 min-h-screen py-8 px-4 sm:px-6" style="overflow-x: clip;">
  <div class="max-w-3xl mx-auto">
    <!-- Action Bar (Hidden when printing) -->
    <div class="no-print mb-6 flex flex-wrap items-center justify-between gap-3 bg-white p-4 rounded-2xl border border-slate-200 shadow-sm">
      <div class="flex items-center gap-3">
        <a href="{{ route('ppdb.status', ['nomor' => $registration->registration_number]) }}" class="inline-flex items-center gap-2 text-sm font-bold text-slate-700 hover:text-rose-600">
          <i class="fa-solid fa-arrow-left"></i>
          Kembali ke Cek Status
        </a>
      </div>
      <div class="flex flex-wrap items-center gap-2.5">
        <button type="button" onclick="window.print()" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-rose-600 text-white text-sm font-bold hover:bg-rose-700 transition cursor-pointer shadow-sm">
          <i class="fa-solid fa-print"></i>
          Cetak / Simpan PDF
        </button>
      </div>
    </div>

    <!-- Official Registration Card -->
    <div class="print-card bg-white rounded-3xl border border-slate-300 shadow-xl overflow-hidden">
      <!-- Kop Kartu -->
      <div class="bg-slate-900 text-white p-6 sm:p-8 border-b-4 border-amber-400">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
          <div class="flex items-center gap-4">
            <img src="/assets/images/logo-utama.png" alt="Logo Cahaya Ilmu" class="w-16 h-16 rounded-2xl bg-white p-1.5 object-contain shrink-0">
            <div>
              <p class="text-[11px] font-bold uppercase tracking-widest text-amber-300">{{ $foundationName }}</p>
              <h1 class="text-xl sm:text-2xl font-extrabold tracking-tight">CAHAYA ILMU ISLAMIC SCHOOL</h1>
              <p class="text-xs text-slate-300 mt-0.5">{{ $settings['alamat_sekolah'] ?? 'Jl. Soekarno-Hatta KM. 8, Balikpapan, Kalimantan Timur' }}</p>
            </div>
          </div>
          <div class="sm:text-right border-t sm:border-t-0 pt-3 sm:pt-0 border-white/15">
            <span class="inline-block px-3 py-1 rounded-full bg-amber-400/20 text-amber-300 text-xs font-extrabold uppercase tracking-wider">
              Kartu Bukti Pendaftaran SPMB
            </span>
            <p class="mt-1 text-xs text-slate-300">Tahun Ajaran {{ $settings['tahun_ajaran'] ?? '2026/2027' }}</p>
          </div>
        </div>
      </div>

      <!-- Nomor & Status Highlight -->
      <div class="bg-slate-50 px-6 sm:px-8 py-5 border-b border-slate-200 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
          <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Nomor Pendaftaran</p>
          <p class="text-2xl sm:text-3xl font-extrabold font-mono text-slate-900 tracking-tight">{{ $registration->registration_number }}</p>
        </div>
        <div class="flex flex-wrap items-center gap-3">
          <div class="px-4 py-2 rounded-xl bg-rose-50 border border-rose-200">
            <p class="text-[10px] font-bold uppercase tracking-wider text-rose-500">Jenjang Pilihan</p>
            <p class="text-sm font-extrabold text-rose-800">{{ $registration->schoolName() }}@if (filled($registration->subLevelLabel())) &middot; {{ $registration->subLevelLabel() }}@endif</p>
          </div>
          <div class="px-4 py-2 rounded-xl bg-slate-900 text-white">
            <p class="text-[10px] font-bold uppercase tracking-wider text-slate-300">Status Saat Ini</p>
            <p class="text-sm font-extrabold text-amber-300">{{ $registration->status }}</p>
          </div>
        </div>
      </div>

      <!-- Detail Data Pendaftar -->
      <div class="p-6 sm:p-8 space-y-6">
        <div>
          <h2 class="text-xs font-extrabold uppercase tracking-wider text-slate-400 mb-3">A. Identitas Calon Peserta Didik</h2>
          <div class="grid sm:grid-cols-2 gap-4 border border-slate-200 rounded-2xl p-4 sm:p-5">
            <div>
              <p class="text-xs text-slate-500">Nama Lengkap</p>
              <p class="text-sm sm:text-base font-extrabold text-slate-900">{{ $registration->full_name }}</p>
            </div>
            <div>
              <p class="text-xs text-slate-500">Jenis Kelamin</p>
              <p class="text-sm sm:text-base font-bold text-slate-800">{{ $registration->gender === 'L' ? 'Laki-laki (Ikhwan)' : 'Perempuan (Akhwat)' }}</p>
            </div>
            <div>
              <p class="text-xs text-slate-500">Tempat, Tanggal Lahir</p>
              <p class="text-sm sm:text-base font-bold text-slate-800">
                {{ $registration->birth_place ?? '-' }}, {{ $registration->birth_date ? $registration->birth_date->translatedFormat('d F Y') : '-' }}
              </p>
            </div>
            <div>
              <p class="text-xs text-slate-500">Asal Sekolah Sebelumnya</p>
              <p class="text-sm sm:text-base font-bold text-slate-800">{{ $registration->previous_school ?: '-' }}</p>
            </div>
          </div>
        </div>

        <div>
          <h2 class="text-xs font-extrabold uppercase tracking-wider text-slate-400 mb-3">B. Data Orang Tua / Wali & Administrasi</h2>
          <div class="grid sm:grid-cols-2 gap-4 border border-slate-200 rounded-2xl p-4 sm:p-5">
            <div>
              <p class="text-xs text-slate-500">Nama Orang Tua / Wali</p>
              <p class="text-sm sm:text-base font-bold text-slate-900">{{ $registration->parent_name }}</p>
            </div>
            <div>
              <p class="text-xs text-slate-500">Nomor WhatsApp Aktif</p>
              <p class="text-sm sm:text-base font-bold text-slate-800">{{ $registration->whatsapp }}</p>
            </div>
            <div>
              <p class="text-xs text-slate-500">Biaya Pendaftaran</p>
              <p class="text-sm sm:text-base font-extrabold text-slate-900">Rp {{ number_format($registration->final_fee, 0, ',', '.') }}</p>
              @if (filled($registration->promoLabel()))
                <p class="mt-1 text-[11px] font-semibold text-emerald-700">
                  {{ $registration->promoLabel() }}
                  @if ($registration->discount_amount > 0)
                    (diskon Rp {{ number_format($registration->discount_amount, 0, ',', '.') }})
                  @endif
                </p>
              @endif
            </div>
            <div>
              <p class="text-xs text-slate-500">Tanggal & Waktu Pendaftaran</p>
              <p class="text-sm sm:text-base font-bold text-slate-800">{{ $registration->created_at?->translatedFormat('d F Y, H:i') }} WITA</p>
            </div>
            <div class="sm:col-span-2">
              <p class="text-xs text-slate-500">Alamat Domisili</p>
              <p class="text-sm font-semibold text-slate-800">{{ $registration->address }}</p>
            </div>
          </div>
        </div>

        <!-- Catatan Penting -->
        <div class="rounded-2xl bg-amber-50 border border-amber-200 p-4 text-xs text-amber-900 space-y-1.5">
          <p class="font-extrabold uppercase tracking-wider text-amber-800">Catatan Penting bagi Orang Tua / Wali:</p>
          <ul class="list-disc list-inside space-y-1">
            <li>Simpan atau cetak kartu bukti pendaftaran ini dan tunjukkan saat pelaksanaan observasi/wawancara di sekolah.</li>
            <li>Siapkan dokumen fisik berupa Fotokopi Akta Kelahiran, Kartu Keluarga (KK), dan Pas Foto saat jadwal observasi.</li>
            <li>Perkembangan status pendaftaran dapat dipantau secara berkala melalui halaman <strong>{{ route('ppdb.status') }}</strong>.</li>
          </ul>
        </div>

        <!-- Tanda Tangan / Pengesahan -->
        <div class="pt-4 grid grid-cols-2 gap-6 text-center text-xs text-slate-600">
          <div>
            <p>Orang Tua / Wali Calon Murid,</p>
            <div class="h-16"></div>
            <p class="font-bold text-slate-900 underline">( {{ $registration->parent_name }} )</p>
          </div>
          <div>
            <p>Balikpapan, {{ $registration->created_at?->translatedFormat('d F Y') ?? now()->translatedFormat('d F Y') }}</p>
            <p>Panitia SPMB {{ $registration->schoolName() }}</p>
            <div class="h-12 flex items-center justify-center">
              <span class="px-3 py-1 rounded border border-emerald-300 bg-emerald-50 text-[10px] font-extrabold text-emerald-700 uppercase">
                Terdaftar Elektronik • Sistem SPMB Online
              </span>
            </div>
            <p class="font-bold text-slate-900 underline">( Panitia SPMB Cahaya Ilmu )</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</body>
</html>
