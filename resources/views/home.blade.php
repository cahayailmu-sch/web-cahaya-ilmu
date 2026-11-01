<!DOCTYPE html>
<html lang="id" class="scroll-smooth overflow-x-clip max-w-[100vw]" style="overflow-x: clip;">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Cahaya Ilmu Islamic School Balikpapan - {{ config('sekolah.motto') }}</title>
  <meta name="description" content="Cahaya Ilmu Islamic School Balikpapan adalah sekolah Islam dengan jenjang KB-TK, SD, SMP, dan SMA. Membangun generasi Rabbani, cerdas, dan berakhlak mulia.">
  <meta name="theme-color" content="#e11d48">
  <meta name="color-scheme" content="light">

  <!--
    ============================================================================
    CAHAYA ILMU ISLAMIC SCHOOL BALIKPAPAN
    ----------------------------------------------------------------------------
    PANDUAN EDIT CEPAT:
    1. Setiap section diawali komentar "AWAL SECTION ..." dan diakhiri
       "AKHIR SECTION ..." agar mudah dicari di VS Code (Ctrl + F).
    2. Semua gambar masih memakai Unsplash. Untuk memakai gambar lokal:
       simpan foto ke folder assets/images/, lalu ganti nilai src menjadi
       "assets/images/nama-file.jpg". Contoh ada di dekat setiap gambar.
    3. Data kontak, alamat, nomor WhatsApp, Instagram per jenjang, dan YouTube
       dikelola dari halaman admin /admin/settings.
    4. Logika interaktif ada di js/script.js, styling tambahan di css/style.css.
    ============================================================================
  -->

  <!-- Open Graph: ubah gambar dan alamat website saat sudah online -->
  <meta property="og:type" content="website">
  <meta property="og:locale" content="id_ID">
  <meta property="og:title" content="Cahaya Ilmu Islamic School Balikpapan">
  <meta property="og:description" content="Pendidikan Islam TK, SD, SMP, dan SMA di Balikpapan. Membangun generasi Rabbani, cerdas, dan berakhlak mulia.">
  <meta property="og:image" content="/assets/images/gedung.jpg">

  <link rel="icon" type="image/png" href="/assets/images/logo-utama.png">

  <!-- Google Fonts: Plus Jakarta Sans + Noto Sans Arabic -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,400;0,500;0,600;0,700;0,800;1,400&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Arabic:wght@100..900&display=swap" rel="stylesheet">

  <!-- Font Awesome (ikon) -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" referrerpolicy="no-referrer">

  <!-- Tailwind CSS via CDN -->
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

  <!-- Styling tambahan -->
  <link rel="stylesheet" href="/css/style.css">
</head>

