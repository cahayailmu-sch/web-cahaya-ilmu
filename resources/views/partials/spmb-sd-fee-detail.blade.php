@php
  $sdFee = $sdFee ?? config('spmb.fees.SD', []);
  $genders = (array) ($sdFee['genders'] ?? []);
  $schoolYear = $sdFee['school_year'] ?? \App\Models\SchoolSetting::activeSchoolYear();
  $schoolName = $sdFee['name'] ?? 'SD Islam Cahaya Ilmu';
  $notes = (array) ($sdFee['notes'] ?? []);
  $totals = (array) ($sdFee['uang_masuk'] ?? []);
  $spps = (array) ($sdFee['spp'] ?? []);
  $konsumsi = (int) ($sdFee['konsumsi'] ?? 0);

  $registrationGender = isset($registration) ? (string) $registration->gender : 'L';
  $selectedGender = $selectedGender ?? old('gender', $registrationGender);
  $selectedGender = in_array($selectedGender, ['L', 'P'], true) ? $selectedGender : 'L';

  $showSelection = (bool) ($showSelection ?? false);
  $pilihanUang = (int) ($pilihanUang ?? old('fee.pilihan_uang_masuk', isset($registration) ? $registration->formValue('pilihan_uang_masuk', 1) : 1));
  $pilihanSpp = (int) ($pilihanSpp ?? old('fee.pilihan_spp', isset($registration) ? $registration->formValue('pilihan_spp', 1) : 1));
  $pilihanUang = in_array($pilihanUang, [1, 2, 3], true) ? $pilihanUang : 1;
  $pilihanSpp = in_array($pilihanSpp, [1, 2, 3], true) ? $pilihanSpp : 1;

  $uid = 'sd-fee-'.($showSelection ? 'form' : 'info');
  $rupiah = static fn ($value): string => 'Rp '.number_format((int) $value, 0, ',', '.');
  $activeGender = $genders[$selectedGender] ?? [];
@endphp

