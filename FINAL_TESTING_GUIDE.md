# 🧪 FINAL TESTING GUIDE - STEP BY STEP

**WAJIB DIBACA SEBELUM TESTING!**

---

## ⚠️ PERSIAPAN SEBELUM TESTING

### 1. Clear Browser Cache
```
Chrome: Ctrl + Shift + Delete → Pilih "All time" → Clear
Firefox: Ctrl + Shift + Delete → Pilih "Everything" → Clear
Edge: Ctrl + Shift + Delete → Pilih "All time" → Clear
```

### 2. Restart Browser
Tutup browser sepenuhnya, lalu buka lagi.

### 3. Cek Folder Structure
```
public/uploads/
  ├── foto/       (untuk foto profil pekerja)
  ├── documents/  (untuk dokumen pengajuan)
  └── sk/         (untuk SK)
```

---

## 🔥 TEST CRITICAL BUG #1: SPV DETAIL PENGAJUAN

**Bug:** Atasan error "tidak memiliki akses" saat klik detail

### Step by Step:
```
1. Login sebagai: ATASAN/SPV
   Username: [NIP atasan]
   Password: [NIP atasan]

2. Klik menu: "Semua Pengajuan Bawahan"

3. Lihat tabel:
   ✅ Harus ada data pengajuan dari bawahan
   ✅ Harus ada berbagai status (pending, disetujui, ditolak)

4. Klik tombol "Detail" pada pengajuan STATUS PENDING:
   ✅ SUKSES jika: Halaman detail muncul
   ✅ SUKSES jika: Dokumen muncul (jika ada)
   ✅ SUKSES jika: Tidak ada error

5. Kembali ke list, klik "Detail" pada pengajuan STATUS DISETUJUI:
   ✅ SUKSES jika: Halaman detail muncul
   ✅ SUKSES jika: Dokumen muncul (jika ada)
   ✅ SUKSES jika: TIDAK ERROR "Anda tidak memiliki akses" ← INI YANG DIPERBAIKI!

6. Kembali ke list, klik "Detail" pada pengajuan STATUS DITOLAK:
   ✅ SUKSES jika: Halaman detail muncul
   ✅ SUKSES jika: Tidak ada error
```

**Expected Result:**
- ✅ Atasan bisa lihat detail SEMUA pengajuan bawahan (apapun statusnya)
- ✅ Tidak ada error "tidak memiliki akses"
- ✅ Dokumen muncul di halaman detail

**Jika Gagal:**
- ❌ Ada error "tidak memiliki akses" → REPORT dengan screenshot
- ❌ Halaman redirect ke approval → REPORT
- ❌ Dokumen tidak muncul → Cek bug #2

---

## 🔥 TEST CRITICAL BUG #2: UPLOAD DOKUMEN

**Bug:** Dokumen tidak tersimpan saat pekerja ajukan pengajuan

### Step by Step:
```
1. Login sebagai: PEKERJA
   Username: [NIP pekerja]
   Password: [NIP pekerja]

2. Klik menu: "Ajukan Kenaikan Golongan"

3. Isi form:
   - Golongan Tujuan: [Pilih golongan berikutnya]
   - Alasan: "Testing upload dokumen"

4. Upload 3 dokumen:
   
   A. Surat Permohonan:
      ✅ File: PDF atau JPG/PNG
      ✅ Ukuran: < 2MB
      ✅ Klik "Choose File" → Pilih file
   
   B. Penilaian Kinerja:
      ✅ File: PDF atau JPG/PNG
      ✅ Ukuran: < 2MB
      ✅ Klik "Choose File" → Pilih file
   
   C. Sertifikat:
      ✅ File: PDF atau JPG/PNG
      ✅ Ukuran: < 2MB
      ✅ Klik "Choose File" → Pilih file

5. Klik tombol "Ajukan"

6. ✅ SUKSES jika:
   - Muncul pesan "Pengajuan berhasil dibuat"
   - Redirect ke halaman list pengajuan
   - Tidak ada error

7. CEK DATABASE (via phpMyAdmin/HeidiSQL):
   ```sql
   SELECT * FROM dokumen_pengajuan 
   ORDER BY uploaded_at DESC 
   LIMIT 3;
   ```
   ✅ SUKSES jika: Ada 3 record baru ← INI YANG DIPERBAIKI!
   ✅ Kolom file_path terisi: "documents/filename.pdf"
   ✅ Kolom nama_dokumen terisi: "filename.pdf"

8. CEK FILE SYSTEM:
   Buka folder: `public/uploads/documents/`
   ✅ SUKSES jika: Ada 3 file baru
   ✅ File bisa dibuka (tidak corrupt)

9. CEK HALAMAN DETAIL:
   - Klik "Pengajuan Saya" di menu
   - Klik "Detail" pada pengajuan yang baru dibuat
   ✅ SUKSES jika: Bagian "Dokumen Pendukung" muncul
   ✅ SUKSES jika: Ada 3 dokumen terdaftar
   ✅ Klik "Lihat" → Dokumen terbuka di tab baru
   ✅ Klik "Unduh" → Dokumen ter-download

10. LOGIN SEBAGAI ATASAN:
    - Klik "Pending Approval"
    - Klik "Review" pada pengajuan yang baru
    ✅ SUKSES jika: Dokumen muncul di halaman review
    ✅ SUKSES jika: Atasan bisa lihat dan download dokumen
```

