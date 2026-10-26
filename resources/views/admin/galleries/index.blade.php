@extends('admin.layouts.app')

@section('title', 'Galeri Sekolah')
@section('subtitle', 'Kelola foto dokumentasi kegiatan dan lingkungan sekolah')

@section('content')
    <div class="space-y-6">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-900">Galeri Sekolah</h1>
                <p class="mt-1 text-sm text-slate-500">Total {{ number_format($totalGalleries) }} foto tersimpan.</p>
            </div>
        </div>

        <section class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm sm:p-6">
            <div class="flex items-center gap-2">
                <span class="grid size-8 place-items-center rounded-lg bg-rose-50 text-rose-600">
                    <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                </span>
                <div>
                    <h2 class="text-sm font-semibold text-slate-900">Upload Foto Baru</h2>
                    <p class="text-xs text-slate-500">Tambahkan dokumentasi kegiatan dan lingkungan sekolah terbaru.</p>
                </div>
            </div>

            <form method="POST" action="{{ route('admin.galleries.store') }}" enctype="multipart/form-data" class="mt-5">
                @csrf

                <div class="grid gap-4 lg:grid-cols-12">
                    <div class="lg:col-span-4">
                        <label for="title" class="block text-sm font-medium text-slate-700">Judul Foto</label>
                        <input id="title"
                               name="title"
                               type="text"
                               value="{{ old('title') }}"
                               required
                               placeholder="Contoh: Kegiatan Pramuka"
                               class="mt-1.5 block w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm shadow-sm outline-none transition placeholder:text-slate-400 focus:border-rose-400 focus:ring-4 focus:ring-rose-100">
                        @error('title')
                            <p class="mt-1.5 text-xs text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="lg:col-span-3">
                        <label for="category" class="block text-sm font-medium text-slate-700">Kategori</label>
                        <select id="category"
                                name="category"
                                required
                                class="mt-1.5 block w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm shadow-sm outline-none transition focus:border-rose-400 focus:ring-4 focus:ring-rose-100">
                            @foreach ($categories as $category)
                                <option value="{{ $category }}" @selected(old('category', 'Kegiatan') === $category)>{{ $category }}</option>
                            @endforeach
                        </select>
                        @error('category')
                            <p class="mt-1.5 text-xs text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="lg:col-span-2">
                        <label for="level" class="block text-sm font-medium text-slate-700">Jenjang</label>
                        @if (auth()->user()->isSuperAdmin())
                            <select id="level"
                                    name="level"
                                    required
                                    class="mt-1.5 block w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm shadow-sm outline-none transition focus:border-rose-400 focus:ring-4 focus:ring-rose-100">
                                @foreach ($levels as $level)
                                    <option value="{{ $level }}" @selected(old('level', 'Umum') === $level)>{{ $level }}</option>
                                @endforeach
                            </select>
                        @else
                            <input type="hidden" name="level" value="{{ auth()->user()->getLevel() }}">
                            <input id="level"
                                   type="text"
                                   value="{{ auth()->user()->getLevel() }}"
                                   disabled
                                   class="mt-1.5 block w-full cursor-not-allowed rounded-xl border border-slate-200 bg-slate-100 px-3.5 py-2.5 text-sm font-semibold text-slate-600 shadow-sm">
                        @endif
                        @error('level')
                            <p class="mt-1.5 text-xs text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="lg:col-span-3">
                        <label for="image" class="block text-sm font-medium text-slate-700">File Foto</label>
                        <input id="image"
                               name="image"
                               type="file"
                               accept="image/jpeg,image/png,image/webp"
                               required
                               class="mt-1.5 block w-full rounded-xl border border-slate-200 bg-white text-xs text-slate-500 shadow-sm file:mr-3 file:rounded-l-xl file:border-0 file:bg-slate-100 file:px-3 file:py-2.5 file:text-xs file:font-semibold file:text-slate-700 hover:file:bg-slate-200">
                        @error('image')
                            <p class="mt-1.5 text-xs text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="mt-4 flex flex-col gap-3 border-t border-slate-100 pt-4 sm:flex-row sm:items-center sm:justify-between">
                    <p class="text-[11px] text-slate-400">Format JPG, PNG, atau WEBP. Ukuran maksimal 6 MB. Rasio terbaik 4:3.</p>
                    <button type="submit"
                            class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-rose-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-rose-700 sm:w-auto">
                        <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5m-13.5-9L12 3m0 0 4.5 4.5M12 3v13.5" />
                        </svg>
                        Upload Foto
                    </button>
                </div>
            </form>
        </section>

        @if (auth()->user()->isSuperAdmin())
            <div class="flex flex-wrap items-center gap-2">
                <a href="{{ route('admin.galleries.index') }}"
                   @class([
                       'inline-flex items-center gap-2 rounded-xl px-3.5 py-2 text-sm font-semibold transition',
                       'bg-rose-600 text-white shadow-sm' => $activeLevel === null,
                       'border border-slate-200 bg-white text-slate-600 hover:border-slate-300 hover:text-slate-900' => $activeLevel !== null,
                   ])>
                    Semua
                    <span @class([
                        'rounded-md px-1.5 py-0.5 text-[11px] font-bold',
                        'bg-white/15 text-white' => $activeLevel === null,
                        'bg-slate-100 text-slate-500' => $activeLevel !== null,
                    ])>{{ number_format($totalGalleries) }}</span>
                </a>

                @foreach ($levels as $level)
                    <a href="{{ route('admin.galleries.index', ['level' => $level]) }}"
                       @class([
                           'inline-flex items-center gap-2 rounded-xl px-3.5 py-2 text-sm font-semibold transition',
                           'bg-rose-600 text-white shadow-sm' => $activeLevel === $level,
                           'border border-slate-200 bg-white text-slate-600 hover:border-slate-300 hover:text-slate-900' => $activeLevel !== $level,
                       ])>
                        {{ $level }}
                        <span @class([
                            'rounded-md px-1.5 py-0.5 text-[11px] font-bold',
                            'bg-white/15 text-white' => $activeLevel === $level,
                            'bg-slate-100 text-slate-500' => $activeLevel !== $level,
                        ])>{{ number_format($levelCounts[$level] ?? 0) }}</span>
                    </a>
                @endforeach
            </div>
        @endif

        @if ($galleries->isEmpty())
            <div class="rounded-2xl border border-dashed border-slate-300 bg-white px-6 py-16 text-center">
                <div class="mx-auto grid size-12 place-items-center rounded-2xl bg-slate-100 text-slate-400">
                    <svg class="size-6" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 0 0 1.5-1.5V6a1.5 1.5 0 0 0-1.5-1.5H3.75A1.5 1.5 0 0 0 2.25 6v12a1.5 1.5 0 0 0 1.5 1.5Zm10.5-11.25h.008v.008h-.008V8.25Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                    </svg>
                </div>
                <p class="mt-3 text-sm font-semibold text-slate-900">Belum ada foto</p>
                <p class="mt-1 text-xs text-slate-500">
                    @if ($activeLevel)
                        Tidak ada foto untuk jenjang {{ $activeLevel }}.
                    @else
                        Unggah foto dokumentasi pertama melalui form di atas.
                    @endif
                </p>
            </div>
        @else
            <div class="grid gap-4 min-[480px]:grid-cols-2 sm:gap-5 xl:grid-cols-3 2xl:grid-cols-4">
                @foreach ($galleries as $gallery)
                    <article class="group overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm transition hover:shadow-md">
                        <div class="relative aspect-[4/3] overflow-hidden bg-slate-100">
                            <img src="{{ $gallery->imageUrl() }}"
                                 alt="{{ $gallery->title }}"
                                 loading="lazy"
                                 class="size-full object-cover transition duration-300 group-hover:scale-105">

                            <span class="absolute bottom-3 left-3 rounded-full bg-white/90 px-2.5 py-1 text-[11px] font-semibold text-slate-700 shadow-sm backdrop-blur">
                                {{ $gallery->level }}
                            </span>

                            <form method="POST"
                                  action="{{ route('admin.galleries.destroy', $gallery) }}"
                                  class="absolute right-3 top-3"
                                  onsubmit='return confirm(@js("Hapus foto \"{$gallery->title}\"? Tindakan ini tidak dapat dibatalkan."))'>
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="grid size-9 place-items-center rounded-full bg-white/90 text-rose-600 shadow-sm backdrop-blur transition hover:bg-rose-50"
                                        title="Hapus foto">
                                    <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                    </svg>
                                </button>
                            </form>
                        </div>

                        <div class="p-4">
                            <h3 class="truncate text-sm font-semibold text-slate-900">{{ $gallery->title }}</h3>
                            <div class="mt-2 flex items-center justify-between gap-2 text-xs text-slate-500">
                                <span class="inline-flex rounded-full bg-slate-100 px-2 py-0.5 font-medium text-slate-600">{{ $gallery->category }}</span>
                                <span>{{ $gallery->created_at->format('d M Y') }}</span>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>

            @if ($galleries->hasPages())
                <div class="rounded-2xl border border-slate-200 bg-white px-5 py-4 shadow-sm">
                    {{ $galleries->links() }}
                </div>
            @endif
        @endif
    </div>
@endsection
