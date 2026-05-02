# SYSTEM FLOW & USE CASE DIAGRAM
## SISTEM INFORMASI PENGAJUAN KENAIKAN GOLONGAN JABATAN PEKERJA
### BRI WILAYAH PADANG

---

## 1. USE CASE DIAGRAM

### 1.1 Actors
1. **Pekerja** - Karyawan yang mengajukan kenaikan golongan
2. **Atasan Langsung** - Supervisor yang mereview pengajuan tahap 1
3. **Manager Wilayah** - Manager yang mereview pengajuan tahap 2
4. **Kepala Wilayah** - Pejabat yang memberikan approval final
5. **Admin/HC** - Pengelola sistem dan data master

### 1.2 Use Cases

```
┌─────────────────────────────────────────────────────────────────┐
│                  SISTEM KENAIKAN GOLONGAN BRI                   │
└─────────────────────────────────────────────────────────────────┘

PEKERJA:
├── Login ke Sistem
├── Lihat Dashboard
├── Lihat Profil
├── Update Profil
├── Buat Pengajuan Kenaikan Golongan
│   ├── Upload Dokumen Pendukung
│   └── Submit Pengajuan
├── Lihat Status Pengajuan
├── Lihat Riwayat Pengajuan
├── Edit Pengajuan (jika status pending)
├── Batalkan Pengajuan (jika status pending)
├── Download SK (jika sudah approved)
└── Lihat Notifikasi

ATASAN LANGSUNG:
├── Login ke Sistem
├── Lihat Dashboard Approval
├── Lihat Daftar Pengajuan Pending
├── Review Detail Pengajuan
├── Lihat Dokumen Pendukung
├── Approve/Reject Pengajuan
│   └── Berikan Catatan
├── Lihat Riwayat Approval
└── Lihat Notifikasi

MANAGER WILAYAH:
├── Login ke Sistem
├── Lihat Dashboard Approval
├── Lihat Daftar Pengajuan Pending (level 2)
├── Review Detail Pengajuan
├── Lihat Dokumen Pendukung
├── Lihat Catatan Atasan
├── Approve/Reject Pengajuan
│   └── Berikan Catatan
├── Lihat Riwayat Approval
└── Lihat Notifikasi

KEPALA WILAYAH:
├── Login ke Sistem
├── Lihat Dashboard Approval
├── Lihat Daftar Pengajuan Pending (level 3)
├── Review Detail Pengajuan
├── Lihat Dokumen Pendukung
├── Lihat Timeline Approval
├── Final Approve/Reject Pengajuan
│   ├── Berikan Catatan
│   ├── Set Tanggal Efektif (jika approve)
│   └── Generate SK (jika approve)
├── Lihat Riwayat Approval
├── Lihat Laporan
└── Lihat Notifikasi

ADMIN/HC:
├── Login ke Sistem
├── Kelola Master Golongan Jabatan
│   ├── Tambah Golongan
│   ├── Edit Golongan
│   ├── Hapus Golongan
│   └── Lihat Daftar Golongan
├── Kelola Master Divisi
│   ├── Tambah Divisi
│   ├── Edit Divisi
│   ├── Hapus Divisi
│   └── Lihat Daftar Divisi
├── Kelola Master Jabatan
│   ├── Tambah Jabatan
│   ├── Edit Jabatan
│   ├── Hapus Jabatan
│   └── Lihat Daftar Jabatan
├── Kelola Data Pekerja
│   ├── Tambah Pekerja
│   ├── Edit Pekerja
│   ├── Hapus Pekerja
│   ├── Import Data Pekerja
│   └── Lihat Daftar Pekerja
├── Kelola User Account
│   ├── Tambah User
│   ├── Edit User
│   ├── Reset Password
│   └── Lihat Daftar User
├── Monitor Semua Pengajuan
├── Generate Laporan
│   ├── Laporan Pengajuan
│   ├── Laporan Pekerja per Golongan
│   ├── Laporan Approval Rate
│   └── Laporan Riwayat Kenaikan
├── Lihat Log Aktivitas
└── Backup Database

SISTEM (Auto):
├── Generate Nomor Pengajuan
├── Send Email Notification
├── Send In-App Notification
├── Update Status Pengajuan
├── Update Golongan Pekerja (after final approval)
└── Generate Surat Keputusan (after final approval)
```

---

## 2. BUSINESS PROCESS FLOW

