# Portal Layanan Publik - Flutter Frontend

Aplikasi frontend portal publik berbasis **Flutter** yang terintegrasi penuh dengan REST API Laravel CMS (`routes/api.php`). Dilengkapi dengan dukungan **GoRouter** (`go_router`) dan fitur **Multibahasa** (Indonesian `id` & English `en`).

---

## 🚀 Fitur Utama

1. **Routing Declarative dengan `go_router`**:
   - `/` : Beranda (Banner Slider, Topik Populer, Berita Unggulan, Berita Terkini, Call-to-Action)
   - `/posts` : Daftar Berita & Informasi (Pencarian, Filter Kategori, Pagination)
   - `/posts/:slug` : Detail Berita Lengkap (Render TipTap HTML, Informasi Penulis, View Count)
   - `/pages/:slug` : Halaman Statis / Profil Instansi (Tentang Kami, Visi Misi, dll.)
   - `/faqs` : Tanya Jawab FAQ (Accordion, Filter per Topik, Pencarian FAQ)
   - `/questions` : Daftar Pertanyaan Masyarakat & Jawaban Terbuka Petugas
   - `/contact` : Informasi Kontak Kantor, Alamat Resmi, & Jam Layanan
   - Error 404 handler terpadu

2. **Dukungan Multibahasa Terpadu (i18n)**:
   - UI Translation (Indonesian & English) melalui `AppLocalizations` & `Translations`.
   - Sinkronisasi API: Penggantian bahasa otomatis menyertakan header `Accept-Language: id/en` serta `X-Locale: id/en`.
   - Data dinamis (judul artikel, ringkasan, konten, kategori, FAQ) disajikan sesuai bahasa yang dipilih dari backend Spatie Translatable.
   - Pilihan bahasa tersimpan secara persisten melalui `SharedPreferences`.

3. **Integrasi REST API Lengkap**:
   - `GET /api/v1/settings` (Pengaturan nama portal, logo, kontak kantor)
   - `GET /api/v1/slides` (Slide banner aktif)
   - `GET /api/v1/categories` (Kategori artikel berita)
   - `GET /api/v1/posts` & `GET /api/v1/posts/{slug}` (Artikel berita & detail)
   - `GET /api/v1/pages/{slug}` (Halaman profil statis)
   - `GET /api/v1/faqs` (Daftar FAQ)
   - `GET /api/v1/question-topics` (Topik layanan)
   - `GET /api/v1/questions` (Pertanyaan masyarakat yang telah dijawab)
   - `POST /api/v1/questions` (Formulir pengajuan pertanyaan baru dari warga dengan validasi lengkap)

---

## 🛠️ Cara Menjalankan Aplikasi

1. **Pastikan Backend Laravel Berjalan**:
   ```bash
   php artisan serve
   # Server aktif di http://localhost:8000
   ```

2. **Jalankan Aplikasi Flutter**:
   ```bash
   cd frontend
   flutter pub get
   
   # Jalankan di Chrome / Web:
   flutter run -d chrome
   
   # Atau jalankan di Windows Desktop:
   flutter run -d windows
   
   # Atau jalankan di Android Emulator / Device:
   flutter run
   ```

3. **Menjalankan Pengujian (Testing)**:
   ```bash
   flutter test
   ```
