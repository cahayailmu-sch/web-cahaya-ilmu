<div id="wa-widget" class="wa-widget">
  <div id="wa-menu" class="wa-menu" role="dialog" aria-label="Pilih admin WhatsApp SPMB" hidden>
    <div class="wa-menu-head">
      <span class="wa-menu-head-icon"><i class="fa-brands fa-whatsapp" aria-hidden="true"></i></span>
      <div>
        <p class="wa-menu-title">Hubungi Admin SPMB</p>
        <p class="wa-menu-subtitle">Pilih jenjang untuk chat langsung</p>
      </div>
      <button id="wa-menu-close" type="button" class="wa-menu-close" aria-label="Tutup pilihan WhatsApp">
        <i class="fa-solid fa-xmark" aria-hidden="true"></i>
      </button>
    </div>

    <div class="wa-menu-list">
      @foreach ($contacts as $contact)
        <a href="{{ $contact['wa_url'] !== '' ? $contact['wa_url'] : '#' }}"
           class="wa-option"
           data-wa-jenjang="{{ $contact['level'] }}">
          <span class="wa-option-icon" aria-hidden="true">{{ $contact['level_label'] }}</span>
          <span class="wa-option-text">
            <span class="wa-option-title">Admin {{ $contact['name'] }}</span>
            <span class="wa-option-subtitle">Admin SPMB &middot; {{ $contact['wa_display'] }}</span>
          </span>
          <i class="fa-brands fa-whatsapp wa-option-arrow" aria-hidden="true"></i>
        </a>
      @endforeach
    </div>

    <p class="wa-menu-foot">Senin&ndash;Jumat, 07.00&ndash;16.00 WITA</p>
  </div>

  <button id="wa-toggle" type="button" class="wa-toggle" aria-expanded="false" aria-controls="wa-menu" aria-haspopup="dialog" aria-label="Buka pilihan WhatsApp admin SPMB">
    <i class="fa-brands fa-whatsapp" aria-hidden="true"></i>
  </button>
</div>
