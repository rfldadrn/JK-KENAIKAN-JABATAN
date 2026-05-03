# 🎯 PANDUAN TESTING APPROVAL - 1 CYCLE LENGKAP

## ⚠️ MASALAH YANG DITEMUKAN

**Issue:** Menu approval untuk role atasan tidak menampilkan pengajuan dari Sari Dewi

**Root Cause:** Sample data default tidak memiliki pengajuan dengan status `pending` dari Sari Dewi. Pengajuan yang ada sudah berstatus `disetujui` (completed).

---

## 🔧 SOLUSI & LANGKAH TESTING

### **STEP 1: Jalankan SQL Testing Data**

1. Buka **phpMyAdmin** atau MySQL client
2. Select database: `sistem_kenaikan_golongan_bri`
3. Import/Execute file: `Design and Reference/05_Testing_Data_Approval.sql`

File ini akan:
- ✅ Insert 3 pengajuan baru dengan status `pending`
- ✅ Pengajuan dari: Sari Dewi, Andi Wijaya, Fitri Ramadhani
- ✅ Sudah termasuk dokumen pendukung

**Expected Result:**
```sql
-- Akan terinsert pengajuan dengan nomor:
- PG-TEST-001 (Sari Dewi) → Atasan: Maya Sari (20140008)
- PG-TEST-002 (Andi Wijaya) → Atasan: Dedi Kurniawan (20150009)
- PG-TEST-003 (Fitri Ramadhani) → Atasan: Maya Sari (20140008)
```

---

### **STEP 2: Validasi Data (Optional tapi Recommended)**

Buka browser: `http://localhost/kenaikanjabatan/public/debug_approval.php`

File ini akan menampilkan:
- ✅ Struktur atasan-bawahan
- ✅ Daftar pengajuan (semua status)
- ✅ Pending approval per role
- ✅ Approval history

**Cek Section:**
- Section 3: "Pending Approval untuk Maya Sari" → Harus ada 2 pengajuan (Sari Dewi & Fitri)
- Section 4: "Pending Approval untuk Dedi Kurniawan" → Harus ada 1 pengajuan (Andi Wijaya)

---

### **STEP 3: Testing 1 Cycle Lengkap (Pengajuan Sari Dewi)**

#### **3.1 Login sebagai Pekerja (Sari Dewi)**

```
URL: http://localhost/kenaikanjabatan/public/
Username: 20210014
Password: password123
Role: pekerja
```

**Action:**
1. Klik menu: **"Pengajuan Saya"**
2. Lihat pengajuan dengan nomor **PG-TEST-001**
3. Klik **"Detail"** untuk melihat informasi lengkap
4. Status harus: **PENDING** (badge kuning)

**Expected:**
- ✅ Pengajuan terlihat di list
- ✅ Status: Pending
- ✅ Golongan: I-C → I-D

---

#### **3.2 Login sebagai Atasan Langsung (Maya Sari)**

**Logout** dari akun Sari Dewi, lalu login:

```
Username: 20140008
Password: password123
Role: atasan
```

**Action:**
1. Klik menu: **"Pending Approval"**
2. **HARUS MUNCUL:** Pengajuan dari:
   - Sari Dewi (PG-TEST-001)
   - Fitri Ramadhani (PG-TEST-003)
3. Klik **"Review"** pada pengajuan Sari Dewi
4. Lihat detail lengkap (dokumen, masa kerja, nilai kinerja, dll)
5. Klik tombol: **"Setujui Pengajuan"**
6. Isi catatan (optional): "Kinerja konsisten dan memenuhi syarat"
7. Submit

**Expected:**
- ✅ Notifikasi sukses: "Pengajuan berhasil disetujui"
- ✅ Redirect ke halaman Pending Approval
- ✅ Pengajuan Sari Dewi hilang dari list (karena sudah disetujui)

