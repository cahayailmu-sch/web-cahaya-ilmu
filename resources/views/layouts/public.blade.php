<!DOCTYPE html>
<html lang="id" class="scroll-smooth overflow-x-clip max-w-[100vw]" style="overflow-x: clip;">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title', 'Cahaya Ilmu Islamic School Balikpapan - '.config('sekolah.motto'))</title>
  <meta name="description" content="@yield('description', 'Cahaya Ilmu Islamic School Balikpapan adalah sekolah Islam dengan jenjang TK, SD, SMP, dan SMA. Membangun generasi Rabbani, cerdas, dan berakhlak mulia.')">
  <meta name="theme-color" content="#e11d48">
  <meta name="color-scheme" content="light">

  <meta property="og:type" content="website">
  <meta property="og:locale" content="id_ID">
  <meta property="og:title" content="@yield('title', 'Cahaya Ilmu Islamic School Balikpapan')">
  <meta property="og:description" content="@yield('description', 'Pendidikan Islam TK, SD, SMP, dan SMA di Balikpapan.')">
  <meta property="og:image" content="@yield('og_image', 'https://images.unsplash.com/photo-1588072432836-e10032774350?auto=format&fit=crop&w=1200&h=630&q=80')">

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
  @stack('head')
</head>

<body class="overflow-x-clip max-w-[100vw] bg-slate-50 font-sans text-slate-700 antialiased" style="overflow-x: clip;">

  <a href="#main" class="skip-link">Langsung ke konten utama</a>

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
        <a href="{{ route('home') }}" @class(['nav-link', 'is-active' => request()->routeIs('home')])>Beranda</a>
        <a href="{{ route('about') }}" @class(['nav-link', 'is-active' => request()->routeIs('about')])>Tentang Kami</a>

        <div class="nav-dropdown relative">
          <button type="button" @class(['nav-link', 'is-active' => request()->routeIs('jenjang.*'), 'inline-flex items-center gap-1.5']) aria-haspopup="true">
            Jenjang
            <i class="fa-solid fa-chevron-down text-[9px] transition-transform duration-300" aria-hidden="true"></i>
          </button>
          <div class="nav-dropdown-panel absolute left-0 top-full z-50 w-72 pt-3">
            <div class="rounded-2xl border border-slate-100 bg-white p-2 shadow-2xl shadow-slate-900/10">
              <p class="px-3 pb-2 pt-1 text-[10px] font-extrabold uppercase tracking-[0.18em] text-slate-400">Pilih Jenjang</p>
              <a href="{{ route('jenjang.show', 'tk') }}" class="dropdown-link">
                <span class="dropdown-icon bg-slate-50 p-1"><img src="/assets/images/logo-tk.png" alt="Logo KB-TK Islam Cahaya Ilmu" class="h-full w-full object-contain"></span>
                <span><span class="block text-sm font-bold text-slate-800">KB-TK Islam Cahaya Ilmu</span><span class="block text-[11px] text-slate-500">KB 3,5 &middot; TK A 4,5 &middot; TK B 5,5 th</span></span>
              </a>
              <a href="{{ route('jenjang.show', 'sd') }}" class="dropdown-link">
                <span class="dropdown-icon bg-slate-50 p-1"><img src="/assets/images/logo-sd.png" alt="Logo SD Islam Cahaya Ilmu" class="h-full w-full object-contain"></span>
                <span><span class="block text-sm font-bold text-slate-800">SD Islam Cahaya Ilmu</span><span class="block text-[11px] text-slate-500">Kelas 1&ndash;6</span></span>
              </a>
              <a href="{{ route('jenjang.show', 'smp') }}" class="dropdown-link">
                <span class="dropdown-icon bg-slate-50 p-1"><img src="/assets/images/logo-smp.png" alt="Logo SMP Islam Cahaya Ilmu" class="h-full w-full object-contain"></span>
                <span><span class="block text-sm font-bold text-slate-800">SMP Islam Cahaya Ilmu</span><span class="block text-[11px] text-slate-500">Kelas 7&ndash;9</span></span>
              </a>
              <a href="{{ route('jenjang.show', 'sma') }}" class="dropdown-link">
                <span class="dropdown-icon bg-slate-50 p-1"><img src="/assets/images/logo-sma.png" alt="Logo SMA Islam Cahaya Ilmu" class="h-full w-full object-contain"></span>
                <span><span class="block text-sm font-bold text-slate-800">SMA Islam Cahaya Ilmu</span><span class="block text-[11px] text-slate-500">Kelas 10&ndash;12 (Banaat/Putri)</span></span>
              </a>
            </div>
          </div>
        </div>

        <a href="{{ route('programs') }}" @class(['nav-link', 'is-active' => request()->routeIs('programs')])>Program Unggulan</a>
        <a href="{{ route('gallery.index') }}" @class(['nav-link', 'is-active' => request()->routeIs('gallery.*')])>Galeri Sekolah</a>
        <a href="{{ route('info.index') }}" @class(['nav-link', 'is-active' => request()->routeIs('info.*')])>Info Sekolah</a>
        <a href="{{ route('home') }}#kontak" class="nav-link">Kontak</a>
      </nav>

      <div class="hidden items-center gap-2 lg:flex">
        <a href="{{ route('ppdb') }}" class="btn btn-primary">
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
          <a href="{{ route('home') }}" class="mobile-nav-link"><i class="fa-solid fa-house w-5 text-brand-600"></i> Beranda</a>
          <a href="{{ route('about') }}" class="mobile-nav-link"><i class="fa-solid fa-circle-info w-5 text-brand-600"></i> Tentang Kami</a>
        </div>

        <div class="mt-3 rounded-2xl border border-slate-100 bg-slate-50 p-3">
          <p class="px-2 pb-2 text-[10px] font-extrabold uppercase tracking-[0.18em] text-slate-400">Jenjang Pendidikan</p>
          <div class="grid grid-cols-2 gap-2">
            <a href="{{ route('jenjang.show', 'tk') }}" class="mobile-jenjang-link"><img src="/assets/images/logo-tk.png" alt="Logo KB-TK" class="h-5 w-5 shrink-0 object-contain"> KB-TK</a>
            <a href="{{ route('jenjang.show', 'sd') }}" class="mobile-jenjang-link"><img src="/assets/images/logo-sd.png" alt="Logo SD" class="h-5 w-5 shrink-0 object-contain"> SD</a>
            <a href="{{ route('jenjang.show', 'smp') }}" class="mobile-jenjang-link"><img src="/assets/images/logo-smp.png" alt="Logo SMP" class="h-5 w-5 shrink-0 object-contain"> SMP</a>
            <a href="{{ route('jenjang.show', 'sma') }}" class="mobile-jenjang-link"><img src="/assets/images/logo-sma.png" alt="Logo SMA" class="h-5 w-5 shrink-0 object-contain"> SMA</a>
          </div>
        </div>

        <div class="mt-3 grid gap-1">
          <a href="{{ route('programs') }}" class="mobile-nav-link"><i class="fa-solid fa-star w-5 text-brand-600"></i> Program Unggulan</a>
          <a href="{{ route('gallery.index') }}" class="mobile-nav-link"><i class="fa-solid fa-school w-5 text-brand-600"></i> Galeri Sekolah</a>
          <a href="{{ route('info.index') }}" class="mobile-nav-link"><i class="fa-solid fa-newspaper w-5 text-brand-600"></i> Info Sekolah</a>
          <a href="{{ route('home') }}#kontak" class="mobile-nav-link"><i class="fa-solid fa-location-dot w-5 text-brand-600"></i> Kontak</a>
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

  <main id="main" class="pt-20 overflow-x-clip">
    @yield('content')
  </main>

  @include('partials.footer')

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
        <p id="lightbox-date" class="lightbox-date" hidden>
          <i class="fa-regular fa-calendar-days" aria-hidden="true"></i>
          <span></span>
        </p>
      </figure>
      <button type="button" class="lightbox-btn lightbox-next" data-lightbox-next aria-label="Foto berikutnya">
        <i class="fa-solid fa-chevron-right" aria-hidden="true"></i>
      </button>
    </div>
  </div>

  @include('partials.whatsapp-widget')

  <button id="back-to-top" type="button" class="back-to-top" aria-label="Kembali ke atas">
    <i class="fa-solid fa-arrow-up" aria-hidden="true"></i>
  </button>

  <script>
    window.CI_WHATSAPP = @json($whatsappNumbers);
  </script>
  <script src="/js/adaptive-images.js"></script>
  <script src="/js/script.js"></script>
  @stack('scripts')
</body>
</html>
