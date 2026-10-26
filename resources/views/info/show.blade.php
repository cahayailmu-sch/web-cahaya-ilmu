@extends('layouts.public')

@section('title', $post->title.' | Info Sekolah Cahaya Ilmu')
@section('description', $post->excerpt(160))
@section('og_image', $post->thumbnailUrl() ?? $post->youtube_thumbnail_url ?? 'https://images.unsplash.com/photo-1588072432836-e10032774350?auto=format&fit=crop&w=1200&h=630&q=80')

@section('content')
  <div class="overflow-x-clip max-w-[100vw]">
  <section class="bg-white pb-4 pt-8 sm:pt-12">
    <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
      <nav class="breadcrumb !text-slate-400" aria-label="Breadcrumb">
        <a href="{{ route('home') }}" class="!text-slate-500 hover:!text-brand-700">Beranda</a>
        <i class="fa-solid fa-chevron-right text-[8px]" aria-hidden="true"></i>
        <a href="{{ route('info.index') }}" class="!text-slate-500 hover:!text-brand-700">Info Sekolah</a>
        <i class="fa-solid fa-chevron-right text-[8px]" aria-hidden="true"></i>
        <span class="min-w-0 max-w-full truncate text-brand-700">{{ $post->title }}</span>
      </nav>

      <div class="mt-6 max-w-4xl">
        <div class="flex flex-wrap items-center gap-2">
          <span class="rounded-full bg-brand-700 px-3 py-1.5 text-[10px] font-extrabold uppercase tracking-[0.12em] text-white">{{ $post->category }}</span>
          <span class="rounded-full border border-slate-200 bg-slate-50 px-3 py-1.5 text-[10px] font-extrabold uppercase tracking-[0.12em] text-slate-600">Jenjang {{ $post->level }}</span>
        </div>

        <h1 class="mt-5 text-2xl font-extrabold leading-tight tracking-tight text-slate-900 sm:text-3xl lg:text-4xl">
          {{ $post->title }}
        </h1>

        <div class="mt-5 flex flex-wrap items-center gap-x-5 gap-y-2 text-xs font-semibold text-slate-500">
          <span class="inline-flex items-center gap-2">
            <i class="fa-regular fa-calendar-days text-brand-600" aria-hidden="true"></i>
            <time datetime="{{ $post->created_at->toDateString() }}">{{ $post->created_at->locale('id')->isoFormat('D MMMM YYYY') }}</time>
          </span>
          <span class="inline-flex items-center gap-2">
            <i class="fa-regular fa-clock text-brand-600" aria-hidden="true"></i>
            {{ $post->readingTime() }} menit baca
          </span>
          <span class="inline-flex items-center gap-2">
            <i class="fa-regular fa-user text-brand-600" aria-hidden="true"></i>
            Admin Cahaya Ilmu
          </span>
        </div>
      </div>

      <figure class="mt-8 overflow-hidden rounded-[1.75rem] border border-slate-100 bg-slate-100 shadow-sm">
        @include('partials.post-carousel', [
            'post' => $post,
            'linkable' => false,
            'eagerFirstImage' => true,
            'carouselClass' => 'post-carousel--feature',
            'imageClass' => 'adaptive-img adaptive-img--contain mx-auto h-auto max-h-[550px] w-full bg-slate-100 object-contain',
            'showVideoBadge' => false,
        ])
      </figure>
    </div>
  </section>

  <section class="section-pad bg-white pt-8">
    <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
      <div class="grid gap-10 lg:grid-cols-[minmax(0,1fr)_340px] lg:items-start">
        <article class="min-w-0">
          @if ($post->youtube_embed_url)
            <div class="mb-8 aspect-video w-full overflow-hidden rounded-2xl shadow-lg">
              <iframe src="{{ $post->youtube_embed_url }}"
                      title="Video YouTube: {{ $post->title }}"
                      class="h-full w-full"
                      loading="lazy"
                      allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                      referrerpolicy="strict-origin-when-cross-origin"
                      allowfullscreen></iframe>
            </div>
          @endif

          <div class="article-content text-[15px] leading-[2.1] text-slate-600" dir="auto">
            {!! nl2br(e($post->content)) !!}
          </div>

          @if ($post->hasInstagramPost())
            <div class="mt-8 rounded-2xl border border-fuchsia-100 bg-fuchsia-50/60 p-5 sm:p-6">
              <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div class="flex items-start gap-3">
                  <span class="grid size-10 shrink-0 place-items-center rounded-xl bg-gradient-to-br from-fuchsia-600 via-rose-500 to-amber-400 text-white shadow-sm">
                    <i class="fa-brands fa-instagram text-lg" aria-hidden="true"></i>
                  </span>
                  <div>
                    <h2 class="text-sm font-extrabold text-slate-900">Postingan Instagram Terkait</h2>
                    <p class="mt-1 text-xs leading-6 text-slate-600">Lihat dokumentasi kegiatan ini langsung dari akun Instagram resmi sekolah.</p>
                  </div>
                </div>
                <a href="{{ $post->instagram_url }}"
                   target="_blank"
                   rel="noopener noreferrer"
                   class="inline-flex shrink-0 items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-fuchsia-600 via-rose-500 to-amber-500 px-4 py-2.5 text-xs font-extrabold text-white shadow-sm transition hover:opacity-90">
                  <i class="fa-brands fa-instagram text-base" aria-hidden="true"></i>
                  Lihat Postingan Asli di Instagram
                </a>
              </div>

              <div class="mt-5 flex justify-center">
                <blockquote class="instagram-media"
                            data-instgrm-permalink="{{ $post->instagram_url }}"
                            data-instgrm-version="14"
                            style="background:#FFF;border:0;border-radius:1rem;box-shadow:0 0 1px 0 rgba(0,0,0,0.5),0 1px 10px 0 rgba(0,0,0,0.15);margin:1px;max-width:540px;min-width:280px;padding:0;width:99.375%;"></blockquote>
              </div>
            </div>
          @endif

          <div class="mt-10 rounded-2xl border border-slate-200 bg-slate-50 p-5 sm:p-6">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
              <div>
                <p class="text-sm font-extrabold text-slate-900">Bagikan informasi ini</p>
                <p class="mt-1 text-xs text-slate-500">Bantu sebarkan kabar baik kepada keluarga dan teman.</p>
              </div>
              <div class="flex flex-wrap gap-2">
                <a href="{{ $whatsappShareUrl }}"
                   target="_blank"
                   rel="noopener noreferrer"
                   class="inline-flex flex-1 items-center justify-center gap-2 rounded-xl bg-emerald-600 px-4 py-2.5 text-xs font-extrabold text-white transition hover:bg-emerald-700 sm:flex-none">
                  <i class="fa-brands fa-whatsapp text-base" aria-hidden="true"></i> WhatsApp
                </a>
                <button type="button"
                        id="copy-link"
                        data-copy-url="{{ $shareUrl }}"
                        class="inline-flex flex-1 items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-xs font-extrabold text-slate-700 transition hover:border-brand-200 hover:text-brand-700 sm:flex-none">
                  <i class="fa-regular fa-copy" aria-hidden="true"></i>
                  <span id="copy-link-label">Copy Link</span>
                </button>
              </div>
            </div>
          </div>

          <div class="mt-8 flex flex-col gap-3 border-t border-slate-100 pt-6 sm:flex-row sm:flex-wrap sm:items-center sm:justify-between">
            <a href="{{ route('info.index') }}" class="inline-flex items-center justify-center gap-2 text-sm font-extrabold text-brand-700 transition hover:text-brand-900 sm:justify-start">
              <i class="fa-solid fa-arrow-left text-xs" aria-hidden="true"></i> Semua Info Sekolah
            </a>
            <a href="{{ route('ppdb') }}" class="btn btn-primary w-full sm:w-auto">Daftar SPMB <i class="fa-solid fa-arrow-right text-xs"></i></a>
          </div>
        </article>

        <aside class="space-y-6 lg:sticky lg:top-24">
          <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <div class="flex items-center justify-between gap-3">
              <h2 class="text-sm font-extrabold text-slate-900">Info Sekolah Lainnya</h2>
              <a href="{{ route('info.index') }}" class="text-[10px] font-extrabold uppercase tracking-wider text-brand-700 hover:text-brand-900">Lihat semua</a>
            </div>

            <div class="mt-4 space-y-4">
              @forelse ($relatedPosts as $related)
                <a href="{{ route('info.show', $related->slug) }}" class="group flex gap-3">
                  @if ($related->thumbnailUrl())
                    <img src="{{ $related->thumbnailUrl() }}" alt="{{ $related->title }}" width="120" height="90" loading="lazy" decoding="async" class="h-16 w-20 shrink-0 rounded-xl bg-slate-100 object-contain">
                  @else
                    <span class="grid h-16 w-20 shrink-0 place-items-center rounded-xl bg-brand-50 text-brand-500">
                      <i class="fa-regular fa-newspaper" aria-hidden="true"></i>
                    </span>
                  @endif
                  <span class="min-w-0">
                    <span class="block text-[10px] font-extrabold uppercase tracking-wider text-brand-700">{{ $related->category }} &middot; {{ $related->level }}</span>
                    <span class="mt-1 line-clamp-2 block text-xs font-bold leading-5 text-slate-800 transition group-hover:text-brand-700">{{ $related->title }}</span>
                    <span class="mt-1 block text-[10px] text-slate-400">{{ $related->created_at->locale('id')->isoFormat('D MMM YYYY') }}</span>
                  </span>
                </a>
              @empty
                <p class="text-xs leading-6 text-slate-500">Belum ada informasi lain yang dipublikasikan.</p>
              @endforelse
            </div>
          </div>

          <div class="overflow-hidden rounded-2xl bg-slate-900 p-6 text-white shadow-sm">
            <span class="inline-flex size-10 items-center justify-center rounded-xl bg-white/10 text-gold-300">
              <i class="fa-solid fa-graduation-cap" aria-hidden="true"></i>
            </span>
            <h2 class="mt-4 text-lg font-extrabold leading-snug">Siap bergabung dengan Cahaya Ilmu?</h2>
            <p class="mt-2 text-xs leading-6 text-slate-300">
              Pendaftaran peserta didik baru dibuka untuk jenjang KB-TK, SD, SMP, dan SMA.
            </p>
            <a href="{{ route('ppdb') }}" class="btn btn-gold mt-5 w-full">Daftar SPMB <i class="fa-solid fa-arrow-right text-xs"></i></a>
            <a href="https://wa.me/{{ $generalWhatsapp }}" target="_blank" rel="noopener noreferrer" class="mt-3 inline-flex w-full items-center justify-center gap-2 text-xs font-bold text-slate-300 transition hover:text-gold-300">
              <i class="fa-brands fa-whatsapp" aria-hidden="true"></i> Tanya admin SPMB
            </a>
          </div>
        </aside>
      </div>
    </div>
  </section>
  </div>
@endsection

@if ($post->hasInstagramPost())
  @push('scripts')
    <script async src="//www.instagram.com/embed.js"></script>
  @endpush
@endif

@push('scripts')
  <script>
    (() => {
      const copyButton = document.getElementById('copy-link');
      const copyLabel = document.getElementById('copy-link-label');
      if (!copyButton || !copyLabel) return;

      copyButton.addEventListener('click', async () => {
        const url = copyButton.dataset.copyUrl || window.location.href;

        try {
          await navigator.clipboard.writeText(url);
          copyLabel.textContent = 'Link Tersalin!';
        } catch (error) {
          const helper = document.createElement('textarea');
          helper.value = url;
          helper.setAttribute('readonly', '');
          helper.style.position = 'fixed';
          helper.style.opacity = '0';
          document.body.appendChild(helper);
          helper.select();
          document.execCommand('copy');
          document.body.removeChild(helper);
          copyLabel.textContent = 'Link Tersalin!';
        }

        window.setTimeout(() => {
          copyLabel.textContent = 'Copy Link';
        }, 2200);
      });
    })();
  </script>
@endpush