### 2.1 Main Process: Pengajuan Kenaikan Golongan

```
START
  │
  ├─→ [PEKERJA] Login ke Sistem
  │
  ├─→ [PEKERJA] Klik "Buat Pengajuan Baru"
  │
  ├─→ [SISTEM] Cek Syarat Pengajuan
  │    ├─ Masa kerja >= 2 tahun? ──NO──→ [SISTEM] Tampilkan Error "Masa kerja belum mencukupi" → END
  │    ├─ Nilai kinerja >= 80? ──NO──→ [SISTEM] Tampilkan Error "Nilai kinerja belum mencukupi" → END
  │    ├─ Ada pengajuan aktif? ──YES──→ [SISTEM] Tampilkan Error "Masih ada pengajuan aktif" → END
  │    └─ Semua syarat terpenuhi? ──YES──→ Lanjut
  │
  ├─→ [PEKERJA] Isi Form Pengajuan
  │    ├─ Pilih Golongan yang Diajukan
  │    ├─ Isi Alasan Pengajuan
  │    ├─ Upload Dokumen:
  │    │   ├─ Surat Permohonan (PDF)
  │    │   ├─ Hasil Penilaian Kinerja (PDF)
  │    │   ├─ Sertifikat (PDF) - Optional
  │    │   └─ Dokumen Lainnya - Optional
  │    └─ Isi Catatan Tambahan (Optional)
  │
  ├─→ [PEKERJA] Submit Pengajuan
  │
  ├─→ [SISTEM] Validasi Data
  │    ├─ Data lengkap? ──NO──→ [SISTEM] Tampilkan Error → Kembali ke Form
  │    └─ Data valid? ──YES──→ Lanjut
  │
  ├─→ [SISTEM] Simpan Pengajuan
  │    ├─ Generate Nomor Pengajuan (PG/2024/03/0001)
  │    ├─ Set Status = "Pending"
  │    ├─ Simpan ke Database
  │    └─ Upload & Simpan Dokumen
  │
  ├─→ [SISTEM] Kirim Notifikasi
  │    ├─ Email ke Atasan Langsung
  │    ├─ In-App Notification ke Atasan
  │    └─ In-App Notification ke Pekerja (konfirmasi)
  │
  ├─→ [SISTEM] Tampilkan Pesan Sukses
  │    └─ "Pengajuan berhasil dibuat dengan nomor: PG/2024/03/0001"
  │
  └─→ END (Menunggu Review Atasan)
```

---

### 2.2 Approval Process Flow - Level 1 (Atasan Langsung)

```
START (Status Pengajuan: Pending)
  │
  ├─→ [ATASAN] Login ke Sistem
  │
  ├─→ [ATASAN] Lihat Dashboard
  │    └─ Muncul badge "3 Pengajuan Perlu Review"
  │
  ├─→ [ATASAN] Klik "Daftar Pengajuan Pending"
  │
  ├─→ [SISTEM] Tampilkan List Pengajuan
  │    └─ Filter: Status = "Pending" AND Atasan = User Login
  │
  ├─→ [ATASAN] Klik "Review" pada Pengajuan
  │
  ├─→ [SISTEM] Tampilkan Detail Pengajuan
  │    ├─ Info Pekerja (NIP, Nama, Golongan Saat Ini)
  │    ├─ Golongan yang Diajukan
  │    ├─ Alasan Pengajuan
  │    ├─ Masa Kerja & Nilai Kinerja
  │    ├─ Dokumen Pendukung (dapat di-download)
  │    └─ Form Approval
  │
  ├─→ [ATASAN] Review Pengajuan
  │    └─ Download & Baca Dokumen Pendukung
  │
  ├─→ [ATASAN] Buat Keputusan
  │    ├─ Pilih: [Approve] atau [Reject]
  │    └─ Isi Catatan (wajib jika Reject)
  │
  ├─→ [ATASAN] Klik "Submit Approval"
  │
  ├─→ [SISTEM] Validasi
  │    ├─ Keputusan sudah dipilih? ──NO──→ Error → Kembali
  │    ├─ Catatan diisi (jika Reject)? ──NO──→ Error → Kembali
  │    └─ Valid? ──YES──→ Lanjut
  │
  ├─→ [SISTEM] Proses Keputusan
  │    │
  │    ├─ Jika APPROVE:
  │    │   ├─ Update Status Pengajuan = "Disetujui Atasan"
  │    │   ├─ Simpan ke approval_history
  │    │   ├─ Kirim Notifikasi:
  │    │   │   ├─ Email ke Pekerja (info approved level 1)
  │    │   │   ├─ Email ke Manager Wilayah (request review)
  │    │   │   └─ In-App Notification
  │    │   └─ Log Aktivitas
  │    │
  │    └─ Jika REJECT:
  │        ├─ Update Status Pengajuan = "Ditolak Atasan"
  │        ├─ Simpan ke approval_history
  │        ├─ Kirim Notifikasi:
  │        │   ├─ Email ke Pekerja (info rejected + catatan)
  │        │   └─ In-App Notification
  │        └─ Log Aktivitas
  │
  ├─→ [SISTEM] Tampilkan Pesan Sukses
  │
  └─→ END
       │
       ├─ Jika APPROVE → Lanjut ke Level 2 (Manager Wilayah)
       └─ Jika REJECT → Pengajuan Selesai (Ditolak)
```

