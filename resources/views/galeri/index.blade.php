@extends('layouts.public')

@section('title', 'Galeri Sekolah | Cahaya Ilmu Islamic School Balikpapan')
@section('description', 'Dokumentasi kegiatan, fasilitas, dan momen kebersamaan siswa Cahaya Ilmu Islamic School Balikpapan.')

@section('content')
  <div class="overflow-x-clip max-w-[100vw]">
  <section class="page-hero relative overflow-hidden">
    <div class="mx-auto max-w-7xl px-4 py-14 sm:px-6 sm:py-20 lg:px-8">
      <nav class="breadcrumb" aria-label="Breadcrumb">
        <a href="{{ route('home') }}">Beranda</a>
        <i class="fa-solid fa-chevron-right text-[8px]" aria-hidden="true"></i>
        <span class="text-gold-300">Galeri Sekolah</span>
      </nav>

      <div class="mt-6 grid gap-10 lg:grid-cols-[1.4fr_0.6fr] lg:items-end">
        <div>
          <span class="inline-flex items-center gap-2 rounded-full border border-white/15 bg-white/5 px-3 py-1.5 text-[10px] font-extrabold uppercase tracking-[0.16em] text-gold-300">
            <i class="fa-regular fa-images" aria-hidden="true"></i> Galeri Sekolah
          </span>
          <h1 class="mt-5 text-3xl font-extrabold leading-tight tracking-tight text-white sm:text-4xl lg:text-5xl">
            Momen belajar, berkarya, dan bertumbuh.
          </h1>
          <p class="mt-4 max-w-2xl text-sm leading-7 text-slate-200/75 sm:text-base">
            Kumpulan dokumentasi kegiatan, fasilitas, dan kebersamaan keluarga besar
            Cahaya Ilmu Islamic School Balikpapan dari seluruh jenjang pendidikan.
          </p>
        </div>

        <div class="page-hero-card">
          <div class="page-hero-card-row">
            <span class="text-slate-200/60">Total foto</span>
            <span class="font-extrabold text-white">{{ number_format($galleries->total()) }} dokumentasi</span>
          </div>
          <div class="page-hero-card-row">
            <span class="text-slate-200/60">Jenjang</span>
            <span class="font-extrabold text-gold-300">{{ implode(' · ', $levels) }}</span>
          </div>
          <div class="page-hero-card-row">
            <span class="text-slate-200/60">Kategori</span>
            <span class="font-extrabold text-white">{{ $categories->isNotEmpty() ? $categories->implode(' · ') : 'Fasilitas · Kegiatan' }}</span>
          </div>
        </div>
      </div>
    </div>
  </section>

  <section class="section-pad bg-slate-50">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
      <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
        <div class="flex flex-wrap gap-2" role="tablist" aria-label="Filter jenjang galeri">
          <a href="{{ route('gallery.index', array_filter(['kategori' => $activeCategory])) }}"
             @class(['gallery-filter-tab', 'is-active' => $activeLevel === ''])
             role="tab"
             aria-selected="{{ $activeLevel === '' ? 'true' : 'false' }}">
            Semua
          </a>
          @foreach ($levels as $level)
            <a href="{{ route('gallery.index', array_filter(['jenjang' => $level, 'kategori' => $activeCategory])) }}"
               @class(['gallery-filter-tab', 'is-active' => $activeLevel === $level])
               role="tab"
               aria-selected="{{ $activeLevel === $level ? 'true' : 'false' }}">
              {{ $level }}
            </a>
          @endforeach
        </div>

        <form method="GET" action="{{ route('gallery.index') }}" class="flex w-full items-center gap-2 sm:w-auto">
          @if ($activeLevel !== '')
            <input type="hidden" name="jenjang" value="{{ $activeLevel }}">
          @endif
          <label for="gallery-category" class="shrink-0 text-[10px] font-extrabold uppercase tracking-wider text-slate-500">Kategori</label>
          <select id="gallery-category"
                  name="kategori"
                  class="min-w-0 flex-1 rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-slate-700 outline-none transition focus:border-brand-400 focus:ring-4 focus:ring-brand-100 sm:flex-none"
                  onchange="this.form.submit()">
            <option value="">Semua Kategori</option>
            @foreach ($categories as $category)
              <option value="{{ $category }}" @selected($activeCategory === $category)>{{ $category }}</option>
            @endforeach
          </select>
          @if ($activeCategory !== '' || $activeLevel !== '')
            <a href="{{ route('gallery.index') }}" class="grid size-9 place-items-center rounded-xl border border-slate-200 bg-white text-slate-500 transition hover:border-brand-200 hover:text-brand-700" aria-label="Reset filter galeri">
              <i class="fa-solid fa-rotate-left text-xs" aria-hidden="true"></i>
            </a>
          @endif
        </form>
      </div>

      <p class="mt-5 text-xs font-semibold text-slate-500">
        Menampilkan {{ $galleries->firstItem() ?? 0 }}&ndash;{{ $galleries->lastItem() ?? 0 }} dari {{ $galleries->total() }} foto
        @if ($activeLevel !== '')
          &middot; jenjang <span class="text-brand-700">{{ $activeLevel }}</span>
        @endif
        @if ($activeCategory !== '')
          &middot; kategori <span class="text-brand-700">{{ $activeCategory }}</span>
        @endif
      </p>

      @if ($galleries->isNotEmpty())
        <div class="gallery-grid mt-7 columns-1 items-start gap-4 sm:columns-2 lg:columns-3 xl:columns-4">
          @foreach ($galleries as $gallery)
            <button type="button"
                    class="gallery-item group mb-4 aspect-[4/3] break-inside-avoid border border-white/60 shadow-sm"
                    data-gallery
                    data-adaptive-frame
                    data-full="{{ $gallery->imageUrl() }}"
                    data-caption="{{ $gallery->title }} — {{ $gallery->category }} · {{ $gallery->level }}"
                    data-date="{{ $gallery->documentationDateLabel() }}"
                    aria-label="Buka foto {{ $gallery->title }}">
              <img src="{{ $gallery->imageUrl() }}"
                   alt="{{ $gallery->title }} - {{ $gallery->category }} {{ $gallery->level }}"
                   width="800" height="600" loading="lazy" decoding="async"
                   class="adaptive-img">
              <span class="gallery-caption">
                <span class="gallery-caption-title">{{ $gallery->title }}</span>
                <span class="gallery-caption-date">
                  <i class="fa-regular fa-calendar-days" aria-hidden="true"></i>
                  {{ $gallery->documentationDateLabel() }}
                </span>
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

        <div class="mt-10">
          {{ $galleries->links() }}
        </div>
      @else
        <div class="mt-10 rounded-2xl border border-slate-200 bg-white p-10 text-center shadow-sm">
          <span class="mx-auto grid size-14 place-items-center rounded-2xl bg-brand-50 text-xl text-brand-600">
            <i class="fa-regular fa-images" aria-hidden="true"></i>
          </span>
          <h2 class="mt-5 text-lg font-extrabold text-slate-900">Belum ada foto untuk filter ini</h2>
          <p class="mx-auto mt-2 max-w-lg text-sm leading-6 text-slate-500">
            Dokumentasi untuk jenjang atau kategori yang dipilih belum tersedia. Silakan lihat koleksi lainnya.
          </p>
          <a href="{{ route('gallery.index') }}" class="btn btn-primary mt-6">Lihat Semua Galeri</a>
        </div>
      @endif

      <div class="mt-12 flex flex-col items-center justify-between gap-4 rounded-2xl border border-slate-200 bg-white p-5 text-center shadow-sm sm:flex-row sm:p-6 sm:text-left">
        <div>
          <p class="text-sm font-extrabold text-slate-900">Ingin melihat sekolah kami secara langsung?</p>
          <p class="mt-1 text-xs leading-6 text-slate-500">Jadwalkan kunjungan atau tanyakan informasi SPMB kepada admin kami.</p>
        </div>
        <div class="flex w-full flex-col justify-center gap-2 sm:w-auto sm:flex-row sm:flex-wrap">
          <a href="{{ route('ppdb') }}" class="btn btn-primary w-full sm:w-auto">Daftar SPMB <i class="fa-solid fa-arrow-right text-xs"></i></a>
          <a href="https://wa.me/{{ $generalWhatsapp }}" target="_blank" rel="noopener noreferrer" class="btn btn-outline w-full sm:w-auto">
            <i class="fa-brands fa-whatsapp text-rose-600"></i> Hubungi Admin
          </a>
        </div>
      </div>
    </div>
  </section>
  </div>
@endsection
