@extends('layouts.public')

@section('title', 'Formulir Lengkap & Surat Kesanggupan Biaya — ' . $registration->schoolName())
@section('description', 'Pengisian formulir pendaftaran lengkap dan surat pernyataan kesanggupan biaya pendidikan SPMB Cahaya Ilmu Islamic School.')

@section('content')
  <section class="page-hero py-12 lg:py-16">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
      <nav aria-label="Breadcrumb" class="breadcrumb mb-4 justify-start">
        <a href="{{ route('home') }}"><i class="fa-solid fa-house mr-1"></i>Beranda</a>
        <span class="breadcrumb-sep"><i class="fa-solid fa-chevron-right text-[10px]"></i></span>
        <a href="{{ route('ppdb') }}">SPMB</a>
        <span class="breadcrumb-sep"><i class="fa-solid fa-chevron-right text-[10px]"></i></span>
        <a href="{{ route('ppdb.status', ['nomor' => $registration->registration_number]) }}">Status</a>
        <span class="breadcrumb-sep"><i class="fa-solid fa-chevron-right text-[10px]"></i></span>
        <span class="breadcrumb-current">Tahap 2: Formulir Lengkap</span>
      </nav>

      <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
          <span class="badge-pill mb-2 text-emerald-400 font-semibold">
            <i class="fa-solid fa-circle-check text-emerald-400"></i>
            Tahap 2 dari 2: Verifikasi Selesai
          </span>
          <h1 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-amber-400 tracking-tight leading-tight">
            Formulir Lengkap &amp; <span>Kesanggupan Biaya</span>
          </h1>
          <p class="mt-2 text-slate-200 text-sm sm:text-base">
            Silakan lengkapi biodata calon peserta didik, data orang tua, kuesioner wawancara, dan pilihan biaya pendidikan untuk {{ $registration->schoolName() }}.
          </p>
        </div>
      </div>
    </div>
  </section>

  <section class="py-10 lg:py-14 bg-white">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Info Ringkas Pendaftar -->
      <div class="mb-8 rounded-3xl border border-slate-200 bg-gradient-to-r from-slate-900 via-brand-950 to-slate-900 p-6 sm:p-7 text-white shadow-lg">
        <div class="grid sm:grid-cols-3 gap-4">
          <div>
            <p class="text-xs uppercase font-bold tracking-widest text-gold-300">Calon Peserta Didik</p>
            <p class="mt-1 text-lg font-extrabold">{{ $registration->full_name }}</p>
            <p class="text-xs text-slate-300 font-mono mt-0.5">{{ $registration->registration_number }} &middot; {{ $registration->genderLabel() }}</p>
          </div>
          <div>
            <p class="text-xs uppercase font-bold tracking-widest text-gold-300">Pilihan Jenjang</p>
            <p class="mt-1 text-lg font-extrabold text-gold-400">{{ $registration->schoolName() }}</p>
            <p class="text-xs text-slate-300 mt-0.5">{{ $registration->birth_place ?: '-' }}, {{ $registration->birth_date?->format('d M Y') }}</p>
          </div>
          <div>
            <p class="text-xs uppercase font-bold tracking-widest text-gold-300">Orang Tua / Kontak</p>
            <p class="mt-1 text-lg font-extrabold">{{ $registration->parent_name }}</p>
            <p class="text-xs text-slate-300 mt-0.5">{{ $registration->whatsapp }}</p>
          </div>
        </div>
      </div>

      @if ($errors->any())
        <div class="mb-8 rounded-2xl border border-rose-200 bg-rose-50/90 p-5 text-rose-900 shadow-sm">
          <div class="flex items-start gap-3">
            <i class="fa-solid fa-triangle-exclamation text-rose-600 text-lg mt-0.5"></i>
            <div>
              <h3 class="text-sm font-bold text-rose-950">Mohon periksa kembali isian formulir Anda</h3>
              <p class="mt-0.5 text-xs text-rose-700">Terdapat beberapa kolom wajib yang belum terisi atau belum sesuai:</p>
              <ul class="mt-2 text-xs list-disc list-inside space-y-1 text-rose-800">
                @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div>
          </div>
        </div>
      @endif

      <form action="{{ route('spmb.full-form.store', $registration->registration_code) }}" method="POST" enctype="multipart/form-data" class="space-y-10" id="form-tahap-2" novalidate>
        @csrf

        <!-- Rincian Bagian A sampai J (Formulir Lengkap & Wawancara) -->
        @foreach ($sections as $section)
          <div class="rounded-3xl border border-slate-200 bg-white p-6 sm:p-8 shadow-sm space-y-6">
            <div class="border-b border-slate-100 pb-3">
              <h2 class="text-lg sm:text-xl font-extrabold text-slate-900 flex items-center gap-2">
                <span class="w-2.5 h-6 rounded-full bg-brand-600"></span>
                {{ $section['title'] }}
              </h2>
            </div>

            <div class="grid gap-5 sm:grid-cols-2">
              @foreach ($section['fields'] as $field)
                @php
                  $fieldKey = 'form.' . $field['key'];
                  $fieldName = 'form[' . $field['key'] . ']';
                  $oldVal = old($fieldKey, $registration->formValue($field['key']));
                @endphp

                <div class="{{ ($field['full_width'] ?? false) ? 'sm:col-span-2' : '' }} space-y-1.5">
                  <label for="{{ $field['key'] }}" class="block text-xs sm:text-sm font-bold text-slate-800">
                    {{ $field['label'] }}
                    @if ($field['required'] ?? false)
                      <span class="text-rose-500 font-bold">*</span>
                    @endif
                  </label>

                  @if ($field['type'] === 'textarea')
                    <textarea
                      id="{{ $field['key'] }}"
                      name="{{ $fieldName }}"
                      rows="3"
                      placeholder="Tuliskan jawaban Anda di sini..."
                      class="w-full rounded-xl border border-slate-200 bg-slate-50/50 p-3 text-xs sm:text-sm text-slate-900 placeholder:text-slate-400 outline-none transition focus:border-brand-500 focus:bg-white focus:ring-2 focus:ring-brand-100"
                    >{{ $oldVal }}</textarea>
                  @elseif ($field['type'] === 'select')
                    <select
                      id="{{ $field['key'] }}"
                      name="{{ $fieldName }}"
                      class="w-full rounded-xl border border-slate-200 bg-slate-50/50 p-3 text-xs sm:text-sm text-slate-900 outline-none transition focus:border-brand-500 focus:bg-white focus:ring-2 focus:ring-brand-100"
                    >
                      <option value="">-- Pilih Salah Satu --</option>
                      @foreach ($field['options'] ?? [] as $optKey => $optVal)
                        <option value="{{ $optKey }}" @selected((string) $oldVal === (string) $optKey)>{{ $optVal }}</option>
                      @endforeach
                    </select>
                  @elseif ($field['type'] === 'radio')
                    <div class="grid gap-2 sm:grid-cols-2 pt-1">
                      @foreach ($field['options'] ?? [] as $optKey => $optVal)
                        <label class="flex items-center gap-3 rounded-xl border border-slate-200 p-3 cursor-pointer transition hover:bg-slate-50 has-[:checked]:border-brand-500 has-[:checked]:bg-brand-50/40">
                          <input
                            type="radio"
                            name="{{ $fieldName }}"
                            value="{{ $optKey }}"
                            @checked((string) $oldVal === (string) $optKey)
                            class="size-4 text-brand-600 focus:ring-brand-500 border-slate-300"
                          >
                          <span class="text-xs sm:text-sm font-semibold text-slate-800">{{ $optVal }}</span>
                        </label>
                      @endforeach
                    </div>
                  @else
                    <input
                      type="{{ $field['type'] }}"
                      id="{{ $field['key'] }}"
                      name="{{ $fieldName }}"
                      value="{{ $oldVal }}"
                      @if (isset($field['max'])) maxlength="{{ $field['max'] }}" @endif
                      placeholder="Masukkan {{ strtolower($field['label']) }}..."
                      class="w-full rounded-xl border border-slate-200 bg-slate-50/50 p-3 text-xs sm:text-sm text-slate-900 placeholder:text-slate-400 outline-none transition focus:border-brand-500 focus:bg-white focus:ring-2 focus:ring-brand-100"
                    >
                  @endif

                  @error($fieldKey)
                    <p class="text-xs text-rose-600">{{ $message }}</p>
                  @enderror
                </div>
              @endforeach
            </div>
          </div>
        @endforeach

        <!-- Tabel Orang Yang Berhak Menjemput (Bagian I) -->
        <div class="rounded-3xl border border-slate-200 bg-white p-6 sm:p-8 shadow-sm space-y-6">
          <div class="border-b border-slate-100 pb-3">
            <h2 class="text-lg sm:text-xl font-extrabold text-slate-900 flex items-center gap-2">
              <span class="w-2.5 h-6 rounded-full bg-brand-600"></span>
              I. Orang Yang Diberi Wewenang Menjemput (Maksimal 3 Orang)
            </h2>
            <p class="mt-1 text-xs sm:text-sm text-slate-500">
              Demi keamanan peserta didik, sebutkan pihak selain orang tua kandung yang diberi wewenang menjemput siswa saat pulang sekolah (kosongkan jika dijemput sendiri oleh orang tua).
            </p>
          </div>

          <div class="overflow-x-auto">
            <table class="w-full text-xs sm:text-sm border border-slate-200 rounded-2xl overflow-hidden divide-y divide-slate-200">
              <thead class="bg-slate-50 font-bold text-slate-700 text-left">
                <tr>
                  <th class="p-3 w-12 text-center">No</th>
                  <th class="p-3">Nama Lengkap</th>
                  <th class="p-3 w-24">Usia</th>
                  <th class="p-3">Hubungan</th>
                  <th class="p-3">No. Identitas (KTP/SIM)</th>
                  <th class="p-3">Telp / WhatsApp</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-slate-100">
                @for ($i = 0; $i < $pickupRows; $i++)
                  @php
                    $pData = old('pickup.' . $i, data_get($registration->full_form_data, 'penjemput.' . $i, []));
                  @endphp
                  <tr class="align-middle">
                    <td class="p-3 text-center font-bold text-slate-500">{{ $i + 1 }}</td>
                    <td class="p-2">
                      <input
                        type="text"
                        name="pickup[{{ $i }}][nama]"
                        value="{{ $pData['nama'] ?? '' }}"
                        placeholder="Nama penjemput {{ $i + 1 }}"
                        class="w-full rounded-lg border border-slate-200 bg-white px-2.5 py-2 text-xs text-slate-900 outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-100"
                      >
                    </td>
                    <td class="p-2">
                      <input
                        type="number"
                        name="pickup[{{ $i }}][usia]"
                        value="{{ $pData['usia'] ?? '' }}"
                        placeholder="Thn"
                        class="w-full rounded-lg border border-slate-200 bg-white px-2.5 py-2 text-xs text-slate-900 outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-100"
                      >
                    </td>
                    <td class="p-2">
                      <input
                        type="text"
                        name="pickup[{{ $i }}][hubungan]"
                        value="{{ $pData['hubungan'] ?? '' }}"
                        placeholder="Misal: Paman/Bibi/Supir"
                        class="w-full rounded-lg border border-slate-200 bg-white px-2.5 py-2 text-xs text-slate-900 outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-100"
                      >
                    </td>
                    <td class="p-2">
                      <input
                        type="text"
                        name="pickup[{{ $i }}][no_identitas]"
                        value="{{ $pData['no_identitas'] ?? '' }}"
                        placeholder="No. KTP/SIM"
                        class="w-full rounded-lg border border-slate-200 bg-white px-2.5 py-2 text-xs text-slate-900 outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-100"
                      >
                    </td>
                    <td class="p-2">
                      <input
                        type="tel"
                        name="pickup[{{ $i }}][telp]"
                        value="{{ $pData['telp'] ?? '' }}"
                        placeholder="0812..."
                        class="w-full rounded-lg border border-slate-200 bg-white px-2.5 py-2 text-xs text-slate-900 outline-none focus:border-brand-500 focus:ring-1 focus:ring-brand-100"
                      >
                    </td>
                  </tr>
                @endfor
              </tbody>
            </table>
          </div>
        </div>

        <!-- Biaya Pendidikan & Surat Kesanggupan Biaya (Bagian K, L, M) -->
        <div class="rounded-3xl border border-slate-200 bg-white p-6 sm:p-8 shadow-sm space-y-8">
          <div class="border-b border-slate-100 pb-3">
            <h2 class="text-lg sm:text-xl font-extrabold text-slate-900 flex items-center gap-2">
              <span class="w-2.5 h-6 rounded-full bg-brand-600"></span>
              K. Pilihan Biaya Pendidikan {{ $registration->schoolName() }}
            </h2>
            <p class="mt-1 text-xs sm:text-sm text-slate-500">
              Berikut rincian pilihan biaya pendidikan. Silakan tentukan opsi yang paling sesuai dengan kemampuan keluarga Anda.
            </p>
            @if (! empty($fees['konsumsi']))
              <div class="mt-4 flex items-start gap-2.5 rounded-2xl border border-gold-200 bg-gold-50/70 p-3.5 text-xs sm:text-sm text-slate-700">
                <i class="fa-solid fa-circle-info mt-0.5 text-gold-600" aria-hidden="true"></i>
                <p>
                  <strong class="font-extrabold text-slate-900">+ Konsumsi : Rp {{ number_format($fees['konsumsi'], 0, ',', '.') }} / bulan</strong>
                  <span class="block text-[11px] sm:text-xs text-slate-500">Wajib dibayarkan bersama SPP setiap bulan.</span>
                </p>
              </div>
            @endif
          </div>

          <!-- Tabel Ringkasan Pilihan Biaya -->
          <div class="grid sm:grid-cols-3 gap-4">
            @for ($p = 1; $p <= 3; $p++)
              <div class="rounded-2xl border border-slate-200 bg-slate-50/70 p-4 space-y-2">
                <span class="inline-block px-2.5 py-0.5 rounded-full text-xs font-extrabold bg-brand-100 text-brand-800">
                  Pilihan {{ $p }}
                </span>
                <div class="pt-1">
                  <p class="text-[11px] text-slate-400 font-semibold uppercase">Uang Masuk / Sarana</p>
                  <p class="text-base font-extrabold text-slate-900">
                    Rp {{ number_format($fees['uang_masuk'][$p] ?? 0, 0, ',', '.') }}
                  </p>
                </div>
                <div>
                  <p class="text-[11px] text-slate-400 font-semibold uppercase">SPP Bulanan</p>
                  <p class="text-base font-extrabold text-brand-700">
                    Rp {{ number_format($fees['spp'][$p] ?? 0, 0, ',', '.') }} <span class="text-[11px] font-normal text-slate-500">/ bln</span>
                  </p>
                </div>
                @if (! empty($fees['konsumsi']))
                  <div class="pt-2 border-t border-slate-200">
                    <p class="text-[11px] text-slate-400 font-semibold uppercase">Konsumsi / Makan Siang</p>
                    <p class="text-xs font-extrabold text-brand-700">
                      + Konsumsi : Rp {{ number_format($fees['konsumsi'], 0, ',', '.') }} / bulan
                    </p>
                  </div>
                @endif
              </div>
            @endfor
          </div>

          <!-- Form Fields Biaya Pendidikan -->
          <div class="space-y-6 pt-4 border-t border-slate-100">
            @foreach ($feeSections as $section)
              <div class="space-y-4">
                <h3 class="text-sm font-extrabold text-slate-900">{{ $section['title'] }}</h3>
                <div class="grid gap-4 sm:grid-cols-2">
                  @foreach ($section['fields'] as $field)
                    @php
                      $fieldKey = 'fee.' . $field['key'];
                      $fieldName = 'fee[' . $field['key'] . ']';
                      $oldVal = old($fieldKey, $registration->formValue($field['key']));
                    @endphp

                    <div class="{{ ($field['full_width'] ?? false) ? 'sm:col-span-2' : '' }} space-y-1.5">
                      <label for="fee_{{ $field['key'] }}" class="block text-xs sm:text-sm font-bold text-slate-800">
                        {{ $field['label'] }}
                        @if ($field['required'] ?? false)
                          <span class="text-rose-500 font-bold">*</span>
                        @endif
                      </label>

                      @if ($field['type'] === 'textarea')
                        <textarea
                          id="fee_{{ $field['key'] }}"
                          name="{{ $fieldName }}"
                          rows="3"
                          placeholder="Tuliskan jawaban Anda di sini..."
                          class="w-full rounded-xl border border-slate-200 bg-slate-50/50 p-3 text-xs sm:text-sm text-slate-900 outline-none transition focus:border-brand-500 focus:bg-white focus:ring-2 focus:ring-brand-100"
                        >{{ $oldVal }}</textarea>
                      @elseif ($field['type'] === 'radio')
                        <div class="grid gap-2 sm:grid-cols-3 pt-1">
                          @foreach ($field['options'] ?? [] as $optKey => $optVal)
                            <label class="flex items-center gap-3 rounded-xl border border-slate-200 p-3 cursor-pointer transition hover:bg-slate-50 has-[:checked]:border-brand-500 has-[:checked]:bg-brand-50/40">
                              <input
                                type="radio"
                                name="{{ $fieldName }}"
                                value="{{ $optKey }}"
                                @checked((string) $oldVal === (string) $optKey)
                                class="size-4 text-brand-600 focus:ring-brand-500 border-slate-300"
                              >
                              <span class="text-xs sm:text-sm font-semibold text-slate-800">{{ $optVal }}</span>
                            </label>
                          @endforeach
                        </div>
                      @else
                        <input
                          type="{{ $field['type'] }}"
                          id="fee_{{ $field['key'] }}"
                          name="{{ $fieldName }}"
                          value="{{ $oldVal }}"
                          placeholder="Masukkan {{ strtolower($field['label']) }}..."
                          class="w-full rounded-xl border border-slate-200 bg-slate-50/50 p-3 text-xs sm:text-sm text-slate-900 outline-none transition focus:border-brand-500 focus:bg-white focus:ring-2 focus:ring-brand-100"
                        >
                      @endif

                      @error($fieldKey)
                        <p class="text-xs text-rose-600">{{ $message }}</p>
                      @enderror
                    </div>
                  @endforeach
                </div>
              </div>
            @endforeach
          </div>
        </div>

        @if ($registration->level === 'TK')
          @php
            $existingPassportPhotoUrl = $registration->passportPhotoUrl();
          @endphp
          <!-- Upload Pas Foto 3x4 (Khusus KB-TK) -->
          <div class="rounded-3xl border border-brand-200 bg-gradient-to-br from-brand-50/50 via-white to-amber-50/30 p-6 sm:p-8 shadow-sm space-y-5">
            <div class="border-b border-brand-100 pb-3">
              <h2 class="text-lg sm:text-xl font-extrabold text-slate-900 flex items-center gap-2">
                <span class="w-2.5 h-6 rounded-full bg-brand-600"></span>
                Upload Pas Foto Ananda Ukuran 3&times;4
              </h2>
              <p class="mt-1 text-xs sm:text-sm text-slate-500">
                Pakaian bebas muslim/muslimah, background bebas. Format JPG/PNG/WebP, maksimal 5 MB.
              </p>
            </div>

            <div class="grid gap-5 sm:grid-cols-[9rem_1fr] items-start">
              <div class="mx-auto sm:mx-0">
                <div class="aspect-[3/4] w-36 overflow-hidden rounded-xl border-2 border-dashed border-brand-300 bg-white shadow-sm">
                  <img
                    id="pas-foto-preview"
                    alt="Pratinjau Pas Foto 3x4 {{ $registration->full_name }}"
                    @if ($existingPassportPhotoUrl) src="{{ $existingPassportPhotoUrl }}" @endif
                    class="h-full w-full object-cover {{ $existingPassportPhotoUrl ? '' : 'hidden' }}"
                  >
                  <div id="pas-foto-placeholder" class="flex h-full w-full flex-col items-center justify-center gap-1.5 text-slate-400 {{ $existingPassportPhotoUrl ? 'hidden' : '' }}">
                    <i class="fa-solid fa-camera-retro text-2xl"></i>
                    <span class="text-[10px] font-bold uppercase tracking-wider">Preview 3&times;4</span>
                  </div>
                </div>
                <p class="mt-1.5 text-center text-[10px] font-semibold text-slate-400">Rasio 3 &times; 4</p>
              </div>

              <div class="space-y-2">
                <label for="pas_foto_3x4" class="block text-xs sm:text-sm font-bold text-slate-800">
                  <i class="fa-solid fa-id-card-clip text-brand-600 mr-1.5"></i>
                  {{ $registration->passportPhotoPath() ? 'Ganti Pas Foto Ananda Ukuran 3×4' : 'Upload Pas Foto Ananda Ukuran 3×4' }}
                  <span class="text-rose-600">*</span>
                </label>
                <input
                  type="file"
                  id="pas_foto_3x4"
                  name="pas_foto_3x4"
                  accept="image/jpeg,image/png,image/jpg,image/webp"
                  class="w-full text-xs text-slate-600 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-brand-50 file:text-brand-700 hover:file:bg-brand-100 cursor-pointer"
                >
                <p class="text-[11px] text-slate-400">
                  Pakaian bebas muslim/muslimah, background bebas. Format JPG/PNG/WebP, maksimal 5MB.
                </p>
                <p id="pas-foto-file-name" class="hidden text-[11px] font-semibold text-brand-700"></p>
                @if ($existingPassportPhotoUrl)
                  <a href="{{ $existingPassportPhotoUrl }}" target="_blank" rel="noopener" class="inline-flex items-center gap-1.5 text-[11px] font-bold text-brand-700 hover:underline">
                    <i class="fa-solid fa-eye"></i> Lihat pas foto yang sudah diunggah
                  </a>
                @endif
                @error('pas_foto_3x4')
                  <p class="text-xs font-semibold text-rose-600">{{ $message }}</p>
                @enderror
              </div>
            </div>
          </div>
        @endif

        <!-- Upload Berkas Persyaratan (Bagian N - Opsional) -->
        <div class="rounded-3xl border border-slate-200 bg-white p-6 sm:p-8 shadow-sm space-y-6">
          <div class="border-b border-slate-100 pb-3">
            <h2 class="text-lg sm:text-xl font-extrabold text-slate-900 flex items-center gap-2">
              <span class="w-2.5 h-6 rounded-full bg-brand-600"></span>
              N. Upload Dokumen &amp; Berkas Persyaratan (Opsional)
            </h2>
            <p class="mt-1 text-xs sm:text-sm text-slate-500">
              Anda dapat mengunggah foto / scan berkas sekarang untuk mempercepat verifikasi admin, atau membawanya saat tes/wawancara di sekolah. (Maksimal 5 MB per file, format: JPG, PNG, WebP, atau PDF).
            </p>
          </div>

          @php
            $documentMeta = [
              'berkas_kk' => ['icon' => 'fa-id-card', 'hint' => 'Pastikan tulisan pada Kartu Keluarga terbaca jelas.', 'col' => ''],
              'berkas_foto_ananda' => ['icon' => 'fa-camera', 'hint' => 'Foto ananda memakai pakaian bebas muslim/muslimah dengan background bebas.', 'col' => ''],
              'berkas_akta' => ['icon' => 'fa-certificate', 'hint' => 'Pastikan nama dan tanggal lahir ananda terbaca jelas.', 'col' => ''],
              'berkas_lainnya' => ['icon' => 'fa-folder-open', 'hint' => 'Misal: Sertifikat/Piagam Prestasi, Surat Keterangan Pindah, atau Ijazah jenjang sebelumnya jika ada.', 'col' => 'sm:col-span-2'],
            ];
          @endphp

          <div class="grid gap-5 sm:grid-cols-2">
            @foreach (\App\Support\SpmbForm::documentFields($registration->level) as $docKey => $docLabel)
              @php($meta = $documentMeta[$docKey] ?? ['icon' => 'fa-file-lines', 'hint' => 'Unggah berkas pendukung bila ada.', 'col' => ''])
              <div class="{{ $meta['col'] }} rounded-2xl border border-slate-200 bg-slate-50/60 p-4 space-y-2">
                <div class="flex items-center justify-between">
                  <label for="{{ $docKey }}" class="block text-xs sm:text-sm font-bold text-slate-800">
                    <i class="fa-solid {{ $meta['icon'] }} text-brand-600 mr-1.5"></i> {{ $docLabel }}
                  </label>
                  @if ($registration->documentUrl($docKey))
                    <a href="{{ $registration->documentUrl($docKey) }}" target="_blank" class="inline-flex items-center gap-1 text-[11px] font-bold text-brand-700 hover:underline">
                      <i class="fa-solid fa-eye"></i> Lihat Berkas
                    </a>
                  @endif
                </div>
                <input
                  type="file"
                  id="{{ $docKey }}"
                  name="{{ $docKey }}"
                  accept=".jpg,.jpeg,.png,.webp,.pdf"
                  class="w-full text-xs text-slate-600 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-brand-50 file:text-brand-700 hover:file:bg-brand-100 cursor-pointer"
                >
                <p class="text-[11px] text-slate-400">{{ $meta['hint'] }}</p>
                @error($docKey)
                  <p class="text-xs text-rose-600">{{ $message }}</p>
                @enderror
              </div>
            @endforeach
          </div>
        </div>

        <!-- Surat Pernyataan & Persetujuan -->
        <div class="rounded-3xl border-2 border-brand-500 bg-gradient-to-br from-brand-50/60 via-white to-amber-50/30 p-6 sm:p-8 shadow-md space-y-6">
          <div class="border-b border-brand-200 pb-3">
            <h2 class="text-lg sm:text-xl font-extrabold text-slate-900 flex items-center gap-2">
              <i class="fa-solid fa-file-signature text-brand-600"></i>
              Surat Pernyataan &amp; Komitmen Orang Tua / Wali
            </h2>
            <p class="mt-1 text-xs sm:text-sm text-slate-600">
              Mohon baca dan centang seluruh pernyataan kesanggupan di bawah ini sebagai bukti persetujuan resmi:
            </p>
          </div>

          <div class="space-y-4">
            @foreach ($agreements as $agKey => $agText)
              <label class="flex items-start gap-3.5 p-4 rounded-2xl border border-slate-200 bg-white cursor-pointer transition hover:bg-slate-50/80 shadow-sm has-[:checked]:border-brand-500 has-[:checked]:bg-brand-50/30">
                <input
                  type="checkbox"
                  name="agreements[{{ $agKey }}]"
                  value="1"
                  @checked(old('agreements.' . $agKey, session()->hasOldInput() ? false : data_get($registration->fee_agreement_data, 'agreements.' . $agKey, true)))
                  class="mt-1 size-5 rounded text-brand-600 focus:ring-brand-500 border-slate-300"
                >
                <span class="text-xs sm:text-sm text-slate-800 leading-relaxed font-medium">
                  {{ $agText }}
                </span>
              </label>
              @error('agreements.' . $agKey)
                <p class="text-xs text-rose-600 pl-8">{{ $message }}</p>
              @enderror
            @endforeach
          </div>
        </div>

        <!-- Tombol Aksi Submit -->
        <div class="pt-4 flex flex-col sm:flex-row items-center justify-between gap-4">
          <a href="{{ route('ppdb.status', ['nomor' => $registration->registration_number]) }}" class="text-xs sm:text-sm font-bold text-slate-600 hover:text-brand-600">
            <i class="fa-solid fa-arrow-left mr-1"></i> Kembali ke Cek Status
          </a>

          <button
            type="submit"
            id="btn-submit-tahap-2"
            class="btn-primary !py-4 !px-8 text-sm sm:text-base font-extrabold shadow-xl hover:shadow-2xl w-full sm:w-auto justify-center"
          >
            <i class="fa-solid fa-paper-plane mr-1.5"></i>
            Kirim Formulir Lengkap &amp; Kesanggupan Biaya
          </button>
        </div>
      </form>
    </div>
  </section>

  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const form = document.getElementById('form-tahap-2');
      if (!form) return;

      const submitBtn = document.getElementById('btn-submit-tahap-2');

      const photoInput = document.getElementById('pas_foto_3x4');
      const photoPreview = document.getElementById('pas-foto-preview');
      const photoPlaceholder = document.getElementById('pas-foto-placeholder');
      const photoFileName = document.getElementById('pas-foto-file-name');
      let photoObjectUrl = null;

      if (photoInput) {
        photoInput.addEventListener('change', function () {
          const file = photoInput.files && photoInput.files[0];

          if (!file) {
            return;
          }

          if (photoObjectUrl) {
            URL.revokeObjectURL(photoObjectUrl);
          }

          photoObjectUrl = URL.createObjectURL(file);

          if (photoPreview) {
            photoPreview.src = photoObjectUrl;
            photoPreview.classList.remove('hidden');
          }

          photoPlaceholder?.classList.add('hidden');

          if (photoFileName) {
            photoFileName.textContent = 'File dipilih: ' + file.name;
            photoFileName.classList.remove('hidden');
          }
        });
      }

      form.addEventListener('submit', function () {
        if (submitBtn) {
          submitBtn.disabled = true;
          submitBtn.classList.add('opacity-75', 'cursor-not-allowed');
          submitBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin mr-2"></i> Mengirim Formulir...';
        }
      });
    });
  </script>
@endsection