---

### 2.3 Approval Process Flow - Level 2 (Manager Wilayah)

```
START (Status Pengajuan: Disetujui Atasan)
  │
  ├─→ [MANAGER] Login ke Sistem
  │
  ├─→ [MANAGER] Lihat Dashboard
  │    └─ Muncul badge "2 Pengajuan Perlu Review"
  │
  ├─→ [MANAGER] Klik "Daftar Pengajuan Pending Level 2"
  │
  ├─→ [SISTEM] Tampilkan List Pengajuan
  │    └─ Filter: Status = "Disetujui Atasan"
  │
  ├─→ [MANAGER] Klik "Review" pada Pengajuan
  │
  ├─→ [SISTEM] Tampilkan Detail Pengajuan
  │    ├─ Info Lengkap Pekerja
  │    ├─ Golongan Saat Ini → Golongan Diajukan
  │    ├─ Alasan Pengajuan
  │    ├─ Dokumen Pendukung
  │    ├─ Timeline Approval:
  │    │   └─ ✓ Disetujui Atasan (+ Catatan)
  │    └─ Form Approval
  │
  ├─→ [MANAGER] Review Pengajuan
  │    ├─ Baca Catatan dari Atasan
  │    └─ Review Dokumen
  │
  ├─→ [MANAGER] Buat Keputusan
  │    ├─ Pilih: [Approve] atau [Reject]
  │    └─ Isi Catatan
  │
  ├─→ [MANAGER] Klik "Submit Approval"
  │
  ├─→ [SISTEM] Proses Keputusan
  │    │
  │    ├─ Jika APPROVE:
  │    │   ├─ Update Status = "Disetujui Manager"
  │    │   ├─ Simpan ke approval_history
  │    │   ├─ Kirim Notifikasi:
  │    │   │   ├─ Email ke Pekerja (info approved level 2)
  │    │   │   ├─ Email ke Kepala Wilayah (request final approval)
  │    │   │   └─ In-App Notification
  │    │   └─ Log Aktivitas
  │    │
  │    └─ Jika REJECT:
  │        ├─ Update Status = "Ditolak Manager"
  │        ├─ Simpan ke approval_history
  │        ├─ Kirim Notifikasi ke Pekerja
  │        └─ Log Aktivitas
  │
  └─→ END
       │
       ├─ Jika APPROVE → Lanjut ke Level 3 (Kepala Wilayah)
       └─ Jika REJECT → Pengajuan Selesai (Ditolak)
```

---

### 2.4 Approval Process Flow - Level 3 (Kepala Wilayah - Final)

