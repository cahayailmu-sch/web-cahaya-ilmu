@extends('layouts.public')

@section('title', 'Cek Status SPMB — Cahaya Ilmu Islamic School Balikpapan')
@section('description', 'Cek status pendaftaran peserta didik baru (SPMB) Cahaya Ilmu Islamic School Balikpapan dan lengkapi formulir tahap 2.')

@section('content')
  <section class="page-hero py-14 lg:py-20">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
      <nav aria-label="Breadcrumb" class="breadcrumb mb-6 justify-center">
        <a href="{{ route('home') }}"><i class="fa-solid fa-house mr-1"></i>Beranda</a>
        <span class="breadcrumb-sep"><i class="fa-solid fa-chevron-right text-[10px]"></i></span>
        <a href="{{ route('ppdb') }}">SPMB</a>
        <span class="breadcrumb-sep"><i class="fa-solid fa-chevron-right text-[10px]"></i></span>
        <span class="breadcrumb-current">Cek Status Pendaftaran</span>
      </nav>

      <span class="badge-pill mb-4 text-rose-400 font-semibold">
        <i class="fa-solid fa-magnifying-glass-chart text-brand-600"></i>
        Layanan Mandiri SPMB {{ $settings['tahun_ajaran'] ?? '2026/2027' }}
      </span>

      <h1 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-amber-400 tracking-tight leading-tight">
        Cek Status <span>Pendaftaran SPMB</span>
      </h1>
      <p class="mt-4 text-slate-200 text-base sm:text-lg max-w-2xl mx-auto">
        Masukkan Nomor Pendaftaran Anda (contoh: <code class="px-2 py-0.5 rounded bg-brand-50 text-brand-700 font-semibold">SPMB-{{ now()->year }}-001</code>) untuk memantau proses verifikasi, melanjutkan pengisian Formulir Lengkap (Tahap 2), atau mencetak dokumen pendaftaran.
      </p>

      <form action="{{ route('ppdb.status') }}" method="GET" class="mt-8 max-w-xl mx-auto">
        <div class="flex flex-col sm:flex-row gap-3 bg-white p-2.5 rounded-2xl border border-slate-200 shadow-lg">
          <div class="relative flex-1">
            <i class="fa-solid fa-hashtag absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
            <input
              type="text"
              name="nomor"
              value="{{ $registrationNumber }}"
              placeholder="Masukkan Nomor Pendaftaran, misal: SPMB-{{ now()->year }}-001"
              required
              class="w-full pl-11 pr-4 py-3.5 rounded-xl text-sm sm:text-base font-semibold text-slate-900 placeholder:font-normal placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-brand-500 uppercase"
            >
          </div>
          <button type="submit" class="btn-primary !py-3.5 !px-6 whitespace-nowrap justify-center">
            <i class="fa-solid fa-magnifying-glass"></i>
            Cek Status
          </button>
        </div>
      </form>
    </div>
  </section>

  <section class="py-12 lg:py-16 bg-white">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
      @if (session('ppdb_success'))
        <div class="mb-8 rounded-3xl border border-emerald-200 bg-emerald-50/90 p-6 sm:p-7 text-emerald-900 shadow-sm">
          <div class="flex items-start gap-4">
            <div class="w-12 h-12 rounded-2xl bg-emerald-600 text-white flex items-center justify-center text-xl shrink-0">
              <i class="fa-solid fa-circle-check"></i>
            </div>
            <div class="flex-1">
              <h3 class="text-lg font-extrabold text-emerald-950">Alhamdulillah! Pendaftaran Awal Berhasil Dikirim</h3>
              <p class="mt-1 text-sm text-emerald-800">
                Pendaftaran atas nama <strong class="text-emerald-950">{{ session('ppdb_success')['full_name'] ?? '' }}</strong> telah tersimpan dengan Nomor Pendaftaran:
                <span class="inline-block px-2.5 py-0.5 rounded-lg bg-white font-mono font-extrabold text-emerald-900 border border-emerald-300 ml-1">
                  {{ session('ppdb_success')['registration_number'] ?? '' }}
                </span>.
              </p>
              <p class="mt-2 text-xs text-emerald-700">
                Panitia SPMB akan memverifikasi bukti pembayaran Anda. Setelah diverifikasi, Anda dapat melanjutkan ke Tahap 2 (Formulir Lengkap &amp; Surat Kesanggupan Biaya).
              </p>
              @if (! empty(session('ppdb_success')['whatsapp_url']))
                <div class="mt-4">
                  <a href="{{ session('ppdb_success')['whatsapp_url'] }}" target="_blank" rel="noopener" class="inline-flex items-center gap-2 rounded-xl bg-emerald-700 px-4 py-2 text-xs font-bold text-white hover:bg-emerald-800 shadow-sm transition">
                    <i class="fa-brands fa-whatsapp text-sm"></i>
                    Konfirmasi Pembayaran ke WhatsApp Admin
                  </a>
                </div>
              @endif
            </div>
          </div>
        </div>
      @endif

      @if ($registrationNumber === '')
        <div class="rounded-3xl border border-slate-200 bg-slate-50/70 p-6 sm:p-10 text-center">
          <div class="w-16 h-16 rounded-2xl bg-brand-50 text-brand-600 flex items-center justify-center text-2xl mx-auto mb-4">
            <i class="fa-solid fa-id-card-clip"></i>
          </div>
          <h2 class="text-xl font-extrabold text-slate-900">Belum Ada Nomor Pendaftaran yang Dicari</h2>
          <p class="mt-2 text-sm sm:text-base text-slate-600 max-w-lg mx-auto">
            Nomor pendaftaran diberikan secara otomatis saat Anda menyelesaikan pengisian formulir SPMB online awal.
          </p>
          <div class="mt-6 flex flex-wrap justify-center gap-3">
            <a href="{{ route('ppdb') }}#formulir" class="btn-primary">
              <i class="fa-solid fa-pen-to-square"></i> Isi Formulir SPMB
            </a>
          </div>
        </div>
      @elseif (! $registration)
        <div class="rounded-3xl border border-rose-200 bg-rose-50/70 p-6 sm:p-10 text-center">
          <div class="w-16 h-16 rounded-2xl bg-rose-100 text-rose-600 flex items-center justify-center text-2xl mx-auto mb-4">
            <i class="fa-solid fa-circle-exclamation"></i>
          </div>
          <h2 class="text-xl font-extrabold text-slate-900">Data Pendaftaran Tidak Ditemukan</h2>
          <p class="mt-2 text-sm sm:text-base text-slate-600 max-w-lg mx-auto">
            Nomor pendaftaran <strong class="text-rose-700 font-mono">{{ $registrationNumber }}</strong> tidak ditemukan. Pastikan format nomor sudah benar (contoh: <span class="font-mono font-semibold">SPMB-{{ now()->year }}-001</span>).
          </p>
          <div class="mt-6 flex flex-wrap justify-center gap-3">
            <a href="{{ route('ppdb') }}#form-ppdb" class="btn-primary">
              <i class="fa-solid fa-user-plus"></i> Daftar SPMB Baru
            </a>
          </div>
        </div>
      @else
        @php
          $statusMeta = match ($registration->status) {
              'verified', 'Diverifikasi' => [
                  'badge' => 'bg-sky-100 text-sky-800 border-sky-200',
                  'icon' => 'fa-circle-check text-sky-600',
                  'title' => 'Berkas & Pembayaran Terverifikasi (Akses Tahap 2 Terbuka)',
                  'desc' => 'Alhamdulillah, berkas dan bukti pembayaran pendaftaran telah diverifikasi oleh Panitia SPMB. Silakan klik tombol di bawah untuk melengkapi Formulir Lengkap & Surat Kesanggupan Biaya Pendidikan.',
                  'step' => 2,
              ],
              'completed' => [
                  'badge' => 'bg-indigo-100 text-indigo-800 border-indigo-200',
                  'icon' => 'fa-file-circle-check text-indigo-600',
                  'title' => 'Formulir Lengkap & Pernyataan Biaya Selesai',
                  'desc' => 'Seluruh formulir pendaftaran lengkap dan surat kesanggupan biaya telah tersimpan di sistem. Silakan bergabung ke Grup WhatsApp resmi jenjang untuk instruksi observasi / tes wawancara berikutnya.',
                  'step' => 2,
              ],
              'accepted', 'Diterima' => [
                  'badge' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
                  'icon' => 'fa-award text-emerald-600',
                  'title' => 'Selamat! Calon Murid Dinyatakan Diterima',
                  'desc' => 'Alhamdulillah, calon peserta didik dinyatakan DITERIMA di Cahaya Ilmu Islamic School Balikpapan. Silakan hubungi Admin SPMB jenjang untuk proses daftar ulang.',
                  'step' => 3,
              ],
              'rejected', 'Ditolak' => [
                  'badge' => 'bg-rose-100 text-rose-800 border-rose-200',
                  'icon' => 'fa-circle-xmark text-rose-600',
                  'title' => 'Pendaftaran Belum Dapat Dilanjutkan',
                  'desc' => 'Mohon maaf, pendaftaran belum dapat dilanjutkan. Silakan menghubungi Admin SPMB jenjang terkait untuk informasi lebih lanjut.',
                  'step' => 3,
              ],
              default => [
                  'badge' => 'bg-amber-100 text-amber-800 border-amber-200',
                  'icon' => 'fa-clock text-amber-600',
                  'title' => 'Tahap 1: Menunggu Verifikasi Panitia SPMB',
                  'desc' => 'Data formulir awal dan bukti pembayaran Anda telah diterima sistem dan sedang dalam antrean verifikasi panitia SPMB. Tahap 2 (Formulir Lengkap) akan terbuka otomatis setelah pembayaran diverifikasi.',
                  'step' => 1,
              ],
          };
          $levelContact = $contacts[$registration->level] ?? null;
          $waGroup = $registration->whatsappGroupUrl($settings);
        @endphp

        <div class="rounded-3xl border border-slate-200 bg-white shadow-xl overflow-hidden">
          <div class="bg-gradient-to-r from-slate-900 via-brand-950 to-slate-900 p-6 sm:p-8 text-white">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
              <div>
                <p class="text-xs font-bold uppercase tracking-widest text-gold-300">Nomor Pendaftaran Resmi</p>
                <p class="mt-1 text-2xl sm:text-3xl font-extrabold font-mono tracking-tight">{{ $registration->registration_number }}</p>
                <p class="mt-1 text-xs text-slate-300">Terdaftar pada {{ $registration->created_at?->translatedFormat('d F Y, H:i') }} WITA</p>
              </div>
              <div class="flex flex-wrap items-center gap-2.5">
                <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full text-xs sm:text-sm font-extrabold border {{ $statusMeta['badge'] }}">
                  <i class="fa-solid {{ $statusMeta['icon'] }}"></i>
                  Status: {{ $registration->statusLabel() }}
                </span>
                <span class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-full text-xs font-extrabold bg-white/10 text-white border border-white/15">
                  <i class="fa-solid fa-graduation-cap text-gold-300"></i>
                  {{ $registration->schoolName() }}
                </span>
              </div>
            </div>
          </div>

          <div class="p-6 sm:p-8 space-y-8">
            <!-- Progress Tahapan Pendaftaran 2 Tahap -->
            <div>
              <p class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-4">Alur Pendaftaran SPMB 2 Tahap</p>
              <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                <div class="p-4 rounded-2xl border {{ $statusMeta['step'] >= 1 ? 'border-brand-200 bg-brand-50/60' : 'border-slate-200 bg-slate-50' }}">
                  <div class="flex items-center gap-2.5">
                    <span class="w-7 h-7 rounded-full text-xs font-extrabold flex items-center justify-center {{ $statusMeta['step'] >= 1 ? 'bg-brand-600 text-white' : 'bg-slate-200 text-slate-600' }}">1</span>
                    <span class="font-bold text-sm text-slate-900">Tahap 1: Formulir Awal</span>
                  </div>
                  <p class="mt-1.5 text-xs text-slate-600">Biodata awal &amp; bukti pembayaran telah diterima.</p>
                </div>

                <div class="p-4 rounded-2xl border {{ in_array($registration->status, ['verified', 'completed', 'accepted']) ? 'border-sky-200 bg-sky-50/70' : 'border-slate-200 bg-slate-50' }}">
                  <div class="flex items-center gap-2.5">
                    <span class="w-7 h-7 rounded-full text-xs font-extrabold flex items-center justify-center {{ in_array($registration->status, ['verified', 'completed', 'accepted']) ? 'bg-sky-600 text-white' : 'bg-slate-200 text-slate-600' }}">2</span>
                    <span class="font-bold text-sm text-slate-900">Tahap 2: Formulir Lengkap</span>
                  </div>
                  <p class="mt-1.5 text-xs text-slate-600">
                    @if ($registration->status === 'completed' || $registration->status === 'accepted')
                      Formulir lengkap &amp; pernyataan telah dikirim.
                    @elseif ($registration->status === 'verified')
                      <span class="text-sky-700 font-bold">Terbuka — Silakan isi sekarang.</span>
                    @else
                      Terkunci hingga pembayaran diverifikasi admin.
                    @endif
                  </p>
                </div>

                <div class="p-4 rounded-2xl border {{ in_array($registration->status, ['accepted', 'Diterima']) ? 'border-emerald-200 bg-emerald-50/70' : (in_array($registration->status, ['rejected', 'Ditolak']) ? 'border-rose-200 bg-rose-50/70' : 'border-slate-200 bg-slate-50') }}">
                  <div class="flex items-center gap-2.5">
                    <span class="w-7 h-7 rounded-full text-xs font-extrabold flex items-center justify-center {{ in_array($registration->status, ['accepted', 'Diterima']) ? 'bg-emerald-600 text-white' : (in_array($registration->status, ['rejected', 'Ditolak']) ? 'bg-rose-600 text-white' : 'bg-slate-200 text-slate-600') }}">3</span>
                    <span class="font-bold text-sm text-slate-900">Observasi &amp; Kelulusan</span>
                  </div>
                  <p class="mt-1.5 text-xs text-slate-600">Observasi calon siswa, wawancara, &amp; pengumuman.</p>
                </div>
              </div>
            </div>

            <!-- Status Box -->
            <div class="rounded-2xl border border-slate-200 bg-slate-50 p-5">
              <h3 class="text-base sm:text-lg font-extrabold text-slate-900 flex items-center gap-2">
                <i class="fa-solid {{ $statusMeta['icon'] }}"></i>
                {{ $statusMeta['title'] }}
              </h3>
              <p class="mt-1.5 text-sm text-slate-600 leading-relaxed">{{ $statusMeta['desc'] }}</p>
            </div>

            <!-- Action Banner: Tahap 2 Terbuka -->
            @if ($registration->status === 'verified')
              <div class="rounded-3xl border-2 border-brand-500 bg-gradient-to-br from-brand-50 via-white to-amber-50/40 p-6 sm:p-7 shadow-lg flex flex-col md:flex-row items-start md:items-center justify-between gap-5">
                <div class="flex items-start gap-4">
                  <div class="w-12 h-12 rounded-2xl bg-brand-600 text-white flex items-center justify-center text-xl shrink-0 shadow-md">
                    <i class="fa-solid fa-pen-nib"></i>
                  </div>
                  <div>
                    <span class="inline-block px-2.5 py-0.5 rounded-full text-[11px] font-extrabold uppercase tracking-wider bg-brand-100 text-brand-800 mb-1">
                      Akses Tahap 2 Telah Dibuka
                    </span>
                    <h4 class="text-lg font-extrabold text-slate-900">Lanjutkan: Formulir Lengkap &amp; Kesanggupan Biaya</h4>
                    <p class="text-xs sm:text-sm text-slate-600 mt-1 max-w-xl">
                      Mohon lengkapi data diri siswa, data orang tua, kuesioner wawancara, dan pilih opsi pembiayaan pendidikan agar pendaftaran Anda dapat diproses ke tahap observasi.
                    </p>
                  </div>
                </div>
                <a href="{{ route('spmb.full-form', $registration->registration_code) }}" class="btn-primary !py-3.5 !px-6 whitespace-nowrap text-sm shrink-0 w-full md:w-auto justify-center shadow-lg hover:shadow-xl">
                  Isi Formulir Lengkap Sekarang
                  <i class="fa-solid fa-arrow-right ml-1"></i>
                </a>
              </div>
            @endif

            <!-- Action Banner: Formulir Lengkap Selesai & Gabung Grup WA -->
            @if ($registration->status === 'completed' || $registration->status === 'accepted')
              <div class="rounded-3xl border-2 border-emerald-500 bg-gradient-to-br from-emerald-50 via-white to-sky-50/40 p-6 sm:p-7 shadow-lg flex flex-col md:flex-row items-start md:items-center justify-between gap-5">
                <div class="flex items-start gap-4">
                  <div class="w-12 h-12 rounded-2xl bg-emerald-600 text-white flex items-center justify-center text-xl shrink-0 shadow-md">
                    <i class="fa-brands fa-whatsapp"></i>
                  </div>
                  <div>
                    <span class="inline-block px-2.5 py-0.5 rounded-full text-[11px] font-extrabold uppercase tracking-wider bg-emerald-100 text-emerald-800 mb-1">
                      Grup Koordinasi Calon Wali Murid
                    </span>
                    <h4 class="text-lg font-extrabold text-slate-900">Gabung Grup WhatsApp Resmi {{ $registration->level }} ({{ $registration->genderLabel() }})</h4>
                    <p class="text-xs sm:text-sm text-slate-600 mt-1 max-w-xl">
                      Seluruh informasi jadwal observasi calon santri, jadwal wawancara orang tua, dan tata tertib disampaikan melalui grup WhatsApp resmi panitia.
                    </p>
                  </div>
                </div>
                @if ($waGroup)
                  <a href="{{ $waGroup }}" target="_blank" rel="noopener" class="btn-wa !py-3.5 !px-6 whitespace-nowrap text-sm shrink-0 w-full md:w-auto justify-center shadow-md hover:shadow-lg">
                    <i class="fa-brands fa-whatsapp text-lg"></i>
                    Gabung Grup WhatsApp
                  </a>
                @else
                  <span class="text-xs text-slate-500 italic bg-slate-100 px-3 py-2 rounded-xl">
                    Link grup WA akan dikirimkan langsung oleh admin jenjang.
                  </span>
                @endif
              </div>
            @endif

            <!-- Ringkasan Data Calon Murid -->
            <div>
              <p class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-3">Ringkasan Data Calon Murid</p>
              <div class="grid sm:grid-cols-2 gap-4">
                <div class="p-4 rounded-2xl border border-slate-100 bg-slate-50/60">
                  <p class="text-xs font-semibold text-slate-400 uppercase">Nama Lengkap Calon Murid</p>
                  <p class="mt-1 text-base font-extrabold text-slate-900">{{ $registration->full_name }}</p>
                </div>
                <div class="p-4 rounded-2xl border border-slate-100 bg-slate-50/60">
                  <p class="text-xs font-semibold text-slate-400 uppercase">Pilihan Jenjang</p>
                  <p class="mt-1 text-base font-extrabold text-brand-700">{{ $registration->schoolName() }}</p>
                </div>
                <div class="p-4 rounded-2xl border border-slate-100 bg-slate-50/60">
                  <p class="text-xs font-semibold text-slate-400 uppercase">Jenis Kelamin &amp; Tempat Lahir</p>
                  <p class="mt-1 text-sm font-bold text-slate-800">
                    {{ $registration->genderLabel() }} &bull; {{ $registration->birth_place ?? '-' }}
                  </p>
                </div>
                <div class="p-4 rounded-2xl border border-slate-100 bg-slate-50/60">
                  <p class="text-xs font-semibold text-slate-400 uppercase">Nama Orang Tua / Wali</p>
                  <p class="mt-1 text-sm font-bold text-slate-800">{{ $registration->parent_name }}</p>
                </div>
                <div class="p-4 rounded-2xl border border-slate-100 bg-slate-50/60">
                  <p class="text-xs font-semibold text-slate-400 uppercase">Asal Sekolah</p>
                  <p class="mt-1 text-sm font-bold text-slate-800">{{ $registration->previous_school ?: '-' }}</p>
                </div>
                <div class="p-4 rounded-2xl border border-slate-100 bg-slate-50/60">
                  <p class="text-xs font-semibold text-slate-400 uppercase">Biaya Pendaftaran Awal</p>
                  <p class="mt-1 text-sm font-extrabold text-slate-900">Rp {{ number_format($registration->final_fee, 0, ',', '.') }}</p>
                </div>
              </div>
            </div>

            <!-- Action Buttons -->
            <div class="pt-4 border-t border-slate-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
              <div class="flex flex-wrap gap-2.5">
                <a href="{{ route('ppdb.card', $registration->registration_number) }}" target="_blank" class="btn-secondary !py-2.5 !px-4 text-xs font-bold">
                  <i class="fa-solid fa-id-card"></i>
                  Kartu Pendaftaran
                </a>

                @if ($registration->hasCompletedFullForm())
                  <a href="{{ route('spmb.full-form.print', $registration->registration_code) }}" target="_blank" class="btn-primary !py-2.5 !px-4 text-xs font-bold">
                    <i class="fa-solid fa-print"></i>
                    Cetak Formulir Lengkap (A4)
                  </a>
                  <a href="{{ route('spmb.full-form.pdf', $registration->registration_code) }}" class="inline-flex items-center gap-1.5 rounded-xl border border-rose-300 bg-rose-50 px-4 py-2.5 text-xs font-bold text-rose-700 hover:bg-rose-100 transition shadow-sm">
                    <i class="fa-solid fa-file-pdf"></i>
                    Download PDF
                  </a>
                @endif

                @if (! empty($levelContact['wa_url']))
                  <a href="{{ $levelContact['wa_url'] }}?text={{ urlencode('Assalamu\'alaikum Admin SPMB '.$registration->level.', saya ingin menanyakan status pendaftaran dengan nomor '.$registration->registration_number.' atas nama '.$registration->full_name.'.') }}" target="_blank" rel="noopener" class="btn-wa !py-2.5 !px-4 text-xs font-bold">
                    <i class="fa-brands fa-whatsapp text-sm"></i>
                    Hubungi Admin {{ $registration->level }}
                  </a>
                @endif
              </div>
              <a href="{{ route('ppdb') }}" class="text-xs sm:text-sm font-bold text-slate-600 hover:text-brand-600">
                <i class="fa-solid fa-arrow-left mr-1"></i> Halaman Utama SPMB
              </a>
            </div>
          </div>
        </div>
      @endif
    </div>
  </section>
@endsection
