@extends('admin.layouts.app')

@section('title', 'Pengaturan Web')
@section('subtitle', 'Kelola identitas sekolah, kontak umum, media sosial, dan gambar website')

@section('content')
    <div class="mx-auto max-w-6xl space-y-6">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-900">Pengaturan Website</h1>
            <p class="mt-1 text-sm text-slate-500">
                Pengaturan profil umum website. Untuk status, kuota, grup WhatsApp, dan biaya SPMB silakan buka
                <a href="{{ route('admin.ppdb-settings.index') }}" class="font-semibold text-rose-600 hover:underline">Pengaturan SPMB</a>.
            </p>
        </div>

        <form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <section class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm sm:p-6">
                <div class="flex items-start gap-2.5 sm:items-center sm:gap-2">
                    <span class="grid size-8 shrink-0 place-items-center rounded-lg bg-rose-50 text-rose-600">
                        <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3.75h.008v.008h-.008v-.008Zm0 3h.008v.008h-.008v-.008Zm0 3h.008v.008h-.008v-.008Z" />
                        </svg>
                    </span>
                    <div>
                        <h2 class="text-sm font-semibold text-slate-900">Identitas Sekolah</h2>
                        <p class="text-xs text-slate-500">Nama sekolah, yayasan, dan motto yang tampil di seluruh halaman.</p>
                    </div>
                </div>

                <div class="mt-5 grid gap-4 sm:grid-cols-2">
                    <div>
                        <label for="nama_sekolah" class="block text-sm font-medium text-slate-700">Nama Sekolah</label>
                        <input id="nama_sekolah"
                               name="nama_sekolah"
                               type="text"
                               value="{{ old('nama_sekolah', $settings['nama_sekolah'] ?? '') }}"
                               placeholder="Cahaya Ilmu Islamic School"
                               class="mt-1.5 block w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm shadow-sm outline-none transition placeholder:text-slate-400 focus:border-rose-400 focus:ring-4 focus:ring-rose-100">
                        @error('nama_sekolah')
                            <p class="mt-1.5 text-xs text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="foundation_name" class="block text-sm font-medium text-slate-700">Nama Yayasan</label>
                        <input id="foundation_name"
                               name="foundation_name"
                               type="text"
                               value="{{ old('foundation_name', $settings['foundation_name'] ?? '') }}"
                               placeholder="Yayasan Ath-Thoifah Al-Manshuroh Balikpapan"
                               class="mt-1.5 block w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm shadow-sm outline-none transition placeholder:text-slate-400 focus:border-rose-400 focus:ring-4 focus:ring-rose-100">
                        @error('foundation_name')
                            <p class="mt-1.5 text-xs text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="sm:col-span-2">
                        <label for="motto" class="block text-sm font-medium text-slate-700">Motto Sekolah</label>
                        <input id="motto"
                               name="motto"
                               type="text"
                               value="{{ old('motto', $settings['motto'] ?? '') }}"
                               placeholder="Tumbuh Kembang di Atas Ilmu dan Amal"
                               class="mt-1.5 block w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm shadow-sm outline-none transition placeholder:text-slate-400 focus:border-rose-400 focus:ring-4 focus:ring-rose-100">
                        @error('motto')
                            <p class="mt-1.5 text-xs text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </section>

            <section class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm sm:p-6">
                <div class="flex items-start gap-2.5 sm:items-center sm:gap-2">
                    <span class="grid size-8 shrink-0 place-items-center rounded-lg bg-rose-50 text-rose-600">
                        <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
                        </svg>
                    </span>
                    <div>
                        <h2 class="text-sm font-semibold text-slate-900">Kontak Umum Sekolah</h2>
                        <p class="text-xs text-slate-500">Alamat, email resmi, WhatsApp pusat, dan lokasi Google Maps.</p>
                    </div>
                </div>

                <div class="mt-5 grid gap-4 sm:grid-cols-2">
                    <div class="sm:col-span-2">
                        <label for="alamat_sekolah" class="block text-sm font-medium text-slate-700">Alamat Lengkap</label>
                        <textarea id="alamat_sekolah"
                                  name="alamat_sekolah"
                                  rows="3"
                                  placeholder="Jl. Syarifuddin Yoes RT. 41 komp. Masjid Al-Imam An-Nasa'i Kel. Gunung Bahagia Kec. Balikpapan Selatan 76114"
                                  class="mt-1.5 block w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm leading-relaxed shadow-sm outline-none transition placeholder:text-slate-400 focus:border-rose-400 focus:ring-4 focus:ring-rose-100">{{ old('alamat_sekolah', $settings['alamat_sekolah'] ?? '') }}</textarea>
                        @error('alamat_sekolah')
                            <p class="mt-1.5 text-xs text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="email_sekolah" class="block text-sm font-medium text-slate-700">Email Resmi</label>
                        <input id="email_sekolah"
                               name="email_sekolah"
                               type="email"
                               value="{{ old('email_sekolah', $settings['email_sekolah'] ?? '') }}"
                               class="mt-1.5 block w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm shadow-sm outline-none transition placeholder:text-slate-400 focus:border-rose-400 focus:ring-4 focus:ring-rose-100">
                        @error('email_sekolah')
                            <p class="mt-1.5 text-xs text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="wa_pusat" class="block text-sm font-medium text-slate-700">Nomor WhatsApp Pusat</label>
                        <input id="wa_pusat"
                               name="wa_pusat"
                               type="text"
                               value="{{ old('wa_pusat', $settings['wa_pusat'] ?? '') }}"
                               placeholder="628115917772"
                               class="mt-1.5 block w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm shadow-sm outline-none transition placeholder:text-slate-400 focus:border-rose-400 focus:ring-4 focus:ring-rose-100">
                        <p class="mt-1 text-[11px] text-slate-400">Gunakan format 08xx atau 628xx.</p>
                        @error('wa_pusat')
                            <p class="mt-1.5 text-xs text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="sm:col-span-2">
                        <label for="maps_embed" class="block text-sm font-medium text-slate-700">Embed Google Maps</label>
                        <textarea id="maps_embed"
                                  name="maps_embed"
                                  rows="3"
                                  placeholder="Tempelkan kode embed atau URL Google Maps di sini..."
                                  class="mt-1.5 block w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 font-mono text-xs leading-relaxed shadow-sm outline-none transition placeholder:text-slate-400 focus:border-rose-400 focus:ring-4 focus:ring-rose-100">{{ old('maps_embed', $settings['maps_embed'] ?? '') }}</textarea>
                        @error('maps_embed')
                            <p class="mt-1.5 text-xs text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </section>

            <section class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm sm:p-6">
                <div class="flex items-start gap-2.5 sm:items-center sm:gap-2">
                    <span class="grid size-8 shrink-0 place-items-center rounded-lg bg-gradient-to-br from-fuchsia-600 via-rose-500 to-amber-400 text-white">
                        <svg class="size-4" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path d="M12 2.163c3.204 0 3.584.012 4.85.07 1.366.062 2.633.336 3.608 1.31.975.976 1.249 2.242 1.31 3.609.058 1.265.07 1.645.07 4.85s-.012 3.584-.07 4.85c-.061 1.366-.335 2.633-1.31 3.608-.975.975-2.242 1.249-3.608 1.31-1.266.058-1.646.07-4.85.07s-3.584-.012-4.85-.07c-1.366-.061-2.633-.335-3.608-1.31-.975-.975-1.249-2.242-1.31-3.608-.058-1.266-.07-1.646-.07-4.85s.012-3.585.07-4.85c.061-1.367.335-2.633 1.31-3.609.975-.974 2.242-1.248 3.608-1.31 1.266-.058 1.646-.07 4.85-.07Zm0 1.802c-3.15 0-3.523.012-4.766.069-.953.043-1.47.203-1.815.337-.456.177-.782.389-1.124.73-.341.342-.553.668-.73 1.124-.134.345-.294.862-.337 1.815-.057 1.243-.069 1.616-.069 4.766s.012 3.523.069 4.766c.043.953.203 1.47.337 1.815.177.456.389.782.73 1.124.342.341.668.553 1.124.73.345.134.862.294 1.815.337 1.243.057 1.616.069 4.766.069s3.523-.012 4.766-.069c.953-.043 1.47-.203 1.815-.337.456-.177.782-.389 1.124-.73.341-.342.553-.668.73-1.124.134-.345.294-.862.337-1.815.057-1.243.069-1.616.069-4.766s-.012-3.523-.069-4.766c-.043-.953-.203-1.47-.337-1.815a3.02 3.02 0 0 0-.73-1.124 3.02 3.02 0 0 0-1.124-.73c-.345-.134-.862-.294-1.815-.337-1.243-.057-1.616-.069-4.766-.069Zm0 3.067a5.135 5.135 0 1 1 0 10.27 5.135 5.135 0 0 1 0-10.27Zm0 1.802a3.333 3.333 0 1 0 0 6.666 3.333 3.333 0 0 0 0-6.666Zm5.338-3.205a1.2 1.2 0 1 1 0 2.4 1.2 1.2 0 0 1 0-2.4Z" />
                        </svg>
                    </span>
                    <div>
                        <h2 class="text-sm font-semibold text-slate-900">Link Sosial Media Resmi — Umum</h2>
                        <p class="text-xs text-slate-500">Akun resmi sekolah yang tampil di footer dan halaman utama.</p>
                    </div>
                </div>

                <div class="mt-5 grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
                    <div>
                        <label for="instagram_username" class="block text-sm font-medium text-slate-700">Username Instagram</label>
                        <input id="instagram_username"
                               name="instagram_username"
                               type="text"
                               value="{{ old('instagram_username', $settings['instagram_username'] ?? '') }}"
                               placeholder="cahayailmu.sch"
                               class="mt-1.5 block w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm shadow-sm outline-none transition placeholder:text-slate-400 focus:border-rose-400 focus:ring-4 focus:ring-rose-100">
                        @error('instagram_username')
                            <p class="mt-1.5 text-xs text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="instagram_url" class="block text-sm font-medium text-slate-700">Link Instagram</label>
                        <input id="instagram_url"
                               name="instagram_url"
                               type="url"
                               value="{{ old('instagram_url', $settings['instagram_url'] ?? '') }}"
                               placeholder="https://www.instagram.com/cahayailmu.sch"
                               class="mt-1.5 block w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm shadow-sm outline-none transition placeholder:text-slate-400 focus:border-rose-400 focus:ring-4 focus:ring-rose-100">
                        @error('instagram_url')
                            <p class="mt-1.5 text-xs text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="facebook_url" class="block text-sm font-medium text-slate-700">Link Facebook</label>
                        <input id="facebook_url"
                               name="facebook_url"
                               type="url"
                               value="{{ old('facebook_url', $settings['facebook_url'] ?? '') }}"
                               placeholder="https://www.facebook.com/..."
                               class="mt-1.5 block w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm shadow-sm outline-none transition placeholder:text-slate-400 focus:border-rose-400 focus:ring-4 focus:ring-rose-100">
                        @error('facebook_url')
                            <p class="mt-1.5 text-xs text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="tiktok_url" class="block text-sm font-medium text-slate-700">Link TikTok</label>
                        <input id="tiktok_url"
                               name="tiktok_url"
                               type="url"
                               value="{{ old('tiktok_url', $settings['tiktok_url'] ?? '') }}"
                               placeholder="https://www.tiktok.com/@..."
                               class="mt-1.5 block w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm shadow-sm outline-none transition placeholder:text-slate-400 focus:border-rose-400 focus:ring-4 focus:ring-rose-100">
                        @error('tiktok_url')
                            <p class="mt-1.5 text-xs text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="youtube_channel_name" class="block text-sm font-medium text-slate-700">Nama Channel YouTube</label>
                        <input id="youtube_channel_name"
                               name="youtube_channel_name"
                               type="text"
                               value="{{ old('youtube_channel_name', $settings['youtube_channel_name'] ?? '') }}"
                               placeholder="Cahaya Ilmu Islamic School"
                               class="mt-1.5 block w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm shadow-sm outline-none transition placeholder:text-slate-400 focus:border-rose-400 focus:ring-4 focus:ring-rose-100">
                        @error('youtube_channel_name')
                            <p class="mt-1.5 text-xs text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="youtube_url" class="block text-sm font-medium text-slate-700">Link YouTube</label>
                        <input id="youtube_url"
                               name="youtube_url"
                               type="url"
                               value="{{ old('youtube_url', $settings['youtube_url'] ?? '') }}"
                               placeholder="https://www.youtube.com/@cahayailmu"
                               class="mt-1.5 block w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm shadow-sm outline-none transition placeholder:text-slate-400 focus:border-rose-400 focus:ring-4 focus:ring-rose-100">
                        @error('youtube_url')
                            <p class="mt-1.5 text-xs text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </section>

            <section class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm sm:p-6">
                <div class="flex items-start gap-2.5 sm:items-center sm:gap-2">
                    <span class="grid size-8 shrink-0 place-items-center rounded-lg bg-rose-50 text-rose-600">
                        <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z" />
                        </svg>
                    </span>
                    <div>
                        <h2 class="text-sm font-semibold text-slate-900">Link Sosial Media per Jenjang</h2>
                        <p class="text-xs text-slate-500">Akun Instagram, YouTube, Facebook, dan TikTok resmi untuk setiap jenjang.</p>
                    </div>
                </div>

                <div class="mt-5 grid gap-4 xl:grid-cols-2">
                    @foreach (['tk' => 'TK', 'sd' => 'SD', 'smp' => 'SMP', 'sma' => 'SMA'] as $key => $label)
                        <div class="rounded-xl border border-slate-200 p-4">
                            <h3 class="text-sm font-semibold text-slate-900">Jenjang {{ $label }}</h3>

                            <div class="mt-4 grid gap-3 sm:grid-cols-2">
                                <div>
                                    <label for="instagram_username_{{ $key }}" class="block text-xs font-medium text-slate-600">Username Instagram {{ $label }}</label>
                                    <input id="instagram_username_{{ $key }}"
                                           name="instagram_username_{{ $key }}"
                                           type="text"
                                           value="{{ old("instagram_username_{$key}", $settings["instagram_username_{$key}"] ?? '') }}"
                                           placeholder="sdislam_cahayailmu"
                                           class="mt-1 block w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm shadow-sm outline-none transition placeholder:text-slate-400 focus:border-rose-400 focus:ring-2 focus:ring-rose-100">
                                    @error("instagram_username_{$key}")
                                        <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="instagram_url_{{ $key }}" class="block text-xs font-medium text-slate-600">Link Instagram {{ $label }}</label>
                                    <input id="instagram_url_{{ $key }}"
                                           name="instagram_url_{{ $key }}"
                                           type="url"
                                           value="{{ old("instagram_url_{$key}", $settings["instagram_url_{$key}"] ?? '') }}"
                                           placeholder="https://www.instagram.com/sdislam_cahayailmu"
                                           class="mt-1 block w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm shadow-sm outline-none transition placeholder:text-slate-400 focus:border-rose-400 focus:ring-2 focus:ring-rose-100">
                                    @error("instagram_url_{$key}")
                                        <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="youtube_{{ $key }}" class="block text-xs font-medium text-slate-600">Link YouTube {{ $label }}</label>
                                    <input id="youtube_{{ $key }}"
                                           name="youtube_{{ $key }}"
                                           type="url"
                                           value="{{ old("youtube_{$key}", $settings["youtube_{$key}"] ?? '') }}"
                                           placeholder="https://www.youtube.com/@namachannel"
                                           class="mt-1 block w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm shadow-sm outline-none transition placeholder:text-slate-400 focus:border-rose-400 focus:ring-2 focus:ring-rose-100">
                                    @error("youtube_{$key}")
                                        <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="facebook_{{ $key }}" class="block text-xs font-medium text-slate-600">Link Facebook {{ $label }}</label>
                                    <input id="facebook_{{ $key }}"
                                           name="facebook_{{ $key }}"
                                           type="url"
                                           value="{{ old("facebook_{$key}", $settings["facebook_{$key}"] ?? '') }}"
                                           placeholder="https://www.facebook.com/..."
                                           class="mt-1 block w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm shadow-sm outline-none transition placeholder:text-slate-400 focus:border-rose-400 focus:ring-2 focus:ring-rose-100">
                                    @error("facebook_{$key}")
                                        <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="sm:col-span-2">
                                    <label for="tiktok_{{ $key }}" class="block text-xs font-medium text-slate-600">Link TikTok {{ $label }}</label>
                                    <input id="tiktok_{{ $key }}"
                                           name="tiktok_{{ $key }}"
                                           type="url"
                                           value="{{ old("tiktok_{$key}", $settings["tiktok_{$key}"] ?? '') }}"
                                           placeholder="https://www.tiktok.com/@..."
                                           class="mt-1 block w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm shadow-sm outline-none transition placeholder:text-slate-400 focus:border-rose-400 focus:ring-2 focus:ring-rose-100">
                                    @error("tiktok_{$key}")
                                        <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <p class="mt-3 text-[11px] text-slate-400">Username Instagram boleh dengan atau tanpa tanda @.</p>
            </section>

            <section class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm sm:p-6">
                <div class="flex items-start gap-2.5 sm:items-center sm:gap-2">
                    <span class="grid size-8 shrink-0 place-items-center rounded-lg bg-rose-50 text-rose-600">
                        <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 0 0 1.5-1.5V6a1.5 1.5 0 0 0-1.5-1.5H3.75A1.5 1.5 0 0 0 2.25 6v12a1.5 1.5 0 0 0 1.5 1.5Zm10.5-11.25h.008v.008h-.008V8.25Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                        </svg>
                    </span>
                    <div>
                        <h2 class="text-sm font-semibold text-slate-900">Gambar Hero / Banner Profil</h2>
                        <p class="text-xs text-slate-500">Unggah banner utama dan foto hero halaman profil jenjang. Otomatis dikompres.</p>
                    </div>
                </div>

                @php
                    $bannerPath = $settings['banner_image'] ?? null;
                    $bannerUrl = $bannerPath
                        ? (str_starts_with($bannerPath, 'http') || str_starts_with($bannerPath, '/') ? $bannerPath : \Illuminate\Support\Facades\Storage::disk('public')->url($bannerPath))
                        : asset('assets/images/gedung-beranda.jpg');
                    $profilePath = $settings['profile_image'] ?? null;
                    $profileUrl = $profilePath
                        ? (str_starts_with($profilePath, 'http') || str_starts_with($profilePath, '/') ? $profilePath : \Illuminate\Support\Facades\Storage::disk('public')->url($profilePath))
                        : asset('assets/images/logo-utama.png');
                @endphp

                <div class="mt-5 grid gap-4 sm:grid-cols-2">
                    <div class="rounded-xl border border-slate-200 p-4">
                        <h3 class="text-sm font-semibold text-slate-900">Banner Utama</h3>
                        <div class="mt-3 overflow-hidden rounded-xl border border-slate-200 bg-slate-100">
                            <img id="preview_banner_image" src="{{ $bannerUrl }}" alt="Pratinjau Banner Utama" class="aspect-[16/9] w-full object-cover">
                        </div>
                        <div class="mt-3">
                            <label for="banner_image" class="block text-xs font-medium text-slate-600">Ganti Banner Utama</label>
                            <input id="banner_image"
                                   name="banner_image"
                                   type="file"
                                   accept="image/jpeg,image/png,image/webp"
                                   onchange="previewHeroImage(this, 'preview_banner_image')"
                                   class="mt-1.5 block w-full text-xs text-slate-500 file:mr-2.5 file:rounded-lg file:border-0 file:bg-slate-100 file:px-2.5 file:py-1.5 file:text-xs file:font-semibold file:text-slate-700 hover:file:bg-slate-200">
                            <p class="mt-1 text-[11px] text-slate-400">JPG, PNG, atau WEBP. Maks. 6 MB.</p>
                            @error('banner_image')
                                <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>
                        @if (! empty($settings['banner_image']))
                            <label class="mt-3 flex cursor-pointer items-center gap-2 border-t border-slate-100 pt-3 text-xs text-slate-600">
                                <input type="checkbox" name="remove_banner_image" value="1" class="size-4 rounded border-slate-300 text-rose-600 focus:ring-rose-500">
                                <span>Kembalikan ke banner default</span>
                            </label>
                        @endif
                    </div>

                    <div class="rounded-xl border border-slate-200 p-4">
                        <h3 class="text-sm font-semibold text-slate-900">Foto Profil / Banner Profil</h3>
                        <div class="mt-3 overflow-hidden rounded-xl border border-slate-200 bg-slate-100">
                            <img id="preview_profile_image" src="{{ $profileUrl }}" alt="Pratinjau Foto Profil" class="aspect-[16/9] w-full object-cover">
                        </div>
                        <div class="mt-3">
                            <label for="profile_image" class="block text-xs font-medium text-slate-600">Ganti Foto Profil</label>
                            <input id="profile_image"
                                   name="profile_image"
                                   type="file"
                                   accept="image/jpeg,image/png,image/webp"
                                   onchange="previewHeroImage(this, 'preview_profile_image')"
                                   class="mt-1.5 block w-full text-xs text-slate-500 file:mr-2.5 file:rounded-lg file:border-0 file:bg-slate-100 file:px-2.5 file:py-1.5 file:text-xs file:font-semibold file:text-slate-700 hover:file:bg-slate-200">
                            <p class="mt-1 text-[11px] text-slate-400">JPG, PNG, atau WEBP. Maks. 6 MB.</p>
                            @error('profile_image')
                                <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>
                        @if (! empty($settings['profile_image']))
                            <label class="mt-3 flex cursor-pointer items-center gap-2 border-t border-slate-100 pt-3 text-xs text-slate-600">
                                <input type="checkbox" name="remove_profile_image" value="1" class="size-4 rounded border-slate-300 text-rose-600 focus:ring-rose-500">
                                <span>Kembalikan ke foto default</span>
                            </label>
                        @endif
                    </div>

                    @foreach (['tk' => 'TK', 'sd' => 'SD', 'smp' => 'SMP', 'sma' => 'SMA'] as $key => $label)
                        @php
                            $heroUrl = \App\Models\SchoolSetting::jenjangHeroImage($key, $settings);
                            $hasCustomHero = ! empty($settings['hero_image_'.$key]);
                        @endphp
                        <div class="flex flex-col justify-between rounded-xl border border-slate-200 p-4">
                            <div>
                                <div class="flex items-center justify-between gap-2">
                                    <h3 class="text-sm font-semibold text-slate-900">Foto Hero {{ $label }}</h3>
                                    @if ($hasCustomHero)
                                        <span class="rounded-md bg-rose-50 px-2 py-0.5 text-[10px] font-bold text-rose-700">Kustom</span>
                                    @else
                                        <span class="rounded-md bg-slate-100 px-2 py-0.5 text-[10px] font-semibold text-slate-600">Default</span>
                                    @endif
                                </div>

                                <div class="mt-3 overflow-hidden rounded-xl border border-slate-200 bg-slate-100">
                                    <img id="preview_hero_{{ $key }}"
                                         src="{{ $heroUrl }}"
                                         alt="Pratinjau Foto Hero {{ $label }}"
                                         class="aspect-[16/9] w-full object-cover">
                                </div>

                                <div class="mt-3">
                                    <label for="hero_image_{{ $key }}" class="block text-xs font-medium text-slate-600">
                                        Ganti Foto Hero {{ $label }}
                                    </label>
                                    <input id="hero_image_{{ $key }}"
                                           name="hero_image_{{ $key }}"
                                           type="file"
                                           accept="image/jpeg,image/png,image/webp"
                                           onchange="previewHeroImage(this, 'preview_hero_{{ $key }}')"
                                           class="mt-1.5 block w-full text-xs text-slate-500 file:mr-2.5 file:rounded-lg file:border-0 file:bg-slate-100 file:px-2.5 file:py-1.5 file:text-xs file:font-semibold file:text-slate-700 hover:file:bg-slate-200">
                                    <p class="mt-1 text-[11px] text-slate-400">JPG, PNG, atau WEBP. Maks. 6 MB.</p>
                                    @error("hero_image_{$key}")
                                        <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            @if ($hasCustomHero)
                                <div class="mt-3 border-t border-slate-100 pt-3">
                                    <label class="flex cursor-pointer items-center gap-2 text-xs text-slate-600">
                                        <input type="checkbox" name="remove_hero_image_{{ $key }}" value="1" class="size-4 rounded border-slate-300 text-rose-600 focus:ring-rose-500">
                                        <span>Kembalikan ke foto default</span>
                                    </label>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </section>

            <section class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm sm:p-6">
                <div class="flex items-start gap-2.5 sm:items-center sm:gap-2">
                    <span class="grid size-8 shrink-0 place-items-center rounded-lg bg-fuchsia-50 text-fuchsia-600">
                        <svg class="size-4" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path d="M12 2.163c3.204 0 3.584.012 4.85.07 1.366.062 2.633.336 3.608 1.31.975.976 1.249 2.242 1.31 3.609.058 1.265.07 1.645.07 4.85s-.012 3.584-.07 4.85c-.061 1.366-.335 2.633-1.31 3.608-.975.975-2.242 1.249-3.608 1.31-1.266.058-1.646.07-4.85.07s-3.584-.012-4.85-.07c-1.366-.061-2.633-.335-3.608-1.31-.975-.975-1.249-2.242-1.31-3.608-.058-1.266-.07-1.646-.07-4.85s.012-3.585.07-4.85c.061-1.367.335-2.633 1.31-3.609.975-.974 2.242-1.248 3.608-1.31 1.266-.058 1.646-.07 4.85-.07Z" />
                        </svg>
                    </span>
                    <div>
                        <h2 class="text-sm font-semibold text-slate-900">Link Postingan Instagram per Jenjang</h2>
                        <p class="text-xs text-slate-500">Sematan otomatis untuk maksimal 3 postingan atau reels terbaru pada halaman detail jenjang.</p>
                    </div>
                </div>

                <div class="mt-5 grid gap-4 xl:grid-cols-2">
                    @foreach (['tk' => 'TK', 'sd' => 'SD', 'smp' => 'SMP', 'sma' => 'SMA'] as $key => $label)
                        <div class="rounded-xl border border-slate-200 p-4">
                            <div class="flex items-center gap-2">
                                <i class="fa-brands fa-instagram text-fuchsia-600" aria-hidden="true"></i>
                                <h3 class="text-sm font-semibold text-slate-900">Jenjang {{ $label }}</h3>
                            </div>
                            <p class="mt-1 text-[11px] text-slate-400">Kosongkan kolom yang tidak digunakan.</p>

                            <div class="mt-3 space-y-3">
                                @for ($index = 1; $index <= 3; $index++)
                                    <div>
                                        <label for="instagram_post_{{ $key }}_{{ $index }}" class="block text-xs font-medium text-slate-600">
                                            Link Postingan / Reels {{ $index }} {{ $label }}
                                        </label>
                                        <input id="instagram_post_{{ $key }}_{{ $index }}"
                                               name="instagram_post_{{ $key }}_{{ $index }}"
                                               type="url"
                                               value="{{ old("instagram_post_{$key}_{$index}", $settings["instagram_post_{$key}_{$index}"] ?? '') }}"
                                               placeholder="https://www.instagram.com/p/XXXXXXXXXXX/"
                                               class="mt-1 block w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm shadow-sm outline-none transition placeholder:text-slate-400 focus:border-rose-400 focus:ring-2 focus:ring-rose-100">
                                        @error("instagram_post_{$key}_{$index}")
                                            <p class="mt-1 text-xs text-rose-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                @endfor
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>

            <section class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm sm:p-6">
                <div class="flex items-start gap-2.5 sm:items-center sm:gap-2">
                    <span class="grid size-8 shrink-0 place-items-center rounded-lg bg-amber-50 text-amber-600">
                        <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z" />
                        </svg>
                    </span>
                    <div>
                        <h2 class="text-sm font-semibold text-slate-900">Statistik Sekolah</h2>
                        <p class="text-xs text-slate-500">Angka yang tampil di halaman utama dan profil sekolah.</p>
                    </div>
                </div>

                <div class="mt-5 grid gap-4 sm:grid-cols-2">
                    <div>
                        <label for="jumlah_siswa" class="block text-sm font-medium text-slate-700">Jumlah Siswa</label>
                        <input id="jumlah_siswa"
                               name="jumlah_siswa"
                               type="number"
                               min="0"
                               placeholder="0"
                               value="{{ old('jumlah_siswa', $settings['jumlah_siswa'] ?? '') }}"
                               class="mt-1.5 block w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm shadow-sm outline-none transition placeholder:text-slate-400 focus:border-rose-400 focus:ring-4 focus:ring-rose-100">
                        @error('jumlah_siswa')
                            <p class="mt-1.5 text-xs text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="jumlah_guru" class="block text-sm font-medium text-slate-700">Jumlah Guru</label>
                        <input id="jumlah_guru"
                               name="jumlah_guru"
                               type="number"
                               min="0"
                               placeholder="0"
                               value="{{ old('jumlah_guru', $settings['jumlah_guru'] ?? '') }}"
                               class="mt-1.5 block w-full rounded-xl border border-slate-200 bg-white px-3.5 py-2.5 text-sm shadow-sm outline-none transition placeholder:text-slate-400 focus:border-rose-400 focus:ring-4 focus:ring-rose-100">
                        @error('jumlah_guru')
                            <p class="mt-1.5 text-xs text-rose-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </section>

            <div class="sticky bottom-4 z-10 flex flex-col gap-3 rounded-2xl border border-slate-200 bg-white/95 p-4 shadow-lg backdrop-blur sm:flex-row sm:items-center sm:justify-between xl:bg-white xl:shadow-sm">
                <p class="text-[11px] leading-relaxed text-slate-400">
                    Perubahan langsung diterapkan ke website setelah disimpan.
                </p>
                <button type="submit"
                        class="inline-flex items-center justify-center gap-2 rounded-xl bg-rose-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-rose-700">
                    <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                    </svg>
                    Simpan Pengaturan Website
                </button>
            </div>
        </form>

        @if ($backupSummary)
            <section class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm sm:p-6">
                <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
                    <div class="flex items-start gap-2.5 sm:items-center sm:gap-2">
                        <span class="grid size-8 shrink-0 place-items-center rounded-lg bg-slate-900 text-white">
                            <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 6.375c0 2.278-3.694 4.125-8.25 4.125S3.75 8.653 3.75 6.375m16.5 0c0-2.278-3.694-4.125-8.25-4.125S3.75 4.097 3.75 6.375m16.5 0v11.25c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125V6.375m16.5 5.625c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125" />
                            </svg>
                        </span>
                        <div>
                            <h2 class="text-sm font-semibold text-slate-900">Cadangan &amp; Keamanan Data (Database Backup)</h2>
                            <p class="text-xs text-slate-500">
                                Unduh salinan seluruh data website dalam satu file
                                <code class="rounded bg-slate-100 px-1 py-0.5 text-[11px] font-semibold text-slate-600">.sql</code>.
                            </p>
                        </div>
                    </div>

                    <a href="{{ route('admin.backup.download') }}"
                       class="inline-flex shrink-0 items-center justify-center gap-2 rounded-xl bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-slate-800 focus:outline-none focus-visible:ring-4 focus-visible:ring-slate-200">
                        <svg class="size-4" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
                        </svg>
                        Download Backup Database (.sql)
                    </a>
                </div>

                <div class="mt-5 grid gap-3 sm:grid-cols-3">
                    <div class="rounded-xl border border-slate-200 bg-slate-50/60 px-4 py-3">
                        <p class="text-[10px] font-semibold uppercase tracking-wider text-slate-400">Ukuran Database</p>
                        <p class="mt-1 text-lg font-bold text-slate-900">{{ \Illuminate\Support\Number::fileSize($backupSummary['size']) }}</p>
                        <p class="text-[11px] text-slate-500">Perkiraan ukuran data aktif</p>
                    </div>

                    <div class="rounded-xl border border-slate-200 bg-slate-50/60 px-4 py-3">
                        <p class="text-[10px] font-semibold uppercase tracking-wider text-slate-400">Total Tabel</p>
                        <p class="mt-1 text-lg font-bold text-slate-900">{{ number_format($backupSummary['tables']) }}</p>
                        <p class="text-[11px] text-slate-500">Seluruh tabel ikut dicadangkan</p>
                    </div>

                    <div class="rounded-xl border border-slate-200 bg-slate-50/60 px-4 py-3">
                        <p class="text-[10px] font-semibold uppercase tracking-wider text-slate-400">Pendaftar SPMB</p>
                        <p class="mt-1 text-lg font-bold text-slate-900">{{ number_format($backupSummary['registrations']) }}</p>
                        <p class="text-[11px] text-slate-500">Data pendaftar tersimpan saat ini</p>
                    </div>
                </div>

                <div class="mt-4 rounded-xl bg-amber-50 px-3.5 py-3 text-[11px] leading-relaxed text-amber-800 ring-1 ring-inset ring-amber-600/20">
                    <strong>Penting:</strong> file backup memuat data pribadi pendaftar dan akun admin. Simpan di tempat aman,
                    jangan dibagikan, dan unduh hanya saat diperlukan.
                </div>

                <p class="mt-3 text-[11px] text-slate-400">
                    Nama file otomatis:
                    <span class="font-semibold text-slate-500">backup-cahayailmu-YYYY-MM-DD_HH-mm.sql</span>
                    &middot; Driver: {{ strtoupper($backupSummary['driver']) }}
                </p>
            </section>
        @endif
    </div>
@endsection

@push('scripts')
<script>
    function previewHeroImage(input, previewId) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const img = document.getElementById(previewId);
                if (img) {
                    img.src = e.target.result;
                }
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endpush