```
START (Status Pengajuan: Disetujui Manager)
  │
  ├─→ [KEPALA WILAYAH] Login ke Sistem
  │
  ├─→ [KEPALA WILAYAH] Lihat Dashboard
  │    └─ Muncul badge "1 Pengajuan Perlu Final Approval"
  │
  ├─→ [KEPALA WILAYAH] Klik "Daftar Pengajuan Final Approval"
  │
  ├─→ [SISTEM] Tampilkan List Pengajuan
  │    └─ Filter: Status = "Disetujui Manager"
  │
  ├─→ [KEPALA WILAYAH] Klik "Review" pada Pengajuan
  │
  ├─→ [SISTEM] Tampilkan Detail Pengajuan
  │    ├─ Info Lengkap Pekerja
  │    ├─ Golongan Saat Ini → Golongan Diajukan
  │    ├─ Alasan Pengajuan
  │    ├─ Dokumen Pendukung
  │    ├─ Complete Timeline Approval:
  │    │   ├─ ✓ Disetujui Atasan (+ Catatan + Tanggal)
  │    │   └─ ✓ Disetujui Manager (+ Catatan + Tanggal)
  │    └─ Form Final Approval
  │
  ├─→ [KEPALA WILAYAH] Review Pengajuan
  │    ├─ Review Timeline Approval
  │    ├─ Review Dokumen
  │    └─ Baca Semua Catatan
  │
  ├─→ [KEPALA WILAYAH] Buat Keputusan
  │    ├─ Pilih: [Approve] atau [Reject]
  │    ├─ Isi Catatan
  │    └─ Jika Approve: Set Tanggal Efektif
  │
  ├─→ [KEPALA WILAYAH] Klik "Submit Final Approval"
  │
  ├─→ [SISTEM] Proses Keputusan
  │    │
  │    ├─ Jika APPROVE:
  │    │   ├─ Update Status = "Disetujui"
  │    │   ├─ Simpan ke approval_history
  │    │   ├─ Update Golongan Pekerja:
  │    │   │   ├─ pekerja.id_golongan_saat_ini = golongan_diajukan
  │    │   │   └─ pekerja.tanggal_golongan_terakhir = tanggal_efektif
  │    │   ├─ Generate Nomor SK (SK/2024/001)
  │    │   ├─ Generate PDF Surat Keputusan
  │    │   ├─ Simpan File SK
  │    │   ├─ Kirim Notifikasi:
  │    │   │   ├─ Email ke Pekerja (congratulation + link download SK)
  │    │   │   ├─ Email ke Atasan
  │    │   │   ├─ Email ke Manager
  │    │   │   ├─ Email ke HC/Admin
  │    │   │   └─ In-App Notification
  │    │   └─ Log Aktivitas
  │    │
  │    └─ Jika REJECT:
  │        ├─ Update Status = "Ditolak Kepala Wilayah"
  │        ├─ Simpan ke approval_history
  │        ├─ Kirim Notifikasi ke Pekerja
  │        └─ Log Aktivitas
  │
  ├─→ [SISTEM] Tampilkan Pesan Sukses
  │
  └─→ END (Pengajuan Selesai - Approved/Rejected)
```

---

## 3. DETAILED ACTIVITY DIAGRAMS

### 3.1 Login Process

```
START
  │
  ├─→ User mengakses halaman login
  │
  ├─→ User input username & password
  │
  ├─→ User klik "Login"
  │
  ├─→ [SISTEM] Validasi Input
  │    ├─ Username kosong? ──YES──→ Error "Username wajib diisi" → Kembali
  │    ├─ Password kosong? ──YES──→ Error "Password wajib diisi" → Kembali
  │    └─ Input valid? ──YES──→ Lanjut
  │
  ├─→ [SISTEM] Cek Kredensial di Database
  │    ├─ Username tidak ditemukan? ──YES──→ Error "Username tidak ditemukan" → Kembali
  │    ├─ Password salah? ──YES──→ Error "Password salah" → Increment failed attempt → Kembali
  │    ├─ Failed attempt >= 3? ──YES──→ Lock account 15 menit → Error → END
  │    ├─ User tidak aktif? ──YES──→ Error "Akun tidak aktif" → END
  │    └─ Kredensial benar? ──YES──→ Lanjut
  │
  ├─→ [SISTEM] Create Session
  │    ├─ Generate session token
  │    ├─ Set session timeout (30 menit)
  │    ├─ Update last_login
  │    └─ Log aktivitas login
  │
  ├─→ [SISTEM] Redirect ke Dashboard berdasarkan Role
  │    ├─ Admin → Dashboard Admin
  │    ├─ Pekerja → Dashboard Pekerja
  │    ├─ Atasan → Dashboard Atasan
  │    ├─ Manager → Dashboard Manager
  │    └─ Kepala Wilayah → Dashboard Kepala Wilayah
  │
  └─→ END (User berhasil login)
```

---

### 3.2 Upload Dokumen Process