**Expected Result:**
- ✅ File ter-upload ke folder `public/uploads/documents/`
- ✅ Record ter-insert ke tabel `dokumen_pengajuan`
- ✅ Dokumen muncul di halaman detail pekerja
- ✅ Dokumen muncul di halaman review atasan
- ✅ Download dokumen works untuk semua role

**Jika Gagal:**
- ❌ Database tidak ada record → BUG MASIH ADA! REPORT!
- ❌ Folder tidak ada file → Cek permission folder (755)
- ❌ Dokumen tidak muncul di halaman → Cek query di DokumenModel
- ❌ Error saat upload → Cek error log PHP

---

## 🔥 TEST CRITICAL BUG #3: FILTER DATA PEKERJA

**Bug:** Tidak ada filter untuk big data di halaman admin

### Step by Step:
```
1. Login sebagai: ADMIN
   Username: admin
   Password: [password admin]

2. Klik menu: "Data Pekerja"

3. Lihat halaman:
   ✅ SUKSES jika: Ada section "Filter" di atas tabel
   ✅ SUKSES jika: Ada 4 dropdown:
      - Filter Divisi
      - Filter Jabatan
      - Filter Golongan
      - Filter Status
   ✅ SUKSES jika: Ada tombol "Reset Filter"

4. TEST FILTER DIVISI:
   - Pilih divisi "IT" (atau divisi lain yang ada datanya)
   - ✅ SUKSES jika: Tabel hanya menampilkan pekerja divisi IT
   - ✅ SUKSES jika: Pagination update otomatis
   - Klik "Reset Filter"
   - ✅ SUKSES jika: Semua data muncul lagi

5. TEST FILTER JABATAN:
   - Pilih jabatan "Manager" (atau jabatan lain)
   - ✅ SUKSES jika: Tabel hanya menampilkan manager
   - Klik "Reset Filter"

6. TEST FILTER GOLONGAN:
   - Pilih golongan "IV/a" (atau golongan lain)
   - ✅ SUKSES jika: Tabel hanya menampilkan golongan IV/a
   - Klik "Reset Filter"

7. TEST FILTER STATUS:
   - Pilih status "Aktif"
   - ✅ SUKSES jika: Tabel hanya menampilkan pekerja aktif
   - Klik "Reset Filter"

8. TEST KOMBINASI FILTER:
   - Pilih divisi "IT"
   - Pilih status "Aktif"
   - ✅ SUKSES jika: Hanya tampil pekerja IT yang aktif
   - Klik "Reset Filter"

9. TEST DENGAN SEARCH BOX:
   - Pilih divisi "IT"
   - Ketik nama pekerja di search box
   - ✅ SUKSES jika: Filter dan search bekerja bersamaan
   - ✅ SUKSES jika: Hasil adalah pekerja IT dengan nama yang dicari

10. TEST PAGINATION:
    - Pilih divisi dengan banyak data
    - ✅ SUKSES jika: Pagination muncul
    - Klik page 2
    - ✅ SUKSES jika: Filter tetap aktif di page 2
```

**Expected Result:**
- ✅ 4 dropdown filter muncul di halaman data pekerja
- ✅ Filter bekerja dengan benar
- ✅ Kombinasi filter works
- ✅ Reset filter works
- ✅ Filter + search box works bersamaan
- ✅ Pagination tetap works dengan filter

**Jika Gagal:**
- ❌ Filter tidak muncul → Refresh halaman, clear cache
- ❌ Filter tidak bekerja → Cek console browser untuk error JavaScript
- ❌ Kombinasi filter error → REPORT dengan screenshot

---

## ✅ TEST SEMUA ROLE - QUICK CHECK

### ROLE: PEKERJA
```
1. Login pekerja
2. Dashboard muncul? ✅
3. Ajukan pengajuan + upload dokumen → Works? ✅
4. Lihat pengajuan saya → Ada search? ✅
5. Detail pengajuan → Dokumen muncul? ✅
```

