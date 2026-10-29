@php
  $feeDetail = $spmbTk['fee_detail'] ?? [];
  $registrationFee = (int) ($feeDetail['registration_fee'] ?? 250000);
  $biayaMasuk = $feeDetail['biaya_masuk'] ?? [];
  $biayaBulanan = $feeDetail['biaya_bulanan'] ?? [];
  $periode = $spmbTk['school_year'] ?? \App\Models\SchoolSetting::activeSchoolYear();
  $cicilanDeadline = $installmentDeadline ?? \App\Models\SchoolSetting::installmentDeadlineLabel();
  $rupiah = fn (int|float $value): string => 'Rp '.number_format($value, 0, ',', '.');
@endphp

<div class="rounded-2xl border border-brand-100 bg-brand-50/60 p-5 sm:p-6">
  <div class="flex flex-wrap items-start justify-between gap-3">
    <h3 class="flex items-center gap-2 text-sm font-extrabold text-brand-900">
      <i class="fa-solid fa-receipt text-brand-600" aria-hidden="true"></i>
      Rincian Biaya {{ $spmbTk['name'] ?? 'KB-TK Islam Cahaya Ilmu' }}
    </h3>
    <span class="inline-flex items-center gap-1.5 rounded-full bg-white px-3 py-1 text-[10px] font-extrabold uppercase tracking-wider text-brand-700 ring-1 ring-brand-100">
      <i class="fa-regular fa-calendar" aria-hidden="true"></i> TA {{ $periode }}
    </span>
  </div>

  <div class="mt-4 rounded-xl bg-white p-4 ring-1 ring-brand-100/70">
    <div class="flex items-baseline justify-between gap-3">
      <span class="text-xs font-bold text-slate-700">Formulir / Pendaftaran Awal</span>
      <span class="text-sm font-extrabold text-brand-900">{{ $rupiah($registrationFee) }}</span>
    </div>
  </div>

  <div class="mt-4 space-y-3">
    <div>
      <p class="text-[10px] font-extrabold uppercase tracking-[0.14em] text-brand-700">A. Biaya Masuk</p>
      <dl class="mt-2 space-y-2 rounded-xl bg-white p-4 text-xs text-slate-700 ring-1 ring-brand-100/70">
        <div class="flex items-baseline justify-between gap-3">
          <dt class="font-semibold">1. Uang Pangkal</dt>
          <dd class="font-extrabold text-slate-900">{{ $rupiah((int) ($biayaMasuk['uang_pangkal'] ?? 4500000)) }}</dd>
        </div>
        <div class="border-t border-slate-100 pt-2">
          <div class="flex items-baseline justify-between gap-3">
            <dt class="font-semibold">2. Perlengkapan Peserta Didik</dt>
            <dd class="font-extrabold text-slate-900">{{ $rupiah((int) ($biayaMasuk['perlengkapan'] ?? 1600000)) }}</dd>
          </div>
          @if (! empty($biayaMasuk['perlengkapan_items']))
            <ul class="mt-2 grid gap-1 pl-4 text-[11px] text-slate-500 sm:grid-cols-2">
              @foreach ($biayaMasuk['perlengkapan_items'] as $item)
                <li class="flex items-start gap-1.5">
                  <i class="fa-solid fa-circle-check mt-0.5 text-[9px] text-brand-500" aria-hidden="true"></i>
                  <span>{{ $item }}</span>
                </li>
              @endforeach
            </ul>
          @endif
        </div>
        <div class="flex items-baseline justify-between gap-3 border-t border-slate-100 pt-2">
          <dt class="font-semibold">3. Biaya Pertahun</dt>
          <dd class="font-extrabold text-slate-900">{{ $rupiah((int) ($biayaMasuk['pertahun'] ?? 1900000)) }}</dd>
        </div>
        <div class="flex items-baseline justify-between gap-3 border-t-2 border-brand-100 pt-2">
          <dt class="font-extrabold text-brand-900">Total Biaya Masuk</dt>
          <dd class="text-sm font-extrabold text-brand-700">{{ $rupiah((int) ($biayaMasuk['total'] ?? 8000000)) }}</dd>
        </div>
      </dl>
    </div>

    <div>
      <p class="text-[10px] font-extrabold uppercase tracking-[0.14em] text-brand-700">B. Biaya Bulanan</p>
      <dl class="mt-2 space-y-2 rounded-xl bg-white p-4 text-xs text-slate-700 ring-1 ring-brand-100/70">
        <div class="flex items-baseline justify-between gap-3">
          <dt class="font-semibold">1. SPP</dt>
          <dd class="font-extrabold text-slate-900">{{ $rupiah((int) ($biayaBulanan['spp'] ?? 550000)) }} <span class="text-[10px] font-normal text-slate-500">/ bulan</span></dd>
        </div>
        <div class="flex items-baseline justify-between gap-3">
          <dt class="font-semibold">2. Konsumsi (Snack)</dt>
          <dd class="font-extrabold text-slate-900">{{ $rupiah((int) ($biayaBulanan['konsumsi'] ?? 150000)) }} <span class="text-[10px] font-normal text-slate-500">/ bulan</span></dd>
        </div>
        <div class="flex items-baseline justify-between gap-3 border-t-2 border-brand-100 pt-2">
          <dt class="font-extrabold text-brand-900">Total Biaya Bulanan</dt>
          <dd class="text-sm font-extrabold text-brand-700">{{ $rupiah((int) ($biayaBulanan['total'] ?? 700000)) }} <span class="text-[10px] font-normal text-slate-500">/ bulan</span></dd>
        </div>
      </dl>
    </div>
  </div>

  <p class="mt-3 flex items-start gap-2 rounded-xl border border-gold-200 bg-gold-50 p-3 text-[11px] font-semibold leading-5 text-gold-900">
    <i class="fa-solid fa-circle-info mt-0.5 text-gold-600" aria-hidden="true"></i>
    <span>{{ $feeDetail['note'] ?? 'Total biaya masuk belum termasuk SPP dan konsumsi.' }}</span>
  </p>

  <div class="mt-3 rounded-xl bg-white p-4 text-[11px] leading-5 text-slate-600 ring-1 ring-brand-100/70">
    <p class="font-extrabold text-slate-800">Cara Pembayaran</p>
    <ul class="mt-1.5 space-y-1">
      <li class="flex items-start gap-1.5">
        <i class="fa-solid fa-building-columns mt-0.5 text-brand-500" aria-hidden="true"></i>
        <span>Transfer Rek. <strong>BSI {{ $spmbTk['account_number'] ?? '3115917772' }}</strong> an. {{ $spmbTk['account_name'] ?? 'TK Islam Cahaya Ilmu' }}</span>
      </li>
      <li class="flex items-start gap-1.5">
        <i class="fa-regular fa-calendar-check mt-0.5 text-brand-500" aria-hidden="true"></i>
        <span>Cicilan sampai bulan <strong>{{ $cicilanDeadline }}</strong></span>
      </li>
    </ul>
  </div>
</div>
