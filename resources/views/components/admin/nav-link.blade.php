@props(['href', 'active' => false])

<a href="{{ $href }}"
   @class([
       'group flex items-center gap-3 rounded-xl px-3 py-2 text-sm font-medium transition',
       'bg-rose-600 text-white shadow-sm shadow-rose-950/20' => $active,
       'text-slate-300 hover:bg-white/5 hover:text-white' => ! $active,
   ])>
    <span @class([
        'grid size-8 shrink-0 place-items-center rounded-lg transition',
        'bg-white/15 text-white' => $active,
        'bg-white/5 text-slate-400 group-hover:bg-white/10 group-hover:text-amber-300' => ! $active,
    ])>
        {{ $icon }}
    </span>
    <span class="truncate">{{ $slot }}</span>
</a>