```
START (User di form pengajuan)
  │
  ├─→ User klik "Browse File" / "Upload Dokumen"
  │
  ├─→ User pilih file dari komputer
  │
  ├─→ [SISTEM] Validasi File (Client-Side)
  │    ├─ File dipilih? ──NO──→ Kembali
  │    ├─ Tipe file = PDF/Image? ──NO──→ Error "Format file tidak didukung" → Kembali
  │    ├─ Ukuran file <= 2MB? ──NO──→ Error "Ukuran file maks 2MB" → Kembali
  │    └─ Valid? ──YES──→ Lanjut
  │
  ├─→ [SISTEM] Preview File (Optional)
  │    └─ Tampilkan nama file & ukuran
  │
  ├─→ User klik "Submit" pengajuan
  │
  ├─→ [SISTEM] Upload File ke Server
  │    ├─ Validasi ulang (Server-Side)
  │    ├─ Scan malware (Optional)
  │    ├─ Generate unique filename
  │    │   Format: {jenis}_{nip}_{timestamp}.{ext}
  │    │   Contoh: surat_permohonan_20210014_20240320103045.pdf
  │    ├─ Create directory jika belum ada
  │    │   Path: /uploads/documents/{tahun}/{bulan}/{nip}/
  │    └─ Simpan file ke directory
  │
  ├─→ [SISTEM] Simpan Info File ke Database
  │    ├─ id_pengajuan
  │    ├─ jenis_dokumen
  │    ├─ nama_dokumen (nama asli)
  │    ├─ file_path (path di server)
  │    ├─ file_size
  │    ├─ mime_type
  │    └─ uploaded_at
  │
  ├─→ [SISTEM] Return Success
  │
  └─→ END (File berhasil diupload)
```

---

### 3.3 Generate Surat Keputusan (SK) Process

```
START (Setelah Final Approval)
  │
  ├─→ [SISTEM] Ambil Data Pengajuan
  │    ├─ Data pekerja (NIP, Nama, dll)
  │    ├─ Golongan lama → Golongan baru
  │    ├─ Tanggal efektif
  │    └─ Nomor pengajuan
  │
  ├─→ [SISTEM] Generate Nomor SK
  │    ├─ Format: SK/{TAHUN}/{COUNTER}
  │    ├─ Contoh: SK/2024/001
  │    └─ Auto-increment counter
  │
  ├─→ [SISTEM] Load Template SK (PDF/HTML)
  │    └─ Template berisi placeholder:
  │        ├─ {nomor_sk}
  │        ├─ {tanggal_sk}
  │        ├─ {nip}
  │        ├─ {nama_lengkap}
  │        ├─ {jabatan}
  │        ├─ {golongan_lama}
  │        ├─ {golongan_baru}
  │        ├─ {tanggal_efektif}
  │        └─ {ttd_kepala_wilayah}
  │
  ├─→ [SISTEM] Replace Placeholder dengan Data Real
  │
  ├─→ [SISTEM] Generate PDF
  │    ├─ Library: TCPDF / FPDF / mPDF
  │    └─ Tambah Header, Footer, Logo BRI
  │
  ├─→ [SISTEM] Simpan PDF File
  │    ├─ Filename: SK_{nomor_sk}_{nip}.pdf
  │    ├─ Path: /uploads/sk/{tahun}/{nomor_sk}.pdf
  │    └─ Update pengajuan.file_sk dengan path
  │
  ├─→ [SISTEM] Update pengajuan.nomor_sk
  │
  ├─→ [SISTEM] Log Aktivitas
  │
  └─→ END (SK berhasil di-generate)
       └─ File tersedia untuk download
```

---

### 3.4 Notification Process

