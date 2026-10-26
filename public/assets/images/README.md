# Panduan Gambar - Cahaya Ilmu Islamic School

Folder ini disiapkan untuk menyimpan gambar lokal setelah website diunggah ke hosting.

## Gambar yang dipakai saat ini

Semua gambar pada `index.html` masih menggunakan URL Unsplash agar website langsung
tampil tanpa perlu menyiapkan file. Daftar gambar dan rekomendasi nama file lokal:

| Bagian | Nama file yang disarankan | Ukuran ideal |
|---|---|---|
| Hero | `hero-sekolah.jpg` | 1000 x 1250 px |
| Tentang kami | `gedung-sekolah.jpg` | 1200 x 900 px |
| Jenjang TK | `jenjang-tk.jpg` | 1200 x 900 px |
| Jenjang SD | `jenjang-sd.jpg` | 1200 x 900 px |
| Jenjang SMP | `jenjang-smp.jpg` | 1200 x 900 px |
| Jenjang SMA | `jenjang-sma.jpg` | 1200 x 900 px |
| Galeri masjid | `fasilitas-masjid.jpg` | 1600 x 1200 px |
| Galeri kelas | `fasilitas-kelas.jpg` | 1200 x 900 px |
| Galeri olahraga | `fasilitas-olahraga.jpg` | 1200 x 900 px |
| Galeri lab | `fasilitas-lab.jpg` | 1200 x 900 px |
| Galeri perpustakaan | `fasilitas-perpustakaan.jpg` | 1200 x 900 px |
| Berita sains | `berita-sains.jpg` | 900 x 600 px |
| Berita tahfidz | `berita-tahfidz.jpg` | 900 x 600 px |
| Berita kunjungan | `berita-kunjungan.jpg` | 900 x 600 px |

## Cara mengganti gambar Unsplash dengan gambar lokal

1. Simpan foto ke folder `assets/images/`.
2. Buka `index.html` di VS Code.
3. Cari nama gambar, misalnya `hero-sekolah` atau `unsplash.com`.
4. Ganti nilai `src` pada tag `<img>` menjadi:

   ```html
   <img src="assets/images/hero-sekolah.jpg" alt="Deskripsi foto">
   ```

5. Untuk foto galeri, ganti juga atribut `data-full` dengan file resolusi tinggi
   agar tampilan lightbox tetap tajam:

   ```html
   data-full="assets/images/fasilitas-masjid.jpg"
   ```

## Tips optimasi

- Gunakan format JPG atau WebP.
- Kompres gambar agar ukuran file di bawah 300 KB.
- Gunakan `loading="lazy"` untuk gambar di bawah layar (sudah diterapkan).
- Tulis `alt` yang menjelaskan isi foto untuk aksesibilitas dan SEO.
- Pastikan foto memiliki izin penggunaan, terutama foto siswa dan kegiatan sekolah.

## Catatan

Gambar Unsplash digunakan sebagai placeholder. Ganti dengan dokumentasi asli
sekolah sebelum website dipublikasikan agar tidak menyesatkan pengunjung.
