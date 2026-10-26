@php
    $post = $post ?? null;
    $editing = $post !== null;
    $existingSlideImages = is_array($post?->images) ? $post->images : [];
@endphp

<form method="POST"
      action="{{ $editing ? route('admin.posts.update', $post) : route('admin.posts.store') }}"
      enctype="multipart/form-data"
      accept-charset="UTF-8"
      class="grid gap-6 xl:grid-cols-3">
    @csrf
    @if ($editing)
        @method('PUT')
    @endif

    <div class="space-y-6 xl:col-span-2">
        <section class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm sm:p-6">
            <div class="space-y-5">
                <div>
                    <label for="title" class="block text-sm font-medium text-slate-700">Judul Info</label>
                    <input id="title"
                           name="title"
                           type="text"
                           value="{{ old('title', $post?->title) }}"
                           required
                           placeholder="Contoh: SPMB 2026/2027"
                           @class([
                               'mt-1.5 block w-full rounded-xl border bg-white px-3.5 py-2.5 text-sm shadow-sm outline-none transition placeholder:text-slate-400 focus:ring-4',
                               'border-rose-300 focus:border-rose-400 focus:ring-rose-100' => $errors->has('title'),
                               'border-slate-200 focus:border-rose-400 focus:ring-rose-100' => ! $errors->has('title'),
                           ])>
                    @error('title')
                        <p class="mt-1.5 text-xs text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="slug" class="block text-sm font-medium text-slate-700">Slug <span class="font-normal text-slate-400">(opsional)</span></label>
                    <input id="slug"
                           name="slug"
                           type="text"
                           value="{{ old('slug', $post?->slug) }}"
                           placeholder="otomatis-dari-judul"
                           @class([
                               'mt-1.5 block w-full rounded-xl border bg-white px-3.5 py-2.5 text-sm shadow-sm outline-none transition placeholder:text-slate-400 focus:ring-4',
                               'border-rose-300 focus:border-rose-400 focus:ring-rose-100' => $errors->has('slug'),
                               'border-slate-200 focus:border-rose-400 focus:ring-rose-100' => ! $errors->has('slug'),
                           ])>
                    <p class="mt-1.5 text-[11px] text-slate-400">Kosongkan untuk membuat slug otomatis dari judul.</p>
                    @error('slug')
                        <p class="mt-1.5 text-xs text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="content" class="block text-sm font-medium text-slate-700">Isi Info</label>
                    <textarea id="content"
                              name="content"
                              rows="14"
                              required
                              dir="auto"
                              placeholder="Tulis isi info atau pengumuman di sini..."
                              @class([
                                  'arabic-input mt-1.5 block w-full rounded-xl border bg-white px-3.5 py-2.5 text-sm leading-relaxed shadow-sm outline-none transition placeholder:text-slate-400 focus:ring-4',
                                  'border-rose-300 focus:border-rose-400 focus:ring-rose-100' => $errors->has('content'),
                                  'border-slate-200 focus:border-rose-400 focus:ring-rose-100' => ! $errors->has('content'),
                              ])>{{ old('content', $post?->content) }}</textarea>
                    <p class="mt-1.5 text-[11px] leading-5 text-slate-400">
                        Mendukung teks Arab berharakat (UTF-8). Ayat Al-Qur'an, hadits, atau kosakata Arab
                        dapat langsung diketik maupun di-copy-paste ke kolom ini.
                    </p>
                    @error('content')
                        <p class="mt-1.5 text-xs text-rose-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </section>
    </div>

    <div class="space-y-6">
        <section class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm sm:p-6">
            <h2 class="text-sm font-semibold text-slate-900">Publikasi</h2>

            <div class="mt-5 space-y-5">
                <div>
                    <label for="category" class="block text-sm font-medium text-slate-700">Kategori</label>
                    <select id="category"
                            name="category"
                            required
                            class="mt-1.5 block w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm shadow-sm outline-none transition focus:border-rose-400 focus:ring-4 focus:ring-rose-100">
                        @foreach (['Berita', 'Pengumuman', 'Prestasi'] as $category)
                            <option value="{{ $category }}" @selected(old('category', $post?->category ?? 'Berita') === $category)>{{ $category }}</option>
                        @endforeach
                    </select>
                    @error('category')
                        <p class="mt-1.5 text-xs text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="level" class="block text-sm font-medium text-slate-700">Jenjang</label>
                    @if (auth()->user()->isSuperAdmin())
                        <select id="level"
                                name="level"
                                required
                                class="mt-1.5 block w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm shadow-sm outline-none transition focus:border-rose-400 focus:ring-4 focus:ring-rose-100">
                            @foreach (['Umum', 'TK', 'SD', 'SMP', 'SMA'] as $level)
                                <option value="{{ $level }}" @selected(old('level', $post?->level ?? 'Umum') === $level)>{{ $level }}</option>
                            @endforeach
                        </select>
                    @else
                        <input type="hidden" name="level" value="{{ auth()->user()->getLevel() }}">
                        <input id="level"
                               type="text"
                               value="{{ auth()->user()->getLevel() }}"
                               disabled
                               class="mt-1.5 block w-full cursor-not-allowed rounded-xl border border-slate-200 bg-slate-100 px-3.5 py-2.5 text-sm font-semibold text-slate-600 shadow-sm">
                        <p class="mt-1 text-[11px] text-slate-400">Terkunci otomatis sesuai akun Admin {{ auth()->user()->getLevel() }}.</p>
                    @endif
                    @error('level')
                        <p class="mt-1.5 text-xs text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="border-t border-slate-100 pt-5">
                    <label for="thumbnail" class="block text-sm font-medium text-slate-700">Thumbnail</label>

                    <div class="mt-2">
                        <img id="thumbnail-preview"
                             src="{{ $post?->thumbnailUrl() ?? '' }}"
                             alt="Pratinjau thumbnail"
                             @class([
                                 'aspect-video w-full rounded-xl border border-slate-200 object-cover',
                                 'hidden' => ! $post?->thumbnailUrl(),
                             ])>
                    </div>

                    <input id="thumbnail"
                           name="thumbnail"
                           type="file"
                           accept="image/jpeg,image/png,image/webp"
                           class="mt-3 block w-full text-xs text-slate-500 file:mr-3 file:rounded-lg file:border-0 file:bg-slate-100 file:px-3 file:py-2 file:text-xs file:font-semibold file:text-slate-700 hover:file:bg-slate-200">
                    <p class="mt-1.5 text-[11px] text-slate-400">JPG, PNG, atau WEBP. Maksimal 6 MB.</p>

                    @if ($editing && $post->thumbnail)
                        <label class="mt-3 flex items-center gap-2 text-xs text-slate-600">
                            <input type="checkbox" name="remove_thumbnail" value="1" class="size-4 rounded border-slate-300 text-rose-600 focus:ring-rose-500">
                            Hapus thumbnail saat ini
                        </label>
                    @endif

                    @error('thumbnail')
                        <p class="mt-1.5 text-xs text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="border-t border-slate-100 pt-5">
                    <label for="images" class="block text-sm font-medium text-slate-700">Foto Slide Dokumentasi</label>
                    <p class="mt-1 text-[11px] leading-5 text-slate-400">
                        Unggah beberapa foto sekaligus untuk ditampilkan sebagai carousel di halaman Info Sekolah.
                    </p>

                    @if ($editing && $existingSlideImages !== [])
                        <div class="mt-3 grid grid-cols-2 gap-2">
                            @foreach ($existingSlideImages as $imagePath)
                                @if (is_string($imagePath) && trim($imagePath) !== '')
                                    <label class="group relative block overflow-hidden rounded-xl border border-slate-200">
                                        <img src="{{ $post->imageUrl($imagePath) }}"
                                             alt="Foto slide {{ $loop->iteration }}"
                                             class="aspect-square w-full object-cover">
                                        <span class="absolute inset-x-0 bottom-0 flex items-center gap-1.5 bg-slate-900/75 px-2 py-1.5 text-[10px] font-semibold text-white">
                                            <input type="checkbox"
                                                   name="remove_images[]"
                                                   value="{{ $imagePath }}"
                                                   class="size-3.5 rounded border-slate-300 text-rose-600 focus:ring-rose-500">
                                            Hapus foto
                                        </span>
                                    </label>
                                @endif
                            @endforeach
                        </div>
                    @endif

                    <input id="images"
                           name="images[]"
                           type="file"
                           multiple
                           accept="image/jpeg,image/png,image/webp"
                           class="mt-3 block w-full text-xs text-slate-500 file:mr-3 file:rounded-lg file:border-0 file:bg-slate-100 file:px-3 file:py-2 file:text-xs file:font-semibold file:text-slate-700 hover:file:bg-slate-200">
                    <p class="mt-1.5 text-[11px] text-slate-400">Maksimal 10 foto. JPG, PNG, atau WEBP. Maksimal 6 MB per foto.</p>

                    <div id="images-preview" class="mt-3 grid grid-cols-2 gap-2 sm:grid-cols-3"></div>

                    @error('images')
                        <p class="mt-1.5 text-xs text-rose-600">{{ $message }}</p>
                    @enderror
                    @error('images.*')
                        <p class="mt-1.5 text-xs text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="border-t border-slate-100 pt-5">
                    <label for="instagram_url" class="block text-sm font-medium text-slate-700">
                        Link Postingan / Reels Instagram <span class="font-normal text-slate-400">(opsional)</span>
                    </label>
                    <input id="instagram_url"
                           name="instagram_url"
                           type="url"
                           value="{{ old('instagram_url', $post?->instagram_url) }}"
                           placeholder="https://www.instagram.com/p/XXXXXXXXXXX/"
                           class="mt-1.5 block w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm shadow-sm outline-none transition placeholder:text-slate-400 focus:border-rose-400 focus:ring-4 focus:ring-rose-100">
                    <p class="mt-1.5 text-[11px] leading-5 text-slate-400">
                        Jika diisi, postingan Instagram akan disematkan otomatis di halaman detail info ini.
                    </p>
                    @error('instagram_url')
                        <p class="mt-1.5 text-xs text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="border-t border-slate-100 pt-5">
                    <label for="youtube_url" class="block text-sm font-medium text-slate-700">
                        Link Video YouTube <span class="font-normal text-slate-400">(opsional)</span>
                    </label>
                    <input id="youtube_url"
                           name="youtube_url"
                           type="url"
                           value="{{ old('youtube_url', $post?->youtube_url) }}"
                           placeholder="https://www.youtube.com/watch?v=... atau https://youtu.be/..."
                           @class([
                               'mt-1.5 block w-full rounded-xl border bg-white px-3.5 py-2.5 text-sm shadow-sm outline-none transition placeholder:text-slate-400 focus:ring-4',
                               'border-rose-300 focus:border-rose-400 focus:ring-rose-100' => $errors->has('youtube_url'),
                               'border-slate-200 focus:border-rose-400 focus:ring-rose-100' => ! $errors->has('youtube_url'),
                           ])>
                    <p class="mt-1.5 text-[11px] leading-5 text-slate-400">
                        Kosongkan jika berita ini tidak memiliki video YouTube.
                    </p>

                    <div id="youtube-preview" class="mt-3 hidden overflow-hidden rounded-xl border border-slate-200 bg-slate-900">
                        <div class="aspect-video w-full">
                            <iframe id="youtube-preview-frame"
                                    src=""
                                    title="Pratinjau video YouTube"
                                    class="h-full w-full"
                                    loading="lazy"
                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                                    allowfullscreen></iframe>
                        </div>
                    </div>

                    @error('youtube_url')
                        <p class="mt-1.5 text-xs text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="border-t border-slate-100 pt-5">
                    <input type="hidden" name="is_published" value="0">
                    <label class="flex items-start gap-2.5">
                        <input type="checkbox"
                               name="is_published"
                               value="1"
                               @checked(old('is_published', $post?->is_published ?? true))
                               class="mt-0.5 size-4 rounded border-slate-300 text-rose-600 focus:ring-rose-500">
                        <span>
                            <span class="block text-sm font-medium text-slate-700">Terbitkan info</span>
                            <span class="block text-xs text-slate-400">Jika tidak dicentang, info disimpan sebagai draf.</span>
                        </span>
                    </label>
                </div>
            </div>
        </section>

        <div class="flex flex-col gap-2 rounded-2xl border border-slate-200 bg-white p-4 shadow-sm sm:flex-row xl:flex-col">
            <button type="submit"
                    class="inline-flex flex-1 items-center justify-center gap-2 rounded-xl bg-rose-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-rose-700">
                <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                </svg>
                {{ $editing ? 'Simpan Perubahan' : 'Simpan Info' }}
            </button>
            <a href="{{ route('admin.posts.index') }}"
               class="inline-flex flex-1 items-center justify-center rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-600 transition hover:bg-slate-50">
                Batal
            </a>
        </div>
    </div>
