/* ============================================================================
   ADAPTIVE ASPECT RATIO UNTUK GAMBAR KONTEN
   ----------------------------------------------------------------------------
   Hanya gambar dengan class .adaptive-img yang diproses. Logo, ikon, dan
   thumbnail kecil sengaja tidak disentuh agar tetap memakai ukuran aslinya.

   Aturan rasio:
   - Landscape  -> 4 / 3
   - Portrait   -> 3 / 4
   - Persegi    -> 1 / 1

   Jika gambar berada di dalam elemen [data-adaptive-frame], rasio kontainer
   ikut disesuaikan agar gambar tidak terpotong oleh tinggi kontainer yang kaku.
   ========================================================================== */

(() => {
  'use strict';

  const IMAGE_SELECTOR = 'img.adaptive-img';
  const RATIOS = Object.freeze({
    landscape: '4 / 3',
    portrait: '3 / 4',
    square: '1 / 1'
  });

  const getImageRatio = (image) => {
    const width = image.naturalWidth;
    const height = image.naturalHeight;

    if (!width || !height) return null;

    if (width === height) {
      return { name: 'square', value: RATIOS.square };
    }

    return width > height
      ? { name: 'landscape', value: RATIOS.landscape }
      : { name: 'portrait', value: RATIOS.portrait };
  };

  const applyAdaptiveRatio = (image) => {
    if (!(image instanceof HTMLImageElement) || !image.matches(IMAGE_SELECTOR)) {
      return;
    }

    const ratio = getImageRatio(image);

    if (!ratio) return;

    image.style.aspectRatio = ratio.value;
    image.dataset.adaptiveRatio = ratio.name;
    image.classList.add('is-adaptive');

    const frame = image.closest('[data-adaptive-frame]');

    if (frame) {
      frame.style.aspectRatio = ratio.value;
      frame.dataset.adaptiveRatio = ratio.name;
      frame.classList.add('is-adaptive-frame');
    }
  };

  const applyAllAdaptiveImages = () => {
    document.querySelectorAll(IMAGE_SELECTOR).forEach(applyAdaptiveRatio);
  };

  /* Load event tidak bubbling, tetapi fase capture menangkap semua gambar,
     termasuk gambar lazy dan gambar yang src-nya diganti secara dinamis. */
  document.addEventListener('load', (event) => {
    if (event.target instanceof HTMLImageElement) {
      applyAdaptiveRatio(event.target);
    }
  }, true);

  /* Script dimuat di akhir body, sehingga seluruh gambar konten sudah ada. */
  applyAllAdaptiveImages();
})();
