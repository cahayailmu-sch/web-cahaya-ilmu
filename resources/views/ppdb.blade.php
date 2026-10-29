<!DOCTYPE html>
<html lang="id" class="scroll-smooth overflow-x-clip max-w-[100vw]" style="overflow-x: clip;">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>SPMB 2027/2028 | Cahaya Ilmu Islamic School Balikpapan</title>
  <meta name="description" content="SPMB (Sistem Penerimaan Murid Baru) 2027/2028 Cahaya Ilmu Islamic School Balikpapan untuk jenjang KB-TK, SD, SMP, dan SMA. Informasi jadwal, persyaratan, alur, dan formulir pendaftaran.">
  <meta name="theme-color" content="#e11d48">
  <meta name="color-scheme" content="light">

  <!--
    ============================================================================
    HALAMAN SPMB - CAHAYA ILMU ISLAMIC SCHOOL BALIKPAPAN
    ----------------------------------------------------------------------------
    PANDUAN EDIT CEPAT:
    1. Setiap section diawali komentar "AWAL SECTION ..." dan diakhiri
       "AKHIR SECTION ...".
    2. Nomor WhatsApp admin, Instagram per jenjang, dan YouTube dikelola dari
       halaman admin /admin/settings.
    3. Formulir ini menyimpan data ke database, lalu menampilkan tombol
       konfirmasi ke WhatsApp admin sesuai jenjang yang dipilih.
    ============================================================================
  -->

  <meta property="og:type" content="website">
  <meta property="og:locale" content="id_ID">
  <meta property="og:title" content="SPMB 2027/2028 - Cahaya Ilmu Islamic School Balikpapan">
  <meta property="og:description" content="Bergabunglah dengan keluarga besar Cahaya Ilmu Islamic School. Pendaftaran siswa baru jenjang KB-TK, SD, SMP, dan SMA telah dibuka.">

  <link rel="icon" type="image/png" href="/assets/images/logo-utama.png">

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,400;0,500;0,600;0,700;0,800;1,400&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Arabic:wght@100..900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" referrerpolicy="no-referrer">

  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            brand: {
              50:  '#fff1f2',
              100: '#ffe4e6',
              200: '#fecdd3',
              300: '#fda4af',
              400: '#fb7185',
              500: '#f43f5e',
              600: '#e11d48',
              700: '#be123c',
              800: '#9f1239',
              900: '#881337',
              950: '#4c0519'
            },
            gold: {
              50:  '#fffbeb',
              100: '#fef3c7',
              200: '#fde68a',
              300: '#fcd34d',
              400: '#fbbf24',
              500: '#f59e0b',
              600: '#ea580c',
              700: '#c2410c',
              800: '#9a3412',
              900: '#7c2d12'
            }
          },
          fontFamily: {
            sans: ['Plus Jakarta Sans', 'ui-sans-serif', 'system-ui', 'sans-serif']
          }
        }
      }
    };
  </script>

  <link rel="stylesheet" href="/css/style.css">
</head>