<div class="sd-fee-detail space-y-5" data-sd-fee>
  <div class="flex flex-wrap items-center justify-between gap-3">
    <div class="inline-flex flex-wrap rounded-xl border border-slate-200 bg-slate-100 p-1" role="tablist" aria-label="Rincian biaya SD berdasarkan jenis kelamin">
      @foreach ([['key' => 'L', 'label' => 'Siswa Baniin (Putra)'], ['key' => 'P', 'label' => 'Siswa Banaat (Putri)']] as $tab)
        <button
          type="button"
          role="tab"
          data-sd-gender-tab="{{ $tab['key'] }}"
          aria-selected="{{ $selectedGender === $tab['key'] ? 'true' : 'false' }}"
          class="rounded-lg px-3.5 py-2 text-[11px] font-extrabold transition sm:text-xs {{ $selectedGender === $tab['key'] ? 'bg-white text-brand-700 shadow-sm' : 'text-slate-500 hover:text-slate-700' }}"
        >
          {{ $tab['label'] }}
        </button>
      @endforeach
    </div>
    <p class="text-[10px] font-bold uppercase tracking-[0.14em] text-slate-400">
      Tahun Ajaran {{ $schoolYear }}
    </p>
  </div>

  @foreach ($genders as $genderKey => $gender)
    <div
      data-sd-gender-panel="{{ $genderKey }}"
      class="space-y-4 {{ $selectedGender === $genderKey ? '' : 'hidden' }}"
    >
      <p class="text-xs leading-6 text-slate-500">
        Rincian biaya resmi <strong class="font-extrabold text-slate-700">{{ $schoolName }}</strong>
        Tahun Ajaran <strong class="font-extrabold text-slate-700">{{ $schoolYear }}</strong>
        untuk <strong class="font-extrabold text-brand-700">{{ $gender['label'] ?? '' }}</strong>.
      </p>

      <!-- A. Uang Masuk -->
      <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white">
        <div class="border-b border-slate-100 bg-slate-50 px-4 py-3">
          <h4 class="text-xs font-extrabold text-slate-800">A. Uang Masuk (Uang Pangkal / Operasional Sekolah)</h4>
        </div>
        <div class="overflow-x-auto">
          <table class="w-full min-w-[26rem] text-left text-xs">
            <thead class="bg-white text-[10px] font-extrabold uppercase tracking-wider text-slate-400">
              <tr>
                <th class="px-4 py-2.5">Komponen</th>
                @foreach ([1, 2, 3] as $package)
                  <th class="px-4 py-2.5 text-right">Pilihan {{ $package }}</th>
                @endforeach
              </tr>
            </thead>
            <tbody class="border-t border-slate-100">
              <tr>
                <td class="px-4 py-3 font-semibold text-slate-700">Uang Pangkal (A)</td>
                @foreach ([1, 2, 3] as $package)
                  <td class="px-4 py-3 text-right font-extrabold text-slate-900">
                    {{ $rupiah($gender['uang_pangkal'][$package] ?? 0) }}
                  </td>
                @endforeach
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- B. Perlengkapan Siswa -->
      <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white">
        <div class="border-b border-slate-100 bg-slate-50 px-4 py-3">
          <h4 class="text-xs font-extrabold text-slate-800">B. Perlengkapan Siswa <span class="font-semibold text-slate-400">(sama untuk Pilihan 1, 2, dan 3)</span></h4>
        </div>
        <div class="space-y-4 px-4 py-4 text-xs">
          <div>
            <p class="font-extrabold text-slate-800">1. Seragam</p>
            <ul class="mt-2 space-y-1.5">
              @foreach (($gender['perlengkapan']['seragam'] ?? []) as $item)
                <li class="flex items-start justify-between gap-4">
                  <span class="text-slate-600">{{ $item['label'] }}</span>
                  <span class="shrink-0 font-bold text-slate-800">{{ $rupiah($item['amount'] ?? 0) }}</span>
                </li>
              @endforeach
            </ul>
            <p class="mt-2 flex items-center justify-between border-t border-dashed border-slate-200 pt-2 font-extrabold text-slate-700">
              <span>Subtotal Seragam</span>
              <span>{{ $rupiah($gender['perlengkapan']['seragam_total'] ?? 0) }}</span>
            </p>
          </div>

          <div>
            <p class="font-extrabold text-slate-800">2. Biaya Tahunan</p>
            <ul class="mt-2 space-y-1.5">
              @foreach (($gender['perlengkapan']['tahunan'] ?? []) as $item)
                <li class="flex items-start justify-between gap-4">
                  <span class="text-slate-600">{{ $item['label'] }}</span>
                  <span class="shrink-0 font-bold text-slate-800">{{ $rupiah($item['amount'] ?? 0) }}</span>
                </li>
              @endforeach
            </ul>
            <p class="mt-2 flex items-center justify-between border-t border-dashed border-slate-200 pt-2 font-extrabold text-slate-700">
              <span>Subtotal Biaya Tahunan</span>
              <span>{{ $rupiah($gender['perlengkapan']['tahunan_total'] ?? 0) }}</span>
            </p>
          </div>

          <p class="flex items-center justify-between rounded-xl bg-brand-50 px-3 py-2.5 text-sm font-extrabold text-brand-800">
            <span>Total Perlengkapan Siswa (B)</span>
            <span>{{ $rupiah($gender['perlengkapan']['total'] ?? 0) }}</span>
          </p>
        </div>
      </div>

      <!-- C. Total Uang Masuk -->
      <div class="overflow-hidden rounded-2xl border border-brand-100 bg-brand-50/50">
        <div class="border-b border-brand-100 px-4 py-3">
          <h4 class="text-xs font-extrabold text-brand-900">C. Total Uang Masuk Keseluruhan (A + B)</h4>
        </div>
        <div class="grid gap-2 p-3 sm:grid-cols-3">
          @foreach ([1, 2, 3] as $package)
            <div class="rounded-xl border border-brand-100 bg-white px-3 py-3 text-center">
              <p class="text-[10px] font-extrabold uppercase tracking-wider text-slate-400">Pilihan {{ $package }}</p>
              <p class="mt-1 text-sm font-extrabold text-brand-800">{{ $rupiah($totals[$package] ?? 0) }}</p>
            </div>
          @endforeach
        </div>
      </div>

      <!-- D. Biaya Bulanan -->
      <div class="overflow-hidden rounded-2xl border border-slate-200 bg-white">
        <div class="border-b border-slate-100 bg-slate-50 px-4 py-3">
          <h4 class="text-xs font-extrabold text-slate-800">D. Biaya Bulanan</h4>
        </div>
        <div class="overflow-x-auto">
          <table class="w-full min-w-[26rem] text-left text-xs">
            <thead class="bg-white text-[10px] font-extrabold uppercase tracking-wider text-slate-400">
              <tr>
                <th class="px-4 py-2.5">Komponen</th>
                @foreach ([1, 2, 3] as $package)
                  <th class="px-4 py-2.5 text-right">Pilihan {{ $package }}</th>
                @endforeach
              </tr>
            </thead>
            <tbody class="border-t border-slate-100">
              <tr>
                <td class="px-4 py-3 font-semibold text-slate-700">a. SPP</td>
                @foreach ([1, 2, 3] as $package)
                  <td class="px-4 py-3 text-right font-extrabold text-slate-900">
                    {{ $rupiah($spps[$package] ?? 0) }} <span class="text-[10px] font-normal text-slate-400">/ bulan</span>
                  </td>
                @endforeach
              </tr>
              <tr class="border-t border-slate-100">
                <td class="px-4 py-3 font-semibold text-slate-700">b. Konsumsi</td>
                @foreach ([1, 2, 3] as $package)
                  <td class="px-4 py-3 text-right font-extrabold text-slate-900">
                    {{ $rupiah($konsumsi) }} <span class="text-[10px] font-normal text-slate-400">/ bulan</span>
                  </td>
                @endforeach
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  @endforeach

  @if ($showSelection)
    <div class="space-y-3">
      <div>
        <h4 class="text-sm font-extrabold text-slate-900">Pilih Paket Biaya</h4>
        <p class="mt-1 text-xs text-slate-500">Pilih salah satu paket. Pilihan ini akan tercatat pada Surat Kesanggupan Biaya.</p>
      </div>

      <div class="grid gap-3 sm:grid-cols-3">
        @foreach ([1, 2, 3] as $package)
          <div
            data-sd-package="{{ $package }}"
            class="cursor-pointer rounded-2xl border border-slate-200 bg-white p-4 transition hover:border-brand-300 hover:bg-brand-50/30 has-[:checked]:border-brand-500 has-[:checked]:bg-brand-50/50"
          >
            <div class="flex items-start gap-2.5">
              <input
                type="radio"
                id="{{ $uid }}-paket-{{ $package }}"
                name="fee[pilihan_uang_masuk]"
                value="{{ $package }}"
                data-sd-package-radio="{{ $package }}"
                @checked($pilihanUang === $package)
                class="mt-1 size-4 shrink-0 border-slate-300 text-brand-600 focus:ring-brand-500"
              >
              <label for="{{ $uid }}-paket-{{ $package }}" class="min-w-0 cursor-pointer">
                <span class="inline-block rounded-full bg-brand-100 px-2.5 py-0.5 text-[10px] font-extrabold text-brand-800">
                  Pilihan {{ $package }}
                </span>
                <dl class="mt-2.5 space-y-1.5 text-[11px] leading-5">
                  <div class="flex items-start justify-between gap-2">
                    <dt class="text-slate-500">Uang Pangkal (A)</dt>
                    <dd
                      class="shrink-0 font-extrabold text-slate-800"
                      data-sd-paket-uang-pangkal="{{ $package }}"
                      data-l="{{ $rupiah($genders['L']['uang_pangkal'][$package] ?? 0) }}"
                      data-p="{{ $rupiah($genders['P']['uang_pangkal'][$package] ?? 0) }}"
                    >{{ $rupiah($activeGender['uang_pangkal'][$package] ?? 0) }}</dd>
                  </div>
                  <div class="flex items-start justify-between gap-2">
                    <dt class="text-slate-500">Perlengkapan (B)</dt>
                    <dd
                      class="shrink-0 font-extrabold text-slate-800"
                      data-sd-paket-perlengkapan="{{ $package }}"
                      data-l="{{ $rupiah($genders['L']['perlengkapan']['total'] ?? 0) }}"
                      data-p="{{ $rupiah($genders['P']['perlengkapan']['total'] ?? 0) }}"
                    >{{ $rupiah($activeGender['perlengkapan']['total'] ?? 0) }}</dd>
                  </div>
                  <div class="flex items-start justify-between gap-2 border-t border-dashed border-slate-200 pt-1.5">
                    <dt class="text-slate-500">Total Uang Masuk (C)</dt>
                    <dd class="shrink-0 font-extrabold text-brand-800">{{ $rupiah($totals[$package] ?? 0) }}</dd>
                  </div>
                  <div class="flex items-start justify-between gap-2">
                    <dt class="text-slate-500">SPP (D.a)</dt>
                    <dd class="shrink-0 font-extrabold text-slate-800">{{ $rupiah($spps[$package] ?? 0) }}<span class="font-normal text-slate-400">/bln</span></dd>
                  </div>
                  <div class="flex items-start justify-between gap-2">
                    <dt class="text-slate-500">Konsumsi (D.b)</dt>
                    <dd class="shrink-0 font-extrabold text-slate-800">{{ $rupiah($konsumsi) }}<span class="font-normal text-slate-400">/bln</span></dd>
                  </div>
                </dl>
              </label>
            </div>
            <input
              type="radio"
              name="fee[pilihan_spp]"
              value="{{ $package }}"
              data-sd-spp="{{ $package }}"
              @checked($pilihanSpp === $package)
              class="sr-only"
              tabindex="-1"
              aria-hidden="true"
            >
          </div>
        @endforeach
      </div>

      @error('fee.pilihan_uang_masuk')
        <p class="text-xs font-semibold text-rose-600">{{ $message }}</p>
      @enderror
      @error('fee.pilihan_spp')
        <p class="text-xs font-semibold text-rose-600">{{ $message }}</p>
      @enderror
    </div>
  @endif

  @if ($notes !== [])
    <div class="rounded-2xl border border-gold-200 bg-gold-50/70 p-4">
      <p class="flex items-center gap-2 text-xs font-extrabold text-gold-900">
        <i class="fa-solid fa-triangle-exclamation" aria-hidden="true"></i>
        Catatan Penting
      </p>
      <ul class="mt-2.5 space-y-1.5 text-[11px] leading-5 text-gold-900/90">
        @foreach ($notes as $note)
          <li class="flex gap-2">
            <span class="shrink-0 font-extrabold">(-)</span>
            <span>{{ $note }}</span>
          </li>
        @endforeach
      </ul>
    </div>
  @endif
