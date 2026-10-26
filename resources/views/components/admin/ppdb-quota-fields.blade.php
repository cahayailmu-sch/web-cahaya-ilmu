@props([
    'settings' => [],
    'quotaSummary' => [],
    'quotaLevels' => [],
])

@php
    $tkGroupDefinitions = \App\Models\SchoolSetting::PPDB_TK_GROUPS;
@endphp

<div class="grid gap-4 xl:grid-cols-2">
    @foreach ($quotaLevels as $key => $label)
        @php
            $summary = $quotaSummary[$label] ?? null;
            $isTk = $key === 'tk';
        @endphp

        <div class="rounded-xl border border-slate-200 p-4">
            <div class="flex flex-wrap items-center justify-between gap-2">
                <h3 class="text-sm font-semibold text-slate-900">
                    {{ $isTk ? 'Kuota KB-TK per Kelompok' : 'Kuota Jenjang '.$label }}
                </h3>
                @if ($summary['is_full'] ?? false)
                    <span class="inline-flex items-center rounded-full bg-rose-100 px-2.5 py-1 text-[10px] font-extrabold uppercase tracking-wider text-rose-700">
                        Kuota Penuh
                    </span>
                @else
                    <span class="inline-flex items-center rounded-full bg-emerald-50 px-2.5 py-1 text-[10px] font-extrabold uppercase tracking-wider text-emerald-700">
                        Tersedia
                    </span>
                @endif
            </div>

            @if ($isTk)
                @php
                    $groupQuotas = $summary['groups'] ?? [];

                    if ($groupQuotas === []) {
                        $groupQuotas = [];

                        foreach ($tkGroupDefinitions as $groupKey => $definition) {
                            $quota = (int) ($settings[$definition['setting_key']] ?? $definition['default']);

                            $groupQuotas[$groupKey] = [
                                'key' => $groupKey,
                                'label' => $definition['label'],
                                'slug' => $definition['slug'],
                                'setting_key' => $definition['setting_key'],
                                'used' => 0,
                                'quota' => $quota,
                                'remaining' => $quota,
                                'percentage' => 0,
                                'is_full' => false,
                            ];
                        }
                    }
                @endphp

                <div class="mt-4 space-y-3">
                    @foreach ($groupQuotas as $group)
                        <div>
                            <div class="flex flex-wrap items-center justify-between gap-2 text-xs">
                                <span class="font-medium text-slate-600">
                                    {{ $group['label'] }}: Terisi {{ number_format($group['used']) }} / {{ number_format($group['quota']) }}
                                </span>
                                @if ($group['is_full'])
                                    <span class="rounded-md bg-rose-100 px-2 py-0.5 text-[10px] font-extrabold uppercase tracking-wider text-rose-700">PENUH</span>
                                @else
                                    <span class="font-semibold text-emerald-700">Sisa {{ number_format($group['remaining']) }} kursi</span>
                                @endif
                            </div>
                            <div class="mt-1.5 h-1.5 overflow-hidden rounded-full bg-slate-100">
                                <div class="h-full rounded-full {{ $group['is_full'] ? 'bg-rose-500' : 'bg-emerald-500' }}"
                                     style="width: {{ $group['percentage'] }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-4 grid gap-3 sm:grid-cols-3">
                    @foreach ($groupQuotas as $group)
                        <div>
                            <label for="{{ $group['setting_key'] }}" class="block text-xs font-medium text-slate-600">Kuota {{ $group['label'] }}</label>
                            <input id="{{ $group['setting_key'] }}"
                                   name="{{ $group['setting_key'] }}"
                                   type="number"
                                   min="0"
                                   max="10000"
                                   placeholder="{{ $tkGroupDefinitions[$group['key']]['default'] ?? 0 }}"
                                   value="{{ old($group['setting_key'], $group['quota']) }}"
                                   class="mt-1 block w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm shadow-sm outline-none transition placeholder:text-slate-400 focus:border-rose-400 focus:ring-2 focus:ring-rose-100">
                            @error($group['setting_key'])
                                <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>
                    @endforeach
                </div>
            @else
                @php
                    $defaults = \App\Models\SchoolSetting::PPDB_DEFAULT_QUOTAS[$key];
                    $maleQuota = $summary['male']['quota'] ?? (int) ($settings["quota_{$key}_l"] ?? $defaults['l']);
                    $femaleQuota = $summary['female']['quota'] ?? (int) ($settings["quota_{$key}_p"] ?? $defaults['p']);
                @endphp

                @if ($summary)
                    <div class="mt-4 space-y-3">
                        @foreach (['male', 'female'] as $genderKey)
                            @php
                                $data = $summary[$genderKey];
                            @endphp
                            <div>
                                <div class="flex flex-wrap items-center justify-between gap-2 text-xs">
                                    <span class="font-medium text-slate-600">
                                        Terisi: {{ number_format($data['used']) }} / {{ number_format($data['quota']) }} {{ $data['label'] }}
                                    </span>
                                    @if ($data['is_full'])
                                        <span class="rounded-md bg-rose-100 px-2 py-0.5 text-[10px] font-extrabold uppercase tracking-wider text-rose-700">PENUH</span>
                                    @else
                                        <span class="font-semibold text-emerald-700">Sisa {{ number_format($data['remaining']) }} kursi</span>
                                    @endif
                                </div>
                                <div class="mt-1.5 h-1.5 overflow-hidden rounded-full bg-slate-100">
                                    <div class="h-full rounded-full {{ $data['is_full'] ? 'bg-rose-500' : 'bg-emerald-500' }}"
                                         style="width: {{ $data['percentage'] }}%"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif

                <div class="mt-4 grid gap-3 sm:grid-cols-2">
                    <div>
                        <label for="quota_{{ $key }}_l" class="block text-xs font-medium text-slate-600">Kuota Laki-laki</label>
                        <input id="quota_{{ $key }}_l"
                               name="quota_{{ $key }}_l"
                               type="number"
                               min="0"
                               max="10000"
                               placeholder="{{ $defaults['l'] }}"
                               value="{{ old("quota_{$key}_l", $maleQuota) }}"
                               class="mt-1 block w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm shadow-sm outline-none transition placeholder:text-slate-400 focus:border-rose-400 focus:ring-2 focus:ring-rose-100">
                        @error("quota_{$key}_l")
                            <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="quota_{{ $key }}_p" class="block text-xs font-medium text-slate-600">Kuota Perempuan</label>
                        <input id="quota_{{ $key }}_p"
                               name="quota_{{ $key }}_p"
                               type="number"
                               min="0"
                               max="10000"
                               placeholder="{{ $defaults['p'] }}"
                               value="{{ old("quota_{$key}_p", $femaleQuota) }}"
                               class="mt-1 block w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm shadow-sm outline-none transition placeholder:text-slate-400 focus:border-rose-400 focus:ring-2 focus:ring-rose-100">
                        @error("quota_{$key}_p")
                            <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            @endif
        </div>
    @endforeach
</div>

@if (empty($quotaLevels))
    <p class="rounded-xl border border-amber-200 bg-amber-50 px-4 py-3 text-xs text-amber-800">
        Akun Anda belum memiliki jenjang, sehingga pengaturan kuota belum dapat diubah.
    </p>
@endif