```
START (Trigger event terjadi)
  │
  ├─→ [SISTEM] Identifikasi Trigger Event
  │    ├─ Pengajuan baru dibuat
  │    ├─ Pengajuan di-approve (level 1, 2, 3)
  │    ├─ Pengajuan di-reject
  │    └─ Pengajuan final approved
  │
  ├─→ [SISTEM] Tentukan Penerima Notifikasi
  │    │
  │    ├─ Event: Pengajuan Baru
  │    │   └─ Penerima: Atasan Langsung
  │    │
  │    ├─ Event: Approved Level 1
  │    │   ├─ Penerima: Pekerja (info)
  │    │   └─ Penerima: Manager Wilayah (action required)
  │    │
  │    ├─ Event: Approved Level 2
  │    │   ├─ Penerima: Pekerja (info)
  │    │   └─ Penerima: Kepala Wilayah (action required)
  │    │
  │    ├─ Event: Final Approved
  │    │   ├─ Penerima: Pekerja (congratulation)
  │    │   ├─ Penerima: Atasan (info)
  │    │   ├─ Penerima: Manager (info)
  │    │   └─ Penerima: Admin/HC (info)
  │    │
  │    └─ Event: Rejected
  │        └─ Penerima: Pekerja (info + reason)
  │
  ├─→ [SISTEM] Buat Konten Notifikasi
  │    ├─ Subject/Judul
  │    ├─ Pesan/Body
  │    └─ Link terkait
  │
  ├─→ [PARALLEL PROCESS]
  │    │
  │    ├─→ [A] Send Email Notification
  │    │    ├─ Load email template
  │    │    ├─ Replace placeholder
  │    │    ├─ Send via SMTP (PHPMailer)
  │    │    └─ Log email sent
  │    │
  │    └─→ [B] Send In-App Notification
  │         ├─ INSERT INTO notifikasi
  │         │   ├─ id_user
  │         │   ├─ judul
  │         │   ├─ pesan
  │         │   ├─ link
  │         │   └─ is_read = 0
  │         ├─ Update badge counter
  │         └─ Trigger real-time update (jika ada websocket)
  │
  ├─→ [SISTEM] Log Aktivitas Notifikasi
  │
  └─→ END
```

---

## 4. SEQUENCE DIAGRAMS

### 4.1 Sequence: Pengajuan Kenaikan Golongan

```
Pekerja          Frontend          Backend         Database         Email Service
   │                │                  │                │                  │
   │─ Login ───────>│                  │                │                  │
   │                │─ POST /login ───>│                │                  │
   │                │                  │─ Query user ──>│                  │
   │                │                  │<─ User data ───│                  │
   │<─ Dashboard ───│<─ Response ──────│                │                  │
   │                │                  │                │                  │
   │─ Buat Pengajuan│                  │                │                  │
   │                │─ GET /pengajuan/create           │                  │
   │                │                  │─ Get master ─>│                  │
   │                │<─ Form ──────────│<─ Data ────────│                  │
   │                │                  │                │                  │
   │─ Isi Form ────>│                  │                │                  │
   │─ Upload Dok ──>│                  │                │                  │
   │─ Submit ──────>│                  │                │                  │
   │                │─ POST /pengajuan/store          │                  │
   │                │                  │─ Validate ────>│                  │
   │                │                  │─ BEGIN TRANS ─>│                  │
   │                │                  │─ INSERT pengajuan                │
   │                │                  │─ INSERT dokumen                  │
   │                │                  │─ COMMIT ──────>│                  │
   │                │                  │─ Send email ───────────────────>│
   │                │                  │─ Insert notif >│                  │
   │                │<─ Success ───────│                │<─ Email sent ───│
   │<─ Redirect ────│                  │                │                  │
```

---

### 4.2 Sequence: Approval Process (Atasan)

```
Atasan           Frontend          Backend         Database      Notification
   │                │                  │                │               │
   │─ Login ───────>│                  │                │               │
   │<─ Dashboard ───│                  │                │               │
   │  (badge: 3)    │                  │                │               │
   │                │                  │                │               │
   │─ Klik Review ─>│                  │                │               │
   │                │─ GET /approval/detail/{id}       │               │
   │                │                  │─ Query data ─>│               │
   │                │<─ Detail ─────────│<─ Data ───────│               │
   │<─ Tampil Detail│                  │                │               │
   │                │                  │                │               │
   │─ Approve ─────>│                  │                │               │
   │  + Catatan     │                  │                │               │
   │                │─ POST /approval/submit           │               │
   │                │                  │─ Validate ────>│               │
   │                │                  │─ BEGIN TRANS ─>│               │
   │                │                  │─ UPDATE status │               │
   │                │                  │─ INSERT approval_history       │
   │                │                  │─ COMMIT ──────>│               │
   │                │                  │─ Trigger notif────────────────>│
   │                │                  │  (Pekerja + Manager)           │
   │                │<─ Success ───────│                │               │
   │<─ Redirect ────│                  │                │               │
```

---

## 5. STATE DIAGRAM - Status Pengajuan