</div>

@once
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const activateGender = function (root, gender) {
        root.querySelectorAll('[data-sd-gender-tab]').forEach(function (tab) {
          const isActive = tab.dataset.sdGenderTab === gender;
          tab.setAttribute('aria-selected', isActive ? 'true' : 'false');
          tab.classList.toggle('bg-white', isActive);
          tab.classList.toggle('text-brand-700', isActive);
          tab.classList.toggle('shadow-sm', isActive);
          tab.classList.toggle('text-slate-500', !isActive);
        });

        root.querySelectorAll('[data-sd-gender-panel]').forEach(function (panel) {
          panel.classList.toggle('hidden', panel.dataset.sdGenderPanel !== gender);
        });

        root.querySelectorAll('[data-sd-paket-uang-pangkal]').forEach(function (element) {
          element.textContent = gender === 'P' ? element.dataset.p : element.dataset.l;
        });

        root.querySelectorAll('[data-sd-paket-perlengkapan]').forEach(function (element) {
          element.textContent = gender === 'P' ? element.dataset.p : element.dataset.l;
        });
      };

      const sdFeeRoots = Array.from(document.querySelectorAll('[data-sd-fee]'));

      sdFeeRoots.forEach(function (root) {
        root.querySelectorAll('[data-sd-gender-tab]').forEach(function (tab) {
          tab.addEventListener('click', function () {
            activateGender(root, tab.dataset.sdGenderTab);
          });
        });

        const syncSpp = function (value) {
          const target = root.querySelector('[data-sd-spp="' + value + '"]');

          if (target) {
            target.checked = true;
          }
        };

        root.querySelectorAll('[data-sd-package-radio]').forEach(function (radio) {
          radio.addEventListener('change', function () {
            syncSpp(radio.value);
          });
        });

        const initialPackage = root.querySelector('[data-sd-package-radio]:checked');

        if (initialPackage) {
          syncSpp(initialPackage.value);
        }

        root.querySelectorAll('[data-sd-package]').forEach(function (card) {
          card.addEventListener('click', function (event) {
            if (event.target.closest('input')) {
              return;
            }

            const radio = card.querySelector('[data-sd-package-radio]');

            if (radio) {
              radio.checked = true;
              radio.dispatchEvent(new Event('change', { bubbles: true }));
            }
          });
        });
      });

      const genderSelect = document.getElementById('jenis-kelamin');

      if (genderSelect) {
        const syncSdGender = function () {
          const gender = genderSelect.value === 'P' ? 'P' : 'L';

          sdFeeRoots.forEach(function (root) {
            activateGender(root, gender);
          });
        };

        genderSelect.addEventListener('change', syncSdGender);

        if (genderSelect.value) {
          syncSdGender();
        }
      }
    });
  </script>
@endonce
