# 🧪 TESTING GUIDE - Quick Verification

**Sebelum Mulai:**
1. Clear browser cache (Ctrl + Shift + Delete)
2. Restart browser
3. Login dengan role yang akan ditest

---

## 🎯 TEST PRIORITAS TINGGI (Harus Berhasil!)

### 1. TEST UPLOAD FOTO PROFIL (Admin)
```
1. Login sebagai admin
2. Klik menu "Data Pekerja"
3. Klik "Tambah Pekerja"
4. Isi semua field
5. Upload foto (JPG/PNG, max 2MB)
6. Klik "Simpan"
7. ✅ SUKSES jika: Pesan sukses muncul, foto ter-upload
8. ✅ CEK: Folder `public/uploads/foto/` → Ada file baru
9. ✅ CEK: Ukuran foto ~300x300px
```

### 2. TEST UPLOAD DOKUMEN PENGAJUAN (Pekerja)
```
1. Login sebagai pekerja
2. Klik "Ajukan Kenaikan Golongan"
3. Isi alasan pengajuan
4. Upload 3 dokumen:
   - Surat Permohonan (PDF/JPG)
   - Penilaian Kinerja (PDF/JPG)
   - Sertifikat (PDF/JPG)
5. Klik "Ajukan"
6. ✅ SUKSES jika: Pesan sukses, redirect ke list pengajuan
7. ✅ CEK: Folder `public/uploads/documents/` → Ada 3 file baru
```

### 3. TEST KEPALA WILAYAH - LAPORAN PENGAJUAN
```
1. Login sebagai kepala wilayah
2. Klik menu "Laporan Pengajuan"
3. ✅ SUKSES jika: 
   - TIDAK ada error "Anda tidak memiliki akses"
   - Halaman laporan muncul
   - Statistik dashboard muncul
   - Tabel data muncul
   - Search box muncul
```

### 4. TEST MANAGER - SEMUA PENGAJUAN
```
1. Login sebagai manager
2. Klik menu "Semua Pengajuan"
3. ✅ SUKSES jika:
   - Data MUNCUL (bukan kosong!)
   - Ada kolom "Pekerja" dengan NIP & Nama
   - Search box muncul
   - Pagination muncul
```

---

## 🔍 TEST SEARCH & FILTER (Semua Role)

### Test 1: Search by Nama
```
1. Buka halaman Data Pekerja (admin)
2. Ketik nama pekerja di search box
3. Tekan Enter
4. ✅ SUKSES jika: Hanya data yang cocok yang muncul
```

### Test 2: Search by NIP
```
1. Buka halaman Pengajuan
2. Ketik NIP di search box
3. Tekan Enter
4. ✅ SUKSES jika: Filter works
```

### Test 3: Pagination
```
1. Buka halaman dengan banyak data
2. Lihat dropdown "Show X entries"
3. Pilih "25" atau "50"
4. ✅ SUKSES jika: Jumlah data per page berubah
```

### Test 4: Sort by Column
```
1. Buka halaman tabel
2. Klik header column (contoh: "Tanggal")
3. ✅ SUKSES jika: 
   - Data ter-sort ascending
   - Klik lagi → descending
   - Icon panah muncul
```

---

## 👥 TEST PER ROLE

### ROLE: PEKERJA
- [ ] Dashboard muncul ✅
- [ ] Bisa ajukan pengajuan ✅
- [ ] Upload dokumen works ✅
- [ ] Lihat pengajuan sendiri ✅
- [ ] Search di list pengajuan works ✅
- [ ] TIDAK ada field "Pekerja" di list ✅

### ROLE: ATASAN
- [ ] Dashboard muncul ✅
- [ ] Menu "Semua Pengajuan Bawahan" ada ✅
- [ ] Klik → Data bawahan muncul ✅
- [ ] Ada field nama pekerja ✅
- [ ] Search works ✅
- [ ] Review → Dokumen muncul ✅
- [ ] Bisa approve/reject ✅

### ROLE: MANAGER
- [ ] Dashboard muncul ✅
- [ ] Menu "Semua Pengajuan" ada ✅
- [ ] Klik → **DATA MUNCUL** (bukan kosong!) ✅
- [ ] **Ada field nama pekerja** ✅
- [ ] Search works ✅
- [ ] Pagination works ✅
- [ ] Review → Dokumen muncul ✅

