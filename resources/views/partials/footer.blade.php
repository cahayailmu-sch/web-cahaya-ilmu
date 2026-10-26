<footer id="site-footer" class="footer relative overflow-hidden">
  <div class="footer-pattern absolute inset-0" aria-hidden="true"></div>

  <div class="relative mx-auto max-w-7xl px-4 pb-8 pt-12 sm:px-6 sm:pt-16 lg:px-8">
    <div class="grid grid-cols-2 gap-8 sm:gap-10 xl:grid-cols-[1.1fr_0.7fr_0.7fr_1.5fr]">
      <div class="col-span-2 xl:col-span-1">
        <a href="{{ route('home') }}" class="flex items-center gap-3" aria-label="Cahaya Ilmu Islamic School Balikpapan">
          <span class="grid h-14 w-24 shrink-0 place-items-center overflow-hidden rounded-2xl border border-white/10 bg-white p-2 shadow-sm sm:h-16 sm:w-28">
            <img src="/assets/images/logo-utama.png" alt="Logo Cahaya Ilmu Islamic School" loading="lazy" decoding="async" class="h-full w-full object-contain">
          </span>
          <span class="min-w-0">
            <span class="block text-[9px] font-extrabold uppercase tracking-[0.22em] text-gold-400">Cahaya Ilmu</span>
            <span class="mt-1 block truncate text-base font-extrabold tracking-tight text-white sm:text-lg">Islamic School</span>
            <span class="mt-1 block text-[10px] font-medium text-slate-400">Balikpapan</span>
          </span>
        </a>

        <p class="mt-5 text-sm font-medium italic leading-6 text-amber-400">
          &ldquo;{{ $motto ?? config('sekolah.motto') }}&rdquo;
        </p>

        <p class="mt-3 max-w-sm text-sm leading-7 text-slate-200/70">
          Lembaga pendidikan Islam terpadu (TK, SD, SMP, SMA) di bawah naungan {{ $foundationName }}.
        </p>

        <ul class="mt-5 space-y-3 text-xs leading-6 text-slate-200/65">
          <li class="flex gap-3">
            <i class="fa-solid fa-location-dot mt-1 text-gold-400" aria-hidden="true"></i>
            <span>{!! nl2br(e($settings['alamat_sekolah'] ?? 'Jl. Syarifuddin Yoes RT. 41 komp. Masjid Al imam an Nasai Kel. Gunung bahagia kec. Balikpapan Selatan 76114')) !!}</span>
          </li>
          <li class="flex gap-3">
            <i class="fa-solid fa-envelope mt-1 shrink-0 text-gold-400" aria-hidden="true"></i>
            <span class="flex min-w-0 flex-wrap gap-x-3 gap-y-1">
              <a href="mailto:{{ trim((string) ($settings['email_sekolah'] ?? '')) ?: 'sdicahayailmu@gmail.com' }}" class="footer-link break-all">{{ trim((string) ($settings['email_sekolah'] ?? '')) ?: 'sdicahayailmu@gmail.com' }}</a>
              <a href="mailto:smpicahayailmu@gmail.com" class="footer-link break-all">smpicahayailmu@gmail.com</a>
              <a href="mailto:smaicahyailmu@gmail.com" class="footer-link break-all">smaicahyailmu@gmail.com</a>
            </span>
          </li>
        </ul>

        @if (! empty($youtube['url']))
          <a href="{{ $youtube['url'] }}" target="_blank" rel="noopener noreferrer" class="social-link mt-6" aria-label="YouTube {{ $youtube['name'] ?: 'Cahaya Ilmu Islamic School' }}">
            <i class="fa-brands fa-youtube" aria-hidden="true"></i>
          </a>
        @endif
      </div>

      <div>
        <h2 class="footer-title">Jelajahi</h2>
        <ul class="mt-5 space-y-3 text-sm">
          <li><a href="{{ route('home') }}" class="footer-link">Beranda</a></li>
          <li><a href="{{ route('about') }}" class="footer-link">Tentang Kami</a></li>
          <li><a href="{{ route('programs') }}" class="footer-link">Program Unggulan</a></li>
          <li><a href="{{ route('gallery.index') }}" class="footer-link">Galeri Sekolah</a></li>
          <li><a href="{{ route('info.index') }}" class="footer-link">Info Sekolah</a></li>
          <li><a href="{{ route('home') }}#faq" class="footer-link">FAQ</a></li>
          <li><a href="{{ route('ppdb') }}" class="footer-link">SPMB</a></li>
        </ul>
      </div>

      <div>
        <h2 class="footer-title">Jenjang</h2>
        <ul class="mt-5 space-y-3 text-sm">
          @foreach ($contacts as $contact)
            <li>
              <a href="{{ route('jenjang.show', strtolower($contact['level'])) }}" class="footer-link">
                {{ $contact['name'] }}
              </a>
            </li>
          @endforeach
        </ul>
      </div>

      <div class="col-span-2 xl:col-span-1">
        <h2 class="footer-title">Kontak &amp; Media Sosial</h2>

        <div class="mt-5 space-y-2.5">
          @foreach ($contacts as $contact)
            <div class="rounded-xl border border-white/10 bg-white/[0.04] px-3 py-2.5">
              <div class="flex items-center justify-between gap-3">
                <p class="text-[11px] font-extrabold text-white">Admin {{ $contact['level_label'] }}</p>
                <div class="flex items-center gap-2 text-sm">
                  @if ($contact['wa_url'] !== '')
                    <a href="{{ $contact['wa_url'] }}" target="_blank" rel="noopener noreferrer" class="text-emerald-400 transition hover:text-emerald-300" aria-label="WhatsApp {{ $contact['name'] }}">
                      <i class="fa-brands fa-whatsapp" aria-hidden="true"></i>
                    </a>
                  @endif
                  @if ($contact['instagram_url'] !== '')
                    <a href="{{ $contact['instagram_url'] }}" target="_blank" rel="noopener noreferrer" class="text-fuchsia-300 transition hover:text-fuchsia-200" aria-label="Instagram {{ $contact['name'] }}">
                      <i class="fa-brands fa-instagram" aria-hidden="true"></i>
                    </a>
                  @endif
                  @if ($contact['youtube_url'] !== '')
                    <a href="{{ $contact['youtube_url'] }}" target="_blank" rel="noopener noreferrer" class="text-red-400 transition hover:text-red-300" aria-label="YouTube {{ $contact['name'] }}">
                      <i class="fa-brands fa-youtube" aria-hidden="true"></i>
                    </a>
                  @endif
                </div>
              </div>

              <div class="mt-1.5 flex flex-wrap items-center gap-x-3 gap-y-1 text-[11px]">
                @if ($contact['wa_url'] !== '')
                  <a href="{{ $contact['wa_url'] }}" target="_blank" rel="noopener noreferrer" class="footer-link">
                    {{ $contact['wa_display'] }}
                  </a>
                @endif
                @if ($contact['instagram_url'] !== '')
                  <a href="{{ $contact['instagram_url'] }}" target="_blank" rel="noopener noreferrer" class="footer-link">
                    {{ $contact['instagram_username'] }}
                  </a>
                @endif
                @if ($contact['youtube_url'] !== '')
                  <a href="{{ $contact['youtube_url'] }}" target="_blank" rel="noopener noreferrer" class="footer-link">
                    {{ $contact['youtube_handle'] ?: 'YouTube '.$contact['level_label'] }}
                  </a>
                @endif
              </div>
            </div>
          @endforeach
        </div>

        @php($youtubeContacts = collect($contacts)->filter(fn ($contact) => $contact['youtube_url'] !== ''))
        @if ($youtubeContacts->isNotEmpty())
          <div class="mt-4 rounded-xl border border-red-400/20 bg-red-500/10 px-3 py-3">
            <p class="flex items-center gap-2 text-[9px] font-extrabold uppercase tracking-[0.14em] text-red-300">
              <i class="fa-brands fa-youtube text-sm" aria-hidden="true"></i> YouTube Resmi per Jenjang
            </p>
            <div class="mt-2 grid gap-1.5 sm:grid-cols-2">
              @foreach ($youtubeContacts as $contact)
                <a href="{{ $contact['youtube_url'] }}" target="_blank" rel="noopener noreferrer" class="footer-link flex items-center justify-between gap-2 rounded-lg bg-white/[0.04] px-2.5 py-1.5">
                  <span class="font-semibold text-white">{{ $contact['level_label'] }}</span>
                  <span class="truncate text-[10px]">{{ $contact['youtube_handle'] ?: 'Buka channel' }}</span>
                </a>
              @endforeach
            </div>
          </div>
        @endif

        @if (! empty($youtube['url']))
          <a href="{{ $youtube['url'] }}" target="_blank" rel="noopener noreferrer" class="mt-4 flex items-center gap-3 rounded-xl border border-red-400/20 bg-red-500/10 px-3 py-2.5 transition hover:border-red-400/40 hover:bg-red-500/15">
            <span class="grid size-8 shrink-0 place-items-center rounded-lg bg-red-500 text-sm text-white">
              <i class="fa-brands fa-youtube" aria-hidden="true"></i>
            </span>
            <span class="min-w-0">
              <span class="block text-[9px] font-extrabold uppercase tracking-[0.14em] text-red-300">YouTube Resmi</span>
              <span class="mt-0.5 block truncate text-[11px] font-bold text-white">{{ $youtube['name'] ?: 'Cahaya Ilmu Islamic School' }}</span>
            </span>
          </a>
        @endif
      </div>
    </div>

    <div class="mt-12 flex flex-col gap-4 border-t border-white/10 pt-6 text-xs text-slate-200/50 sm:flex-row sm:items-center sm:justify-between">
      <p>© {{ date('Y') }} Cahaya Ilmu Islamic School - {{ $foundationName }}. All rights reserved.</p>
      <a href="#main" class="inline-flex items-center gap-2 font-semibold text-slate-200/60 transition hover:text-gold-400">
        Kembali ke atas <i class="fa-solid fa-arrow-up text-[10px]"></i>
      </a>
    </div>
  </div>
</footer>
