@extends('admin.layouts.app')

@section('title', 'Pengaturan SPMB')
@section('subtitle', 'Atur status, tahun ajaran, kuota, grup WhatsApp, biaya, dan rekening SPMB')

@section('content')
    <div class="mx-auto max-w-6xl space-y-6">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-900">Pengaturan SPMB</h1>
                <p class="mt-1 text-sm text-slate-500">
                    Pengaturan ini khusus untuk penerimaan peserta didik baru dan langsung dipakai halaman publik SPMB.
                </p>
            </div>
            <span class="inline-flex w-fit items-center gap-1.5 rounded-full border border-rose-200 bg-rose-50 px-3 py-1.5 text-xs font-bold text-rose-700">
                <span class="size-1.5 rounded-full bg-rose-500"></span>
                {{ $isSuperAdmin ? 'Superadmin — semua jenjang' : 'Admin jenjang '.(array_values($quotaLevels)[0] ?? '-') }}
            </span>
        </div>

        <form method="POST" action="{{ route('admin.ppdb-settings.update') }}" class="space-y-6">
            @csrf
            @method('PUT')

            @if ($isSuperAdmin)
                <section class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm sm:p-6">
                    <div class="flex items-start gap-2.5 sm:items-center sm:gap-2">
                        <span class="grid size-8 shrink-0 place-items-center rounded-lg bg-rose-50 text-rose-600">
                            <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
                            </svg>
                        </span>
                        <div>
                            <h2 class="text-sm font-semibold text-slate-900">Status &amp; Tahun Ajaran SPMB</h2>
                            <p class="text-xs text-slate-500">Saklar utama pembukaan pendaftaran dan tahun ajaran yang tampil di website.</p>
                        </div>
                    </div>

                    <div class="mt-5 grid gap-4 sm:grid-cols-2">
                        <div>
                            <label for="status_ppdb" class="block text-sm font-medium text-slate-700">Status SPMB (Semua Jenjang)</label>
                            <select id="status_ppdb"
                                    name="status_ppdb"
                                    class="mt-1.5 block w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm shadow-sm outline-none transition focus:border-rose-400 focus:ring-4 focus:ring-rose-100">
                                @foreach (['Buka', 'Tutup'] as $status)
                                    <option value="{{ $status }}" @selected(old('status_ppdb', $settings['status_ppdb'] ?? 'Tutup') === $status)>
                                        SPMB {{ $status }}
                                    </option>
                                @endforeach
                            </select>
                            @error('status_ppdb')
                                <p class="mt-1.5 text-xs text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="tahun_ajaran" class="block text-sm font-medium text-slate-700">Tahun Ajaran Aktif</label>
                            <input id="tahun_ajaran"
                                   name="tahun_ajaran"
                                   type="text"
                                   value="{{ old('tahun_ajaran', $settings['tahun_ajaran'] ?? '') }}"
                                   placeholder="2026/2027"
                                   class="mt-1.5 block w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm shadow-sm outline-none transition placeholder:text-slate-400 focus:border-rose-400 focus:ring-4 focus:ring-rose-100">
                            <p class="mt-1 text-[11px] text-slate-400">Contoh: 2026/2027. Dipakai pada halaman SPMB, status, dan kartu pendaftar.</p>
                            @error('tahun_ajaran')
                                <p class="mt-1.5 text-xs text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </section>
            @endif

            <section class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm sm:p-6">
                <div class="flex items-start gap-2.5 sm:items-center sm:gap-2">
                    <span class="grid size-8 shrink-0 place-items-center rounded-lg bg-rose-50 text-rose-600">
                        <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z" />
                        </svg>
                    </span>
                    <div>
                        <h2 class="text-sm font-semibold text-slate-900">Kuota Pendaftar per Jenjang</h2>
                        <p class="text-xs text-slate-500">KB-TK memakai kuota per kelompok (KB, TK A, TK B). SD, SMP, dan SMA memakai kuota Laki-laki (Ikhwan) dan Perempuan (Akhwat). Pendaftar otomatis diblokir saat kuota penuh.</p>
                    </div>
                </div>

                <div class="mt-5">
                    <x-admin.ppdb-quota-fields :settings="$settings" :quota-summary="$quotaSummary" :quota-levels="$quotaLevels" />
                </div>

                <p class="mt-3 text-[11px] text-slate-400">
                    Kuota KB-TK dihitung per kelompok (KB, TK A, TK B), sedangkan SD, SMP, dan SMA dihitung per jenis kelamin. Pendaftar berstatus Ditolak tidak dihitung.
                </p>
            </section>

            <section class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm sm:p-6">
                <div class="flex items-start gap-2.5 sm:items-center sm:gap-2">
                    <span class="grid size-8 shrink-0 place-items-center rounded-lg bg-emerald-50 text-emerald-600">
                        <svg class="size-4" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 0 1-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0 0 12.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 0 0 5.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 0 0-3.48-8.413Z" />
                        </svg>
                    </span>
                    <div>
                        <h2 class="text-sm font-semibold text-slate-900">Link Grup WhatsApp Pendaftar (Tahap 2)</h2>
                        <p class="text-xs text-slate-500">Pendaftar yang menyelesaikan Formulir Lengkap diarahkan ke grup sesuai jenjang dan jenis kelamin.</p>
                    </div>
                </div>

                <div class="mt-5 grid gap-4 md:grid-cols-2">
                    @forelse ($quotaLevels as $key => $label)
                        <div class="rounded-xl border border-slate-200 bg-slate-50/50 p-4 space-y-3">
                            <div class="flex items-center justify-between">
                                <span class="rounded-md bg-white px-2.5 py-1 text-xs font-bold text-slate-800 ring-1 ring-slate-200">
                                    Jenjang {{ $label }}
                                </span>
                                <span class="text-[11px] text-slate-400">Format: https://chat.whatsapp.com/...</span>
                            </div>

                            <div>
                                <label for="wa_group_{{ $key }}_l" class="block text-xs font-semibold text-slate-700">
                                    Grup WA {{ $label }} — Laki-laki / Ikhwan
                                </label>
                                <input id="wa_group_{{ $key }}_l"
                                       name="wa_group_{{ $key }}_l"
                                       type="url"
                                       placeholder="https://chat.whatsapp.com/..."
                                       value="{{ old("wa_group_{$key}_l", $settings["wa_group_{$key}_l"] ?? '') }}"
                                       class="mt-1 block w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs shadow-sm outline-none transition placeholder:text-slate-400 focus:border-rose-400 focus:ring-2 focus:ring-rose-100">
                                @error("wa_group_{$key}_l")
                                    <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="wa_group_{{ $key }}_p" class="block text-xs font-semibold text-slate-700">
                                    Grup WA {{ $label }} — Perempuan / Akhwat
                                </label>
                                <input id="wa_group_{{ $key }}_p"
                                       name="wa_group_{{ $key }}_p"
                                       type="url"
                                       placeholder="https://chat.whatsapp.com/..."
                                       value="{{ old("wa_group_{$key}_p", $settings["wa_group_{$key}_p"] ?? '') }}"
                                       class="mt-1 block w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs shadow-sm outline-none transition placeholder:text-slate-400 focus:border-rose-400 focus:ring-2 focus:ring-rose-100">
                                @error("wa_group_{$key}_p")
                                    <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                                @enderror
                            </div>

                            @if ($key === 'sma')
                                <p class="text-[11px] text-slate-400">Jenjang SMA dikhususkan untuk putri, sehingga grup Ikhwan bersifat opsional.</p>
                            @endif
                        </div>
                    @empty
                        <p class="rounded-xl border border-amber-200 bg-amber-50 px-4 py-3 text-xs text-amber-800 md:col-span-2">
                            Akun Anda belum memiliki jenjang, sehingga pengaturan grup WhatsApp belum dapat diubah.
                        </p>
                    @endforelse
                </div>
            </section>

            @if ($isSuperAdmin)
                <section class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm sm:p-6">
                    <div class="flex items-start gap-2.5 sm:items-center sm:gap-2">
                        <span class="grid size-8 shrink-0 place-items-center rounded-lg bg-rose-50 text-rose-600">
                            <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 0 0 2.25-2.25V6.75A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25v10.5A2.25 2.25 0 0 0 4.5 19.5Z" />
                            </svg>
                        </span>
                        <div>
                            <h2 class="text-sm font-semibold text-slate-900">Jadwal, Biaya &amp; Rekening Pendaftaran</h2>
                            <p class="text-xs text-slate-500">Atur rentang pendaftaran, biaya, rekening pembayaran, dan kontak panitia untuk setiap jenjang.</p>
                        </div>
                    </div>

                    <div class="mt-5 grid gap-5 xl:grid-cols-2">
                        @foreach (['tk' => 'TK', 'sd' => 'SD', 'smp' => 'SMP', 'sma' => 'SMA'] as $key => $label)
                            @php
                                $levelOpen = $ppdbLevels[$label]['is_open'] ?? false;
                                $displayLabel = $key === 'tk' ? 'KB-TK' : $label;
                            @endphp
                            <div class="rounded-xl border border-slate-200 p-4">
                                <div class="flex items-center justify-between gap-3">
                                    <h3 class="text-sm font-semibold text-slate-900">Jenjang {{ $displayLabel }}</h3>
                                    <span class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-[10px] font-extrabold {{ $levelOpen ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-500' }}">
                                        <span class="size-1.5 rounded-full {{ $levelOpen ? 'bg-emerald-500' : 'bg-slate-400' }}"></span>
                                        {{ $levelOpen ? 'Dibuka' : 'Ditutup' }}
                                    </span>
                                </div>

                                <div class="mt-4 grid gap-3 sm:grid-cols-2">
                                    <div>
                                        <label for="ppdb_status_{{ $key }}" class="block text-xs font-medium text-slate-600">Status SPMB {{ $displayLabel }}</label>
                                        <select id="ppdb_status_{{ $key }}"
                                                name="ppdb_status_{{ $key }}"
                                                class="mt-1 block w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm shadow-sm outline-none transition focus:border-rose-400 focus:ring-2 focus:ring-rose-100">
                                            @foreach (['Buka', 'Tutup'] as $status)
                                                <option value="{{ $status }}" @selected(old("ppdb_status_{$key}", $settings["ppdb_status_{$key}"] ?? 'Tutup') === $status)>{{ $status }}</option>
                                            @endforeach
                                        </select>
                                        @error("ppdb_status_{$key}")
                                            <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="biaya_{{ $key }}" class="block text-xs font-medium text-slate-600">Biaya Pendaftaran {{ $displayLabel }}</label>
                                        <input id="biaya_{{ $key }}"
                                               name="biaya_{{ $key }}"
                                               type="number"
                                               min="0"
                                               step="1000"
                                               placeholder="0"
                                               value="{{ old("biaya_{$key}", $settings["biaya_{$key}"] ?? '') }}"
                                               class="mt-1 block w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm shadow-sm outline-none transition placeholder:text-slate-400 focus:border-rose-400 focus:ring-2 focus:ring-rose-100">
                                        @error("biaya_{$key}")
                                            <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="ppdb_start_{{ $key }}" class="block text-xs font-medium text-slate-600">Tanggal Mulai</label>
                                        <input id="ppdb_start_{{ $key }}"
                                               name="ppdb_start_{{ $key }}"
                                               type="date"
                                               value="{{ old("ppdb_start_{$key}", $settings["ppdb_start_{$key}"] ?? '') }}"
                                               class="mt-1 block w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm shadow-sm outline-none transition focus:border-rose-400 focus:ring-2 focus:ring-rose-100">
                                        @error("ppdb_start_{$key}")
                                            <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="ppdb_end_{{ $key }}" class="block text-xs font-medium text-slate-600">Tanggal Selesai</label>
                                        <input id="ppdb_end_{{ $key }}"
                                               name="ppdb_end_{{ $key }}"
                                               type="date"
                                               value="{{ old("ppdb_end_{$key}", $settings["ppdb_end_{$key}"] ?? '') }}"
                                               class="mt-1 block w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm shadow-sm outline-none transition focus:border-rose-400 focus:ring-2 focus:ring-rose-100">
                                        @error("ppdb_end_{$key}")
                                            <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="sm:col-span-2">
                                        <label for="bank_{{ $key }}" class="block text-xs font-medium text-slate-600">Nama Bank</label>
                                        <input id="bank_{{ $key }}"
                                               name="bank_{{ $key }}"
                                               type="text"
                                               placeholder="Contoh: Bank Syariah Indonesia (BSI)"
                                               value="{{ old("bank_{$key}", $settings["bank_{$key}"] ?? '') }}"
                                               class="mt-1 block w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm shadow-sm outline-none transition placeholder:text-slate-400 focus:border-rose-400 focus:ring-2 focus:ring-rose-100">
                                        @error("bank_{$key}")
                                            <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="rek_{{ $key }}" class="block text-xs font-medium text-slate-600">Nomor Rekening</label>
                                        <input id="rek_{{ $key }}"
                                               name="rek_{{ $key }}"
                                               type="text"
                                               placeholder="7001234567"
                                               value="{{ old("rek_{$key}", $settings["rek_{$key}"] ?? '') }}"
                                               class="mt-1 block w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm shadow-sm outline-none transition placeholder:text-slate-400 focus:border-rose-400 focus:ring-2 focus:ring-rose-100">
                                        @error("rek_{$key}")
                                            <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="rek_nama_{{ $key }}" class="block text-xs font-medium text-slate-600">Atas Nama</label>
                                        <input id="rek_nama_{{ $key }}"
                                               name="rek_nama_{{ $key }}"
                                               type="text"
                                               placeholder="Yayasan Ath-Thoifah Al-Manshuroh Balikpapan"
                                               value="{{ old("rek_nama_{$key}", $settings["rek_nama_{$key}"] ?? '') }}"
                                               class="mt-1 block w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm shadow-sm outline-none transition placeholder:text-slate-400 focus:border-rose-400 focus:ring-2 focus:ring-rose-100">
                                        @error("rek_nama_{$key}")
                                            <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="sm:col-span-2">
                                        <label for="wa_admin_{{ $key }}" class="block text-xs font-medium text-slate-600">Nomor WhatsApp Panitia {{ $displayLabel }}</label>
                                        <input id="wa_admin_{{ $key }}"
                                               name="wa_admin_{{ $key }}"
                                               type="text"
                                               placeholder="628115917772"
                                               value="{{ old("wa_admin_{$key}", $settings["wa_admin_{$key}"] ?? '') }}"
                                               class="mt-1 block w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm shadow-sm outline-none transition placeholder:text-slate-400 focus:border-rose-400 focus:ring-2 focus:ring-rose-100">
                                        <p class="mt-1 text-[11px] text-slate-400">Dipakai tombol WhatsApp panitia dan tautan konfirmasi pendaftaran.</p>
                                        @error("wa_admin_{$key}")
                                            <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <p class="mt-3 text-[11px] text-slate-400">
                        Status jenjang otomatis dianggap ditutup bila di luar rentang tanggal, status jenjang "Tutup", atau status SPMB global "Tutup".
                    </p>
                </section>
            @else
                <section class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm sm:p-6">
                    <div class="flex items-start gap-2.5 sm:items-center sm:gap-2">
                        <span class="grid size-8 shrink-0 place-items-center rounded-lg bg-emerald-50 text-emerald-600">
                            <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 0 0 2.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 0 1-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 0 0-1.091-.852H4.5A2.25 2.25 0 0 0 2.25 4.5v2.25Z" />
                            </svg>
                        </span>
                        <div>
                            <h2 class="text-sm font-semibold text-slate-900">Kontak Panitia SPMB</h2>
                            <p class="text-xs text-slate-500">Nomor WhatsApp panitia untuk jenjang yang Anda kelola.</p>
                        </div>
                    </div>

                    @foreach ($quotaLevels as $key => $label)
                        <div class="mt-5 max-w-xl">
                            <label for="wa_admin_{{ $key }}" class="block text-sm font-medium text-slate-700">Nomor WhatsApp Panitia {{ $label }}</label>
                            <input id="wa_admin_{{ $key }}"
                                   name="wa_admin_{{ $key }}"
                                   type="text"
                                   placeholder="628115917772"
                                   value="{{ old("wa_admin_{$key}", $settings["wa_admin_{$key}"] ?? '') }}"
                                   class="mt-1.5 block w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm shadow-sm outline-none transition placeholder:text-slate-400 focus:border-rose-400 focus:ring-4 focus:ring-rose-100">
                            @error("wa_admin_{$key}")
                                <p class="mt-1.5 text-xs text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>
                    @endforeach
                </section>
            @endif

            <div class="sticky bottom-4 z-10 flex flex-col gap-3 rounded-2xl border border-slate-200 bg-white/95 p-4 shadow-lg backdrop-blur sm:flex-row sm:items-center sm:justify-between xl:bg-white xl:shadow-sm">
                <p class="text-[11px] leading-relaxed text-slate-400">
                    Perubahan langsung diterapkan ke halaman SPMB setelah disimpan.
                </p>
                <button type="submit"
                        class="inline-flex items-center justify-center gap-2 rounded-xl bg-rose-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-rose-700">
                    <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                    </svg>
                    Simpan Pengaturan SPMB
                </button>
            </div>
        </form>

        @if ($canManageReferralCodes)
            <section class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm sm:p-6">
                <div class="flex flex-wrap items-start justify-between gap-3 sm:items-center">
                    <div class="flex items-start gap-2.5 sm:items-center sm:gap-2">
                        <span class="grid size-8 shrink-0 place-items-center rounded-lg bg-gold-50 text-gold-700">
                            <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 6v.75m0 3v.75m0 3v.75m0 3V18m-9-5.25h5.25M7.5 15h3M3.375 5.25c-.621 0-1.125.504-1.125 1.125v3.026a2.999 2.999 0 0 1 0 5.198v3.026c0 .621.504 1.125 1.125 1.125h17.25c.621 0 1.125-.504 1.125-1.125v-3.026a2.999 2.999 0 0 1 0-5.198V6.375c0-.621-.504-1.125-1.125-1.125H3.375Z" />
                            </svg>
                        </span>
                        <div>
                            <h2 class="text-sm font-semibold text-slate-900">Kelola Kode Referral &amp; Promo KB-TK</h2>
                            <p class="text-xs text-slate-500">Pantau 10 kode Event Muslim Vibes dan kelola kode referral kakak alumni yang dapat dipakai berulang.</p>
                        </div>
                    </div>
                    <a href="#form-kode-referral-baru"
                       class="inline-flex items-center gap-1.5 rounded-xl bg-rose-600 px-3.5 py-2 text-xs font-bold text-white shadow-sm transition hover:bg-rose-700">
                        <svg class="size-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                        Tambah Kode Referral Baru
                    </a>
                </div>

                @if (session('error'))
                    <div class="mt-4 rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-xs font-semibold text-rose-800">
                        {{ session('error') }}
                    </div>
                @endif

                <div class="mt-5">
                    <div class="flex flex-wrap items-center justify-between gap-2">
                        <h3 class="text-xs font-extrabold uppercase tracking-wider text-slate-500">
                            Kode Event Muslim Vibes — Gratis Biaya Pendaftaran Rp 250.000
                        </h3>
                        <div class="flex flex-wrap items-center gap-2">
                            <span class="rounded-full bg-slate-100 px-2.5 py-1 text-[10px] font-extrabold text-slate-600">
                                {{ $eventReferralCodes->count() }} kode
                            </span>
                            <form method="POST"
                                  action="{{ route('admin.referrals.reset-event') }}"
                                  onsubmit='return confirm(@js("Reset semua kode Event Muslim Vibes agar kembali tersedia (0 terpakai)?")) && confirm(@js("Konfirmasi terakhir: seluruh kode Event Muslim Vibes akan direset. Lanjutkan?"))'>
                                @csrf
                                <button type="submit"
                                        class="inline-flex items-center gap-1.5 rounded-lg bg-amber-50 px-2.5 py-1.5 text-[11px] font-bold text-amber-700 ring-1 ring-inset ring-amber-600/20 transition hover:bg-amber-100">
                                    <svg class="size-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" />
                                    </svg>
                                    Reset Semua Kode Event
                                </button>
                            </form>
                        </div>
                    </div>

                    <div class="mt-3 overflow-x-auto rounded-xl border border-slate-200">
                        <table class="min-w-full divide-y divide-slate-200 text-sm">
                            <thead class="bg-slate-50 text-left text-[10px] font-semibold uppercase tracking-wider text-slate-500">
                                <tr>
                                    <th class="px-4 py-2.5">Kode</th>
                                    <th class="px-4 py-2.5">Status</th>
                                    <th class="px-4 py-2.5">Dipakai Oleh</th>
                                    <th class="px-4 py-2.5">Catatan Internal</th>
                                    <th class="px-4 py-2.5 text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @forelse ($eventReferralCodes as $eventCode)
                                    @php
                                        $usedBy = $eventCode->usedByRegistration;
                                    @endphp
                                    <tr class="align-top">
                                        <td class="px-4 py-3 font-mono text-xs font-extrabold text-slate-800">{{ $eventCode->code }}</td>
                                        <td class="px-4 py-3">
                                            @if (! $eventCode->is_active)
                                                <span class="rounded-md bg-slate-100 px-2 py-0.5 text-[10px] font-extrabold uppercase tracking-wider text-slate-500">Nonaktif</span>
                                            @elseif ($eventCode->isAvailable())
                                                <span class="rounded-md bg-emerald-50 px-2 py-0.5 text-[10px] font-extrabold uppercase tracking-wider text-emerald-700">Tersedia</span>
                                            @elseif ($usedBy)
                                                <span class="rounded-md bg-rose-100 px-2 py-0.5 text-[10px] font-extrabold uppercase tracking-wider text-rose-700">Terpakai (Habis)</span>
                                                <span class="mt-1 block text-[10px] font-semibold text-rose-700">Sudah Dipakai oleh: {{ $usedBy->full_name }}</span>
                                            @else
                                                <span class="rounded-md bg-rose-100 px-2 py-0.5 text-[10px] font-extrabold uppercase tracking-wider text-rose-700">Terpakai (Habis)</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 text-xs text-slate-600">
                                            @if ($usedBy)
                                                <span class="font-semibold text-slate-800">{{ $usedBy->full_name }}</span>
                                                <span class="block font-mono text-[10px] text-slate-400">{{ $usedBy->registration_number }}</span>
                                            @else
                                                <span class="text-slate-400">Belum dipakai</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 text-[11px] leading-5 text-slate-500">{{ $eventCode->notes ?: '-' }}</td>
                                        <td class="px-4 py-3">
                                            <div class="flex flex-wrap justify-end gap-1.5">
                                                <button type="button"
                                                        onclick="openReferralEditModal({{ $eventCode->id }})"
                                                        class="rounded-lg bg-slate-100 px-2.5 py-1.5 text-[11px] font-semibold text-slate-700 transition hover:bg-slate-200">
                                                    Edit
                                                </button>
                                                @if ($eventCode->used_count > 0)
                                                    <form method="POST" action="{{ route('admin.referrals.reset', $eventCode) }}"
                                                          onsubmit='return confirm(@js("Yakin ingin mereset status pemakaian kode ini agar bisa digunakan kembali?"))'>
                                                        @csrf
                                                        <button type="submit"
                                                                class="rounded-lg bg-amber-50 px-2.5 py-1.5 text-[11px] font-semibold text-amber-700 ring-1 ring-inset ring-amber-600/20 transition hover:bg-amber-100">
                                                            Reset
                                                        </button>
                                                    </form>
                                                @endif
                                                <form method="POST"
                                                      action="{{ route('admin.referrals.destroy', $eventCode) }}"
                                                      onsubmit="return confirm('Apakah Anda yakin ingin menghapus kode referral ini?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                            class="inline-flex items-center gap-1 rounded-lg bg-rose-50 px-2.5 py-1.5 text-[11px] font-semibold text-rose-700 ring-1 ring-inset ring-rose-600/20 transition hover:bg-rose-100">
                                                        <svg class="size-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                                        </svg>
                                                        Hapus
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-4 py-8 text-center text-xs text-slate-400">
                                            Kode event belum tersedia. Jalankan seeder kode referral.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="mt-8 grid gap-6 xl:grid-cols-[1.6fr_1fr]">
                    <div>
                        <h3 class="text-xs font-extrabold uppercase tracking-wider text-slate-500">
                            Kode Referral Kakak Alumni — Potongan Rp 500.000
                        </h3>

                        <div class="mt-3 overflow-x-auto rounded-xl border border-slate-200">
                            <table class="min-w-full divide-y divide-slate-200 text-sm">
                                <thead class="bg-slate-50 text-left text-[10px] font-semibold uppercase tracking-wider text-slate-500">
                                    <tr>
                                        <th class="px-4 py-2.5">Kode</th>
                                        <th class="px-4 py-2.5">Pemakaian</th>
                                        <th class="px-4 py-2.5">Status</th>
                                        <th class="px-4 py-2.5 text-right">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    @forelse ($siblingReferralCodes as $siblingCode)
                                        <tr class="align-top">
                                            <td class="px-4 py-3">
                                                <p class="font-mono text-xs font-extrabold text-slate-800">{{ $siblingCode->code }}</p>
                                                @if (filled($siblingCode->notes))
                                                    <p class="mt-1 max-w-[16rem] text-[11px] leading-5 text-slate-400">{{ $siblingCode->notes }}</p>
                                                @endif
                                            </td>
                                            <td class="px-4 py-3 text-xs text-slate-600">
                                                <span class="font-bold text-slate-800">{{ $siblingCode->used_count }}</span> / {{ $siblingCode->max_uses }}
                                                <span class="block text-[10px] text-slate-400">Sisa {{ $siblingCode->remainingUses() }}</span>
                                            </td>
                                            <td class="px-4 py-3">
                                                @if ($siblingCode->is_active && ! $siblingCode->isExhausted())
                                                    <span class="rounded-md bg-emerald-50 px-2 py-0.5 text-[10px] font-extrabold uppercase tracking-wider text-emerald-700">Aktif</span>
                                                @elseif ($siblingCode->is_active)
                                                    <span class="rounded-md bg-amber-50 px-2 py-0.5 text-[10px] font-extrabold uppercase tracking-wider text-amber-700">Habis</span>
                                                @else
                                                    <span class="rounded-md bg-slate-100 px-2 py-0.5 text-[10px] font-extrabold uppercase tracking-wider text-slate-500">Nonaktif</span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-3">
                                                <div class="flex flex-wrap justify-end gap-1.5">
                                                    <button type="button"
                                                            onclick="openReferralEditModal({{ $siblingCode->id }})"
                                                            class="rounded-lg bg-slate-100 px-2.5 py-1.5 text-[11px] font-semibold text-slate-700 transition hover:bg-slate-200">
                                                        Edit
                                                    </button>
                                                    @if ($siblingCode->used_count > 0)
                                                        <form method="POST" action="{{ route('admin.referrals.reset', $siblingCode) }}"
                                                              onsubmit='return confirm(@js("Yakin ingin mereset status pemakaian kode ini agar bisa digunakan kembali?"))'>
                                                            @csrf
                                                            <button type="submit"
                                                                    class="rounded-lg bg-amber-50 px-2.5 py-1.5 text-[11px] font-semibold text-amber-700 ring-1 ring-inset ring-amber-600/20 transition hover:bg-amber-100">
                                                                Reset
                                                            </button>
                                                        </form>
                                                    @endif
                                                    <form method="POST" action="{{ route('admin.referral-codes.toggle', $siblingCode) }}">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit"
                                                                class="rounded-lg bg-slate-100 px-2.5 py-1.5 text-[11px] font-semibold text-slate-700 transition hover:bg-slate-200">
                                                            {{ $siblingCode->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                                        </button>
                                                    </form>
                                                    <form method="POST"
                                                          action="{{ route('admin.referrals.destroy', $siblingCode) }}"
                                                          onsubmit="return confirm('Apakah Anda yakin ingin menghapus kode referral ini?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                                class="inline-flex items-center gap-1 rounded-lg bg-rose-50 px-2.5 py-1.5 text-[11px] font-semibold text-rose-700 ring-1 ring-inset ring-rose-600/20 transition hover:bg-rose-100">
                                                            <svg class="size-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                                                                <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                                            </svg>
                                                            Hapus
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="px-4 py-8 text-center text-xs text-slate-400">
                                                Belum ada kode referral kakak alumni.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div id="form-kode-referral-baru" class="scroll-mt-24 rounded-xl border border-slate-200 bg-slate-50/60 p-4">
                        <h3 class="text-sm font-semibold text-slate-900">Tambah Kode Referral Baru</h3>
                        <p class="mt-1 text-[11px] leading-5 text-slate-500">
                            Buat kode event susulan (gratis biaya pendaftaran) atau kode kakak alumni di luar daftar default. Kode otomatis disimpan dalam huruf kapital.
                        </p>

                        @if ($errors->referralCreate->any())
                            <div class="mt-3 rounded-lg border border-rose-200 bg-rose-50 px-3 py-2 text-[11px] font-semibold text-rose-700">
                                <ul class="list-disc space-y-1 pl-4">
                                    @foreach ($errors->referralCreate->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('admin.referral-codes.store') }}" class="mt-4 space-y-3">
                            @csrf

                            <div>
                                <label for="new-referral-code" class="block text-xs font-semibold text-slate-700">Kode Referral</label>
                                <div class="mt-1 flex gap-2">
                                    <input id="new-referral-code"
                                           name="code"
                                           type="text"
                                           maxlength="30"
                                           value="{{ $errors->referralCreate->any() ? old('code') : '' }}"
                                           placeholder="ALUMNI26-01"
                                           autocomplete="off"
                                           class="block w-full rounded-lg border border-slate-200 bg-white px-3 py-2 font-mono text-xs font-bold uppercase tracking-wider shadow-sm outline-none transition placeholder:font-normal placeholder:tracking-normal placeholder:text-slate-400 focus:border-rose-400 focus:ring-2 focus:ring-rose-100">
                                    <button type="button"
                                            id="generate-referral-code"
                                            class="shrink-0 rounded-lg border border-slate-200 bg-white px-3 py-2 text-[11px] font-bold text-slate-600 transition hover:border-rose-300 hover:text-rose-700 disabled:opacity-60">
                                        Generate
                                    </button>
                                </div>
                                @if ($errors->referralCreate->has('code'))
                                    <p class="mt-1 text-xs text-rose-600">{{ $errors->referralCreate->first('code') }}</p>
                                @endif
                            </div>

                            <div>
                                <label for="new-referral-type" class="block text-xs font-semibold text-slate-700">Jenis Kode</label>
                                <select id="new-referral-type"
                                        name="type"
                                        class="mt-1 block w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs shadow-sm outline-none transition focus:border-rose-400 focus:ring-2 focus:ring-rose-100">
                                    @foreach (\App\Models\ReferralCode::TYPES as $typeKey => $typeMeta)
                                        <option value="{{ $typeKey }}" @selected(($errors->referralCreate->any() ? old('type') : \App\Models\ReferralCode::TYPE_SIBLING_ALUMNI) === $typeKey)>
                                            {{ $typeMeta['label'] }}
                                        </option>
                                    @endforeach
                                </select>
                                @if ($errors->referralCreate->has('type'))
                                    <p class="mt-1 text-xs text-rose-600">{{ $errors->referralCreate->first('type') }}</p>
                                @endif
                            </div>

                            <div>
                                <label for="new-referral-discount" class="block text-xs font-semibold text-slate-700">Nominal Potongan (Rp)</label>
                                <input id="new-referral-discount"
                                       name="discount_amount"
                                       type="number"
                                       min="0"
                                       max="100000000"
                                       value="{{ $errors->referralCreate->any() ? old('discount_amount') : 500000 }}"
                                       class="mt-1 block w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs shadow-sm outline-none transition focus:border-rose-400 focus:ring-2 focus:ring-rose-100">
                                @if ($errors->referralCreate->has('discount_amount'))
                                    <p class="mt-1 text-xs text-rose-600">{{ $errors->referralCreate->first('discount_amount') }}</p>
                                @endif
                            </div>

                            <div>
                                <label for="new-referral-max-uses" class="block text-xs font-semibold text-slate-700">Batas Pemakaian</label>
                                <input id="new-referral-max-uses"
                                       name="max_uses"
                                       type="number"
                                       min="1"
                                       max="1000"
                                       value="{{ $errors->referralCreate->any() ? old('max_uses') : 10 }}"
                                       class="mt-1 block w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs shadow-sm outline-none transition focus:border-rose-400 focus:ring-2 focus:ring-rose-100">
                                @if ($errors->referralCreate->has('max_uses'))
                                    <p class="mt-1 text-xs text-rose-600">{{ $errors->referralCreate->first('max_uses') }}</p>
                                @endif
                            </div>

                            <div>
                                <label for="new-referral-notes" class="block text-xs font-semibold text-slate-700">
                                    Catatan Internal <span class="font-normal text-slate-400">(opsional)</span>
                                </label>
                                <input id="new-referral-notes"
                                       name="notes"
                                       type="text"
                                       maxlength="255"
                                       value="{{ $errors->referralCreate->any() ? old('notes') : '' }}"
                                       placeholder="Contoh: Keluarga Alumni Angkatan 2020"
                                       class="mt-1 block w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs shadow-sm outline-none transition placeholder:text-slate-400 focus:border-rose-400 focus:ring-2 focus:ring-rose-100">
                                @if ($errors->referralCreate->has('notes'))
                                    <p class="mt-1 text-xs text-rose-600">{{ $errors->referralCreate->first('notes') }}</p>
                                @endif
                            </div>

                            <div>
                                <label class="flex cursor-pointer items-center gap-2 rounded-lg border border-slate-200 bg-white px-3 py-2 shadow-sm">
                                    <input id="new-referral-active"
                                           name="is_active"
                                           type="checkbox"
                                           value="1"
                                           @checked($errors->referralCreate->any() ? old('is_active') : true)
                                           class="size-4 rounded border-slate-300 text-rose-600 focus:ring-rose-500">
                                    <span class="text-xs font-semibold text-slate-700">Aktif (langsung bisa dipakai)</span>
                                </label>
                            </div>

                            <button type="submit"
                                    class="inline-flex w-full items-center justify-center gap-2 rounded-xl bg-rose-600 px-4 py-2.5 text-xs font-bold text-white shadow-sm transition hover:bg-rose-700">
                                <svg class="size-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                </svg>
                                Tambah Kode Referral
                            </button>
                        </form>
                    </div>
                </div>
            </section>

            @php
                $referralEditPayload = $referralCodes->mapWithKeys(fn (\App\Models\ReferralCode $code): array => [
                    $code->id => [
                        'action' => route('admin.referrals.update', $code),
                        'code' => $code->code,
                        'type' => $code->type,
                        'discount_amount' => $code->discount_amount,
                        'max_uses' => $code->max_uses,
                        'notes' => $code->notes,
                        'is_active' => $code->is_active,
                        'used_count' => $code->used_count,
                    ],
                ]);
                $referralTypeDefaults = array_map(
                    fn (array $type): int => $type['discount_amount'],
                    \App\Models\ReferralCode::TYPES,
                );
                $oldReferralEdit = $errors->referralEdit->any() ? [
                    'id' => old('_referral_id'),
                    'code' => old('code'),
                    'type' => old('type'),
                    'discount_amount' => old('discount_amount'),
                    'max_uses' => old('max_uses'),
                    'notes' => old('notes'),
                    'is_active' => (bool) old('is_active'),
                ] : null;
            @endphp

            <div id="referral-edit-modal" class="fixed inset-0 z-50 hidden" role="dialog" aria-modal="true" aria-labelledby="referral-edit-title">
                <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" data-close-referral-modal></div>
                <div class="relative flex min-h-full items-center justify-center p-4">
                    <div class="w-full max-w-lg overflow-hidden rounded-2xl bg-white shadow-xl">
                        <div class="flex items-start justify-between gap-3 border-b border-slate-100 px-5 py-4">
                            <div>
                                <h3 id="referral-edit-title" class="text-sm font-bold text-slate-900">Edit Kode Referral</h3>
                                <p class="mt-0.5 text-[11px] text-slate-500">Perubahan langsung berlaku pada formulir pendaftaran KB-TK.</p>
                            </div>
                            <button type="button" data-close-referral-modal
                                    class="rounded-lg p-1.5 text-lg leading-none text-slate-400 transition hover:bg-slate-100 hover:text-slate-700"
                                    aria-label="Tutup">&times;</button>
                        </div>

                        <form id="referral-edit-form" method="POST" action="#" class="space-y-3 px-5 py-4">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="_referral_id" id="referral-edit-id" value="">

                            @if ($errors->referralEdit->any())
                                <div class="rounded-xl border border-rose-200 bg-rose-50 px-3 py-2 text-[11px] font-semibold text-rose-700">
                                    <ul class="list-disc space-y-1 pl-4">
                                        @foreach ($errors->referralEdit->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <div>
                                <label for="referral-edit-code" class="block text-xs font-semibold text-slate-700">Kode Referral</label>
                                <input id="referral-edit-code"
                                       name="code"
                                       type="text"
                                       maxlength="30"
                                       required
                                       autocomplete="off"
                                       class="mt-1 block w-full rounded-lg border border-slate-200 bg-white px-3 py-2 font-mono text-xs font-bold uppercase tracking-wider text-slate-800 shadow-sm outline-none transition focus:border-rose-400 focus:ring-2 focus:ring-rose-100">
                            </div>

                            <div class="grid gap-3 sm:grid-cols-2">
                                <div>
                                    <label for="referral-edit-type" class="block text-xs font-semibold text-slate-700">Jenis Kode</label>
                                    <select id="referral-edit-type"
                                            name="type"
                                            required
                                            class="mt-1 block w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs shadow-sm outline-none transition focus:border-rose-400 focus:ring-2 focus:ring-rose-100">
                                        @foreach (\App\Models\ReferralCode::TYPES as $typeKey => $typeMeta)
                                            <option value="{{ $typeKey }}">{{ $typeMeta['label'] }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label for="referral-edit-discount" class="block text-xs font-semibold text-slate-700">Nominal Potongan (Rp)</label>
                                    <input id="referral-edit-discount"
                                           name="discount_amount"
                                           type="number"
                                           min="0"
                                           max="100000000"
                                           required
                                           class="mt-1 block w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs shadow-sm outline-none transition focus:border-rose-400 focus:ring-2 focus:ring-rose-100">
                                </div>
                            </div>

                            <div class="grid gap-3 sm:grid-cols-2">
                                <div>
                                    <label for="referral-edit-max-uses" class="block text-xs font-semibold text-slate-700">Batas Pemakaian</label>
                                    <input id="referral-edit-max-uses"
                                           name="max_uses"
                                           type="number"
                                           min="1"
                                           max="1000"
                                           required
                                           class="mt-1 block w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs shadow-sm outline-none transition focus:border-rose-400 focus:ring-2 focus:ring-rose-100">
                                    <p id="referral-edit-used-info" class="mt-1 text-[10px] leading-4 text-slate-400"></p>
                                </div>

                                <div>
                                    <span class="block text-xs font-semibold text-slate-700">Status Kode</span>
                                    <label class="mt-1 flex cursor-pointer items-center gap-2 rounded-lg border border-slate-200 bg-white px-3 py-2 shadow-sm">
                                        <input id="referral-edit-active"
                                               name="is_active"
                                               type="checkbox"
                                               value="1"
                                               class="size-4 rounded border-slate-300 text-rose-600 focus:ring-rose-500">
                                        <span class="text-xs font-semibold text-slate-700">Aktif (bisa dipakai)</span>
                                    </label>
                                </div>
                            </div>

                            <div>
                                <label for="referral-edit-notes" class="block text-xs font-semibold text-slate-700">
                                    Catatan Internal <span class="font-normal text-slate-400">(opsional)</span>
                                </label>
                                <input id="referral-edit-notes"
                                       name="notes"
                                       type="text"
                                       maxlength="255"
                                       placeholder="Contoh: Keluarga Alumni Angkatan 2020"
                                       class="mt-1 block w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs shadow-sm outline-none transition placeholder:text-slate-400 focus:border-rose-400 focus:ring-2 focus:ring-rose-100">
                            </div>

                            <div class="flex items-center justify-end gap-2 border-t border-slate-100 pt-3">
                                <button type="button" data-close-referral-modal
                                        class="rounded-xl bg-slate-100 px-4 py-2 text-xs font-bold text-slate-700 transition hover:bg-slate-200">
                                    Batal
                                </button>
                                <button type="submit"
                                        class="rounded-xl bg-rose-600 px-4 py-2 text-xs font-bold text-white shadow-sm transition hover:bg-rose-700">
                                    Simpan Perubahan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <script>
                const referralEditPayload = @json($referralEditPayload);
                const referralTypeDefaults = @json($referralTypeDefaults);
                const oldReferralEdit = @json($oldReferralEdit);

                const referralEditModal = document.getElementById('referral-edit-modal');
                const referralEditForm = document.getElementById('referral-edit-form');
                const referralEditFields = {
                    id: document.getElementById('referral-edit-id'),
                    code: document.getElementById('referral-edit-code'),
                    type: document.getElementById('referral-edit-type'),
                    discount: document.getElementById('referral-edit-discount'),
                    maxUses: document.getElementById('referral-edit-max-uses'),
                    notes: document.getElementById('referral-edit-notes'),
                    active: document.getElementById('referral-edit-active'),
                    usedInfo: document.getElementById('referral-edit-used-info'),
                };

                function closeReferralEditModal() {
                    if (!referralEditModal) return;

                    referralEditModal.classList.add('hidden');
                    document.body.classList.remove('overflow-hidden');
                }

                function openReferralEditModal(id, overrides = null) {
                    const data = referralEditPayload?.[id];

                    if (!referralEditModal || !referralEditForm || !data) return;

                    referralEditForm.action = data.action;
                    referralEditFields.id.value = id;
                    referralEditFields.code.value = overrides?.code ?? data.code;
                    referralEditFields.type.value = overrides?.type ?? data.type;
                    referralEditFields.discount.value = overrides?.discount_amount ?? data.discount_amount;
                    referralEditFields.maxUses.value = overrides?.max_uses ?? data.max_uses;
                    referralEditFields.notes.value = overrides ? (overrides.notes ?? '') : (data.notes ?? '');
                    referralEditFields.active.checked = overrides ? Boolean(overrides.is_active) : Boolean(data.is_active);
                    referralEditFields.maxUses.min = Math.max(1, Number(data.used_count) || 1);
                    referralEditFields.usedInfo.textContent = Number(data.used_count) > 0
                        ? `Sudah terpakai ${data.used_count} kali. Reset dulu bila ingin batas pemakaian lebih kecil.`
                        : 'Belum pernah terpakai.';

                    referralEditModal.classList.remove('hidden');
                    document.body.classList.add('overflow-hidden');
                    setTimeout(() => referralEditFields.code.focus(), 50);
                }

                window.openReferralEditModal = openReferralEditModal;
                window.closeReferralEditModal = closeReferralEditModal;

                referralEditModal?.querySelectorAll('[data-close-referral-modal]').forEach((element) => {
                    element.addEventListener('click', closeReferralEditModal);
                });

                document.addEventListener('keydown', (event) => {
                    if (event.key === 'Escape') {
                        closeReferralEditModal();
                    }
                });

                referralEditFields.type?.addEventListener('change', function () {
                    const defaultAmount = referralTypeDefaults?.[this.value];

                    if (defaultAmount !== undefined) {
                        referralEditFields.discount.value = defaultAmount;
                    }
                });

                const newReferralType = document.getElementById('new-referral-type');
                const newReferralDiscount = document.getElementById('new-referral-discount');

                newReferralType?.addEventListener('change', function () {
                    const defaultAmount = referralTypeDefaults?.[this.value];

                    if (defaultAmount !== undefined && newReferralDiscount) {
                        newReferralDiscount.value = defaultAmount;
                    }
                });

                if (oldReferralEdit?.id) {
                    const reopenEditModal = () => openReferralEditModal(oldReferralEdit.id, oldReferralEdit);

                    if (document.readyState === 'loading') {
                        document.addEventListener('DOMContentLoaded', reopenEditModal);
                    } else {
                        reopenEditModal();
                    }
                }
            </script>

            <script>
                document.getElementById('generate-referral-code')?.addEventListener('click', async function () {
                    const button = this;
                    const input = document.getElementById('new-referral-code');
                    if (!input) return;

                    button.disabled = true;
                    button.textContent = 'Membuat...';

                    try {
                        const response = await fetch(@json(route('admin.referral-codes.generate')), {
                            headers: { 'Accept': 'application/json' },
                        });

                        if (!response.ok) throw new Error('Gagal membuat kode.');

                        const data = await response.json();
                        input.value = data.code || '';
                    } catch (error) {
                        input.value = 'ALUMNI26-' + Math.random().toString(36).slice(2, 6).toUpperCase();
                    } finally {
                        button.disabled = false;
                        button.textContent = 'Generate';
                        input.focus();
                        input.select();
                    }
                });
            </script>
        @endif
    </div>
@endsection
