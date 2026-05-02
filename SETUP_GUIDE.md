# PANDUAN SETUP APLIKASI
## Sistem Kenaikan Golongan BRI - Wilayah Padang

### STEP 1: Persiapan Environment

1. **Install Laragon** (Recommended)
   - Download dari: https://laragon.org/download/
   - Install dengan setting default
   - Pastikan sudah termasuk PHP 7.4+ dan MySQL 5.7+

2. **Atau Install XAMPP/WAMP**
   - PHP minimal versi 7.4
   - MySQL minimal versi 5.7
   - Apache dengan mod_rewrite enabled

### STEP 2: Setup Project

1. **Extract Project ke Folder Web Server**
   ```
   Laragon: C:\laragon\www\kenaikanjabatan\
   XAMPP: C:\xampp\htdocs\kenaikanjabatan\
   WAMP: C:\wamp64\www\kenaikanjabatan\
   ```

2. **Struktur Folder Harus Seperti Ini:**
   ```
   kenaikanjabatan/
   ├── app/
   ├── core/
   ├── public/
   │   ├── index.php
   │   ├── .htaccess
   │   ├── assets/
   │   └── uploads/
   └── Design and Reference/
   ```

### STEP 3: Setup Database

1. **Buka phpMyAdmin**
   - Laragon: http://localhost/phpmyadmin
   - XAMPP: http://localhost/phpmyadmin
   - Username: root
   - Password: (kosong untuk default)

2. **Buat Database Baru**
   - Klik tab "Databases"
   - Nama database: `sistem_kenaikan_golongan_bri`
   - Collation: `utf8mb4_unicode_ci`
   - Klik "Create"

3. **Import Schema Database**
   - Pilih database yang baru dibuat
   - Klik tab "Import"
   - Choose file: `Design and Reference/03_Database_Schema.sql`
   - Klik "Go"
   - Tunggu sampai selesai (akan membuat 10 tabel + 2 views + stored procedures)

4. **Import Sample Data**
   - Masih di tab "Import"
   - Choose file: `Design and Reference/04_Sample_Data.sql`
   - Klik "Go"
   - Tunggu sampai selesai

5. **Verifikasi Database**
   - Klik database di sidebar kiri
   - Pastikan ada tabel:
     * users
     * pekerja
     * golongan_jabatan
     * divisi
     * jabatan
     * pengajuan
     * dokumen_pengajuan
     * approval_history
     * notifikasi
     * log_aktivitas

### STEP 4: Konfigurasi Aplikasi

1. **Buka File Config**
   ```
   File: app/config/Config.php
   ```

2. **Edit Konfigurasi Database (Jika Perlu)**
   ```php
   // Default setting untuk Laragon/XAMPP
   define('DB_HOST', 'localhost');
   define('DB_NAME', 'sistem_kenaikan_golongan_bri');
   define('DB_USER', 'root');
   define('DB_PASS', ''); // Kosongkan jika tidak ada password
   ```

3. **Sesuaikan Setting Lainnya**
   ```php
   // Ubah ke production jika sudah live
   define('APP_ENV', 'development');
   ```

### STEP 5: Test Akses Aplikasi

1. **Start Web Server**
   - Laragon: Klik "Start All"
   - XAMPP: Start Apache dan MySQL
   - WAMP: Start All Services

2. **Buka Browser**
   ```
   URL: http://localhost/kenaikanjabatan/public/
   ```

3. **Halaman Login Harus Muncul**
   - Jika tampil halaman login dengan logo BRI, setup BERHASIL!
   - Jika error, lanjut ke troubleshooting di bawah

### STEP 6: Test Login

**Login sebagai Admin:**
- Username: `admin`
- Password: `admin123`

Jika berhasil, akan masuk ke Dashboard Admin.

**Akun Test Lainnya:**
| Role | Username | Password |
|------|----------|----------|
| Admin | admin | admin123 |
| Pekerja | budi.santoso | password123 |
| Atasan | siti.nurhaliza | password123 |
| Manager | agus.prasetyo | password123 |
| Kepala Wilayah | bambang.sutopo | password123 |

### TROUBLESHOOTING

#### 1. Error "Cannot connect to database"
**Solusi:**
- Pastikan MySQL service running
- Cek konfigurasi di `app/config/Config.php`
- Test koneksi database di phpMyAdmin
- Pastikan nama database sudah benar

#### 2. Error 404 - Page not found
**Solusi:**
- Pastikan mengakses: `http://localhost/kenaikanjabatan/public/`
- Cek file `.htaccess` ada di folder `public/`
- Aktifkan mod_rewrite di Apache:
  ```
  Laragon: Menu > Apache > httpd.conf
  Cari: LoadModule rewrite_module
  Pastikan tidak ada # di depannya
  ```

#### 3. Error "Headers already sent"
**Solusi:**
- Pastikan tidak ada spasi atau BOM di awal file PHP
- Save semua file dengan encoding UTF-8 (no BOM)

#### 4. CSS/JS tidak load
**Solusi:**
- Cek koneksi internet (menggunakan CDN untuk Bootstrap & jQuery)
- Atau download library dan letakkan di `public/assets/`

#### 5. Upload file error
**Solusi:**
- Cek permission folder `public/uploads/`
- Windows: Klik kanan > Properties > Security
- Berikan Full Control untuk user Anda
- Edit `php.ini`:
  ```
  upload_max_filesize = 10M
  post_max_size = 10M
  ```

#### 6. Session tidak jalan
**Solusi:**
- Pastikan PHP session enabled
- Cek folder temporary untuk session (php.ini):
  ```
  session.save_path = "C:/laragon/tmp"
  ```

### OPTIONAL: Setup Virtual Host (Recommended)

#### Untuk Laragon:
1. Klik kanan icon Laragon
2. Apache > sites-enabled > Add
3. Nama: sikgol-bri
4. Isi konfigurasi:
   ```apache
   <VirtualHost *:80>
       DocumentRoot "C:/laragon/www/kenaikanjabatan/public"
       ServerName sikgol-bri.test
       <Directory "C:/laragon/www/kenaikanjabatan/public">
           Options Indexes FollowSymLinks
           AllowOverride All
           Require all granted
       </Directory>
   </VirtualHost>
   ```
5. Restart Apache
6. Akses: http://sikgol-bri.test

#### Edit hosts file:
1. Buka: `C:\Windows\System32\drivers\etc\hosts` (as Administrator)
2. Tambahkan: `127.0.0.1    sikgol-bri.test`
3. Save

### CHECKLIST SETUP

- [ ] Laragon/XAMPP installed
- [ ] Project extracted ke folder web server
- [ ] Database created
- [ ] Schema imported (10 tables)
- [ ] Sample data imported
- [ ] Config.php updated
- [ ] Apache & MySQL running
- [ ] Akses http://localhost/kenaikanjabatan/public/ berhasil
- [ ] Login dengan akun admin berhasil
- [ ] Dashboard tampil dengan benar

### SELESAI!

Jika semua checklist di atas sudah ✓, maka aplikasi sudah siap digunakan.

Untuk pertanyaan atau bantuan lebih lanjut:
- Baca file README.md
- Cek dokumentasi di folder `Design and Reference/`

---
**Selamat Menggunakan Sistem Kenaikan Golongan BRI!** 🎉
