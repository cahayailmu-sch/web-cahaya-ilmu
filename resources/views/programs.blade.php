@extends('layouts.public')

@section('title', 'Program Unggulan | Cahaya Ilmu Islamic School Balikpapan')
@section('description', 'Penjelasan lengkap program unggulan Cahaya Ilmu: Tahfidz Al-Qur\'an, Hadits Arba\'in An-Nawawi, Doa Harian, bahasa Arab dan Inggris, pembinaan akhlak, serta ekstrakurikuler bakat dan olahraga.')

@section('content')
  <section class="page-hero relative overflow-hidden">
    <div class="mx-auto max-w-7xl px-4 py-14 sm:px-6 sm:py-20 lg:px-8">
      <nav class="breadcrumb" aria-label="Breadcrumb">
        <a href="{{ route('home') }}">Beranda</a>
        <i class="fa-solid fa-chevron-right text-[8px]" aria-hidden="true"></i>
        <span class="text-gold-300">Program Unggulan</span>
      </nav>

      <div class="mt-6 max-w-3xl">
        <span class="inline-flex items-center gap-2 rounded-full border border-white/15 bg-white/5 px-3 py-1.5 text-[10px] font-extrabold uppercase tracking-[0.16em] text-gold-300">
          <i class="fa-solid fa-star" aria-hidden="true"></i> Program Unggulan
        </span>
        <h1 class="mt-5 text-3xl font-extrabold leading-tight tracking-tight text-white sm:text-4xl lg:text-5xl">
          Bekal terbaik untuk dunia dan akhirat.
        </h1>
        <p class="mt-4 text-sm leading-7 text-slate-200/75 sm:text-base">
          Empat program utama yang saling melengkapi: membangun kedekatan dengan Al-Qur'an, Hadits,
          dan doa harian, kemampuan berbahasa, karakter Islami, serta ruang tumbuhnya bakat siswa.
        </p>
      </div>
    </div>
  </section>

  <section class="bg-white py-10">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
      <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
        @foreach ([
          ['01', 'Tahfidz Terpadu', 'fa-book-quran'],
          ['02', 'Bahasa Arab & Inggris', 'fa-language'],
          ['03', 'Akhlak & Ibadah', 'fa-hands-praying'],
          ['04', 'Bakat & Olahraga', 'fa-futbol'],
        ] as [$number, $title, $icon])
          <a href="#program-{{ $number }}" class="group flex items-center gap-3 rounded-2xl border border-slate-200 bg-slate-50 p-4 transition hover:border-brand-200 hover:bg-white hover:shadow-sm">
            <span class="grid size-10 shrink-0 place-items-center rounded-xl bg-white text-brand-700 shadow-sm"><i class="fa-solid {{ $icon }}" aria-hidden="true"></i></span>
            <span>
              <span class="block text-[9px] font-extrabold uppercase tracking-wider text-slate-400">Program {{ $number }}</span>
              <span class="mt-1 block text-xs font-extrabold text-slate-800 group-hover:text-brand-700">{{ $title }}</span>
            </span>
          </a>
        @endforeach
      </div>
    </div>
  </section>

  <section id="program-01" class="section-pad scroll-mt-24 bg-white">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
      <div class="grid gap-10 lg:grid-cols-[0.9fr_1.1fr] lg:items-center">
        <div class="relative overflow-hidden rounded-[2rem] bg-brand-700 p-7 text-white shadow-xl sm:p-9">
          <span class="absolute -right-14 -top-14 size-44 rounded-full bg-white/10" aria-hidden="true"></span>
          <span class="text-6xl font-extrabold text-white/15">01</span>
          <span class="mt-4 grid size-14 place-items-center rounded-2xl bg-white/10 text-2xl text-gold-300">
            <i class="fa-solid fa-book-quran" aria-hidden="true"></i>
          </span>
          <h2 class="mt-5 text-2xl font-extrabold">Tahfidz Al-Qur'an, Hadits &amp; Doa Harian</h2>
          <p class="mt-3 text-sm leading-7 text-brand-100">
            Program pembinaan hafalan terstruktur yang tidak hanya berfokus pada Tahfidz Al-Qur'an,
            tetapi juga mencakup hafalan Hadits Arba'in An-Nawawi serta Doa-Doa Harian sesuai sunnah
            untuk membentuk generasi berilmu dan beramal.
          </p>
          <div class="mt-6 flex flex-wrap gap-2">
            <span class="rounded-full bg-white/10 px-3 py-1.5 text-[10px] font-extrabold uppercase tracking-wider text-white">Tahfidz Al-Qur'an</span>
            <span class="rounded-full bg-white/10 px-3 py-1.5 text-[10px] font-extrabold uppercase tracking-wider text-white">Hadits Arba'in</span>
            <span class="rounded-full bg-white/10 px-3 py-1.5 text-[10px] font-extrabold uppercase tracking-wider text-white">Doa Harian</span>
            <span class="rounded-full bg-white/10 px-3 py-1.5 text-[10px] font-extrabold uppercase tracking-wider text-white">Tahsin</span>
            <span class="rounded-full bg-white/10 px-3 py-1.5 text-[10px] font-extrabold uppercase tracking-wider text-white">Murajaah</span>
          </div>
        </div>

        <div>
          <span class="eyebrow">Cara Kami Membina</span>
          <h3 class="section-title mt-4">Tiga pilar hafalan, satu generasi beramal.</h3>
          <p class="mt-4 text-sm leading-7 text-slate-600">
            Program Tahfidz Terpadu dijalankan secara bertahap sesuai usia dan kemampuan siswa.
            Guru pembina mendampingi halaqah kecil, mencatat capaian harian, dan memastikan tiga
            pilar hafalan berjalan seimbang: Tahfidz Al-Qur'an, Hadits Arba'in An-Nawawi, serta
            doa-doa harian yang dipraktikkan dalam keseharian.
          </p>
          <div class="mt-6 grid gap-3 sm:grid-cols-2">
            @foreach ([
              'Target hafalan Al-Qur\'an bertahap sesuai jenjang.',
              'Hafalan Hadits Arba\'in An-Nawawi sesuai tingkatan.',
              'Hafalan dan praktik doa-doa harian sesuai sunnah.',
              'Murajaah harian untuk menjaga hafalan.',
              'Tahsin bacaan sebelum menambah hafalan.',
              'Halaqah kecil bersama guru pembina.',
              'Setoran dan murajaah terpantau.',
              'Wisuda tahfidz dan apresiasi capaian.',
            ] as $item)
              <div class="flex items-start gap-2.5 rounded-xl border border-slate-200 bg-slate-50 p-3">
                <i class="fa-solid fa-circle-check mt-1 text-xs text-brand-600" aria-hidden="true"></i>
                <span class="text-xs font-semibold leading-5 text-slate-600">{{ $item }}</span>
              </div>
            @endforeach
          </div>
        </div>
      </div>
    </div>
  </section>

  <section id="program-02" class="section-pad scroll-mt-24 bg-slate-50">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
      <div class="grid gap-10 lg:grid-cols-[1.1fr_0.9fr] lg:items-center">
        <div class="lg:order-2">
          <div class="relative overflow-hidden rounded-[2rem] bg-gold-500 p-7 text-slate-950 shadow-xl sm:p-9">
            <span class="absolute -right-14 -top-14 size-44 rounded-full bg-white/20" aria-hidden="true"></span>
            <span class="text-6xl font-extrabold text-slate-950/10">02</span>
            <span class="mt-4 grid size-14 place-items-center rounded-2xl bg-white/30 text-2xl text-slate-900">
              <i class="fa-solid fa-language" aria-hidden="true"></i>
            </span>
            <h2 class="mt-5 text-2xl font-extrabold">Bahasa Arab & Inggris Aktif</h2>
            <p class="mt-3 text-sm leading-7 text-slate-800/80">
              Pembiasaan percakapan, public speaking, dan pembelajaran bilingual untuk membangun
              kepercayaan diri di tingkat global.
            </p>
            <div class="mt-6 flex flex-wrap gap-2">
              <span class="rounded-full bg-slate-950/10 px-3 py-1.5 text-[10px] font-extrabold uppercase tracking-wider text-slate-900">Bilingual</span>
              <span class="rounded-full bg-slate-950/10 px-3 py-1.5 text-[10px] font-extrabold uppercase tracking-wider text-slate-900">Public Speaking</span>
              <span class="rounded-full bg-slate-950/10 px-3 py-1.5 text-[10px] font-extrabold uppercase tracking-wider text-slate-900">Percakapan</span>
            </div>
          </div>
        </div>

        <div class="lg:order-1">
          <span class="eyebrow">Cara Kami Membiasakan</span>
          <h3 class="section-title mt-4">Bahasa yang hidup dalam keseharian.</h3>
          <p class="mt-4 text-sm leading-7 text-slate-600">
            Bahasa tidak hanya dipelajari di kelas, tetapi dibiasakan dalam kegiatan sekolah.
            Siswa berlatih kosakata, percakapan, presentasi, dan tampil percaya diri di depan teman
            serta guru.
          </p>
          <div class="mt-6 space-y-3">
            <div class="flex gap-3 rounded-xl border border-slate-200 bg-white p-4">
              <span class="grid size-9 shrink-0 place-items-center rounded-lg bg-brand-50 text-brand-700"><i class="fa-solid fa-comments text-xs"></i></span>
              <div><p class="text-xs font-extrabold text-slate-900">Percakapan Harian</p><p class="mt-1 text-xs leading-5 text-slate-500">Kosakata dan ungkapan dipraktikkan dalam rutinitas sekolah.</p></div>
            </div>
            <div class="flex gap-3 rounded-xl border border-slate-200 bg-white p-4">
              <span class="grid size-9 shrink-0 place-items-center rounded-lg bg-gold-50 text-gold-600"><i class="fa-solid fa-microphone-lines text-xs"></i></span>
              <div><p class="text-xs font-extrabold text-slate-900">Public Speaking</p><p class="mt-1 text-xs leading-5 text-slate-500">Latihan presentasi, pidato, dan tampil di depan audiens.</p></div>
            </div>
            <div class="flex gap-3 rounded-xl border border-slate-200 bg-white p-4">
              <span class="grid size-9 shrink-0 place-items-center rounded-lg bg-brand-50 text-brand-700"><i class="fa-solid fa-pen-nib text-xs"></i></span>
              <div><p class="text-xs font-extrabold text-slate-900">Menulis & Membaca</p><p class="mt-1 text-xs leading-5 text-slate-500">Penguatan literasi melalui teks sederhana dan proyek kreatif.</p></div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section id="program-03" class="section-pad scroll-mt-24 bg-white">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
      <div class="grid gap-10 lg:grid-cols-[0.9fr_1.1fr] lg:items-center">
        <div class="relative overflow-hidden rounded-[2rem] bg-slate-900 p-7 text-white shadow-xl sm:p-9">
          <span class="absolute -right-14 -top-14 size-44 rounded-full bg-white/5" aria-hidden="true"></span>
          <span class="text-6xl font-extrabold text-white/10">03</span>
          <span class="mt-4 grid size-14 place-items-center rounded-2xl bg-white/10 text-2xl text-gold-300">
            <i class="fa-solid fa-hands-praying" aria-hidden="true"></i>
          </span>
          <h2 class="mt-5 text-2xl font-extrabold">Pembinaan Akhlak & Ibadah</h2>
          <p class="mt-3 text-sm leading-7 text-slate-300">
            Pembiasaan sholat, adab harian, dan mentoring karakter bersama guru pendamping
            di setiap jenjang.
          </p>
          <div class="mt-6 flex flex-wrap gap-2">
            <span class="rounded-full bg-white/10 px-3 py-1.5 text-[10px] font-extrabold uppercase tracking-wider text-white">Adab Harian</span>
            <span class="rounded-full bg-white/10 px-3 py-1.5 text-[10px] font-extrabold uppercase tracking-wider text-white">Mentoring</span>
            <span class="rounded-full bg-white/10 px-3 py-1.5 text-[10px] font-extrabold uppercase tracking-wider text-white">Ibadah</span>
          </div>
        </div>

        <div>
          <span class="eyebrow">Cara Kami Mendampingi</span>
          <h3 class="section-title mt-4">Karakter tumbuh melalui keteladanan.</h3>
          <p class="mt-4 text-sm leading-7 text-slate-600">
            Pembinaan akhlak tidak berhenti pada pelajaran agama. Guru mendampingi siswa dalam
            ibadah harian, menyelesaikan konflik, mengambil tanggung jawab, dan menunjukkan
            kepedulian kepada sesama.
          </p>
          <div class="mt-6 grid gap-3 sm:grid-cols-2">
            @foreach ([
              'Sholat berjamaah dan doa harian.',
              'Adab kepada guru, teman, dan orang tua.',
              'Mentoring kelompok kecil bersama pembina.',
              'Kegiatan sosial dan kepedulian lingkungan.',
              'Apresiasi perilaku baik dan keteladanan.',
              'Komunikasi berkala dengan orang tua.',
            ] as $item)
              <div class="flex items-start gap-2.5 rounded-xl border border-slate-200 bg-slate-50 p-3">
                <i class="fa-solid fa-circle-check mt-1 text-xs text-brand-600" aria-hidden="true"></i>
                <span class="text-xs font-semibold leading-5 text-slate-600">{{ $item }}</span>
              </div>
            @endforeach
          </div>
        </div>
      </div>
    </div>
  </section>

  <section id="program-04" class="section-pad scroll-mt-24 bg-slate-50">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
      <div class="section-heading max-w-3xl">
        <span class="eyebrow">Program 04</span>
        <h2 class="section-title mt-4">Ekstrakurikuler Bakat & Olahraga</h2>
        <p class="section-lead">
          Berkuda, memanah, futsal, renang, tenis meja, karate wadokai, tata boga, crafting,
          IT Club, hingga multimedia sesuai jenjang untuk menumbuhkan bakat dan sportivitas.
        </p>
      </div>

      <div class="mt-10 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
        @foreach ([
          ['Berkuda & Memanah', 'Melatih ketangkasan fisik, fokus, kesabaran, dan olahraga sunnah (SD & SMP).', 'fa-bullseye'],
          ['Futsal & Tenis Meja', 'Menumbuhkan sportivitas, kebugaran, ketangkasan, dan kerja sama tim (SD & SMP).', 'fa-futbol'],
          ['Renang', 'Melatih keberanian, kebugaran fisik, dan teknik dasar di air (SD).', 'fa-person-swimming'],
          ['Karate Wadokai', 'Melatih kedisiplinan, bela diri, dan ketangguhan mental-fisik (SD).', 'fa-hand-fist'],
          ['Tata Boga', 'Melatih kemandirian dan keterampilan mengolah makanan sehat dan kreatif (SD).', 'fa-utensils'],
          ['Crafting', 'Mengasah kreativitas tangan, ketelitian, dan karya seni kriya (SD).', 'fa-scissors'],
          ['IT Club', 'Mengasah literasi digital, logika pemrograman, dan keterampilan komputer (SMP).', 'fa-laptop-code'],
          ['Multimedia', 'Pengembangan desain visual dan produksi konten kreatif khusus banaat/putri (SMA).', 'fa-photo-film'],
        ] as [$title, $description, $icon])
          <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <span class="grid size-10 place-items-center rounded-xl bg-brand-50 text-brand-700">
              <i class="fa-solid {{ $icon }}" aria-hidden="true"></i>
            </span>
            <h3 class="mt-4 text-sm font-extrabold text-slate-900">{{ $title }}</h3>
            <p class="mt-2 text-xs leading-6 text-slate-500">{{ $description }}</p>
          </div>
        @endforeach
      </div>
    </div>
  </section>

  <section class="bg-white pb-16">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
      <div class="grid gap-6 rounded-[2rem] bg-slate-900 p-6 text-white shadow-xl sm:p-10 lg:grid-cols-[1.2fr_0.8fr] lg:items-center">
        <div>
          <span class="inline-flex items-center gap-2 rounded-full border border-white/15 bg-white/5 px-3 py-1.5 text-[10px] font-extrabold uppercase tracking-[0.16em] text-gold-300">
            <i class="fa-solid fa-graduation-cap" aria-hidden="true"></i> SPMB {{ $settings['tahun_ajaran'] ?? '2026/2027' }}
          </span>
          <h2 class="mt-4 text-2xl font-extrabold leading-tight sm:text-3xl">Wujudkan bekal terbaik untuk masa depan anak.</h2>
          <p class="mt-3 text-sm leading-7 text-slate-300">
            Kenali program setiap jenjang dan konsultasikan kebutuhan pendidikan putra-putri Anda
            bersama admin SPMB kami.
          </p>
        </div>
        <div class="flex flex-col gap-3 sm:flex-row sm:flex-wrap lg:justify-end">
          <a href="{{ route('ppdb') }}" class="btn btn-gold w-full sm:w-auto">Daftar SPMB <i class="fa-solid fa-arrow-right text-xs"></i></a>
          <a href="https://wa.me/{{ $generalWhatsapp }}" target="_blank" rel="noopener noreferrer" class="btn btn-ghost w-full !border-white/20 !text-white hover:!bg-white/10 sm:w-auto">
            <i class="fa-brands fa-whatsapp text-gold-300"></i> Tanya Admin
          </a>
        </div>
      </div>
    </div>
  </section>
@endsection
