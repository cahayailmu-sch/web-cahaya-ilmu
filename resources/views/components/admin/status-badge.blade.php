@props(['status'])

@php
    $labels = \App\Models\PpdbRegistration::statusLabels();
    $displayLabel = $labels[$status] ?? $status;

    $classes = match ($status) {
        'pending', 'Baru' => 'bg-amber-50 text-amber-700 ring-amber-600/20',
        'verified', 'Diverifikasi' => 'bg-sky-50 text-sky-700 ring-sky-600/20',
        'completed' => 'bg-indigo-50 text-indigo-700 ring-indigo-600/20',
        'accepted', 'Diterima' => 'bg-emerald-50 text-emerald-700 ring-emerald-600/20',
        'rejected', 'Ditolak' => 'bg-rose-50 text-rose-700 ring-rose-600/20',
        default => 'bg-slate-100 text-slate-700 ring-slate-500/20',
    };
@endphp

<span class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-semibold ring-1 ring-inset {{ $classes }}">
    <span class="size-1.5 rounded-full bg-current opacity-70"></span>
    {{ $displayLabel }}
</span>
