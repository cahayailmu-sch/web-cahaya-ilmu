@extends('layouts.public')

@section('title', 'Tentang Kami | Cahaya Ilmu Islamic School Balikpapan')
@section('description', 'Profil lengkap Cahaya Ilmu Islamic School Balikpapan di bawah naungan Yayasan Ath-Thoifah Al-Manshuroh Balikpapan: sejarah, visi misi, struktur, dan keunggulan sekolah.')

@section('content')
  <section class="page-hero relative overflow-hidden">
    <div class="mx-auto max-w-7xl px-4 py-14 sm:px-6 sm:py-20 lg:px-8">
      <nav class="breadcrumb" aria-label="Breadcrumb">
        <a href="{{ route('home') }}">Beranda</a>
        <i class="fa-solid fa-chevron-right text-[8px]" aria-hidden="true"></i>
        <span class="text-gold-300">Tentang Kami</span>
      </nav>

      <div class="mt-6 grid gap-10 lg:grid-cols-[1.35fr_0.65fr] lg:items-end">
        <div>
          <span class="inline-flex items-center gap-2 rounded-full border border-white/15 bg-white/5 px-3 py-1.5 text-[10px] font-extrabold uppercase tracking-[0.16em] text-gold-300">
            <i class="fa-solid fa-mosque" aria-hidden="true"></i> Cahaya Ilmu Islamic School
          </span>
          <h1 class="mt-5 text-3xl font-extrabold leading-tight tracking-tight text-white sm:text-4xl lg:text-5xl">
            Mendidik generasi Rabbani sejak 2014.
          </h1>
          <p class="mt-4 max-w-2xl text-sm leading-7 text-slate-200/75 sm:text-base">
            Cahaya Ilmu Islamic School Balikpapan hadir sebagai rumah belajar yang memadukan
            keunggulan akademik, pembinaan adab, serta kecintaan kepada Al-Qur'an, Hadits, dan doa harian.
          </p>
        </div>

        <div class="page-hero-card">
          <div class="page-hero-card-row">
            <span class="text-slate-200/60">Berdiri sejak</span>
            <span class="font-extrabold text-white">2014</span>
          </div>
          <div class="page-hero-card-row">
            <span class="text-slate-200/60">Jenjang</span>
            <span class="font-extrabold text-gold-300">TK · SD · SMP · SMA</span>
          </div>
          <div class="page-hero-card-row">
            <span class="text-slate-200/60">Lokasi</span>
            <span class="font-extrabold text-white">Balikpapan, Kalimantan Timur</span>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section class="section-pad bg-white">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
      <div class="grid gap-12 lg:grid-cols-[1.05fr_0.95fr] lg:items-center">
        <div class="relative mb-6 sm:mb-8 lg:mb-0">
          <div class="overflow-hidden rounded-[2rem] border border-slate-100 shadow-xl" data-adaptive-frame>
            <img src="{{ asset('assets/images/gedung.jpg') }}"
                 alt="Gedung Cahaya Ilmu Islamic School Balikpapan"
                 width="1400" height="1000" loading="lazy"
                 class="adaptive-img aspect-[4/3] w-full object-cover">
          </div>
          <div class="absolute -bottom-6 left-3 right-3 grid grid-cols-3 gap-2 rounded-2xl border border-slate-100 bg-white p-3 shadow-xl sm:left-8 sm:right-auto sm:w-[26rem] sm:gap-3 sm:p-4">
            <div class="text-center">
              <p class="text-base font-extrabold text-brand-700 sm:text-xl">12+</p>
              <p class="mt-1 text-[8px] font-extrabold uppercase tracking-normal text-slate-400 sm:text-[9px] sm:tracking-wider">Tahun Mendidik</p>
            </div>
            <div class="border-x border-slate-100 text-center">
              <p class="text-base font-extrabold text-brand-700 sm:text-xl">4</p>
              <p class="mt-1 text-[8px] font-extrabold uppercase tracking-normal text-slate-400 sm:text-[9px] sm:tracking-wider">Jenjang</p>
            </div>
            <div class="text-center">
              <p class="text-base font-extrabold text-brand-700 sm:text-xl">{{ number_format((int) ($settings['jumlah_siswa'] ?? 850)) }}</p>
              <p class="mt-1 text-[8px] font-extrabold uppercase tracking-normal text-slate-400 sm:text-[9px] sm:tracking-wider">Siswa Aktif</p>
            </div>
          </div>
        </div>

        <div class="lg:pl-4">
          <span class="eyebrow">Sejarah Kami</span>
          <span class="mt-4 flex w-fit max-w-full items-start gap-2 rounded-full border border-amber-200 bg-amber-50 px-3 py-1.5 text-left text-[11px] font-bold leading-5 text-amber-800">
            <i class="fa-solid fa-building-columns mt-0.5 shrink-0 text-amber-600" aria-hidden="true"></i>
            <span>Di bawah naungan {{ $foundationName }}</span>
          </span>
          <h2 class="section-title mt-4">Dari sebuah cita-cita menjadi keluarga besar.</h2>
          <p class="mt-4 text-sm leading-7 text-slate-600">
            Cahaya Ilmu lahir dari kepedulian sekelompok pendidik dan orang tua di Balikpapan terhadap
            kebutuhan pendidikan Islam yang bermutu. Dimulai dari jenjang taman kanak-kanak, sekolah
            tumbuh bersama kepercayaan masyarakat hingga membuka jenjang SD, SMP, dan SMA.
          </p>
          <p class="mt-4 text-sm leading-7 text-slate-600">
            Kini Cahaya Ilmu Islamic School menjadi lingkungan belajar terpadu yang memadukan
            Kurikulum Merdeka, pembinaan Al-Qur'an, Hadits Arba'in, dan doa harian, penguatan bahasa,
            serta pengembangan karakter.
            Setiap jenjang memiliki kekhasan, namun berbagi satu tujuan: membentuk generasi yang
            cerdas, mandiri, dan berakhlak mulia.
          </p>
          <p class="mt-4 text-sm leading-7 text-slate-600">
            Cahaya Ilmu Islamic School, yang menaungi jenjang KB-TK, SD, SMP, dan SMA Islam Cahaya Ilmu,
            berada di bawah naungan {{ $foundationName }}.
          </p>

          <div class="mt-8 space-y-4">
            <div class="flex gap-4">
              <span class="grid size-10 shrink-0 place-items-center rounded-xl bg-brand-50 text-brand-700"><i class="fa-solid fa-seedling" aria-hidden="true"></i></span>
              <div>
                <h3 class="text-sm font-extrabold text-slate-900">Awal Berdiri</h3>
                <p class="mt-1 text-xs leading-6 text-slate-500">Membuka layanan pendidikan anak usia dini dengan pendekatan Islam dan bermain.</p>
              </div>
            </div>
            <div class="flex gap-4">
              <span class="grid size-10 shrink-0 place-items-center rounded-xl bg-gold-50 text-gold-600"><i class="fa-solid fa-layer-group" aria-hidden="true"></i></span>
              <div>
                <h3 class="text-sm font-extrabold text-slate-900">Pengembangan Jenjang</h3>
                <p class="mt-1 text-xs leading-6 text-slate-500">Menghadirkan jenjang SD, SMP, dan SMA dengan kurikulum yang berkesinambungan.</p>
              </div>
            </div>
            <div class="flex gap-4">
              <span class="grid size-10 shrink-0 place-items-center rounded-xl bg-brand-50 text-brand-700"><i class="fa-solid fa-trophy" aria-hidden="true"></i></span>
              <div>
                <h3 class="text-sm font-extrabold text-slate-900">Pembinaan Prestasi</h3>
                <p class="mt-1 text-xs leading-6 text-slate-500">Mengembangkan bakat siswa melalui ekstrakurikuler, olimpiade, dan kompetisi.</p>
              </div>
            </div>
            <div class="flex gap-4">
              <span class="grid size-10 shrink-0 place-items-center rounded-xl bg-gold-50 text-gold-600"><i class="fa-solid fa-microchip" aria-hidden="true"></i></span>
              <div>
                <h3 class="text-sm font-extrabold text-slate-900">Transformasi Digital</h3>
                <p class="mt-1 text-xs leading-6 text-slate-500">Menghadirkan layanan informasi sekolah dan SPMB daring yang mudah diakses.</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  @include('partials.visi-misi', [
    'eyebrow' => 'Visi & Misi',
    'title' => 'Arah pendidikan yang jelas dan terukur.',
    'lead' => 'Visi dan misi ini menjadi kompas seluruh jenjang Cahaya Ilmu dalam mendidik generasi yang beriman, berilmu, dan berakhlak karimah.',
    'sectionClass' => 'section-pad bg-slate-50',
  ])

  <section class="section-pad bg-white">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
      <div class="section-heading max-w-3xl">
        <span class="eyebrow">Struktur Organisasi</span>
        <h2 class="section-title mt-4">Satu tim, satu tujuan pendidikan.</h2>
        <p class="section-lead">Tata kelola yayasan dan sekolah dirancang agar setiap jenjang mendapatkan pendampingan yang fokus dan profesional.</p>
      </div>

      <div class="mt-10 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
        @foreach ([
          ['Yayasan', 'Menetapkan arah kebijakan, nilai, dan pengembangan lembaga.', 'fa-building-columns'],
          ['Direktur Pendidikan', 'Mengkoordinasikan mutu akademik dan pembinaan lintas jenjang.', 'fa-user-tie'],
          ['Kepala Sekolah', 'Memimpin operasional dan pembelajaran di masing-masing jenjang.', 'fa-chalkboard-user'],
          ['Bidang Kurikulum', 'Mengembangkan kurikulum, asesmen, dan program pembelajaran.', 'fa-book-open'],
          ['Bina Pribadi Islam', 'Mendampingi pembinaan karakter, ibadah, dan adab siswa.', 'fa-hands-praying'],
          ['Tata Usaha & Layanan', 'Mendukung administrasi, komunikasi, dan layanan orang tua.', 'fa-clipboard-list'],
        ] as [$role, $description, $icon])
          <div class="rounded-2xl border border-slate-200 bg-slate-50 p-5 transition hover:border-brand-200 hover:bg-white hover:shadow-sm">
            <span class="grid size-10 place-items-center rounded-xl bg-white text-brand-700 shadow-sm">
              <i class="fa-solid {{ $icon }}" aria-hidden="true"></i>
            </span>
            <h3 class="mt-4 text-sm font-extrabold text-slate-900">{{ $role }}</h3>
            <p class="mt-2 text-xs leading-6 text-slate-500">{{ $description }}</p>
          </div>
        @endforeach
      </div>
    </div>
  </section>

  <section class="section-pad bg-slate-50">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
      <div class="section-heading max-w-3xl">
        <span class="eyebrow">Keunggulan</span>
        <h2 class="section-title mt-4">Mengapa keluarga memilih Cahaya Ilmu.</h2>
      </div>

      <div class="mt-10 grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
        @foreach ([
          ['Pendidikan Al-Qur\'an, Hadits & Doa', 'Tahsin, tahfidz Al-Qur\'an, hafalan Hadits Arba\'in An-Nawawi, dan doa harian yang terarah sesuai usia, didampingi guru pembina.', 'fa-book-quran'],
          ['Karakter & Adab', 'Pembiasaan adab, ibadah, dan kemandirian dalam keseharian sekolah.', 'fa-heart'],
          ['Akademik Seimbang', 'Kurikulum Merdeka dengan penguatan literasi, numerasi, dan sains.', 'fa-scale-balanced'],
          ['Guru Pendamping', 'Guru yang hadir bukan hanya mengajar, tetapi juga mendampingi tumbuh kembang.', 'fa-user-group'],
          ['Ekstrakurikuler', 'Ruang menemukan minat dan bakat melalui kegiatan pilihan yang beragam.', 'fa-futbol'],
          ['Lingkungan Aman', 'Fasilitas yang mendukung, lingkungan ramah anak, dan komunikasi terbuka.', 'fa-shield-heart'],
        ] as [$title, $description, $icon])
          <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            <span class="grid size-11 place-items-center rounded-xl bg-brand-50 text-brand-700">
              <i class="fa-solid {{ $icon }}" aria-hidden="true"></i>
            </span>
            <h3 class="mt-4 text-base font-extrabold text-slate-900">{{ $title }}</h3>
            <p class="mt-2 text-sm leading-6 text-slate-500">{{ $description }}</p>
          </div>
        @endforeach
      </div>
    </div>
  </section>

  <section class="bg-white pb-16">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
      <div class="overflow-hidden rounded-[2rem] bg-brand-700 px-6 py-10 text-center shadow-xl sm:px-10 lg:px-16">
        <h2 class="text-2xl font-extrabold text-white sm:text-3xl">Mari bertumbuh bersama Cahaya Ilmu.</h2>
        <p class="mx-auto mt-3 max-w-2xl text-sm leading-7 text-brand-100">
          Kenali lebih dekat setiap jenjang, atau langsung daftarkan putra-putri Anda pada SPMB
          {{ $settings['tahun_ajaran'] ?? '2026/2027' }}.
        </p>
        <div class="mt-7 flex flex-col justify-center gap-3 sm:flex-row sm:flex-wrap">
          <a href="{{ route('ppdb') }}" class="btn btn-gold w-full sm:w-auto">Daftar SPMB <i class="fa-solid fa-arrow-right text-xs"></i></a>
          <a href="{{ route('jenjang.show', 'tk') }}" class="btn btn-ghost w-full !border-white/30 !text-white hover:!bg-white/10 sm:w-auto">Lihat Profil Jenjang</a>
        </div>
      </div>
    </div>
  </section>
@endsection
