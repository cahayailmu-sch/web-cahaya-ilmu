@php
    $postImages = array_slice($post->allImageUrls(), 0, 10);
    $fallbackImage = 'https://images.unsplash.com/photo-1588072432836-e10032774350?auto=format&fit=crop&w=900&h=600&q=80';
    $images = $postImages !== [] ? $postImages : [$post->youtube_thumbnail_url ?? $fallbackImage];
    $hasCarousel = count($images) > 1;
    $linkable = $linkable ?? true;
    $carouselClass = $carouselClass ?? '';
    $imageClass = $imageClass ?? 'adaptive-img aspect-[4/3] h-auto w-full bg-slate-100 object-cover object-center';
    $eagerFirstImage = $eagerFirstImage ?? false;
    $showVideoBadge = $showVideoBadge ?? true;
    $hasYoutubeVideo = $showVideoBadge && $post->hasYoutubeVideo();
@endphp

@if ($hasCarousel)
    <div class="post-carousel {{ $carouselClass }}"
         data-post-carousel
         data-carousel-autoplay="1500"
         aria-label="Galeri foto {{ $post->title }}"
         aria-roledescription="carousel">
        <div class="post-carousel-track" data-carousel-track>
            @foreach ($images as $index => $image)
                <figure class="post-carousel-slide" data-carousel-slide>
                    <img src="{{ $image }}"
                         alt="{{ $post->title }} - foto {{ $index + 1 }}"
                         loading="{{ $index === 0 && $eagerFirstImage ? 'eager' : 'lazy' }}"
                         decoding="async"
                         class="{{ $imageClass }}">
                </figure>
            @endforeach
        </div>

        <span class="post-carousel-badge" aria-hidden="true">
            <i class="fa-regular fa-clone"></i>
            <span>{{ count($images) }}</span>
        </span>

        @if ($hasYoutubeVideo)
            <span class="absolute left-3 top-3 z-10 inline-flex items-center gap-1.5 rounded-full bg-rose-600 px-2.5 py-1 text-[10px] font-extrabold uppercase tracking-wider text-white shadow-sm">
                <i class="fa-solid fa-play" aria-hidden="true"></i>
                Video
            </span>
        @endif

        <button type="button"
                class="post-carousel-arrow post-carousel-arrow--prev"
                data-carousel-prev
                aria-label="Foto sebelumnya">
            <i class="fa-solid fa-chevron-left" aria-hidden="true"></i>
        </button>

        <button type="button"
                class="post-carousel-arrow post-carousel-arrow--next"
                data-carousel-next
                aria-label="Foto berikutnya">
            <i class="fa-solid fa-chevron-right" aria-hidden="true"></i>
        </button>

        <div class="post-carousel-dots" data-carousel-dots role="group" aria-label="Pilih foto"></div>
    </div>
@else
    <div class="relative">
        @if ($linkable)
            <a href="{{ route('info.show', $post->slug) }}" class="block w-full bg-slate-100" aria-label="Baca {{ $post->title }}">
                <img src="{{ $images[0] }}"
                     alt="{{ $post->title }}"
                     loading="lazy"
                     decoding="async"
                     class="{{ $imageClass }}">
            </a>
        @else
            <img src="{{ $images[0] }}"
                 alt="{{ $post->title }}"
                 loading="{{ $eagerFirstImage ? 'eager' : 'lazy' }}"
                 decoding="async"
                 class="{{ $imageClass }}">
        @endif

        @if ($hasYoutubeVideo)
            <span class="absolute left-3 top-3 z-10 inline-flex items-center gap-1.5 rounded-full bg-rose-600 px-2.5 py-1 text-[10px] font-extrabold uppercase tracking-wider text-white shadow-sm">
                <i class="fa-solid fa-play" aria-hidden="true"></i>
                Video
            </span>
        @endif
    </div>
@endif
