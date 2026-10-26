@extends('admin.layouts.app')

@section('title', 'Info Sekolah')
@section('subtitle', 'Kelola info, pengumuman, dan prestasi sekolah')

@section('content')
    <div class="space-y-6">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-900">Info Sekolah</h1>
                <p class="mt-1 text-sm text-slate-500">Total {{ number_format($posts->total()) }} info tersimpan.</p>
            </div>
            <a href="{{ route('admin.posts.create') }}"
               class="inline-flex items-center justify-center gap-2 rounded-xl bg-rose-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-rose-700">
                <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                Tulis Info Sekolah
            </a>
        </div>

        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="divide-y divide-slate-200 md:hidden">
                @forelse ($posts as $post)
                    @php
                        $categoryClasses = [
                            'Berita' => 'bg-slate-100 text-slate-700 ring-slate-500/20',
                            'Pengumuman' => 'bg-amber-50 text-amber-700 ring-amber-600/20',
                            'Prestasi' => 'bg-rose-50 text-rose-700 ring-rose-600/20',
                        ];
                    @endphp
                    <div class="space-y-3 p-4">
                        <div class="flex items-start gap-3">
                            @if ($post->primaryImageUrl())
                                <img src="{{ $post->primaryImageUrl() }}"
                                     alt="{{ $post->title }}"
                                     class="size-16 shrink-0 rounded-xl border border-slate-200 object-cover">
                            @else
                                <div class="grid size-16 shrink-0 place-items-center rounded-xl border border-slate-200 bg-slate-50 text-slate-400">
                                    <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 0 0 1.5-1.5V6a1.5 1.5 0 0 0-1.5-1.5H3.75A1.5 1.5 0 0 0 2.25 6v12a1.5 1.5 0 0 0 1.5 1.5Zm10.5-11.25h.008v.008h-.008V8.25Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                                    </svg>
                                </div>
                            @endif
                            <div class="min-w-0 flex-1">
                                <div class="flex flex-wrap items-center gap-1.5">
                                    <span class="inline-flex rounded-full px-2 py-0.5 text-[11px] font-semibold ring-1 ring-inset {{ $categoryClasses[$post->category] ?? 'bg-slate-100 text-slate-600 ring-slate-500/20' }}">
                                        {{ $post->category }}
                                    </span>
                                    <span class="rounded-md bg-slate-100 px-2 py-0.5 text-[11px] font-semibold text-slate-600">{{ $post->level }}</span>
                                    @if ($post->is_published)
                                        <span class="inline-flex items-center gap-1 rounded-full bg-rose-50 px-2 py-0.5 text-[11px] font-semibold text-rose-700 ring-1 ring-inset ring-rose-600/20">
                                            <span class="size-1.5 rounded-full bg-rose-500"></span>
                                            Terbit
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 rounded-full bg-slate-100 px-2 py-0.5 text-[11px] font-semibold text-slate-600 ring-1 ring-inset ring-slate-500/20">
                                            <span class="size-1.5 rounded-full bg-slate-400"></span>
                                            Draf
                                        </span>
                                    @endif
                                </div>
                                <p class="mt-1.5 line-clamp-2 text-sm font-semibold text-slate-900">{{ $post->title }}</p>
                                <p class="mt-0.5 truncate text-xs text-slate-500">/{{ $post->slug }}</p>
                            </div>
                        </div>

                        <div class="flex items-center justify-between border-t border-slate-100 pt-2.5">
                            <span class="text-xs text-slate-500">{{ $post->created_at->format('d M Y') }}</span>
                            <div class="flex items-center gap-2">
                                <a href="{{ route('admin.posts.edit', $post) }}"
                                   class="inline-flex items-center gap-1.5 rounded-lg bg-amber-500 px-3 py-1.5 text-xs font-semibold text-white transition hover:bg-amber-600">
                                    Edit
                                </a>

                                <form method="POST"
                                      action="{{ route('admin.posts.destroy', $post) }}"
                                      onsubmit='return confirm(@js("Hapus info \"{$post->title}\"? Tindakan ini tidak dapat dibatalkan."))'>
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="inline-flex items-center gap-1.5 rounded-lg bg-rose-50 px-3 py-1.5 text-xs font-semibold text-rose-600 ring-1 ring-inset ring-rose-600/20 transition hover:bg-rose-100">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="px-6 py-12 text-center">
                        <p class="text-sm font-semibold text-slate-900">Belum ada info sekolah</p>
                        <p class="mt-1 text-xs text-slate-500">Klik &ldquo;Tulis Info Sekolah&rdquo; untuk membuat info pertama.</p>
                    </div>
                @endforelse
            </div>

            <div class="hidden overflow-x-auto md:block">
                <table class="min-w-full divide-y divide-slate-200 text-sm">
                    <thead class="bg-slate-50 text-left text-[11px] font-semibold uppercase tracking-wider text-slate-500">
                        <tr>
                            <th class="px-5 py-3">Info</th>
                            <th class="px-5 py-3">Kategori</th>
                            <th class="px-5 py-3">Jenjang</th>
                            <th class="px-5 py-3">Status</th>
                            <th class="px-5 py-3">Tanggal</th>
                            <th class="px-5 py-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($posts as $post)
                            @php
                                $categoryClasses = [
                                    'Berita' => 'bg-slate-100 text-slate-700 ring-slate-500/20',
                                    'Pengumuman' => 'bg-amber-50 text-amber-700 ring-amber-600/20',
                                    'Prestasi' => 'bg-rose-50 text-rose-700 ring-rose-600/20',
                                ];
                            @endphp
                            <tr class="transition hover:bg-slate-50/70">
                                <td class="px-5 py-4">
                                    <div class="flex items-center gap-3">
                                        @if ($post->primaryImageUrl())
                                            <img src="{{ $post->primaryImageUrl() }}"
                                                 alt="{{ $post->title }}"
                                                 class="size-14 shrink-0 rounded-xl border border-slate-200 object-cover">
                                        @else
                                            <div class="grid size-14 shrink-0 place-items-center rounded-xl border border-slate-200 bg-slate-50 text-slate-400">
                                                <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor" aria-hidden="true">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 0 0 1.5-1.5V6a1.5 1.5 0 0 0-1.5-1.5H3.75A1.5 1.5 0 0 0 2.25 6v12a1.5 1.5 0 0 0 1.5 1.5Zm10.5-11.25h.008v.008h-.008V8.25Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                                                </svg>
                                            </div>
                                        @endif
                                        <div class="min-w-0">
                                            <p class="max-w-md truncate font-semibold text-slate-900">{{ $post->title }}</p>
                                            <p class="mt-0.5 max-w-md truncate text-xs text-slate-500">/{{ $post->slug }}</p>
                                            @if (count($post->images ?? []) > 0 || $post->hasInstagramPost())
                                                <div class="mt-1.5 flex flex-wrap items-center gap-1.5">
                                                    @if (count($post->images ?? []) > 0)
                                                        <span class="inline-flex items-center gap-1 rounded-full bg-slate-100 px-2 py-0.5 text-[10px] font-semibold text-slate-600">
                                                            <i class="fa-regular fa-clone" aria-hidden="true"></i>
                                                            {{ count($post->images) }} foto slide
                                                        </span>
                                                    @endif
                                                    @if ($post->hasInstagramPost())
                                                        <span class="inline-flex items-center gap-1 rounded-full bg-fuchsia-50 px-2 py-0.5 text-[10px] font-semibold text-fuchsia-700">
                                                            <i class="fa-brands fa-instagram" aria-hidden="true"></i>
                                                            Instagram
                                                        </span>
                                                    @endif
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-5 py-4">
                                    <span class="inline-flex rounded-full px-2.5 py-1 text-xs font-semibold ring-1 ring-inset {{ $categoryClasses[$post->category] ?? 'bg-slate-100 text-slate-600 ring-slate-500/20' }}">
                                        {{ $post->category }}
                                    </span>
                                </td>
                                <td class="px-5 py-4">
                                    <span class="rounded-md bg-slate-100 px-2 py-1 text-xs font-semibold text-slate-600">{{ $post->level }}</span>
                                </td>
                                <td class="px-5 py-4">
                                    @if ($post->is_published)
                                        <span class="inline-flex items-center gap-1.5 rounded-full bg-rose-50 px-2.5 py-1 text-xs font-semibold text-rose-700 ring-1 ring-inset ring-rose-600/20">
                                            <span class="size-1.5 rounded-full bg-rose-500"></span>
                                            Terbit
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 rounded-full bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-600 ring-1 ring-inset ring-slate-500/20">
                                            <span class="size-1.5 rounded-full bg-slate-400"></span>
                                            Draf
                                        </span>
                                    @endif
                                </td>
                                <td class="px-5 py-4 text-xs text-slate-500">{{ $post->created_at->format('d M Y') }}</td>
                                <td class="px-5 py-4">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('admin.posts.edit', $post) }}"
                                           class="grid size-8 place-items-center rounded-lg bg-amber-500 text-white transition hover:bg-amber-600"
                                           title="Edit info">
                                            <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" aria-hidden="true">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                            </svg>
                                        </a>

                                        <form method="POST"
                                              action="{{ route('admin.posts.destroy', $post) }}"
                                              onsubmit='return confirm(@js("Hapus info \"{$post->title}\"? Tindakan ini tidak dapat dibatalkan."))'>
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="grid size-8 place-items-center rounded-lg bg-rose-50 text-rose-600 ring-1 ring-inset ring-rose-600/20 transition hover:bg-rose-100"
                                                    title="Hapus info">
                                                <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" aria-hidden="true">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-16 text-center">
                                    <div class="mx-auto grid size-12 place-items-center rounded-2xl bg-slate-100 text-slate-400">
                                        <svg class="size-6" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 7.5h1.5m-1.5 3h1.5m-7.5 3h7.5m-7.5 3h7.5m3-9h3.375c.621 0 1.125.504 1.125 1.125V18a2.25 2.25 0 0 1-2.25 2.25M16.5 7.5V18a2.25 2.25 0 0 0 2.25 2.25M16.5 7.5V4.875c0-.621-.504-1.125-1.125-1.125H4.125C3.504 3.75 3 4.254 3 4.875V18a2.25 2.25 0 0 0 2.25 2.25h13.5M6 7.5h3v3H6v-3Z" />
                                        </svg>
                                    </div>
                                    <p class="mt-3 text-sm font-semibold text-slate-900">Belum ada info sekolah</p>
                                    <p class="mt-1 text-xs text-slate-500">Klik &ldquo;Tulis Info Sekolah&rdquo; untuk membuat info pertama.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($posts->hasPages())
                <div class="border-t border-slate-200 px-5 py-4">
                    {{ $posts->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
