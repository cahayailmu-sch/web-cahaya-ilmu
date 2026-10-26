@extends('admin.layouts.app')

@section('title', 'Edit Agenda Sekolah')
@section('subtitle', 'Perbarui rincian kegiatan sekolah')

@section('content')
    <div class="mx-auto max-w-2xl space-y-6">
        <div class="flex items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-900">Edit Agenda Kegiatan</h1>
                <p class="mt-1 text-sm text-slate-500">Perbarui jadwal, jenjang, atau status publikasi agenda.</p>
            </div>
            <a href="{{ route('admin.agendas.index') }}"
               class="inline-flex items-center gap-1.5 rounded-xl border border-slate-200 bg-white px-3.5 py-2 text-xs font-semibold text-slate-700 transition hover:bg-slate-50">
                Kembali
            </a>
        </div>

        <form method="POST" action="{{ route('admin.agendas.update', $agenda) }}" class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6 space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label for="title" class="block text-sm font-medium text-slate-700">Judul Kegiatan <span class="text-rose-600">*</span></label>
                <input id="title"
                       type="text"
                       name="title"
                       value="{{ old('title', $agenda->title) }}"
                       required
                       class="mt-1.5 w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm text-slate-900 shadow-xs outline-none transition focus:border-rose-400 focus:ring-2 focus:ring-rose-100">
                @error('title')
                    <p class="mt-1.5 text-xs font-medium text-rose-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label for="event_date" class="block text-sm font-medium text-slate-700">Tanggal <span class="text-rose-600">*</span></label>
                    <input id="event_date"
                           type="date"
                           name="event_date"
                           value="{{ old('event_date', $agenda->event_date->toDateString()) }}"
                           required
                           class="mt-1.5 w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm text-slate-900 shadow-xs outline-none transition focus:border-rose-400 focus:ring-2 focus:ring-rose-100">
                    @error('event_date')
                        <p class="mt-1.5 text-xs font-medium text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="level" class="block text-sm font-medium text-slate-700">Jenjang <span class="text-rose-600">*</span></label>
                    @if (auth()->user()->isSuperAdmin())
                        <select id="level"
                                name="level"
                                required
                                class="mt-1.5 w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm text-slate-900 shadow-xs outline-none transition focus:border-rose-400 focus:ring-2 focus:ring-rose-100">
                            @foreach ($levels as $level)
                                <option value="{{ $level }}" @selected(old('level', $agenda->level) === $level)>{{ $level }}</option>
                            @endforeach
                        </select>
                    @else
                        <input type="hidden" name="level" value="{{ auth()->user()->getLevel() }}">
                        <input id="level"
                               type="text"
                               value="{{ auth()->user()->getLevel() }}"
                               disabled
                               class="mt-1.5 w-full cursor-not-allowed rounded-xl border border-slate-200 bg-slate-100 px-3.5 py-2.5 text-sm font-semibold text-slate-600 shadow-xs">
                    @endif
                </div>
            </div>

            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label for="time_label" class="block text-sm font-medium text-slate-700">Waktu</label>
                    <input id="time_label"
                           type="text"
                           name="time_label"
                           value="{{ old('time_label', $agenda->time_label) }}"
                           class="mt-1.5 w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm text-slate-900 shadow-xs outline-none transition focus:border-rose-400 focus:ring-2 focus:ring-rose-100">
                </div>

                <div>
                    <label for="location" class="block text-sm font-medium text-slate-700">Lokasi</label>
                    <input id="location"
                           type="text"
                           name="location"
                           value="{{ old('location', $agenda->location) }}"
                           class="mt-1.5 w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm text-slate-900 shadow-xs outline-none transition focus:border-rose-400 focus:ring-2 focus:ring-rose-100">
                </div>
            </div>

            <div>
                <label for="description" class="block text-sm font-medium text-slate-700">Deskripsi Singkat</label>
                <textarea id="description"
                          name="description"
                          rows="4"
                          class="mt-1.5 w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm text-slate-900 shadow-xs outline-none transition focus:border-rose-400 focus:ring-2 focus:ring-rose-100">{{ old('description', $agenda->description) }}</textarea>
            </div>

            <label class="flex cursor-pointer items-center gap-2.5 rounded-xl border border-slate-200 bg-slate-50/70 p-3">
                <input type="checkbox"
                       name="is_published"
                       value="1"
                       @checked(old('is_published', $agenda->is_published))
                       class="size-4 rounded border-slate-300 text-rose-600 focus:ring-rose-500">
                <span class="text-xs font-semibold text-slate-700">Tampilkan di website publik</span>
            </label>

            <div class="flex flex-col-reverse gap-2 pt-2 sm:flex-row sm:justify-end">
                <a href="{{ route('admin.agendas.index') }}"
                   class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">
                    Batal
                </a>
                <button type="submit"
                        class="inline-flex items-center justify-center rounded-xl bg-rose-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-rose-700">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
@endsection
