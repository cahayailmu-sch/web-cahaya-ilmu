@extends('admin.layouts.app')

@section('title', 'Tambah Info Sekolah')
@section('subtitle', 'Tulis info, pengumuman, atau prestasi baru')

@section('content')
    <div class="mx-auto max-w-6xl space-y-6">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.posts.index') }}"
               class="grid size-9 place-items-center rounded-xl border border-slate-200 bg-white text-slate-500 transition hover:bg-slate-50 hover:text-slate-700">
                <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                </svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-900">Tulis Info Sekolah Baru</h1>
                <p class="mt-1 text-sm text-slate-500">Isi detail info lalu simpan sebagai draf atau terbitkan.</p>
            </div>
        </div>

        @include('admin.posts._form')
    </div>
@endsection