<body class="overflow-x-clip max-w-[100vw] bg-slate-50 font-sans text-slate-700 antialiased" style="overflow-x: clip;">

  <!-- Aksesibilitas: lompat ke konten utama -->
  <a href="#main" class="skip-link">Langsung ke konten utama</a>

  <!-- ============================================================
       AWAL SECTION HEADER / NAVBAR
       ============================================================ -->
  <header id="site-header" class="site-header fixed top-0 left-0 right-0 w-full z-50 transition-all duration-300 bg-white/95 backdrop-blur-md shadow-sm border-b border-slate-100">
    <div class="mx-auto flex h-20 max-w-7xl items-center justify-between gap-4 px-4 sm:px-6 lg:px-8">

      <!-- Logo sekolah -->
      <a href="#beranda" class="flex min-w-0 shrink-0 items-center gap-2.5 sm:gap-3" aria-label="Cahaya Ilmu Islamic School Balikpapan - Beranda">
        <img src="/assets/images/logo-utama.png" alt="Logo Cahaya Ilmu Islamic School" class="h-11 w-auto shrink-0 object-contain sm:h-14">
        <span class="min-w-0 leading-none">
          <span class="block text-[9px] font-extrabold uppercase tracking-[0.22em] text-brand-700">Cahaya Ilmu</span>
          <span class="mt-1 block truncate text-base font-extrabold tracking-tight text-slate-900 sm:text-xl">Islamic School</span>
          <span class="mt-1 hidden text-[10px] font-medium text-slate-500 sm:block">Balikpapan &bull; Kalimantan Timur</span>
        </span>
      </a>

      <!-- Navigasi desktop -->
      <nav class="hidden items-center gap-0.5 lg:flex" aria-label="Navigasi utama">
        <a href="#beranda" class="nav-link is-active" data-nav-target="beranda">Beranda</a>
        <a href="#tentang" class="nav-link" data-nav-target="tentang">Tentang Kami</a>

        <!-- Dropdown Jenjang -->
        <div class="nav-dropdown relative">
          <button type="button" class="nav-link inline-flex items-center gap-1.5" data-nav-target="jenjang" aria-haspopup="true">
            Jenjang
            <i class="fa-solid fa-chevron-down text-[9px] transition-transform duration-300" aria-hidden="true"></i>
          </button>
          <div class="nav-dropdown-panel absolute left-0 top-full z-50 w-72 pt-3">
            <div class="rounded-2xl border border-slate-100 bg-white p-2 shadow-2xl shadow-slate-900/10">
              <p class="px-3 pb-2 pt-1 text-[10px] font-extrabold uppercase tracking-[0.18em] text-slate-400">Pilih Jenjang</p>
              <a href="#jenjang" class="dropdown-link" data-jenjang="tk">
                <span class="dropdown-icon bg-slate-50 p-1"><img src="/assets/images/logo-tk.png" alt="Logo KB-TK Islam Cahaya Ilmu" class="h-full w-full object-contain"></span>
                <span><span class="block text-sm font-bold text-slate-800">KB-TK Islam Cahaya Ilmu</span><span class="block text-[11px] text-slate-500">KB 3,5 &middot; TK A 4,5 &middot; TK B 5,5 th</span></span>
              </a>
              <a href="#jenjang" class="dropdown-link" data-jenjang="sd">
                <span class="dropdown-icon bg-slate-50 p-1"><img src="/assets/images/logo-sd.png" alt="Logo SD Islam Cahaya Ilmu" class="h-full w-full object-contain"></span>
                <span><span class="block text-sm font-bold text-slate-800">SD Islam Cahaya Ilmu</span><span class="block text-[11px] text-slate-500">Kelas 1&ndash;6</span></span>
              </a>
              <a href="#jenjang" class="dropdown-link" data-jenjang="smp">
                <span class="dropdown-icon bg-slate-50 p-1"><img src="/assets/images/logo-smp.png" alt="Logo SMP Islam Cahaya Ilmu" class="h-full w-full object-contain"></span>
                <span><span class="block text-sm font-bold text-slate-800">SMP Islam Cahaya Ilmu</span><span class="block text-[11px] text-slate-500">Kelas 7&ndash;9</span></span>
              </a>
              <a href="#jenjang" class="dropdown-link" data-jenjang="sma">
                <span class="dropdown-icon bg-slate-50 p-1"><img src="/assets/images/logo-sma.png" alt="Logo SMA Islam Cahaya Ilmu" class="h-full w-full object-contain"></span>
                <span><span class="block text-sm font-bold text-slate-800">SMA Islam Cahaya Ilmu</span><span class="block text-[11px] text-slate-500">Kelas 10&ndash;12 (Banaat/Putri)</span></span>
              </a>
            </div>
          </div>
        </div>

        <a href="#program" class="nav-link" data-nav-target="program">Program Unggulan</a>
        <a href="#fasilitas" class="nav-link" data-nav-target="fasilitas">Galeri Sekolah</a>
        <a href="#berita" class="nav-link" data-nav-target="berita">Info Sekolah</a>
        <a href="#kontak" class="nav-link" data-nav-target="kontak">Kontak</a>
      </nav>

      <!-- CTA desktop -->
      <div class="hidden items-center gap-2 lg:flex">
        <a href="tel:+625421234567" class="hidden items-center gap-2 rounded-xl px-3 py-2 text-xs font-bold text-slate-600 transition hover:bg-brand-50 hover:text-brand-700 2xl:inline-flex">
          <i class="fa-solid fa-phone text-brand-600" aria-hidden="true"></i>
          0542-123-4567
        </a>
        <a href="{{ route('ppdb') }}" class="btn btn-primary">
          Daftar SPMB
          <i class="fa-solid fa-arrow-right text-[11px]" aria-hidden="true"></i>
        </a>
      </div>

      <!-- Tombol hamburger (mobile) -->
      <button id="menu-toggle" type="button" class="inline-flex h-11 w-11 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-700 shadow-sm transition hover:border-brand-200 hover:text-brand-700 lg:hidden" aria-expanded="false" aria-controls="mobile-menu" aria-label="Buka menu navigasi">
        <i id="menu-icon-open" class="fa-solid fa-bars text-lg" aria-hidden="true"></i>
        <i id="menu-icon-close" class="fa-solid fa-xmark hidden text-xl" aria-hidden="true"></i>
      </button>
    </div>

    <!-- Menu mobile -->
    <div id="mobile-menu" class="mobile-menu relative z-50 lg:hidden">
      <nav class="mx-auto max-w-7xl px-4 pb-6 pt-2 sm:px-6" aria-label="Navigasi mobile">
        <div class="grid gap-1">
          <a href="#beranda" class="mobile-nav-link"><i class="fa-solid fa-house w-5 text-brand-600"></i> Beranda</a>
          <a href="#tentang" class="mobile-nav-link"><i class="fa-solid fa-circle-info w-5 text-brand-600"></i> Tentang Kami</a>
        </div>

        <div class="mt-3 rounded-2xl border border-slate-100 bg-slate-50 p-3">
          <p class="px-2 pb-2 text-[10px] font-extrabold uppercase tracking-[0.18em] text-slate-400">Jenjang Pendidikan</p>
          <div class="grid grid-cols-2 gap-2">
            <a href="#jenjang" class="mobile-jenjang-link" data-jenjang="tk"><img src="/assets/images/logo-tk.png" alt="Logo KB-TK" class="h-5 w-5 shrink-0 object-contain"> KB-TK</a>
            <a href="#jenjang" class="mobile-jenjang-link" data-jenjang="sd"><img src="/assets/images/logo-sd.png" alt="Logo SD" class="h-5 w-5 shrink-0 object-contain"> SD</a>
            <a href="#jenjang" class="mobile-jenjang-link" data-jenjang="smp"><img src="/assets/images/logo-smp.png" alt="Logo SMP" class="h-5 w-5 shrink-0 object-contain"> SMP</a>
            <a href="#jenjang" class="mobile-jenjang-link" data-jenjang="sma"><img src="/assets/images/logo-sma.png" alt="Logo SMA" class="h-5 w-5 shrink-0 object-contain"> SMA</a>
          </div>
        </div>

        <div class="mt-3 grid gap-1">
          <a href="#program" class="mobile-nav-link"><i class="fa-solid fa-star w-5 text-brand-600"></i> Program Unggulan</a>
          <a href="#fasilitas" class="mobile-nav-link"><i class="fa-solid fa-school w-5 text-brand-600"></i> Galeri Sekolah</a>
          <a href="#berita" class="mobile-nav-link"><i class="fa-solid fa-newspaper w-5 text-brand-600"></i> Info Sekolah</a>
          <a href="#kontak" class="mobile-nav-link"><i class="fa-solid fa-location-dot w-5 text-brand-600"></i> Kontak</a>
        </div>

        <div class="mt-4 grid gap-2">
          <a href="{{ route('ppdb') }}" class="btn btn-primary w-full">Daftar SPMB <i class="fa-solid fa-arrow-right text-xs"></i></a>
          <a href="https://wa.me/{{ $generalWhatsapp }}?text=Assalamu%27alaikum%2C%20saya%20ingin%20bertanya%20tentang%20Cahaya%20Ilmu%20Islamic%20School." target="_blank" rel="noopener noreferrer" class="btn btn-outline w-full">
            <i class="fa-brands fa-whatsapp text-base text-rose-600"></i> Tanya Admin
          </a>
        </div>
      </nav>
    </div>
  </header>
  <!-- AKHIR SECTION HEADER / NAVBAR -->

  <main id="main" class="pt-20 overflow-x-clip">

    <!-- ============================================================
         AWAL SECTION HERO
         ============================================================ -->
    <section id="beranda" class="hero relative overflow-hidden">
      <div class="hero-pattern absolute inset-0" aria-hidden="true"></div>
      <div class="hero-glow hero-glow--one absolute" aria-hidden="true"></div>
      <div class="hero-glow hero-glow--two absolute" aria-hidden="true"></div>

      <div class="relative mx-auto grid max-w-7xl gap-10 px-4 pb-16 pt-10 sm:gap-12 sm:px-6 sm:pb-20 sm:pt-14 lg:grid-cols-[1.05fr_0.95fr] lg:items-center lg:gap-12 lg:px-8 lg:pb-28 lg:pt-20">

        <!-- Teks hero -->
        <div class="hero-copy min-w-0">
          <!-- Baris pembuka: nama yayasan (kiri) dan sapaan Arab (kanan) -->
          <div class="mb-4 flex items-center justify-between gap-4 border-b border-white/10 pb-3">
            <p class="flex min-w-0 items-center gap-2.5 text-[10px] font-semibold uppercase leading-5 tracking-[0.14em] text-slate-300 sm:gap-3 sm:text-xs sm:tracking-[0.16em] md:text-sm">
              <span class="h-px w-6 shrink-0 bg-amber-400/70 sm:w-8" aria-hidden="true"></span>
              <span class="min-w-0 break-words">{{ $foundationName }}</span>
            </p>
            <p dir="rtl" lang="ar" class="shrink-0 text-right font-arabic text-2xl font-bold leading-[2.1] text-amber-400 drop-shadow-[0_1px_12px_rgba(251,191,36,0.25)] md:text-3xl">أَهْلًا وَسَهْلًا</p>
          </div>

          <h1 class="hero-title mt-1 text-3xl font-extrabold text-white sm:text-4xl lg:text-5xl">
            Membangun Generasi
            <span class="text-gold-400">Rabbani</span>,
            Cerdas, dan
            <span class="hero-underline">Berakhlak Mulia</span>
          </h1>

          <p class="mt-5 max-w-xl text-[15px] leading-7 text-slate-200/80 sm:mt-6 sm:text-base sm:leading-8 lg:text-lg">
            Perpaduan Kurikulum Merdeka dan nilai-nilai keislaman untuk membentuk anak yang
            cerdas, mandiri, dan berakhlak mulia&mdash;dari jenjang KB-TK, SD, SMP, hingga SMA
            di Kota Balikpapan.
            <span class="mt-2 block font-medium italic text-amber-300">&ldquo;{{ $motto }}&rdquo;</span>
          </p>

          <div class="mt-7 flex flex-col gap-3 sm:mt-8 sm:flex-row sm:items-center">
            <a href="{{ route('ppdb') }}" class="btn btn-gold w-full sm:w-auto">
              SPMB (Sistem Penerimaan Murid Baru)
              <i class="fa-solid fa-arrow-right text-xs" aria-hidden="true"></i>
            </a>
            <a href="#jenjang" class="btn btn-ghost w-full sm:w-auto">
              Lihat Jenjang Pendidikan
              <i class="fa-solid fa-arrow-down text-xs" aria-hidden="true"></i>
            </a>
          </div>

          <div class="mt-7 flex flex-wrap items-center gap-x-5 gap-y-2 text-[11px] font-semibold text-slate-200/70 sm:mt-9 sm:gap-x-6 sm:text-sm">
            <span><i class="fa-solid fa-circle-check mr-1.5 text-gold-400" aria-hidden="true"></i>Kurikulum Merdeka</span>
            <span><i class="fa-solid fa-circle-check mr-1.5 text-gold-400" aria-hidden="true"></i>Tahfidz Terpadu</span>
            <span><i class="fa-solid fa-circle-check mr-1.5 text-gold-400" aria-hidden="true"></i>4 Jenjang Pendidikan</span>
          </div>
        </div>

        <!-- Visual hero -->
        <div class="relative mx-auto w-full max-w-md lg:max-w-none">
          <div class="hero-orbit" aria-hidden="true"></div>

          <!--
            GANTI GAMBAR HERO:
            1. Simpan foto ke assets/images/hero-sekolah.jpg
            2. Ganti src di bawah menjadi "assets/images/hero-sekolah.jpg"
            Rekomendasi: 1000 x 1250 px, format JPG/WebP, maksimal 300 KB.
          -->
          <div class="arch-frame relative overflow-hidden border-[6px] border-white/20 bg-slate-800 shadow-2xl" data-adaptive-frame>
            <img
              src="/assets/images/gedung-beranda.jpg"
              alt="Siswa Cahaya Ilmu Islamic School belajar bersama di dalam kelas"
              width="1000" height="1250" fetchpriority="high"
              class="adaptive-img h-full w-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-t from-slate-950/70 via-transparent to-transparent" aria-hidden="true"></div>
            <div class="absolute bottom-3 left-3 right-3 flex items-center gap-2.5 rounded-2xl border border-white/20 bg-white/10 p-2.5 backdrop-blur-md sm:bottom-5 sm:left-5 sm:right-5 sm:gap-3 sm:p-3">
              <span class="grid h-9 w-9 shrink-0 place-items-center rounded-xl bg-gold-400 text-brand-950 sm:h-10 sm:w-10">
                <i class="fa-solid fa-shield-halved" aria-hidden="true"></i>
              </span>
              <div class="min-w-0">
                <p class="text-[9px] font-bold uppercase tracking-[0.16em] text-gold-300 sm:text-[10px] sm:tracking-[0.18em]">Lingkungan Belajar</p>
                <p class="truncate text-xs font-bold text-white sm:text-sm">Aman, Islami &amp; Menyenangkan</p>
              </div>
            </div>
          </div>

          <!-- Kartu melayang kiri atas -->
          <div class="hero-float hero-float--top left-2 xl:-left-7">
            <span class="grid h-11 w-11 shrink-0 place-items-center rounded-xl bg-brand-50 text-brand-700">
              <i class="fa-solid fa-book-quran text-lg" aria-hidden="true"></i>
            </span>
            <div>
              <p class="text-[10px] font-bold uppercase tracking-[0.15em] text-slate-400">Program Tahfidz Terpadu</p>
              <p class="text-sm font-extrabold text-slate-800">Al-Qur'an, Hadits Arba'in &amp; Doa Harian</p>
            </div>
          </div>

          <!-- Kartu melayang kanan bawah -->
          <div class="hero-float hero-float--bottom right-2 xl:-right-6">
            <span class="grid h-11 w-11 shrink-0 place-items-center rounded-xl bg-gold-50 text-gold-600">
              <i class="fa-solid fa-users text-lg" aria-hidden="true"></i>
            </span>
            <div>
              <p class="text-[10px] font-bold uppercase tracking-[0.15em] text-slate-400">Keluarga Cahaya Ilmu</p>
              <p class="text-sm font-extrabold text-slate-800">{{ number_format($stats['students'], 0, ',', '.') }}+ siswa aktif</p>
            </div>
          </div>

          <!-- Stempel tahun -->
          <div class="hero-stamp right-2 hidden xl:flex xl:-right-6" aria-hidden="true">
            <span class="text-2xl font-extrabold leading-none text-brand-800">12+</span>
            <span class="mt-1 text-[9px] font-bold uppercase leading-tight tracking-[0.12em] text-slate-500">Tahun<br>Mendidik</span>
          </div>
        </div>
      </div>

      <!-- Ornamen lengkung bawah hero -->
      <div class="hero-bottom-shape" aria-hidden="true"></div>
    </section>
    <!-- AKHIR SECTION HERO -->


    <!-- ============================================================
         AWAL SECTION STATISTIK SEKOLAH
         ============================================================ -->
    <section id="statistik" class="relative z-20 -mt-10 pb-2" aria-label="Statistik sekolah">
      <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="stats-card grid grid-cols-2 gap-5 rounded-3xl border border-slate-100 bg-white p-5 sm:gap-6 sm:p-7 lg:grid-cols-4 lg:p-8">

          <div class="stat-item">
            <span class="stat-icon"><i class="fa-solid fa-users" aria-hidden="true"></i></span>
            <div>
              <p class="text-2xl font-extrabold tracking-tight text-brand-800 sm:text-3xl">
                <span data-counter="{{ $stats['students'] }}">{{ number_format($stats['students'], 0, ',', '.') }}</span><span class="text-gold-500">+</span>
              </p>
              <p class="mt-1 text-[11px] font-semibold text-slate-500 sm:text-xs">Siswa Aktif</p>
            </div>
          </div>

          <div class="stat-item">
            <span class="stat-icon"><i class="fa-solid fa-chalkboard-user" aria-hidden="true"></i></span>
            <div>
              <p class="text-2xl font-extrabold tracking-tight text-brand-800 sm:text-3xl">
                <span data-counter="{{ $stats['teachers'] }}">{{ number_format($stats['teachers'], 0, ',', '.') }}</span><span class="text-gold-500">+</span>
              </p>
              <p class="mt-1 text-[11px] font-semibold text-slate-500 sm:text-xs">Pendidik Profesional</p>
            </div>
          </div>

          <div class="stat-item">
            <span class="stat-icon"><i class="fa-solid fa-clipboard-list" aria-hidden="true"></i></span>
            <div>
              <p class="text-2xl font-extrabold tracking-tight text-brand-800 sm:text-3xl">
                <span data-counter="{{ $stats['registrations'] }}">{{ number_format($stats['registrations'], 0, ',', '.') }}</span><span class="text-gold-500">+</span>
              </p>
              <p class="mt-1 text-[11px] font-semibold text-slate-500 sm:text-xs">Pendaftar SPMB</p>
            </div>
          </div>

          <div class="stat-item">
            <span class="stat-icon"><i class="fa-solid fa-images" aria-hidden="true"></i></span>
            <div>
              <p class="text-2xl font-extrabold tracking-tight text-brand-800 sm:text-3xl">
                <span data-counter="{{ $stats['galleries'] }}">{{ number_format($stats['galleries'], 0, ',', '.') }}</span><span class="text-gold-500">+</span>
              </p>
              <p class="mt-1 text-[11px] font-semibold text-slate-500 sm:text-xs">Foto Galeri</p>
            </div>
          </div>

        </div>
      </div>
    </section>
    <!-- AKHIR SECTION STATISTIK SEKOLAH -->

    <!-- ============================================================
         AWAL SECTION TENTANG KAMI
         ============================================================ -->
    <section id="tentang" class="section-pad overflow-hidden bg-white">
      <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="grid gap-12 lg:grid-cols-2 lg:items-center lg:gap-20">

          <!-- Visual tentang kami -->
          <div class="reveal">
            <div class="about-visual">
              <!--
                GANTI GAMBAR TENTANG KAMI:
                Simpan foto gedung sekolah ke assets/images/gedung-sekolah.jpg,
                lalu ganti src menjadi "assets/images/gedung-sekolah.jpg".
                Rekomendasi: 1200 x 900 px.
              -->
              <div class="about-image" data-adaptive-frame>
                <img
                  src="/assets/images/gedung.jpg"
                  alt="Gedung Cahaya Ilmu Islamic School Balikpapan"
                  width="1200" height="900" loading="lazy" decoding="async"
                  class="adaptive-img h-full w-full object-cover">
              </div>

              <div class="about-years left-2 lg:-left-2">
                <span class="text-3xl font-extrabold leading-none text-brand-800">12+</span>
                <span class="mt-1 text-[9px] font-extrabold uppercase leading-tight tracking-[0.14em] text-slate-500">Tahun<br>Mendidik</span>
              </div>

              <div class="about-quote-card">
                <i class="fa-solid fa-quote-left text-lg text-gold-500" aria-hidden="true"></i>
                <p class="mt-2 text-sm font-semibold leading-6 text-slate-700">
                  &ldquo;Pendidikan bukan sekadar mengisi akal, tetapi menumbuhkan hati yang dekat dengan Allah.&rdquo;
                </p>
                <p class="mt-3 text-[10px] font-bold uppercase tracking-[0.16em] text-brand-700">Direktur Pendidikan</p>
              </div>
            </div>
          </div>

          <!-- Teks tentang kami -->
          <div class="reveal">
            <!-- Pembuka Arab: Basmalah & Salam, rata kanan dengan aksen emas -->
            <div class="mb-6 flex justify-end sm:mb-7">
              <div dir="rtl" lang="ar" class="ml-auto w-fit max-w-xl rounded-2xl border border-amber-100 border-r-4 border-r-amber-400 bg-amber-50/60 px-4 py-3 pr-4 text-right sm:px-5 sm:py-4">
                <p dir="rtl" lang="ar" class="font-arabic text-right text-lg font-semibold leading-[2.2] text-brand-800 sm:text-2xl">بِسْمِ اللَّهِ الرَّحْمَٰنِ الرَّحِيمِ</p>
                <p dir="rtl" lang="ar" class="font-arabic mt-1 text-right text-sm font-semibold leading-[2.2] text-amber-700 sm:text-lg">السَّلَامُ عَلَيْكُمْ وَرَحْمَةُ اللَّهِ وَبَرَكَاتُهُ</p>
              </div>
            </div>

            <span class="eyebrow">Tentang Kami</span>
            <p class="mt-3 flex items-start gap-2 text-[10px] font-semibold uppercase tracking-[0.12em] text-amber-700 sm:text-[11px] sm:tracking-[0.14em]">
              <i class="fa-solid fa-building-columns mt-1 shrink-0 text-amber-600" aria-hidden="true"></i>
              <span class="min-w-0 break-words">Di bawah naungan {{ $foundationName }}</span>
            </p>
            <h2 class="section-title mt-4">Rumah tumbuh anak untuk ilmu, iman, dan masa depan.</h2>
            <p class="mt-5 text-sm leading-7 text-slate-600 sm:text-base">
              Berdiri sejak 2014, Cahaya Ilmu Islamic School Balikpapan hadir sebagai rumah belajar
              yang memadukan keunggulan akademik dengan pembinaan iman dan akhlak. Kami percaya
              pendidikan terbaik lahir ketika anak merasa aman, dihargai, dan dibimbing dengan teladan.
            </p>
            <p class="mt-4 text-sm leading-7 text-slate-600 sm:text-base">
              Cahaya Ilmu Islamic School, yang menaungi jenjang KB-TK, SD, SMP, dan SMA Islam Cahaya Ilmu,
              berada di bawah naungan {{ $foundationName }}. Kami mengelola empat jenjang pendidikan
              dengan kurikulum yang berkesinambungan, guru yang peduli, serta lingkungan yang islami.
            </p>

            <figure class="mt-6 border-l-2 border-rose-300 pl-4 sm:mt-7 sm:pl-5">
              <figcaption class="text-[10px] font-extrabold uppercase tracking-[0.16em] text-rose-600">Motto Sekolah</figcaption>
              <blockquote class="mt-1 text-sm font-extrabold italic leading-7 text-slate-800 sm:text-lg">
                &ldquo;{{ $motto }}&rdquo;
              </blockquote>
            </figure>

            <div class="mt-7 grid gap-4 sm:mt-8 sm:grid-cols-2">
              <div class="value-card">
                <span class="value-icon"><i class="fa-solid fa-mosque" aria-hidden="true"></i></span>
                <h3 class="mt-4 text-sm font-extrabold text-slate-900">Pendidikan Integral</h3>
                <p class="mt-2 text-xs leading-6 text-slate-500">Akademik, adab, dan ibadah tumbuh bersama dalam keseharian.</p>
              </div>
              <div class="value-card">
                <span class="value-icon"><i class="fa-solid fa-heart" aria-hidden="true"></i></span>
                <h3 class="mt-4 text-sm font-extrabold text-slate-900">Lingkungan Islami</h3>
                <p class="mt-2 text-xs leading-6 text-slate-500">Budaya salam, sholat berjamaah, dan adab yang hidup setiap hari.</p>
              </div>
              <div class="value-card">
                <span class="value-icon"><i class="fa-solid fa-chalkboard-user" aria-hidden="true"></i></span>
                <h3 class="mt-4 text-sm font-extrabold text-slate-900">Guru Inspiratif</h3>
                <p class="mt-2 text-xs leading-6 text-slate-500">Pendidik kompeten yang hadir sebagai teladan dan pendamping.</p>
              </div>
              <div class="value-card">
                <span class="value-icon"><i class="fa-solid fa-handshake-angle" aria-hidden="true"></i></span>
                <h3 class="mt-4 text-sm font-extrabold text-slate-900">Kemitraan Orang Tua</h3>
                <p class="mt-2 text-xs leading-6 text-slate-500">Komunikasi terbuka antara sekolah, siswa, dan keluarga.</p>
              </div>
            </div>

            <div class="mt-8 flex flex-col gap-3 sm:flex-row sm:flex-wrap sm:items-center sm:gap-4">
              <a href="#jenjang" class="btn btn-primary w-full sm:w-auto">Kenali 4 Jenjang Kami <i class="fa-solid fa-arrow-right text-xs"></i></a>
              <a href="{{ route('about') }}" class="btn btn-outline w-full sm:w-auto">Baca Selengkapnya</a>
              <a href="#kontak" class="inline-flex items-center justify-center gap-2 py-1 text-sm font-extrabold text-brand-700 transition hover:text-brand-900 sm:justify-start">
                Hubungi Admin <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i>
              </a>
            </div>
          </div>

        </div>
      </div>
    </section>
    <!-- AKHIR SECTION TENTANG KAMI -->

    <!-- ============================================================
         AWAL SECTION VISI & MISI
         ============================================================ -->
    @include('partials.visi-misi', [
      'id' => 'visi-misi',
      'eyebrow' => 'Visi & Misi',
      'title' => 'Visi dan misi yang menjadi kompas seluruh jenjang.',
      'lead' => 'Satu arah pendidikan untuk TK, SD, SMP, dan SMA: membangun generasi beraqidah shahihah, beribadah sesuai sunnah, dan berakhlak karimah.',
      'sectionClass' => 'section-pad bg-gradient-to-b from-white via-slate-50 to-slate-50',
    ])
    <!-- AKHIR SECTION VISI & MISI -->

    <!-- ============================================================
         AWAL SECTION JENJANG PENDIDIKAN
         ============================================================ -->
    <section id="jenjang" class="section-pad overflow-hidden bg-slate-50">
      <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">

        <div class="section-heading section-heading--center reveal">
          <span class="eyebrow">Jenjang Pendidikan</span>
          <h2 class="section-title mt-4">Empat jenjang, satu arah tujuan.</h2>
          <p class="section-lead mx-auto">
            Dari masa bermain di TK hingga persiapan kampus di SMA, setiap jenjang memiliki
            kurikulum dan pendampingan yang dirancang sesuai tahap perkembangan anak.
          </p>
        </div>

        <!-- Tab jenjang -->
        <div class="mt-10 flex flex-wrap justify-center gap-2 sm:gap-3" role="tablist" aria-label="Pilih jenjang pendidikan">
          <button type="button" class="jenjang-tab" id="tab-tk" role="tab" aria-selected="true" aria-controls="panel-tk" tabindex="0" data-tab="tk">
            <span class="jenjang-tab-icon"><img src="/assets/images/logo-tk.png" alt="Logo KB-TK Islam Cahaya Ilmu" loading="lazy" decoding="async" class="h-8 w-auto max-w-[2rem] object-contain"></span>
            <span class="text-left"><span class="block text-sm font-extrabold">KB-TK</span><span class="block text-[10px] font-semibold opacity-70">KB 3,5 &middot; TK A 4,5 &middot; TK B 5,5 th</span></span>
          </button>
          <button type="button" class="jenjang-tab" id="tab-sd" role="tab" aria-selected="false" aria-controls="panel-sd" tabindex="-1" data-tab="sd">
            <span class="jenjang-tab-icon"><img src="/assets/images/logo-sd.png" alt="Logo SD Islam Cahaya Ilmu" loading="lazy" decoding="async" class="h-8 w-auto max-w-[2rem] object-contain"></span>
            <span class="text-left"><span class="block text-sm font-extrabold">SD</span><span class="block text-[10px] font-semibold opacity-70">Kelas 1&ndash;6</span></span>
          </button>
          <button type="button" class="jenjang-tab" id="tab-smp" role="tab" aria-selected="false" aria-controls="panel-smp" tabindex="-1" data-tab="smp">
            <span class="jenjang-tab-icon"><img src="/assets/images/logo-smp.png" alt="Logo SMP Islam Cahaya Ilmu" loading="lazy" decoding="async" class="h-8 w-auto max-w-[2rem] object-contain"></span>
            <span class="text-left"><span class="block text-sm font-extrabold">SMP</span><span class="block text-[10px] font-semibold opacity-70">Kelas 7&ndash;9</span></span>
          </button>
          <button type="button" class="jenjang-tab" id="tab-sma" role="tab" aria-selected="false" aria-controls="panel-sma" tabindex="-1" data-tab="sma">
            <span class="jenjang-tab-icon"><img src="/assets/images/logo-sma.png" alt="Logo SMA Islam Cahaya Ilmu" loading="lazy" decoding="async" class="h-8 w-auto max-w-[2rem] object-contain"></span>
            <span class="text-left"><span class="block text-sm font-extrabold">SMA</span><span class="block text-[10px] font-semibold opacity-70">Kelas 10&ndash;12</span></span>
          </button>
        </div>

        <!-- Panel TK -->
        <div class="mt-9">
          <div id="panel-tk" class="tab-panel grid gap-8 rounded-[2rem] border border-slate-100 bg-white p-5 shadow-sm sm:p-7 lg:grid-cols-2 lg:items-center lg:gap-12" role="tabpanel" aria-labelledby="tab-tk">
            <div class="relative">
              <!-- GANTI GAMBAR TK: assets/images/jenjang-tk.jpg (1200 x 900 px) -->
              <div class="overflow-hidden rounded-[1.5rem]" data-adaptive-frame>
                <img src="{{ $jenjangHeroes['tk'] }}" alt="Anak TK bermain dan belajar dengan gembira" width="1000" height="800" loading="lazy" decoding="async" class="adaptive-img aspect-[4/3] h-full w-full object-cover">
              </div>
              <span class="panel-badge"><i class="fa-solid fa-shapes text-gold-500"></i> KB-TK &bull; KB 3,5 &middot; TK A 4,5 &middot; TK B 5,5 th</span>
            </div>
            <div>
              <span class="eyebrow">Jenjang KB-TK</span>
              <div class="mt-3 flex items-center gap-3">
                <img src="/assets/images/logo-tk.png" alt="Logo KB-TK Islam Cahaya Ilmu" loading="lazy" decoding="async" class="h-12 w-auto shrink-0 object-contain">
                <h3 class="text-2xl font-extrabold tracking-tight text-slate-900 sm:text-3xl">KB-TK Islam Cahaya Ilmu</h3>
              </div>
              <p class="mt-4 text-sm leading-7 text-slate-600">
                KB-TK Islam Cahaya Ilmu merawat masa emas anak melalui Kurikulum Merdeka &amp; Deep Learning:
                bermain sambil belajar, mengenal Allah, membangun adab, dan mencintai Al-Qur'an dalam
                suasana yang menyenangkan dan penuh kasih.
              </p>
              <ul class="mt-6 grid gap-3 sm:grid-cols-2">
                <li class="check-item"><i class="fa-solid fa-circle-check" aria-hidden="true"></i><span>Belajar melalui sentra, proyek, dan permainan terarah</span></li>
                <li class="check-item"><i class="fa-solid fa-circle-check" aria-hidden="true"></i><span>Pembiasaan adab Islami dan ibadah harian</span></li>
                <li class="check-item"><i class="fa-solid fa-circle-check" aria-hidden="true"></i><span>Tahfidz surat pendek, Hadits Arba'in, dan doa harian</span></li>
                <li class="check-item"><i class="fa-solid fa-circle-check" aria-hidden="true"></i><span>Stimulasi motorik, bahasa, dan sosial emosional</span></li>
              </ul>
              <div class="mt-6 flex flex-wrap gap-2">
                <span class="meta-chip"><i class="fa-regular fa-clock"></i> 07.30&ndash;12.30 WITA</span>
                <span class="meta-chip"><i class="fa-solid fa-users"></i> Rasio 1:12</span>
                <span class="meta-chip"><i class="fa-solid fa-calendar-days"></i> 5 hari sekolah</span>
              </div>
              <div class="mt-7 flex flex-col gap-3 sm:flex-row sm:flex-wrap">
                <a href="{{ route('ppdb') }}?jenjang=tk" class="btn btn-primary w-full sm:w-auto">Daftar Jenjang KB-TK <i class="fa-solid fa-arrow-right text-xs"></i></a>
                <a href="{{ route('jenjang.show', 'tk') }}" class="btn btn-outline w-full sm:w-auto">Baca Selengkapnya Profil KB-TK <i class="fa-solid fa-arrow-right text-[10px]"></i></a>
              </div>
            </div>
          </div>

          <!-- Panel SD -->
          <div id="panel-sd" class="tab-panel grid gap-8 rounded-[2rem] border border-slate-100 bg-white p-5 shadow-sm sm:p-7 lg:grid-cols-2 lg:items-center lg:gap-12" role="tabpanel" aria-labelledby="tab-sd" hidden>
            <div class="relative">
              <!-- GANTI GAMBAR SD: assets/images/jenjang-sd.jpg (1200 x 900 px) -->
              <div class="overflow-hidden rounded-[1.5rem]" data-adaptive-frame>
                <img src="{{ $jenjangHeroes['sd'] }}" alt="gedung depan SD" loading="lazy" decoding="async" width="1000" height="800" class="adaptive-img aspect-[4/3] h-full w-full object-cover">
              </div>
              <span class="panel-badge"><i class="fa-solid fa-book-open-reader text-gold-500"></i> SD &bull; Kelas 1&ndash;6</span>
            </div>
            <div>
              <span class="eyebrow">Jenjang SD</span>
              <div class="mt-3 flex items-center gap-3">
                <img src="/assets/images/logo-sd.png" alt="Logo SD Islam Cahaya Ilmu" loading="lazy" decoding="async" class="h-12 w-auto shrink-0 object-contain">
                <h3 class="text-2xl font-extrabold tracking-tight text-slate-900 sm:text-3xl">SD Islam Cahaya Ilmu</h3>
              </div>
              <p class="mt-4 text-sm leading-7 text-slate-600">
                Jenjang SD membangun fondasi akademik yang kuat dan karakter Islami yang kokoh.
                Siswa dibimbing untuk mencintai Al-Qur'an, percaya diri berbahasa, serta kritis
                dalam literasi dan sains.
              </p>
              <ul class="mt-6 grid gap-3 sm:grid-cols-2">
                <li class="check-item"><i class="fa-solid fa-circle-check" aria-hidden="true"></i><span>Tahfidz Al-Qur'an, Hadits Arba'in An-Nawawi &amp; doa harian</span></li>
                <li class="check-item"><i class="fa-solid fa-circle-check" aria-hidden="true"></i><span>Penguatan Bahasa Arab dan Bahasa Inggris</span></li>
                <li class="check-item"><i class="fa-solid fa-circle-check" aria-hidden="true"></i><span>Literasi, numerasi, dan sains terpadu</span></li>
                <li class="check-item"><i class="fa-solid fa-circle-check" aria-hidden="true"></i><span>Ekskul Futsal, Berkuda, Memanah, Renang, Tata Boga, Crafting, Tenis Meja &amp; Karate Wadokai</span></li>
              </ul>
              <div class="mt-6 flex flex-wrap gap-2">
                <span class="meta-chip"><i class="fa-regular fa-clock"></i> Kelas 1&ndash;3: 07.00&ndash;13.00 WITA</span>
                <span class="meta-chip"><i class="fa-regular fa-clock"></i> Kelas 4&ndash;6: 07.00&ndash;14.00 WITA</span>
                <span class="meta-chip"><i class="fa-solid fa-users"></i> Rasio 1:25</span>
                <span class="meta-chip"><i class="fa-solid fa-book"></i> Kurikulum Merdeka</span>
                <span class="meta-chip"><i class="fa-solid fa-calendar-days"></i> 5 hari sekolah</span>
              </div>
              <div class="mt-7 flex flex-col gap-3 sm:flex-row sm:flex-wrap">
                <a href="{{ route('ppdb') }}?jenjang=sd" class="btn btn-primary w-full sm:w-auto">Daftar Jenjang SD <i class="fa-solid fa-arrow-right text-xs"></i></a>
                <a href="{{ route('jenjang.show', 'sd') }}" class="btn btn-outline w-full sm:w-auto">Baca Selengkapnya Profil SD <i class="fa-solid fa-arrow-right text-[10px]"></i></a>
              </div>
            </div>
          </div>

          <!-- Panel SMP -->
          <div id="panel-smp" class="tab-panel grid gap-8 rounded-[2rem] border border-slate-100 bg-white p-5 shadow-sm sm:p-7 lg:grid-cols-2 lg:items-center lg:gap-12" role="tabpanel" aria-labelledby="tab-smp" hidden>
            <div class="relative">
              <!-- GANTI GAMBAR SMP: assets/images/jenjang-smp.jpg (1200 x 900 px) -->
              <div class="overflow-hidden rounded-[1.5rem]" data-adaptive-frame>
                <img src="{{ $jenjangHeroes['smp'] }}" alt="depan gedung SMP" loading="lazy" decoding="async" width="1000" height="800" class="adaptive-img aspect-[4/3] h-full w-full object-cover">
              </div>
              <span class="panel-badge"><i class="fa-solid fa-users text-gold-500"></i> SMP &bull; Kelas 7&ndash;9</span>
            </div>
            <div>
              <span class="eyebrow">Jenjang SMP</span>
              <div class="mt-3 flex items-center gap-3">
                <img src="/assets/images/logo-smp.png" alt="Logo SMP Islam Cahaya Ilmu" loading="lazy" decoding="async" class="h-12 w-auto shrink-0 object-contain">
                <h3 class="text-2xl font-extrabold tracking-tight text-slate-900 sm:text-3xl">SMP Islam Cahaya Ilmu</h3>
              </div>
              <p class="mt-4 text-sm leading-7 text-slate-600">
                Masa remaja adalah masa membangun identitas. Di SMP, siswa menemukan jati diri
                sebagai muslim yang percaya diri, mampu memimpin, dan berprestasi tanpa
                kehilangan adab.
              </p>
              <ul class="mt-6 grid gap-3 sm:grid-cols-2">
                <li class="check-item"><i class="fa-solid fa-circle-check" aria-hidden="true"></i><span>Bina Pribadi Islam (BPI) dan mentoring</span></li>
                <li class="check-item"><i class="fa-solid fa-circle-check" aria-hidden="true"></i><span>Kepemimpinan melalui OSIS dan proyek sosial</span></li>
                <li class="check-item"><i class="fa-solid fa-circle-check" aria-hidden="true"></i><span>Lanjutan Tahfidz Al-Qur'an, Hadits Arba'in &amp; doa harian</span></li>
                <li class="check-item"><i class="fa-solid fa-circle-check" aria-hidden="true"></i><span>Ekskul Berkuda, Memanah, IT Club, Futsal &amp; Berkuda Memanah</span></li>
              </ul>
              <div class="mt-6 flex flex-wrap gap-2">
                <span class="meta-chip"><i class="fa-regular fa-clock"></i> 07.00&ndash;15.00 WITA</span>
                <span class="meta-chip"><i class="fa-solid fa-users"></i> Rasio 1:25</span>
                <span class="meta-chip"><i class="fa-solid fa-medal"></i> Bimbingan prestasi</span>
                <span class="meta-chip"><i class="fa-solid fa-calendar-days"></i> 5 hari sekolah</span>
              </div>
              <div class="mt-7 flex flex-col gap-3 sm:flex-row sm:flex-wrap">
                <a href="{{ route('ppdb') }}?jenjang=smp" class="btn btn-primary w-full sm:w-auto">Daftar Jenjang SMP <i class="fa-solid fa-arrow-right text-xs"></i></a>
                <a href="{{ route('jenjang.show', 'smp') }}" class="btn btn-outline w-full sm:w-auto">Baca Selengkapnya Profil SMP <i class="fa-solid fa-arrow-right text-[10px]"></i></a>
              </div>
            </div>
          </div>

          <!-- Panel SMA -->
          <div id="panel-sma" class="tab-panel grid gap-8 rounded-[2rem] border border-slate-100 bg-white p-5 shadow-sm sm:p-7 lg:grid-cols-2 lg:items-center lg:gap-12" role="tabpanel" aria-labelledby="tab-sma" hidden>
            <div class="relative">
              <!-- GANTI GAMBAR SMA: assets/images/jenjang-sma.jpg (1200 x 900 px) -->
              <div class="overflow-hidden rounded-[1.5rem]" data-adaptive-frame>
                <img src="{{ $jenjangHeroes['sma'] }}" alt="Siswi SMA Islam Cahaya Ilmu" loading="lazy" decoding="async" width="1000" height="800" class="adaptive-img aspect-[4/3] h-full w-full object-cover">
              </div>
              <span class="panel-badge"><i class="fa-solid fa-graduation-cap text-gold-500"></i> SMA &bull; Kelas 10&ndash;12 (Banaat/Putri)</span>
            </div>
            <div>
              <span class="eyebrow">Jenjang SMA &bull; Khusus Banaat / Putri</span>
              <div class="mt-3 flex items-center gap-3">
                <img src="/assets/images/logo-sma.png" alt="Logo SMA Islam Cahaya Ilmu" loading="lazy" decoding="async" class="h-12 w-auto shrink-0 object-contain">
                <h3 class="text-2xl font-extrabold tracking-tight text-slate-900 sm:text-3xl">SMA Islam Cahaya Ilmu</h3>
              </div>
              <p class="mt-4 text-sm leading-7 text-slate-600">
                SMA Islam Cahaya Ilmu dikhususkan bagi peserta didik banaat (putri), menyiapkan
                lulusan muslimah yang siap melanjutkan studi ke perguruan tinggi namun tetap berakar
                kuat pada nilai-nilai Islam, adab, dan kemandirian.
              </p>
              <ul class="mt-6 grid gap-3 sm:grid-cols-2">
                <li class="check-item"><i class="fa-solid fa-circle-check" aria-hidden="true"></i><span>Lingkungan belajar khusus banaat (putri) yang terjaga</span></li>
                <li class="check-item"><i class="fa-solid fa-circle-check" aria-hidden="true"></i><span>Persiapan kampus impian dan bimbingan studi lanjut</span></li>
                <li class="check-item"><i class="fa-solid fa-circle-check" aria-hidden="true"></i><span>Kemandirian dan pembinaan akhlakul karimah muslimah</span></li>
                <li class="check-item"><i class="fa-solid fa-circle-check" aria-hidden="true"></i><span>Tahfidz Al-Qur'an, Hadits Arba'in &amp; doa harian</span></li>
                <li class="check-item"><i class="fa-solid fa-circle-check" aria-hidden="true"></i><span>Ekstrakurikuler khusus Multimedia</span></li>
              </ul>
              <div class="mt-6 flex flex-wrap gap-2">
                <span class="meta-chip"><i class="fa-regular fa-clock"></i> 07.20&ndash;15.00 WITA</span>
                <span class="meta-chip"><i class="fa-solid fa-users"></i> Rasio 1:25</span>
                <span class="meta-chip"><i class="fa-solid fa-venus"></i> Khusus Banaat / Putri</span>
                <span class="meta-chip"><i class="fa-solid fa-photo-film"></i> Ekskul Multimedia</span>
                <span class="meta-chip"><i class="fa-solid fa-calendar-days"></i> 5 hari sekolah</span>
              </div>
              <div class="mt-7 flex flex-col gap-3 sm:flex-row sm:flex-wrap">
                <a href="{{ route('ppdb') }}?jenjang=sma" class="btn btn-primary w-full sm:w-auto">Daftar Jenjang SMA <i class="fa-solid fa-arrow-right text-xs"></i></a>
                <a href="{{ route('jenjang.show', 'sma') }}" class="btn btn-outline w-full sm:w-auto">Baca Selengkapnya Profil SMA <i class="fa-solid fa-arrow-right text-[10px]"></i></a>
              </div>
            </div>
          </div>
        </div>

      </div>
    </section>
    <!-- AKHIR SECTION JENJANG PENDIDIKAN -->


    <!-- ============================================================
         AWAL SECTION PROGRAM UNGGULAN
         ============================================================ -->
    <section id="program" class="section-pad relative overflow-hidden bg-white">
      <div class="section-glow absolute -right-32 top-10 h-80 w-80 rounded-full bg-gold-100/50 blur-3xl" aria-hidden="true"></div>
      <div class="section-glow absolute -left-32 bottom-0 h-80 w-80 rounded-full bg-brand-100/50 blur-3xl" aria-hidden="true"></div>

      <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
          <div class="section-heading reveal">
            <span class="eyebrow">Program Unggulan</span>
            <h2 class="section-title mt-4">Bekal terbaik untuk dunia dan akhirat.</h2>
            <p class="section-lead">
              Program yang dirancang untuk membentuk kebiasaan baik, kemampuan akademik,
              dan karakter Islami yang tumbuh secara berkelanjutan.
            </p>
          </div>
          <div class="flex flex-col gap-2.5 sm:flex-row sm:flex-wrap sm:items-center sm:gap-3">
            <a href="{{ route('programs') }}" class="btn btn-outline w-full shrink-0 sm:w-auto reveal">Baca Selengkapnya <i class="fa-solid fa-arrow-right text-xs"></i></a>
            <a href="{{ route('ppdb') }}" class="btn btn-primary w-full shrink-0 sm:w-auto reveal">Informasi SPMB <i class="fa-solid fa-arrow-right text-xs"></i></a>
          </div>
        </div>

        <div class="mt-10 grid gap-5 sm:grid-cols-2 lg:grid-cols-4">

          <!-- Program 1 -->
          <article class="program-card reveal">
            <span class="program-number" aria-hidden="true">01</span>
            <span class="program-icon"><i class="fa-solid fa-book-quran" aria-hidden="true"></i></span>
            <h3 class="mt-6 text-lg font-extrabold text-slate-900">Tahfidz Al-Qur'an, Hadits &amp; Doa Harian</h3>
            <p class="mt-3 text-sm leading-6 text-slate-600">
              Program pembinaan hafalan terstruktur yang tidak hanya berfokus pada Tahfidz Al-Qur'an,
              tetapi juga mencakup hafalan Hadits Arba'in An-Nawawi serta Doa-Doa Harian sesuai sunnah
              untuk membentuk generasi berilmu dan beramal.
            </p>
            <div class="mt-5 flex flex-wrap gap-2">
              <span class="mini-tag">Tahfidz Al-Qur'an</span>
              <span class="mini-tag">Hadits Arba'in</span>
              <span class="mini-tag">Doa Harian</span>
            </div>
          </article>

          <!-- Program 2 -->
          <article class="program-card reveal">
            <span class="program-number" aria-hidden="true">02</span>
            <span class="program-icon program-icon--gold"><i class="fa-solid fa-language" aria-hidden="true"></i></span>
            <h3 class="mt-6 text-lg font-extrabold text-slate-900">Bahasa Arab &amp; Inggris Aktif</h3>
            <p class="mt-3 text-sm leading-6 text-slate-600">
              Pembiasaan percakapan, public speaking, dan pembelajaran bilingual untuk
              membangun kepercayaan diri di tingkat global.
            </p>
            <div class="mt-5 flex flex-wrap gap-2">
              <span class="mini-tag">Bilingual</span>
              <span class="mini-tag">Public Speaking</span>
            </div>
          </article>

          <!-- Program 3 -->
          <article class="program-card reveal">
            <span class="program-number" aria-hidden="true">03</span>
            <span class="program-icon"><i class="fa-solid fa-hands-praying" aria-hidden="true"></i></span>
            <h3 class="mt-6 text-lg font-extrabold text-slate-900">Pembinaan Akhlak &amp; Ibadah</h3>
            <p class="mt-3 text-sm leading-6 text-slate-600">
              Pembiasaan sholat, adab harian, dan mentoring karakter bersama guru
              pendamping di setiap jenjang.
            </p>
            <div class="mt-5 flex flex-wrap gap-2">
              <span class="mini-tag">Adab Harian</span>
              <span class="mini-tag">Mentoring</span>
            </div>
          </article>

          <!-- Program 4 -->
          <article class="program-card reveal">
            <span class="program-number" aria-hidden="true">04</span>
            <span class="program-icon program-icon--gold"><i class="fa-solid fa-futbol" aria-hidden="true"></i></span>
            <h3 class="mt-6 text-lg font-extrabold text-slate-900">Ekstrakurikuler Bakat &amp; Olahraga</h3>
            <p class="mt-3 text-sm leading-6 text-slate-600">
              Berkuda, memanah, futsal, renang, tenis meja, karate wadokai, tata boga, crafting,
              IT Club, hingga multimedia sesuai jenjang.
            </p>
            <div class="mt-5 flex flex-wrap gap-2">
              <span class="mini-tag">Berkuda &amp; Memanah</span>
              <span class="mini-tag">IT Club &amp; Multimedia</span>
              <span class="mini-tag">Olahraga &amp; Kreasi</span>
            </div>
          </article>

        </div>
      </div>
    </section>
    <!-- AKHIR SECTION PROGRAM UNGGULAN -->

    <!-- ============================================================
         AWAL SECTION GALERI SEKOLAH
         ============================================================ -->
    <section id="fasilitas" class="section-pad bg-slate-50">
      <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="section-heading section-heading--center reveal">
          <span class="eyebrow">Dokumentasi Sekolah</span>
          <h2 class="section-title mt-4">Galeri Sekolah</h2>
          <p class="section-lead mx-auto">
            Dokumentasi kegiatan belajar, fasilitas, dan lingkungan sekolah yang mendukung
            pembelajaran, ibadah, olahraga, serta eksplorasi minat siswa di setiap jenjang.
            Klik foto untuk melihat lebih besar.
          </p>
        </div>

        @if ($galleries->isNotEmpty())
          <div class="gallery-grid mt-10 columns-1 items-start gap-3 min-[480px]:columns-2 sm:gap-4 md:columns-4">
            @foreach ($galleries as $gallery)
              <button type="button"
                      class="gallery-item group mb-3 aspect-[4/3] break-inside-avoid sm:mb-4"
                      data-gallery
                      data-adaptive-frame
                      data-full="{{ $gallery->imageUrl() }}"
                      data-caption="{{ $gallery->title }} - {{ $gallery->category }}"
                      aria-label="Perbesar foto {{ $gallery->title }}">
                <img src="{{ $gallery->imageUrl() }}" alt="{{ $gallery->title }}" width="900" height="700" loading="lazy" decoding="async" class="adaptive-img">
                <span class="gallery-zoom" aria-hidden="true"><i class="fa-solid fa-expand"></i></span>
                <span class="gallery-caption">
                  <span class="gallery-caption-label">{{ $gallery->category }}</span>
                  <span class="gallery-caption-title">{{ $gallery->title }}</span>
                </span>
              </button>
            @endforeach
          </div>
        @else
          <p class="mt-10 rounded-2xl border border-slate-200 bg-white p-8 text-center text-sm font-semibold text-slate-500">
            Belum ada foto galeri yang dipublikasikan.
          </p>
        @endif

        <div class="mt-8 flex flex-wrap items-center justify-center gap-x-6 gap-y-2 text-xs font-semibold text-slate-500">
          <span><i class="fa-solid fa-circle-check mr-1.5 text-brand-600"></i>Lingkungan aman</span>
          <span><i class="fa-solid fa-circle-check mr-1.5 text-brand-600"></i>Area ibadah</span>
          <span><i class="fa-solid fa-circle-check mr-1.5 text-brand-600"></i>Sarana olahraga</span>
          <span><i class="fa-solid fa-circle-check mr-1.5 text-brand-600"></i>Ruang literasi</span>
        </div>

        <div class="mt-9 text-center">
          <a href="{{ route('gallery.index') }}" class="btn btn-primary w-full sm:w-auto reveal">
            Lihat Selengkapnya Galeri Sekolah <i class="fa-solid fa-arrow-right text-xs"></i>
          </a>
        </div>
      </div>
    </section>
    <!-- AKHIR SECTION GALERI SEKOLAH -->

    <!-- ============================================================
         AWAL SECTION INFO & KEGIATAN SEKOLAH
         ============================================================ -->
    <section id="berita" class="section-pad bg-white">
      <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
          <div class="section-heading reveal">
            <span class="eyebrow">Info Sekolah</span>
            <h2 class="section-title mt-4">Info &amp; Kegiatan Sekolah</h2>
            <p class="section-lead">
              Kabar terbaru, pengumuman, prestasi, dan kegiatan yang menjadi bagian dari
              perjalanan siswa-siswi kami.
            </p>
          </div>
          <span class="hidden shrink-0 items-center gap-2 rounded-full border border-slate-200 bg-white px-4 py-2.5 text-xs font-bold text-slate-500 sm:inline-flex reveal">
            <i class="fa-regular fa-newspaper text-brand-600" aria-hidden="true"></i>
            {{ $posts->count() }} info terbaru
          </span>
        </div>

        <div class="mt-10 grid items-start gap-6 md:grid-cols-2 lg:grid-cols-3">
          @forelse ($posts as $post)
            <article class="news-card reveal">
              <div class="relative overflow-hidden bg-slate-100">
                @include('partials.post-carousel', ['post' => $post])
                <span class="news-category {{ $post->category === 'Prestasi' ? 'news-category--gold' : '' }}">{{ $post->category }}</span>
              </div>
              <div class="p-6">
                <div class="flex items-center gap-2 text-[11px] font-semibold text-slate-400">
                  <i class="fa-regular fa-calendar-days text-brand-600"></i>
                  <time datetime="{{ $post->created_at->toDateString() }}">{{ $post->created_at->locale('id')->isoFormat('D MMMM YYYY') }}</time>
                  <span class="h-1 w-1 rounded-full bg-slate-300"></span>
                  <span>{{ $post->readingTime() }} menit baca</span>
                </div>
                <h3 class="mt-3 text-lg font-extrabold leading-7 text-slate-900">
                  <a href="{{ route('info.show', $post->slug) }}" class="transition hover:text-brand-700">{{ $post->title }}</a>
                </h3>
                <p class="mt-3 text-sm leading-6 text-slate-600">{{ $post->excerpt() }}</p>
                <a href="{{ route('info.show', $post->slug) }}" class="news-link mt-5">
                  Baca Selengkapnya <i class="fa-solid fa-arrow-right text-[10px]"></i>
                </a>
              </div>
            </article>
          @empty
            <p class="rounded-2xl border border-slate-200 bg-slate-50 p-8 text-center text-sm font-semibold text-slate-500 md:col-span-2 lg:col-span-3">
              Belum ada info sekolah yang dipublikasikan.
            </p>
          @endforelse
        </div>

        <div class="mt-9 text-center">
          <a href="{{ route('info.index') }}" class="btn btn-outline w-full sm:w-auto reveal">
            Lihat Semua Info Sekolah <i class="fa-solid fa-arrow-right text-xs"></i>
          </a>
        </div>
      </div>
    </section>
    <!-- AKHIR SECTION INFO & KEGIATAN SEKOLAH -->

    <!-- ============================================================
         AWAL SECTION AGENDA & KALENDER KEGIATAN SEKOLAH
         ============================================================ -->
    @if (isset($agendas) && $agendas->isNotEmpty())
      <section id="agenda" class="section-pad bg-slate-50 border-t border-slate-100">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
          <div class="flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
            <div class="section-heading reveal">
              <span class="eyebrow">Kalender Akademik &amp; Kegiatan</span>
              <h2 class="section-title mt-4">Agenda Kegiatan Sekolah</h2>
              <p class="section-lead">
                Jadwal kegiatan terdekat untuk murid, orang tua/wali, dan calon peserta didik baru di lingkungan Cahaya Ilmu Islamic School.
              </p>
            </div>
            <a href="{{ route('info.index') }}#agenda-sekolah" class="btn btn-outline w-full shrink-0 sm:w-auto reveal">
              Lihat Semua Agenda <i class="fa-solid fa-calendar-check text-xs"></i>
            </a>
          </div>

          <div class="mt-10 grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
            @foreach ($agendas as $agenda)
              <article class="reveal flex flex-col justify-between rounded-3xl border border-slate-200/80 bg-white p-5 sm:p-6 shadow-sm transition hover:-translate-y-1 hover:shadow-md">
                <div>
                  <div class="flex items-start justify-between gap-3">
                    <div class="flex items-center gap-3">
                      <div class="flex h-14 w-14 shrink-0 flex-col items-center justify-center rounded-2xl bg-brand-600 text-white shadow-sm">
                        <span class="text-lg font-extrabold leading-none">{{ $agenda->event_date->format('d') }}</span>
                        <span class="mt-0.5 text-[10px] font-bold uppercase tracking-wider text-gold-300">{{ $agenda->event_date->locale('id')->isoFormat('MMM') }}</span>
                      </div>
                      <div>
                        <span class="inline-flex items-center rounded-full bg-brand-50 px-2.5 py-0.5 text-[10px] font-extrabold uppercase tracking-wider text-brand-700">
                          {{ $agenda->level === 'Umum' ? 'Semua Jenjang' : 'Jenjang '.$agenda->level }}
                        </span>
                        <p class="mt-1 text-xs font-semibold text-slate-400">{{ $agenda->event_date->locale('id')->isoFormat('dddd, D MMMM Y') }}</p>
                      </div>
                    </div>
                  </div>

                  <h3 class="mt-4 text-base font-extrabold leading-snug text-slate-900">{{ $agenda->title }}</h3>
                  @if ($agenda->description)
                    <p class="mt-2 text-xs leading-relaxed text-slate-600">{{ \Illuminate\Support\Str::limit($agenda->description, 110) }}</p>
                  @endif
                </div>

                <div class="mt-4 pt-3.5 border-t border-slate-100 space-y-1.5 text-xs text-slate-500">
                  @if ($agenda->time_label)
                    <p class="flex items-center gap-2">
                      <i class="fa-regular fa-clock text-brand-600 w-3.5 text-center"></i>
                      <span>{{ $agenda->time_label }}</span>
                    </p>
                  @endif
                  @if ($agenda->location)
                    <p class="flex items-center gap-2">
                      <i class="fa-solid fa-location-dot text-gold-600 w-3.5 text-center"></i>
                      <span class="truncate">{{ $agenda->location }}</span>
                    </p>
                  @endif
                </div>
              </article>
            @endforeach
          </div>
        </div>
      </section>
    @endif
    <!-- AKHIR SECTION AGENDA & KALENDER KEGIATAN SEKOLAH -->


    <!-- ============================================================
         AWAL SECTION CTA / SPMB
         ============================================================ -->
    <section id="ppdb" class="relative overflow-hidden bg-slate-900 py-16 sm:py-20">
      <div class="cta-pattern absolute inset-0" aria-hidden="true"></div>
      <div class="absolute -right-20 -top-24 h-72 w-72 rounded-full bg-gold-400/10 blur-3xl" aria-hidden="true"></div>
      <div class="absolute -bottom-32 -left-20 h-80 w-80 rounded-full bg-rose-500/10 blur-3xl" aria-hidden="true"></div>

      <div class="relative mx-auto grid max-w-7xl gap-10 px-4 sm:px-6 lg:grid-cols-[1fr_400px] lg:items-center lg:px-8">
        <div class="reveal">
          <span class="inline-flex items-center gap-2 rounded-full border border-gold-400/30 bg-gold-400/10 px-4 py-2 text-xs font-extrabold uppercase tracking-[0.16em] text-gold-300">
            <i class="fa-solid fa-bullhorn" aria-hidden="true"></i>
            SPMB 2027/2028
          </span>
          <h2 class="mt-5 text-3xl font-extrabold leading-tight tracking-tight text-white sm:text-4xl lg:text-[2.75rem]">
            Buka pintu masa depan anak bersama Cahaya Ilmu.
          </h2>
          <p class="mt-5 max-w-2xl text-sm leading-7 text-slate-200/75 sm:text-base">
            Pendaftaran siswa baru gelombang pertama telah dibuka. Bergabunglah dengan keluarga
            besar Cahaya Ilmu Islamic School dan berikan anak Anda lingkungan belajar yang
            menumbuhkan iman, ilmu, dan akhlak.
          </p>
          <ul class="mt-7 grid gap-3 text-sm font-semibold text-slate-200/90 sm:grid-cols-2">
            <li class="flex items-start gap-2.5"><i class="fa-solid fa-circle-check mt-0.5 text-gold-400"></i>Kuota terbatas untuk setiap jenjang</li>
            <li class="flex items-start gap-2.5"><i class="fa-solid fa-circle-check mt-0.5 text-gold-400"></i>Gelombang 1: 1 Okt &ndash; 30 Nov 2026</li>
            <li class="flex items-start gap-2.5"><i class="fa-solid fa-circle-check mt-0.5 text-gold-400"></i>Tes observasi &amp; wawancara ramah anak</li>
            <li class="flex items-start gap-2.5"><i class="fa-solid fa-circle-check mt-0.5 text-gold-400"></i>Informasi biaya transparan</li>
          </ul>
        </div>

        <div class="reveal">
          <div class="rounded-3xl border border-white/20 bg-white p-6 shadow-2xl shadow-slate-950/30 sm:p-7">
            <div class="flex items-center justify-between gap-4">
              <div>
                <p class="text-[10px] font-extrabold uppercase tracking-[0.18em] text-brand-700">Gelombang 1</p>
                <p class="mt-1 text-2xl font-extrabold tracking-tight text-slate-900">SPMB {{ $settings['status_ppdb'] ?? 'Tutup' }}</p>
              </div>
              <span class="grid h-12 w-12 place-items-center rounded-2xl bg-brand-50 text-brand-700">
                <i class="fa-solid fa-school text-xl" aria-hidden="true"></i>
              </span>
            </div>

            <div class="mt-5 space-y-3 border-y border-slate-100 py-5 text-sm">
              <div class="flex items-center justify-between gap-4">
                <span class="text-slate-500">Pendaftaran</span>
                <span class="font-bold text-slate-800">1 Okt &ndash; 30 Nov 2026</span>
              </div>
              <div class="flex items-center justify-between gap-4">
                <span class="text-slate-500">Tes &amp; wawancara</span>
                <span class="font-bold text-slate-800">Desember 2026</span>
              </div>
              <div class="flex items-center justify-between gap-4">
                <span class="text-slate-500">Pengumuman</span>
                <span class="font-bold text-slate-800">5 Januari 2027</span>
              </div>
            </div>

            <a href="{{ route('ppdb') }}" class="btn btn-primary mt-5 w-full">Daftar SPMB Sekarang <i class="fa-solid fa-arrow-right text-xs"></i></a>
            <a href="#kontak" class="btn btn-outline mt-2 w-full">
              <i class="fa-solid fa-address-book text-base text-rose-600"></i> Lihat Kontak Admin per Jenjang
            </a>
            <p class="mt-4 text-center text-[11px] leading-5 text-slate-400">Informasi jadwal dan biaya lengkap tersedia di halaman SPMB.</p>
          </div>
        </div>
      </div>
    </section>
    <!-- AKHIR SECTION CTA / SPMB -->

    <!-- ============================================================
         AWAL SECTION FAQ
         ============================================================ -->
    <section id="faq" class="section-pad bg-slate-50">
      <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="grid gap-10 lg:grid-cols-[0.8fr_1.2fr] lg:items-start lg:gap-16">
          <div class="reveal">
            <span class="eyebrow">Pertanyaan Umum</span>
            <h2 class="section-title mt-4">Hal yang sering ditanyakan orang tua.</h2>
            <p class="section-lead">
              Berikut jawaban singkat seputar kurikulum, program Bahasa Arab dan Tahfidz,
              ekstrakurikuler, serta lokasi sekolah. Jika masih ada pertanyaan, silakan
              hubungi admin kami.
            </p>
            <a href="#kontak" class="btn btn-outline mt-6 w-full sm:w-auto">
              <i class="fa-solid fa-address-book text-base text-rose-600" aria-hidden="true"></i>
              Lihat Kontak Admin per Jenjang
            </a>
          </div>

          <div class="reveal">
            <div class="faq-item is-open">
              <button type="button" class="faq-question" aria-expanded="true" aria-controls="faq-kurikulum">
                <span>Kurikulum apa yang diterapkan di Cahaya Ilmu?</span>
                <span class="faq-icon"><i class="fa-solid fa-plus" aria-hidden="true"></i></span>
              </button>
              <div id="faq-kurikulum" class="faq-answer">
                <div class="faq-answer-inner">
                  Kami memadukan Kurikulum Merdeka dengan pendidikan agama Islam, pembiasaan
                  adab, serta penguatan literasi dan numerasi. Pembelajaran dirancang aktif dan
                  menyenangkan, dengan pendampingan guru di setiap jenjang agar tumbuh kembang
                  anak tetap terarah.
                </div>
              </div>
            </div>

            <div class="faq-item">
              <button type="button" class="faq-question" aria-expanded="false" aria-controls="faq-tahfidz">
                <span>Bagaimana program Bahasa Arab dan Tahfidz?</span>
                <span class="faq-icon"><i class="fa-solid fa-plus" aria-hidden="true"></i></span>
              </button>
              <div id="faq-tahfidz" class="faq-answer">
                <div class="faq-answer-inner">
                  Program Tahfidz Terpadu dimulai dari tahsin, lalu hafalan Al-Qur'an bertahap sesuai
                  jenjang dengan target juz yang terukur. Selain Al-Qur'an, siswa juga menghafal
                  Hadits Arba'in An-Nawawi dan doa-doa harian sesuai sunnah yang dipraktikkan dalam
                  keseharian. Ada halaqah dan murajaah rutin untuk menjaga hafalan. Bahasa Arab
                  dipelajari melalui kosakata, percakapan sederhana, dan pembiasaan sehari-hari
                  bersama guru pendamping.
                </div>
              </div>
            </div>

            <div class="faq-item">
              <button type="button" class="faq-question" aria-expanded="false" aria-controls="faq-ekskul">
                <span>Ekstrakurikuler apa saja yang tersedia?</span>
                <span class="faq-icon"><i class="fa-solid fa-plus" aria-hidden="true"></i></span>
              </button>
              <div id="faq-ekskul" class="faq-answer">
                <div class="faq-answer-inner">
                  Tersedia beragam pilihan seperti Pramuka, futsal, badminton, panahan, robotik,
                  seni kaligrafi, English Club, dan klub Tahfidz. Pilihan kegiatan menyesuaikan
                  jenjang, minat siswa, serta jadwal sekolah. Informasi pendaftaran ekstrakurikuler
                  disampaikan di awal tahun ajaran.
                </div>
              </div>
            </div>

            <div class="faq-item">
              <button type="button" class="faq-question" aria-expanded="false" aria-controls="faq-lokasi">
                <span>Di mana lokasi sekolah dan bagaimana aksesnya?</span>
                <span class="faq-icon"><i class="fa-solid fa-plus" aria-hidden="true"></i></span>
              </button>
              <div id="faq-lokasi" class="faq-answer">
                <div class="faq-answer-inner">
                  Cahaya Ilmu Islamic School berada di Jl. Syarifuddin Yoes RT. 41 komp. Masjid Al imam an Nasa'i Kel. Gunung bahagia kec. Balikpapan Selatan 76114. Lokasinya mudah dijangkau dari pusat
                  kota. Anda dapat membuka tautan Google Maps di bagian kontak atau menghubungi
                  admin untuk petunjuk arah.
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
    <!-- AKHIR SECTION FAQ -->

    <!-- ============================================================
         AWAL SECTION KONTAK & MEDIA SOSIAL
         ============================================================ -->
    <section id="kontak" class="section-pad bg-white">
      <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="section-heading section-heading--center reveal">
          <span class="eyebrow">Kontak &amp; Media Sosial</span>
          <h2 class="section-title mt-4">Hubungi admin sesuai jenjang pilihan Anda.</h2>
          <p class="section-lead mx-auto">
            Setiap jenjang memiliki admin WhatsApp serta akun Instagram dan YouTube resmi. Pilih jenjang
            yang dituju untuk mendapatkan informasi SPMB dan kegiatan sekolah dengan cepat.
          </p>
        </div>

        <div class="mt-10 grid gap-5 sm:grid-cols-2 xl:grid-cols-4">
          @foreach ($contacts as $contact)
            <article class="group relative flex h-full flex-col overflow-hidden rounded-[1.75rem] border border-slate-200 bg-white p-5 shadow-sm transition duration-300 hover:-translate-y-1 hover:border-brand-200 hover:shadow-xl sm:p-6">
              <span class="absolute inset-x-0 top-0 h-1.5 bg-gradient-to-r from-brand-700 via-brand-500 to-gold-400" aria-hidden="true"></span>

              <div class="flex items-center gap-3">
                <span class="grid h-14 w-20 shrink-0 place-items-center rounded-2xl border border-slate-100 bg-slate-50 p-2">
                  <img src="{{ $contact['logo'] }}" alt="Logo {{ $contact['name'] }}" class="h-full w-full object-contain" loading="lazy" decoding="async">
                </span>
                <div class="min-w-0">
                  <p class="text-[9px] font-extrabold uppercase tracking-[0.16em] text-brand-700">Jenjang {{ $contact['level_label'] }}</p>
                  <h3 class="mt-1 text-base font-extrabold leading-5 text-slate-900">{{ $contact['name'] }}</h3>
                </div>
              </div>

              <div class="mt-5 flex flex-1 flex-col gap-3">
                <div class="rounded-2xl border border-emerald-100 bg-emerald-50/70 p-4">
                  <p class="flex items-center gap-2 text-[9px] font-extrabold uppercase tracking-[0.14em] text-emerald-700">
                    <i class="fa-brands fa-whatsapp text-sm" aria-hidden="true"></i> WhatsApp Admin
                  </p>
                  <p class="mt-1.5 text-lg font-extrabold tracking-tight text-slate-900">{{ $contact['wa_display'] }}</p>

                  @if ($contact['wa_url'] !== '')
                    <a href="{{ $contact['wa_url'] }}" target="_blank" rel="noopener noreferrer" class="mt-3 inline-flex w-full items-center justify-center gap-2 rounded-xl bg-emerald-600 px-3 py-2.5 text-xs font-extrabold text-white shadow-sm transition hover:bg-emerald-700">
                      <i class="fa-brands fa-whatsapp text-sm" aria-hidden="true"></i>
                      Chat WhatsApp Admin {{ $contact['level_label'] }}
                    </a>
                  @else
                    <p class="mt-3 rounded-xl bg-white/70 px-3 py-2.5 text-center text-[10px] font-bold text-emerald-800">Nomor WhatsApp belum diatur.</p>
                  @endif
                </div>

                <div class="rounded-2xl border border-fuchsia-100 bg-fuchsia-50/70 p-4">
                  <p class="flex items-center gap-2 text-[9px] font-extrabold uppercase tracking-[0.14em] text-fuchsia-700">
                    <i class="fa-brands fa-instagram text-sm" aria-hidden="true"></i> Instagram Resmi
                  </p>
                  <p class="mt-1.5 truncate text-sm font-extrabold text-slate-900">{{ $contact['instagram_username'] ?: '-' }}</p>

                  @if ($contact['instagram_url'] !== '')
                    <a href="{{ $contact['instagram_url'] }}" target="_blank" rel="noopener noreferrer" class="mt-3 inline-flex w-full items-center justify-center gap-2 rounded-xl bg-fuchsia-600 px-3 py-2.5 text-xs font-extrabold text-white shadow-sm transition hover:bg-fuchsia-700">
                      <i class="fa-brands fa-instagram text-sm" aria-hidden="true"></i>
                      Buka Instagram {{ $contact['level_label'] }}
                    </a>
                  @else
                    <p class="mt-3 rounded-xl bg-white/70 px-3 py-2.5 text-center text-[10px] font-bold text-fuchsia-800">Akun Instagram belum diatur.</p>
                  @endif
                </div>

                <div class="rounded-2xl border border-red-100 bg-red-50/70 p-4">
                  <p class="flex items-center gap-2 text-[9px] font-extrabold uppercase tracking-[0.14em] text-red-700">
                    <i class="fa-brands fa-youtube text-sm" aria-hidden="true"></i> YouTube Resmi
                  </p>
                  <p class="mt-1.5 truncate text-sm font-extrabold text-slate-900">{{ $contact['youtube_handle'] ?: '-' }}</p>

                  @if ($contact['youtube_url'] !== '')
                    <a href="{{ $contact['youtube_url'] }}" target="_blank" rel="noopener noreferrer" class="mt-3 inline-flex w-full items-center justify-center gap-2 rounded-xl bg-red-600 px-3 py-2.5 text-xs font-extrabold text-white shadow-sm transition hover:bg-red-700">
                      <i class="fa-brands fa-youtube text-sm" aria-hidden="true"></i>
                      Buka YouTube {{ $contact['level_label'] }}
                    </a>
                  @else
                    <p class="mt-3 rounded-xl bg-white/70 px-3 py-2.5 text-center text-[10px] font-bold text-red-800">Channel YouTube belum diatur.</p>
                  @endif
                </div>
              </div>
            </article>
          @endforeach
        </div>

        @if (! empty($youtube['url']))
          <div class="relative mt-8 overflow-hidden rounded-[2rem] bg-slate-900 px-6 py-8 shadow-xl shadow-slate-900/10 sm:px-8 lg:flex lg:items-center lg:justify-between lg:gap-8 lg:px-10">
            <div class="absolute -right-16 -top-20 h-56 w-56 rounded-full bg-red-500/20 blur-3xl" aria-hidden="true"></div>
            <div class="absolute -bottom-20 -left-10 h-56 w-56 rounded-full bg-brand-500/20 blur-3xl" aria-hidden="true"></div>

            <div class="relative flex items-start gap-4">
              <span class="grid size-14 shrink-0 place-items-center rounded-2xl bg-red-600 text-2xl text-white shadow-lg shadow-red-900/30">
                <i class="fa-brands fa-youtube" aria-hidden="true"></i>
              </span>
              <div>
                <p class="text-[9px] font-extrabold uppercase tracking-[0.18em] text-red-300">YouTube Resmi Sekolah</p>
                <h3 class="mt-1.5 text-xl font-extrabold leading-tight text-white sm:text-2xl">{{ $youtube['name'] ?: 'Cahaya Ilmu Islamic School' }}</h3>
                <p class="mt-2 max-w-xl text-xs leading-6 text-slate-300 sm:text-sm">
                  Saksikan dokumentasi kegiatan, prestasi siswa, dan cerita pembelajaran di channel YouTube resmi kami.
                </p>
              </div>
            </div>

            <a href="{{ $youtube['url'] }}" target="_blank" rel="noopener noreferrer" class="relative mt-6 inline-flex w-full items-center justify-center gap-2 rounded-xl bg-red-600 px-5 py-3 text-sm font-extrabold text-white shadow-lg shadow-red-950/30 transition hover:bg-red-700 lg:mt-0 lg:w-auto lg:shrink-0">
              <i class="fa-brands fa-youtube text-base" aria-hidden="true"></i>
              Kunjungi YouTube Kami
            </a>
          </div>
        @endif
      </div>
    </section>
    <!-- AKHIR SECTION KONTAK & MEDIA SOSIAL -->

  </main>

  <!-- ============================================================
       AWAL SECTION FOOTER
       ============================================================ -->
  @include('partials.footer')
  <!-- AKHIR SECTION FOOTER -->

  <!-- ============================================================
       AWAL SECTION LIGHTBOX GALERI
       ============================================================ -->
  <div id="lightbox" class="lightbox" role="dialog" aria-modal="true" aria-label="Pratinjau foto galeri sekolah" hidden>
    <button type="button" class="lightbox-backdrop" data-lightbox-close aria-label="Tutup pratinjau foto"></button>
    <div class="lightbox-inner">
      <button type="button" class="lightbox-btn lightbox-close" data-lightbox-close aria-label="Tutup">
        <i class="fa-solid fa-xmark" aria-hidden="true"></i>
      </button>
      <button type="button" class="lightbox-btn lightbox-prev" data-lightbox-prev aria-label="Foto sebelumnya">
        <i class="fa-solid fa-chevron-left" aria-hidden="true"></i>
      </button>
      <figure class="lightbox-figure">
        <img id="lightbox-image" alt="">
        <figcaption id="lightbox-caption" class="lightbox-caption"></figcaption>
      </figure>
      <button type="button" class="lightbox-btn lightbox-next" data-lightbox-next aria-label="Foto berikutnya">
        <i class="fa-solid fa-chevron-right" aria-hidden="true"></i>
      </button>
    </div>
  </div>
  <!-- AKHIR SECTION LIGHTBOX GALERI -->

  <!-- ============================================================
       AWAL SECTION MODAL INFO SEKOLAH
       ============================================================ -->
  <div id="news-modal" class="news-modal" role="dialog" aria-modal="true" aria-labelledby="news-modal-title" hidden>
    <div class="news-modal-backdrop" data-news-close></div>
    <div class="news-modal-panel">
      <button type="button" class="news-modal-close" data-news-close aria-label="Tutup info">
        <i class="fa-solid fa-xmark" aria-hidden="true"></i>
      </button>
      <img id="news-modal-image" alt="" class="news-modal-image adaptive-img">
      <div class="p-6 sm:p-8">
        <div class="flex flex-wrap items-center gap-3 text-[11px] font-bold">
          <span id="news-modal-category" class="rounded-full bg-brand-50 px-3 py-1 text-brand-700">Kegiatan</span>
          <time id="news-modal-date" class="flex items-center gap-1.5 text-slate-400"><i class="fa-regular fa-calendar-days"></i><span></span></time>
        </div>
        <h2 id="news-modal-title" class="mt-4 text-2xl font-extrabold leading-tight tracking-tight text-slate-900 sm:text-3xl">Judul info</h2>
        <div id="news-modal-body" class="news-modal-body mt-5"></div>
        <div class="mt-8 flex flex-wrap gap-3 border-t border-slate-100 pt-6">
          <a href="{{ route('ppdb') }}" class="btn btn-primary">Daftar SPMB <i class="fa-solid fa-arrow-right text-xs"></i></a>
          <button type="button" class="btn btn-outline" data-news-close>Tutup Info</button>
        </div>
      </div>
    </div>
  </div>
  <!-- AKHIR SECTION MODAL INFO SEKOLAH -->

  <!-- ============================================================
       AWAL MENU WHATSAPP MELAYANG
       ============================================================ -->
  @include('partials.whatsapp-widget')
  <!-- AKHIR MENU WHATSAPP MELAYANG -->

  <!-- Tombol kembali ke atas -->
  <button id="back-to-top" type="button" class="back-to-top" aria-label="Kembali ke atas">
    <i class="fa-solid fa-arrow-up" aria-hidden="true"></i>
  </button>

  <!-- Logika interaktif -->
  <script>
    window.CI_WHATSAPP = {
      TK: @json($whatsappNumbers['TK']),
      SD: @json($whatsappNumbers['SD']),
      SMP: @json($whatsappNumbers['SMP']),
      SMA: @json($whatsappNumbers['SMA']),
      UMUM: @json($whatsappNumbers['UMUM'])
    };
  </script>

  <script src="/js/adaptive-images.js"></script>
  <script src="/js/script.js"></script>
</body>
</html>