**Validasi Database:**
```sql
-- Status pengajuan berubah
SELECT nomor_pengajuan, status FROM pengajuan WHERE nomor_pengajuan = 'PG-TEST-001';
-- Expected: status = 'disetujui_atasan'

-- Approval history bertambah
SELECT * FROM approval_history WHERE id_pengajuan = (
    SELECT id_pengajuan FROM pengajuan WHERE nomor_pengajuan = 'PG-TEST-001'
);
-- Expected: 1 record dengan level_approval = 'atasan', keputusan = 'approved'
```

---

#### **3.3 Login sebagai Manager**

**Logout** dari Maya Sari, lalu login sebagai **SALAH SATU** manager:

**Option A - Siti Rahmawati:**
```
Username: 20050002
Password: password123
Role: manager
```

**Option B - Ahmad Yani:**
```
Username: 20060003
Password: password123
Role: manager
```

**Option C - Dewi Sartika:**
```
Username: 20070004
Password: password123
Role: manager
```

**Action:**
1. Klik menu: **"Pending Approval"**
2. **HARUS MUNCUL:** Pengajuan dari Sari Dewi (PG-TEST-001)
   - Status sekarang: **disetujui_atasan**
3. Klik **"Review"**
4. Lihat riwayat approval (sudah ada approval dari Maya Sari)
5. Klik tombol: **"Setujui Pengajuan"**
6. Isi catatan: "Direkomendasikan untuk kenaikan golongan"
7. Submit

**Expected:**
- ✅ Notifikasi sukses: "Pengajuan berhasil disetujui"
- ✅ Pengajuan hilang dari list manager
- ✅ Status berubah: `disetujui_atasan` → `disetujui_manager`

**Validasi Database:**
```sql
SELECT nomor_pengajuan, status FROM pengajuan WHERE nomor_pengajuan = 'PG-TEST-001';
-- Expected: status = 'disetujui_manager'

SELECT COUNT(*) as jumlah_approval FROM approval_history WHERE id_pengajuan = (
    SELECT id_pengajuan FROM pengajuan WHERE nomor_pengajuan = 'PG-TEST-001'
);
-- Expected: jumlah_approval = 2 (atasan + manager)
```

---

#### **3.4 Login sebagai Kepala Wilayah (Final Approval)**

**Logout** dari Manager, lalu login:

```
Username: 19900001
Password: password123
Role: kepala_wilayah
```

**Action:**
1. Klik menu: **"Pending Approval"**
2. **HARUS MUNCUL:** Pengajuan dari Sari Dewi (PG-TEST-001)
   - Status sekarang: **disetujui_manager**
3. Klik **"Review"**
4. Lihat riwayat approval lengkap:
   - Level 1: Maya Sari (Atasan) - Approved
   - Level 2: [Nama Manager] - Approved
5. Klik tombol: **"Setujui Pengajuan"** (Final Approval)
6. Isi catatan: "Disetujui untuk kenaikan golongan menjadi I-D"
7. Submit

**Expected:**
- ✅ Notifikasi sukses: "Pengajuan berhasil disetujui"
- ✅ Status berubah: `disetujui_manager` → `disetujui`
- ✅ **PENTING:** Golongan Sari Dewi otomatis update dari I-C → I-D

**Validasi Database:**
```sql
-- 1. Status pengajuan FINAL
SELECT nomor_pengajuan, status FROM pengajuan WHERE nomor_pengajuan = 'PG-TEST-001';
-- Expected: status = 'disetujui'

-- 2. Approval history LENGKAP (3 level)
SELECT 
    level_approval, 
    keputusan, 
    tanggal_approval 
FROM approval_history 
WHERE id_pengajuan = (SELECT id_pengajuan FROM pengajuan WHERE nomor_pengajuan = 'PG-TEST-001')
ORDER BY tanggal_approval;
-- Expected: 3 records (atasan, manager, kepala_wilayah) semua 'approved'

-- 3. Golongan Sari Dewi TERUPDATE
SELECT 
    p.nip,
    p.nama_lengkap,
    gol.kode_golongan as golongan_saat_ini
FROM pekerja p
JOIN golongan_jabatan gol ON p.id_golongan_saat_ini = gol.id_golongan
WHERE p.nip = '20210014';
-- Expected: golongan_saat_ini = 'I-D' (sebelumnya I-C)
```

---

