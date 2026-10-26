@extends('layouts.public')

@section('title', 'Formulir Lengkap Berhasil Dikirim — SPMB ' . $registration->schoolName())
@section('description', 'Pengisian formulir lengkap dan surat pernyataan kesanggupan biaya SPMB Cahaya Ilmu Islamic School telah berhasil.')

@section('content')
  <section class="page-hero py-14 lg:py-20">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
      <div class="w-20 h-20 rounded-3xl bg-emerald-100 text-emerald-600 flex items-center justify-center text-4xl mx-auto mb-6 shadow-md">
        <i class="fa-solid fa-circle-check"></i>
      </div>

      <span class="badge-pill mb-3 text-emerald-400 font-semibold">
        <i class="fa-solid fa-award text-emerald-400"></i>
        Tahap 2 Selesai — Data Lengkap &amp; Pernyataan Diterima
      </span>

      <h1 class="text-3xl sm:text-4xl font-extrabold text-amber-400 tracking-tight leading-tight">
        Alhamdulillah, Formulir Lengkap <br><span>Berhasil Dikirim!</span>
      </h1>
      <p class="mt-3 text-slate-200 text-sm sm:text-base max-w-xl mx-auto">
        Jazakumullahu khairan. Seluruh data pendaftaran, kuesioner wawancara, dan surat pernyataan kesanggupan biaya telah tercatat dengan rapi di sistem kami.
      </p>
    </div>
  </section>

  <section class="py-12 lg:py-16 bg-white">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
      <!-- Grup WhatsApp Card Utama -->
      <div class="rounded-3xl border-2 border-emerald-500 bg-gradient-to-br from-emerald-50 via-white to-sky-50/50 p-6 sm:p-8 shadow-xl text-center space-y-4">
        <div class="w-16 h-16 rounded-2xl bg-emerald-600 text-white flex items-center justify-center text-3xl mx-auto shadow-md">
          <i class="fa-brands fa-whatsapp"></i>
        </div>

        <div>
          <span class="inline-block px-3 py-1 rounded-full text-xs font-extrabold uppercase tracking-wider bg-emerald-100 text-emerald-900 mb-2">
            Langkah Penting Berikutnya
          </span>
          <h2 class="text-xl sm:text-2xl font-extrabold text-slate-900">
            Gabung Grup WhatsApp Resmi {{ $registration->level }}
          </h2>
          <p class="text-xs sm:text-sm text-slate-600 max-w-lg mx-auto mt-1.5 leading-relaxed">
            Seluruh pengumuman teknis, jadwal observasi calon santri, jadwal wawancara tatap muka orang tua, serta tata tertib akan dikoordinasikan secara terpusat melalui grup WhatsApp resmi berikut:
          </p>
        </div>

        <div class="pt-2">
          @if ($whatsappGroupUrl)
            <a
              href="{{ $whatsappGroupUrl }}"
              target="_blank"
              rel="noopener noreferrer"
              class="inline-flex items-center justify-center gap-2.5 rounded-2xl bg-emerald-600 px-8 py-4 text-base font-extrabold text-white shadow-lg transition duration-200 hover:bg-emerald-700 hover:shadow-xl hover:-translate-y-0.5 active:translate-y-0"
            >
              <i class="fa-brands fa-whatsapp text-2xl"></i>
              Gabung Grup WhatsApp {{ $registration->level }} ({{ $registration->genderLabel() }})
            </a>
          @else
            <div class="rounded-xl border border-amber-200 bg-amber-50 p-4 text-xs sm:text-sm text-amber-800 max-w-md mx-auto">
              <i class="fa-solid fa-circle-info mr-1"></i>
              Link grup WhatsApp resmi untuk jenjang ini sedang disiapkan oleh Admin SPMB. Panitia akan menghubungi Anda melalui nomor WhatsApp yang terdaftar.
            </div>
          @endif
        </div>
      </div>

      <!-- Ringkasan Pendaftaran -->
      <div class="rounded-3xl border border-slate-200 bg-slate-50/60 p-6 sm:p-7 space-y-4">
        <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400">Ringkasan Data Calon Peserta Didik</h3>
        <div class="grid sm:grid-cols-2 gap-4 text-xs sm:text-sm">
          <div>
            <span class="text-slate-400">Nama Lengkap</span>
            <p class="font-extrabold text-slate-900 mt-0.5">{{ $registration->full_name }}</p>
          </div>
          <div>
            <span class="text-slate-400">Nomor Registrasi SPMB</span>
            <p class="font-mono font-extrabold text-brand-700 mt-0.5">{{ $registration->registration_number }}</p>
          </div>
          <div>
            <span class="text-slate-400">Jenjang Pilihan</span>
            <p class="font-bold text-slate-800 mt-0.5">{{ $registration->schoolName() }} ({{ $registration->genderLabel() }})</p>
          </div>
          <div>
            <span class="text-slate-400">Status Pendaftaran</span>
            <p class="font-bold text-emerald-700 mt-0.5">&check; {{ $registration->statusLabel() }}</p>
          </div>
        </div>
      </div>

      <!-- Tombol Dokumen & Navigasi -->
      <div class="flex flex-col sm:flex-row items-center justify-center gap-3 pt-2">
        <a
          href="{{ route('spmb.full-form.print', $registration->registration_code) }}"
          target="_blank"
          class="btn-primary !py-3.5 !px-6 text-xs sm:text-sm font-bold w-full sm:w-auto justify-center"
        >
          <i class="fa-solid fa-print"></i>
          Cetak Formulir Lengkap (A4)
        </a>

        <a
          href="{{ route('spmb.full-form.pdf', $registration->registration_code) }}"
          class="inline-flex items-center justify-center gap-2 rounded-xl border border-rose-300 bg-rose-50 px-6 py-3.5 text-xs sm:text-sm font-bold text-rose-700 hover:bg-rose-100 transition shadow-sm w-full sm:w-auto"
        >
          <i class="fa-solid fa-file-pdf"></i>
          Download Dokumen PDF
        </a>

        <a
          href="{{ route('ppdb.status', ['nomor' => $registration->registration_number]) }}"
          class="btn-secondary !py-3.5 !px-6 text-xs sm:text-sm font-bold w-full sm:w-auto justify-center"
        >
          <i class="fa-solid fa-id-badge"></i>
          Cek Status Pendaftaran
        </a>
      </div>
    </div>
  </section>
@endsection
