@extends('admin.layouts.app')

@section('title', 'Edit Foto Galeri')
@section('subtitle', 'Perbarui judul, tanggal dokumentasi, dan detail foto galeri')

@section('content')
    <div class="mx-auto max-w-4xl space-y-6">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.galleries.index') }}"
               class="grid size-9 place-items-center rounded-xl border border-slate-200 bg-white text-slate-500 transition hover:bg-slate-50 hover:text-slate-700">
                <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                </svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-900">Edit Foto Galeri</h1>
                <p class="mt-1 text-sm text-slate-500">{{ $gallery->title }}</p>
            </div>
        </div>

        <section class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm sm:p-6">
            <form method="POST"
                  action="{{ route('admin.galleries.update', $gallery) }}"
                  enctype="multipart/form-data"
                  class="space-y-5">
                @csrf
                @method('PUT')

                <div class="grid gap-4 lg:grid-cols-12">
                    <div class="lg:col-span-6">
                        <label for="title" class="block text-sm font-medium text-slate-700">Judul Foto</label>
                        <input id="title"
                               name="title"
                               type="text"
                               value="{{ old('title', $gallery->title) }}"
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
                                <option value="{{ $category }}" @selected(old('category', $gallery->category) === $category)>{{ $category }}</option>
                            @endforeach
                        </select>
                        @error('category')
                            <p class="mt-1.5 text-xs text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="lg:col-span-3">
                        <label for="level" class="block text-sm font-medium text-slate-700">Jenjang</label>
                        @if (auth()->user()->isSuperAdmin())
                            <select id="level"
                                    name="level"
                                    required
                                    class="mt-1.5 block w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm shadow-sm outline-none transition focus:border-rose-400 focus:ring-4 focus:ring-rose-100">
                                @foreach ($levels as $level)
                                    <option value="{{ $level }}" @selected(old('level', $gallery->level) === $level)>{{ $level }}</option>
                                @endforeach
                            </select>
                        @else
                            <input type="hidden" name="level" value="{{ $gallery->level }}">
                            <input id="level"
                                   type="text"
                                   value="{{ $gallery->level }}"
                                   disabled
                                   class="mt-1.5 block w-full cursor-not-allowed rounded-xl border border-slate-200 bg-slate-100 px-3.5 py-2.5 text-sm font-semibold text-slate-600 shadow-sm">
                        @endif
                        @error('level')
                            <p class="mt-1.5 text-xs text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="lg:col-span-4">
                        <label for="taken_at" class="block text-sm font-medium text-slate-700">Tanggal Dokumentasi</label>
                        <input id="taken_at"
                               name="taken_at"
                               type="date"
                               value="{{ old('taken_at', $gallery->taken_at?->format('Y-m-d')) }}"
                               max="{{ now()->toDateString() }}"
                               class="mt-1.5 block w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm shadow-sm outline-none transition focus:border-rose-400 focus:ring-4 focus:ring-rose-100">
                        <p class="mt-1 text-[10px] leading-4 text-slate-400">Tanggal kegiatan/foto diambil. Kosongkan untuk memakai tanggal unggah lama.</p>
                        @error('taken_at')
                            <p class="mt-1.5 text-xs text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="lg:col-span-8">
                        <label for="image" class="block text-sm font-medium text-slate-700">Ganti File Foto <span class="font-normal text-slate-400">(opsional)</span></label>
                        <input id="image"
                               name="image"
                               type="file"
                               accept="image/jpeg,image/png,image/webp"
                               class="mt-1.5 block w-full rounded-xl border border-slate-200 bg-white text-xs text-slate-500 shadow-sm file:mr-3 file:rounded-l-xl file:border-0 file:bg-slate-100 file:px-3 file:py-2.5 file:text-xs file:font-semibold file:text-slate-700 hover:file:bg-slate-200">
                        <p class="mt-1 text-[10px] leading-4 text-slate-400">Biarkan kosong jika tidak ingin mengganti foto. Format JPG, PNG, atau WEBP, maksimal 6 MB.</p>
                        @error('image')
                            <p class="mt-1.5 text-xs text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="flex flex-col gap-3 border-t border-slate-100 pt-4 sm:flex-row sm:items-center sm:justify-between">
                    <div class="flex items-center gap-3">
                        <img src="{{ $gallery->imageUrl() }}"
                             alt="{{ $gallery->title }}"
                             class="size-16 rounded-xl border border-slate-200 object-cover">
                        <div class="text-[11px] leading-5 text-slate-400">
                            <p class="font-semibold text-slate-600">Foto saat ini</p>
                            <p>Diunggah {{ $gallery->created_at?->format('d M Y') }}</p>
                        </div>
                    </div>
                    <div class="flex flex-col gap-2 sm:flex-row">
                        <a href="{{ route('admin.galleries.index') }}"
                           class="inline-flex items-center justify-center rounded-xl border border-slate-200 px-4 py-2.5 text-sm font-semibold text-slate-600 transition hover:bg-slate-50">
                            Batal
                        </a>
                        <button type="submit"
                                class="inline-flex items-center justify-center gap-2 rounded-xl bg-rose-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-rose-700">
                            <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                            </svg>
                            Simpan Perubahan
                        </button>
                    </div>
                </div>
            </form>
        </section>
    </div>
@endsection