### ROLE: ATASAN/SPV
```
1. Login atasan
2. Dashboard muncul? ✅
3. Pending approval → Ada data? ✅
4. Semua pengajuan bawahan → Works? ✅
5. Detail pengajuan (semua status) → TIDAK ERROR? ✅ ← CRITICAL!
6. Dokumen muncul di detail? ✅
```

### ROLE: MANAGER
```
1. Login manager
2. Dashboard muncul? ✅
3. Semua pengajuan → Data muncul? ✅
4. Field nama pekerja muncul? ✅
5. Search works? ✅
6. Detail pengajuan → Dokumen muncul? ✅
```

### ROLE: KEPALA WILAYAH
```
1. Login kepala wilayah
2. Dashboard muncul? ✅
3. Laporan pengajuan → TIDAK ERROR? ✅
4. Semua pengajuan → Data muncul? ✅
5. Search works? ✅
```

### ROLE: ADMIN
```
1. Login admin
2. Data pekerja → Filter 4 dropdown muncul? ✅ ← CRITICAL!
3. Filter by divisi → Works? ✅
4. Upload foto pekerja → Tersimpan di foto/? ✅
5. Semua pengajuan → Search works? ✅
6. Laporan → Search works? ✅
```

---

## 📊 TESTING REPORT TEMPLATE

Setelah testing, isi form ini:

```
==============================================
TESTING REPORT - SISTEM KENAIKAN GOLONGAN
==============================================

Tanggal Testing: [Isi tanggal]
Tester: [Isi nama]
Browser: [Chrome/Firefox/Edge]
Version: [Isi versi browser]

==============================================
CRITICAL BUG #1: SPV DETAIL PENGAJUAN
==============================================
Status: [ ] PASS  [ ] FAIL
Catatan: [Isi jika ada masalah]

==============================================
CRITICAL BUG #2: UPLOAD DOKUMEN
==============================================
Status: [ ] PASS  [ ] FAIL
Upload berhasil?: [ ] Ya  [ ] Tidak
File di folder?: [ ] Ya  [ ] Tidak
Record di DB?: [ ] Ya  [ ] Tidak
Dokumen muncul di detail?: [ ] Ya  [ ] Tidak
Catatan: [Isi jika ada masalah]

==============================================
CRITICAL BUG #3: FILTER DATA PEKERJA
==============================================
Status: [ ] PASS  [ ] FAIL
Filter muncul?: [ ] Ya  [ ] Tidak
Filter divisi works?: [ ] Ya  [ ] Tidak
Filter jabatan works?: [ ] Ya  [ ] Tidak
Filter golongan works?: [ ] Ya  [ ] Tidak
Filter status works?: [ ] Ya  [ ] Tidak
Kombinasi filter works?: [ ] Ya  [ ] Tidak
Reset filter works?: [ ] Ya  [ ] Tidak
Catatan: [Isi jika ada masalah]

==============================================
TESTING SEMUA ROLE
==============================================
Pekerja: [ ] PASS  [ ] FAIL
Atasan: [ ] PASS  [ ] FAIL
Manager: [ ] PASS  [ ] FAIL
Kepala Wilayah: [ ] PASS  [ ] FAIL
Admin: [ ] PASS  [ ] FAIL

==============================================
BUGS BARU DITEMUKAN (Jika ada)
==============================================
1. [Deskripsi bug]
2. [Deskripsi bug]
3. [Deskripsi bug]

==============================================
KESIMPULAN
==============================================
Status Final: [ ] SEMUA PASS  [ ] ADA YANG FAIL
Rekomendasi: [ ] SIAP PRODUCTION  [ ] PERLU PERBAIKAN

Tanda Tangan Tester: __________________
```

---

## 🎯 KRITERIA PASS/FAIL

### ✅ PASS jika:
- Semua 3 critical bug sudah fixed
- Upload dokumen works 100%
- Atasan bisa lihat detail semua pengajuan
- Filter data pekerja works dengan kombinasi
- Tidak ada error di console browser
- Tidak ada error di log PHP

### ❌ FAIL jika:
- Upload dokumen masih tidak tersimpan
- Atasan masih error akses detail
- Filter tidak bekerja
- Ada error di console/log
- Ada crash/freeze

---

**TESTING PRIORITY:**
1. 🔴 CRITICAL: Bug #1, #2, #3 harus PASS!
2. 🟡 HIGH: Semua role bisa login dan akses menu
3. 🟢 MEDIUM: Search/filter/pagination works

**Jika ada yang FAIL, segera report dengan:**
- Screenshot error
- Browser console log
- PHP error log
- Step untuk reproduce bug

Good luck testing! 🚀
