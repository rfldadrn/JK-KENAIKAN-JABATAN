# SISTEM INFORMASI KENAIKAN GOLONGAN JABATAN PEKERJA BRI
## Aplikasi Kenaikan Golongan Bank Rakyat Indonesia - Wilayah Padang

### Deskripsi
Sistem informasi berbasis web untuk mengelola proses pengajuan dan persetujuan kenaikan golongan jabatan pekerja di BRI Wilayah Padang. Dibangun menggunakan PHP Native dengan arsitektur MVC (Model-View-Controller).

### Teknologi yang Digunakan
- **Backend**: PHP 7.4+ (Native)
- **Database**: MySQL 5.7+
- **Frontend**: HTML5, CSS3, JavaScript (jQuery)
- **Framework CSS**: Bootstrap 5.3
- **Server**: Apache (Laragon/XAMPP/WAMP)

### Fitur Utama
1. **Multi-role Authentication System**
   - Admin/HC
   - Pekerja
   - Atasan Langsung
   - Manager Wilayah
   - Kepala Wilayah

2. **Master Data Management**
   - Golongan Jabatan
   - Divisi/Unit Kerja
   - Jabatan
   - Data Pekerja

3. **Pengajuan Kenaikan Golongan**
   - Form pengajuan dengan upload dokumen
   - Tracking status real-time
   - Riwayat pengajuan

4. **Sistem Approval Bertingkat**
   - Level 1: Atasan Langsung
   - Level 2: Manager Wilayah
   - Level 3: Kepala Wilayah (Final)

5. **Dashboard & Reporting**
   - Dashboard per role dengan statistik
   - Laporan pengajuan
   - Laporan pekerja per golongan

### Instalasi & Setup

#### Persiapan
1. Install Laragon atau web server (Apache + PHP + MySQL)
2. Pastikan PHP versi 7.4 atau lebih baru
3. Aktifkan extension PHP yang diperlukan:
   - `php_pdo_mysql`
   - `php_mbstring`
   - `php_gd` (untuk resize gambar)

#### Langkah Instalasi

1. **Clone atau Extract Project**
   ```
   Letakkan folder project di: C:\laragon\www\kenaikanjabatan
   ```

2. **Setup Database**
   - Buka phpMyAdmin atau MySQL client
   - Buat database baru:
     ```sql
     CREATE DATABASE sistem_kenaikan_golongan_bri CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
     ```
   - Import schema database:
     ```
     File: Design and Reference/03_Database_Schema.sql
     ```
   - Import data sample:
     ```
     File: Design and Reference/04_Sample_Data.sql
     ```

3. **Konfigurasi Environment**
   - Copy file `.env.example` menjadi `.env` (jika ada)
   - Buka file `app/config/Config.php`
   - Sesuaikan konfigurasi database:
     ```php
     define('DB_HOST', 'localhost');
     define('DB_NAME', 'sistem_kenaikan_golongan_bri');
     define('DB_USER', 'root');
     define('DB_PASS', ''); // Kosongkan jika tidak ada password
     ```

4. **Setup Permissions (Linux/Mac)**
   ```bash
   chmod -R 755 public/uploads
   ```

5. **Akses Aplikasi**
   - URL: `http://localhost/kenaikanjabatan/public/`
   - atau setup virtual host di Laragon:
     - Menu Laragon > Apache > sites-enabled > Add
     - Nama: `sikgol-bri`
     - Akses: `http://sikgol-bri.test`

### Login Default

Setelah import sample data, gunakan akun berikut untuk login:

| Role | Username | Password |
|------|----------|----------|
| Admin | admin | admin123 |
| Pekerja | budi.santoso | password123 |
| Atasan | siti.nurhaliza | password123 |
| Manager | agus.prasetyo | password123 |
| Kepala Wilayah | bambang.sutopo | password123 |

### Struktur Folder
```
kenaikanjabatan/
├── app/
│   ├── config/          # Konfigurasi aplikasi & database
│   ├── controllers/     # Controller files
│   ├── models/          # Model files
│   ├── views/           # View files (HTML)
│   └── helpers/         # Helper classes
├── core/                # Core framework (App, Controller, Model)
├── public/              # Public accessible files
│   ├── index.php        # Entry point
│   ├── .htaccess        # URL rewriting
│   ├── assets/          # CSS, JS, images
│   └── uploads/         # Upload directory
└── Design and Reference/ # Documentation & SQL files
```

### Penggunaan

#### Admin/HC
1. Login sebagai Admin
2. Kelola master data (Golongan, Divisi, Jabatan, Pekerja)
3. Monitor semua pengajuan
4. Lihat laporan dan statistik

#### Pekerja
1. Login sebagai Pekerja
2. Buat pengajuan kenaikan golongan
3. Upload dokumen pendukung
4. Pantau status pengajuan

#### Approver (Atasan/Manager/Kepala Wilayah)
1. Login sesuai role
2. Review pengajuan yang pending
3. Approve atau reject dengan catatan
4. Lihat riwayat approval

### Business Rules
- Minimal masa kerja: 2 tahun
- Nilai kinerja minimal: 80/100
- Hanya bisa mengajukan 1 tingkat di atas golongan saat ini
- Tidak boleh ada pengajuan aktif yang sedang diproses
- Maksimal ukuran upload: 2MB per file
- Format file: PDF, JPG, PNG

### Troubleshooting

#### Error "Call to undefined function"
- Pastikan semua file helper sudah ter-load di `public/index.php`

#### Error koneksi database
- Cek konfigurasi di `app/config/Config.php`
- Pastikan MySQL service running
- Cek username dan password database

#### Error 404 (Page not found)
- Pastikan mod_rewrite Apache aktif
- Cek file `.htaccess` di folder `public/`

#### Upload file gagal
- Cek permission folder `public/uploads/`
- Pastikan `upload_max_filesize` dan `post_max_size` di php.ini cukup besar

### Fitur yang Sedang Dikembangkan
- [ ] Email notification system
- [ ] Generate PDF Surat Keputusan (SK)
- [ ] Export laporan ke Excel
- [ ] Import data pekerja dari Excel
- [ ] Grafik dan chart lebih detail
- [ ] Mobile responsive optimization

### Dukungan & Kontak
Untuk bantuan teknis atau pertanyaan, hubungi:
- Email: support@sikgol-bri.com
- Telp: (0751) 123-4567

### Lisensi
Copyright © 2024 Bank Rakyat Indonesia - Wilayah Padang. All rights reserved.

### Changelog
**Version 1.0.0** (2024)
- Initial release
- Core MVC framework
- Authentication system
- Dashboard per role
- Master data management
- Multi-level approval workflow
