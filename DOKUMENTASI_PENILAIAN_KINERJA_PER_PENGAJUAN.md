# Dokumentasi Perubahan: Penilaian Kinerja Per Pengajuan

## Ringkasan
Perubahan ini dibuat untuk menghindari penggunaan nilai kinerja lama secara berulang pada pengajuan berikutnya.

Sebelumnya:
- Sistem hanya mengandalkan `pekerja.nilai_kinerja_terakhir`.
- Nilai tersebut dapat terus dipakai untuk pengajuan selanjutnya tanpa penilaian baru.

Sekarang:
- Setiap pengajuan memiliki nilai penilaian sendiri: `pengajuan.nilai_kinerja_pengajuan`.
- Nilai ini diisi saat proses review oleh atasan langsung.
- Saat pengajuan final disetujui, nilai tersebut menjadi `pekerja.nilai_kinerja_terakhir`.

## Perubahan Teknis

### 1. Database
Ditambahkan kolom baru pada tabel `pengajuan`:
- `nilai_kinerja_pengajuan` DECIMAL(5,2) NULL

Implementasi:
- Runtime migration idempotent di `app/config/Database.php`.
- Skema SQL juga diperbarui di `database/new-db.sql`.

### 2. Approval Flow
Perubahan di `app/controllers/ApprovalController.php`:
- Hanya role `atasan`, `manager`, `kepala_wilayah` yang boleh memproses approval.
- Validasi level review (`canReview`) diterapkan juga di endpoint proses (POST), bukan hanya halaman review.
- Saat `atasan` melakukan approve:
  - Nilai penilaian pengajuan wajib diisi.
  - Nilai harus numerik 0-100.
  - Nilai minimal harus >= `MIN_NILAI_KINERJA`.
- Saat `manager`/`kepala_wilayah` approve:
  - Pengajuan harus sudah punya `nilai_kinerja_pengajuan` dari atasan.
  - Nilai harus memenuhi minimal.
- Saat status final `disetujui`:
  - Update golongan pekerja.
  - Update `pekerja.nilai_kinerja_terakhir` dari `pengajuan.nilai_kinerja_pengajuan`.

### 3. UI
Perubahan di:
- `app/views/approval/review.php`
- `app/views/pengajuan/detail.php`

Tambahan:
- Menampilkan nilai master pekerja dan nilai penilaian pengajuan.
- Form review atasan memiliki input nilai penilaian pengajuan.

## Alur Sederhana (Simple Flow)
1. Pekerja membuat pengajuan + upload dokumen.
2. Atasan review pengajuan.
3. Jika atasan approve, atasan wajib isi nilai penilaian kinerja pengajuan.
4. Manager dan kepala wilayah melanjutkan approval sesuai level.
5. Jika final disetujui:
   - Golongan pekerja naik.
   - Nilai kinerja terbaru pekerja diganti dengan nilai penilaian dari pengajuan ini.
6. Pengajuan berikutnya menggunakan nilai terbaru tersebut.

## Dampak Bisnis
- Penilaian lebih adil per siklus pengajuan.
- Mencegah reuse nilai lama tanpa evaluasi baru.
- Jejak audit penilaian lebih jelas di level pengajuan.

## Checklist Verifikasi
- [ ] Atasan tidak bisa approve tanpa isi nilai penilaian.
- [ ] Nilai di luar 0-100 ditolak.
- [ ] Nilai < minimum ditolak.
- [ ] Manager/kepala tidak bisa approve jika nilai pengajuan belum ada.
- [ ] Final approval mengubah golongan dan nilai kinerja terbaru pekerja.
- [ ] Detail pengajuan menampilkan nilai penilaian pengajuan.