</form>

@push('scripts')
    <script>
        const thumbnailInput = document.getElementById('thumbnail');
        const thumbnailPreview = document.getElementById('thumbnail-preview');

        thumbnailInput?.addEventListener('change', () => {
            const [file] = thumbnailInput.files;

            if (!file) {
                return;
            }

            thumbnailPreview.src = URL.createObjectURL(file);
            thumbnailPreview.classList.remove('hidden');
        });

        const imagesInput = document.getElementById('images');
        const imagesPreview = document.getElementById('images-preview');

        const youtubeInput = document.getElementById('youtube_url');
        const youtubePreview = document.getElementById('youtube-preview');
        const youtubePreviewFrame = document.getElementById('youtube-preview-frame');

        const youtubeVideoId = (value) => {
            const patterns = [
                /youtube\.com\/watch\?(?:[^#]*&)?v=([A-Za-z0-9_-]{11})/i,
                /youtu\.be\/([A-Za-z0-9_-]{11})/i,
                /youtube\.com\/shorts\/([A-Za-z0-9_-]{11})/i,
                /youtube\.com\/embed\/([A-Za-z0-9_-]{11})/i,
                /youtube\.com\/live\/([A-Za-z0-9_-]{11})/i,
                /^([A-Za-z0-9_-]{11})$/,
            ];

            for (const pattern of patterns) {
                const match = value.trim().match(pattern);

                if (match) {
                    return match[1];
                }
            }

            return null;
        };

        const updateYoutubePreview = () => {
            if (!youtubeInput || !youtubePreview || !youtubePreviewFrame) {
                return;
            }

            const videoId = youtubeVideoId(youtubeInput.value);

            if (!videoId) {
                youtubePreview.classList.add('hidden');
                youtubePreviewFrame.src = '';
                return;
            }

            youtubePreviewFrame.src = `https://www.youtube-nocookie.com/embed/${videoId}?rel=0`;
            youtubePreview.classList.remove('hidden');
        };

        youtubeInput?.addEventListener('input', updateYoutubePreview);
        updateYoutubePreview();

        imagesInput?.addEventListener('change', () => {
            if (!imagesPreview) {
                return;
            }

            imagesPreview.innerHTML = '';

            Array.from(imagesInput.files).forEach((file) => {
                if (!file.type.startsWith('image/')) {
                    return;
                }

                const wrapper = document.createElement('div');
                wrapper.className = 'overflow-hidden rounded-xl border border-slate-200';

                const image = document.createElement('img');
                image.src = URL.createObjectURL(file);
                image.alt = 'Pratinjau foto slide';
                image.className = 'aspect-square w-full object-cover';

                wrapper.appendChild(image);
                imagesPreview.appendChild(wrapper);
            });
        });
    </script>
@endpush
