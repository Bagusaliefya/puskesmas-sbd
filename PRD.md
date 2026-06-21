# PRD — Puskesmas SBD (Sistem Informasi Puskesmas Berbasis Data)

> **Versi:** 1.0
> **Status:** Draft
> **Tanggal:** 11 Juni 2026

---

## 1. Latar Belakang

Puskesmas sebagai fasilitas kesehatan tingkat pertama membutuhkan sistem informasi yang terintegrasi untuk mengelola pendaftaran pasien, pemeriksaan medis, resep obat, inventaris obat, dan manajemen pegawai. Saat ini banyak puskesmas masih menggunakan pencatatan manual yang rentan terhadap kesalahan, kehilangan data, dan inefisiensi operasional.

Puskesmas SBD hadir sebagai solusi berbasis web untuk mendigitalisasi seluruh alur kerja puskesmas — dari pendaftaran mandiri pasien, antrian, pemeriksaan oleh dokter, pemberian resep, hingga pelaporan manajerial.

---

## 2. Tujuan Produk

1. Menyediakan sistem pendaftaran pasien yang cepat (mandiri via web maupun oleh petugas)
2. Mempermudah alur pemeriksaan dan pencatatan resep oleh dokter
3. Mengelola data obat dan stok secara real-time
4. Menyediakan data manajerial berupa laporan untuk pengambilan keputusan
5. Mengurangi beban administratif pegawai puskesmas

---

## 3. Pengguna & Peran (User Roles)

| Role | Deskripsi | Akses Utama |
|---|---|---|
| **Admin** | Manajer puskesmas / kepala tata usaha | Manajemen pegawai, obat, laporan |
| **Petugas** | Front office / pendaftaran | Manajemen pasien, pendaftaran |
| **Dokter** | Dokter pemeriksa | Pemeriksaan, resep |
| **Pasien (Umum)** | Masyarakat pengguna layanan | Registrasi mandiri (tanpa login) |

### 3.1 Matriks Otorisasi

| Fitur | Admin | Petugas | Dokter |
|---|---|---|---|
| Kelola Pegawai | ✓ | — | — |
| Kelola Obat | ✓ | — | — |
| Lihat Laporan | ✓ | — | — |
| Kelola Pasien | ✓ | ✓ | — |
| Pendaftaran | ✓ | ✓ | — |
| Pemeriksaan | — | — | ✓ |
| Resep | — | — | ✓ |

---

## 4. Fitur-Fitur (Functional Requirements)

### 4.1 Halaman Publik (Tidak Perlu Login)

| ID | Fitur | Deskripsi |
|---|---|---|
| F1.1 | Landing Page | Halaman utama dengan branding puskesmas, navigasi ke login dan pendaftaran mandiri |
| F1.2 | Pendaftaran Mandiri | Formulir registrasi untuk pasien baru: nama, tanggal lahir, jenis kelamin, alamat, no HP, golongan darah, keluhan. Menghasilkan nomor antrian secara otomatis |
| F1.3 | Halaman Sukses | Konfirmasi pendaftaran dengan nomor antrian yang bisa dicetak |

### 4.2 Autentikasi

| ID | Fitur | Deskripsi |
|---|---|---|
| F2.1 | Login | Form login dengan email & password, validasi kredensial, redirect ke dashboard sesuai role |
| F2.2 | Logout | Hapus session, redirect ke halaman login |

### 4.3 Dashboard (Role-Based)

| ID | Fitur | Deskripsi |
|---|---|---|
| F3.1 | Dashboard Admin | Menampilkan total pegawai, total pasien, total obat, total pendaftaran |
| F3.2 | Dashboard Petugas | Menampilkan total pasien, jumlah pendaftaran hari ini, daftar antrian hari ini |
| F3.3 | Dashboard Dokter | Menampilkan jumlah pemeriksaan hari ini, jumlah pasien menunggu, daftar pasien menunggu |
| F3.4 | Pencarian Global | Pencarian across pasien (nama/HP), obat (nama), pegawai (nama/jabatan) |

### 4.4 Manajemen Pegawai (Admin)

| ID | Fitur | Deskripsi |
|---|---|---|
| F4.1 | Lihat Daftar Pegawai | Tabel seluruh pegawai dengan filter jabatan (petugas/dokter) |
| F4.2 | Tambah Pegawai | Form lengkap: nama, jabatan, no HP, tanggal masuk, alamat. Untuk petugas: loket. Untuk dokter: spesialisasi. Auto-generate akun User |
| F4.3 | Edit Pegawai | Ubah data pegawai |
| F4.4 | Hapus Pegawai | Hapus data pegawai beserta akun User terkait |
| F4.5 | Detail Pegawai | Lihat informasi lengkap pegawai |

