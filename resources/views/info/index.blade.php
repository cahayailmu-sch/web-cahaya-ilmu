@extends('layouts.public')

@section('title', 'Info Sekolah | Cahaya Ilmu Islamic School Balikpapan')
@section('description', 'Kumpulan berita, pengumuman, dan prestasi siswa Cahaya Ilmu Islamic School Balikpapan.')

@section('content')
  <div class="overflow-x-clip max-w-[100vw]">
  <section class="page-hero relative overflow-hidden">
    <div class="mx-auto max-w-7xl px-4 py-14 sm:px-6 sm:py-20 lg:px-8">
      <nav class="breadcrumb" aria-label="Breadcrumb">
        <a href="{{ route('home') }}">Beranda</a>
        <i class="fa-solid fa-chevron-right text-[8px]" aria-hidden="true"></i>
        <span class="text-gold-300">Info Sekolah</span>
      </nav>

      <div class="mt-6 grid gap-10 lg:grid-cols-[1.4fr_0.6fr] lg:items-end">
        <div>
          <span class="inline-flex items-center gap-2 rounded-full border border-white/15 bg-white/5 px-3 py-1.5 text-[10px] font-extrabold uppercase tracking-[0.16em] text-gold-300">
            <i class="fa-regular fa-newspaper" aria-hidden="true"></i> Pusat Informasi
          </span>
          <h1 class="mt-5 text-3xl font-extrabold leading-tight tracking-tight text-white sm:text-4xl lg:text-5xl">
            Kabar, pengumuman, dan prestasi sekolah.
          </h1>
          <p class="mt-4 max-w-2xl text-sm leading-7 text-slate-200/75 sm:text-base">
            Ikuti perjalanan siswa-siswi Cahaya Ilmu Islamic School Balikpapan melalui berita kegiatan,
            pengumuman resmi, dan capaian prestasi dari setiap jenjang pendidikan.
          </p>
        </div>

        <div class="page-hero-card">
          <div class="page-hero-card-row">
            <span class="text-slate-200/60">Total informasi</span>
            <span class="font-extrabold text-white">{{ number_format($posts->total()) }} artikel</span>
          </div>
          <div class="page-hero-card-row">
            <span class="text-slate-200/60">Kategori</span>
            <span class="font-extrabold text-gold-300">{{ implode(' · ', $categories) }}</span>
          </div>
          <div class="page-hero-card-row">
            <span class="text-slate-200/60">Jenjang</span>
            <span class="font-extrabold text-white">{{ implode(' · ', $levels) }}</span>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section class="section-pad bg-slate-50">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
      <form method="GET" action="{{ route('info.index') }}" class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm sm:p-5">
        <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-[1fr_auto_auto_auto]">
          <div class="sm:col-span-2 lg:col-span-1">
            <label for="info-search" class="sr-only">Cari judul informasi</label>
            <div class="relative">
              <i class="fa-solid fa-magnifying-glass pointer-events-none absolute left-3.5 top-1/2 -translate-y-1/2 text-xs text-slate-400" aria-hidden="true"></i>
              <input id="info-search"
                     type="search"
                     name="q"
                     value="{{ $search }}"
                     placeholder="Cari judul berita, pengumuman, atau prestasi..."
                     class="w-full rounded-xl border border-slate-200 bg-slate-50 py-2.5 pl-9 pr-3 text-sm text-slate-700 outline-none transition placeholder:text-slate-400 focus:border-brand-400 focus:bg-white focus:ring-4 focus:ring-brand-100">
            </div>
          </div>

          <div>
            <label for="info-category" class="sr-only">Filter kategori</label>
            <select id="info-category"
                    name="kategori"
                    class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm font-semibold text-slate-700 outline-none transition focus:border-brand-400 focus:bg-white focus:ring-4 focus:ring-brand-100 lg:w-44"
                    onchange="this.form.submit()">
              <option value="">Semua Kategori</option>
              @foreach ($categories as $category)
                <option value="{{ $category }}" @selected($activeCategory === $category)>{{ $category }}</option>
              @endforeach
            </select>
          </div>

          <div>
            <label for="info-level" class="sr-only">Filter jenjang</label>
            <select id="info-level"
                    name="jenjang"
                    class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2.5 text-sm font-semibold text-slate-700 outline-none transition focus:border-brand-400 focus:bg-white focus:ring-4 focus:ring-brand-100 lg:w-36"
                    onchange="this.form.submit()">
              <option value="">Semua Jenjang</option>
              @foreach ($levels as $level)
                <option value="{{ $level }}" @selected($activeLevel === $level)>{{ $level }}</option>
              @endforeach
            </select>
          </div>

          <div class="flex gap-2 sm:col-span-2 lg:col-span-1">
            <button type="submit" class="btn btn-primary flex-1 lg:flex-none">
              <i class="fa-solid fa-magnifying-glass text-xs" aria-hidden="true"></i> Cari
            </button>
            @if ($search !== '' || $activeCategory !== '' || $activeLevel !== '')
              <a href="{{ route('info.index') }}" class="btn btn-outline" aria-label="Reset filter">
                <i class="fa-solid fa-rotate-left text-xs" aria-hidden="true"></i>
              </a>
            @endif
          </div>
        </div>
      </form>

      <div class="mt-6 flex flex-wrap items-center justify-between gap-3">
        <p class="text-xs font-semibold text-slate-500">
          Menampilkan {{ $posts->firstItem() ?? 0 }}&ndash;{{ $posts->lastItem() ?? 0 }} dari {{ $posts->total() }} informasi
          @if ($activeCategory !== '')
            &middot; kategori <span class="text-brand-700">{{ $activeCategory }}</span>
          @endif
          @if ($activeLevel !== '')
            &middot; jenjang <span class="text-brand-700">{{ $activeLevel }}</span>
          @endif
          @if ($search !== '')
            &middot; pencarian &ldquo;{{ $search }}&rdquo;
          @endif
        </p>
        <a href="{{ route('home') }}#berita" class="text-xs font-extrabold text-brand-700 transition hover:text-brand-900">
          <i class="fa-solid fa-arrow-left mr-1 text-[10px]" aria-hidden="true"></i> Kembali ke beranda
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
                  <span class="h-1 w-1 rounded-full bg-slate-300"></span>
                  <span>{{ $post->readingTime() }} menit baca</span>
                </div>
                <h2 class="mt-3 text-lg font-extrabold leading-7 text-slate-900">
                  <a href="{{ route('info.show', $post->slug) }}" class="transition hover:text-brand-700">{{ $post->title }}</a>
                </h2>
                <p class="mt-3 text-sm leading-6 text-slate-600">{{ $post->excerpt() }}</p>
                <a href="{{ route('info.show', $post->slug) }}" class="news-link mt-5">
                  Baca Selengkapnya <i class="fa-solid fa-arrow-right text-[10px]" aria-hidden="true"></i>
                </a>
              </div>
            </article>
          @endforeach
        </div>

        <div class="mt-10">
          {{ $posts->links() }}
        </div>
      @else
        <div class="mt-10 rounded-2xl border border-slate-200 bg-white p-10 text-center shadow-sm">
          <span class="mx-auto grid size-14 place-items-center rounded-2xl bg-brand-50 text-xl text-brand-600">
            <i class="fa-regular fa-newspaper" aria-hidden="true"></i>
          </span>
          <h2 class="mt-5 text-lg font-extrabold text-slate-900">Belum ada informasi yang sesuai</h2>
          <p class="mx-auto mt-2 max-w-lg text-sm leading-6 text-slate-500">
            Coba ubah kata kunci, kategori, atau jenjang yang dipilih. Informasi terbaru akan tampil di sini.
          </p>
          <a href="{{ route('info.index') }}" class="btn btn-primary mt-6">Lihat Semua Info Sekolah</a>
        </div>
      @endif
    </div>
  </section>

  @if (isset($agendas) && $agendas->isNotEmpty())
    <section id="agenda-sekolah" class="section-pad bg-white border-t border-slate-100">
      <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="section-heading section-heading--center reveal">
          <span class="eyebrow">Kalender Kegiatan</span>
          <h2 class="section-title mt-4">Agenda Kegiatan Sekolah</h2>
          <p class="section-lead mx-auto">
            Jadwal kegiatan akademik, keislaman, dan kemitraan orang tua di lingkungan Cahaya Ilmu Islamic School.
          </p>
        </div>

        <div class="mt-10 grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
          @foreach ($agendas as $agenda)
            <article class="flex flex-col justify-between rounded-3xl border border-slate-200 bg-slate-50/70 p-6 shadow-sm">
              <div>
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

                <h3 class="mt-4 text-base font-extrabold leading-snug text-slate-900">{{ $agenda->title }}</h3>
                @if ($agenda->description)
                  <p class="mt-2 text-xs leading-relaxed text-slate-600">{{ $agenda->description }}</p>
                @endif
              </div>

              <div class="mt-4 pt-3.5 border-t border-slate-200/70 space-y-1.5 text-xs text-slate-500">
                @if ($agenda->time_label)
                  <p class="flex items-center gap-2">
                    <i class="fa-regular fa-clock text-brand-600 w-3.5 text-center"></i>
                    <span>{{ $agenda->time_label }}</span>
                  </p>
                @endif
                @if ($agenda->location)
                  <p class="flex items-center gap-2">
                    <i class="fa-solid fa-location-dot text-gold-600 w-3.5 text-center"></i>
                    <span>{{ $agenda->location }}</span>
                  </p>
                @endif
              </div>
            </article>
          @endforeach
        </div>
      </div>
    </section>
  @endif

  <section class="section-pad bg-white">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
      <div class="section-heading section-heading--center reveal">
        <span class="eyebrow">Instagram Resmi</span>
        <h2 class="section-title mt-4">Update Terbaru Instagram per Jenjang</h2>
        <p class="section-lead mx-auto">
          Ikuti dokumentasi kegiatan dan prestasi terbaru dari akun Instagram resmi TK, SD, SMP, dan SMA
          Cahaya Ilmu Islamic School Balikpapan.
        </p>
      </div>

      <div class="mt-10 grid items-start gap-6 sm:grid-cols-2 xl:grid-cols-4">
        @foreach ($instagramFeeds as $level => $feedPosts)
          @php
            $contact = $contacts[$level] ?? null;
            $instagramHandle = $contact['instagram_username'] ?? '@'.strtolower($level).'islam_cahayailmu';
          @endphp
          <article class="flex flex-col overflow-hidden rounded-[1.75rem] border border-slate-200 bg-white shadow-sm">
            <div class="relative overflow-hidden bg-gradient-to-r from-fuchsia-600 via-rose-500 to-amber-500 p-5">
              <div class="absolute -right-8 -top-10 size-28 rounded-full bg-white/10 blur-2xl" aria-hidden="true"></div>
              <div class="relative flex items-center gap-3">
                <span class="grid size-12 shrink-0 place-items-center overflow-hidden rounded-2xl border-2 border-white/70 bg-white p-1">
                  <img src="{{ $contact['logo'] ?? '/assets/images/logo-utama.png' }}" alt="Logo {{ $level }} Islam Cahaya Ilmu" loading="lazy" decoding="async" class="h-full w-full object-contain">
                </span>
                <div class="min-w-0">
                  <p class="text-[9px] font-extrabold uppercase tracking-[0.14em] text-white/80">Update Terbaru Instagram {{ $level }}</p>
                  <p class="mt-1 truncate text-sm font-extrabold text-white">{{ $instagramHandle }}</p>
                </div>
                @if ($contact && $contact['instagram_url'] !== '')
                  <a href="{{ $contact['instagram_url'] }}"
                     target="_blank"
                     rel="noopener noreferrer"
                     class="ml-auto grid size-8 shrink-0 place-items-center rounded-full bg-white/20 text-white transition hover:bg-white/30"
                     aria-label="Ikuti Instagram {{ $level }}">
                    <i class="fa-brands fa-instagram text-sm" aria-hidden="true"></i>
                  </a>
                @endif
              </div>
            </div>

            <div class="flex flex-1 flex-col p-5">
              @forelse ($feedPosts as $post)
                <a href="{{ route('info.show', $post->slug) }}" class="group flex gap-3 border-b border-slate-100 pb-3 transition last:border-0 last:pb-0 hover:text-brand-700 [&+&]:pt-3">
                  <span class="size-16 shrink-0 overflow-hidden rounded-xl bg-slate-100">
                    <img src="{{ $post->primaryImageUrl() ?? 'https://images.unsplash.com/photo-1588072432836-e10032774350?auto=format&fit=crop&w=300&h=300&q=80' }}"
                         alt="{{ $post->title }}"
                         width="120"
                         height="120"
                         loading="lazy"
                         decoding="async"
                         class="h-full w-full bg-slate-100 object-contain">
                  </span>
                  <span class="min-w-0">
                    <span class="block text-[9px] font-extrabold uppercase tracking-wider text-fuchsia-600">{{ $post->category }}</span>
                    <span class="mt-1 line-clamp-2 block text-xs font-bold leading-5 text-slate-800 transition group-hover:text-brand-700">{{ $post->title }}</span>
                    <span class="mt-1 block text-[10px] text-slate-400">{{ $post->created_at->locale('id')->isoFormat('D MMM YYYY') }}</span>
                  </span>
                </a>
              @empty
                <p class="flex-1 text-xs leading-6 text-slate-500">Belum ada postingan info sekolah dengan foto slide atau tautan Instagram untuk jenjang ini.</p>
              @endforelse

              <a href="{{ route('jenjang.show', strtolower($level)) }}" class="mt-4 inline-flex items-center gap-2 text-[11px] font-extrabold text-brand-700 transition hover:text-brand-900">
                Kunjungi Halaman {{ $level }} <i class="fa-solid fa-arrow-right text-[9px]" aria-hidden="true"></i>
              </a>
            </div>
          </article>
        @endforeach
      </div>
    </div>
  </section>
  </div>
@endsection