```
┌─────────────┐
│   PENDING   │  ← Initial State (setelah submit)
└──────┬──────┘
       │
       ├──[Atasan Approve]──→ ┌────────────────────┐
       │                      │ DISETUJUI_ATASAN   │
       │                      └─────────┬──────────┘
       │                                │
       │                                ├──[Manager Approve]──→ ┌────────────────────┐
       │                                │                       │ DISETUJUI_MANAGER  │
       │                                │                       └─────────┬──────────┘
       │                                │                                 │
       │                                │                                 ├──[Kepala Approve]──→ ┌────────────┐
       │                                │                                 │                      │ DISETUJUI  │ ← Final Success
       │                                │                                 │                      └────────────┘
       │                                │                                 │
       │                                │                                 └──[Kepala Reject]──→ ┌─────────────────────────┐
       │                                │                                                       │ DITOLAK_KEPALA_WILAYAH │ ← Final
       │                                │                                                       └─────────────────────────┘
       │                                │
       │                                └──[Manager Reject]──→ ┌──────────────────┐
       │                                                       │ DITOLAK_MANAGER  │ ← Final
       │                                                       └──────────────────┘
       │
       ├──[Atasan Reject]──→ ┌──────────────────┐
       │                     │ DITOLAK_ATASAN   │ ← Final
       │                     └──────────────────┘
       │
       └──[Pekerja Cancel]──→ ┌──────────────┐
                              │ DIBATALKAN   │ ← Final
                              └──────────────┘

STATUS LEGEND:
┌─────────────────────────────────────────────────────────────┐
│ PENDING                → Menunggu review atasan             │
│ DISETUJUI_ATASAN       → Menunggu review manager            │
│ DISETUJUI_MANAGER      → Menunggu final approval            │
│ DISETUJUI              → Final approved, golongan updated   │
│ DITOLAK_ATASAN         → Rejected by atasan (final)         │
│ DITOLAK_MANAGER        → Rejected by manager (final)        │
│ DITOLAK_KEPALA_WILAYAH → Rejected by kepala wilayah (final) │
│ DIBATALKAN             → Cancelled by pekerja (final)       │
└─────────────────────────────────────────────────────────────┘
```

---

## 6. DATA FLOW DIAGRAM (DFD)

### 6.1 Context Diagram (Level 0)

```
┌──────────────┐
│   PEKERJA    │
└──────┬───────┘
       │
       ├─ Data Pengajuan
       ├─ Dokumen Pendukung
       │
       ↓
┌─────────────────────────────────────────┐
│                                         │
│   SISTEM INFORMASI KENAIKAN GOLONGAN   │     ←─ Data Master (Admin)
│                                         │     ←─ Approval (Atasan/Manager/Kepala)
│                                         │
└───────────────┬─────────────────────────┘
                │
                ├─→ Status Pengajuan (Pekerja)
                ├─→ Notifikasi (All Users)
                ├─→ Laporan (Admin/Kepala)
                └─→ Surat Keputusan (Pekerja)
```

### 6.2 DFD Level 1

```
[PEKERJA] ─ Data Pengajuan ──→ [1.0 Kelola Pengajuan] ──→ (D1: pengajuan)
                                        │
                                        ├──→ (D2: dokumen_pengajuan)
                                        │
                                        └─ Notif Pengajuan Baru ──→ [ATASAN]

[ATASAN] ── Keputusan Approval ──→ [2.0 Proses Approval] ←── (D1: pengajuan)
                                           │
                                           ├──→ (D3: approval_history)
                                           │
                                           └─ Notif ──→ [PEKERJA] / [MANAGER]

[MANAGER] ─ Keputusan Approval ──→ [2.0 Proses Approval]
                                           │
                                           └─ Notif ──→ [KEPALA WILAYAH]

[KEPALA] ── Final Approval ──→ [3.0 Final Approval & Generate SK]
                                        │
                                        ├─ Update ──→ (D4: pekerja)
                                        ├─ Generate ──→ (D5: file_sk)
                                        └─ Notif ──→ [PEKERJA] / [ADMIN]

[ADMIN] ── Data Master ──→ [4.0 Kelola Master Data] ──→ (D6: golongan)
                                                     ──→ (D7: divisi)
                                                     ──→ (D8: jabatan)
                                                     ──→ (D4: pekerja)

[SISTEM] ──→ [5.0 Kelola Notifikasi] ──→ (D9: notifikasi)
                                     ──→ [Email Service]

[ALL USERS] ←── Laporan ──── [6.0 Generate Laporan] ←── (All Data Stores)
```