### 4.5 Manajemen Obat (Admin)

| ID | Fitur | Deskripsi |
|---|---|---|
| F5.1 | Lihat Daftar Obat | Tabel obat dengan stok dan harga |
| F5.2 | Tambah Obat | Nama obat, stok awal, harga |
| F5.3 | Edit Obat | Ubah data obat |
| F5.4 | Hapus Obat | Hapus data obat (dibatasi jika sudah digunakan di resep) |

### 4.6 Manajemen Pasien (Admin & Petugas)

| ID | Fitur | Deskripsi |
|---|---|---|
| F6.1 | Lihat Daftar Pasien | Tabel seluruh pasien |
| F6.2 | Tambah Pasien | Data lengkap pasien |
| F6.3 | Edit Pasien | Ubah data pasien |
| F6.4 | Detail Pasien | Lihat data pasien + riwayat pendaftaran, pemeriksaan, dan resep |

### 4.7 Pendaftaran (Petugas)

| ID | Fitur | Deskripsi |
|---|---|---|
| F7.1 | Lihat Daftar Pendaftaran | Semua pendaftaran, diurutkan berdasarkan tanggal |
| F7.2 | Tambah Pendaftaran | Pilih pasien existing atau buat baru, isi keluhan, tipe = 'petugas' |
| F7.3 | Detail Pendaftaran | Lihat data pendaftaran + riwayat pemeriksaan dan resep |

### 4.8 Pemeriksaan (Dokter)

| ID | Fitur | Deskripsi |
|---|---|---|
| F8.1 | Lihat Daftar Pemeriksaan | Pemeriksaan hari ini |
| F8.2 | Lihat Pasien Menunggu | Daftar pendaftaran yang belum memiliki pemeriksaan |
| F8.3 | Buat Pemeriksaan | Pilih pendaftaran, isi diagnosa, simpan |
| F8.4 | Detail Pemeriksaan | Lihat diagnosa, tanggal, dan resep terkait |

### 4.9 Resep (Dokter)

| ID | Fitur | Deskripsi |
|---|---|---|
| F9.1 | Buat Resep | Pilih pemeriksaan, tambahkan item obat (pilih obat, jumlah, dosis). Validasi stok, otomatis kurangi stok. Cegah duplikasi resep |
| F9.2 | Detail Resep | Lihat semua item obat dalam resep (nama obat, jumlah, dosis, subtotal harga) |

### 4.10 Laporan (Admin)

| ID | Fitur | Deskripsi |
|---|---|---|
| F10.1 | Laporan Pendaftaran | Total pendaftaran, total pemeriksaan, jumlah pendaftaran per bulan |
| F10.2 | Laporan Obat | Total penggunaan obat (kuantitas), total pendapatan obat (jumlah × harga) |

---

## 5. Kebutuhan Non-Fungsional

| ID | Kebutuhan | Deskripsi |
|---|---|---|
| NF1 | Keamanan | Autentikasi session-based, RBAC dengan Spatie permission + role enum, middleware custom untuk route guard |
| NF2 | Performa | Waktu render halaman < 3 detik pada koneksi rata-rata |
| NF3 | Responsive | Tampilan mobile-friendly dengan Tailwind + DaisyUI |
| NF4 | Dark Mode | Dukungan tema terang dan gelap, tersimpan di localStorage |
| NF5 | Database Integrity | Foreign key constraints, cascade delete/restrict sesuai relasi |
| NF6 | Testing | Unit test + Feature test dengan SQLite in-memory |
| NF7 | Bahasa | UI Bahasa Indonesia, kode program Bahasa Inggris |
| NF8 | Cetak | Halaman sukses pendaftaran dapat dicetak (print CSS) |

---

## 6. Struktur Data

### 6.1 Entity Relationship (Ringkasan)

```
Pegawai (parent)
├── Petugas (child) — loket
└── Dokter (child) — spesialisasi

User — belongsTo — Pegawai

Pasien — hasMany — Pendaftaran
Pendaftaran — belongsTo — Pasien
Pendaftaran — hasOne — Pemeriksaan
Pemeriksaan — belongsTo — Pendaftaran
Pemeriksaan — belongsTo — Dokter
Pemeriksaan — hasOne — Resep
Resep — hasMany — DetailResep
DetailResep — belongsTo — Obat
```

### 6.2 Tabel Database

