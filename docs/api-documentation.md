# 📚 Dokumentasi REST API - CMS & Layanan Publik (V1)

Dokumentasi lengkap REST API untuk integrasi aplikasi mobile / web frontend (**Flutter**).

- **Base URL:** `http://localhost:8000/api/v1` (atau domain server Anda)
- **Content-Type:** `application/json`
- **Accept:** `application/json`

---

## 📑 Daftar Endpoint

### 1. Kategori Artikel
#### `GET /api/v1/categories`
Mengambil daftar kategori artikel yang aktif beserta jumlah artikelnya.

- **Response `200 OK`:**
```json
{
  "data": [
    {
      "id": 1,
      "name": "Berita Utama",
      "slug": "berita-utama",
      "description": "Informasi dan warta terkini seputar kegiatan instansi.",
      "icon": "newspaper",
      "posts_count": 3
    }
  ]
}
```

---

### 2. Artikel & Berita (Posts)
#### `GET /api/v1/posts`
Mengambil daftar artikel yang telah dipublikasikan dengan pagination.

- **Query Parameters:**
  - `page` *(int, opsional)*: Nomor halaman (default: 1)
  - `per_page` *(int, opsional)*: Jumlah per halaman (default: 10, max: 50)
  - `category` *(string, opsional)*: Filter berdasarkan slug atau ID kategori
  - `featured` *(bool, opsional)*: `1` untuk mengambil artikel unggulan saja
  - `search` *(string, opsional)*: Pencarian pada judul dan ringkasan artikel

- **Response `200 OK`:**
```json
{
  "data": [
    {
      "id": 1,
      "title": "Peluncuran Portal Layanan Publik Terpadu",
      "slug": "peluncuran-portal-layanan-publik-terpadu",
      "summary": "Instansi meluncurkan portal baru...",
      "image_url": "http://localhost:8000/storage/posts/banner.jpg",
      "is_featured": true,
      "view_count": 120,
      "published_at": "2026-11-04T02:24:52+00:00",
      "category": {
        "id": 1,
        "name": "Berita Utama",
        "slug": "berita-utama"
      },
      "author": {
        "id": 1,
        "name": "Admin Instansi",
        "avatar": null
      },
      "created_at": "2026-11-04T02:24:52+00:00"
    }
  ],
  "links": {
    "first": "...",
    "last": "...",
    "prev": null,
    "next": null
  },
  "meta": {
    "current_page": 1,
    "from": 1,
    "last_page": 1,
    "per_page": 10,
    "total": 1
  }
}
```

#### `GET /api/v1/posts/{slug}`
Mengambil detail satu artikel lengkap berdasarkan `slug`. Otomatis menambah jumlah `view_count`.

- **Response `200 OK`:**
```json
{
  "data": {
    "id": 1,
    "title": "Peluncuran Portal Layanan Publik Terpadu",
    "slug": "peluncuran-portal-layanan-publik-terpadu",
    "summary": "Instansi meluncurkan portal baru...",
    "content": "<p>Isi konten berita lengkap HTML...</p>",
    "image_url": "http://localhost:8000/storage/posts/banner.jpg",
    "is_featured": true,
    "view_count": 121,
    "published_at": "2026-11-04T02:24:52+00:00",
    "category": {
      "id": 1,
      "name": "Berita Utama",
      "slug": "berita-utama"
    },
    "author": {
      "id": 1,
      "name": "Admin Instansi",
      "avatar": null
    }
  }
}
```

---

### 3. Halaman Statis (Pages)
#### `GET /api/v1/pages/{slug}`
Mengambil isi halaman profil seperti `tentang-kami`, `visi-misi`, `struktur-organisasi`.

- **Response `200 OK`:**
```json
{
  "data": {
    "id": 1,
    "title": "Tentang Kami",
    "slug": "tentang-kami",
    "content": "<p>Deskripsi profil instansi...</p>",
    "image_url": null,
    "show_in_menu": true,
    "created_at": "2026-11-04T02:28:21+00:00",
    "updated_at": "2026-11-04T02:28:21+00:00"
  }
}
```

---

### 4. Slide Banner
#### `GET /api/v1/slides`
Mengambil daftar banner aktif yang sedang dalam periode tayang untuk slider aplikasi.

- **Response `200 OK`:**
```json
{
  "data": [
    {
      "id": 1,
      "title": "Selamat Datang di Portal Resmi Instansi",
      "description": "Akses informasi cepat, transparan, dan terpercaya.",
      "image_url": "http://localhost:8000/storage/slides/banner1.jpg",
      "link_url": "/posts",
      "sort_order": 1
    }
  ]
}
```

---

### 5. Menu Navigasi Dinamis
#### `GET /api/v1/menus/{location}`
Mengambil struktur menu berdasarkan lokasi (`header`, `footer`, atau `sidebar`).

- **Parameters URL:**
  - `location`: `header` | `footer` | `sidebar`