<body class="overflow-x-clip max-w-[100vw] bg-slate-50 font-sans text-slate-700 antialiased" style="overflow-x: clip;">

  <a href="#main" class="skip-link">Langsung ke konten utama</a>

  <!-- ============================================================
       AWAL SECTION HEADER / NAVBAR SPMB
       ============================================================ -->
  <header id="site-header" class="site-header fixed top-0 left-0 right-0 w-full z-50 transition-all duration-300 bg-white/95 backdrop-blur-md shadow-sm border-b border-slate-100">
    <div class="mx-auto flex h-20 max-w-7xl items-center justify-between gap-4 px-4 sm:px-6 lg:px-8">

      <a href="{{ route('home') }}" class="flex min-w-0 shrink-0 items-center gap-2.5 sm:gap-3" aria-label="Cahaya Ilmu Islamic School Balikpapan - Beranda">
        <img src="/assets/images/logo-utama.png" alt="Logo Cahaya Ilmu Islamic School" class="h-11 w-auto shrink-0 object-contain sm:h-14">
        <span class="min-w-0 leading-none">
          <span class="block text-[9px] font-extrabold uppercase tracking-[0.22em] text-brand-700">Cahaya Ilmu</span>
          <span class="mt-1 block truncate text-base font-extrabold tracking-tight text-slate-900 sm:text-xl">Islamic School</span>
          <span class="mt-1 hidden text-[10px] font-medium text-slate-500 sm:block">Balikpapan &bull; Kalimantan Timur</span>
        </span>
      </a>

      <nav class="hidden items-center gap-0.5 lg:flex" aria-label="Navigasi utama">
        <a href="{{ route('home') }}#beranda" class="nav-link">Beranda</a>
        <a href="{{ route('home') }}#tentang" class="nav-link">Tentang Kami</a>

        <div class="nav-dropdown relative">
          <a href="{{ route('home') }}#jenjang" class="nav-link inline-flex items-center gap-1.5" aria-haspopup="true">
            Jenjang
            <i class="fa-solid fa-chevron-down text-[9px] transition-transform duration-300" aria-hidden="true"></i>
          </a>
          <div class="nav-dropdown-panel absolute left-0 top-full z-50 w-72 pt-3">
            <div class="rounded-2xl border border-slate-100 bg-white p-2 shadow-2xl shadow-slate-900/10">
              <p class="px-3 pb-2 pt-1 text-[10px] font-extrabold uppercase tracking-[0.18em] text-slate-400">Pilih Jenjang</p>
              <a href="{{ route('home') }}#jenjang" class="dropdown-link" data-jenjang="tk">
                <span class="dropdown-icon bg-slate-50 p-1"><img src="/assets/images/logo-tk.png" alt="Logo KB-TK Islam Cahaya Ilmu" class="h-full w-full object-contain"></span>
                <span><span class="block text-sm font-bold text-slate-800">KB-TK Islam Cahaya Ilmu</span><span class="block text-[11px] text-slate-500">KB 3,5 &middot; TK A 4,5 &middot; TK B 5,5 th</span></span>
              </a>
              <a href="{{ route('home') }}#jenjang" class="dropdown-link" data-jenjang="sd">
                <span class="dropdown-icon bg-slate-50 p-1"><img src="/assets/images/logo-sd.png" alt="Logo SD Islam Cahaya Ilmu" class="h-full w-full object-contain"></span>
                <span><span class="block text-sm font-bold text-slate-800">SD Islam Cahaya Ilmu</span><span class="block text-[11px] text-slate-500">Kelas 1&ndash;6</span></span>
              </a>
              <a href="{{ route('home') }}#jenjang" class="dropdown-link" data-jenjang="smp">
                <span class="dropdown-icon bg-slate-50 p-1"><img src="/assets/images/logo-smp.png" alt="Logo SMP Islam Cahaya Ilmu" class="h-full w-full object-contain"></span>
                <span><span class="block text-sm font-bold text-slate-800">SMP Islam Cahaya Ilmu</span><span class="block text-[11px] text-slate-500">Kelas 7&ndash;9</span></span>
              </a>
              <a href="{{ route('home') }}#jenjang" class="dropdown-link" data-jenjang="sma">
                <span class="dropdown-icon bg-slate-50 p-1"><img src="/assets/images/logo-sma.png" alt="Logo SMA Islam Cahaya Ilmu" class="h-full w-full object-contain"></span>
                <span><span class="block text-sm font-bold text-slate-800">SMA Islam Cahaya Ilmu</span><span class="block text-[11px] text-slate-500">Kelas 10&ndash;12 (Banaat/Putri)</span></span>
              </a>
            </div>
          </div>
        </div>

        <a href="{{ route('home') }}#program" class="nav-link">Program Unggulan</a>
        <a href="{{ route('home') }}#fasilitas" class="nav-link">Galeri Sekolah</a>
        <a href="{{ route('home') }}#berita" class="nav-link">Info Sekolah</a>
        <a href="{{ route('home') }}#kontak" class="nav-link">Kontak</a>
      </nav>

      <div class="hidden items-center gap-2 lg:flex">
        <a href="#formulir" class="btn btn-primary">
          Daftar SPMB
          <i class="fa-solid fa-arrow-right text-[11px]" aria-hidden="true"></i>
        </a>
      </div>

      <button id="menu-toggle" type="button" class="inline-flex h-11 w-11 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-700 shadow-sm transition hover:border-brand-200 hover:text-brand-700 lg:hidden" aria-expanded="false" aria-controls="mobile-menu" aria-label="Buka menu navigasi">
        <i id="menu-icon-open" class="fa-solid fa-bars text-lg" aria-hidden="true"></i>
        <i id="menu-icon-close" class="fa-solid fa-xmark hidden text-xl" aria-hidden="true"></i>
      </button>
    </div>

    <div id="mobile-menu" class="mobile-menu relative z-50 lg:hidden">
      <nav class="mx-auto max-w-7xl px-4 pb-6 pt-2 sm:px-6" aria-label="Navigasi mobile">
        <div class="grid gap-1">
          <a href="{{ route('home') }}#beranda" class="mobile-nav-link"><i class="fa-solid fa-house w-5 text-brand-600"></i> Beranda</a>
          <a href="{{ route('home') }}#tentang" class="mobile-nav-link"><i class="fa-solid fa-circle-info w-5 text-brand-600"></i> Tentang Kami</a>
        </div>

        <div class="mt-3 rounded-2xl border border-slate-100 bg-slate-50 p-3">
          <p class="px-2 pb-2 text-[10px] font-extrabold uppercase tracking-[0.18em] text-slate-400">Jenjang Pendidikan</p>
          <div class="grid grid-cols-2 gap-2">
            <a href="{{ route('home') }}#jenjang" class="mobile-jenjang-link" data-jenjang="tk"><img src="/assets/images/logo-tk.png" alt="Logo KB-TK" class="h-5 w-5 shrink-0 object-contain"> KB-TK</a>
            <a href="{{ route('home') }}#jenjang" class="mobile-jenjang-link" data-jenjang="sd"><img src="/assets/images/logo-sd.png" alt="Logo SD" class="h-5 w-5 shrink-0 object-contain"> SD</a>
            <a href="{{ route('home') }}#jenjang" class="mobile-jenjang-link" data-jenjang="smp"><img src="/assets/images/logo-smp.png" alt="Logo SMP" class="h-5 w-5 shrink-0 object-contain"> SMP</a>
            <a href="{{ route('home') }}#jenjang" class="mobile-jenjang-link" data-jenjang="sma"><img src="/assets/images/logo-sma.png" alt="Logo SMA" class="h-5 w-5 shrink-0 object-contain"> SMA</a>
          </div>
        </div>

        <div class="mt-3 grid gap-1">
          <a href="{{ route('home') }}#program" class="mobile-nav-link"><i class="fa-solid fa-star w-5 text-brand-600"></i> Program Unggulan</a>
          <a href="{{ route('home') }}#fasilitas" class="mobile-nav-link"><i class="fa-solid fa-school w-5 text-brand-600"></i> Galeri Sekolah</a>
          <a href="{{ route('home') }}#berita" class="mobile-nav-link"><i class="fa-solid fa-newspaper w-5 text-brand-600"></i> Info Sekolah</a>
          <a href="{{ route('home') }}#kontak" class="mobile-nav-link"><i class="fa-solid fa-location-dot w-5 text-brand-600"></i> Kontak</a>
        </div>

        <div class="mt-4 grid gap-2">
          <a href="#formulir" class="btn btn-primary w-full">Isi Formulir SPMB <i class="fa-solid fa-arrow-right text-xs"></i></a>
          <a href="{{ url('/#kontak') }}" class="btn btn-outline w-full">
            <i class="fa-solid fa-address-book text-base text-rose-600"></i> Kontak Admin per Jenjang
          </a>
        </div>
      </nav>
    </div>
  </header>
  <!-- AKHIR SECTION HEADER / NAVBAR SPMB -->

  <main id="main" class="pt-20 overflow-x-clip">

    <!-- ============================================================
         AWAL SECTION HERO SPMB
         ============================================================ -->
    <section class="page-hero relative overflow-hidden">
      <div class="hero-pattern absolute inset-0" aria-hidden="true"></div>
      <div class="absolute -right-24 -top-24 h-80 w-80 rounded-full bg-gold-400/10 blur-3xl" aria-hidden="true"></div>

      <div class="relative mx-auto max-w-7xl px-4 pb-16 pt-8 sm:px-6 sm:pb-20 sm:pt-10 lg:px-8 lg:pb-24 lg:pt-14">
        <nav class="breadcrumb" aria-label="Breadcrumb">
          <a href="{{ route('home') }}">Beranda</a>
          <i class="fa-solid fa-chevron-right text-[8px]" aria-hidden="true"></i>
          <span aria-current="page">SPMB 2027/2028</span>
        </nav>

        <div class="mt-6 grid gap-8 sm:mt-8 sm:gap-10 lg:grid-cols-[1.05fr_0.95fr] lg:items-center lg:gap-16">
          <div class="reveal min-w-0">
            <!-- Sapaan Arab & pengantar menyatu tanpa badge -->
            <p class="mb-1 font-arabic text-left text-2xl font-bold leading-[2] text-amber-400 md:text-3xl" lang="ar">أَهْلًا وَسَهْلًا</p>
            <p class="text-xs font-medium text-slate-300 sm:text-sm">Ahlan wa Sahlan, Calon Peserta Didik Baru</p>

            <p class="mt-3 flex items-start gap-2.5 text-[10px] font-semibold uppercase leading-5 tracking-[0.14em] text-slate-300 sm:items-center sm:gap-3 sm:text-[11px] sm:tracking-[0.16em] md:text-xs">
              <span class="mt-2 h-px w-6 shrink-0 bg-amber-400/70 sm:mt-0 sm:w-8" aria-hidden="true"></span>
              <span class="min-w-0">SPMB 2027/2028 &bull; Hanya 1 Gelombang (Tanpa Gelombang 2)</span>
            </p>

            <h1 class="hero-title mt-4 text-3xl font-extrabold text-white sm:text-4xl lg:text-5xl">
              Mulai perjalanan belajar anak di
              <span class="text-gold-400">Cahaya Ilmu</span>.
            </h1>
            <p class="mt-5 max-w-xl text-sm leading-7 text-slate-200/80 sm:mt-6 sm:text-base">
              Kami membuka pendaftaran siswa baru untuk jenjang KB-TK, SD, SMP, dan SMA.
              Dapatkan lingkungan belajar Islami yang hangat, guru yang peduli, dan
              program akademik yang menumbuhkan potensi anak.
              <span class="mt-2 block font-medium italic text-amber-300">&ldquo;{{ $motto }}&rdquo;</span>
            </p>

            <p class="mt-3 text-[11px] font-medium leading-6 text-slate-200/65 sm:text-xs">
              Penyelenggara: <span class="break-words">{{ $foundationName }}</span>
            </p>

            <div class="mt-7 flex flex-col flex-wrap gap-3 sm:mt-8 sm:flex-row">
              <a href="#formulir" class="btn btn-gold w-full sm:w-auto">
                Isi Formulir Pendaftaran
                <i class="fa-solid fa-arrow-down text-xs" aria-hidden="true"></i>
              </a>
              <a href="{{ route('ppdb.status') }}" class="btn btn-ghost w-full sm:w-auto">
                <i class="fa-solid fa-magnifying-glass-chart text-base" aria-hidden="true"></i>
                Cek Status &amp; Cetak Kartu
              </a>
              <a href="{{ url('/#kontak') }}" class="btn btn-ghost w-full sm:w-auto">
                <i class="fa-solid fa-address-book text-base" aria-hidden="true"></i>
                Lihat Kontak Admin per Jenjang
              </a>
            </div>

            <div class="mt-7 grid max-w-lg grid-cols-3 gap-2 border-t border-white/10 pt-5 sm:mt-9 sm:gap-3 sm:pt-6">
              <div>
                <p class="text-lg font-extrabold text-white sm:text-2xl">4</p>
                <p class="mt-1 text-[9px] font-semibold leading-4 text-slate-200/60 sm:text-[10px]">Jenjang pendidikan</p>
              </div>
              <div class="border-l border-white/10 pl-3 sm:pl-4">
                <p class="text-lg font-extrabold text-white sm:text-2xl">1.250+</p>
                <p class="mt-1 text-[9px] font-semibold leading-4 text-slate-200/60 sm:text-[10px]">Siswa aktif</p>
              </div>
              <div class="border-l border-white/10 pl-3 sm:pl-4">
                <p class="text-lg font-extrabold text-white sm:text-2xl">12+</p>
                <p class="mt-1 text-[9px] font-semibold leading-4 text-slate-200/60 sm:text-[10px]">Tahun mendidik</p>
              </div>
            </div>
          </div>

          <!-- Kartu jadwal penting -->
          @php
            $ppdbEarliestStart = collect($ppdbLevels)->pluck('start')->filter()->sortBy(fn ($date) => $date->timestamp)->first();
            $ppdbLatestEnd = collect($ppdbLevels)->pluck('end')->filter()->sortBy(fn ($date) => $date->timestamp)->last();
          @endphp
          <div class="page-hero-card reveal">
            <div class="flex items-start justify-between gap-4">
              <div>
                <p class="text-[10px] font-extrabold uppercase tracking-[0.18em] text-gold-300">Jadwal Penting</p>
                <h2 class="mt-2 text-xl font-extrabold text-white sm:text-2xl">SPMB {{ $settings['tahun_ajaran'] ?? '2026/2027' }}</h2>
              </div>
              <span class="shrink-0 rounded-full border px-2.5 py-1 text-[8px] font-extrabold uppercase tracking-[0.12em] sm:px-3 sm:py-1.5 sm:text-[9px] sm:tracking-[0.14em] {{ $ppdbOpen ? 'border-gold-400/30 bg-gold-400/10 text-gold-300' : 'border-slate-400/30 bg-slate-400/10 text-slate-300' }}">{{ $ppdbOpen ? 'Dibuka' : 'Ditutup' }}</span>
            </div>

            <div class="mt-5">
              <div class="page-hero-card-row">
                <span class="text-slate-200/60">Gelombang Pendaftaran</span>
                <span class="text-right font-extrabold text-amber-300">Hanya 1 Gelombang</span>
              </div>
              <div class="page-hero-card-row">
                <span class="text-slate-200/60">Waktu Pendaftaran</span>
                <span class="text-right font-extrabold text-white">
                  @if ($ppdbEarliestStart && $ppdbLatestEnd)
                    {{ $ppdbEarliestStart->locale('id')->isoFormat('D MMM') }} &ndash; {{ $ppdbLatestEnd->locale('id')->isoFormat('D MMM YYYY') }}
                  @else
                    Mulai 7 Des 2026 / Sesuai Jenjang
                  @endif
                </span>
              </div>
              <div class="page-hero-card-row">
                <span class="text-slate-200/60">Pengumpulan berkas &amp; observasi</span>
                <span class="text-right font-extrabold text-white">Sesuai jadwal grup WA SPMB</span>
              </div>
              <div class="page-hero-card-row">
                <span class="text-slate-200/60">Wawancara orang tua</span>
                <span class="text-right font-extrabold text-white">Bersamaan sesi observasi</span>
              </div>
              <div class="page-hero-card-row">
                <span class="text-slate-200/60">Pengumuman &amp; daftar ulang</span>
                <span class="text-right font-extrabold text-white">Dikonfirmasi via WhatsApp</span>
              </div>
            </div>

            <a href="#formulir" class="btn btn-gold mt-6 w-full">Daftar Sekarang <i class="fa-solid fa-arrow-right text-xs"></i></a>
            <p class="mt-3 text-center text-[10px] leading-5 text-slate-200/50">Kuota terbatas untuk setiap jenjang.</p>
          </div>
        </div>
      </div>
    </section>
    <!-- AKHIR SECTION HERO SPMB -->

    <!-- ============================================================
         AWAL SECTION RINGKASAN SPMB
         ============================================================ -->
    <section class="relative z-20 -mt-10 pb-2" aria-label="Ringkasan informasi SPMB">
      <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="grid gap-4 rounded-3xl border border-slate-100 bg-white p-5 shadow-xl shadow-slate-900/5 sm:grid-cols-2 sm:p-6 lg:grid-cols-4">

          <div class="ppdb-info-card">
            <span class="ppdb-info-icon"><i class="fa-solid fa-school" aria-hidden="true"></i></span>
            <h2 class="mt-4 text-sm font-extrabold text-slate-900">4 Jenjang Pendidikan</h2>
            <p class="mt-2 text-xs leading-6 text-slate-500">TK, SD, SMP, dan SMA dengan kurikulum yang berkesinambungan.</p>
          </div>

          <div class="ppdb-info-card">
            <span class="ppdb-info-icon ppdb-info-icon--gold"><i class="fa-regular fa-calendar-days" aria-hidden="true"></i></span>
            <h2 class="mt-4 text-sm font-extrabold text-slate-900">Jadwal per Jenjang</h2>
            <p class="mt-2 text-xs leading-6 text-slate-500">Status dan jadwal pendaftaran setiap jenjang dapat berbeda. Lihat kartu jadwal di bagian formulir.</p>
          </div>

          <div class="ppdb-info-card">
            <span class="ppdb-info-icon"><i class="fa-solid fa-users" aria-hidden="true"></i></span>
            <h2 class="mt-4 text-sm font-extrabold text-slate-900">Kuota Terbatas</h2>
            <p class="mt-2 text-xs leading-6 text-slate-500">Setiap jenjang memiliki daya tampung terbatas. Daftar lebih awal.</p>
          </div>

          <div class="ppdb-info-card">
            <span class="ppdb-info-icon ppdb-info-icon--gold"><i class="fa-solid fa-heart" aria-hidden="true"></i></span>
            <h2 class="mt-4 text-sm font-extrabold text-slate-900">Ramah Anak</h2>
            <p class="mt-2 text-xs leading-6 text-slate-500">Observasi dan wawancara dirancang nyaman untuk calon siswa.</p>
          </div>

        </div>
      </div>
    </section>
    <!-- AKHIR SECTION RINGKASAN SPMB -->

    <!-- ============================================================
         AWAL SECTION ALUR PENDAFTARAN
         ============================================================ -->
    <section id="alur" class="section-pad bg-white">
      <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="section-heading section-heading--center reveal">
          <span class="eyebrow">Alur Pendaftaran SPMB</span>
          <h2 class="section-title mt-4">Empat langkah mudah menjadi keluarga Cahaya Ilmu.</h2>
          <p class="section-lead mx-auto">
            Proses pendaftaran dirancang sederhana dan transparan. Tim SPMB akan mendampingi
            Anda pada setiap tahap.
          </p>
        </div>

        <div class="mt-10 grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
          <div class="timeline-step reveal">
            <div class="flex items-start justify-between gap-3">
              <span class="timeline-number">01</span>
              <span class="grid h-10 w-10 place-items-center rounded-xl bg-brand-50 text-brand-700" aria-hidden="true">
                <i class="fa-regular fa-pen-to-square"></i>
              </span>
            </div>
            <h3 class="mt-5 text-base font-extrabold text-slate-900">Isi Formulir</h3>
            <p class="mt-2 text-sm leading-6 text-slate-600">Lengkapi formulir pendaftaran online melalui halaman ini.</p>
          </div>
          <div class="timeline-step reveal">
            <div class="flex items-start justify-between gap-3">
              <span class="timeline-number">02</span>
              <span class="grid h-10 w-10 place-items-center rounded-xl bg-gold-50 text-gold-600" aria-hidden="true">
                <i class="fa-brands fa-whatsapp"></i>
              </span>
            </div>
            <h3 class="mt-5 text-base font-extrabold text-slate-900">Konfirmasi WA</h3>
            <p class="mt-2 text-sm leading-6 text-slate-600">Data terkirim ke WhatsApp panitia, lalu Anda menerima konfirmasi.</p>
          </div>
          <div class="timeline-step reveal">
            <div class="flex items-start justify-between gap-3">
              <span class="timeline-number">03</span>
              <span class="grid h-10 w-10 place-items-center rounded-xl bg-brand-50 text-brand-700" aria-hidden="true">
                <i class="fa-solid fa-clipboard-check"></i>
              </span>
            </div>
            <h3 class="mt-5 text-base font-extrabold text-slate-900">Observasi / Tes</h3>
            <p class="mt-2 text-sm leading-6 text-slate-600">Calon siswa mengikuti observasi ramah anak sesuai jenjangnya.</p>
          </div>
          <div class="timeline-step reveal">
            <div class="flex items-start justify-between gap-3">
              <span class="timeline-number">04</span>
              <span class="grid h-10 w-10 place-items-center rounded-xl bg-gold-50 text-gold-600" aria-hidden="true">
                <i class="fa-solid fa-user-check"></i>
              </span>
            </div>
            <h3 class="mt-5 text-base font-extrabold text-slate-900">Daftar Ulang</h3>
            <p class="mt-2 text-sm leading-6 text-slate-600">Setelah dinyatakan diterima, selesaikan administrasi daftar ulang.</p>
          </div>
        </div>
      </div>
    </section>
    <!-- AKHIR SECTION ALUR PENDAFTARAN -->

    <!-- ============================================================
         AWAL SECTION SYARAT PENDAFTARAN (TAB FILTER)
         ============================================================ -->
    <section id="syarat" class="section-pad bg-slate-50">
      <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="section-heading section-heading--center reveal">
          <span class="eyebrow">Syarat Pendaftaran</span>
          <h2 class="section-title mt-4">Persyaratan sesuai jenjang pendidikan.</h2>
          <p class="section-lead mx-auto">
            Pilih jenjang di bawah untuk melihat dokumen dan ketentuan yang perlu disiapkan.
            Panitia akan mengonfirmasi kelengkapan berkas melalui WhatsApp.
          </p>
        </div>

        <div class="mt-8 flex flex-wrap justify-center gap-2.5 reveal" role="tablist" aria-label="Filter syarat pendaftaran berdasarkan jenjang">
          <button id="tab-tk" type="button" class="jenjang-tab syarat-tab" role="tab" aria-selected="true" aria-controls="panel-tk" data-jenjang="tk">
            <span class="jenjang-tab-icon"><img src="/assets/images/logo-tk.png" alt="Logo KB-TK Islam Cahaya Ilmu" class="h-8 w-auto max-w-[2rem] object-contain"></span>
            <span class="text-left">
              <span class="block text-sm font-extrabold">KB-TK</span>
              <span class="block text-[10px] font-semibold opacity-70">KB 3,5 &middot; TK A 4,5 &middot; TK B 5,5 th</span>
            </span>
          </button>
          <button id="tab-sd" type="button" class="jenjang-tab syarat-tab" role="tab" aria-selected="false" aria-controls="panel-sd" data-jenjang="sd" tabindex="-1">
            <span class="jenjang-tab-icon"><img src="/assets/images/logo-sd.png" alt="Logo SD Islam Cahaya Ilmu" class="h-8 w-auto max-w-[2rem] object-contain"></span>
            <span class="text-left">
              <span class="block text-sm font-extrabold">SD</span>
              <span class="block text-[10px] font-semibold opacity-70">Min. 6 tahun 3 bulan</span>
            </span>
          </button>
          <button id="tab-smp" type="button" class="jenjang-tab syarat-tab" role="tab" aria-selected="false" aria-controls="panel-smp" data-jenjang="smp" tabindex="-1">
            <span class="jenjang-tab-icon"><img src="/assets/images/logo-smp.png" alt="Logo SMP Islam Cahaya Ilmu" class="h-8 w-auto max-w-[2rem] object-contain"></span>
            <span class="text-left">
              <span class="block text-sm font-extrabold">SMP</span>
              <span class="block text-[10px] font-semibold opacity-70">Usia 12&ndash;15 tahun</span>
            </span>
          </button>
          <button id="tab-sma" type="button" class="jenjang-tab syarat-tab" role="tab" aria-selected="false" aria-controls="panel-sma" data-jenjang="sma" tabindex="-1">
            <span class="jenjang-tab-icon"><img src="/assets/images/logo-sma.png" alt="Logo SMA Islam Cahaya Ilmu" class="h-8 w-auto max-w-[2rem] object-contain"></span>
            <span class="text-left">
              <span class="block text-sm font-extrabold">SMA</span>
              <span class="block text-[10px] font-semibold opacity-70">Usia 15&ndash;18 tahun</span>
            </span>
          </button>
        </div>

        <div class="reveal">
          <!-- Panel syarat KB-TK -->
          <div id="panel-tk" class="tab-panel mt-7" role="tabpanel" aria-labelledby="tab-tk" tabindex="0">
            <div class="overflow-hidden rounded-[1.5rem] border border-slate-100 bg-white shadow-sm">
              <div class="flex flex-wrap items-center justify-between gap-4 border-b border-slate-100 px-5 py-5 sm:px-6">
                <div class="flex items-center gap-3">
                  <span class="ppdb-info-icon"><i class="fa-solid fa-shapes" aria-hidden="true"></i></span>
                  <div>
                    <p class="text-[10px] font-extrabold uppercase tracking-[0.16em] text-brand-700">Jenjang KB-TK &middot; SPMB {{ $spmbTk['school_year'] }}</p>
                    <h3 class="mt-1 text-lg font-extrabold text-slate-900">{{ $spmbTk['name'] }}</h3>
                    <p class="text-xs font-semibold text-slate-500">{{ $spmbTk['curriculum'] }}</p>
                  </div>
                </div>
                <span class="inline-flex items-center gap-2 rounded-full bg-brand-50 px-3 py-1.5 text-[10px] font-extrabold text-brand-700">
                  <i class="fa-solid fa-wallet" aria-hidden="true"></i> Biaya Pendaftaran Rp 250.000
                </span>
              </div>

              <div class="border-b border-slate-100 bg-brand-50/60 px-5 py-4 text-xs leading-6 text-brand-900 sm:px-6">
                <p>
                  <i class="fa-regular fa-clock mr-1.5" aria-hidden="true"></i>
                  <strong>Waktu Pendaftaran Online:</strong> mulai {{ $spmbTk['registration']['start_label'] }}, pukul {{ $spmbTk['registration']['hours'] }}.
                  {{ $spmbTk['registration']['event'] }} {{ $spmbTk['registration']['note'] }}
                </p>
              </div>

              <div class="grid gap-6 p-5 sm:p-6 md:grid-cols-2">
                <div>
                  <h4 class="flex items-center gap-2 text-xs font-extrabold uppercase tracking-[0.12em] text-slate-500">
                    <i class="fa-solid fa-child-reaching text-brand-600" aria-hidden="true"></i> Kelompok &amp; Syarat Usia Minimal (Juni {{ $targetSchoolYear }})
                  </h4>
                  <div class="mt-4 overflow-hidden rounded-xl border border-slate-200">
                    <table class="w-full text-left text-xs">
                      <thead class="bg-slate-50 text-[10px] font-extrabold uppercase tracking-wider text-slate-500">
                        <tr>
                          <th class="px-3 py-2.5">Kelompok</th>
                          <th class="px-3 py-2.5">Usia Minimal</th>
                          <th class="px-3 py-2.5">Lahir Maksimal</th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach ($spmbTk['groups'] as $groupKey => $group)
                          <tr class="border-t border-slate-100">
                            <td class="px-3 py-2.5 font-extrabold text-slate-800">{{ $groupKey }}</td>
                            <td class="px-3 py-2.5 text-slate-600">{{ $group['min_age_label'] }}</td>
                            <td class="px-3 py-2.5 text-slate-600">{{ $group['cutoff_label'] }}</td>
                          </tr>
                        @endforeach
                      </tbody>
                    </table>
                  </div>
                  <p class="mt-3 text-[11px] leading-5 text-slate-500">
                    Usia dihitung per bulan Juni {{ $targetSchoolYear }}. Pilih kelompok yang sesuai saat mengisi formulir; sistem akan mengingatkan bila usia ananda belum memenuhi syarat.
                  </p>
                </div>

                <div>
                  <h4 class="flex items-center gap-2 text-xs font-extrabold uppercase tracking-[0.12em] text-slate-500">
                    <i class="fa-regular fa-folder-open text-brand-600" aria-hidden="true"></i> Dokumen yang disiapkan
                  </h4>
                  <ul class="mt-4 space-y-3">
                    @foreach ($spmbTk['documents'] as $document)
                      <li class="requirement-item"><i class="fa-solid fa-circle-check"></i><span>{{ $document }}</span></li>
                    @endforeach
                  </ul>
                  <p class="mt-3 text-[11px] leading-5 text-slate-500">
                    Bukti transfer tidak diperlukan bila ananda memperoleh kode referral Event Muslim Vibes (gratis biaya pendaftaran).
                  </p>
                </div>
              </div>

              <div class="border-t border-slate-100 px-5 py-5 sm:px-6">
                @include('partials.spmb-tk-fee-detail', ['spmbTk' => $spmbTk])
              </div>

              <div class="border-t border-slate-100 px-5 py-5 sm:px-6">
                <h4 class="flex items-center gap-2 text-xs font-extrabold uppercase tracking-[0.12em] text-slate-500">
                  <i class="fa-solid fa-list-check text-gold-600" aria-hidden="true"></i> Alur SPMB {{ $spmbTk['name'] }}
                </h4>
                <ol class="mt-4 grid gap-3 sm:grid-cols-2">
                  @foreach ($spmbTk['steps'] as $index => $step)
                    <li class="flex gap-3 rounded-xl border border-slate-100 bg-slate-50/70 p-3">
                      <span class="grid h-7 w-7 shrink-0 place-items-center rounded-full bg-brand-600 text-[11px] font-extrabold text-white">{{ $index + 1 }}</span>
                      <span class="text-[11px] font-semibold leading-5 text-slate-600">{{ $step }}</span>
                    </li>
                  @endforeach
                </ol>
              </div>

              <div class="grid gap-5 border-t border-slate-100 px-5 py-5 sm:px-6 md:grid-cols-2">
                <div class="rounded-xl border border-brand-100 bg-brand-50 p-4">
                  <h4 class="flex items-center gap-2 text-[10px] font-extrabold uppercase tracking-[0.14em] text-brand-700">
                    <i class="fa-solid fa-building-columns" aria-hidden="true"></i> Biaya Pendaftaran &amp; Rekening Resmi
                  </h4>
                  <p class="mt-2 text-lg font-extrabold text-brand-900">Rp {{ number_format($spmbTk['fee'], 0, ',', '.') }}</p>
                  <dl class="mt-2 space-y-1 text-xs text-brand-900">
                    <div><dt class="inline font-bold">Bank:</dt> <dd class="inline">{{ $spmbTk['bank'] }}</dd></div>
                    <div><dt class="inline font-bold">No. Rekening:</dt> <dd class="inline font-extrabold tracking-wide">{{ $spmbTk['account_number'] }}</dd></div>
                    <div><dt class="inline font-bold">Atas Nama:</dt> <dd class="inline">{{ $spmbTk['account_name'] }}</dd></div>
                  </dl>
                </div>
                <div class="rounded-xl border border-gold-100 bg-gold-50 p-4">
                  <h4 class="flex items-center gap-2 text-[10px] font-extrabold uppercase tracking-[0.14em] text-gold-700">
                    <i class="fa-solid fa-gift" aria-hidden="true"></i> Kode Referral &amp; Promo KB-TK
                  </h4>
                  <ul class="mt-3 space-y-3 text-xs leading-5 text-gold-900">
                    <li>
                      <p class="font-extrabold">Event Muslim Vibes — Gratis Biaya Pendaftaran Rp 250.000</p>
                      <p class="text-gold-800/80">Tersedia 10 kode spesial <strong>CI26-...</strong>, masing-masing hanya berlaku untuk 1 pendaftar.</p>
                    </li>
                  </ul>
                  <p class="mt-3 text-[10px] font-bold text-gold-700">Masukkan kode pada formulir, lalu klik “Cek Kode” sebelum mengirim pendaftaran.</p>
                </div>
              </div>

              <div class="flex flex-wrap items-center gap-3 border-t border-slate-100 bg-slate-50 px-5 py-4 sm:px-6">
                <a href="https://wa.me/{{ $spmbTk['whatsapp'] }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2 rounded-lg bg-emerald-600 px-3 py-2 text-[11px] font-extrabold text-white transition hover:bg-emerald-700">
                  <i class="fa-brands fa-whatsapp" aria-hidden="true"></i> WA Admin {{ $spmbTk['whatsapp_display'] }}
                </a>
                @foreach ($spmbTk['social_links'] as $social => $socialUrl)
                  <a href="{{ $socialUrl }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2 rounded-lg border border-slate-200 bg-white px-3 py-2 text-[11px] font-bold text-slate-600 transition hover:border-brand-300 hover:text-brand-700">
                    <i class="fa-brands fa-{{ $social }}" aria-hidden="true"></i> {{ $spmbTk['social_handle'] }}
                  </a>
                @endforeach
              </div>
            </div>
          </div>

          <!-- Panel syarat SD -->
          <div id="panel-sd" class="tab-panel mt-7" role="tabpanel" aria-labelledby="tab-sd" tabindex="0" hidden>
            <div class="overflow-hidden rounded-[1.5rem] border border-slate-100 bg-white shadow-sm">
              <div class="flex flex-wrap items-center justify-between gap-4 border-b border-slate-100 px-5 py-5 sm:px-6">
                <div class="flex items-center gap-3">
                  <span class="ppdb-info-icon ppdb-info-icon--gold"><i class="fa-solid fa-book-open-reader" aria-hidden="true"></i></span>
                  <div>
                    <p class="text-[10px] font-extrabold uppercase tracking-[0.16em] text-brand-700">Jenjang SD</p>
                    <h3 class="mt-1 text-lg font-extrabold text-slate-900">SD Islam Cahaya Ilmu</h3>
                  </div>
                </div>
                <span class="inline-flex items-center gap-2 rounded-full bg-gold-50 px-3 py-1.5 text-[10px] font-extrabold text-gold-700">
                  <i class="fa-regular fa-calendar" aria-hidden="true"></i> Min. 6 tahun 3 bulan (kelahiran maks. 31 Maret)
                </span>
              </div>
              <div class="grid gap-6 p-5 sm:p-6 md:grid-cols-2">
                <div>
                  <h4 class="flex items-center gap-2 text-xs font-extrabold uppercase tracking-[0.12em] text-slate-500">
                    <i class="fa-regular fa-folder-open text-brand-600" aria-hidden="true"></i> Dokumen yang dikumpulkan saat observasi
                  </h4>
                  <ul class="mt-4 space-y-3">
                    <li class="requirement-item"><i class="fa-solid fa-circle-check"></i><span>Fotokopi Kartu Keluarga (KK)</span></li>
                    <li class="requirement-item"><i class="fa-solid fa-circle-check"></i><span>Fotokopi KTP orang tua/wali</span></li>
                    <li class="requirement-item"><i class="fa-solid fa-circle-check"></i><span>Fotokopi akta kelahiran</span></li>
                    <li class="requirement-item"><i class="fa-solid fa-circle-check"></i><span>Fotokopi ijazah TK / rapor PAUD (jika ada)</span></li>
                    <li class="requirement-item"><i class="fa-solid fa-circle-check"></i><span>Foto terbaru ukuran 3&times;4 (2 lembar)</span></li>
                    <li class="requirement-item"><i class="fa-solid fa-circle-check"></i><span>Surat keterangan pindah, bila siswa pindahan</span></li>
                  </ul>
                </div>
                <div>
                  <h4 class="flex items-center gap-2 text-xs font-extrabold uppercase tracking-[0.12em] text-slate-500">
                    <i class="fa-solid fa-list-check text-gold-600" aria-hidden="true"></i> Ketentuan pendaftaran
                  </h4>
                  <ul class="mt-4 space-y-3">
                    <li class="requirement-item"><i class="fa-solid fa-circle-check"></i><span>Minimal berusia 6 tahun 3 bulan per 1 Juli (kelahiran maksimal 31 Maret).</span></li>
                    <li class="requirement-item"><i class="fa-solid fa-circle-check"></i><span>Mengisi formulir pendaftaran online &amp; membayar biaya pendaftaran.</span></li>
                    <li class="requirement-item"><i class="fa-solid fa-circle-check"></i><span>Mengikuti seleksi observasi dan wawancara sesuai waktu yang dijadwalkan.</span></li>
                    <li class="requirement-item"><i class="fa-solid fa-circle-check"></i><span>Bersedia mematuhi peraturan sekolah.</span></li>
                    <li class="requirement-item"><i class="fa-solid fa-circle-check"></i><span>Menandatangani surat pernyataan dan kesepakatan di atas materai.</span></li>
                  </ul>
                </div>
              </div>
              <div class="border-t border-slate-100 bg-slate-50 px-5 py-4 text-xs leading-6 text-slate-500 sm:px-6">
                <i class="fa-solid fa-circle-info mr-1.5 text-brand-600" aria-hidden="true"></i>
                Pastikan nomor WhatsApp yang diinput saat pendaftaran selalu aktif untuk koordinasi grup WhatsApp SPMB dan jadwal observasi.
              </div>
            </div>
          </div>

          <!-- Panel syarat SMP -->
          <div id="panel-smp" class="tab-panel mt-7" role="tabpanel" aria-labelledby="tab-smp" tabindex="0" hidden>
            <div class="overflow-hidden rounded-[1.5rem] border border-slate-100 bg-white shadow-sm">
              <div class="flex flex-wrap items-center justify-between gap-4 border-b border-slate-100 px-5 py-5 sm:px-6">
                <div class="flex items-center gap-3">
                  <span class="ppdb-info-icon"><i class="fa-solid fa-users" aria-hidden="true"></i></span>
                  <div>
                    <p class="text-[10px] font-extrabold uppercase tracking-[0.16em] text-brand-700">Jenjang SMP</p>
                    <h3 class="mt-1 text-lg font-extrabold text-slate-900">SMP Islam Cahaya Ilmu</h3>
                  </div>
                </div>
                <span class="inline-flex items-center gap-2 rounded-full bg-brand-50 px-3 py-1.5 text-[10px] font-extrabold text-brand-700">
                  <i class="fa-regular fa-calendar" aria-hidden="true"></i> Usia 12&ndash;15 tahun
                </span>
              </div>
              <div class="grid gap-6 p-5 sm:p-6 md:grid-cols-2">
                <div>
                  <h4 class="flex items-center gap-2 text-xs font-extrabold uppercase tracking-[0.12em] text-slate-500">
                    <i class="fa-regular fa-folder-open text-brand-600" aria-hidden="true"></i> Dokumen yang disiapkan
                  </h4>
                  <ul class="mt-4 space-y-3">
                    <li class="requirement-item"><i class="fa-solid fa-circle-check"></i><span>Fotokopi Kartu Keluarga (KK)</span></li>
                    <li class="requirement-item"><i class="fa-solid fa-circle-check"></i><span>Fotokopi akta kelahiran</span></li>
                    <li class="requirement-item"><i class="fa-solid fa-circle-check"></i><span>Fotokopi KTP orang tua/wali</span></li>
                    <li class="requirement-item"><i class="fa-solid fa-circle-check"></i><span>Foto terbaru ukuran 3&times;4 (2 lembar)</span></li>
                    <li class="requirement-item"><i class="fa-solid fa-circle-check"></i><span>Fotokopi ijazah/SKL SD/sederajat</span></li>
                    <li class="requirement-item"><i class="fa-solid fa-circle-check"></i><span>Fotokopi rapor kelas 4&ndash;6</span></li>
                    <li class="requirement-item"><i class="fa-solid fa-circle-check"></i><span>Surat keterangan pindah, bila ada</span></li>
                  </ul>
                </div>
                <div>
                  <h4 class="flex items-center gap-2 text-xs font-extrabold uppercase tracking-[0.12em] text-slate-500">
                    <i class="fa-solid fa-list-check text-gold-600" aria-hidden="true"></i> Ketentuan khusus
                  </h4>
                  <ul class="mt-4 space-y-3">
                    <li class="requirement-item"><i class="fa-solid fa-circle-check"></i><span>Lulus SD/sederajat dibuktikan dengan ijazah atau SKL.</span></li>
                    <li class="requirement-item"><i class="fa-solid fa-circle-check"></i><span>Mengikuti tes diagnostik Matematika dan Bahasa Indonesia.</span></li>
                    <li class="requirement-item"><i class="fa-solid fa-circle-check"></i><span>Calon siswa dan orang tua mengikuti wawancara.</span></li>
                    <li class="requirement-item"><i class="fa-solid fa-circle-check"></i><span>Asal sekolah wajib diisi pada formulir.</span></li>
                  </ul>
                </div>
              </div>
              <div class="border-t border-slate-100 bg-slate-50 px-5 py-4 text-xs leading-6 text-slate-500 sm:px-6">
                <i class="fa-solid fa-circle-info mr-1.5 text-brand-600" aria-hidden="true"></i>
                Tes diagnostik digunakan untuk memetakan kemampuan awal siswa, bukan semata-mata untuk seleksi.
              </div>
            </div>
          </div>

          <!-- Panel syarat SMA -->
          <div id="panel-sma" class="tab-panel mt-7" role="tabpanel" aria-labelledby="tab-sma" tabindex="0" hidden>
            <div class="overflow-hidden rounded-[1.5rem] border border-slate-100 bg-white shadow-sm">
              <div class="flex flex-wrap items-center justify-between gap-4 border-b border-slate-100 px-5 py-5 sm:px-6">
                <div class="flex items-center gap-3">
                  <span class="ppdb-info-icon ppdb-info-icon--gold"><i class="fa-solid fa-graduation-cap" aria-hidden="true"></i></span>
                  <div>
                    <p class="text-[10px] font-extrabold uppercase tracking-[0.16em] text-brand-700">Jenjang SMA &bull; Khusus Banaat (Putri)</p>
                    <h3 class="mt-1 text-lg font-extrabold text-slate-900">SMA Islam Cahaya Ilmu</h3>
                  </div>
                </div>
                <div class="flex flex-wrap items-center gap-2">
                  <span class="inline-flex items-center gap-1.5 rounded-full bg-rose-50 px-3 py-1.5 text-[10px] font-extrabold text-rose-700">
                    <i class="fa-solid fa-venus" aria-hidden="true"></i> Khusus Banaat / Putri
                  </span>
                  <span class="inline-flex items-center gap-2 rounded-full bg-gold-50 px-3 py-1.5 text-[10px] font-extrabold text-gold-700">
                    <i class="fa-regular fa-calendar" aria-hidden="true"></i> Usia 15&ndash;18 tahun
                  </span>
                </div>
              </div>
              <div class="grid gap-6 p-5 sm:p-6 md:grid-cols-2">
                <div>
                  <h4 class="flex items-center gap-2 text-xs font-extrabold uppercase tracking-[0.12em] text-slate-500">
                    <i class="fa-regular fa-folder-open text-brand-600" aria-hidden="true"></i> Dokumen yang disiapkan
                  </h4>
                  <ul class="mt-4 space-y-3">
                    <li class="requirement-item"><i class="fa-solid fa-circle-check"></i><span>Fotokopi Akta Kelahiran (2 lembar)</span></li>
                    <li class="requirement-item"><i class="fa-solid fa-circle-check"></i><span>Fotokopi Kartu Keluarga / KK (2 lembar)</span></li>
                    <li class="requirement-item"><i class="fa-solid fa-circle-check"></i><span>Fotokopi KTP Orang Tua (2 lembar)</span></li>
                    <li class="requirement-item"><i class="fa-solid fa-circle-check"></i><span>Fotokopi Rapor Kelas 8 Semester Genap</span></li>
                    <li class="requirement-item"><i class="fa-solid fa-circle-check"></i><span>Materai 10.000 (3 lembar)</span></li>
                  </ul>
                </div>
                <div>
                  <h4 class="flex items-center gap-2 text-xs font-extrabold uppercase tracking-[0.12em] text-slate-500">
                    <i class="fa-solid fa-list-check text-gold-600" aria-hidden="true"></i> Ketentuan khusus
                  </h4>
                  <ul class="mt-4 space-y-3">
                    <li class="requirement-item"><i class="fa-solid fa-circle-check"></i><span>Dikhususkan untuk calon peserta didik banaat (putri / perempuan).</span></li>
                    <li class="requirement-item"><i class="fa-solid fa-circle-check"></i><span>Lulus SMP/sederajat dibuktikan dengan ijazah atau SKL.</span></li>
                    <li class="requirement-item"><i class="fa-solid fa-circle-check"></i><span>Mengikuti tes potensi akademik dan wawancara.</span></li>
                    <li class="requirement-item"><i class="fa-solid fa-circle-check"></i><span>Ekstrakurikuler yang tersedia adalah Multimedia.</span></li>
                    <li class="requirement-item"><i class="fa-solid fa-circle-check"></i><span>Asal sekolah wajib diisi pada formulir.</span></li>
                  </ul>
                </div>
              </div>
              <div class="border-t border-slate-100 bg-slate-50 px-5 py-4 text-xs leading-6 text-slate-500 sm:px-6">
                <i class="fa-solid fa-circle-info mr-1.5 text-brand-600" aria-hidden="true"></i>
                Jenjang SMA Islam Cahaya Ilmu hanya menerima pendaftaran calon siswi banaat (putri).
              </div>
            </div>
          </div>
        </div>

        <!-- Gambaran Umum Materi Observasi & Wawancara Orang Tua -->
        <div class="mt-8 grid gap-5 md:grid-cols-2 reveal">
          <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
            <div class="flex items-center gap-3">
              <span class="grid h-10 w-10 shrink-0 place-items-center rounded-xl bg-brand-50 text-brand-700">
                <i class="fa-solid fa-child-reaching" aria-hidden="true"></i>
              </span>
              <div>
                <p class="text-[10px] font-extrabold uppercase tracking-[0.14em] text-brand-700">Seleksi Calon Murid</p>
                <h3 class="text-base font-extrabold text-slate-900">Materi Observasi Calon Peserta Didik</h3>
              </div>
            </div>
            <p class="mt-3 text-xs leading-6 text-slate-600">
              Observasi dilakukan secara klasikal maupun individu dengan pendekatan sederhana sesuai usia dan nalar anak (perpaduan kurikulum khas Cahaya Ilmu &amp; Dinas Pendidikan) untuk memetakan kematangan kognitif serta sosial-emosional:
            </p>
            <ul class="mt-3 space-y-2 text-xs text-slate-700">
              <li class="flex items-start gap-2"><i class="fa-solid fa-check mt-1 text-brand-600"></i><span>Pengetahuan dasar baca, tulis, dan hitung sesuai jenjang</span></li>
              <li class="flex items-start gap-2"><i class="fa-solid fa-check mt-1 text-brand-600"></i><span>Pengenalan dasar bacaan dan huruf hijaiyyah</span></li>
              <li class="flex items-start gap-2"><i class="fa-solid fa-check mt-1 text-brand-600"></i><span>Eksplorasi kemampuan motorik halus dan kasar</span></li>
              <li class="flex items-start gap-2"><i class="fa-solid fa-check mt-1 text-brand-600"></i><span>Eksplorasi kesiapan dan sikap sosial-emosional anak</span></li>
            </ul>
          </div>

          <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
            <div class="flex items-center gap-3">
              <span class="grid h-10 w-10 shrink-0 place-items-center rounded-xl bg-gold-50 text-gold-700">
                <i class="fa-solid fa-handshake-angle" aria-hidden="true"></i>
              </span>
              <div>
                <p class="text-[10px] font-extrabold uppercase tracking-[0.14em] text-gold-700">Sinergi Pendidikan</p>
                <h3 class="text-base font-extrabold text-slate-900">Wawancara Orang Tua / Wali</h3>
              </div>
            </div>
            <p class="mt-3 text-xs leading-6 text-slate-600">
              Wawancara orang tua merupakan upaya membangun sinergitas yang erat antara sekolah, rumah, dan lingkungan terkait pola asuh serta pola didik ananda, meliputi:
            </p>
            <ul class="mt-3 space-y-2 text-xs text-slate-700">
              <li class="flex items-start gap-2"><i class="fa-solid fa-check mt-1 text-gold-600"></i><span>Kesepahaman manhaj, prinsip beragama, dan adab Islami</span></li>
              <li class="flex items-start gap-2"><i class="fa-solid fa-check mt-1 text-gold-600"></i><span>Praktik pembiasaan ibadah dan akhlak di rumah serta lingkungan</span></li>
              <li class="flex items-start gap-2"><i class="fa-solid fa-check mt-1 text-gold-600"></i><span>Komitmen orang tua dalam mendampingi dan mengawal pendidikan ananda</span></li>
              <li class="flex items-start gap-2"><i class="fa-solid fa-check mt-1 text-gold-600"></i><span>Kesiapan mematuhi tata tertib serta kesepakatan sekolah</span></li>
            </ul>
          </div>
        </div>

        <div class="mt-6 flex flex-col items-center justify-between gap-4 rounded-2xl border border-brand-100 bg-brand-50 px-5 py-4 text-left sm:flex-row">
          <p class="flex items-start gap-2 text-xs leading-6 text-brand-800/80">
            <i class="fa-solid fa-circle-info mt-1 shrink-0 text-brand-700" aria-hidden="true"></i>
            <span>Seluruh dokumen dibawa dalam bentuk fotokopi saat observasi. Pastikan nomor WhatsApp yang didaftarkan selalu aktif untuk menerima informasi jadwal observasi dari panitia.</span>
          </p>
          <a href="#formulir" class="btn btn-primary w-full shrink-0 sm:w-auto">Isi Formulir <i class="fa-solid fa-arrow-right text-[11px]"></i></a>
        </div>
      </div>
    </section>
    <!-- AKHIR SECTION SYARAT PENDAFTARAN (TAB FILTER) -->

    <!-- ============================================================
         AWAL SECTION FORMULIR SPMB
         ============================================================ -->
    <section id="formulir" class="section-pad relative overflow-hidden bg-white">
      <div class="absolute -right-32 top-20 h-80 w-80 rounded-full bg-brand-50 blur-3xl" aria-hidden="true"></div>
      <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <!-- Kartu status & jadwal SPMB per jenjang -->
        <div class="mb-4 flex flex-col gap-1 sm:flex-row sm:items-end sm:justify-between">
          <div>
            <h2 class="text-base font-extrabold text-slate-900">Ketersediaan Kuota SPMB</h2>
            <p class="mt-1 text-xs leading-6 text-slate-500">Sisa kursi Laki-laki dan Perempuan diperbarui otomatis dari data pendaftar aktif.</p>
          </div>
          <span class="text-[10px] font-extrabold uppercase tracking-[0.12em] text-brand-700">Laki-laki &amp; Perempuan</span>
        </div>
        <div class="mb-8 grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
          @foreach ($ppdbLevels as $level => $levelInfo)
            <div class="rounded-2xl border p-5 shadow-sm {{ $levelInfo['is_open'] ? 'border-brand-100 bg-white' : 'border-slate-200 bg-slate-50' }}">
              <div class="flex items-start justify-between gap-3">
                <p class="text-sm font-extrabold text-slate-900">Jenjang {{ $level }}</p>
                <span class="inline-flex shrink-0 items-center gap-1.5 rounded-full px-2.5 py-1 text-[9px] font-extrabold uppercase tracking-[0.08em] {{ $levelInfo['is_open'] ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-200 text-slate-600' }}">
                  <span class="size-1.5 rounded-full {{ $levelInfo['is_open'] ? 'bg-emerald-500' : 'bg-slate-400' }}"></span>
                  {{ $levelInfo['is_open'] ? 'Pendaftaran Dibuka' : 'Pendaftaran Ditutup' }}
                </span>
              </div>
              <p class="mt-3 flex items-start gap-2 text-[11px] leading-5 text-slate-500">
                <i class="fa-regular fa-calendar-days mt-0.5 text-brand-600" aria-hidden="true"></i>
                <span>
                  @if ($levelInfo['start'] && $levelInfo['end'])
                    {{ $levelInfo['start']->locale('id')->isoFormat('D MMM YYYY') }} &ndash; {{ $levelInfo['end']->locale('id')->isoFormat('D MMM YYYY') }}
                  @else
                    Jadwal menyusul
                  @endif
                </span>
              </p>
              <p class="mt-2 text-[11px] leading-5 {{ $levelInfo['is_open'] ? 'text-brand-700' : 'text-slate-500' }}">
                @if ($levelInfo['is_open'])
                  Biaya pendaftaran {{ $levelInfo['fee_display'] }}. Pilih jenjang ini pada formulir untuk melihat rekening pembayaran.
                @else
                  Pendaftaran jenjang ini sedang tidak dibuka. Silakan pilih jenjang lain yang masih tersedia.
                @endif
              </p>

              @php($levelQuota = $ppdbQuotas[$level] ?? null)
              @if ($levelQuota)
                <div class="mt-3 space-y-2 border-t border-slate-100 pt-3">
                  @if ($level === 'TK' && ! empty($levelQuota['groups']))
                    @foreach ($levelQuota['groups'] as $groupQuota)
                      <div class="flex items-center justify-between gap-2 text-[11px]">
                        <span class="font-semibold text-slate-600">Kelompok {{ $groupQuota['label'] }}</span>
                        @if ($groupQuota['is_full'])
                          <span class="inline-flex items-center rounded-full bg-rose-100 px-2 py-0.5 text-[9px] font-extrabold uppercase tracking-wider text-rose-700">PENUH</span>
                        @else
                          <span class="font-bold text-emerald-700">Sisa {{ number_format($groupQuota['remaining']) }} dari {{ number_format($groupQuota['quota']) }}</span>
                        @endif
                      </div>
                    @endforeach
                  @else
                    @foreach (['male', 'female'] as $genderKey)
                      @php($genderQuota = $levelQuota[$genderKey])
                      <div class="flex items-center justify-between gap-2 text-[11px]">
                        <span class="font-semibold text-slate-600">{{ $genderQuota['label'] }}</span>
                        @if ($genderQuota['is_full'])
                          <span class="inline-flex items-center rounded-full bg-rose-100 px-2 py-0.5 text-[9px] font-extrabold uppercase tracking-wider text-rose-700">PENUH</span>
                        @else
                          <span class="font-bold text-emerald-700">Sisa {{ number_format($genderQuota['remaining']) }} dari {{ number_format($genderQuota['quota']) }}</span>
                        @endif
                      </div>
                    @endforeach
                  @endif
                </div>

                @if ($levelQuota['is_full'])
                  <p class="mt-3 rounded-lg bg-rose-50 px-2.5 py-2 text-[10px] font-semibold leading-5 text-rose-700">
                    <i class="fa-solid fa-circle-exclamation mr-1" aria-hidden="true"></i>
                    @if ($level === 'TK')
                      Kuota semua kelompok KB-TK sudah PENUH.
                    @else
                      Kuota jenjang {{ $level }} sudah PENUH untuk Laki-laki dan Perempuan.
                    @endif
                  </p>
                @endif
              @endif
            </div>
          @endforeach
        </div>

        <div class="grid gap-10 lg:grid-cols-[0.8fr_1.2fr] lg:items-start lg:gap-14">

          <div class="reveal">
            <span class="eyebrow">Formulir Pendaftaran</span>
            <h2 class="section-title mt-4">Mulai dari sini, kami akan menghubungi Anda.</h2>
            <p class="mt-5 text-sm leading-7 text-slate-600">
              Isi data di samping dengan lengkap. Data akan langsung tersimpan di sistem
              sekolah, lalu Anda dapat mengirim konfirmasi ke WhatsApp admin jenjang
              yang dipilih.
            </p>

            <div class="mt-7 rounded-2xl border border-brand-100 bg-brand-50 p-5">
              <div class="flex items-start gap-3">
                <span class="grid h-10 w-10 shrink-0 place-items-center rounded-xl bg-white text-brand-700 shadow-sm">
                  <i class="fa-brands fa-whatsapp text-lg" aria-hidden="true"></i>
                </span>
                <div>
                  <h3 class="text-sm font-extrabold text-brand-900">Butuh bantuan?</h3>
                  <p class="mt-1 text-xs leading-6 text-brand-800/75">Hubungi panitia SPMB pada jam kerja, Senin&ndash;Jumat 07.00&ndash;16.00 WITA.</p>
                  <a href="{{ url('/#kontak') }}" class="mt-3 inline-flex items-center gap-2 text-xs font-extrabold text-brand-700 hover:text-brand-900">
                    Lihat Kontak Admin per Jenjang <i class="fa-solid fa-arrow-right text-[9px]"></i>
                  </a>
                </div>
              </div>
            </div>

            <div class="mt-6 rounded-2xl border border-slate-200 bg-slate-50 p-5">
              <div class="flex items-start gap-3">
                <span class="grid h-10 w-10 shrink-0 place-items-center rounded-xl bg-white text-slate-800 shadow-sm">
                  <i class="fa-solid fa-id-card-clip text-lg text-brand-600" aria-hidden="true"></i>
                </span>
                <div class="w-full">
                  <h3 class="text-sm font-extrabold text-slate-900">Sudah Mendaftar Sebelumnya?</h3>
                  <p class="mt-1 text-xs leading-6 text-slate-600">Pantau status verifikasi berkas atau cetak ulang Kartu Bukti Pendaftaran SPMB Anda.</p>
                  <a href="{{ route('ppdb.status') }}" class="mt-3 inline-flex items-center gap-2 text-xs font-extrabold text-brand-700 hover:text-brand-900">
                    Cek Status &amp; Cetak Kartu Pendaftaran <i class="fa-solid fa-arrow-right text-[9px]"></i>
                  </a>
                </div>
              </div>
            </div>

            <div class="mt-6 space-y-3">
              <div class="flex items-start gap-2.5 text-xs leading-6 text-slate-500">
                <i class="fa-solid fa-shield-halved mt-1 text-brand-600" aria-hidden="true"></i>
                <span>Data hanya digunakan untuk keperluan SPMB dan tidak dibagikan tanpa izin.</span>
              </div>
              <div class="flex items-start gap-2.5 text-xs leading-6 text-slate-500">
                <i class="fa-regular fa-clock mt-1 text-brand-600" aria-hidden="true"></i>
                <span>Panitia akan menghubungi Anda maksimal 2 hari kerja setelah formulir diterima.</span>
              </div>
            </div>
          </div>

          <div class="ppdb-form-card reveal">
            <div class="flex items-start justify-between gap-4">
              <div>
                <h3 class="text-lg font-extrabold text-slate-900">Formulir Pendaftaran Online</h3>
                <p class="mt-1 text-xs text-slate-500">Kolom bertanda <span class="font-bold text-red-500">*</span> wajib diisi.</p>
              </div>
              <span class="hidden rounded-full bg-brand-50 px-3 py-1.5 text-[9px] font-extrabold uppercase tracking-[0.12em] text-brand-700 sm:inline-flex">
                SPMB {{ $settings['tahun_ajaran'] ?? '2026/2027' }}
              </span>
            </div>

            @if (! $ppdbOpen)
              <div class="mt-5 rounded-xl border border-amber-200 bg-amber-50 p-4 text-xs leading-6 text-amber-800">
                <i class="fa-solid fa-triangle-exclamation mr-1" aria-hidden="true"></i>
                Pendaftaran SPMB saat ini berstatus <strong>Tutup</strong> untuk seluruh jenjang.
                Silakan hubungi admin SPMB atau coba kembali pada periode pendaftaran berikutnya.
              </div>
            @endif

            <form id="ppdb-form" class="mt-6" action="{{ route('ppdb.store') }}" method="POST" enctype="multipart/form-data">
              @csrf

              @if ($errors->any())
                <div id="ppdb-form-errors" tabindex="-1" role="alert" class="mb-5 scroll-mt-24 rounded-xl border border-red-200 bg-red-50 p-4 text-xs text-red-700 outline-none ring-2 ring-red-200 focus:ring-red-400">
                  <p class="font-extrabold">Periksa kembali data pendaftaran Anda:</p>
                  <ul class="mt-2 list-inside list-disc space-y-1">
                    @foreach ($errors->all() as $error)
                      <li>{{ $error }}</li>
                    @endforeach
                  </ul>
                </div>
              @endif

              @php($selectedLevel = old('level', strtoupper((string) request('jenjang'))))

              <div class="grid gap-4 sm:grid-cols-2">
                <div class="sm:col-span-2">
                  <label for="nama-siswa" class="form-label">Nama Lengkap Calon Siswa <span>*</span></label>
                  <input type="text" id="nama-siswa" name="full_name" value="{{ old('full_name') }}" class="form-input" placeholder="Contoh: Ahmad Fauzan" autocomplete="name" required>
                </div>

                <div>
                  <label for="tempat-lahir" class="form-label">Tempat Lahir <span>*</span></label>
                  <input type="text" id="tempat-lahir" name="birth_place" value="{{ old('birth_place') }}" class="form-input" placeholder="Contoh: Balikpapan" required>
                </div>

                <div>
                  <label for="tanggal-lahir" class="form-label">Tanggal Lahir <span>*</span></label>
                  <input type="date"
                         id="tanggal-lahir"
                         name="birth_date"
                         value="{{ old('birth_date') }}"
                         class="form-input"
                         max="{{ $selectedLevel === 'SD' ? $sdCutoffBirthDate : now()->toDateString() }}"
                         data-default-max="{{ now()->toDateString() }}"
                         data-sd-max="{{ $sdCutoffBirthDate }}"
                         aria-describedby="umur-sd-warning umur-sd-valid"
                         required>
                </div>

                <div id="umur-sd-warning" class="hidden rounded-lg border-l-4 border-rose-600 bg-rose-50 p-4 text-rose-800 sm:col-span-2" role="alert">
                  <div class="flex items-start gap-2.5">
                    <i class="fa-solid fa-triangle-exclamation mt-0.5 text-base" aria-hidden="true"></i>
                    <p class="text-xs font-semibold leading-6"><span id="umur-sd-warning-text"></span></p>
                  </div>
                </div>

                <div id="umur-sd-valid" class="hidden rounded-lg border-l-4 border-emerald-600 bg-emerald-50 p-4 text-emerald-800 sm:col-span-2" role="status">
                  <div class="flex items-start gap-2.5">
                    <i class="fa-solid fa-circle-check mt-0.5 text-base" aria-hidden="true"></i>
                    <p id="umur-sd-valid-text" class="text-xs font-semibold leading-6"></p>
                  </div>
                </div>

                <div>
                  <label for="jenis-kelamin" class="form-label">Jenis Kelamin <span>*</span></label>
                  <select id="jenis-kelamin" name="gender" class="form-select" required>
                    <option value="" disabled @selected(! old('gender'))>Pilih jenis kelamin</option>
                    <option value="L" @selected(old('gender') === 'L')>Laki-laki</option>
                    <option value="P" @selected(old('gender') === 'P')>Perempuan</option>
                  </select>
                  <div id="gender-quota-badges" class="mt-2 flex flex-wrap gap-1.5" aria-live="polite"></div>
                  <p id="gender-sma-hint" class="form-hint hidden text-brand-700">Jenjang SMA khusus banaat (putri / perempuan).</p>
                </div>

                <div>
                  <label for="jenjang-pilihan" class="form-label">Pilihan Jenjang <span>*</span></label>
                  <select id="jenjang-pilihan" name="level" class="form-select" required>
                    <option value="" disabled @selected(! $selectedLevel)>Pilih jenjang</option>
                    @foreach (['TK' => 'KB-TK Islam Cahaya Ilmu', 'SD' => 'SD Islam Cahaya Ilmu', 'SMP' => 'SMP Islam Cahaya Ilmu', 'SMA' => 'SMA Islam Cahaya Ilmu (Khusus Banaat / Putri)'] as $level => $levelName)
                      @php($isLevelOpen = $ppdbLevels[$level]['is_open'] ?? false)
                      <option value="{{ $level }}" @selected($selectedLevel === $level) @disabled(! $isLevelOpen)>
                        {{ $levelName }}{{ $isLevelOpen ? '' : ' — Pendaftaran Ditutup' }}
                      </option>
                    @endforeach
                  </select>
                  @if (collect($ppdbLevels)->every(fn ($level) => ! $level['is_open']))
                    <p class="form-hint text-red-600">Semua jenjang sedang ditutup. Silakan hubungi admin SPMB.</p>
                  @endif
                </div>

                <div id="sub-level-wrap" class="hidden rounded-xl border border-brand-100 bg-brand-50/60 p-4 sm:col-span-2">
                  <label for="sub-level" class="form-label">Pilih Kelompok KB-TK <span>*</span></label>
                  <select id="sub-level" name="sub_level" class="form-select" aria-describedby="sub-level-hint">
                    <option value="" disabled @selected(! old('sub_level'))>Pilih kelompok</option>
                    @foreach ($spmbTk['groups'] as $groupKey => $group)
                      <option value="{{ $groupKey }}"
                              data-base-label="{{ $group['label'] }} — min. {{ $group['min_age_label'] }} per Juni {{ $targetSchoolYear }}"
                              @selected(old('sub_level') === $groupKey)>
                        {{ $group['label'] }} &mdash; min. {{ $group['min_age_label'] }} per Juni {{ $targetSchoolYear }}
                      </option>
                    @endforeach
                  </select>
                  <p id="sub-level-hint" class="form-hint">
                    Usia minimal dihitung pada bulan Juni {{ $targetSchoolYear }}:
                    @foreach ($spmbTk['groups'] as $groupKey => $group)
                      {{ $groupKey }} {{ $group['min_age_label'] }}@if (! $loop->last), @endif
                    @endforeach.
                  </p>
                  <div id="sub-level-quota-badges" class="mt-2 flex flex-wrap gap-1.5" aria-live="polite"></div>
                  <div id="sub-level-warning" class="mt-3 hidden rounded-lg border-l-4 border-rose-600 bg-rose-50 p-3 text-rose-800" role="alert">
                    <p id="sub-level-warning-text" class="text-[11px] font-semibold leading-5"></p>
                  </div>
                </div>

                <div id="referral-tk-wrap" class="hidden rounded-xl border border-gold-100 bg-gold-50/60 p-4 sm:col-span-2">
                  <label for="referral-code" class="form-label">Kode Referral / Voucher Promo KB-TK <span class="font-normal text-slate-400">(opsional)</span></label>
                  <div class="mt-1.5 flex flex-col gap-2 sm:flex-row">
                    <input type="text"
                           id="referral-code"
                           name="referral_code"
                           value="{{ old('referral_code') }}"
                           class="form-input uppercase tracking-wider"
                           maxlength="50"
                           autocomplete="off"
                           spellcheck="false"
                           aria-describedby="referral-code-hint referral-status">
                    <button type="button"
                            id="check-referral"
                            class="inline-flex shrink-0 items-center justify-center gap-2 rounded-xl bg-brand-700 px-4 py-2.5 text-xs font-extrabold text-white transition hover:bg-brand-800 disabled:cursor-wait disabled:opacity-60">
                      <i class="fa-solid fa-ticket" aria-hidden="true"></i>
                      <span id="check-referral-text">Cek Kode</span>
                    </button>
                  </div>
                  <p id="referral-code-hint" class="form-hint">Punya kode voucher dari Event Muslim Vibes? Masukkan kodenya lalu klik Cek Kode.</p>
                  <div id="referral-status" class="mt-3 hidden rounded-lg border p-3 text-[11px] font-semibold leading-5" role="status" aria-live="polite"></div>
                  @error('referral_code')
                    <p class="mt-2 text-xs font-semibold text-rose-600">{{ $message }}</p>
                  @enderror
                </div>

                <div id="quota-detail" class="hidden rounded-xl border border-brand-100 bg-brand-50 p-4 sm:col-span-2" aria-live="polite">
                  <div class="flex flex-wrap items-center justify-between gap-2">
                    <p class="flex items-center gap-2 text-[10px] font-extrabold uppercase tracking-[0.14em] text-brand-700">
                      <i class="fa-solid fa-chair" aria-hidden="true"></i>
                      Ketersediaan Kuota <span id="quota-detail-level"></span>
                    </p>
                    <span id="quota-detail-full-badge" class="hidden rounded-full bg-rose-100 px-2.5 py-1 text-[9px] font-extrabold uppercase tracking-wider text-rose-700">Kuota Penuh</span>
                  </div>

                  <div id="quota-group-grid" class="mt-3 hidden grid gap-2 sm:grid-cols-3">
                    @foreach ($spmbTk['groups'] as $groupKey => $group)
                      @php($groupSlug = strtolower(str_replace(' ', '_', $groupKey)))
                      <div class="rounded-lg border border-brand-100 bg-white p-3">
                        <div class="flex items-center justify-between gap-2">
                          <span class="text-xs font-extrabold text-slate-800">Kelompok {{ $groupKey }}</span>
                          <span id="quota-group-{{ $groupSlug }}-status" class="text-[10px] font-extrabold text-emerald-700">-</span>
                        </div>
                        <p id="quota-group-{{ $groupSlug }}-count" class="mt-1 text-[11px] text-slate-500">Terisi: - / -</p>
                        <div class="mt-1.5 h-1.5 overflow-hidden rounded-full bg-slate-100">
                          <div id="quota-group-{{ $groupSlug }}-bar" class="h-full rounded-full bg-emerald-500" style="width: 0%"></div>
                        </div>
                      </div>
                    @endforeach
                  </div>

                  <div id="quota-gender-grid" class="mt-3 hidden grid gap-2 sm:grid-cols-2">
                    @foreach (['male' => 'Laki-laki', 'female' => 'Perempuan'] as $genderKey => $genderLabel)
                      <div class="rounded-lg border border-brand-100 bg-white p-3">
                        <div class="flex items-center justify-between gap-2">
                          <span class="text-xs font-extrabold text-slate-800">{{ $genderLabel }}</span>
                          <span id="quota-{{ $genderKey }}-status" class="text-[10px] font-extrabold text-emerald-700">-</span>
                        </div>
                        <p id="quota-{{ $genderKey }}-count" class="mt-1 text-[11px] text-slate-500">Terisi: - / -</p>
                        <div class="mt-1.5 h-1.5 overflow-hidden rounded-full bg-slate-100">
                          <div id="quota-{{ $genderKey }}-bar" class="h-full rounded-full bg-emerald-500" style="width: 0%"></div>
                        </div>
                      </div>
                    @endforeach
                  </div>
                </div>

                <div id="quota-both-full-banner" class="hidden rounded-xl border border-rose-200 bg-rose-50 p-4 text-xs leading-6 text-rose-800 sm:col-span-2" role="alert">
                  <p class="flex items-start gap-2 font-semibold">
                    <i class="fa-solid fa-circle-exclamation mt-1 shrink-0" aria-hidden="true"></i>
                    <span id="quota-both-full-text">Pendaftaran jenjang <strong id="quota-both-full-level"></strong> telah memenuhi kuota untuk Laki-laki dan Perempuan. Silakan pilih jenjang lain atau hubungi panitia SPMB melalui WhatsApp untuk informasi daftar tunggu (Waiting List).</span>
                  </p>
                </div>

                <div id="quota-full-warning" class="hidden rounded-xl border border-rose-200 bg-rose-50 p-4 text-xs leading-6 text-rose-800 sm:col-span-2" role="alert">
                  <p class="flex items-start gap-2 font-semibold">
                    <i class="fa-solid fa-triangle-exclamation mt-1 shrink-0" aria-hidden="true"></i>
                    <span id="quota-full-warning-text"></span>
                  </p>
                  <a id="quota-wa-link"
                     href="{{ url('/#kontak') }}"
                     target="_blank"
                     rel="noopener noreferrer"
                     class="mt-2 inline-flex items-center gap-2 rounded-lg bg-rose-600 px-3 py-2 text-[11px] font-extrabold text-white transition hover:bg-rose-700">
                    <i class="fa-brands fa-whatsapp" aria-hidden="true"></i>
                    Hubungi Panitia via WhatsApp
                  </a>
                </div>

                <div id="info-rekening" class="hidden sm:col-span-2 rounded-xl border border-brand-100 bg-brand-50 p-4" aria-live="polite">
                  <p class="flex items-center gap-2 text-[10px] font-extrabold uppercase tracking-[0.14em] text-brand-700">
                    <i class="fa-solid fa-building-columns" aria-hidden="true"></i> Informasi Rekening Pendaftaran
                  </p>
                  <p id="rek-instruction" class="mt-2 text-xs leading-6 text-brand-800/80">
                    Silakan transfer biaya pendaftaran sesuai jenjang yang dipilih ke rekening berikut, lalu unggah bukti transfernya.
                  </p>
                  <dl class="mt-3 grid gap-2 text-xs text-brand-900 sm:grid-cols-2">
                    <div class="min-w-0">
                      <dt class="text-[10px] font-bold uppercase tracking-wider text-brand-700/70">Nama Bank</dt>
                      <dd id="rek-bank" class="mt-0.5 break-words font-extrabold">-</dd>
                    </div>
                    <div class="min-w-0">
                      <dt class="text-[10px] font-bold uppercase tracking-wider text-brand-700/70">Nomor Rekening</dt>
                      <dd id="rek-nomor" class="mt-0.5 break-all font-extrabold tracking-wide">-</dd>
                    </div>
                    <div class="min-w-0">
                      <dt class="text-[10px] font-bold uppercase tracking-wider text-brand-700/70">Atas Nama</dt>
                      <dd id="rek-nama" class="mt-0.5 break-words font-extrabold">-</dd>
                    </div>
                    <div class="min-w-0">
                      <dt class="text-[10px] font-bold uppercase tracking-wider text-brand-700/70">Biaya Pendaftaran</dt>
                      <dd class="mt-0.5 font-extrabold"><span id="rek-biaya">-</span></dd>
                    </div>
                  </dl>
                </div>

                <div class="sm:col-span-2">
                  <label for="asal-sekolah" class="form-label">Asal Sekolah <span id="asal-sekolah-wajib" hidden>*</span></label>
                  <input type="text" id="asal-sekolah" name="previous_school" value="{{ old('previous_school') }}" class="form-input" placeholder="Nama sekolah/PAUD sebelumnya" autocomplete="organization" aria-describedby="asal-sekolah-hint">
                  <p id="asal-sekolah-hint" class="form-hint">Opsional untuk jenjang KB-TK dan SD, wajib diisi untuk SMP dan SMA.</p>
                </div>

                <div>
                  <label for="nama-ortu" class="form-label">Nama Orang Tua / Wali <span>*</span></label>
                  <input type="text" id="nama-ortu" name="parent_name" value="{{ old('parent_name') }}" class="form-input" placeholder="Nama lengkap orang tua/wali" autocomplete="name" required>
                </div>

                <div>
                  <label for="whatsapp" class="form-label">Nomor WhatsApp Aktif <span>*</span></label>
                  <input type="tel" id="whatsapp" name="whatsapp" value="{{ old('whatsapp') }}" class="form-input" placeholder="08xx-xxxx-xxxx" inputmode="tel" pattern="[0-9+() -]{9,20}" autocomplete="tel" required>
                  <p class="form-hint">Gunakan nomor yang aktif agar mudah dihubungi.</p>
                </div>

                <div class="sm:col-span-2">
                  <label for="alamat" class="form-label">Alamat Domisili di Balikpapan <span class="font-normal text-slate-400">(opsional)</span></label>
                  <textarea id="alamat" name="address" class="form-textarea" placeholder="Tuliskan alamat lengkap, kelurahan, kecamatan, dan kota (dapat dilengkapi pada Tahap 2)" autocomplete="street-address">{{ old('address') }}</textarea>
                  <p class="form-hint">Alamat lengkap juga dapat dilengkapi pada Formulir Lengkap Tahap 2.</p>
                </div>

                <div id="payment-proof-wrap" class="sm:col-span-2">
                  <label for="payment-proof" class="form-label">
                    <span id="payment-proof-label-text">Upload Bukti Pembayaran Pendaftaran</span>
                    <span id="payment-proof-required">*</span>
                  </label>
                  <input type="file" id="payment-proof" name="payment_proof" class="form-input py-2 text-xs" accept=".jpg,.jpeg,.png,.pdf,image/jpeg,image/png,application/pdf" aria-describedby="payment-proof-hint">
                  <p id="payment-proof-hint" class="form-hint">Format JPG, PNG, atau PDF, maksimal 6 MB. Pastikan nominal, tujuan transfer, dan tanggal transaksi terlihat jelas.</p>
                </div>
              </div>

              <label class="form-check mt-5">
                <input type="checkbox" name="persetujuan" value="1" @checked(old('persetujuan')) required>
                <span>Saya menyetujui data yang dikirim digunakan untuk keperluan proses SPMB Cahaya Ilmu Islamic School Balikpapan.</span>
              </label>

              <button type="submit" class="btn btn-primary mt-5 w-full">
                Kirim Pendaftaran
                <i class="fa-solid fa-paper-plane text-sm" aria-hidden="true"></i>
              </button>

              <p class="mt-3 text-center text-[10px] leading-5 text-slate-400">
                Data akan disimpan di sistem sekolah. Setelah berhasil, tombol konfirmasi WhatsApp admin akan muncul otomatis.
              </p>
            </form>

            @php($ppdbSuccess = session('ppdb_success'))
            @if ($ppdbSuccess)
              <div id="ppdb-success" class="form-success mt-5" role="status" aria-live="polite">
                <div class="flex items-start gap-3">
                  <i class="fa-solid fa-circle-check mt-0.5 text-lg text-brand-600" aria-hidden="true"></i>
                  <div class="w-full">
                    <p class="text-sm font-extrabold">Terima kasih, {{ $ppdbSuccess['full_name'] }}!</p>
                    <p class="mt-1 text-xs leading-6">
                      Pendaftaran Anda berhasil disimpan dengan nomor
                      <strong>{{ $ppdbSuccess['registration_number'] }}</strong>
                      untuk jenjang <strong>{{ $ppdbSuccess['level_label'] ?? $ppdbSuccess['level'] }}</strong>@if (filled($ppdbSuccess['sub_level_label'] ?? null)) kelompok <strong>{{ $ppdbSuccess['sub_level_label'] }}</strong>@endif.
                      Panitia akan menghubungi Anda melalui WhatsApp.
                    </p>
                    <p class="mt-1 text-xs leading-6">
                      Biaya pendaftaran: <strong>{{ $ppdbSuccess['final_fee_display'] ?? '-' }}</strong>
                    </p>
                    @if (filled($ppdbSuccess['promo_label'] ?? null))
                      <p class="mt-1 text-xs leading-6">
                        Referral:
                        @if (filled($ppdbSuccess['referral_code'] ?? null))
                          <strong class="font-mono">{{ $ppdbSuccess['referral_code'] }}</strong> &mdash;
                        @endif
                        <strong>{{ $ppdbSuccess['promo_label'] }}</strong>
                        @if (($ppdbSuccess['discount_amount'] ?? 0) > 0)
                          (diskon Rp {{ number_format($ppdbSuccess['discount_amount'], 0, ',', '.') }})
                        @endif
                      </p>
                    @endif

                    <div class="mt-4 grid gap-2.5 sm:grid-cols-2">
                      <a href="{{ route('ppdb.card', $ppdbSuccess['registration_number']) }}" target="_blank" class="btn btn-outline !py-2.5 !text-xs w-full">
                        <i class="fa-solid fa-print text-sm" aria-hidden="true"></i>
                        Cetak Kartu Pendaftaran
                      </a>
                      <a href="{{ route('ppdb.status', ['nomor' => $ppdbSuccess['registration_number']]) }}" class="btn btn-outline !py-2.5 !text-xs w-full">
                        <i class="fa-solid fa-magnifying-glass-chart text-sm" aria-hidden="true"></i>
                        Cek Status Pendaftaran
                      </a>
                    </div>

                    @if ($ppdbSuccess['whatsapp_url'])
                      <a href="{{ $ppdbSuccess['whatsapp_url'] }}" target="_blank" rel="noopener noreferrer" class="btn btn-primary mt-4 w-full">
                        <i class="fa-brands fa-whatsapp text-base" aria-hidden="true"></i>
                        Kirim Konfirmasi ke WhatsApp Admin {{ $ppdbSuccess['level_label'] ?? $ppdbSuccess['level'] }}
                      </a>
                      <p class="mt-2 text-center text-[10px] leading-5 text-brand-800/70">
                        Tombol akan membuka WhatsApp admin jenjang {{ $ppdbSuccess['level_label'] ?? $ppdbSuccess['level'] }} dengan pesan konfirmasi otomatis.
                      </p>
                    @else
                      <p class="mt-3 text-xs leading-6">
                        Nomor WhatsApp admin jenjang ini belum diatur. Silakan hubungi panitia SPMB secara langsung.
                      </p>
                    @endif
                  </div>
                </div>
              </div>
            @endif
          </div>

        </div>
      </div>
    </section>
    <!-- AKHIR SECTION FORMULIR SPMB -->

    <!-- ============================================================
         AWAL SECTION FAQ SPMB
         ============================================================ -->
    <section id="faq" class="section-pad bg-slate-50">
      <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="grid gap-10 lg:grid-cols-[0.8fr_1.2fr] lg:items-start lg:gap-16">
          <div class="reveal">
            <span class="eyebrow">Pertanyaan Umum</span>
            <h2 class="section-title mt-4">Masih ada yang ingin ditanyakan?</h2>
            <p class="section-lead">
              Berikut beberapa pertanyaan yang sering diajukan orang tua. Jika belum terjawab,
              silakan hubungi panitia SPMB.
            </p>
            <a href="{{ url('/#kontak') }}" class="btn btn-outline mt-6 w-full sm:w-auto">
              <i class="fa-solid fa-address-book text-base text-rose-600"></i> Lihat Kontak Admin per Jenjang
            </a>
          </div>

          <div class="reveal">
            <div class="faq-item is-open">
              <button type="button" class="faq-question" aria-expanded="true" aria-controls="faq-1">
                <span>Kapan pendaftaran SPMB dibuka dan apakah ada gelombang ke-2?</span>
                <span class="faq-icon"><i class="fa-solid fa-plus" aria-hidden="true"></i></span>
              </button>
              <div id="faq-1" class="faq-answer">
                <div class="faq-answer-inner">
                  Pendaftaran SPMB Tahun Ajaran 2027/2028 dibuka hanya dalam <strong>1 gelombang</strong> (tidak ada gelombang 2).
                  Kami menyarankan orang tua segera mendaftarkan ananda saat pendaftaran dibuka karena daya tampung setiap jenjang terbatas.
                </div>
              </div>
            </div>

            <div class="faq-item">
              <button type="button" class="faq-question" aria-expanded="false" aria-controls="faq-2">
                <span>Apa saja materi observasi calon murid dan wawancara orang tua?</span>
                <span class="faq-icon"><i class="fa-solid fa-plus" aria-hidden="true"></i></span>
              </button>
              <div id="faq-2" class="faq-answer">
                <div class="faq-answer-inner">
                  Observasi calon peserta didik dilakukan secara sederhana sesuai usia dan nalar anak untuk memetakan kesiapan kognitif serta sosial-emosional (dasar baca-tulis-hitung, pengenalan huruf hijaiyyah, motorik halus &amp; kasar, serta sikap sosial-emosional). Sementara wawancara orang tua bertujuan membangun sinergi pola asuh, prinsip beragama, dan komitmen pendampingan ananda di rumah.
                </div>
              </div>
            </div>

            <div class="faq-item">
              <button type="button" class="faq-question" aria-expanded="false" aria-controls="faq-3">
                <span>Dokumen apa saja yang perlu disiapkan?</span>
                <span class="faq-icon"><i class="fa-solid fa-plus" aria-hidden="true"></i></span>
              </button>
              <div id="faq-3" class="faq-answer">
                <div class="faq-answer-inner">
                  Siapkan fotokopi Kartu Keluarga, akta kelahiran, foto 3&times;4, dan rapor
                  terakhir untuk jenjang SD/SMP/SMA. Bila siswa pindahan, sertakan surat
                  keterangan pindah. Panitia akan mengonfirmasi kelengkapan berkas.
                </div>
              </div>
            </div>

            <div class="faq-item">
              <button type="button" class="faq-question" aria-expanded="false" aria-controls="faq-4">
                <span>Bagaimana informasi biaya pendidikan?</span>
                <span class="faq-icon"><i class="fa-solid fa-plus" aria-hidden="true"></i></span>
              </button>
              <div id="faq-4" class="faq-answer">
                <div class="faq-answer-inner">
                  Informasi biaya pendaftaran, uang pangkal, dan SPP disampaikan langsung oleh
                  panitia agar sesuai jenjang dan program yang dipilih. Silakan hubungi
                  WhatsApp panitia untuk mendapatkan rincian resmi.
                </div>
              </div>
            </div>

            <div class="faq-item">
              <button type="button" class="faq-question" aria-expanded="false" aria-controls="faq-5">
                <span>Apakah bisa mendaftar di luar jadwal gelombang 1?</span>
                <span class="faq-icon"><i class="fa-solid fa-plus" aria-hidden="true"></i></span>
              </button>
              <div id="faq-5" class="faq-answer">
                <div class="faq-answer-inner">
                  Pendaftaran di luar jadwal dapat dilakukan bila kuota masih tersedia. Hubungi
                  panitia untuk mengetahui ketersediaan kursi pada jenjang yang dituju.
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
    <!-- AKHIR SECTION FAQ SPMB -->

  </main>

  <!-- ============================================================
       AWAL SECTION FOOTER SPMB
       ============================================================ -->
  @include('partials.footer')
  <!-- AKHIR SECTION FOOTER SPMB -->

  <!-- ============================================================
       AWAL MENU WHATSAPP MELAYANG
       ============================================================ -->
  @include('partials.whatsapp-widget')
  <!-- AKHIR MENU WHATSAPP MELAYANG -->

  <button id="back-to-top" type="button" class="back-to-top" aria-label="Kembali ke atas">
    <i class="fa-solid fa-arrow-up" aria-hidden="true"></i>
  </button>

  <script>
    window.CI_WHATSAPP = {
      TK: @json($whatsappNumbers['TK']),
      SD: @json($whatsappNumbers['SD']),
      SMP: @json($whatsappNumbers['SMP']),
      SMA: @json($whatsappNumbers['SMA']),
      UMUM: @json($whatsappNumbers['UMUM'])
    };

    window.CI_PPDB_LEVELS = @json($ppdbLevelsJs);
    window.CI_PPDB_QUOTA = @json($ppdbQuotas);
    window.CI_SPMB_AGE = {
      minimum_months: {{ $minimumSdAgeMonths }},
      reference_date: @json($schoolYearStartDate),
      sd_cutoff_date: @json($sdCutoffBirthDate),
      target_year: {{ $targetSchoolYear }}
    };

    window.CI_SPMB_TK = {
      name: @json($spmbTk['name']),
      school_year: @json($spmbTk['school_year']),
      fee: {{ (int) $spmbTk['fee'] }},
      fee_display: {{ Illuminate\Support\Js::from('Rp '.number_format($spmbTk['fee'], 0, ',', '.')) }},
      bank: @json($spmbTk['bank']),
      account_number: @json($spmbTk['account_number']),
      account_name: @json($spmbTk['account_name']),
      groups: @json($spmbTk['groups']),
      target_year: {{ $targetSchoolYear }}
    };

    /*
     * URL path relatif (tanpa domain absolut) agar bebas Mixed Content dan
     * CSRF saat diakses lewat DevTunnel / HTTPS reverse proxy.
     */
    window.CI_REFERRAL_ENDPOINT = '/spmb/check-referral';
  </script>

  <script src="/js/adaptive-images.js"></script>
  <script src="/js/script.js?v={{ filemtime(public_path('js/script.js')) }}"></script>
  @if (session('ppdb_success'))
    <script>
      document.getElementById('ppdb-success')?.scrollIntoView({ behavior: 'smooth', block: 'center' });
    </script>
  @endif
  @if ($errors->any())
    <script>
      (function () {
        const errorBox = document.getElementById('ppdb-form-errors');
        if (!errorBox) return;

        errorBox.scrollIntoView({ behavior: 'smooth', block: 'center' });
        errorBox.focus({ preventScroll: true });
      })();
    </script>
  @endif

</body>
</html>
