/* ============================================================================
   CAHAYA ILMU ISLAMIC SCHOOL BALIKPAPAN
   File: js/script.js
   ----------------------------------------------------------------------------
   Berisi seluruh logika interaktif halaman:
   1. Menu mobile & navbar sticky
   2. Scroll spy menu aktif
   3. Tab jenjang pendidikan & syarat SPMB (termasuk dukungan keyboard)
   3b. Smooth scroll untuk tautan menu navigasi
   4. Animasi angka statistik
   5. Animasi reveal saat scroll
   6. Lightbox galeri fasilitas
   7. Modal baca berita
   8. Formulir SPMB (validasi) & FAQ accordion
   9. Tombol kembali ke atas & tahun copyright
   10. Menu WhatsApp melayang (pilih admin per jenjang)
   ========================================================================== */

/* ============================================================================
   KONFIGURASI NOMOR WHATSAPP ADMIN SPMB
   ----------------------------------------------------------------------------
   Ganti nomor WhatsApp di bawah ini untuk masing-masing jenjang.
   - Nomor ini dipakai oleh formulir SPMB dan menu WhatsApp melayang.
   - Gunakan format kode negara, tanpa tanda "+", spasi, atau tanda hubung.
   - Contoh: 0812-3456-7890  ->  '6281234567890'
   - Nomor "UMUM" dipakai sebagai cadangan bila jenjang tidak dikenali.
   ========================================================================== */
const ADMIN_WHATSAPP = Object.freeze({
  TK: '628115917772',    // Nomor WhatsApp admin SPMB jenjang TK
  SD: '6285796135987',   // Nomor WhatsApp admin SPMB jenjang SD
  SMP: '6282254424312',  // Nomor WhatsApp admin SPMB jenjang SMP
  SMA: '6285394999283',  // Nomor WhatsApp admin SPMB jenjang SMA
  UMUM: '6285796135987'  // Nomor WhatsApp cadangan / panitia umum
});