- **Response `200 OK`:**
```json
{
  "data": {
    "id": 1,
    "name": "Menu Utama (Header)",
    "location": "header",
    "items": [
      {
        "id": 1,
        "label": "Beranda",
        "url": "/",
        "type": "custom",
        "sort_order": 1,
        "children": []
      },
      {
        "id": 2,
        "label": "Tentang Kami",
        "url": "/pages/tentang-kami",
        "type": "page",
        "sort_order": 2,
        "children": []
      }
    ]
  }
}
```

---

### 6. Pengaturan Situs (Settings)
#### `GET /api/v1/settings`
Mengambil seluruh konfigurasi umum instansi (nama instansi, logo, kontak telepon, email, media sosial).

- **Response `200 OK`:**
```json
{
  "data": {
    "grouped": {
      "general": {
        "site_name": "Portal Resmi Instansi",
        "site_tagline": "Melayani Masyarakat dengan Integritas dan Transparansi",
        "site_description": "Portal informasi resmi dan layanan pengajuan pertanyaan masyarakat."
      },
      "contact": {
        "office_address": "Jl. Merdeka No. 123, Jakarta Pusat",
        "office_phone": "(021) 1234567",
        "office_email": "kontak@instansi.go.id"
      },
      "social": {
        "social_facebook": "https://facebook.com/instansiresmi",
        "social_instagram": "https://instagram.com/instansiresmi"
      }
    },
    "settings": {
      "site_name": "Portal Resmi Instansi",
      "office_phone": "(021) 1234567",
      "office_email": "kontak@instansi.go.id"
    }
  }
}
```

---

### 7. Topik Pertanyaan
#### `GET /api/v1/question-topics`
Mengambil daftar topik pengajuan pertanyaan masyarakat dan FAQ.

- **Response `200 OK`:**
```json
{
  "data": [
    {
      "id": 1,
      "name": "Layanan Administrasi",
      "slug": "layanan-administrasi",
      "description": "Pertanyaan seputar surat izin, rekomendasi, dan berkas.",
      "icon": "document-check",
      "questions_count": 5,
      "faqs_count": 2
    }
  ]
}
```

---

### 8. Tanya Jawab (FAQ)
#### `GET /api/v1/faqs`
Mengambil daftar pertanyaan yang sering diajukan.

- **Query Parameters:**
  - `topic` *(string/int, opsional)*: Filter berdasarkan slug atau ID topik

- **Response `200 OK`:**
```json
{
  "data": [
    {
      "id": 1,
      "question": "Berapa lama proses penerbitan surat rekomendasi?",
      "answer": "<p>Proses verifikasi dan penerbitan berkas membutuhkan waktu 1–3 hari kerja...</p>",
      "topic": {
        "id": 1,
        "name": "Layanan Administrasi",
        "slug": "layanan-administrasi"
      }
    }
  ]
}
```

---

### 9. Pertanyaan Masyarakat (Tanya Kami)
#### `GET /api/v1/questions`
Mengambil daftar pertanyaan yang telah dijawab oleh petugas untuk transparansi publik.

- **Query Parameters:**
  - `page` *(int, opsional)*: Halaman pagination
  - `topic` *(string/int, opsional)*: Filter topik
  - `search` *(string, opsional)*: Cari subjek/isi pertanyaan

- **Response `200 OK`:**
```json
{
  "data": [
    {
      "id": 1,
      "name": "Ahmad Fauzi",
      "subject": "Persyaratan Legalisir Berkas Dokumen",
      "body": "Selamat pagi, mohon informasi mengenai persyaratan legalisir dokumen...",
      "status": "answered",
      "topic": {
        "id": 1,
        "name": "Layanan Administrasi",
        "slug": "layanan-administrasi"
      },
      "answer": {
        "id": 1,
        "body": "Selamat pagi Sdr. Ahmad. Untuk legalisir dokumen...",
        "answered_at": "2026-11-04T02:35:16+00:00",
        "answered_by": "Petugas Pelayanan"
      },
      "created_at": "2026-11-04T02:35:16+00:00"
    }
  ]
}
```

#### `POST /api/v1/questions`
Mengirimkan pertanyaan baru dari masyarakat ke sistem. Dilengkapi proteksi rate-limiting (maksimal 10 request/menit per IP).

- **Headers:**
  - `Content-Type: application/json`

- **Request Body:**
```json
{
  "topic_id": 1,
  "name": "Budi Santoso",
  "email": "budi@example.com",
  "phone": "081299998888",
  "subject": "Jadwal Pelayanan Akhir Pekan",
  "body": "Apakah loket pelayanan tetap buka di hari Sabtu dan Minggu?"
}
```

- **Response `201 Created`:**
```json
{
  "message": "Pertanyaan Anda berhasil dikirim dan akan segera diproses oleh petugas.",
  "data": {
    "id": 3,
    "subject": "Jadwal Pelayanan Akhir Pekan",
    "created_at": "2026-11-04T02:46:18+00:00"
  }
}
```

- **Validation Error `422 Unprocessable Entity`:**
```json
{
  "message": "The topic id field is required. (and 4 more errors)",
  "errors": {
    "topic_id": ["The topic id field is required."],
    "email": ["The email field must be a valid email address."],
    "body": ["The body field must be at least 10 characters."]
  }
}
```