### ROLE: KEPALA WILAYAH
- [ ] Dashboard muncul ✅
- [ ] Menu "Semua Pengajuan" ada ✅
- [ ] Data muncul ✅
- [ ] Ada field nama pekerja ✅
- [ ] **Menu "Laporan Pengajuan" ada** ✅
- [ ] **Klik → TIDAK ERROR!** ✅
- [ ] **Laporan muncul dengan search** ✅
- [ ] Review → Dokumen muncul ✅

### ROLE: ADMIN
- [ ] Dashboard muncul ✅
- [ ] Data Pekerja → Search works ✅
- [ ] Data Jabatan → Search works ✅
- [ ] Data Divisi → Search works ✅
- [ ] Data Golongan → Search works ✅
- [ ] **Tambah pekerja → Foto upload ke `foto/`** ✅
- [ ] **Foto otomatis diresize** ✅
- [ ] Semua Pengajuan → Data muncul ✅
- [ ] Laporan Pengajuan → Search works ✅
- [ ] Laporan Pekerja → Search works ✅

---

## ⚠️ TEST SECURITY

### Test 1: View Dokumen - Access Control
```
1. Login sebagai Pekerja A
2. Buat pengajuan dengan dokumen
3. Logout
4. Login sebagai Pekerja B
5. Copy URL dokumen Pekerja A
6. Paste di browser
7. ✅ SUKSES jika: Error "Tidak memiliki akses"
```

### Test 2: Direct File Access
```
1. Copy URL file dokumen
2. Logout
3. Paste URL di browser tanpa login
4. ✅ SUKSES jika: Redirect ke login
```

---

## 🐛 JIKA ADA ERROR

### Error: "Tidak ada file yang diupload"
**Solusi:**
- Cek ukuran file (max 2MB)
- Cek format file (PDF, JPG, PNG)
- Cek permission folder `public/uploads/`

### Error: "Tipe file tidak diizinkan"
**Solusi:**
- Gunakan PDF, JPG, atau PNG saja
- Jangan gunakan DOCX, RAR, ZIP, dll

### Error: Foto tidak muncul
**Solusi:**
- Cek path di database (harus: `foto/filename.jpg`)
- Cek file ada di `public/uploads/foto/`
- Cek permission folder 755

### Error: Data kosong di Manager
**Solusi:**
- Clear browser cache
- Refresh halaman (F5)
- Logout dan login lagi

### Error: Search tidak works
**Solusi:**
- Cek jQuery sudah load (buka console browser)
- Cek DataTables sudah load
- Refresh halaman

---

## ✅ KRITERIA SUKSES

Sistem dianggap **PASS** jika:

1. ✅ Upload foto profil → File tersimpan di `foto/`
2. ✅ Upload dokumen pengajuan → File tersimpan di `documents/`
3. ✅ Foto otomatis diresize ke 300x300
4. ✅ Kepala Wilayah bisa akses laporan (tidak error)
5. ✅ Manager lihat semua pengajuan (tidak kosong)
6. ✅ Manager lihat field nama pekerja
7. ✅ Kepala Wilayah lihat field nama pekerja
8. ✅ Search box muncul di semua halaman data
9. ✅ Pagination works di semua halaman
10. ✅ Sort by column works
11. ✅ Access control dokumen works (tidak bisa akses dokumen orang lain)

---

## 📊 REPORT TESTING

Setelah testing, isi checklist:

```
TESTING COMPLETED: [Tanggal]
TESTER: [Nama]

HASIL TESTING:
[ ] Upload Foto Profil - PASS / FAIL
[ ] Upload Dokumen Pengajuan - PASS / FAIL
[ ] Kepala Wilayah Laporan - PASS / FAIL
[ ] Manager Semua Pengajuan - PASS / FAIL
[ ] Search & Filter (semua halaman) - PASS / FAIL
[ ] Access Control Dokumen - PASS / FAIL

BUGS DITEMUKAN:
1. [Deskripsi bug jika ada]
2. [Deskripsi bug jika ada]

STATUS FINAL: PASS / FAIL
```

---

**Testing Priority:**
1. 🔴 CRITICAL: Upload Foto, Upload Dokumen, Kepala Wilayah Laporan
2. 🟡 HIGH: Manager Data Muncul, Search & Filter
3. 🟢 MEDIUM: Sort, Pagination

Mulai dari CRITICAL dulu, pastikan semua PASS sebelum lanjut!
