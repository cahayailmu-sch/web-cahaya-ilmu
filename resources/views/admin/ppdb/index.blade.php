@extends('admin.layouts.app')

@section('title', 'Data SPMB')
@section('subtitle', 'Kelola pendaftar peserta didik baru')

@section('content')
    <div class="space-y-6">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-900">Data Pendaftar SPMB</h1>
                <p class="mt-1 text-sm text-slate-500">
                    Total {{ number_format($totalRegistrations) }} pendaftar
                    @if ($activeLevel)
                        &middot; menampilkan jenjang {{ $activeLevel }}
                    @endif
                    @if (! empty($activeStatus))
                        &middot; status {{ $statusLabels[$activeStatus] ?? $activeStatus }}
                    @endif
                    @if (! empty($search))
                        &middot; pencarian &ldquo;{{ $search }}&rdquo;
                    @endif
                </p>
            </div>

            <div class="flex flex-wrap items-center gap-2">
                <a href="{{ route('admin.ppdb.export', request()->only(['level', 'status', 'q'])) }}"
                   class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-4 py-2.5 text-xs sm:text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-700">
                    <svg class="size-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
                    </svg>
                    Export Excel / CSV
                </a>
            </div>
        </div>

        <!-- Kuota SPMB Ringkas -->
        <section class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm sm:p-6">
            <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                <div class="flex items-start gap-2.5 sm:items-center sm:gap-2">
                    <span class="grid size-8 shrink-0 place-items-center rounded-lg bg-rose-50 text-rose-600">
                        <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z" />
                        </svg>
                    </span>
                    <div>
                        <h2 class="text-sm font-semibold text-slate-900">Kuota SPMB (KB-TK per Kelompok; SD–SMA per Jenis Kelamin)</h2>
                        <p class="text-xs text-slate-500">Pantau sisa kursi KB, TK A, dan TK B, serta kuota Laki-laki &amp; Perempuan untuk SD, SMP, dan SMA.</p>
                    </div>
                </div>

                <a href="{{ route('admin.ppdb-settings.index') }}"
                   class="inline-flex shrink-0 items-center justify-center gap-1.5 rounded-xl border border-slate-200 bg-white px-3.5 py-2 text-xs font-semibold text-slate-600 transition hover:bg-slate-50 hover:text-slate-900">
                    Buka Pengaturan Lengkap
                    <svg class="size-3.5" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                    </svg>
                </a>
            </div>

            <form method="POST" action="{{ route('admin.ppdb-settings.update') }}" class="mt-5">
                @csrf
                @method('PUT')

                <x-admin.ppdb-quota-fields :settings="$settings" :quota-summary="$quotaSummary" :quota-levels="$quotaLevels" />

                <div class="mt-4 flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                    <p class="text-[11px] text-slate-400">Perubahan kuota langsung berlaku pada formulir pendaftaran publik.</p>
                    <button type="submit"
                            class="inline-flex items-center justify-center gap-2 rounded-xl bg-rose-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-rose-700">
                        <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                        </svg>
                        Simpan Kuota
                    </button>
                </div>
            </form>
        </section>

        <!-- Filter Form -->
        <form method="GET" action="{{ route('admin.ppdb.index') }}" class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
            @if ($activeLevel)
                <input type="hidden" name="level" value="{{ $activeLevel }}">
            @endif
            <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-[1fr_260px_auto]">
                <div>
                    <label for="search-ppdb" class="sr-only">Cari Pendaftar</label>
                    <div class="relative">
                        <svg class="pointer-events-none absolute left-3.5 top-1/2 size-4 -translate-y-1/2 text-slate-400" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                        </svg>
                        <input id="search-ppdb"
                                type="search"
                                name="q"
                                value="{{ $search ?? '' }}"
                                placeholder="Cari nama calon murid, nomor SPMB, orang tua, WA, atau asal sekolah..."
                                class="w-full rounded-xl border border-slate-200 bg-slate-50/60 py-2.5 pl-10 pr-3.5 text-sm text-slate-900 placeholder:text-slate-400 outline-none transition focus:border-rose-400 focus:bg-white focus:ring-2 focus:ring-rose-100">
                    </div>
                </div>

                <div>
                    <label for="filter-status" class="sr-only">Filter Status</label>
                    <select id="filter-status"
                            name="status"
                            class="w-full rounded-xl border border-slate-200 bg-slate-50/60 px-3.5 py-2.5 text-sm font-medium text-slate-700 outline-none transition focus:border-rose-400 focus:bg-white focus:ring-2 focus:ring-rose-100">
                        <option value="">Semua Status</option>
                        @foreach ($statuses as $statusOption)
                            <option value="{{ $statusOption }}" @selected(($activeStatus ?? null) === $statusOption)>
                                Status: {{ $statusLabels[$statusOption] ?? $statusOption }} ({{ number_format($statusCounts[$statusOption] ?? 0) }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="flex items-center gap-2 sm:col-span-2 lg:col-span-1">
                    <button type="submit"
                            class="inline-flex flex-1 items-center justify-center gap-1.5 rounded-xl bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-slate-800 lg:flex-none">
                        Terapkan Filter
                    </button>
                    @if ($activeLevel || ! empty($activeStatus) || ! empty($search))
                        <a href="{{ route('admin.ppdb.index') }}"
                           class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm font-semibold text-slate-600 transition hover:bg-slate-50 hover:text-slate-900">
                            Reset
                        </a>
                    @endif
                </div>
            </div>
        </form>

        @if (auth()->user()->isSuperAdmin())
            <div class="flex flex-wrap items-center gap-2">
                <a href="{{ route('admin.ppdb.index', array_filter(['status' => $activeStatus ?? null, 'q' => $search ?? null])) }}"
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
                    ])>{{ number_format($totalRegistrations) }}</span>
                </a>

                @foreach ($levels as $level)
                    <a href="{{ route('admin.ppdb.index', array_filter(['level' => $level, 'status' => $activeStatus ?? null, 'q' => $search ?? null])) }}"
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

        <!-- Daftar Pendaftar -->
        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <!-- Mobile List Card View -->
            <div class="divide-y divide-slate-200 lg:hidden">
                @forelse ($registrations as $registration)
                    @php
                        $stageTwoUrl = $registration->fullFormUrl();
                        $waStageTwoMsg = "Assalamu'alaikum Ayah/Bunda dari {$registration->full_name}. Pembayaran pendaftaran SPMB {$registration->level} telah terverifikasi. Silakan lengkapi Formulir Lengkap & Surat Kesanggupan Biaya melalui tautan berikut:\n{$stageTwoUrl}";
                        $waStageTwoUrl = $registration->whatsappLink() . '?text=' . urlencode($waStageTwoMsg);
                    @endphp
                    <div class="space-y-3.5 p-4 sm:p-5">
                        <div class="flex items-start justify-between gap-3">
                            <div class="min-w-0">
                                <div class="flex flex-wrap items-center gap-2">
                                    <span class="rounded-md bg-slate-100 px-2 py-0.5 text-xs font-semibold text-slate-600">{{ $registration->level }}</span>
                                    <x-admin.status-badge :status="$registration->status" />
                                </div>
                                <p class="mt-2 font-semibold text-slate-900">{{ $registration->full_name }}</p>
                                <p class="mt-0.5 text-xs text-slate-500 font-mono">
                                    {{ $registration->registration_number }}
                                    &middot; {{ $registration->gender === 'L' ? 'Laki-laki' : 'Perempuan' }}
                                    &middot; {{ $registration->created_at->format('d M Y') }}
                                </p>
                                @if ($registration->previous_school)
                                    <p class="mt-1 text-xs text-slate-400">Asal: {{ $registration->previous_school }}</p>
                                @endif
                            </div>
                        </div>

                        <dl class="grid gap-2.5 rounded-xl bg-slate-50 p-3 text-xs sm:grid-cols-2">
                            <div>
                                <dt class="text-[11px] font-medium text-slate-400">Tempat, Tanggal Lahir</dt>
                                <dd class="mt-0.5 font-medium text-slate-700">
                                    {{ $registration->birth_place ?: '-' }}@if ($registration->birth_date), {{ $registration->birth_date->format('d M Y') }} ({{ $registration->age() }} thn)@endif
                                </dd>
                            </div>
                            <div>
                                <dt class="text-[11px] font-medium text-slate-400">Orang Tua / WhatsApp</dt>
                                <dd class="mt-0.5 font-medium text-slate-700">{{ $registration->parent_name }} &middot; {{ $registration->whatsapp }}</dd>
                            </div>
                            <div>
                                <dt class="text-[11px] font-medium text-slate-400">Biaya Pendaftaran</dt>
                                <dd class="mt-0.5 font-semibold text-slate-900">Rp {{ number_format($registration->final_fee, 0, ',', '.') }}</dd>
                                @if (filled($registration->referral_code))
                                    <dd class="mt-1.5 space-y-1 text-[11px] leading-5">
                                        <span class="inline-flex items-center gap-1 rounded-md bg-gold-50 px-2 py-0.5 font-mono font-extrabold uppercase tracking-wider text-gold-800 ring-1 ring-inset ring-gold-600/20">
                                            {{ $registration->referral_code }}
                                        </span>
                                        @if (filled($registration->promoLabel()))
                                            <span class="block text-slate-500">{{ $registration->promoLabel() }}</span>
                                        @endif
                                        @if ($registration->discount_amount > 0)
                                            <span class="block font-semibold text-emerald-700">
                                                Diskon Rp {{ number_format($registration->discount_amount, 0, ',', '.') }}
                                            </span>
                                        @endif
                                    </dd>
                                @endif
                            </div>
                            <div>
                                <dt class="text-[11px] font-medium text-slate-400">Bukti Bayar</dt>
                                <dd class="mt-1">
                                    @if ($registration->paymentProofIsFree())
                                        <span class="inline-flex items-center gap-1.5 rounded-lg bg-emerald-50 px-2.5 py-1 text-xs font-extrabold text-emerald-700 ring-1 ring-inset ring-emerald-600/20">
                                            GRATIS &mdash; Kode Referral
                                        </span>
                                    @elseif ($registration->paymentProofUrl())
                                        <a href="{{ $registration->paymentProofUrl() }}"
                                           target="_blank"
                                           rel="noopener"
                                           class="inline-flex items-center gap-1.5 rounded-lg bg-white px-2.5 py-1 text-xs font-semibold text-slate-700 ring-1 ring-inset ring-slate-300 transition hover:bg-slate-100">
                                            Lihat Bukti Bayar
                                        </a>
                                    @else
                                        <span class="text-slate-400">Belum diunggah</span>
                                    @endif
                                </dd>
                            </div>
                        </dl>

                        <!-- Tahap 2 & Cetak Actions -->
                        <div class="flex flex-wrap items-center gap-1.5 pt-1">
                            <a href="{{ route('admin.ppdb.show', $registration) }}"
                               class="inline-flex items-center gap-1 rounded-lg bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-700 hover:bg-slate-200 transition">
                                <svg class="size-3.5" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.964-7.178Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" /></svg>
                                Detail
                            </a>

                            @if (in_array($registration->status, ['verified', 'completed', 'accepted', 'Diverifikasi', 'Diterima']))
                                <button type="button"
                                        onclick="navigator.clipboard.writeText('{{ $stageTwoUrl }}'); alert('Link Formulir Lengkap berhasil disalin!');"
                                        class="inline-flex items-center gap-1 rounded-lg bg-sky-50 px-2.5 py-1 text-xs font-semibold text-sky-700 ring-1 ring-inset ring-sky-600/20 hover:bg-sky-100 transition"
                                        title="Salin Link Formulir Lengkap">
                                    <svg class="size-3.5" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.666 3.888A2.25 2.25 0 0 0 13.5 2.25h-3c-1.03 0-1.9.693-2.166 1.638m7.332 0c.055.194.084.4.084.612v0a.75.75 0 0 1-.75.75H9a.75.75 0 0 1-.75-.75v0c0-.212.03-.418.084-.612m7.332 0c.646.049 1.288.11 1.927.184 1.1.128 1.907 1.077 1.907 2.185V19.5a2.25 2.25 0 0 1-2.25 2.25H6.75A2.25 2.25 0 0 1 4.5 19.5V6.257c0-1.108.806-2.057 1.907-2.185a48.208 48.208 0 0 1 1.927-.184" /></svg>
                                    Copy Link F2
                                </button>

                                <a href="{{ $waStageTwoUrl }}"
                                   target="_blank"
                                   rel="noopener"
                                   class="inline-flex items-center gap-1 rounded-lg bg-emerald-50 px-2.5 py-1 text-xs font-semibold text-emerald-700 ring-1 ring-inset ring-emerald-600/20 hover:bg-emerald-100 transition"
                                   title="Kirim link Formulir Lengkap via WhatsApp">
                                    <svg class="size-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 0 1-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0 0 12.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 0 0 5.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 0 0-3.48-8.413Z"/></svg>
                                    WA Link F2
                                </a>
                            @endif

                            @if ($registration->hasCompletedFullForm())
                                <a href="{{ route('admin.ppdb.print', $registration) }}"
                                   target="_blank"
                                   class="inline-flex items-center gap-1 rounded-lg bg-indigo-50 px-2.5 py-1 text-xs font-semibold text-indigo-700 ring-1 ring-inset ring-indigo-600/20 hover:bg-indigo-100 transition"
                                   title="Cetak Formulir Lengkap (A4)">
                                    <svg class="size-3.5" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24-1.076-.64-2.122-1.18-3.111a12.09 12.09 0 0 0-3.54-3.793m0 0a12.094 12.094 0 0 1 5.25-.972 12.094 12.094 0 0 1 5.25.972m-10.5 0v10.5c0 1.242 1.008 2.25 2.25 2.25h10.5a2.25 2.25 0 0 0 2.25-2.25V6.924" /></svg>
                                    Cetak A4
                                </a>
                                <a href="{{ route('admin.ppdb.pdf', $registration) }}"
                                   class="inline-flex items-center gap-1 rounded-lg bg-rose-50 px-2.5 py-1 text-xs font-semibold text-rose-700 ring-1 ring-inset ring-rose-600/20 hover:bg-rose-100 transition"
                                   title="Download PDF">
                                    PDF
                                </a>
                            @endif
                        </div>

                        <!-- Update Status Form -->
                        <div class="flex flex-wrap items-center justify-between gap-2 pt-2 border-t border-slate-100">
                            <form method="POST"
                                  action="{{ route('admin.ppdb.status', $registration) }}"
                                  class="flex flex-1 items-center gap-1.5 sm:flex-none">
                                @csrf
                                @method('PATCH')
                                <label class="sr-only" for="mobile-status-{{ $registration->id }}">Ubah status {{ $registration->full_name }}</label>
                                <select id="mobile-status-{{ $registration->id }}"
                                        name="status"
                                        class="min-w-0 flex-1 rounded-lg border border-slate-200 bg-white py-1.5 pl-2.5 pr-7 text-xs font-medium text-slate-700 shadow-sm outline-none transition focus:border-rose-400 focus:ring-2 focus:ring-rose-100 sm:flex-none">
                                    @foreach ($statuses as $st)
                                        <option value="{{ $st }}" @selected($registration->status === $st)>{{ $statusLabels[$st] ?? $st }}</option>
                                    @endforeach
                                </select>
                                <button type="submit"
                                        class="rounded-lg bg-rose-600 px-2.5 py-1.5 text-xs font-semibold text-white transition hover:bg-rose-700">
                                    Simpan
                                </button>
                            </form>

                            <form method="POST"
                                  action="{{ route('admin.ppdb.destroy', $registration) }}"
                                  onsubmit='return confirm(@js("Hapus data pendaftar {$registration->full_name}? Tindakan ini tidak dapat dibatalkan."))'>
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="grid size-8 place-items-center rounded-lg bg-rose-50 text-rose-600 ring-1 ring-inset ring-rose-600/20 transition hover:bg-rose-100"
                                        title="Hapus pendaftar">
                                    <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="px-6 py-16 text-center">
                        <p class="text-sm font-semibold text-slate-900">Belum ada pendaftar</p>
                        <p class="mt-1 text-xs text-slate-500">
                            @if ($activeLevel)
                                Tidak ada pendaftar untuk jenjang {{ $activeLevel }}.
                            @else
                                Data pendaftar SPMB akan muncul di sini.
                            @endif
                        </p>
                    </div>
                @endforelse
            </div>

            <!-- Desktop Table View -->
            <div class="hidden overflow-x-auto lg:block">
                <table class="min-w-full divide-y divide-slate-200 text-sm">
                    <thead class="bg-slate-50 text-left text-[11px] font-semibold uppercase tracking-wider text-slate-500">
                        <tr>
                            <th class="px-5 py-3">Pendaftar</th>
                            <th class="px-5 py-3">Jenjang</th>
                            <th class="px-5 py-3">Tempat, Tanggal Lahir</th>
                            <th class="px-5 py-3">Orang Tua / WhatsApp</th>
                            <th class="px-5 py-3">Biaya Pendaftaran</th>
                            <th class="px-5 py-3">Bukti Bayar</th>
                            <th class="px-5 py-3">Status</th>
                            <th class="px-5 py-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($registrations as $registration)
                            @php
                                $stageTwoUrl = $registration->fullFormUrl();
                                $waStageTwoMsg = "Assalamu'alaikum Ayah/Bunda dari {$registration->full_name}. Pembayaran pendaftaran SPMB {$registration->level} telah terverifikasi. Silakan lengkapi Formulir Lengkap & Surat Kesanggupan Biaya melalui tautan berikut:\n{$stageTwoUrl}";
                                $waStageTwoUrl = $registration->whatsappLink() . '?text=' . urlencode($waStageTwoMsg);
                            @endphp
                            <tr class="align-top transition hover:bg-slate-50/70">
                                <td class="px-5 py-4">
                                    <a href="{{ route('admin.ppdb.show', $registration) }}" class="font-semibold text-slate-900 hover:text-rose-600 transition">
                                        {{ $registration->full_name }}
                                    </a>
                                    <p class="mt-0.5 text-xs text-slate-500 font-mono">
                                        {{ $registration->registration_number }}
                                        &middot; {{ $registration->gender === 'L' ? 'Laki-laki' : 'Perempuan' }}
                                        &middot; {{ $registration->created_at->format('d M Y') }}
                                    </p>
                                    @if ($registration->previous_school)
                                        <p class="mt-1 text-xs text-slate-400">Asal: {{ $registration->previous_school }}</p>
                                    @endif
                                </td>
                                <td class="px-5 py-4">
                                    <span class="rounded-md bg-slate-100 px-2 py-1 text-xs font-semibold text-slate-600">{{ $registration->level }}</span>
                                </td>
                                <td class="px-5 py-4">
                                    <p class="text-slate-700">{{ $registration->birth_place ?: '-' }}</p>
                                    <p class="mt-0.5 text-xs text-slate-500">
                                        @if ($registration->birth_date)
                                            {{ $registration->birth_date->format('d M Y') }}
                                            <span class="text-slate-400">&middot; {{ $registration->age() }} tahun</span>
                                        @else
                                            -
                                        @endif
                                    </p>
                                </td>
                                <td class="px-5 py-4">
                                    <p class="text-slate-700">{{ $registration->parent_name }}</p>
                                    <p class="mt-0.5 text-xs text-slate-500">{{ $registration->whatsapp }}</p>
                                </td>
                                <td class="px-5 py-4">
                                    <p class="text-xs text-slate-500">Biaya Awal</p>
                                    <p class="mt-0.5 font-semibold text-slate-900">
                                        Rp {{ number_format($registration->final_fee, 0, ',', '.') }}
                                    </p>
                                    @if (filled($registration->referral_code))
                                        <p class="mt-1.5">
                                            <span class="inline-flex items-center gap-1 rounded-md bg-gold-50 px-2 py-0.5 font-mono text-[10px] font-extrabold uppercase tracking-wider text-gold-800 ring-1 ring-inset ring-gold-600/20">
                                                {{ $registration->referral_code }}
                                            </span>
                                        </p>
                                        @if ($registration->discount_amount > 0)
                                            <p class="mt-1 text-[11px] font-semibold text-emerald-700">
                                                Diskon Rp {{ number_format($registration->discount_amount, 0, ',', '.') }}
                                            </p>
                                        @endif
                                    @endif
                                </td>
                                <td class="px-5 py-4">
                                    @if ($registration->paymentProofIsFree())
                                        <span class="inline-flex items-center gap-1.5 rounded-lg bg-emerald-50 px-2.5 py-1.5 text-xs font-extrabold text-emerald-700 ring-1 ring-inset ring-emerald-600/20">
                                            GRATIS
                                        </span>
                                    @elseif ($registration->paymentProofUrl())
                                        <a href="{{ $registration->paymentProofUrl() }}"
                                           target="_blank"
                                           rel="noopener"
                                           class="inline-flex items-center gap-1.5 rounded-lg bg-slate-100 px-2.5 py-1.5 text-xs font-semibold text-slate-700 ring-1 ring-inset ring-slate-500/20 transition hover:bg-slate-200"
                                           title="Lihat bukti pembayaran {{ $registration->full_name }}">
                                            <svg class="size-3.5" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" aria-hidden="true">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.964-7.178Z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                            </svg>
                                            Bukti Bayar
                                        </a>
                                    @else
                                        <span class="text-xs text-slate-400">Belum diunggah</span>
                                    @endif
                                </td>
                                <td class="px-5 py-4">
                                    <x-admin.status-badge :status="$registration->status" />
                                    @if ($registration->hasCompletedFullForm())
                                        <span class="mt-1 block text-[11px] font-semibold text-indigo-600">
                                            &check; Formulir Lengkap Terisi
                                        </span>
                                    @endif
                                </td>
                                <td class="px-5 py-4">
                                    <div class="flex flex-col items-end gap-2">
                                        <!-- Primary Actions Group -->
                                        <div class="flex flex-wrap items-center justify-end gap-1.5">
                                            <a href="{{ route('admin.ppdb.show', $registration) }}"
                                               class="inline-flex items-center gap-1 rounded-lg bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-700 ring-1 ring-inset ring-slate-300 hover:bg-slate-200 transition"
                                               title="Lihat Detail Pendaftar">
                                                Detail
                                            </a>

                                            @if (in_array($registration->status, ['verified', 'completed', 'accepted', 'Diverifikasi', 'Diterima']))
                                                <button type="button"
                                                        onclick="navigator.clipboard.writeText('{{ $stageTwoUrl }}'); alert('Link Formulir Lengkap berhasil disalin ke clipboard:\n{{ $stageTwoUrl }}');"
                                                        class="inline-flex items-center gap-1 rounded-lg bg-sky-50 px-2.5 py-1 text-xs font-semibold text-sky-700 ring-1 ring-inset ring-sky-600/20 hover:bg-sky-100 transition"
                                                        title="Salin Link Formulir Lengkap ke Clipboard">
                                                    <svg class="size-3.5" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.666 3.888A2.25 2.25 0 0 0 13.5 2.25h-3c-1.03 0-1.9.693-2.166 1.638m7.332 0c.055.194.084.4.084.612v0a.75.75 0 0 1-.75.75H9a.75.75 0 0 1-.75-.75v0c0-.212.03-.418.084-.612m7.332 0c.646.049 1.288.11 1.927.184 1.1.128 1.907 1.077 1.907 2.185V19.5a2.25 2.25 0 0 1-2.25 2.25H6.75A2.25 2.25 0 0 1 4.5 19.5V6.257c0-1.108.806-2.057 1.907-2.185a48.208 48.208 0 0 1 1.927-.184" /></svg>
                                                    Salin Link F2
                                                </button>

                                                <a href="{{ $waStageTwoUrl }}"
                                                   target="_blank"
                                                   rel="noopener"
                                                   class="inline-flex items-center gap-1 rounded-lg bg-emerald-50 px-2.5 py-1 text-xs font-semibold text-emerald-700 ring-1 ring-inset ring-emerald-600/20 hover:bg-emerald-100 transition"
                                                   title="Kirim Link Formulir Lengkap via WhatsApp Orang Tua">
                                                    <svg class="size-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 0 1-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0 0 12.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 0 0 5.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 0 0-3.48-8.413Z"/></svg>
                                                    Kirim WA F2
                                                </a>
                                            @endif

                                            @if ($registration->hasCompletedFullForm())
                                                <a href="{{ route('admin.ppdb.print', $registration) }}"
                                                   target="_blank"
                                                   class="inline-flex items-center gap-1 rounded-lg bg-indigo-50 px-2.5 py-1 text-xs font-semibold text-indigo-700 ring-1 ring-inset ring-indigo-600/20 hover:bg-indigo-100 transition"
                                                   title="Cetak Dokumen Lengkap (A4)">
                                                    <svg class="size-3.5" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24-1.076-.64-2.122-1.18-3.111a12.09 12.09 0 0 0-3.54-3.793m0 0a12.094 12.094 0 0 1 5.25-.972 12.094 12.094 0 0 1 5.25.972m-10.5 0v10.5c0 1.242 1.008 2.25 2.25 2.25h10.5a2.25 2.25 0 0 0 2.25-2.25V6.924" /></svg>
                                                    Cetak A4
                                                </a>
                                                <a href="{{ route('admin.ppdb.pdf', $registration) }}"
                                                   class="inline-flex items-center gap-1 rounded-lg bg-rose-50 px-2.5 py-1 text-xs font-semibold text-rose-700 ring-1 ring-inset ring-rose-600/20 hover:bg-rose-100 transition"
                                                   title="Download Dokumen PDF (A4)">
                                                    PDF
                                                </a>
                                            @endif

                                            <a href="{{ $registration->whatsappLink() }}"
                                               target="_blank"
                                               rel="noopener"
                                               class="inline-flex items-center gap-1 rounded-lg bg-amber-50 px-2.5 py-1 text-xs font-semibold text-amber-700 ring-1 ring-inset ring-amber-600/20 hover:bg-amber-100 transition"
                                               title="Chat WhatsApp">
                                                WA
                                            </a>
                                        </div>

                                        <!-- Status Change & Delete Group -->
                                        <div class="flex items-center gap-1.5">
                                            <form method="POST"
                                                  action="{{ route('admin.ppdb.status', $registration) }}"
                                                  class="flex items-center gap-1.5">
                                                @csrf
                                                @method('PATCH')
                                                <label class="sr-only" for="status-{{ $registration->id }}">Ubah status {{ $registration->full_name }}</label>
                                                <select id="status-{{ $registration->id }}"
                                                        name="status"
                                                        class="rounded-lg border border-slate-200 bg-white py-1 pl-2 pr-7 text-xs font-medium text-slate-700 shadow-sm outline-none transition focus:border-rose-400 focus:ring-2 focus:ring-rose-100">
                                                    @foreach ($statuses as $st)
                                                        <option value="{{ $st }}" @selected($registration->status === $st)>{{ $statusLabels[$st] ?? $st }}</option>
                                                    @endforeach
                                                </select>
                                                <button type="submit"
                                                        class="rounded-lg bg-rose-600 px-2.5 py-1 text-xs font-semibold text-white transition hover:bg-rose-700"
                                                        title="Simpan status">
                                                    Simpan
                                                </button>
                                            </form>

                                            <form method="POST"
                                                  action="{{ route('admin.ppdb.destroy', $registration) }}"
                                                  onsubmit='return confirm(@js("Hapus data pendaftar {$registration->full_name}? Tindakan ini tidak dapat dibatalkan."))'>
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="grid size-7 place-items-center rounded-lg bg-rose-50 text-rose-600 ring-1 ring-inset ring-rose-600/20 transition hover:bg-rose-100"
                                                        title="Hapus pendaftar">
                                                    <svg class="size-3.5" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" aria-hidden="true">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-16 text-center">
                                    <div class="mx-auto grid size-12 place-items-center rounded-2xl bg-slate-100 text-slate-400">
                                        <svg class="size-6" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25ZM6.75 12h.008v.008H6.75V12Zm0 3h.008v.008H6.75V15Zm0 3h.008v.008H6.75V18Z" />
                                        </svg>
                                    </div>
                                    <p class="mt-3 text-sm font-semibold text-slate-900">Belum ada pendaftar</p>
                                    <p class="mt-1 text-xs text-slate-500">
                                        @if ($activeLevel)
                                            Tidak ada pendaftar untuk jenjang {{ $activeLevel }}.
                                        @else
                                            Data pendaftar SPMB akan muncul di sini.
                                        @endif
                                    </p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($registrations->hasPages())
                <div class="border-t border-slate-200 px-5 py-4">
                    {{ $registrations->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
