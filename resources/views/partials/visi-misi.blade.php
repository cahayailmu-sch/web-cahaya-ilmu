@php
    $sectionClass = $sectionClass ?? 'section-pad bg-slate-50';
    $eyebrow = $eyebrow ?? 'Visi & Misi';
    $title = $title ?? 'Arah pendidikan yang jelas dan terukur.';
    $lead = $lead ?? null;
    $visi = $visi ?? config('sekolah.visi');
    $misi = $misi ?? config('sekolah.misi');
@endphp

<section @isset($id) id="{{ $id }}" @endisset class="{{ $sectionClass }}">
  <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
    <div class="section-heading max-w-3xl">
      <span class="eyebrow">{{ $eyebrow }}</span>
      <h2 class="section-title mt-4">{{ $title }}</h2>
      @if ($lead)
        <p class="section-lead">{{ $lead }}</p>
      @endif
    </div>

    <div class="reveal mt-8 grid gap-8 sm:mt-10 sm:gap-10 lg:mt-12 lg:grid-cols-[0.95fr_1.05fr] lg:items-start lg:gap-16">
      <!-- Visi sebagai tipografi besar, tanpa kotak -->
      <div class="lg:border-r lg:border-slate-200 lg:pr-12">
        <span class="text-[11px] font-extrabold uppercase tracking-[0.2em] text-rose-700">Visi Kami</span>
        <blockquote class="mt-3 text-xl font-semibold leading-[1.5] text-slate-900 sm:mt-4 sm:text-2xl lg:text-3xl lg:leading-[1.5]">
          &ldquo;{{ $visi }}&rdquo;
        </blockquote>
        <span class="mt-5 block h-px w-14 bg-rose-400/60 sm:mt-6 sm:w-16" aria-hidden="true"></span>
      </div>

      <!-- Misi sebagai daftar bersih bernomor -->
      <div>
        <div class="flex flex-wrap items-baseline justify-between gap-2">
          <h3 class="text-lg font-extrabold text-slate-900 sm:text-xl">Misi</h3>
          <span class="text-[10px] font-extrabold uppercase tracking-[0.16em] text-slate-400 sm:text-[11px]">8 Komitmen</span>
        </div>
        <p class="mt-2 text-sm leading-7 text-slate-500">Delapan komitmen untuk mewujudkan visi pendidikan Cahaya Ilmu.</p>

        <ol class="mt-4 divide-y divide-slate-200 sm:mt-5">
          @foreach ($misi as $mission)
            <li class="flex gap-3 py-3.5 sm:gap-4 sm:py-4">
              <span class="shrink-0 text-xs font-extrabold tabular-nums tracking-wider text-rose-600">{{ str_pad((string) $loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
              <p class="text-sm leading-6 text-slate-600 sm:leading-7">{{ $mission }}</p>
            </li>
          @endforeach
        </ol>
      </div>
    </div>
  </div>
</section>
