@extends('admin.layouts.app')

@section('title', 'Ringkasan')
@section('subtitle', 'Gambaran singkat data sekolah dan SPMB')

@section('content')
    <div class="space-y-6">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-900">Ringkasan</h1>
                <p class="mt-1 text-sm text-slate-500">Pantau data pendaftar, info sekolah, dan galeri sekolah.</p>
            </div>
            <div class="flex flex-col gap-2 sm:flex-row sm:items-center">
                @if (auth()->user()->isSuperAdmin())
                    <a href="{{ route('admin.backup.download') }}"
                       class="inline-flex items-center justify-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 shadow-sm transition hover:border-slate-300 hover:bg-slate-50">
                        <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 6.375c0 2.278-3.694 4.125-8.25 4.125S3.75 8.653 3.75 6.375m16.5 0c0-2.278-3.694-4.125-8.25-4.125S3.75 4.097 3.75 6.375m16.5 0v11.25c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125V6.375m16.5 5.625c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125" />
                        </svg>
                        Backup Database
                    </a>
                @endif

                <a href="{{ route('admin.ppdb.index') }}"
                   class="inline-flex items-center justify-center gap-2 rounded-xl bg-rose-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-rose-700">
                    Kelola Data SPMB
                    <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                    </svg>
                </a>
            </div>
        </div>

        <div class="grid gap-4 min-[480px]:grid-cols-2 xl:grid-cols-4">
            <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm sm:p-5">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Total Pendaftar</p>
                        <p class="mt-2 text-3xl font-bold text-slate-900">{{ number_format($totalRegistrations) }}</p>
                    </div>
                    <span class="grid size-10 place-items-center rounded-xl bg-rose-50 text-rose-600">
                        <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
                        </svg>
                    </span>
                </div>
                <p class="mt-3 text-xs text-slate-500">{{ number_format($newRegistrations) }} pendaftar baru menunggu verifikasi</p>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm sm:p-5">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Info Sekolah</p>
                        <p class="mt-2 text-3xl font-bold text-slate-900">{{ number_format($postCount) }}</p>
                    </div>
                    <span class="grid size-10 place-items-center rounded-xl bg-slate-100 text-slate-600">
                        <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 7.5h1.5m-1.5 3h1.5m-7.5 3h7.5m-7.5 3h7.5m3-9h3.375c.621 0 1.125.504 1.125 1.125V18a2.25 2.25 0 0 1-2.25 2.25M16.5 7.5V18a2.25 2.25 0 0 0 2.25 2.25M16.5 7.5V4.875c0-.621-.504-1.125-1.125-1.125H4.125C3.504 3.75 3 4.254 3 4.875V18a2.25 2.25 0 0 0 2.25 2.25h13.5M6 7.5h3v3H6v-3Z" />
                        </svg>
                    </span>
                </div>
                <p class="mt-3 text-xs text-slate-500">{{ number_format($publishedPostCount) }} info sudah dipublikasikan</p>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm sm:p-5">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Galeri Sekolah</p>
                        <p class="mt-2 text-3xl font-bold text-slate-900">{{ number_format($galleryCount) }}</p>
                    </div>
                    <span class="grid size-10 place-items-center rounded-xl bg-amber-50 text-amber-600">
                        <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 0 0 1.5-1.5V6a1.5 1.5 0 0 0-1.5-1.5H3.75A1.5 1.5 0 0 0 2.25 6v12a1.5 1.5 0 0 0 1.5 1.5Zm10.5-11.25h.008v.008h-.008V8.25Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                        </svg>
                    </span>
                </div>
                <p class="mt-3 text-xs text-slate-500">Foto dokumentasi sekolah tersimpan</p>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm sm:p-5">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wider text-slate-400">Pendaftar Baru</p>
                        <p class="mt-2 text-3xl font-bold text-slate-900">{{ number_format($newRegistrations) }}</p>
                    </div>
                    <span class="grid size-10 place-items-center rounded-xl bg-rose-50 text-rose-600">
                        <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
                        </svg>
                    </span>
                </div>
                <p class="mt-3 text-xs text-slate-500">Perlu ditindaklanjuti oleh admin</p>
            </div>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm sm:p-6">
            <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 class="text-base font-semibold text-slate-900">Progress Kuota SPMB</h2>
                    <p class="mt-1 text-xs text-slate-500">Terisi dari pendaftar aktif (Baru, Diverifikasi, dan Diterima).</p>
                </div>
                <a href="{{ route('admin.ppdb-settings.index') }}" class="text-xs font-semibold text-rose-700 transition hover:text-rose-800">
                    Atur kuota &rarr;
                </a>
            </div>

            <div class="mt-5 grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
                @foreach ($quotaSummary as $level => $summary)
                    <div class="rounded-xl border p-4 {{ $summary['is_full'] ? 'border-rose-200 bg-rose-50/50' : 'border-slate-200' }}">
                        <div class="flex items-center justify-between gap-2">
                            <span class="grid size-7 place-items-center rounded-lg bg-rose-50 text-[10px] font-bold text-rose-700">{{ $level }}</span>
                            @if ($summary['is_full'])
                                <span class="rounded-md bg-rose-100 px-2 py-0.5 text-[10px] font-extrabold uppercase tracking-wider text-rose-700">PENUH</span>
                            @else
                                <span class="text-[10px] font-semibold uppercase tracking-wider text-slate-400">Kuota</span>
                            @endif
                        </div>

                        <div class="mt-4 space-y-3">
                            @if ($level === 'TK' && ! empty($summary['groups']))
                                @foreach ($summary['groups'] as $group)
                                    <div>
                                        <div class="flex items-center justify-between gap-2 text-[11px]">
                                            <span class="font-medium text-slate-600">
                                                Terisi: {{ number_format($group['used']) }} / {{ number_format($group['quota']) }} {{ $group['label'] }}
                                            </span>
                                            <span class="font-semibold {{ $group['is_full'] ? 'text-rose-600' : 'text-emerald-700' }}">
                                                {{ $group['is_full'] ? 'PENUH' : 'Sisa '.number_format($group['remaining']) }}
                                            </span>
                                        </div>
                                        <div class="mt-1.5 h-1.5 overflow-hidden rounded-full bg-slate-100">
                                            <div class="h-full rounded-full {{ $group['is_full'] ? 'bg-rose-500' : 'bg-emerald-500' }}"
                                                 style="width: {{ $group['percentage'] }}%"></div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                @foreach (['male', 'female'] as $genderKey)
                                    @php
                                        $data = $summary[$genderKey];
                                    @endphp
                                    <div>
                                        <div class="flex items-center justify-between gap-2 text-[11px]">
                                            <span class="font-medium text-slate-600">
                                                Terisi: {{ number_format($data['used']) }} / {{ number_format($data['quota']) }} {{ $data['label'] }}
                                            </span>
                                            <span class="font-semibold {{ $data['is_full'] ? 'text-rose-600' : 'text-emerald-700' }}">
                                                {{ $data['is_full'] ? 'PENUH' : 'Sisa '.number_format($data['remaining']) }}
                                            </span>
                                        </div>
                                        <div class="mt-1.5 h-1.5 overflow-hidden rounded-full bg-slate-100">
                                            <div class="h-full rounded-full {{ $data['is_full'] ? 'bg-rose-500' : 'bg-emerald-500' }}"
                                                 style="width: {{ $data['percentage'] }}%"></div>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="grid gap-6 xl:grid-cols-2">
            <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm sm:p-6">
                <div class="flex items-center justify-between gap-2">
                    <div>
                        <h2 class="text-base font-semibold text-slate-900">Pendaftar per Jenjang</h2>
                        <p class="mt-1 text-xs text-slate-500">Distribusi pendaftar SPMB berdasarkan jenjang.</p>
                    </div>
                    <span class="rounded-lg bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-600">
                        {{ number_format($totalRegistrations) }} total
                    </span>
                </div>

                <div class="mt-6 space-y-5">
                    @foreach ($levels as $level)
                        @php
                            $count = (int) ($levelCounts[$level] ?? 0);
                            $percentage = $totalRegistrations > 0 ? round(($count / $totalRegistrations) * 100) : 0;
                        @endphp
                        <div>
                            <div class="flex items-center justify-between text-sm">
                                <div class="flex items-center gap-2">
                                    <span class="grid size-7 place-items-center rounded-lg bg-rose-50 text-[10px] font-bold text-rose-700">{{ $level }}</span>
                                    <span class="font-medium text-slate-700">Jenjang {{ $level }}</span>
                                </div>
                                <span class="text-xs font-semibold text-slate-500">{{ $count }} pendaftar &middot; {{ $percentage }}%</span>
                            </div>
                            <div class="mt-2 h-2 overflow-hidden rounded-full bg-slate-100">
                                <div class="h-full rounded-full bg-rose-500" style="width: {{ $percentage }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm sm:p-6">
                <div>
                    <h2 class="text-base font-semibold text-slate-900">Status Pendaftaran</h2>
                    <p class="mt-1 text-xs text-slate-500">Kondisi terkini seluruh pendaftar.</p>
                </div>

                @php
                    $statusColors = [
                        'pending' => ['bg-amber-500', 'bg-amber-50', 'text-amber-700'],
                        'verified' => ['bg-sky-500', 'bg-sky-50', 'text-sky-700'],
                        'completed' => ['bg-indigo-500', 'bg-indigo-50', 'text-indigo-700'],
                        'accepted' => ['bg-emerald-500', 'bg-emerald-50', 'text-emerald-700'],
                        'rejected' => ['bg-rose-500', 'bg-rose-50', 'text-rose-700'],
                        'Baru' => ['bg-amber-500', 'bg-amber-50', 'text-amber-700'],
                        'Diverifikasi' => ['bg-sky-500', 'bg-sky-50', 'text-sky-700'],
                        'Diterima' => ['bg-emerald-500', 'bg-emerald-50', 'text-emerald-700'],
                        'Ditolak' => ['bg-rose-500', 'bg-rose-50', 'text-rose-700'],
                    ];
                @endphp

                <div class="mt-6 grid grid-cols-2 gap-3">
                    @foreach ($statuses as $status)
                        @php
                            $count = (int) ($statusCounts[$status] ?? 0);
                            [$dot, $bg, $text] = $statusColors[$status] ?? ['bg-slate-400', 'bg-slate-50', 'text-slate-700'];
                            $label = $statusLabels[$status] ?? $status;
                        @endphp
                        <div class="rounded-xl border border-slate-100 {{ $bg }} p-4">
                            <div class="flex items-center gap-2">
                                <span class="size-2 rounded-full {{ $dot }}"></span>
                                <p class="text-xs font-semibold {{ $text }}">{{ $label }}</p>
                            </div>
                            <p class="mt-2 text-2xl font-bold text-slate-900">{{ number_format($count) }}</p>
                        </div>
                    @endforeach
                </div>

                <div class="mt-5 flex items-center justify-between rounded-xl bg-slate-50 px-4 py-3 text-xs">
                    <span class="text-slate-500">Total seluruh pendaftar</span>
                    <span class="font-bold text-slate-900">{{ number_format($totalRegistrations) }}</span>
                </div>
            </div>
        </div>

        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="flex items-center justify-between border-b border-slate-200 px-5 py-4">
                <div>
                    <h2 class="text-base font-semibold text-slate-900">Pendaftar Terbaru</h2>
                    <p class="mt-0.5 text-xs text-slate-500">5 pendaftar terakhir yang masuk.</p>
                </div>
                <a href="{{ route('admin.ppdb.index') }}" class="text-xs font-semibold text-rose-700 transition hover:text-rose-800">
                    Lihat semua &rarr;
                </a>
            </div>

            <div class="divide-y divide-slate-100 sm:hidden">
                @forelse ($recentRegistrations as $registration)
                    <div class="flex items-center justify-between gap-3 p-4">
                        <div class="min-w-0">
                            <p class="truncate text-sm font-semibold text-slate-900">{{ $registration->full_name }}</p>
                            <p class="mt-0.5 text-xs text-slate-500">
                                {{ $registration->registration_number }} &middot; {{ $registration->created_at->format('d M Y') }}
                            </p>
                        </div>
                        <div class="flex shrink-0 items-center gap-2">
                            <span class="rounded-md bg-slate-100 px-2 py-1 text-xs font-semibold text-slate-600">{{ $registration->level }}</span>
                            <x-admin.status-badge :status="$registration->status" />
                        </div>
                    </div>
                @empty
                    <div class="px-5 py-12 text-center text-sm text-slate-500">Belum ada pendaftar SPMB.</div>
                @endforelse
            </div>

            <div class="hidden overflow-x-auto sm:block">
                <table class="min-w-full divide-y divide-slate-200 text-sm">
                    <thead class="bg-slate-50 text-left text-[11px] font-semibold uppercase tracking-wider text-slate-500">
                        <tr>
                            <th class="px-5 py-3">Pendaftar</th>
                            <th class="px-5 py-3">Jenjang</th>
                            <th class="px-5 py-3">Status</th>
                            <th class="px-5 py-3 text-right">Tanggal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($recentRegistrations as $registration)
                            <tr class="transition hover:bg-slate-50/70">
                                <td class="px-5 py-3.5">
                                    <p class="font-semibold text-slate-900">{{ $registration->full_name }}</p>
                                    <p class="text-xs text-slate-500">{{ $registration->registration_number }}</p>
                                </td>
                                <td class="px-5 py-3.5">
                                    <span class="rounded-md bg-slate-100 px-2 py-1 text-xs font-semibold text-slate-600">{{ $registration->level }}</span>
                                </td>
                                <td class="px-5 py-3.5">
                                    <x-admin.status-badge :status="$registration->status" />
                                </td>
                                <td class="px-5 py-3.5 text-right text-xs text-slate-500">{{ $registration->created_at->format('d M Y') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-5 py-12 text-center text-sm text-slate-500">Belum ada pendaftar SPMB.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="flex items-center justify-between border-b border-slate-200 px-5 py-4">
                <div>
                    <h2 class="text-base font-semibold text-slate-900">5 Aktivitas Terbaru</h2>
                    <p class="mt-0.5 text-xs text-slate-500">
                        @if (auth()->user()->isSuperAdmin())
                            Pantauan langsung aktivitas dan perubahan terakhir di seluruh sistem.
                        @else
                            Riwayat aktivitas terakhir dari akun Anda.
                        @endif
                    </p>
                </div>
                <a href="{{ route('admin.activity-logs.index') }}" class="text-xs font-semibold text-rose-700 transition hover:text-rose-800">
                    Lihat semua log &rarr;
                </a>
            </div>

            <div class="divide-y divide-slate-100">
                @forelse ($recentActivities ?? [] as $activity)
                    <div class="flex flex-col gap-2 px-5 py-3.5 sm:flex-row sm:items-center sm:justify-between">
                        <div class="min-w-0 space-y-1">
                            <div class="flex flex-wrap items-center gap-2">
                                <span class="inline-flex rounded-full px-2 py-0.5 text-[11px] font-semibold {{ $activity->actionBadgeClass() }}">
                                    {{ $activity->actionLabel() }}
                                </span>
                                <span class="rounded-md bg-slate-100 px-2 py-0.5 text-[11px] font-semibold text-slate-700">
                                    {{ $activity->module }}
                                </span>
                                <span class="text-xs font-semibold text-slate-900">{{ $activity->user_name }}</span>
                                @if ($activity->role_level)
                                    <span class="rounded-md bg-rose-50 px-1.5 py-0.5 text-[10px] font-bold text-rose-700">
                                        {{ $activity->role_level }}
                                    </span>
                                @endif
                            </div>
                            <p class="text-sm text-slate-700">{{ $activity->description }}</p>
                        </div>

                        <div class="shrink-0 text-xs text-slate-400 sm:text-right">
                            <p class="font-medium text-slate-600">{{ $activity->created_at?->timezone('Asia/Makassar')->format('d M Y, H:i') }} WITA</p>
                            <p>{{ $activity->created_at?->diffForHumans() }}</p>
                        </div>
                    </div>
                @empty
                    <div class="px-5 py-10 text-center text-sm text-slate-500">Belum ada riwayat aktivitas tercatat.</div>
                @endforelse
            </div>
        </div>
    </div>
@endsection