| Tabel | Primary Key | Notes |
|---|---|---|
| `users` | `id` | `role` enum (admin/petugas/dokter), `id_pegawai` nullable FK |
| `pegawai` | `id_pegawai` | Parent table untuk petugas & dokter |
| `petugas` | `id_petugas` | FK `id_pegawai` → pegawai (cascade) |
| `dokter` | `id_dokter` | FK `id_pegawai` → pegawai (cascade) |
| `pasien` | `id_pasien` | Data demografi pasien |
| `pendaftaran` | `id_pendaftaran` | FK `id_pasien`, `id_petugas`, tipe mandiri/petugas |
| `pemeriksaan` | `id_pemeriksaan` | FK `id_pendaftaran`, `id_dokter` |
| `obat` | `id_obat` | Stok dan harga |
| `resep` | `id_resep` | FK `id_pemeriksaan` (unique — satu resep per pemeriksaan) |
| `detail_resep` | `id_detail` | FK `id_resep`, `id_obat` |

---

## 7. Alur Kerja (Workflow)

### 7.1 Alur Pendaftaran Mandiri
```
Pasien → Landing Page → /daftar → Isi form → Submit
    → Simpan pasien + pendaftaran (tipe=mandiri)
    → Redirect ke halaman sukses dengan nomor antrian
    → Pasien datang ke puskesmas, menunggu dipanggil
```

### 7.2 Alur Pendaftaran oleh Petugas
```
Petugas login → Dashboard → Pendaftaran → Tambah
    → Pilih pasien existing / buat baru
    → Isi keluhan → Submit (tipe=petugas)
    → Pasien masuk antrian
```

### 7.3 Alur Pemeriksaan & Resep
```
Dokter login → Dashboard → Lihat pasien menunggu
    → Pilih pendaftaran → Buat pemeriksaan (isi diagnosa)
    → Buat resep → Pilih obat + jumlah + dosis
    → Submit → Stok berkurang
```

### 7.4 Alur Manajemen Stok Obat
```
Admin tambah/edit obat (stok awal)
    ↓
Dokter buat resep → stok divalidasi & dikurangi otomatis
    ↓
Stok habis → Admin mendapat informasi via laporan
```

---

## 8. Batasan & Asumsi

1. Satu pendaftaran hanya bisa memiliki satu pemeriksaan (hasOne)
2. Satu pemeriksaan hanya bisa memiliki satu resep (unique constraint on `id_pemeriksaan`)
3. Pendaftaran tipe 'mandiri' tidak memiliki `id_petugas` (nullable)
4. Setiap pegawai yang dibuat akan auto-generate akun User dengan email `nama@puskesmas.test`
5. Sistem tidak menangani pembayaran / billing
6. Sistem tidak memiliki notifikasi real-time (belum menggunakan WebSocket/pusher)
7. Semua laporan bersifat read-only aggregate data (belum ada ekspor PDF/Excel)

---

## 9. Kebutuhan Teknis

| Komponen | Spesifikasi |
|---|---|
| Backend Framework | Laravel 13.x (PHP 8.3+) |
| Database | MySQL (dev), SQLite (testing) |
| Frontend | Blade + Tailwind CSS v4 + DaisyUI 5 + Flowbite |
| Build Tool | Vite |
| RBAC | Spatie laravel-permission + custom role enum |
| Session / Cache / Queue | Database driver |
| Testing | PHPUnit 12.x |
| Dev Server | `composer dev` (PHP serve + queue + Vite via concurrently) |

---

## 10. Future Scope (v2.0+)

- Ekspor laporan ke PDF / Excel
- Cetak resep (format standar)
- History medis pasien yang lebih detail (riwayat penyakit, alergi)
- Notifikasi real-time (antrian, status pemeriksaan)
- Modul pembayaran / billing
- Modul rawat inap
- API untuk integrasi dengan BPJS / farmasi eksternal
- Multi-puskesmas (tenant)
- Role kasir / apoteker terpisah
- Manajemen jadwal dokter

---

## 11. Glossary

| Istilah | Definisi |
|---|---|
| Puskesmas | Pusat Kesehatan Masyarakat (Community Health Center) |
| Petugas | Front office staff yang menangani pendaftaran |
| Dokter | Dokter yang melakukan pemeriksaan medis |
| Pendaftaran | Proses registrasi pasien untuk mendapatkan layanan |
| Pemeriksaan | Proses diagnosa oleh dokter |
| Resep | Daftar obat yang diberikan dokter kepada pasien |
| Detail Resep | Item obat dalam resep (obat, jumlah, dosis) |
| Loket | Counter / jalur pendaftaran yang ditangani petugas |
| Mandiri | Pendaftaran yang dilakukan pasien sendiri via web |
