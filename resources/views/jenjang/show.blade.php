@extends('layouts.public')

@section('title', $profile['name'].' | Cahaya Ilmu Islamic School Balikpapan')
@section('description', $profile['tagline'].' '.$profile['name'].' - Cahaya Ilmu Islamic School Balikpapan.')
@section('og_image', $profile['hero_image'])

@section('content')
  <div class="overflow-x-clip max-w-[100vw]">
  <section class="page-hero relative overflow-hidden">
    <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 sm:py-16 lg:px-8 lg:py-20">
      <nav class="breadcrumb" aria-label="Breadcrumb">
        <a href="{{ route('home') }}">Beranda</a>
        <i class="fa-solid fa-chevron-right text-[8px]" aria-hidden="true"></i>
        <a href="{{ route('home') }}#jenjang">Jenjang</a>
        <i class="fa-solid fa-chevron-right text-[8px]" aria-hidden="true"></i>
        <span class="text-gold-300">{{ $profile['slug'] === 'tk' ? 'KB-TK' : $profile['short'] }}</span>
      </nav>

      <div class="mt-6 grid gap-8 sm:mt-8 sm:gap-10 lg:grid-cols-[1.15fr_0.85fr] lg:items-center">
        <div>
          <!-- Sapaan Arab & pengantar menyatu tanpa badge -->
          <p class="mb-1 font-arabic text-left text-2xl font-bold leading-[2] text-amber-400 md:text-3xl" lang="ar">أَهْلًا وَسَهْلًا</p>
          <p class="text-xs font-medium text-slate-300 sm:text-sm">Ahlan wa Sahlan, Calon Peserta Didik Baru</p>

          <p class="mt-3 flex items-start gap-2.5 text-[10px] font-extrabold uppercase leading-5 tracking-[0.16em] text-gold-300 sm:items-center sm:gap-3 sm:tracking-[0.18em]">
            <span class="mt-2 h-px w-6 shrink-0 bg-gold-400/70 sm:mt-0 sm:w-8" aria-hidden="true"></span>
            <span class="min-w-0">Profil Jenjang {{ $profile['short'] }}</span>
          </p>

          <h1 class="hero-title hero-title--compact mt-4 font-extrabold text-white">
            {{ $profile['name'] }}
          </h1>
          <p class="mt-4 max-w-2xl text-sm leading-7 text-slate-200/75 sm:text-base">
            {{ $profile['tagline'] }}
            <span class="mt-2 block font-medium italic text-amber-300">&ldquo;{{ $motto }}&rdquo;</span>
          </p>

          <p class="mt-3 text-[11px] font-medium leading-6 text-slate-200/65 sm:text-xs">
            Penyelenggara: <span class="break-words">{{ $foundationName }}</span>
          </p>

          <div class="mt-5 flex flex-wrap items-center gap-x-4 gap-y-2 text-[11px] font-semibold text-slate-300/90 sm:gap-x-5 sm:text-xs">
            <span class="inline-flex items-center gap-2"><i class="fa-solid fa-child-reaching text-gold-300" aria-hidden="true"></i> {{ $profile['age'] }}</span>
            <span class="hidden h-3 w-px bg-white/20 sm:inline-block" aria-hidden="true"></span>
            <span class="inline-flex items-center gap-2"><i class="fa-regular fa-clock text-gold-300" aria-hidden="true"></i> {{ $profile['schedule'] }}</span>
            <span class="hidden h-3 w-px bg-white/20 sm:inline-block" aria-hidden="true"></span>
            <span class="inline-flex items-center gap-2"><i class="fa-solid fa-user-group text-gold-300" aria-hidden="true"></i> {{ $profile['ratio'] }}</span>
          </div>

          <div class="mt-7 flex flex-wrap gap-3 sm:mt-8">
            <a href="{{ route('ppdb', ['jenjang' => $profile['slug']]) }}" class="btn btn-gold w-full min-[420px]:w-auto">
              Daftar SPMB Jenjang Ini <i class="fa-solid fa-arrow-right text-xs"></i>
            </a>
            <a href="#galeri-jenjang" class="btn btn-ghost w-full min-[420px]:w-auto !border-white/20 !text-white hover:!bg-white/10">
              <i class="fa-regular fa-images text-gold-300"></i> Lihat Galeri
            </a>
          </div>
        </div>

        <div class="relative mb-6 mt-2 sm:mb-4 sm:mt-0">
          <div class="overflow-hidden rounded-[1.75rem] border border-white/15 shadow-2xl shadow-black/30" data-adaptive-frame>
            <img src="{{ $profile['hero_image'] }}" alt="{{ $profile['name'] }}" width="1400" height="1000" class="adaptive-img aspect-[4/3] w-full object-cover">
          </div>
          <div class="absolute -bottom-6 left-3 right-3 flex items-center gap-3 rounded-2xl border border-white/20 bg-white p-2.5 shadow-xl sm:left-0 sm:right-auto sm:w-auto sm:p-3">
            <span class="grid h-12 w-16 shrink-0 place-items-center rounded-xl bg-slate-50 p-1.5 sm:h-16 sm:w-24 sm:p-2">
              <img src="{{ preg_match('~^https?://~i', $profile['logo']) ? $profile['logo'] : '/'.ltrim($profile['logo'], '/') }}" alt="Logo {{ $profile['name'] }}" class="h-full w-full object-contain">
            </span>
            <span class="min-w-0">
              <span class="block text-[9px] font-extrabold uppercase tracking-[0.14em] text-brand-700">Cahaya Ilmu</span>
              <span class="mt-1 block truncate text-sm font-extrabold text-slate-900">{{ $profile['short'] }}</span>
            </span>
          </div>
        </div>
      </div>
    </div>
  </section>

  @if ($profile['slug'] === 'tk' && ! empty($spmbTk))
    <section id="spmb-kb-tk" class="section-pad scroll-mt-24 bg-white">
      <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="section-heading max-w-3xl">
          <span class="eyebrow">SPMB {{ $spmbTk['school_year'] }}</span>
          <h2 class="section-title mt-4">Pendaftaran {{ $spmbTk['name'] }}.</h2>
          <p class="section-lead">
            {{ $spmbTk['curriculum'] }}. Pendaftaran online dimulai {{ $spmbTk['registration']['start_label'] }},
            pukul {{ $spmbTk['registration']['hours'] }}, sampai kuota terpenuhi.
            {{ $spmbTk['registration']['event'] }}
          </p>
        </div>

        <div class="mt-8 grid gap-5 lg:grid-cols-2">
          <div class="rounded-2xl border border-slate-200 bg-slate-50 p-5 sm:p-6">
            <h3 class="flex items-center gap-2 text-sm font-extrabold text-slate-900">
              <i class="fa-solid fa-child-reaching text-brand-600" aria-hidden="true"></i>
              Kelompok &amp; Syarat Usia Minimal (Juni {{ $spmbTk['school_year'] }})
            </h3>
            <div class="mt-4 overflow-hidden rounded-xl border border-slate-200 bg-white">
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
              Usia dihitung per bulan Juni {{ $spmbTk['school_year'] }}. Pilih kelompok yang sesuai pada formulir;
              sistem akan mengingatkan bila usia ananda belum memenuhi syarat.
            </p>
          </div>

          <div class="rounded-2xl border border-brand-100 bg-brand-50 p-5 sm:p-6">
            <h3 class="flex items-center gap-2 text-sm font-extrabold text-brand-900">
              <i class="fa-solid fa-building-columns text-brand-600" aria-hidden="true"></i>
              Biaya Pendaftaran &amp; Rekening Resmi
            </h3>
            <p class="mt-3 text-2xl font-extrabold text-brand-900">Rp {{ number_format($spmbTk['fee'], 0, ',', '.') }}</p>
            <dl class="mt-3 space-y-1.5 text-xs text-brand-900">
              <div><dt class="inline font-bold">Bank:</dt> <dd class="inline">{{ $spmbTk['bank'] }}</dd></div>
              <div><dt class="inline font-bold">Nomor Rekening:</dt> <dd class="inline font-extrabold tracking-wide">{{ $spmbTk['account_number'] }}</dd></div>
              <div><dt class="inline font-bold">Atas Nama:</dt> <dd class="inline">{{ $spmbTk['account_name'] }}</dd></div>
            </dl>
            <p class="mt-3 text-[11px] leading-5 text-brand-800/80">
              Bukti transfer diunggah saat pendaftaran online. Bila memperoleh promo gratis biaya pendaftaran,
              bukti transfer tidak diperlukan.
            </p>
          </div>
        </div>

        <div class="mt-5 grid gap-5 lg:grid-cols-2">
          <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
            <h3 class="flex items-center gap-2 text-sm font-extrabold text-slate-900">
              <i class="fa-regular fa-folder-open text-brand-600" aria-hidden="true"></i>
              Dokumen yang Disiapkan
            </h3>
            <ul class="mt-4 space-y-3">
              @foreach ($spmbTk['documents'] as $document)
                <li class="flex items-start gap-2.5 text-xs font-semibold leading-6 text-slate-600">
                  <i class="fa-solid fa-circle-check mt-1 shrink-0 text-brand-600" aria-hidden="true"></i>
                  <span>{{ $document }}</span>
                </li>
              @endforeach
            </ul>
          </div>

          <div class="rounded-2xl border border-gold-100 bg-gold-50 p-5 sm:p-6">
            <h3 class="flex items-center gap-2 text-sm font-extrabold text-gold-900">
              <i class="fa-solid fa-gift text-gold-600" aria-hidden="true"></i>
              Promo Khusus KB-TK
            </h3>
            <ul class="mt-4 space-y-4">
              @foreach ($spmbTk['promos'] as $promo)
                <li class="text-xs leading-6 text-gold-900">
                  <p class="font-extrabold">{{ $promo['label'] }}</p>
                  <p class="text-gold-800/80">{{ $promo['summary'] }}</p>
                </li>
              @endforeach
            </ul>
          </div>
        </div>

        <div class="mt-5 rounded-2xl border border-slate-200 bg-slate-50 p-5 sm:p-6">
          <h3 class="flex items-center gap-2 text-sm font-extrabold text-slate-900">
            <i class="fa-solid fa-list-check text-gold-600" aria-hidden="true"></i>
            Alur SPMB {{ $spmbTk['name'] }}
          </h3>
          <ol class="mt-4 grid gap-3 sm:grid-cols-2">
            @foreach ($spmbTk['steps'] as $index => $step)
              <li class="flex gap-3 rounded-xl border border-slate-100 bg-white p-3">
                <span class="grid h-7 w-7 shrink-0 place-items-center rounded-full bg-brand-600 text-[11px] font-extrabold text-white">{{ $index + 1 }}</span>
                <span class="text-[11px] font-semibold leading-5 text-slate-600">{{ $step }}</span>
              </li>
            @endforeach
          </ol>
        </div>

        <div class="mt-5 flex flex-wrap items-center gap-3 rounded-2xl border border-slate-200 bg-white p-5 sm:p-6">
          <a href="https://wa.me/{{ $spmbTk['whatsapp'] }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2 rounded-lg bg-emerald-600 px-3.5 py-2.5 text-xs font-extrabold text-white transition hover:bg-emerald-700">
            <i class="fa-brands fa-whatsapp text-base" aria-hidden="true"></i> WA Admin {{ $spmbTk['whatsapp_display'] }}
          </a>
          @foreach ($spmbTk['social_links'] as $social => $socialUrl)
            <a href="{{ $socialUrl }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2 rounded-lg border border-slate-200 bg-white px-3.5 py-2.5 text-xs font-bold text-slate-600 transition hover:border-brand-300 hover:text-brand-700">
              <i class="fa-brands fa-{{ $social }}" aria-hidden="true"></i> {{ $spmbTk['social_handle'] }}
            </a>
          @endforeach
        </div>
      </div>
    </section>
  @endif

  <section class="section-pad bg-white">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
      <div class="grid gap-10 lg:grid-cols-[1.3fr_0.7fr] lg:items-start">
        <div>
          <span class="eyebrow">Tentang Jenjang</span>
          <h2 class="section-title mt-4">Lingkungan belajar yang tumbuh bersama anak.</h2>
          @foreach ($profile['description'] as $paragraph)
            <p class="mt-4 text-sm leading-7 text-slate-600">{{ $paragraph }}</p>
          @endforeach
        </div>

        <div class="grid gap-3 sm:grid-cols-3 lg:grid-cols-1">
          <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
            <span class="text-[10px] font-extrabold uppercase tracking-[0.14em] text-slate-400">Rentang Usia</span>
            <p class="mt-2 text-sm font-extrabold text-slate-900">{{ $profile['age'] }}</p>
            @if ($profile['slug'] === 'sd')
              <p class="mt-1.5 text-xs text-slate-500">Minimal berusia 6 tahun 3 bulan per 1 Juli (kelahiran maksimal 31 Maret)</p>
            @elseif ($profile['slug'] === 'tk' && ! empty($spmbTk))
              <ul class="mt-1.5 space-y-0.5 text-xs text-slate-500">
                @foreach ($spmbTk['groups'] as $groupKey => $group)
                  <li>{{ $groupKey }}: min. {{ $group['min_age_label'] }} per Juni {{ $spmbTk['school_year'] }}</li>
                @endforeach
              </ul>
            @endif
          </div>
          <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
            <span class="text-[10px] font-extrabold uppercase tracking-[0.14em] text-slate-400">Jam Belajar</span>
            <p class="mt-2 text-sm font-extrabold text-slate-900">{{ $profile['schedule'] }}</p>
          </div>
          <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
            <span class="text-[10px] font-extrabold uppercase tracking-[0.14em] text-slate-400">Pendampingan</span>
            <p class="mt-2 text-sm font-extrabold text-slate-900">{{ $profile['ratio'] }}</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  @include('partials.visi-misi', [
    'eyebrow' => 'Visi & Misi',
    'title' => 'Arah pendidikan '.$profile['short'].' yang selaras dengan seluruh jenjang.',
    'lead' => 'Visi dan misi Cahaya Ilmu berlaku sama di setiap jenjang, termasuk '.$profile['name'].'.',
    'sectionClass' => 'section-pad bg-slate-50',
  ])

  <section class="section-pad bg-white">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
      <div class="section-heading max-w-3xl">
        <span class="eyebrow">Program Unggulan</span>
        <h2 class="section-title mt-4">Bekal belajar yang dirancang untuk jenjang {{ $profile['short'] }}.</h2>
        <p class="section-lead">Setiap program mendukung perkembangan akademik, karakter, dan spiritual siswa secara seimbang.</p>
      </div>

      <div class="mt-10 grid gap-5 sm:grid-cols-2">
        @foreach ($profile['programs'] as $program)
          <div class="program-card">
            <div class="program-icon {{ $loop->even ? 'program-icon--gold' : '' }}">
              <i class="fa-solid {{ $program['icon'] }}" aria-hidden="true"></i>
            </div>
            <h3 class="mt-5 text-lg font-extrabold text-slate-900">{{ $program['title'] }}</h3>
            <p class="mt-3 text-sm leading-7 text-slate-600">{{ $program['description'] }}</p>
          </div>
        @endforeach
      </div>
    </div>
  </section>

  <section class="section-pad bg-slate-50">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
      <div class="grid gap-10 lg:grid-cols-2 lg:gap-16">
        <div class="border-t-2 border-rose-200 pt-6">
          <span class="eyebrow">Target Lulusan</span>
          <h2 class="mt-4 text-2xl font-extrabold text-slate-900">Profil lulusan {{ $profile['short'] }}</h2>
          <ol class="mt-5 space-y-4">
            @foreach ($profile['graduate_profile'] as $item)
              <li class="flex gap-3">
                <span class="w-5 shrink-0 pt-0.5 text-xs font-extrabold tabular-nums text-brand-700">{{ str_pad((string) $loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                <span class="text-sm leading-6 text-slate-600">{{ $item }}</span>
              </li>
            @endforeach
          </ol>
        </div>

        <div class="border-t-2 border-amber-200 pt-6">
          <span class="eyebrow">Kurikulum</span>
          <h2 class="mt-4 text-2xl font-extrabold text-slate-900">Kurikulum &amp; pendekatan belajar</h2>
          <ul class="mt-5 space-y-4">
            @foreach ($profile['curriculum'] as $item)
              <li class="flex gap-3">
                <i class="fa-solid fa-book-open mt-1 text-xs text-gold-600" aria-hidden="true"></i>
                <span class="text-sm leading-6 text-slate-600">{{ $item }}</span>
              </li>
            @endforeach
          </ul>
        </div>
      </div>
    </div>
  </section>

  <section class="section-pad bg-white">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
      <div class="grid gap-8 lg:grid-cols-2">
        <div>
          <span class="eyebrow">Ekstrakurikuler</span>
          <h2 class="section-title mt-4">Ruang menemukan minat dan bakat.</h2>
          <p class="section-lead">Kegiatan pilihan untuk melatih keberanian, kerja sama, dan keterampilan di luar kelas.</p>
          <ul class="mt-6 flex flex-wrap gap-x-5 gap-y-2 text-xs font-bold text-slate-600">
            @foreach ($profile['extracurriculars'] as $activity)
              <li class="flex items-center gap-2">
                <span class="size-1.5 shrink-0 rounded-full bg-gold-500" aria-hidden="true"></span>
                {{ $activity }}
              </li>
            @endforeach
          </ul>
        </div>

        <div>
          <span class="eyebrow">Fasilitas</span>
          <h2 class="section-title mt-4">Fasilitas yang mendukung belajar.</h2>
          <p class="section-lead">Lingkungan yang aman, bersih, dan nyaman untuk belajar serta beribadah.</p>
          <ul class="mt-6 grid gap-3 sm:grid-cols-2">
            @foreach ($profile['facilities'] as $facility)
              <li class="flex items-start gap-2.5 text-xs font-bold leading-5 text-slate-700">
                <i class="fa-solid fa-circle-check mt-0.5 shrink-0 text-xs text-brand-600" aria-hidden="true"></i>
                <span>{{ $facility }}</span>
              </li>
            @endforeach
          </ul>
        </div>
      </div>
    </div>
  </section>

  <section id="galeri-jenjang" class="section-pad scroll-mt-24 bg-slate-50">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
      <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div class="section-heading">
          <span class="eyebrow">Galeri {{ $profile['short'] }}</span>
          <h2 class="section-title mt-4">Dokumentasi kegiatan jenjang {{ $profile['short'] }}.</h2>
        </div>
        <a href="{{ route('gallery.index', ['jenjang' => $profile['short']]) }}" class="btn btn-outline w-full sm:w-auto">
          Lihat Semua Galeri <i class="fa-solid fa-arrow-right text-xs"></i>
        </a>
      </div>

      @if ($galleries->isNotEmpty())
        <div class="gallery-grid mt-8 columns-1 items-start gap-4 sm:columns-2 lg:columns-4">
          @foreach ($galleries as $gallery)
            <button type="button"
                    class="gallery-item group mb-4 aspect-[4/3] break-inside-avoid border border-white/60 shadow-sm"
                    data-gallery
                    data-adaptive-frame
                    data-full="{{ $gallery->imageUrl() }}"
                    data-caption="{{ $gallery->title }} — {{ $gallery->category }} · {{ $gallery->level }}"
                    aria-label="Buka foto {{ $gallery->title }}">
              <img src="{{ $gallery->imageUrl() }}"
                   alt="{{ $gallery->title }} - {{ $profile['short'] }}"
                   width="800" height="600" loading="lazy" decoding="async"
                   class="adaptive-img">
              <span class="gallery-caption">
                <span class="gallery-caption-title">{{ $gallery->title }}</span>
                <span class="gallery-caption-meta">
                  <span class="gallery-chip">{{ $gallery->category }}</span>
                  <span class="gallery-chip">{{ $gallery->level }}</span>
                </span>
              </span>
              <span class="gallery-zoom" aria-hidden="true">
                <i class="fa-solid fa-expand"></i>
              </span>
            </button>
          @endforeach
        </div>
      @else
        <div class="mt-8 rounded-2xl border border-slate-200 bg-white p-8 text-center shadow-sm">
          <p class="text-sm font-extrabold text-slate-900">Belum ada dokumentasi khusus jenjang {{ $profile['short'] }}.</p>
          <p class="mt-2 text-xs leading-6 text-slate-500">Silakan lihat koleksi galeri sekolah secara keseluruhan.</p>
          <a href="{{ route('gallery.index') }}" class="btn btn-primary mt-5">Lihat Galeri Sekolah</a>
        </div>
      @endif
    </div>
  </section>

  <section class="section-pad bg-white">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
      <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div class="section-heading">
          <span class="eyebrow">Info {{ $profile['short'] }}</span>
          <h2 class="section-title mt-4">Kabar terbaru jenjang {{ $profile['short'] }}.</h2>
        </div>
        <a href="{{ route('info.index', ['jenjang' => $profile['short']]) }}" class="btn btn-outline w-full sm:w-auto">
          Lihat Semua Info <i class="fa-solid fa-arrow-right text-xs"></i>
        </a>
      </div>

      @if ($posts->isNotEmpty())
        <div class="mt-8 grid items-start gap-6 md:grid-cols-2 lg:grid-cols-3">
          @foreach ($posts as $post)
            <article class="news-card">
              <div class="relative overflow-hidden bg-slate-100">
                @include('partials.post-carousel', ['post' => $post])
                <span class="news-category {{ $post->category === 'Prestasi' ? 'news-category--gold' : '' }}">{{ $post->category }}</span>
              </div>
              <div class="p-6">
                <div class="flex flex-wrap items-center gap-2 text-[11px] font-semibold text-slate-400">
                  <span class="inline-flex items-center gap-1.5 rounded-full bg-slate-100 px-2.5 py-1 text-[10px] font-extrabold uppercase tracking-wider text-slate-600">
                    {{ $post->level }}
                  </span>
                  <i class="fa-regular fa-calendar-days text-brand-600" aria-hidden="true"></i>
                  <time datetime="{{ $post->created_at->toDateString() }}">{{ $post->created_at->locale('id')->isoFormat('D MMMM YYYY') }}</time>
                </div>
                <h3 class="mt-3 text-lg font-extrabold leading-7 text-slate-900">
                  <a href="{{ route('info.show', $post->slug) }}" class="transition hover:text-brand-700">{{ $post->title }}</a>
                </h3>
                <p class="mt-3 text-sm leading-6 text-slate-600">{{ $post->excerpt() }}</p>
                <a href="{{ route('info.show', $post->slug) }}" class="news-link mt-5">
                  Baca Selengkapnya <i class="fa-solid fa-arrow-right text-[10px]" aria-hidden="true"></i>
                </a>
              </div>
            </article>
          @endforeach
        </div>
      @else
        <div class="mt-8 rounded-2xl border border-slate-200 bg-slate-50 p-8 text-center">
          <p class="text-sm font-extrabold text-slate-900">Belum ada informasi khusus jenjang {{ $profile['short'] }}.</p>
          <p class="mt-2 text-xs leading-6 text-slate-500">Informasi kegiatan dan prestasi jenjang ini akan tampil di sini.</p>
          <a href="{{ route('info.index') }}" class="btn btn-outline mt-5">Lihat Semua Info Sekolah</a>
        </div>
      @endif
    </div>
  </section>

  @php($levelContact = $contacts[$profile['short']] ?? null)

  @if ($levelContact && (count($instagramPostLinks) > 0 || $instagramFeedPosts->isNotEmpty()))
    @php($instagramHandle = $levelContact['instagram_username'] ?: '@'.strtolower($profile['short']).'islam_cahayailmu')
    <section class="section-pad bg-slate-50">
      <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="overflow-hidden rounded-[2rem] border border-fuchsia-100 bg-white shadow-sm">
          <div class="relative overflow-hidden bg-gradient-to-r from-fuchsia-600 via-rose-500 to-amber-500 px-5 py-6 sm:px-8 sm:py-7">
            <div class="absolute -right-10 -top-16 size-44 rounded-full bg-white/10 blur-2xl" aria-hidden="true"></div>
            <div class="relative flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
              <div class="flex items-center gap-3">
                <span class="grid size-12 shrink-0 place-items-center overflow-hidden rounded-2xl border-2 border-white/70 bg-white p-1.5 shadow-sm sm:size-14">
                  <img src="{{ $levelContact['logo'] }}" alt="Logo {{ $levelContact['name'] }}" loading="lazy" decoding="async" class="h-full w-full object-contain">
                </span>
                <div class="min-w-0">
                  <span class="text-[10px] font-extrabold uppercase tracking-[0.16em] text-white/80">Instagram Resmi {{ $profile['short'] }}</span>
                  <h2 class="mt-1 text-lg font-extrabold leading-tight text-white sm:text-2xl">Update Terbaru Instagram {{ $profile['short'] }}</h2>
                  <p class="mt-1 truncate text-xs font-semibold text-white/85">{{ $instagramHandle }} &middot; {{ $profile['name'] }}</p>
                </div>
              </div>

              @if ($levelContact['instagram_url'] !== '')
                <a href="{{ $levelContact['instagram_url'] }}"
                   target="_blank"
                   rel="noopener noreferrer"
                   class="inline-flex w-full shrink-0 items-center justify-center gap-2 rounded-xl bg-white px-4 py-2.5 text-xs font-extrabold text-fuchsia-700 shadow-sm transition hover:bg-fuchsia-50 sm:w-auto">
                  <i class="fa-brands fa-instagram text-base" aria-hidden="true"></i>
                  Ikuti {{ $instagramHandle }}
                </a>
              @endif
            </div>
          </div>

          <div class="p-4 sm:p-6 lg:p-8">
            @if (count($instagramPostLinks) > 0)
              <div>
                <div class="flex items-center gap-2">
                  <i class="fa-brands fa-instagram text-fuchsia-600" aria-hidden="true"></i>
                  <h3 class="text-sm font-extrabold text-slate-900">Postingan Instagram Pilihan</h3>
                </div>
                <p class="mt-1 text-xs leading-6 text-slate-500">Sematan resmi dari akun {{ $instagramHandle }}.</p>

                <div class="mt-4 grid gap-4 lg:grid-cols-3">
                  @foreach ($instagramPostLinks as $instagramPostUrl)
                    <div class="overflow-hidden rounded-2xl border border-slate-200 bg-slate-50 p-2 sm:p-3">
                      <div class="flex justify-center">
                        <blockquote class="instagram-media"
                                    data-instgrm-permalink="{{ $instagramPostUrl }}"
                                    data-instgrm-version="14"
                                    style="background:#FFF;border:0;border-radius:0.9rem;box-shadow:none;margin:1px;max-width:540px;min-width:260px;padding:0;width:100%;"></blockquote>
                      </div>
                      <a href="{{ $instagramPostUrl }}"
                         target="_blank"
                         rel="noopener noreferrer"
                         class="mt-2 inline-flex w-full items-center justify-center gap-2 rounded-xl border border-fuchsia-100 bg-white px-3 py-2 text-[11px] font-extrabold text-fuchsia-700 transition hover:bg-fuchsia-50">
                        Buka di Instagram <i class="fa-solid fa-arrow-up-right-from-square text-[9px]" aria-hidden="true"></i>
                      </a>
                    </div>
                  @endforeach
                </div>
              </div>
            @endif

            @if ($instagramFeedPosts->isNotEmpty())
              <div class="{{ count($instagramPostLinks) > 0 ? 'mt-8 border-t border-slate-100 pt-8' : '' }}">
                <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                  <div>
                    <h3 class="text-sm font-extrabold text-slate-900">Postingan Info Sekolah Terbaru</h3>
                    <p class="mt-1 text-xs leading-6 text-slate-500">Dokumentasi kegiatan jenjang {{ $profile['short'] }} yang memiliki foto slide atau tautan Instagram.</p>
                  </div>
                  <a href="{{ route('info.index', ['jenjang' => $profile['short']]) }}" class="text-xs font-extrabold text-brand-700 transition hover:text-brand-900">
                    Lihat semua info <i class="fa-solid fa-arrow-right text-[10px]" aria-hidden="true"></i>
                  </a>
                </div>

                <div class="mt-4 grid items-start gap-5 md:grid-cols-2 xl:grid-cols-4">
                  @foreach ($instagramFeedPosts as $post)
                    <article class="news-card">
                      <div class="relative overflow-hidden bg-slate-100">
                        @include('partials.post-carousel', ['post' => $post])
                        <span class="news-category {{ $post->category === 'Prestasi' ? 'news-category--gold' : '' }}">{{ $post->category }}</span>
                        @if ($post->hasInstagramPost())
                          <a href="{{ $post->instagram_url }}"
                             target="_blank"
                             rel="noopener noreferrer"
                             class="absolute bottom-2.5 right-2.5 grid size-8 place-items-center rounded-full bg-gradient-to-br from-fuchsia-600 via-rose-500 to-amber-500 text-white shadow-lg transition hover:scale-105"
                             aria-label="Lihat postingan Instagram {{ $post->title }}">
                            <i class="fa-brands fa-instagram text-sm" aria-hidden="true"></i>
                          </a>
                        @endif
                      </div>
                      <div class="p-4">
                        <time datetime="{{ $post->created_at->toDateString() }}" class="text-[10px] font-bold uppercase tracking-wider text-slate-400">
                          {{ $post->created_at->locale('id')->isoFormat('D MMM YYYY') }}
                        </time>
                        <h4 class="mt-2 text-sm font-extrabold leading-6 text-slate-900">
                          <a href="{{ route('info.show', $post->slug) }}" class="transition hover:text-brand-700">{{ $post->title }}</a>
                        </h4>
                        <a href="{{ route('info.show', $post->slug) }}" class="news-link mt-3">
                          Baca info <i class="fa-solid fa-arrow-right text-[9px]" aria-hidden="true"></i>
                        </a>
                      </div>
                    </article>
                  @endforeach
                </div>
              </div>
            @endif
          </div>
        </div>
      </div>
    </section>

    @push('scripts')
      <script async src="//www.instagram.com/embed.js"></script>
    @endpush
  @endif

  <section class="bg-slate-50 pb-16">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
      <div class="relative overflow-hidden rounded-[2rem] bg-slate-900 px-6 py-10 text-center shadow-xl sm:px-10 lg:px-16 lg:py-14">
        <div class="absolute -right-20 -top-20 size-64 rounded-full bg-brand-600/20 blur-3xl" aria-hidden="true"></div>
        <div class="absolute -bottom-24 -left-20 size-64 rounded-full bg-gold-500/20 blur-3xl" aria-hidden="true"></div>
        <div class="relative mx-auto max-w-2xl">
          <span class="inline-flex items-center gap-2 rounded-full border border-white/15 bg-white/5 px-3 py-1.5 text-[10px] font-extrabold uppercase tracking-[0.16em] text-gold-300">
            SPMB {{ $settings['tahun_ajaran'] ?? '2026/2027' }}
          </span>
          <h2 class="mt-4 text-2xl font-extrabold leading-tight text-white sm:text-3xl">Bergabung dengan {{ $profile['name'] }}.</h2>
          <p class="mt-3 text-sm leading-7 text-slate-300">
            Daftarkan putra-putri Anda dan menjadi bagian dari keluarga besar Cahaya Ilmu Islamic School Balikpapan.
            @if ($profile['slug'] === 'sd')
              <span class="mt-2 block text-xs font-semibold text-gold-300">Syarat usia masuk: Minimal berusia 6 tahun 3 bulan per 1 Juli (kelahiran maksimal 31 Maret).</span>
            @elseif ($profile['slug'] === 'tk' && ! empty($spmbTk))
              <span class="mt-2 block text-xs font-semibold text-gold-300">
                Syarat usia per Juni {{ $spmbTk['school_year'] }}:
                @foreach ($spmbTk['groups'] as $groupKey => $group)
                  {{ $groupKey }} min. {{ $group['min_age_label'] }}@if (! $loop->last), @endif
                @endforeach.
              </span>
            @endif
          </p>

          @if ($levelContact)
            <div class="mt-4 flex flex-wrap items-center justify-center gap-x-5 gap-y-2 text-xs font-semibold text-slate-300">
              <span class="inline-flex items-center gap-2">
                <i class="fa-brands fa-whatsapp text-emerald-400" aria-hidden="true"></i>
                {{ $levelContact['wa_display'] }}
              </span>
              @if ($levelContact['instagram_url'] !== '')
                <a href="{{ $levelContact['instagram_url'] }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2 transition hover:text-fuchsia-300">
                  <i class="fa-brands fa-instagram text-fuchsia-300" aria-hidden="true"></i>
                  {{ $levelContact['instagram_username'] }}
                </a>
              @endif
              @if ($levelContact['youtube_url'] !== '')
                <a href="{{ $levelContact['youtube_url'] }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2 transition hover:text-red-300">
                  <i class="fa-brands fa-youtube text-red-300" aria-hidden="true"></i>
                  {{ $levelContact['youtube_handle'] ?: 'YouTube '.$profile['short'] }}
                </a>
              @endif
            </div>
          @endif

          <div class="mt-7 flex flex-col justify-center gap-3 sm:flex-row sm:flex-wrap">
            <a href="{{ route('ppdb', ['jenjang' => $profile['slug']]) }}" class="btn btn-gold w-full sm:w-auto">
              Daftar SPMB Jenjang Ini <i class="fa-solid fa-arrow-right text-xs"></i>
            </a>
            @if ($levelContact && $levelContact['wa_url'] !== '')
              <a href="{{ $levelContact['wa_url'] }}" target="_blank" rel="noopener noreferrer" class="btn btn-ghost w-full !border-white/20 !text-white hover:!bg-white/10 sm:w-auto">
                <i class="fa-brands fa-whatsapp text-emerald-400"></i> Chat Admin {{ $profile['short'] }}
              </a>
            @endif
            @if ($levelContact && $levelContact['youtube_url'] !== '')
              <a href="{{ $levelContact['youtube_url'] }}" target="_blank" rel="noopener noreferrer" class="btn btn-ghost w-full !border-white/20 !text-white hover:!bg-white/10 sm:w-auto">
                <i class="fa-brands fa-youtube text-red-400"></i> YouTube {{ $profile['short'] }}
              </a>
            @endif
          </div>
        </div>
      </div>
    </div>
  </section>
  </div>
@endsection
