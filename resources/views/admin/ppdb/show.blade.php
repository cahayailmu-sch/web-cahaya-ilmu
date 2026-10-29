@extends('admin.layouts.app')

@section('title', 'Detail Pendaftar SPMB — ' . $registration->full_name)
@section('subtitle', 'No. Pendaftaran ' . $registration->registration_number)

@section('content')
    <div class="space-y-6">
        <!-- Header & Action Bar -->
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.ppdb.index', ['level' => $registration->level]) }}"
                   class="grid size-9 place-items-center rounded-xl border border-slate-200 bg-white text-slate-600 hover:bg-slate-50 transition shadow-sm">
                    <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" /></svg>
                </a>
                <div>
                    <div class="flex flex-wrap items-center gap-2">
                        <h1 class="text-2xl font-bold tracking-tight text-slate-900">{{ $registration->full_name }}</h1>
                        <span class="rounded-md bg-slate-100 px-2 py-0.5 text-xs font-semibold text-slate-700">{{ $registration->levelLabel() }}{{ filled($registration->sub_level) ? ' · '.$registration->sub_level : '' }}</span>
                        <x-admin.status-badge :status="$registration->status" />
                    </div>
                    <p class="mt-0.5 text-xs text-slate-500 font-mono">
                        {{ $registration->registration_number }} &middot; Terdaftar {{ $registration->created_at->format('d M Y, H:i') }} WITA
                    </p>
                </div>
            </div>

            <div class="flex flex-wrap items-center gap-2">
                @if (in_array($registration->status, ['verified', 'completed', 'accepted', 'Diverifikasi', 'Diterima']))
                    @php
                        $stageTwoUrl = $registration->fullFormUrl();
                        $waStageTwoMsg = "Assalamu'alaikum Ayah/Bunda dari {$registration->full_name}. Pembayaran pendaftaran SPMB {$registration->level} telah terverifikasi. Silakan lengkapi Formulir Lengkap & Surat Kesanggupan Biaya melalui tautan berikut:\n{$stageTwoUrl}";
                        $waStageTwoUrl = $registration->whatsappLink() . '?text=' . urlencode($waStageTwoMsg);
                    @endphp
                    <button type="button"
                            onclick="navigator.clipboard.writeText('{{ $stageTwoUrl }}'); alert('Link Formulir Lengkap berhasil disalin!');"
                            class="inline-flex items-center gap-1.5 rounded-xl bg-sky-50 px-3.5 py-2 text-xs font-bold text-sky-700 ring-1 ring-inset ring-sky-600/20 hover:bg-sky-100 transition shadow-sm">
                        <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.666 3.888A2.25 2.25 0 0 0 13.5 2.25h-3c-1.03 0-1.9.693-2.166 1.638m7.332 0c.055.194.084.4.084.612v0a.75.75 0 0 1-.75.75H9a.75.75 0 0 1-.75-.75v0c0-.212.03-.418.084-.612m7.332 0c.646.049 1.288.11 1.927.184 1.1.128 1.907 1.077 1.907 2.185V19.5a2.25 2.25 0 0 1-2.25 2.25H6.75A2.25 2.25 0 0 1 4.5 19.5V6.257c0-1.108.806-2.057 1.907-2.185a48.208 48.208 0 0 1 1.927-.184" /></svg>
                        Salin Link Tahap 2
                    </button>
                    <a href="{{ $waStageTwoUrl }}" target="_blank" rel="noopener"
                       class="inline-flex items-center gap-1.5 rounded-xl bg-emerald-600 px-3.5 py-2 text-xs font-bold text-white hover:bg-emerald-700 transition shadow-sm">
                        <svg class="size-4" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 0 1-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 0 1-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 0 1 2.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0 0 12.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 0 0 5.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 0 0-3.48-8.413Z"/></svg>
                        Kirim WA Link Tahap 2
                    </a>
                @endif

                @if ($registration->hasCompletedFullForm())
                    <a href="{{ route('admin.ppdb.print', $registration) }}" target="_blank"
                       class="inline-flex items-center gap-1.5 rounded-xl bg-indigo-600 px-3.5 py-2 text-xs font-bold text-white hover:bg-indigo-700 transition shadow-sm">
                        <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24-1.076-.64-2.122-1.18-3.111a12.09 12.09 0 0 0-3.54-3.793m0 0a12.094 12.094 0 0 1 5.25-.972 12.094 12.094 0 0 1 5.25.972m-10.5 0v10.5c0 1.242 1.008 2.25 2.25 2.25h10.5a2.25 2.25 0 0 0 2.25-2.25V6.924" /></svg>
                        Cetak A4
                    </a>
                    <a href="{{ route('admin.ppdb.pdf', $registration) }}"
                       class="inline-flex items-center gap-1.5 rounded-xl bg-rose-600 px-3.5 py-2 text-xs font-bold text-white hover:bg-rose-700 transition shadow-sm">
                        <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" /></svg>
                        Download PDF
                    </a>
                @endif
            </div>
        </div>

        <!-- Status Management Card -->
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <h2 class="text-sm font-bold text-slate-900">Ubah Status Pendaftaran</h2>
                    <p class="text-xs text-slate-500 mt-0.5">
                        Mengubah status menjadi <strong>Terverifikasi</strong> akan membuka akses formulir tahap 2 bagi pendaftar ini.
                    </p>
                </div>

                <form method="POST" action="{{ route('admin.ppdb.status', $registration) }}" class="flex items-center gap-2">
                    @csrf
                    @method('PATCH')
                    <select name="status" class="rounded-xl border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-slate-700 shadow-sm outline-none focus:border-rose-400 focus:ring-2 focus:ring-rose-100">
                        @foreach (\App\Models\PpdbRegistration::statusLabels() as $key => $lbl)
                            <option value="{{ $key }}" @selected($registration->status === $key)>{{ $lbl }}</option>
                        @endforeach
                    </select>
                    <button type="submit" class="rounded-xl bg-rose-600 px-4 py-2 text-xs font-bold text-white hover:bg-rose-700 transition shadow-sm">
                        Update Status
                    </button>
                </form>
            </div>
        </div>

        <!-- Ringkasan Tahap 1 -->
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm space-y-6">
            <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                <div class="flex items-center gap-2.5">
                    <span class="w-7 h-7 rounded-lg bg-brand-50 text-brand-700 font-bold text-xs flex items-center justify-center">1</span>
                    <h2 class="text-base font-bold text-slate-900">Data Pendaftaran Awal (Tahap 1)</h2>
                </div>
                <span class="text-xs text-slate-400 font-mono">Kode Akses: {{ $registration->registration_code }}</span>
            </div>

            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-5 text-xs">
                <div>
                    <span class="text-slate-400 font-medium">Nama Lengkap</span>
                    <p class="mt-1 font-bold text-slate-900 text-sm">{{ $registration->full_name }}</p>
                </div>
                <div>
                    <span class="text-slate-400 font-medium">Jenjang &amp; Gender</span>
                    <p class="mt-1 font-bold text-slate-900 text-sm">
                        {{ $registration->schoolName() }}@if (filled($registration->subLevelLabel())) &middot; {{ $registration->subLevelLabel() }}@endif &middot; {{ $registration->genderLabel() }}
                    </p>
                </div>
                <div>
                    <span class="text-slate-400 font-medium">Tempat, Tanggal Lahir</span>
                    <p class="mt-1 font-bold text-slate-900 text-sm">
                        {{ $registration->birth_place ?: '-' }}, {{ $registration->birth_date?->format('d F Y') }} ({{ $registration->age() }} tahun)
                    </p>
                </div>
                <div>
                    <span class="text-slate-400 font-medium">Asal Sekolah</span>
                    <p class="mt-1 font-bold text-slate-900 text-sm">{{ $registration->previous_school ?: '-' }}</p>
                </div>
                <div>
                    <span class="text-slate-400 font-medium">Nama Orang Tua / Wali</span>
                    <p class="mt-1 font-bold text-slate-900 text-sm">{{ $registration->parent_name }}</p>
                </div>
                <div>
                    <span class="text-slate-400 font-medium">WhatsApp Orang Tua</span>
                    <p class="mt-1 font-bold text-slate-900 text-sm flex items-center gap-1.5">
                        {{ $registration->whatsapp }}
                        <a href="{{ $registration->whatsappLink() }}" target="_blank" class="text-emerald-600 hover:text-emerald-700 font-bold">
                            (Chat WA)
                        </a>
                    </p>
                </div>
                <div class="sm:col-span-2">
                    <span class="text-slate-400 font-medium">Alamat Tempat Tinggal</span>
                    <p class="mt-1 font-medium text-slate-800 text-sm">{{ $registration->address }}</p>
                </div>
                <div>
                    <span class="text-slate-400 font-medium">Biaya Pendaftaran Awal</span>
                    <p class="mt-1 font-extrabold text-slate-900 text-base">Rp {{ number_format($registration->final_fee, 0, ',', '.') }}</p>
                    @if (filled($registration->referral_code))
                        <p class="mt-1.5 flex flex-wrap items-center gap-2 text-[11px]">
                            <span class="inline-flex items-center gap-1 rounded-md bg-gold-50 px-2 py-0.5 font-mono font-extrabold uppercase tracking-wider text-gold-800 ring-1 ring-inset ring-gold-600/20">
                                {{ $registration->referral_code }}
                            </span>
                            @if ($registration->discount_amount > 0)
                                <span class="font-semibold text-emerald-700">
                                    Diskon Rp {{ number_format($registration->discount_amount, 0, ',', '.') }}
                                </span>
                            @endif
                        </p>
                    @endif
                    @if (filled($registration->promoLabel()))
                        <p class="mt-1 text-[11px] font-semibold text-emerald-700">
                            {{ $registration->promoLabel() }}
                        </p>
                    @endif
                </div>
            </div>

            <!-- Bukti Pembayaran -->
            <div class="pt-4 border-t border-slate-100">
                <span class="text-xs text-slate-400 font-medium">Bukti Pembayaran Pendaftaran Awal:</span>
                @if ($registration->paymentProofIsFree())
                    <div class="mt-3 rounded-xl border border-emerald-200 bg-emerald-50 p-4">
                        <p class="text-xs font-extrabold uppercase tracking-wider text-emerald-700">GRATIS — Kode Referral Event Muslim Vibes</p>
                        <p class="mt-1 text-xs font-semibold text-emerald-800">{{ $registration->payment_proof }}</p>
                        <p class="mt-1 text-[11px] text-emerald-700/80">Tidak diperlukan unggahan bukti transfer karena biaya pendaftaran dibebaskan.</p>
                    </div>
                @elseif ($registration->paymentProofUrl())
                    <div class="mt-3 flex items-start gap-4">
                        <a href="{{ $registration->paymentProofUrl() }}" target="_blank" rel="noopener" class="block max-w-xs rounded-xl overflow-hidden border border-slate-200 shadow hover:opacity-95 transition">
                            <img src="{{ $registration->paymentProofUrl() }}" alt="Bukti Bayar" class="max-h-48 w-auto object-cover">
                        </a>
                        <div class="text-xs text-slate-600 space-y-1">
                            <p class="font-semibold text-slate-800">File Bukti Terunggah</p>
                            <a href="{{ $registration->paymentProofUrl() }}" target="_blank" class="inline-flex items-center gap-1 text-brand-600 hover:underline font-bold">
                                Buka Ukuran Penuh &rarr;
                            </a>
                        </div>
                    </div>
                @else
                    <p class="mt-1 text-xs text-slate-400 italic">Bukti pembayaran belum diunggah.</p>
                @endif
            </div>
        </div>

        <!-- Detail Tahap 2: Formulir Lengkap & Pernyataan Biaya -->
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm space-y-6">
            <div class="flex items-center justify-between border-b border-slate-100 pb-4">
                <div class="flex items-center gap-2.5">
                    <span class="w-7 h-7 rounded-lg bg-indigo-50 text-indigo-700 font-bold text-xs flex items-center justify-center">2</span>
                    <h2 class="text-base font-bold text-slate-900">Formulir Lengkap &amp; Surat Kesanggupan Biaya (Tahap 2)</h2>
                </div>
                @if ($registration->hasCompletedFullForm())
                    <span class="rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-bold text-emerald-700 ring-1 ring-inset ring-emerald-600/20">
                        &check; Selesai Diisi ({{ $registration->completed_at?->format('d M Y, H:i') }} WITA)
                    </span>
                @else
                    <span class="rounded-full bg-amber-50 px-2.5 py-1 text-xs font-bold text-amber-700 ring-1 ring-inset ring-amber-600/20">
                        Belum Diisi oleh Calon Wali Murid
                    </span>
                @endif
            </div>

            @if (! $registration->hasCompletedFullForm())
                <div class="rounded-xl border border-dashed border-slate-300 bg-slate-50/60 p-8 text-center">
                    <svg class="size-10 text-slate-400 mx-auto" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" /></svg>
                    <p class="mt-2 text-sm font-bold text-slate-700">Formulir Lengkap Belum Tersedia</p>
                    <p class="mt-1 text-xs text-slate-500 max-w-md mx-auto">
                        Setelah Anda memverifikasi pembayaran pendaftar, pendaftar dapat mengisi seluruh data lanjutan, kuesioner wawancara orang tua, dan surat kesanggupan biaya.
                    </p>
                </div>
            @else
                @php
                    $feesConfig = $registration->feeOptions();
                    $pUang = (int) $registration->formValue('pilihan_uang_masuk', 1);
                    $pSpp = (int) $registration->formValue('pilihan_spp', 1);
                    $uangNominal = $feesConfig['uang_masuk'][$pUang] ?? 0;
                    $sppNominal = $feesConfig['spp'][$pSpp] ?? 0;
                    $konsumsiNominal = $feesConfig['konsumsi'] ?? 0;
                    $isTkRegistration = $registration->level === 'TK';
                    $installmentCount = $registration->installmentCount();
                    $installmentDeadline = \App\Models\SchoolSetting::installmentDeadlineLabel();
                    $pickupsList = $registration->full_form_data['penjemput'] ?? [];
                @endphp

                <!-- Berkas Dokumen Persyaratan Yang Diunggah -->
                <div>
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-3">Dokumen Persyaratan Yang Diunggah</h3>
                    <div class="grid sm:grid-cols-3 gap-4 text-xs">
                        @if ($registration->level === 'TK')
                            <div class="rounded-xl border border-brand-200 bg-brand-50/40 p-4 space-y-2 sm:row-span-2">
                                <span class="text-slate-500 font-semibold block">Pas Foto Ananda Ukuran 3&times;4</span>
                                @if ($registration->passportPhotoUrl())
                                    <a href="{{ $registration->passportPhotoUrl() }}" target="_blank" rel="noopener"
                                       class="block w-28 overflow-hidden rounded-lg border border-slate-300 bg-white shadow-sm hover:opacity-95 transition"
                                       title="Lihat Pas Foto 3x4 {{ $registration->full_name }}">
                                        <img src="{{ $registration->passportPhotoUrl() }}" alt="Pas Foto 3x4 {{ $registration->full_name }}" class="aspect-[3/4] w-full object-cover">
                                    </a>
                                    <a href="{{ $registration->passportPhotoUrl() }}"
                                       download="Pas-Foto-3x4-{{ \Illuminate\Support\Str::slug($registration->full_name) }}.{{ pathinfo($registration->passportPhotoPath(), PATHINFO_EXTENSION) }}"
                                       class="inline-flex items-center gap-1.5 rounded-lg bg-brand-600 px-3 py-1.5 text-xs font-bold text-white hover:bg-brand-700 transition shadow-sm">
                                        <svg class="size-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
                                        </svg>
                                        Lihat / Download Pas Foto 3&times;4
                                    </a>
                                @else
                                    <p class="text-slate-400 italic">Belum diunggah</p>
                                @endif
                            </div>
                        @endif
                        @foreach (\App\Support\SpmbForm::documentFields($registration->level) as $docKey => $docLabel)
                            <div class="rounded-xl border border-slate-200 bg-slate-50/60 p-4 space-y-2">
                                <span class="text-slate-400 font-semibold block">{{ $docLabel }}</span>
                                @if ($registration->documentUrl($docKey))
                                    <a href="{{ $registration->documentUrl($docKey) }}" target="_blank"
                                       class="inline-flex items-center gap-1.5 rounded-lg bg-brand-600 px-3 py-1.5 text-xs font-bold text-white hover:bg-brand-700 transition shadow-sm">
                                        <svg class="size-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" /></svg>
                                        Lihat Berkas
                                    </a>
                                @else
                                    <p class="text-slate-400 italic">Belum diunggah</p>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Data Lanjutan Siswa -->
                <div>
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-3">A. Data Siswa Lanjutan</h3>
                    <div class="grid sm:grid-cols-3 gap-4 text-xs bg-slate-50/60 p-4 rounded-xl">
                        <div>
                            <span class="text-slate-400">NIK Siswa</span>
                            <p class="font-bold text-slate-800 mt-0.5">{{ $registration->formValue('nik', '-') }}</p>
                        </div>
                        <div>
                            <span class="text-slate-400">Nama Panggilan</span>
                            <p class="font-bold text-slate-800 mt-0.5">{{ $registration->formValue('nama_panggilan', '-') }}</p>
                        </div>
                        <div>
                            <span class="text-slate-400">Nomor Kartu Keluarga (KK)</span>
                            <p class="font-bold text-slate-800 mt-0.5">{{ $registration->formValue('no_kk', '-') }}</p>
                        </div>
                        <div>
                            <span class="text-slate-400">Agama</span>
                            <p class="font-bold text-slate-800 mt-0.5">{{ $registration->formValue('agama', 'Islam') }}</p>
                        </div>
                        <div>
                            <span class="text-slate-400">Anak Ke- / Dari Bersaudara</span>
                            <p class="font-bold text-slate-800 mt-0.5">{{ $registration->formValue('anak_ke', '-') }} dari {{ $registration->formValue('jumlah_saudara', '-') }} bersaudara</p>
                        </div>
                        <div>
                            <span class="text-slate-400">Tinggal Bersama</span>
                            <p class="font-bold text-slate-800 mt-0.5">{{ $registration->formValue('tinggal_bersama', 'Orang Tua') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Data Ayah & Ibu -->
                <div class="grid md:grid-cols-2 gap-5">
                    <div class="rounded-xl border border-slate-200 p-4 space-y-2 text-xs">
                        <h4 class="font-bold text-slate-900 border-b border-slate-100 pb-2">Data Ayah Kandung</h4>
                        <p><span class="text-slate-400">Nama:</span> <strong class="text-slate-800">{{ $registration->formValue('ayah_nama', $registration->parent_name) }}</strong></p>
                        <p><span class="text-slate-400">NIK:</span> {{ $registration->formValue('ayah_nik', '-') }}</p>
                        <p><span class="text-slate-400">TTL:</span> {{ $registration->formValue('ayah_tempat_lahir', '-') }}, {{ $registration->formValue('ayah_tanggal_lahir', '-') }}</p>
                        <p><span class="text-slate-400">Pendidikan:</span> {{ $registration->formValue('ayah_pendidikan', '-') }}</p>
                        <p><span class="text-slate-400">Pekerjaan:</span> {{ $registration->formValue('ayah_pekerjaan', '-') }}</p>
                        <p><span class="text-slate-400">Penghasilan:</span> {{ $registration->formValue('ayah_penghasilan', '-') }}</p>
                        <p><span class="text-slate-400">No. HP:</span> {{ $registration->formValue('ayah_hp', $registration->whatsapp) }}</p>
                    </div>

                    <div class="rounded-xl border border-slate-200 p-4 space-y-2 text-xs">
                        <h4 class="font-bold text-slate-900 border-b border-slate-100 pb-2">Data Ibu Kandung</h4>
                        <p><span class="text-slate-400">Nama:</span> <strong class="text-slate-800">{{ $registration->formValue('ibu_nama', '-') }}</strong></p>
                        <p><span class="text-slate-400">NIK:</span> {{ $registration->formValue('ibu_nik', '-') }}</p>
                        <p><span class="text-slate-400">TTL:</span> {{ $registration->formValue('ibu_tempat_lahir', '-') }}, {{ $registration->formValue('ibu_tanggal_lahir', '-') }}</p>
                        <p><span class="text-slate-400">Pendidikan:</span> {{ $registration->formValue('ibu_pendidikan', '-') }}</p>
                        <p><span class="text-slate-400">Pekerjaan:</span> {{ $registration->formValue('ibu_pekerjaan', '-') }}</p>
                        <p><span class="text-slate-400">Penghasilan:</span> {{ $registration->formValue('ibu_penghasilan', '-') }}</p>
                        <p><span class="text-slate-400">No. HP:</span> {{ $registration->formValue('ibu_hp', '-') }}</p>
                    </div>
                </div>

                <!-- Formulir Wawancara Orang Tua -->
                <div>
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-3">B. Formulir Wawancara Orang Tua</h3>
                    <div class="space-y-3 text-xs bg-slate-50/60 p-4 rounded-xl">
                        <div>
                            <span class="text-slate-400 font-semibold">1. Motivasi &amp; Kriteria Sekolah Idaman:</span>
                            <p class="font-medium text-slate-800 mt-1 whitespace-pre-line">{{ $registration->formValue('alasan_memilih', '-') }}</p>
                        </div>
                        <div>
                            <span class="text-slate-400 font-semibold">2. Harapan &amp; Target ke depan:</span>
                            <p class="font-medium text-slate-800 mt-1 whitespace-pre-line">{{ $registration->formValue('harapan_target', '-') }}</p>
                        </div>
                        <div>
                            <span class="text-slate-400 font-semibold">3. Pola Asuh &amp; Peraturan di Rumah:</span>
                            <p class="font-medium text-slate-800 mt-1 whitespace-pre-line">{{ $registration->formValue('peraturan_rumah', '-') }}</p>
                        </div>
                        <div>
                            <span class="text-slate-400 font-semibold">4. Kebiasaan / Pengaruh Terbesar Anak:</span>
                            <p class="font-medium text-slate-800 mt-1 whitespace-pre-line">{{ $registration->formValue('pengaruh_terbesar', '-') }}</p>
                        </div>
                        <div>
                            <span class="text-slate-400 font-semibold">5. Riwayat Penyakit Anak:</span>
                            <p class="font-medium text-slate-800 mt-1 whitespace-pre-line">{{ $registration->formValue('riwayat_penyakit', 'Tidak ada') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Daftar Penjemput -->
                @if (! empty($pickupsList))
                    <div>
                        <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-3">C. Orang Yang Diberi Wewenang Menjemput</h3>
                        <table class="w-full text-xs divide-y divide-slate-200 border border-slate-200 rounded-xl overflow-hidden">
                            <thead class="bg-slate-50 font-bold text-slate-600 text-left">
                                <tr>
                                    <th class="p-2.5">No</th>
                                    <th class="p-2.5">Nama Lengkap</th>
                                    <th class="p-2.5">Hubungan Keluarga</th>
                                    <th class="p-2.5">Nomor HP / WhatsApp</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @foreach ($pickupsList as $idx => $pickup)
                                    @if (! empty($pickup['nama']))
                                        <tr>
                                            <td class="p-2.5 font-bold">{{ $idx + 1 }}</td>
                                            <td class="p-2.5 font-semibold text-slate-800">{{ $pickup['nama'] }}</td>
                                            <td class="p-2.5 text-slate-600">{{ $pickup['hubungan'] ?? '-' }}</td>
                                            <td class="p-2.5 text-slate-600">{{ $pickup['telp'] ?? '-' }}</td>
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif

                <!-- Pilihan Biaya Pendidikan -->
                <div>
                    <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-3">D. Pilihan Biaya Pendidikan &amp; Kesanggupan</h3>
                    <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-4 text-xs bg-slate-50/60 p-4 rounded-xl">
                        <div>
                            <span class="text-slate-400">{{ $isTkRegistration ? 'Biaya Masuk (Uang Pangkal + Perlengkapan + Pertahun)' : 'Pilihan Biaya Masuk (Sarana)' }}</span>
                            <p class="font-extrabold text-slate-900 text-sm mt-0.5">
                                Rp {{ number_format((int) $uangNominal, 0, ',', '.') }}@unless ($isTkRegistration) (Pilihan {{ $pUang }})@endunless
                            </p>
                        </div>
                        <div>
                            <span class="text-slate-400">{{ $isTkRegistration ? 'SPP Bulanan' : 'Pilihan Iuran SPP Bulanan' }}</span>
                            <p class="font-extrabold text-slate-900 text-sm mt-0.5">
                                Rp {{ number_format((int) $sppNominal, 0, ',', '.') }}@unless ($isTkRegistration) (Pilihan {{ $pSpp }})@endunless
                            </p>
                        </div>
                        @if (! empty($konsumsiNominal))
                            <div>
                                <span class="text-slate-400">{{ $isTkRegistration ? 'Konsumsi (Snack) Bulanan' : 'Iuran Makan Siang / Konsumsi' }}</span>
                                <p class="font-extrabold text-slate-900 text-sm mt-0.5">
                                    Rp {{ number_format((int) $konsumsiNominal, 0, ',', '.') }}
                                </p>
                            </div>
                        @endif
                        <div>
                            <span class="text-slate-400">Metode Pembayaran Biaya Masuk</span>
                            <p class="font-extrabold text-brand-700 text-sm mt-0.5">
                                {{ $registration->formValue('cara_bayar', 'Cash') === 'Dicicil'
                                    ? 'Dicicil (' . ($installmentCount ?? 3) . ' kali)'
                                    : 'Lunas / Cash' }}
                            </p>
                            @if ($registration->formValue('cara_bayar', 'Cash') === 'Dicicil')
                                <p class="mt-1 text-[10px] font-semibold text-slate-500">
                                    Batas akhir pelunasan: {{ $installmentDeadline }}
                                </p>
                            @endif
                        </div>
                    </div>
                    <div class="mt-3 text-xs text-slate-600 italic">
                        Penanggung Jawab Biaya: <strong>{{ $registration->formValue('pj_nama', $registration->parent_name) }}</strong>
                        @if ($ttdAyah = $registration->formValue('ttd_ayah'))
                            &middot; TTD Ayah: <strong>{{ $ttdAyah }}</strong>
                        @endif
                        @if ($ttdIbu = $registration->formValue('ttd_ibu'))
                            &middot; TTD Ibu: <strong>{{ $ttdIbu }}</strong>
                        @endif
                    </div>
                </div>

                @if ($isTkRegistration)
                    <div>
                        <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-3">E. Data Saudara Kandung Alumni TK Islam Cahaya Ilmu</h3>
                        <div class="rounded-xl bg-slate-50/60 p-4 text-xs space-y-2">
                            <p>
                                <span class="text-slate-400">Status:</span>
                                <strong class="{{ $registration->hasAlumniSiblings() ? 'text-emerald-700' : 'text-slate-800' }}">
                                    {{ $registration->hasAlumniSiblings() ? 'YA, ADA' : 'TIDAK ADA' }}
                                </strong>
                            </p>
                            @if ($registration->hasAlumniSiblings())
                                <div>
                                    <span class="text-slate-400">Nama Saudara Kandung Alumni:</span>
                                    <p class="mt-1 whitespace-pre-line font-semibold text-slate-800">{{ $registration->alumniSiblingNames() ?: '-' }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif
            @endif
        </div>
    </div>
@endsection
