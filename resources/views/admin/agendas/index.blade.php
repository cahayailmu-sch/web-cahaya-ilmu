@extends('admin.layouts.app')

@section('title', 'Agenda Sekolah')
@section('subtitle', 'Kelola jadwal kegiatan dan kalender akademik sekolah')

@section('content')
    <div class="space-y-6">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-900">Agenda &amp; Kalender Sekolah</h1>
            <p class="mt-1 text-sm text-slate-500">
                Total {{ number_format($totalAgendas) }} agenda kegiatan
                @if ($activeLevel)
                    &middot; menampilkan jenjang {{ $activeLevel }}
                @endif
            </p>
        </div>

        @if (auth()->user()->isSuperAdmin())
            <div class="flex flex-wrap items-center gap-2">
                <a href="{{ route('admin.agendas.index') }}"
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
                    ])>{{ number_format($totalAgendas) }}</span>
                </a>

                @foreach ($levels as $level)
                    <a href="{{ route('admin.agendas.index', ['level' => $level]) }}"
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

        <div class="grid gap-6 lg:grid-cols-[360px_1fr] lg:items-start">
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
                <h2 class="text-base font-semibold text-slate-900">Tambah Agenda Baru</h2>
                <p class="mt-0.5 text-xs text-slate-500">Jadwal kegiatan akan tampil di Beranda dan Info Sekolah.</p>

                <form method="POST" action="{{ route('admin.agendas.store') }}" class="mt-5 space-y-4">
                    @csrf

                    <div>
                        <label for="title" class="block text-sm font-medium text-slate-700">Judul Kegiatan <span class="text-rose-600">*</span></label>
                        <input id="title"
                               type="text"
                               name="title"
                               value="{{ old('title') }}"
                               required
                               placeholder="Contoh: Kajian Parenting Islami"
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
                                   value="{{ old('event_date', now()->toDateString()) }}"
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
                                        <option value="{{ $level }}" @selected(old('level', $activeLevel ?? 'Umum') === $level)>{{ $level }}</option>
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
                            @error('level')
                                <p class="mt-1.5 text-xs font-medium text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <label for="time_label" class="block text-sm font-medium text-slate-700">Waktu</label>
                            <input id="time_label"
                                   type="text"
                                   name="time_label"
                                   value="{{ old('time_label') }}"
                                   placeholder="08.00 – 11.30 WITA"
                                   class="mt-1.5 w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm text-slate-900 shadow-xs outline-none transition focus:border-rose-400 focus:ring-2 focus:ring-rose-100">
                        </div>

                        <div>
                            <label for="location" class="block text-sm font-medium text-slate-700">Lokasi</label>
                            <input id="location"
                                   type="text"
                                   name="location"
                                   value="{{ old('location') }}"
                                   placeholder="Aula Utama / Masjid"
                                   class="mt-1.5 w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm text-slate-900 shadow-xs outline-none transition focus:border-rose-400 focus:ring-2 focus:ring-rose-100">
                        </div>
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-medium text-slate-700">Deskripsi Singkat</label>
                        <textarea id="description"
                                  name="description"
                                  rows="3"
                                  placeholder="Ringkasan kegiatan untuk wali murid dan pengunjung..."
                                  class="mt-1.5 w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm text-slate-900 shadow-xs outline-none transition focus:border-rose-400 focus:ring-2 focus:ring-rose-100">{{ old('description') }}</textarea>
                    </div>

                    <label class="flex cursor-pointer items-center gap-2.5 rounded-xl border border-slate-200 bg-slate-50/70 p-3">
                        <input type="checkbox"
                               name="is_published"
                               value="1"
                               @checked(old('is_published', true))
                               class="size-4 rounded border-slate-300 text-rose-600 focus:ring-rose-500">
                        <span class="text-xs font-semibold text-slate-700">Tampilkan langsung di website publik</span>
                    </label>

                    <button type="submit"
                            class="w-full rounded-xl bg-rose-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-rose-700">
                        Simpan Agenda
                    </button>
                </form>
            </div>

            <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                <div class="divide-y divide-slate-200 lg:hidden">
                    @forelse ($agendas as $agenda)
                        <div class="space-y-3 p-4 sm:p-5">
                            <div class="flex items-start justify-between gap-3">
                                <div class="min-w-0">
                                    <div class="flex flex-wrap items-center gap-2">
                                        <span class="rounded-md bg-slate-100 px-2 py-0.5 text-xs font-semibold text-slate-600">{{ $agenda->level }}</span>
                                        <span class="rounded-md px-2 py-0.5 text-xs font-semibold {{ $agenda->is_published ? 'bg-amber-50 text-amber-700' : 'bg-slate-100 text-slate-500' }}">
                                            {{ $agenda->is_published ? 'Tayang' : 'Draft' }}
                                        </span>
                                    </div>
                                    <p class="mt-2 font-semibold text-slate-900">{{ $agenda->title }}</p>
                                    <p class="mt-0.5 text-xs text-slate-500">
                                        {{ $agenda->event_date->format('d M Y') }}
                                        @if ($agenda->time_label) &middot; {{ $agenda->time_label }} @endif
                                        @if ($agenda->location) &middot; {{ $agenda->location }} @endif
                                    </p>
                                    @if ($agenda->description)
                                        <p class="mt-1.5 text-xs text-slate-600">{{ $agenda->description }}</p>
                                    @endif
                                </div>
                            </div>

                            <div class="flex items-center justify-end gap-2 pt-1">
                                <a href="{{ route('admin.agendas.edit', $agenda) }}"
                                   class="rounded-lg border border-slate-200 px-3 py-1.5 text-xs font-semibold text-slate-700 transition hover:bg-slate-50">
                                    Edit
                                </a>
                                <form method="POST"
                                      action="{{ route('admin.agendas.destroy', $agenda) }}"
                                      onsubmit='return confirm(@js("Hapus agenda {$agenda->title}?"))'>
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="rounded-lg bg-rose-50 px-3 py-1.5 text-xs font-semibold text-rose-600 transition hover:bg-rose-100">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <div class="px-6 py-16 text-center">
                            <p class="text-sm font-semibold text-slate-900">Belum ada agenda kegiatan</p>
                            <p class="mt-1 text-xs text-slate-500">Tambahkan jadwal kegiatan melalui formulir di samping.</p>
                        </div>
                    @endforelse
                </div>

                <div class="hidden overflow-x-auto lg:block">
                    <table class="min-w-full divide-y divide-slate-200 text-sm">
                        <thead class="bg-slate-50 text-left text-[11px] font-semibold uppercase tracking-wider text-slate-500">
                            <tr>
                                <th class="px-5 py-3">Tanggal</th>
                                <th class="px-5 py-3">Kegiatan</th>
                                <th class="px-5 py-3">Jenjang</th>
                                <th class="px-5 py-3">Waktu &amp; Lokasi</th>
                                <th class="px-5 py-3">Status</th>
                                <th class="px-5 py-3 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse ($agendas as $agenda)
                                <tr class="align-top transition hover:bg-slate-50/70">
                                    <td class="px-5 py-4 whitespace-nowrap">
                                        <p class="font-semibold text-slate-900">{{ $agenda->event_date->format('d M Y') }}</p>
                                    </td>
                                    <td class="px-5 py-4">
                                        <p class="font-semibold text-slate-900">{{ $agenda->title }}</p>
                                        @if ($agenda->description)
                                            <p class="mt-1 text-xs text-slate-500 line-clamp-2">{{ $agenda->description }}</p>
                                        @endif
                                    </td>
                                    <td class="px-5 py-4">
                                        <span class="rounded-md bg-slate-100 px-2 py-1 text-xs font-semibold text-slate-600">{{ $agenda->level }}</span>
                                    </td>
                                    <td class="px-5 py-4 text-xs text-slate-600">
                                        <p>{{ $agenda->time_label ?: '-' }}</p>
                                        <p class="mt-0.5 text-slate-400">{{ $agenda->location ?: '-' }}</p>
                                    </td>
                                    <td class="px-5 py-4">
                                        <span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-semibold {{ $agenda->is_published ? 'bg-amber-50 text-amber-700 ring-1 ring-inset ring-amber-600/20' : 'bg-slate-100 text-slate-600' }}">
                                            {{ $agenda->is_published ? 'Tayang' : 'Draft' }}
                                        </span>
                                    </td>
                                    <td class="px-5 py-4">
                                        <div class="flex items-center justify-end gap-2">
                                            <a href="{{ route('admin.agendas.edit', $agenda) }}"
                                               class="rounded-lg border border-slate-200 px-2.5 py-1.5 text-xs font-semibold text-slate-700 transition hover:bg-slate-50">
                                                Edit
                                            </a>
                                            <form method="POST"
                                                  action="{{ route('admin.agendas.destroy', $agenda) }}"
                                                  onsubmit='return confirm(@js("Hapus agenda {$agenda->title}?"))'>
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="rounded-lg bg-rose-50 px-2.5 py-1.5 text-xs font-semibold text-rose-600 transition hover:bg-rose-100">
                                                    Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-16 text-center">
                                        <p class="text-sm font-semibold text-slate-900">Belum ada agenda kegiatan</p>
                                        <p class="mt-1 text-xs text-slate-500">Tambahkan jadwal kegiatan melalui formulir di samping.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if ($agendas->hasPages())
                    <div class="border-t border-slate-200 px-5 py-4">
                        {{ $agendas->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