(function () {
  'use strict';

  /* Penanda bahwa JavaScript aktif. Dipakai oleh CSS untuk animasi reveal. */
  document.documentElement.classList.add('reveal-enabled');

  /* -------------------------------------------------------------------------
     Helper
     ------------------------------------------------------------------------- */
  const $ = (selector, scope = document) => scope.querySelector(selector);
  const $$ = (selector, scope = document) => Array.from(scope.querySelectorAll(selector));
  const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  const smoothBehavior = prefersReducedMotion ? 'auto' : 'smooth';

  /* Ubah nomor 08... menjadi 628... dan buang karakter selain angka. */
  const normalizeWhatsappNumber = (value) => {
    const digits = String(value || '').replace(/\D/g, '');
    if (!digits) return '';
    return digits.startsWith('0') ? `62${digits.slice(1)}` : digits;
  };

  /* Ambil nomor admin sesuai jenjang dari database (diinjeksi oleh Blade). */
  const getAdminWhatsappNumber = (jenjang) => {
    const key = String(jenjang || '').toUpperCase();
    const configured = window.CI_WHATSAPP || {};
    const number = configured[key] || configured.UMUM || ADMIN_WHATSAPP[key] || ADMIN_WHATSAPP.UMUM;
    return normalizeWhatsappNumber(number);
  };

  /* -------------------------------------------------------------------------
     1. TAHUN COPYRIGHT
     ------------------------------------------------------------------------- */
  const yearElement = $('#current-year');
  if (yearElement) {
    yearElement.textContent = String(new Date().getFullYear());
  }

  /* -------------------------------------------------------------------------
     2. HEADER STICKY & TOMBOL KEMBALI KE ATAS
     ------------------------------------------------------------------------- */
  const header = $('#site-header');
  const backToTop = $('#back-to-top');

  const updateHeaderState = () => {
    if (header) {
      header.classList.toggle('is-scrolled', window.scrollY > 10);
    }
    if (backToTop) {
      backToTop.classList.toggle('is-visible', window.scrollY > 500);
    }
  };

  /* -------------------------------------------------------------------------
     3. MENU MOBILE
     ------------------------------------------------------------------------- */
  const menuToggle = $('#menu-toggle');
  const mobileMenu = $('#mobile-menu');
  const iconOpen = $('#menu-icon-open');
  const iconClose = $('#menu-icon-close');
  let isMenuOpen = false;

  const setMobileMenu = (open) => {
    if (!menuToggle || !mobileMenu) return;

    isMenuOpen = open;
    menuToggle.setAttribute('aria-expanded', String(open));
    mobileMenu.classList.toggle('is-open', open);

    if (open) {
      mobileMenu.style.maxHeight = `${mobileMenu.scrollHeight}px`;
    } else {
      mobileMenu.style.maxHeight = '0px';
    }

    if (iconOpen) iconOpen.classList.toggle('hidden', open);
    if (iconClose) iconClose.classList.toggle('hidden', !open);
  };

  if (menuToggle) {
    menuToggle.addEventListener('click', () => {
      setMobileMenu(!isMenuOpen);
    });
  }

  /* Tutup menu setelah salah satu tautan diklik */
  $$('#mobile-menu a').forEach((link) => {
    link.addEventListener('click', () => setMobileMenu(false));
  });

  /* Tutup menu saat area di luar header diklik */
  document.addEventListener('click', (event) => {
    if (!isMenuOpen || !header) return;
    if (!header.contains(event.target)) {
      setMobileMenu(false);
    }
  });

  /* Tutup dengan tombol Escape */
  document.addEventListener('keydown', (event) => {
    if (event.key === 'Escape' && isMenuOpen) {
      setMobileMenu(false);
      if (menuToggle) menuToggle.focus();
    }
  });

  /* Sesuaikan tinggi menu saat ukuran layar berubah */
  window.addEventListener('resize', () => {
    if (window.innerWidth >= 1024) {
      setMobileMenu(false);
      return;
    }
    if (isMenuOpen) {
      mobileMenu.style.maxHeight = `${mobileMenu.scrollHeight}px`;
    }
  });

  /* Tombol "Jenjang" di navbar desktop: gulir ke section jenjang */
  const jenjangNavButton = $('.nav-dropdown > button');
  if (jenjangNavButton) {
    jenjangNavButton.addEventListener('click', () => {
      const jenjangSection = $('#jenjang');
      if (jenjangSection) {
        jenjangSection.scrollIntoView({ behavior: smoothBehavior, block: 'start' });
      }
    });
  }

  /* -------------------------------------------------------------------------
     3B. SMOOTH SCROLL UNTUK MENU NAVIGASI
     ------------------------------------------------------------------------- */
  $$('a[href*="#"]').forEach((link) => {
    const href = link.getAttribute('href');
    if (!href || href === '#') return;

    const url = new URL(link.href, window.location.href);
    const isSamePage = url.pathname === window.location.pathname && url.search === window.location.search;
    if (!isSamePage || !url.hash) return;

    const targetId = decodeURIComponent(url.hash.slice(1));
    const target = document.getElementById(targetId);
    if (!target) return;

    link.addEventListener('click', (event) => {
      if (event.defaultPrevented || event.button !== 0 || event.metaKey || event.ctrlKey || event.shiftKey || event.altKey) {
        return;
      }

      event.preventDefault();
      target.scrollIntoView({ behavior: smoothBehavior, block: 'start' });

      try {
        window.history.pushState(null, '', `#${targetId}`);
      } catch (error) {
        /* Abaikan bila browser membatasi perubahan URL (mis. file://). */
      }
    });
  });

  /* -------------------------------------------------------------------------
     4. SCROLL SPY (MENU AKTIF)
     ------------------------------------------------------------------------- */
  const navTargets = $$('[data-nav-target]');
  const sectionIds = [...new Set(navTargets.map((item) => item.dataset.navTarget))];
  const observedSections = sectionIds
    .map((id) => document.getElementById(id))
    .filter(Boolean);

  const updateActiveNav = () => {
    if (!observedSections.length) return;

    const scrollPosition = window.scrollY + 160;
    let currentSection = observedSections[0].id;

    observedSections.forEach((section) => {
      if (section.offsetTop <= scrollPosition) {
        currentSection = section.id;
      }
    });

    /* Jika sudah di dasar halaman, aktifkan menu Kontak */
    const nearBottom = window.innerHeight + window.scrollY >= document.documentElement.scrollHeight - 80;
    if (nearBottom) {
      currentSection = 'kontak';
    }

    navTargets.forEach((item) => {
      item.classList.toggle('is-active', item.dataset.navTarget === currentSection);
    });
  };

  /* -------------------------------------------------------------------------
     5. TAB JENJANG PENDIDIKAN
     ------------------------------------------------------------------------- */
  const jenjangTabs = $$('.jenjang-tab');
  const jenjangPanels = $$('.tab-panel');

  const activateJenjangTab = (tab, focusTab = false) => {
    if (!tab) return;

    const targetId = tab.getAttribute('aria-controls');

    jenjangTabs.forEach((item) => {
      const isSelected = item === tab;
      item.setAttribute('aria-selected', String(isSelected));
      item.tabIndex = isSelected ? 0 : -1;
    });

    jenjangPanels.forEach((panel) => {
      panel.hidden = panel.id !== targetId;
    });

    if (focusTab) {
      tab.focus();
    }
  };

  jenjangTabs.forEach((tab, index) => {
    tab.addEventListener('click', () => activateJenjangTab(tab));

    tab.addEventListener('keydown', (event) => {
      let nextIndex = null;

      if (event.key === 'ArrowRight') {
        nextIndex = (index + 1) % jenjangTabs.length;
      } else if (event.key === 'ArrowLeft') {
        nextIndex = (index - 1 + jenjangTabs.length) % jenjangTabs.length;
      } else if (event.key === 'Home') {
        nextIndex = 0;
      } else if (event.key === 'End') {
        nextIndex = jenjangTabs.length - 1;
      }

      if (nextIndex !== null) {
        event.preventDefault();
        activateJenjangTab(jenjangTabs[nextIndex], true);
      }
    });
  });

  /* Tautan "TK/SD/SMP/SMA" di navbar, footer, dan mobile menu */
  $$('[data-jenjang]').forEach((link) => {
    link.addEventListener('click', () => {
      const tab = document.getElementById(`tab-${link.dataset.jenjang}`);
      if (tab) {
        activateJenjangTab(tab);
      }
    });
  });

  /* Mendukung tautan ppdb.html?jenjang=tk / sd / smp / sma */
  const jenjangParam = new URLSearchParams(window.location.search).get('jenjang');
  if (jenjangParam) {
    const initialTab = document.getElementById(`tab-${jenjangParam.toLowerCase()}`);
    if (initialTab) {
      activateJenjangTab(initialTab);
    }
  }

  /* -------------------------------------------------------------------------
     6. ANIMASI ANGKA STATISTIK
     ------------------------------------------------------------------------- */
  const counters = $$('[data-counter]');
  const numberFormatter = new Intl.NumberFormat('id-ID');

  const animateCounter = (element) => {
    const target = Number(element.dataset.counter);
    if (!Number.isFinite(target)) return;

    if (prefersReducedMotion) {
      element.textContent = numberFormatter.format(target);
      return;
    }

    const duration = 1600;
    const startTime = performance.now();

    const step = (currentTime) => {
      const progress = Math.min((currentTime - startTime) / duration, 1);
      const eased = 1 - Math.pow(1 - progress, 4);
      element.textContent = numberFormatter.format(Math.round(target * eased));

      if (progress < 1) {
        window.requestAnimationFrame(step);
      } else {
        element.textContent = numberFormatter.format(target);
      }
    };

    window.requestAnimationFrame(step);
  };

  if (counters.length) {
    if (prefersReducedMotion || !('IntersectionObserver' in window)) {
      counters.forEach((counter) => {
        counter.textContent = numberFormatter.format(Number(counter.dataset.counter));
      });
    } else {
      counters.forEach((counter) => {
        counter.textContent = '0';
      });

      const counterObserver = new IntersectionObserver(
        (entries, observer) => {
          entries.forEach((entry) => {
            if (entry.isIntersecting) {
              animateCounter(entry.target);
              observer.unobserve(entry.target);
            }
          });
        },
        { threshold: 0.35 }
      );

      counters.forEach((counter) => counterObserver.observe(counter));
    }
  }

  /* -------------------------------------------------------------------------
     7. ANIMASI REVEAL SAAT SCROLL
     ------------------------------------------------------------------------- */
  const revealItems = $$('.reveal');

  if (revealItems.length) {
    if (prefersReducedMotion || !('IntersectionObserver' in window)) {
      revealItems.forEach((item) => item.classList.add('is-visible'));
    } else {
      const revealObserver = new IntersectionObserver(
        (entries, observer) => {
          entries.forEach((entry) => {
            if (entry.isIntersecting) {
              entry.target.classList.add('is-visible');
              observer.unobserve(entry.target);
            }
          });
        },
        {
          threshold: 0.1,
          rootMargin: '0px 0px -40px 0px'
        }
      );

      revealItems.forEach((item) => revealObserver.observe(item));

      /* Cadangan: tampilkan elemen yang sudah berada di viewport. */
      window.setTimeout(() => {
        revealItems.forEach((item) => {
          const rect = item.getBoundingClientRect();
          if (rect.top < window.innerHeight && rect.bottom > 0) {
            item.classList.add('is-visible');
          }
        });
      }, 1200);
    }
  }

  /* -------------------------------------------------------------------------
     Helper fokus untuk dialog (lightbox & modal berita)
     ------------------------------------------------------------------------- */
  const trapFocus = (container, event) => {
    if (event.key !== 'Tab' || !container) return;

    const focusableElements = $$(
      'a[href], button:not([disabled]), input:not([disabled]), select:not([disabled]), textarea:not([disabled]), [tabindex]:not([tabindex="-1"])',
      container
    ).filter((element) => element.offsetParent !== null);

    if (!focusableElements.length) return;

    const firstElement = focusableElements[0];
    const lastElement = focusableElements[focusableElements.length - 1];

    if (event.shiftKey && document.activeElement === firstElement) {
      event.preventDefault();
      lastElement.focus();
    } else if (!event.shiftKey && document.activeElement === lastElement) {
      event.preventDefault();
      firstElement.focus();
    }
  };

  /* -------------------------------------------------------------------------
     8. LIGHTBOX GALERI
     ------------------------------------------------------------------------- */
  const galleryItems = $$('[data-gallery]');
  const lightbox = $('#lightbox');
  const lightboxImage = $('#lightbox-image');
  const lightboxCaption = $('#lightbox-caption');
  const lightboxClose = $('.lightbox-close', lightbox || document);
  const lightboxPrev = $('[data-lightbox-prev]', lightbox || document);
  const lightboxNext = $('[data-lightbox-next]', lightbox || document);

  let activeGalleryIndex = 0;
  let lightboxLastFocused = null;

  const renderLightbox = () => {
    const item = galleryItems[activeGalleryIndex];
    if (!item || !lightboxImage) return;

    const image = $('img', item);
    lightboxImage.src = item.dataset.full || (image ? image.src : '');
    lightboxImage.alt = image ? image.alt : 'Foto fasilitas sekolah';
    if (lightboxCaption) {
      lightboxCaption.textContent = item.dataset.caption || (image ? image.alt : '');
    }
  };

  const openLightbox = (index) => {
    if (!lightbox || !lightboxImage) return;

    lightboxLastFocused = document.activeElement;
    activeGalleryIndex = index;
    renderLightbox();
    lightbox.hidden = false;
    lightbox.setAttribute('aria-hidden', 'false');
    document.body.classList.add('no-scroll');

    if (lightboxClose) {
      lightboxClose.focus();
    }
  };

  const closeLightbox = () => {
    if (!lightbox) return;

    lightbox.hidden = true;
    lightbox.setAttribute('aria-hidden', 'true');
    document.body.classList.remove('no-scroll');
    lightboxImage.removeAttribute('src');

    if (lightboxLastFocused && typeof lightboxLastFocused.focus === 'function') {
      lightboxLastFocused.focus();
    }
  };

  const showPreviousImage = () => {
    if (!galleryItems.length) return;
    activeGalleryIndex = (activeGalleryIndex - 1 + galleryItems.length) % galleryItems.length;
    renderLightbox();
  };

  const showNextImage = () => {
    if (!galleryItems.length) return;
    activeGalleryIndex = (activeGalleryIndex + 1) % galleryItems.length;
    renderLightbox();
  };

  if (lightbox && galleryItems.length) {
    lightbox.setAttribute('aria-hidden', 'true');

    galleryItems.forEach((item, index) => {
      item.addEventListener('click', () => openLightbox(index));
    });

    $$('[data-lightbox-close]', lightbox).forEach((button) => {
      button.addEventListener('click', closeLightbox);
    });

    if (lightboxPrev) lightboxPrev.addEventListener('click', showPreviousImage);
    if (lightboxNext) lightboxNext.addEventListener('click', showNextImage);

    document.addEventListener('keydown', (event) => {
      if (lightbox.hidden) return;

      if (event.key === 'Escape') {
        closeLightbox();
      } else if (event.key === 'ArrowLeft') {
        showPreviousImage();
      } else if (event.key === 'ArrowRight') {
        showNextImage();
      } else {
        trapFocus(lightbox, event);
      }
    });
  }

  /* -------------------------------------------------------------------------
     9. MODAL BERITA
     ------------------------------------------------------------------------- */
  const newsTriggers = $$('[data-news-open]');
  const newsModal = $('#news-modal');
  const newsModalTitle = $('#news-modal-title');
  const newsModalCategory = $('#news-modal-category');
  const newsModalDate = $('#news-modal-date');
  const newsModalImage = $('#news-modal-image');
  const newsModalBody = $('#news-modal-body');
  const newsCloseButtons = $$('[data-news-close]');
  const newsModalClose = $('.news-modal-close', newsModal || document);

  let newsLastFocused = null;

  const openNewsModal = (trigger) => {
    if (!newsModal || !newsModalTitle || !newsModalBody) return;

    const template = document.getElementById(trigger.dataset.newsTemplate);

    newsLastFocused = trigger;
    newsModalTitle.textContent = trigger.dataset.newsTitle || 'Berita Sekolah';
    if (newsModalCategory) {
      newsModalCategory.textContent = trigger.dataset.newsCategory || 'Kegiatan';
    }
    if (newsModalDate) {
      const dateText = $('span', newsModalDate);
      if (dateText) {
        dateText.textContent = trigger.dataset.newsDate || '';
      } else {
        newsModalDate.textContent = trigger.dataset.newsDate || '';
      }
      if (trigger.dataset.newsDatetime) {
        newsModalDate.setAttribute('datetime', trigger.dataset.newsDatetime);
      }
    }
    if (newsModalImage) {
      newsModalImage.src = trigger.dataset.newsImage || '';
      newsModalImage.alt = trigger.dataset.newsTitle || 'Dokumentasi kegiatan sekolah';
    }

    newsModalBody.innerHTML = '';
    if (template) {
      newsModalBody.appendChild(template.content.cloneNode(true));
    }

    newsModal.hidden = false;
    newsModal.setAttribute('aria-hidden', 'false');
    document.body.classList.add('no-scroll');
    newsModal.focus();

    if (newsModalClose) {
      newsModalClose.focus();
    }
  };

  const closeNewsModal = () => {
    if (!newsModal) return;

    newsModal.hidden = true;
    newsModal.setAttribute('aria-hidden', 'true');
    document.body.classList.remove('no-scroll');

    if (newsLastFocused && typeof newsLastFocused.focus === 'function') {
      newsLastFocused.focus();
    }
  };

  if (newsModal && newsTriggers.length) {
    newsModal.setAttribute('aria-hidden', 'true');

    newsTriggers.forEach((trigger) => {
      trigger.addEventListener('click', () => openNewsModal(trigger));
    });

    newsCloseButtons.forEach((button) => {
      button.addEventListener('click', closeNewsModal);
    });

    document.addEventListener('keydown', (event) => {
      if (newsModal.hidden) return;

      if (event.key === 'Escape') {
        closeNewsModal();
      } else {
        trapFocus(newsModal, event);
      }
    });
  }

  /* -------------------------------------------------------------------------
     10. FORMULIR SPMB (validasi & sinkronisasi jenjang)
     ------------------------------------------------------------------------- */
  const ppdbForm = $('#ppdb-form');

  if (ppdbForm) {
    const jenjangSelect = $('#jenjang-pilihan');
    const asalSekolahInput = $('#asal-sekolah');
    const asalSekolahHint = $('#asal-sekolah-hint');
    const asalSekolahRequiredMark = $('#asal-sekolah-wajib');
    const birthDateInput = $('#tanggal-lahir');
    const ageWarning = $('#umur-sd-warning');
    const ageWarningText = $('#umur-sd-warning-text');
    const ageValid = $('#umur-sd-valid');
    const ageValidText = $('#umur-sd-valid-text');
    const submitButton = ppdbForm.querySelector('button[type="submit"]');
    const rekeningBox = $('#info-rekening');
    const rekeningBank = $('#rek-bank');
    const rekeningNumber = $('#rek-nomor');
    const rekeningName = $('#rek-nama');
    const rekeningFee = $('#rek-biaya');
    const paymentProofInput = $('#payment-proof');
    const paymentProofRequiredMark = $('#payment-proof-required');
    const paymentProofHint = $('#payment-proof-hint');
    const defaultPaymentProofHint = paymentProofHint ? paymentProofHint.textContent : '';
    const subLevelWrap = $('#sub-level-wrap');
    const subLevelSelect = $('#sub-level');
    const subLevelWarning = $('#sub-level-warning');
    const subLevelWarningText = $('#sub-level-warning-text');
    const subLevelQuotaBadges = $('#sub-level-quota-badges');
    const referralWrap = $('#referral-tk-wrap');
    const referralInput = $('#referral-code');
    const referralButton = $('#check-referral');
    const referralButtonText = $('#check-referral-text');
    const referralStatus = $('#referral-status');
    const paymentProofWrap = $('#payment-proof-wrap');
    const referralEndpoint = (() => {
      const configured = String(window.CI_REFERRAL_ENDPOINT || '').trim();

      try {
        const url = new URL(configured || '/spmb/check-referral', window.location.origin);

        return `${url.pathname}${url.search}`;
      } catch (error) {
        return '/spmb/check-referral';
      }
    })();
    const ppdbLevels = window.CI_PPDB_LEVELS || {};
    const ppdbQuotas = window.CI_PPDB_QUOTA || {};
    const tkConfig = window.CI_SPMB_TK || {};
    const tkGroups = tkConfig.groups || {};
    const quotaDetail = $('#quota-detail');
    const quotaDetailLevel = $('#quota-detail-level');
    const quotaBothFullBanner = $('#quota-both-full-banner');
    const quotaBothFullText = $('#quota-both-full-text');
    const quotaFullWarning = $('#quota-full-warning');
    const quotaFullWarningText = $('#quota-full-warning-text');
    const quotaWaLink = $('#quota-wa-link');
    const quotaGenderGrid = $('#quota-gender-grid');
    const quotaGroupGrid = $('#quota-group-grid');
    const genderQuotaBadges = $('#gender-quota-badges');
    let ageIsValid = true;
    let quotaIsFull = false;
    let referralState = null;
    let referralRequestId = 0;

    const syncSubmitState = () => {
      if (!submitButton) return;

      /*
       * Kunci tombol submit hanya ketika jenjang/kelompok yang dipilih sudah
       * penuh. Pengguna tetap bisa memperbaiki pilihan untuk melanjutkan.
       */
      submitButton.disabled = quotaIsFull;
      submitButton.setAttribute('aria-disabled', String(quotaIsFull));
      submitButton.classList.toggle('cursor-not-allowed', quotaIsFull);
      submitButton.classList.toggle('opacity-50', quotaIsFull);
    };

    const normalizeDate = (value) => {
      const match = String(value || '').trim().match(/^\d{4}-\d{2}-\d{2}/);

      return match ? match[0] : '';
    };

    /* Format tanggal memakai zona waktu lokal agar tidak bergeser sehari pada UTC+8. */
    const formatLocalDate = (date) => {
      if (!(date instanceof Date) || Number.isNaN(date.getTime())) return '';

      const year = date.getFullYear();
      const month = String(date.getMonth() + 1).padStart(2, '0');
      const day = String(date.getDate()).padStart(2, '0');

      return `${year}-${month}-${day}`;
    };

    const jenjangFromUrl = new URLSearchParams(window.location.search).get('jenjang');
    const spmbAgeConfig = window.CI_SPMB_AGE || {};
    const minimumSdAgeMonths = Number(spmbAgeConfig.minimum_months) || 75;
    const now = new Date();
    const fallbackSchoolYear = now.getMonth() >= 6 ? now.getFullYear() + 1 : now.getFullYear();
    const schoolYearStart = spmbAgeConfig.reference_date
      ? new Date(`${spmbAgeConfig.reference_date}T00:00:00`)
      : new Date(fallbackSchoolYear, 6, 1);

    const computedSdCutoffDate = (() => {
      if (Number.isNaN(schoolYearStart.getTime())) return '';

      const cutoff = new Date(schoolYearStart);
      cutoff.setMonth(cutoff.getMonth() - minimumSdAgeMonths);
      cutoff.setDate(cutoff.getDate() - 1);

      return Number.isNaN(cutoff.getTime()) ? '' : formatLocalDate(cutoff);
    })();

    const sdCutoffDate = normalizeDate(spmbAgeConfig.sd_cutoff_date)
      || normalizeDate(birthDateInput ? birthDateInput.dataset.sdMax : '')
      || computedSdCutoffDate;
    const defaultMaxDate = normalizeDate(birthDateInput ? birthDateInput.dataset.defaultMax : '')
      || formatLocalDate(now);

    /* Hitung usia dalam bulan penuh pada 1 Juli tahun ajaran mendatang. */
    const calculateAgeInMonths = (value, referenceDate = schoolYearStart) => {
      if (!value) return null;

      const birthDate = new Date(`${value}T00:00:00`);
      if (Number.isNaN(birthDate.getTime()) || Number.isNaN(referenceDate.getTime())) {
        return null;
      }

      let months = (referenceDate.getFullYear() - birthDate.getFullYear()) * 12
        + (referenceDate.getMonth() - birthDate.getMonth());

      if (referenceDate.getDate() < birthDate.getDate()) {
        months -= 1;
      }

      return months;
    };

    const formatAge = (months) => `${Math.floor(months / 12)} Tahun ${months % 12} Bulan`;

    const isTkLevel = () => (jenjangSelect ? jenjangSelect.value === 'TK' : false);
    const selectedTkGroup = () => (isTkLevel() && subLevelSelect ? tkGroups[subLevelSelect.value] || null : null);
    const normalizedReferralCode = () => String(referralInput?.value || '')
      .replace(/[\s\u00A0\u2007\u200B\u200C\u200D\u202F\u2060\uFEFF]/g, '')
      .replace(/[\u2010\u2011\u2012\u2013\u2014\u2015\u2212\uFF0D]/g, '-')
      .toUpperCase();
    const referralIsApplied = () => Boolean(referralState && referralState.code === normalizedReferralCode());

    /* Biaya efektif KB-TK: gratis bila kode Event Muslim Vibes sudah tervalidasi. */
    const effectiveTkFee = () => {
      if (referralIsApplied() && referralState.waives_registration_fee) return 0;

      return Number(tkConfig.fee) || 0;
    };

    const setReferralStatus = (tone, message) => {
      if (!referralStatus) return;

      if (!message) {
        referralStatus.classList.add('hidden');
        referralStatus.textContent = '';
        return;
      }

      const tones = {
        success: 'border-emerald-200 bg-emerald-50 text-emerald-800',
        error: 'border-rose-200 bg-rose-50 text-rose-800',
        info: 'border-slate-200 bg-white text-slate-600',
      };

      referralStatus.className = `mt-3 rounded-lg border p-3 text-[11px] font-semibold leading-5 ${tones[tone] || tones.info}`;
      referralStatus.textContent = message;
      referralStatus.classList.remove('hidden');
    };

    const clearReferralState = ({ clearStatus = true } = {}) => {
      referralState = null;

      if (clearStatus) setReferralStatus('info', '');
    };

    const applyReferralState = (data) => {
      referralState = { ...data, code: normalizedReferralCode() };

      setReferralStatus('success', data.message);
      updateRekeningInfo();
      updatePaymentProofState();
    };

    const checkReferralCode = async () => {
      const code = normalizedReferralCode();

      if (!code) {
        clearReferralState();
        setReferralStatus('error', 'Masukkan kode referral terlebih dahulu, atau kosongkan bila tidak memiliki kode.');
        updateRekeningInfo();
        updatePaymentProofState();

        return false;
      }

      if (!referralEndpoint) {
        setReferralStatus('error', 'Layanan cek kode referral belum tersedia.');

        return false;
      }

      const requestId = ++referralRequestId;
      referralButton?.setAttribute('disabled', 'disabled');
      if (referralButtonText) referralButtonText.textContent = 'Memeriksa...';
      setReferralStatus('info', 'Memeriksa kode referral...');

      try {
        const query = `${referralEndpoint}${referralEndpoint.includes('?') ? '&' : '?'}code=${encodeURIComponent(code)}`;
        const response = await fetch(query, {
          method: 'GET',
          headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
          },
          credentials: 'same-origin',
          cache: 'no-store',
        });
        const data = await response.json().catch(() => ({}));

        if (requestId !== referralRequestId) return false;

        if (!response.ok || !data.valid) {
          clearReferralState({ clearStatus: false });
          setReferralStatus('error', data.message || '❌ Kode referral tidak valid atau kuota kode ini sudah terpakai.');
          updateRekeningInfo();
          updatePaymentProofState();

          return false;
        }

        applyReferralState(data);

        return true;
      } catch (error) {
        if (requestId !== referralRequestId) return false;

        clearReferralState({ clearStatus: false });
        setReferralStatus('error', '❌ Gagal memeriksa kode referral. Periksa koneksi internet lalu coba lagi.');
        updateRekeningInfo();
        updatePaymentProofState();

        return false;
      } finally {
        if (requestId === referralRequestId) {
          referralButton?.removeAttribute('disabled');
          if (referralButtonText) referralButtonText.textContent = 'Cek Kode';
        }
      }
    };

    /* Tampilkan pilihan kelompok & kode referral hanya saat jenjang TK dipilih. */
    const updateTkFields = () => {
      const isTk = isTkLevel();

      subLevelWrap?.classList.toggle('hidden', !isTk);
      referralWrap?.classList.toggle('hidden', !isTk);

      if (subLevelSelect) {
        subLevelSelect.required = isTk;
        subLevelSelect.setAttribute('aria-required', String(isTk));

        if (!isTk) {
          subLevelSelect.value = '';
          subLevelWarning?.classList.add('hidden');
        }
      }

      if (!isTk) {
        if (referralInput) referralInput.value = '';
        clearReferralState();
      }
    };

    /* Perbarui rincian biaya pendaftaran sesuai jenjang yang dipilih. */
    const updateFeeSummary = (fee) => {
      if (rekeningFee) {
        rekeningFee.textContent = `Rp ${new Intl.NumberFormat('id-ID').format(Number(fee) || 0)}`;
      }
    };

    /* Validasi usia minimal jenjang SD dan kelompok KB-TK. */
    const updateAgeValidation = () => {
      if (!birthDateInput) return true;

      const isSd = jenjangSelect ? jenjangSelect.value === 'SD' : false;
      const tkGroup = selectedTkGroup();
      const tkCutoff = tkGroup ? normalizeDate(tkGroup.cutoff) : '';

      // 1. Atur batas tanggal maksimal pada input date
      if (isSd && sdCutoffDate) {
        birthDateInput.setAttribute('max', sdCutoffDate);
      } else if (tkCutoff) {
        birthDateInput.setAttribute('max', tkCutoff);
      } else {
        birthDateInput.setAttribute('max', defaultMaxDate);
      }

      const birthValue = birthDateInput.value ? birthDateInput.value.trim() : '';

      // Calon peserta didik SD tidak memenuhi syarat jika tanggal lahir lebih dari batas 31 Maret
      const isSdTooYoung = isSd && Boolean(birthValue) && Boolean(sdCutoffDate) && birthValue > sdCutoffDate;

      // Kelompok KB-TK tidak memenuhi syarat bila lahir setelah batas usia kelompok tersebut.
      const isTkTooYoung = Boolean(tkGroup) && Boolean(birthValue) && Boolean(tkCutoff) && birthValue > tkCutoff;
      const isTooYoung = isSdTooYoung || isTkTooYoung;

      const tkReferenceDate = new Date(Number(tkConfig.target_year) || fallbackSchoolYear, 5, 30);
      const ageInMonths = tkGroup
        ? calculateAgeInMonths(birthValue, tkReferenceDate)
        : calculateAgeInMonths(birthValue);
      const hasAge = ageInMonths !== null;
      const ageLabel = hasAge ? formatAge(ageInMonths) : '';

      const sdWarningMessage = 'Maaf, batas usia minimal masuk SD adalah kelahiran maksimal 31 Maret (6 tahun 3 bulan per 1 Juli). Kelahiran 1 April ke atas belum dapat mendaftar.';
      const tkWarningMessage = tkGroup
        ? `Maaf, kelompok ${tkGroup.label} memerlukan usia minimal ${tkGroup.min_age_label} pada Juni ${tkConfig.target_year}. Ananda yang lahir setelah ${tkGroup.cutoff_label} belum memenuhi syarat kelompok ini. Silakan pilih kelompok yang sesuai.`
        : '';
      const warningMessage = isTkTooYoung ? tkWarningMessage : sdWarningMessage;

      if (ageWarning) {
        ageWarning.classList.toggle('hidden', !isTooYoung);
      }
      if (ageWarningText) {
        ageWarningText.textContent = isTooYoung ? warningMessage : '';
      }
      if (subLevelWarning) {
        subLevelWarning.classList.toggle('hidden', !isTkTooYoung);
      }
      if (subLevelWarningText) {
        subLevelWarningText.textContent = isTkTooYoung ? tkWarningMessage : '';
      }

      const isEligible = Boolean(birthValue) && !isTooYoung && (isSd || Boolean(tkGroup));
      if (ageValid) {
        ageValid.classList.toggle('hidden', !isEligible);
      }
      if (ageValidText) {
        if (!isEligible) {
          ageValidText.textContent = '';
        } else if (tkGroup) {
          ageValidText.textContent = hasAge
            ? `Usia pada Juni ${tkConfig.target_year}: ${ageLabel} (Memenuhi syarat usia ${tkGroup.label})`
            : `Memenuhi syarat usia ${tkGroup.label}`;
        } else {
          ageValidText.textContent = hasAge
            ? `Usia pada 1 Juli: ${ageLabel} (Memenuhi syarat usia masuk SD)`
            : 'Memenuhi syarat usia masuk SD';
        }
      }

      if (submitButton) {
        ageIsValid = !isTooYoung;
        syncSubmitState();
      }

      birthDateInput.setCustomValidity(isTooYoung ? warningMessage : '');
      birthDateInput.setAttribute('aria-invalid', String(isTooYoung));

      return !isTooYoung;
    };

    /* Tampilkan rekening & biaya sesuai jenjang yang dipilih. */
    const updateRekeningInfo = () => {
      if (!jenjangSelect || !rekeningBox) return;

      if (isTkLevel()) {
        if (rekeningBank) rekeningBank.textContent = tkConfig.bank || '-';
        if (rekeningNumber) rekeningNumber.textContent = tkConfig.account_number || '-';
        if (rekeningName) rekeningName.textContent = tkConfig.account_name || '-';
        updateFeeSummary(effectiveTkFee());
        rekeningBox.classList.remove('hidden');

        return;
      }

      const levelInfo = ppdbLevels[jenjangSelect.value];

      if (!levelInfo) {
        rekeningBox.classList.add('hidden');
        return;
      }

      if (rekeningBank) rekeningBank.textContent = levelInfo.bank || '-';
      if (rekeningNumber) rekeningNumber.textContent = levelInfo.account_number || '-';
      if (rekeningName) rekeningName.textContent = levelInfo.account_name || '-';
      updateFeeSummary(Number(levelInfo.fee) || 0);

      rekeningBox.classList.remove('hidden');
    };

    /* Asal sekolah wajib untuk SMP/SMA, opsional untuk TK/SD. */
    const genderSelect = $('#jenis-kelamin');
    const genderSmaHint = $('#gender-sma-hint');

    const updateSmaGenderState = () => {
      if (!genderSelect) return;

      const isSma = jenjangSelect ? jenjangSelect.value === 'SMA' : false;
      const maleOption = genderSelect.querySelector('option[value="L"]');

      if (maleOption) {
        maleOption.disabled = isSma;
      }

      if (isSma) {
        genderSelect.value = 'P';
      }

      if (genderSmaHint) {
        genderSmaHint.classList.toggle('hidden', !isSma);
      }
    };

    const quotaGenderKeys = ['male', 'female'];
    const quotaGenderLabels = { male: 'Laki-laki', female: 'Perempuan' };
    const quotaGenderFromCode = { L: 'male', P: 'female' };
    const quotaGroupSlug = (groupKey) => String(groupKey).toLowerCase().replace(/\s+/g, '_');

    const currentLevelQuota = () => {
      if (!jenjangSelect || !jenjangSelect.value) return null;

      return ppdbQuotas[jenjangSelect.value] || null;
    };

    /* Tampilkan badge sisa/penuh pada pilihan jenis kelamin (SD, SMP, SMA). */
    const updateGenderQuotaIndicators = (quota, isTk = false) => {
      if (genderSelect) {
        Object.entries(quotaGenderFromCode).forEach(([code, key]) => {
          const option = genderSelect.querySelector(`option[value="${code}"]`);
          if (!option) return;

          const label = quotaGenderLabels[key];
          const isFull = !isTk && Boolean(quota && quota[key] && quota[key].is_full);
          option.textContent = isFull ? `${label} — PENUH` : label;
        });
      }

      if (!genderQuotaBadges) return;

      genderQuotaBadges.innerHTML = '';

      if (isTk || !quota) return;

      quotaGenderKeys.forEach((key) => {
        const data = quota[key];
        const badge = document.createElement('span');

        badge.className = data.is_full
          ? 'inline-flex items-center gap-1 rounded-full bg-rose-100 px-2 py-0.5 text-[10px] font-extrabold text-rose-700'
          : 'inline-flex items-center gap-1 rounded-full bg-emerald-50 px-2 py-0.5 text-[10px] font-bold text-emerald-700';
        badge.textContent = data.is_full
          ? `${data.label} — PENUH`
          : `${data.label} — sisa ${data.remaining}`;
        genderQuotaBadges.appendChild(badge);
      });
    };

    /* Tampilkan sisa/penuh pada pilihan kelompok dan kartu kuota KB-TK. */
    const updateTkGroupQuotaIndicators = (quota) => {
      const groups = quota && quota.groups ? quota.groups : {};

      if (subLevelSelect) {
        Array.from(subLevelSelect.options).forEach((option) => {
          if (!option.value) return;

          const baseLabel = option.dataset.baseLabel || option.textContent.trim();
          option.dataset.baseLabel = baseLabel;

          const data = groups[option.value];

          option.textContent = data
            ? `${baseLabel} — ${data.is_full ? 'PENUH' : `sisa ${data.remaining}`}`
            : baseLabel;
        });
      }

      if (subLevelQuotaBadges) {
        subLevelQuotaBadges.innerHTML = '';

        Object.entries(groups).forEach(([groupKey, data]) => {
          const badge = document.createElement('span');

          badge.className = data.is_full
            ? 'inline-flex items-center gap-1 rounded-full bg-rose-100 px-2 py-0.5 text-[10px] font-extrabold text-rose-700'
            : 'inline-flex items-center gap-1 rounded-full bg-emerald-50 px-2 py-0.5 text-[10px] font-bold text-emerald-700';
          badge.textContent = data.is_full
            ? `${data.label || groupKey} — PENUH`
            : `${data.label || groupKey} — sisa ${data.remaining}`;
          subLevelQuotaBadges.appendChild(badge);
        });
      }

      Object.entries(groups).forEach(([groupKey, data]) => {
        const slug = data.slug || quotaGroupSlug(groupKey);
        const status = document.getElementById(`quota-group-${slug}-status`);
        const count = document.getElementById(`quota-group-${slug}-count`);
        const bar = document.getElementById(`quota-group-${slug}-bar`);

        if (status) {
          status.textContent = data.is_full ? 'PENUH' : `Sisa ${data.remaining}`;
          status.className = data.is_full
            ? 'text-[10px] font-extrabold text-rose-600'
            : 'text-[10px] font-extrabold text-emerald-700';
        }

        if (count) {
          count.textContent = `Terisi: ${data.used} / ${data.quota} ${data.label}`;
        }

        if (bar) {
          bar.style.width = `${data.percentage}%`;
          bar.className = `h-full rounded-full ${data.is_full ? 'bg-rose-500' : 'bg-emerald-500'}`;
        }
      });
    };

    const updateQuotaWhatsAppLink = (level, quotaLabel) => {
      if (!quotaWaLink) return;

      const whatsappNumbers = window.CI_WHATSAPP || {};
      const adminNumber = String(whatsappNumbers[level] || whatsappNumbers.UMUM || '').replace(/[^0-9]/g, '');

      if (!adminNumber) return;

      const target = level === 'TK' ? `kelompok ${quotaLabel}` : `${quotaLabel}`;
      const message = `Assalamu'alaikum, saya ingin menanyakan daftar tunggu (Waiting List) SPMB jenjang ${level} untuk ${target} karena kuota sudah penuh.`;

      quotaWaLink.href = `https://wa.me/${adminNumber}?text=${encodeURIComponent(message)}`;
    };

    /* Perbarui panel kuota, peringatan penuh, dan status tombol submit. */
    const updateQuotaState = () => {
      const level = jenjangSelect ? jenjangSelect.value : '';
      const quota = currentLevelQuota();
      const gender = genderSelect ? genderSelect.value : '';
      const isTk = level === 'TK';

      updateGenderQuotaIndicators(quota, isTk);

      if (!level || !quota) {
        quotaDetail?.classList.add('hidden');
        quotaBothFullBanner?.classList.add('hidden');
        quotaFullWarning?.classList.add('hidden');
        quotaGroupGrid?.classList.add('hidden');
        quotaGenderGrid?.classList.add('hidden');
        quotaIsFull = false;
        syncSubmitState();

        return;
      }

      quotaDetail?.classList.remove('hidden');
      if (quotaDetailLevel) quotaDetailLevel.textContent = level;

      if (isTk) {
        updateTkGroupQuotaIndicators(quota);
        quotaGroupGrid?.classList.remove('hidden');
        quotaGenderGrid?.classList.add('hidden');

        const groupKey = subLevelSelect ? subLevelSelect.value : '';
        const groupQuota = groupKey && quota.groups ? quota.groups[groupKey] || null : null;
        const isSelectedFull = Boolean(groupQuota && groupQuota.is_full);
        const groups = quota.groups || {};
        const groupKeys = Object.keys(groups);
        const isAllFull = groupKeys.length > 0 && groupKeys.every((key) => groups[key].is_full);

        quotaIsFull = isSelectedFull;
        quotaFullWarning?.classList.toggle('hidden', !isSelectedFull);

        if (isSelectedFull && groupQuota) {
          if (quotaFullWarningText) {
            quotaFullWarningText.textContent = `Mohon maaf, kuota pendaftaran SPMB untuk kelompok ${groupQuota.label} saat ini sudah PENUH (${groupQuota.used}/${groupQuota.quota}). Silakan pilih kelompok lain atau hubungi panitia SPMB melalui WhatsApp untuk informasi daftar tunggu (Waiting List).`;
          }

          updateQuotaWhatsAppLink(level, groupQuota.label);
        }

        quotaBothFullBanner?.classList.toggle('hidden', !isAllFull);
        if (isAllFull && quotaBothFullText) {
          quotaBothFullText.textContent = 'Pendaftaran KB-TK telah memenuhi kuota untuk seluruh kelompok (KB, TK A, dan TK B). Silakan hubungi panitia SPMB melalui WhatsApp untuk informasi daftar tunggu (Waiting List).';
        }

        syncSubmitState();

        return;
      }

      quotaGroupGrid?.classList.add('hidden');
      quotaGenderGrid?.classList.remove('hidden');

      quotaGenderKeys.forEach((key) => {
        const data = quota[key];
        const status = document.getElementById(`quota-${key}-status`);
        const count = document.getElementById(`quota-${key}-count`);
        const bar = document.getElementById(`quota-${key}-bar`);

        if (status) {
          status.textContent = data.is_full ? 'PENUH' : `Sisa ${data.remaining}`;
          status.className = data.is_full
            ? 'text-[10px] font-extrabold text-rose-600'
            : 'text-[10px] font-extrabold text-emerald-700';
        }

        if (count) {
          count.textContent = `Terisi: ${data.used} / ${data.quota} ${data.label}`;
        }

        if (bar) {
          bar.style.width = `${data.percentage}%`;
          bar.className = `h-full rounded-full ${data.is_full ? 'bg-rose-500' : 'bg-emerald-500'}`;
        }
      });

      const isBothFull = Boolean(quota.is_full);
      quotaBothFullBanner?.classList.toggle('hidden', !isBothFull);
      if (isBothFull && quotaBothFullText) {
        quotaBothFullText.textContent = `Pendaftaran jenjang ${level} telah memenuhi kuota untuk Laki-laki dan Perempuan. Silakan pilih jenjang lain atau hubungi panitia SPMB melalui WhatsApp untuk informasi daftar tunggu (Waiting List).`;
      }

      const selectedKey = quotaGenderFromCode[gender] || null;
      const selectedQuota = selectedKey ? quota[selectedKey] : null;
      const isSelectedFull = Boolean(selectedQuota && selectedQuota.is_full);

      quotaIsFull = isSelectedFull;
      quotaFullWarning?.classList.toggle('hidden', !isSelectedFull);

      if (isSelectedFull && selectedQuota) {
        if (quotaFullWarningText) {
          quotaFullWarningText.textContent = `Mohon maaf, kuota pendaftaran SPMB untuk jenjang ${level} - ${selectedQuota.label} saat ini sudah PENUH (${selectedQuota.used}/${selectedQuota.quota}). Silakan hubungi panitia SPMB melalui WhatsApp untuk informasi daftar tunggu (Waiting List).`;
        }

        updateQuotaWhatsAppLink(level, selectedQuota.label);
      }

      syncSubmitState();
    };

    const updateAsalSekolahState = () => {
      const jenjang = jenjangSelect ? jenjangSelect.value : '';
      const isRequired = jenjang === 'SMP' || jenjang === 'SMA';

      if (asalSekolahInput) {
        asalSekolahInput.required = isRequired;
        asalSekolahInput.setAttribute('aria-required', String(isRequired));
      }
      if (asalSekolahRequiredMark) {
        asalSekolahRequiredMark.hidden = !isRequired;
      }
      if (asalSekolahHint) {
        asalSekolahHint.textContent = isRequired
          ? 'Wajib diisi untuk jenjang SMP dan SMA.'
          : 'Opsional untuk jenjang TK dan SD, wajib diisi untuk SMP dan SMA.';
      }
    };

    /* Sinkronkan tab syarat pendaftaran dengan dropdown jenjang. */
    const syncSyaratTab = () => {
      if (!jenjangSelect || !jenjangSelect.value) return;
      const tab = document.getElementById(`tab-${jenjangSelect.value.toLowerCase()}`);
      if (tab) activateJenjangTab(tab);
    };

    /* Bukti pembayaran hanya wajib jika jenjang yang dipilih memiliki biaya pendaftaran. */
    const updatePaymentProofState = () => {
      if (!paymentProofInput) return;

      const levelInfo = jenjangSelect ? ppdbLevels[jenjangSelect.value] : null;
      const fee = isTkLevel()
        ? effectiveTkFee()
        : (Number(levelInfo && levelInfo.fee) || 0);
      const isRequired = fee > 0;
      const isFreeReferral = isTkLevel() && referralIsApplied() && Boolean(referralState.waives_registration_fee);

      paymentProofInput.required = isRequired;
      paymentProofRequiredMark?.classList.toggle('hidden', !isRequired);
      paymentProofWrap?.classList.toggle('hidden', isFreeReferral);

      if (isFreeReferral) {
        paymentProofInput.value = '';
      }

      if (paymentProofHint) {
        if (isFreeReferral) {
          paymentProofHint.textContent = 'Tidak diperlukan karena biaya pendaftaran GRATIS melalui kode referral Event Muslim Vibes.';
        } else if (isRequired) {
          paymentProofHint.textContent = defaultPaymentProofHint;
        } else {
          paymentProofHint.textContent = 'Opsional untuk jenjang ini karena tidak ada biaya pendaftaran.';
        }
      }
    };

    const updateJenjangState = () => {
      updateTkFields();
      updateAsalSekolahState();
      updateSmaGenderState();
      updateAgeValidation();
      updateRekeningInfo();
      updatePaymentProofState();
      updateQuotaState();
      syncSyaratTab();
    };

    const handleJenjangChange = () => {
      updateJenjangState();
    };

    if (jenjangFromUrl && jenjangSelect) {
      const normalizedJenjang = jenjangFromUrl.toUpperCase();
      const availableOption = Array.from(jenjangSelect.options).some(
        (option) => option.value === normalizedJenjang && !option.disabled
      );
      if (availableOption) {
        jenjangSelect.value = normalizedJenjang;
      }
    }

    updateJenjangState();

    if (jenjangSelect) {
      jenjangSelect.addEventListener('change', handleJenjangChange);
    }

    if (genderSelect) {
      genderSelect.addEventListener('change', updateQuotaState);
    }

    if (birthDateInput) {
      birthDateInput.addEventListener('input', updateAgeValidation);
      birthDateInput.addEventListener('change', updateAgeValidation);
    }

    if (subLevelSelect) {
      subLevelSelect.addEventListener('change', () => {
        updateAgeValidation();
        updateRekeningInfo();
        updatePaymentProofState();
        updateQuotaState();
      });
    }

    if (referralInput) {
      referralInput.addEventListener('input', () => {
        if (referralState && referralState.code !== normalizedReferralCode()) {
          clearReferralState();
          updateRekeningInfo();
          updatePaymentProofState();
        }
      });

      referralInput.addEventListener('blur', () => {
        referralInput.value = normalizedReferralCode();
      });

      referralInput.addEventListener('keydown', (event) => {
        if (event.key !== 'Enter') return;

        event.preventDefault();
        checkReferralCode();
      });
    }

    referralButton?.addEventListener('click', (event) => {
      event.preventDefault();
      checkReferralCode();
    });

    /* Klik tab syarat juga memilih jenjang pada formulir. */
    $$('.syarat-tab').forEach((tab) => {
      tab.addEventListener('click', () => {
        if (!jenjangSelect || !tab.dataset.jenjang) return;
        const targetOption = Array.from(jenjangSelect.options).find(
          (option) => option.value === tab.dataset.jenjang.toUpperCase()
        );
        if (targetOption && targetOption.disabled) return;
        jenjangSelect.value = tab.dataset.jenjang.toUpperCase();
        handleJenjangChange();
      });
    });

    ppdbForm.addEventListener('submit', (event) => {
      const ageIsValidNow = updateAgeValidation();
      updateQuotaState();

      if (isTkLevel() && subLevelSelect && !subLevelSelect.value) {
        event.preventDefault();
        subLevelSelect.reportValidity();
        subLevelWrap?.scrollIntoView({ behavior: 'smooth', block: 'center' });
        subLevelSelect.focus({ preventScroll: true });
        return;
      }

      if (isTkLevel() && normalizedReferralCode() !== '') {
        if (!referralIsApplied()) {
          event.preventDefault();
          setReferralStatus('error', 'Klik tombol Cek Kode untuk memvalidasi kode referral sebelum mengirim pendaftaran.');
          referralWrap?.scrollIntoView({ behavior: 'smooth', block: 'center' });
          referralInput?.focus({ preventScroll: true });
          return;
        }
      }

      if (!ageIsValidNow) {
        event.preventDefault();
        const targetWarning = subLevelWarning && !subLevelWarning.classList.contains('hidden')
          ? subLevelWarning
          : ageWarning;
        targetWarning?.scrollIntoView({ behavior: 'smooth', block: 'center' });
        birthDateInput?.focus({ preventScroll: true });
        return;
      }

      if (quotaIsFull) {
        event.preventDefault();
        quotaFullWarning?.scrollIntoView({ behavior: 'smooth', block: 'center' });
      }
    });

  }

  /* -------------------------------------------------------------------------
     10B. MENU WHATSAPP MELAYANG (PILIH ADMIN PER JENJANG)
     ------------------------------------------------------------------------- */
  const waWidget = $('#wa-widget');
  const waToggle = $('#wa-toggle');
  const waMenu = $('#wa-menu');
  const waMenuClose = $('#wa-menu-close');
  const waOptions = $$('[data-wa-jenjang]');

  const buildAdminChatUrl = (jenjang) => {
    const adminNumber = getAdminWhatsappNumber(jenjang);
    if (!adminNumber) return '';

    const label = `${jenjang} Islam Cahaya Ilmu`;
    const message = [
      "Assalamu'alaikum warahmatullahi wabarakatuh,",
      '',
      `Saya ingin bertanya tentang pendaftaran siswa baru jenjang ${label}.`,
      'Mohon informasi mengenai persyaratan, jadwal, dan biaya pendaftaran.',
      '',
      'Terima kasih.'
    ].join('\n');

    return `https://wa.me/${adminNumber}?text=${encodeURIComponent(message)}`;
  };

  waOptions.forEach((option) => {
    const jenjang = String(option.dataset.waJenjang || '').toUpperCase();
    const chatUrl = buildAdminChatUrl(jenjang);

    if (chatUrl) {
      option.href = chatUrl;
      option.target = '_blank';
      option.rel = 'noopener noreferrer';
    } else {
      option.addEventListener('click', (event) => {
        event.preventDefault();
        window.alert(`Nomor WhatsApp admin ${jenjang} belum diatur.`);
      });
    }
  });

  if (waWidget && waToggle && waMenu) {
    let isWaMenuOpen = false;

    const setWaMenu = (open, returnFocus = false) => {
      isWaMenuOpen = open;
      waMenu.hidden = !open;
      waToggle.setAttribute('aria-expanded', String(open));
      waWidget.classList.toggle('is-open', open);

      if (open) {
        const firstOption = $('.wa-option', waMenu);
        if (firstOption) firstOption.focus({ preventScroll: true });
      } else if (returnFocus) {
        waToggle.focus({ preventScroll: true });
      }
    };

    waToggle.addEventListener('click', () => setWaMenu(!isWaMenuOpen));

    if (waMenuClose) {
      waMenuClose.addEventListener('click', () => setWaMenu(false, true));
    }

    waOptions.forEach((option) => {
      option.addEventListener('click', () => setWaMenu(false));
    });

    $$('[data-wa-open]').forEach((button) => {
      button.addEventListener('click', () => setWaMenu(true));
    });

    document.addEventListener('click', (event) => {
      if (!isWaMenuOpen || waWidget.contains(event.target)) return;
      if (event.target.closest('[data-wa-open]')) return;
      setWaMenu(false);
    });

    document.addEventListener('keydown', (event) => {
      if (event.key !== 'Escape' || !isWaMenuOpen) return;
      setWaMenu(false, true);
    });
  }

  /* -------------------------------------------------------------------------
     11. FAQ ACCORDION (halaman SPMB & halaman utama)
     ------------------------------------------------------------------------- */
  const faqItems = $$('.faq-item');

  if (faqItems.length) {
    const setFaqState = (item, open) => {
      const button = $('.faq-question', item);
      const answer = $('.faq-answer', item);

      item.classList.toggle('is-open', open);
      if (button) button.setAttribute('aria-expanded', String(open));
      if (answer) answer.style.maxHeight = open ? `${answer.scrollHeight}px` : '0px';
    };

    faqItems.forEach((item) => {
      const button = $('.faq-question', item);
      if (!button) return;

      button.addEventListener('click', () => {
        const willOpen = !item.classList.contains('is-open');

        faqItems.forEach((otherItem) => {
          if (otherItem !== item) setFaqState(otherItem, false);
        });

        setFaqState(item, willOpen);
      });

      setFaqState(item, item.classList.contains('is-open'));
    });

    window.addEventListener('resize', () => {
      faqItems.forEach((item) => {
        if (!item.classList.contains('is-open')) return;
        const answer = $('.faq-answer', item);
        if (answer) answer.style.maxHeight = `${answer.scrollHeight}px`;
      });
    });
  }

  /* -------------------------------------------------------------------------
     12. TOMBOL KEMBALI KE ATAS
     ------------------------------------------------------------------------- */
  if (backToTop) {
    backToTop.addEventListener('click', () => {
      window.scrollTo({ top: 0, behavior: smoothBehavior });
    });
  }

  /* -------------------------------------------------------------------------
     12b. CAROUSEL FOTO INFO SEKOLAH
     ------------------------------------------------------------------------- */
  const postCarousels = $$('[data-post-carousel]');

  postCarousels.forEach((carousel) => {
    const track = $('[data-carousel-track]', carousel);
    const slides = $$('[data-carousel-slide]', carousel);
    const prevButton = $('[data-carousel-prev]', carousel);
    const nextButton = $('[data-carousel-next]', carousel);
    const dotsContainer = $('[data-carousel-dots]', carousel);

    if (!track || slides.length < 2) return;

    let currentIndex = 0;
    const dots = [];
    let heightFrame = null;
    const autoplayDelay = Number(carousel.dataset.carouselAutoplay) || 1500;
    let autoplayTimer = null;
    let isHoveredOrFocused = false;

    const syncCarouselHeight = () => {
      const activeSlide = slides[currentIndex];

      if (!activeSlide) return;

      const height = Math.ceil(activeSlide.getBoundingClientRect().height);

      if (height > 0) {
        carousel.style.height = `${height}px`;
      }
    };

    const scheduleHeightSync = () => {
      if (heightFrame !== null) return;

      heightFrame = window.requestAnimationFrame(() => {
        heightFrame = null;
        syncCarouselHeight();
      });
    };

    const render = () => {
      track.style.transform = `translate3d(-${currentIndex * 100}%, 0, 0)`;

      slides.forEach((slide, index) => {
        slide.setAttribute('aria-hidden', String(index !== currentIndex));
      });

      dots.forEach((dot, index) => {
        const isActive = index === currentIndex;
        dot.classList.toggle('is-active', isActive);
        dot.setAttribute('aria-current', isActive ? 'true' : 'false');
      });

      scheduleHeightSync();
    };

    const stopAutoplay = () => {
      if (autoplayTimer !== null) {
        window.clearInterval(autoplayTimer);
        autoplayTimer = null;
      }
    };

    const startAutoplay = () => {
      stopAutoplay();

      if (autoplayDelay <= 0 || isHoveredOrFocused || document.hidden) return;

      autoplayTimer = window.setInterval(() => {
        if (!document.hidden && !isHoveredOrFocused) {
          goTo(currentIndex + 1, false);
        }
      }, autoplayDelay);
    };

    const goTo = (index, restartAutoplay = true) => {
      const total = slides.length;
      currentIndex = (index + total) % total;
      render();

      if (restartAutoplay) {
        startAutoplay();
      }
    };

    if (dotsContainer) {
      slides.forEach((slide, index) => {
        const dot = document.createElement('button');
        dot.type = 'button';
        dot.className = 'post-carousel-dot';
        dot.setAttribute('aria-label', `Tampilkan foto ${index + 1}`);
        dot.addEventListener('click', () => goTo(index));
        dotsContainer.appendChild(dot);
        dots.push(dot);
      });
    }

    slides.forEach((slide) => {
      const image = $('img', slide);

      if (image && !image.complete) {
        image.addEventListener('load', scheduleHeightSync, { once: true });
      }
    });

    window.addEventListener('resize', scheduleHeightSync);

    prevButton?.addEventListener('click', () => goTo(currentIndex - 1));
    nextButton?.addEventListener('click', () => goTo(currentIndex + 1));

    carousel.addEventListener('mouseenter', () => {
      isHoveredOrFocused = true;
      stopAutoplay();
    });

    carousel.addEventListener('mouseleave', () => {
      isHoveredOrFocused = false;
      startAutoplay();
    });

    carousel.addEventListener('focusin', () => {
      isHoveredOrFocused = true;
      stopAutoplay();
    });

    carousel.addEventListener('focusout', (event) => {
      if (!carousel.contains(event.relatedTarget)) {
        isHoveredOrFocused = false;
        startAutoplay();
      }
    });

    document.addEventListener('visibilitychange', () => {
      if (document.hidden) {
        stopAutoplay();
      } else {
        startAutoplay();
      }
    });

    carousel.setAttribute('tabindex', '0');
    carousel.addEventListener('keydown', (event) => {
      if (event.key === 'ArrowLeft') {
        event.preventDefault();
        goTo(currentIndex - 1);
      } else if (event.key === 'ArrowRight') {
        event.preventDefault();
        goTo(currentIndex + 1);
      }
    });

    let pointerStartX = null;
    let pointerStartY = null;

    carousel.addEventListener('pointerdown', (event) => {
      if (event.pointerType === 'mouse' && event.button !== 0) return;

      pointerStartX = event.clientX;
      pointerStartY = event.clientY;
      stopAutoplay();
    });

    carousel.addEventListener('pointerup', (event) => {
      if (pointerStartX === null) return;

      const deltaX = event.clientX - pointerStartX;
      const deltaY = event.clientY - (pointerStartY || 0);

      pointerStartX = null;
      pointerStartY = null;

      if (Math.abs(deltaX) > 40 && Math.abs(deltaX) > Math.abs(deltaY)) {
        goTo(currentIndex + (deltaX < 0 ? 1 : -1));
      } else {
        startAutoplay();
      }
    });

    carousel.addEventListener('pointercancel', () => {
      pointerStartX = null;
      pointerStartY = null;
      startAutoplay();
    });

    render();
    startAutoplay();
  });

  /* -------------------------------------------------------------------------
     13. SCROLL EVENT TERPADU
     ------------------------------------------------------------------------- */
  let scrollTicking = false;

  const handleScroll = () => {
    if (scrollTicking) return;

    scrollTicking = true;
    window.requestAnimationFrame(() => {
      updateHeaderState();
      updateActiveNav();
      scrollTicking = false;
    });
  };

  window.addEventListener('scroll', handleScroll, { passive: true });
  window.addEventListener('resize', handleScroll);

  /* Jalankan sekali saat halaman dimuat */
  updateHeaderState();
  updateActiveNav();
})();