#### **3.5 Validasi Akhir - Login kembali sebagai Sari Dewi**

```
Username: 20210014
Password: password123
```

**Action:**
1. Klik menu: **"Pengajuan Saya"**
2. Lihat pengajuan PG-TEST-001
3. Status harus: **DISETUJUI** (badge hijau)
4. Lihat riwayat approval lengkap (3 level)
5. Klik menu: **"Riwayat Pengajuan"**
6. Pengajuan PG-TEST-001 harus muncul di sini

**Expected:**
- ✅ Status: DISETUJUI
- ✅ Approval history lengkap terlihat
- ✅ Golongan di profil sudah berubah ke I-D

---

## ✅ CHECKLIST VALIDASI 1 CYCLE

- [ ] Pengajuan dibuat oleh Sari Dewi (status: pending)
- [ ] Maya Sari (atasan) bisa melihat pengajuan di "Pending Approval"
- [ ] Maya Sari approve → Status: disetujui_atasan
- [ ] Manager bisa melihat pengajuan di "Pending Approval"
- [ ] Manager approve → Status: disetujui_manager
- [ ] Kepala Wilayah bisa melihat pengajuan di "Pending Approval"
- [ ] Kepala Wilayah approve → Status: disetujui
- [ ] Golongan Sari Dewi otomatis update dari I-C ke I-D
- [ ] Approval history tercatat lengkap (3 level)
- [ ] Sari Dewi bisa melihat status akhir "DISETUJUI"

---

## 🐛 TROUBLESHOOTING

### Problem 1: Atasan tidak melihat pengajuan pending

**Solusi:**
1. Cek relasi atasan-bawahan:
```sql
SELECT 
    p.nip, 
    p.nama_lengkap,
    p.id_atasan,
    atasan.nama_lengkap as nama_atasan
FROM pekerja p
LEFT JOIN pekerja atasan ON p.id_atasan = atasan.id_pekerja
WHERE p.nip = '20210014';
```

2. Pastikan `id_atasan` Sari Dewi = 8 (Maya Sari)
3. Pastikan pengajuan berstatus `pending`

---

### Problem 2: Manager tidak melihat pengajuan

**Solusi:**
1. Pastikan ada pengajuan dengan status `disetujui_atasan`
```sql
SELECT * FROM pengajuan WHERE status = 'disetujui_atasan';
```

2. Semua manager bisa melihat pengajuan dengan status ini
3. Jika kosong, berarti belum ada atasan yang approve

---

### Problem 3: Golongan tidak terupdate setelah final approval

**Cek code di ApprovalController.php:**
```php
// If final approval, update pekerja golongan
if ($newStatus === 'disetujui') {
    $this->pekerjaModel->update($pengajuan->id_pekerja, [
        'id_golongan_saat_ini' => $pengajuan->id_golongan_diajukan
    ]);
}
```

**Validasi:**
```sql
SELECT 
    peng.nomor_pengajuan,
    peng.status,
    p.nama_lengkap,
    gol.kode_golongan as golongan_saat_ini
FROM pengajuan peng
JOIN pekerja p ON peng.id_pekerja = p.id_pekerja
JOIN golongan_jabatan gol ON p.id_golongan_saat_ini = gol.id_golongan
WHERE peng.nomor_pengajuan = 'PG-TEST-001';
```

---

## 📞 BANTUAN

Jika masih ada masalah:

1. **Buka debug helper:** `http://localhost/kenaikanjabatan/public/debug_approval.php`
2. **Cek semua section** dan pastikan data sesuai
3. **Jalankan query validasi** di phpMyAdmin
4. **Cek error log** di browser console dan PHP error log

---

## 📚 FILE PENDUKUNG

1. **05_Testing_Data_Approval.sql** - Data pengajuan testing
2. **debug_approval.php** - Tool debugging & validasi
3. **PANDUAN_APPROVAL_TESTING.md** - Struktur organisasi lengkap
4. **99_Helper_Queries_Testing.sql** - Query helper untuk debugging

---

**✅ Selamat Testing!** Pastikan semua checklist terpenuhi untuk 1 cycle lengkap.