---

## 7. WIREFRAME / MOCKUP REFERENCE

### 7.1 Halaman Login
```
┌────────────────────────────────────────────┐
│              [Logo BRI]                    │
│                                            │
│   Sistem Kenaikan Golongan Jabatan         │
│   BRI Wilayah Padang                       │
│                                            │
│   ┌──────────────────────────────────┐    │
│   │ Username: [__________________]   │    │
│   └──────────────────────────────────┘    │
│                                            │
│   ┌──────────────────────────────────┐    │
│   │ Password: [__________________]   │    │
│   └──────────────────────────────────┘    │
│                                            │
│   [ ] Remember Me                          │
│                                            │
│        [     LOGIN     ]                   │
│                                            │
│   Lupa Password?                           │
└────────────────────────────────────────────┘
```

### 7.2 Dashboard Pekerja
```
┌────────────────────────────────────────────┐
│ [Logo] Sistem Kenaikan Golongan  [Profile▼]│
├────────────────────────────────────────────┤
│ Dashboard | Pengajuan | Riwayat | Profil   │
├────────────────────────────────────────────┤
│                                            │
│ Selamat Datang, Sari Dewi                 │
│                                            │
│ ┌──────────────┐  ┌──────────────┐        │
│ │ Golongan     │  │ Masa Kerja   │        │
│ │ Saat Ini:    │  │              │        │
│ │   I-C        │  │  2 Tahun     │        │
│ └──────────────┘  └──────────────┘        │
│                                            │
│ ┌────────────────────────────────────┐    │
│ │ Status Pengajuan Aktif             │    │
│ │                                    │    │
│ │ Nomor: PG/2024/03/0003             │    │
│ │ Status: PENDING                    │    │
│ │ Diajukan: 20 Mar 2024              │    │
│ │                                    │    │
│ │ Timeline:                          │    │
│ │ ● Pending - Menunggu Atasan        │    │
│ │ ○ Review Manager                   │    │
│ │ ○ Final Approval                   │    │
│ │                                    │    │
│ │         [Lihat Detail]             │    │
│ └────────────────────────────────────┘    │
│                                            │
│         [+ Buat Pengajuan Baru]            │
│                                            │
│ ┌────────────────────────────────────┐    │
│ │ Riwayat Pengajuan                  │    │
│ │ ─────────────────────────────────  │    │
│ │ 2024 | I-B → I-C | Disetujui      │    │
│ │ 2022 | I-A → I-B | Disetujui      │    │
│ └────────────────────────────────────┘    │
└────────────────────────────────────────────┘
```

### 7.3 Form Pengajuan Baru
```
┌────────────────────────────────────────────┐
│ Buat Pengajuan Kenaikan Golongan           │
├────────────────────────────────────────────┤
│                                            │
│ Informasi Pekerja:                         │
│ NIP          : 20210014                    │
│ Nama         : Sari Dewi                   │
│ Golongan     : I-C                         │
│ Divisi       : Customer Service            │
│ Masa Kerja   : 2 Tahun 3 Bulan             │
│ Nilai Kinerja: 82.00                       │
│                                            │
│ ────────────────────────────────────────   │
│                                            │
│ Golongan yang Diajukan: *                  │
│ [▼ I-D - Golongan I-D (Junior Staff)]      │
│                                            │
│ Alasan Pengajuan: *                        │
│ ┌────────────────────────────────────┐    │
│ │ [Tulis alasan pengajuan di sini... │    │
│ │                                    │    │
│ │ ]                                  │    │
│ └────────────────────────────────────┘    │
│                                            │
│ Upload Dokumen Pendukung: *                │
│                                            │
│ Surat Permohonan (PDF):                    │
│ [ Choose File ] No file chosen             │
│                                            │
│ Penilaian Kinerja (PDF):                   │
│ [ Choose File ] No file chosen             │
│                                            │
│ Sertifikat (Optional):                     │
│ [ Choose File ] No file chosen             │
│                                            │
│ Catatan Tambahan:                          │
│ ┌────────────────────────────────────┐    │
│ │                                    │    │
│ └────────────────────────────────────┘    │
│                                            │
│  [Batal]              [Submit Pengajuan]   │
│                                            │
└────────────────────────────────────────────┘
```

---

**Document Version**: 1.0  
**Last Updated**: 2024  
**Author**: System Analyst
