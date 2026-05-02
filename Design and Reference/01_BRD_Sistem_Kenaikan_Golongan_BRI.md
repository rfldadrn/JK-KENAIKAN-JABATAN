# BUSINESS REQUIREMENTS DOCUMENT (BRD)
## SISTEM INFORMASI PENGAJUAN KENAIKAN GOLONGAN JABATAN PEKERJA BERBASIS WEB
### STUDI KASUS: BRI WILAYAH PADANG

---

## 1. EXECUTIVE SUMMARY

### 1.1 Latar Belakang
Bank Rakyat Indonesia (BRI) Wilayah Padang membutuhkan sistem informasi yang terstruktur untuk mengelola proses pengajuan kenaikan golongan jabatan pekerja. Saat ini, proses masih dilakukan secara manual/semi-manual menggunakan dokumen fisik dan spreadsheet, yang menyebabkan:
- Keterlambatan verifikasi dan persetujuan
- Potensi kesalahan input data
- Duplikasi dan kehilangan berkas
- Kurangnya transparansi proses
- Kesulitan pelacakan status pengajuan

### 1.2 Tujuan Sistem
1. Merancang dan membangun sistem informasi pengajuan kenaikan golongan jabatan berbasis web
2. Meningkatkan transparansi, akurasi data, dan mempercepat proses persetujuan
3. Menyediakan sistem terintegrasi dan terpusat untuk pengelolaan data pekerja
4. Memfasilitasi monitoring real-time untuk semua stakeholder

### 1.3 Ruang Lingkup
**In Scope:**
- Pengajuan kenaikan golongan jabatan pekerja
- Proses verifikasi dan approval bertingkat
- Manajemen data pekerja dan golongan jabatan
- Pelacakan status pengajuan
- Laporan dan dashboard
- Notifikasi otomatis

**Out of Scope:**
- Sistem payroll/penggajian
- Sistem absensi
- Sistem penilaian kinerja detail (hanya menggunakan hasil penilaian)
- Integrasi dengan sistem BRI pusat

---

## 2. STAKEHOLDER ANALYSIS

### 2.1 User Roles

| Role | Deskripsi | Akses |
|------|-----------|-------|
| **Admin/HC** | Human Capital yang mengelola sistem | Full access - CRUD semua data |
| **Pekerja** | Karyawan BRI yang mengajukan kenaikan golongan | Submit pengajuan, lihat status |
| **Atasan Langsung** | Supervisor/Manager yang menyetujui tahap 1 | Review & approve/reject pengajuan |
| **Manager Wilayah** | Manager wilayah yang menyetujui tahap 2 | Review & approve/reject pengajuan |
| **Kepala Wilayah** | Pejabat tertinggi yang menyetujui final | Final approval/reject |

---

## 3. FUNCTIONAL REQUIREMENTS

### 3.1 Modul Manajemen User

#### FR-001: Login & Autentikasi
- **Deskripsi**: User dapat login menggunakan username dan password
- **Input**: Username, Password
- **Proses**: Validasi kredensial, cek role user
- **Output**: Dashboard sesuai role
- **Business Rules**: 
  - Maksimal 3x percobaan login gagal
  - Session timeout 30 menit
  - Password minimal 8 karakter

#### FR-002: Manajemen Profil
- **Deskripsi**: User dapat melihat dan update profil
- **Input**: Data personal, kontak
- **Proses**: Update database
- **Output**: Konfirmasi update berhasil

### 3.2 Modul Manajemen Data Master

#### FR-003: Master Golongan Jabatan
- **Deskripsi**: Admin mengelola data golongan jabatan
- **Input**: Kode golongan, nama golongan, deskripsi, syarat
- **Proses**: CRUD golongan jabatan
- **Output**: Data tersimpan
- **Business Rules**:
  - Kode golongan harus unique
  - Struktur: I, II, III, IV (level) dengan sub A, B, C, D

#### FR-004: Master Divisi/Unit Kerja
- **Deskripsi**: Admin mengelola divisi/unit kerja
- **Input**: Kode divisi, nama divisi
- **Proses**: CRUD divisi
- **Output**: Data tersimpan

#### FR-005: Master Jabatan
- **Deskripsi**: Admin mengelola jabatan
- **Input**: Kode jabatan, nama jabatan, golongan minimal
- **Proses**: CRUD jabatan
- **Output**: Data tersimpan

### 3.3 Modul Manajemen Pekerja

#### FR-006: Data Pekerja
- **Deskripsi**: Admin mengelola data pekerja
- **Input**: 
  - NIP, Nama, Tanggal Lahir, Alamat
  - Divisi, Jabatan, Golongan Saat Ini
  - Tanggal Bergabung, Masa Kerja
  - Email, No Telepon
- **Proses**: CRUD data pekerja
- **Output**: Data tersimpan
- **Business Rules**:
  - NIP harus unique
  - Email harus valid format
  - Auto-calculate masa kerja

### 3.4 Modul Pengajuan Kenaikan Golongan

#### FR-007: Buat Pengajuan Baru
- **Deskripsi**: Pekerja mengajukan kenaikan golongan
- **Input**:
  - Golongan yang diajukan
  - Alasan pengajuan
  - Dokumen pendukung (PDF/Image)
    - Surat permohonan
    - Hasil penilaian kinerja
    - Sertifikat pelatihan/pendidikan
- **Proses**: 
  - Validasi syarat pengajuan
  - Upload dokumen
  - Generate nomor pengajuan
  - Set status "Pending"
- **Output**: Nomor pengajuan, notifikasi ke atasan
- **Business Rules**:
  - Minimal masa kerja: 2 tahun untuk kenaikan golongan
  - Nilai kinerja minimal: 80/100
  - Hanya bisa mengajukan 1 tingkat di atas golongan saat ini
  - Tidak ada pengajuan aktif yang sedang diproses
  - Maksimal ukuran file: 2MB per dokumen

#### FR-008: Lihat Status Pengajuan
- **Deskripsi**: Pekerja melihat status pengajuan mereka
- **Output**: 
  - Nomor pengajuan
  - Tanggal pengajuan
  - Status (Pending/Disetujui Atasan/Disetujui Manager/Approved/Rejected)
  - Timeline proses
  - Catatan/komentar dari approver

#### FR-009: Edit/Batalkan Pengajuan
- **Deskripsi**: Pekerja dapat edit atau batalkan pengajuan
- **Proses**: Update status menjadi "Dibatalkan"
- **Business Rules**: 
  - Hanya bisa dibatalkan jika status masih "Pending"

### 3.5 Modul Approval/Verifikasi

#### FR-010: Review Pengajuan (Atasan Langsung)
- **Deskripsi**: Atasan langsung review dan approve/reject pengajuan
- **Input**: 
  - Keputusan (Approve/Reject)
  - Catatan/komentar
- **Proses**: 
  - Update status pengajuan
  - Jika approve: forward ke Manager Wilayah
  - Jika reject: status "Ditolak Atasan"
- **Output**: Notifikasi ke pekerja dan approver berikutnya
- **Business Rules**:
  - Harus memberikan catatan jika reject
  - Batas waktu review: 7 hari kerja

#### FR-011: Review Pengajuan (Manager Wilayah)
- **Deskripsi**: Manager Wilayah review pengajuan tahap 2
- **Input**: Keputusan (Approve/Reject), Catatan
- **Proses**: 
  - Jika approve: forward ke Kepala Wilayah
  - Jika reject: status "Ditolak Manager"
- **Output**: Notifikasi
- **Business Rules**: Sama dengan FR-010

#### FR-012: Final Approval (Kepala Wilayah)
- **Deskripsi**: Kepala Wilayah memberikan persetujuan final
- **Input**: Keputusan (Approve/Reject), Catatan, Tanggal Efektif
- **Proses**: 
  - Jika approve: 
    - Status "Disetujui"
    - Update golongan pekerja
    - Generate surat keputusan
  - Jika reject: status "Ditolak Kepala Wilayah"
- **Output**: Notifikasi, Surat Keputusan (PDF)
- **Business Rules**: 
  - Tanggal efektif tidak boleh kurang dari tanggal approval

### 3.6 Modul Laporan

#### FR-013: Laporan Pengajuan
- **Deskripsi**: Menyediakan laporan pengajuan
- **Filter**: 
  - Periode (tanggal mulai - tanggal selesai)
  - Status
  - Divisi
  - Golongan
- **Output**: 
  - Tabel data pengajuan
  - Export PDF/Excel
  - Statistik (jumlah approve, reject, pending)

#### FR-014: Laporan Pekerja per Golongan
- **Deskripsi**: Laporan distribusi pekerja berdasarkan golongan
- **Filter**: Divisi, Golongan
- **Output**: 
  - Chart distribusi
  - Tabel detail
  - Export PDF/Excel

#### FR-015: Laporan Riwayat Kenaikan Golongan
- **Deskripsi**: Riwayat kenaikan golongan per pekerja
- **Input**: NIP/Nama Pekerja
- **Output**: Timeline kenaikan golongan

### 3.7 Modul Dashboard

#### FR-016: Dashboard Admin/HC
- **Output**:
  - Total pekerja per golongan
  - Pengajuan pending review
  - Statistik approval rate
  - Chart trend pengajuan per bulan
  - Quick action buttons

#### FR-017: Dashboard Pekerja
- **Output**:
  - Info golongan saat ini
  - Status pengajuan aktif
  - Riwayat pengajuan
  - Syarat kenaikan golongan berikutnya

#### FR-018: Dashboard Approver
- **Output**:
  - Jumlah pengajuan pending approval
  - List pengajuan yang perlu direview
  - Statistik approval
  - History approval

### 3.8 Modul Notifikasi

#### FR-019: Notifikasi Email
- **Trigger**:
  - Pengajuan baru dibuat → notif ke atasan
  - Pengajuan diapprove → notif ke pekerja & approver berikutnya
  - Pengajuan direject → notif ke pekerja
  - Pengajuan final approved → notif ke pekerja & HC
- **Content**: 
  - Subject sesuai aksi
  - Link ke detail pengajuan
  - Informasi ringkas

#### FR-020: Notifikasi In-App
- **Deskripsi**: Notifikasi dalam sistem (bell icon)
- **Output**: Badge jumlah notifikasi unread
- **Proses**: Mark as read saat diklik

---

## 4. NON-FUNCTIONAL REQUIREMENTS

### 4.1 Performance
- Response time < 3 detik untuk halaman utama
- Support minimal 100 concurrent users
- Database query optimization dengan indexing

### 4.2 Security
- Password hashing menggunakan bcrypt/SHA-256
- SQL injection prevention (prepared statements)
- XSS protection
- CSRF token untuk form submission
- File upload validation (type, size, malware scan)
- Role-based access control (RBAC)
- HTTPS untuk production

### 4.3 Usability
- Interface bahasa Indonesia
- Responsive design (mobile-friendly)
- Intuitive navigation
- Help/guide untuk setiap modul
- Breadcrumb navigation

### 4.4 Reliability
- Database backup harian
- Error logging
- Session management
- Data validation (client & server side)

### 4.5 Compatibility
- Browser: Chrome, Firefox, Safari, Edge (latest versions)
- Server: Apache/Nginx dengan PHP 7.4+
- Database: MySQL 5.7+ / MariaDB 10.3+
- Mobile responsive (Bootstrap/Tailwind CSS)

---

## 5. BUSINESS RULES SUMMARY

### 5.1 Syarat Kenaikan Golongan
1. Masa kerja minimal 2 tahun sejak golongan terakhir
2. Nilai kinerja minimal 80/100 dalam 1 tahun terakhir
3. Tidak ada pengajuan aktif yang sedang diproses
4. Kenaikan maksimal 1 tingkat (misal: II-C ke III-A)
5. Telah mengikuti pelatihan sesuai standar (jika diperlukan)

### 5.2 Workflow Approval
1. **Pekerja** submit pengajuan → status: "Pending"
2. **Atasan Langsung** review (7 hari) → Approve/Reject
   - Jika reject: selesai (status: "Ditolak Atasan")
   - Jika approve: lanjut ke tahap 3
3. **Manager Wilayah** review (7 hari) → Approve/Reject
   - Jika reject: selesai (status: "Ditolak Manager")
   - Jika approve: lanjut ke tahap 4
4. **Kepala Wilayah** final approval (7 hari) → Approve/Reject
   - Jika reject: selesai (status: "Ditolak Kepala Wilayah")
   - Jika approve: status "Disetujui", update golongan pekerja

### 5.3 Status Pengajuan
- **Pending**: Menunggu review atasan langsung
- **Disetujui Atasan**: Menunggu review manager wilayah
- **Disetujui Manager**: Menunggu final approval kepala wilayah
- **Disetujui**: Final approved, golongan sudah diupdate
- **Ditolak Atasan**: Rejected oleh atasan langsung
- **Ditolak Manager**: Rejected oleh manager wilayah
- **Ditolak Kepala Wilayah**: Rejected oleh kepala wilayah
- **Dibatalkan**: Dibatalkan oleh pekerja

---

## 6. DATA REQUIREMENTS

### 6.1 Data Master
- Golongan Jabatan (Kode, Nama, Syarat)
- Divisi/Unit Kerja
- Jabatan
- User/Pekerja

### 6.2 Data Transaksi
- Pengajuan Kenaikan Golongan
- Approval History
- Dokumen Pendukung
- Notifikasi

### 6.3 Data Reporting
- Log Aktivitas
- Statistik Pengajuan
- Audit Trail

---

## 7. INTEGRATION REQUIREMENTS

### 7.1 Email Service
- SMTP configuration untuk notifikasi email
- Email template management

### 7.2 File Storage
- Local storage untuk dokumen (dengan backup)
- Path: /uploads/documents/
- Struktur: /uploads/documents/{tahun}/{bulan}/{nip}/

---

## 8. TECHNICAL CONSTRAINTS

### 8.1 Technology Stack
- **Backend**: PHP Native (MVC Architecture)
- **Database**: MySQL (Laragon)
- **Frontend**: HTML5, CSS3, JavaScript, Bootstrap/Tailwind
- **Server**: Apache (Laragon)
- **Tools**: Composer (autoload), PHPMailer (email)

### 8.2 Development Guidelines
- MVC Pattern (Model-View-Controller)
- PDO untuk database connection
- Prepared statements untuk semua query
- OOP approach
- Code documentation
- Git version control

---

## 9. ASSUMPTIONS & DEPENDENCIES

### 9.1 Assumptions
- BRI Wilayah Padang memiliki infrastruktur server yang memadai
- User memiliki akses internet yang stabil
- Data pekerja sudah tersedia untuk import
- Struktur golongan jabatan sudah terdefinisi

### 9.2 Dependencies
- PHP 7.4 atau lebih tinggi
- MySQL 5.7 atau lebih tinggi
- Web server (Apache/Nginx)
- Email server/SMTP access
- Browser modern (support HTML5, CSS3, ES6)

---

## 10. SUCCESS CRITERIA

### 10.1 Kriteria Keberhasilan
1. Sistem dapat diakses 24/7 dengan uptime minimal 95%
2. Proses pengajuan berkurang dari rata-rata 21 hari menjadi maksimal 14 hari
3. Tingkat kepuasan user minimal 80% (berdasarkan survey)
4. Zero data loss dalam 6 bulan pertama
5. Pengurangan kesalahan input data minimal 70%
6. Transparansi 100% (semua stakeholder dapat tracking real-time)

### 10.2 Key Performance Indicators (KPI)
- Average processing time per pengajuan
- Approval rate (%)
- User adoption rate (%)
- System uptime (%)
- Number of errors/bugs reported
- User satisfaction score

---

## 11. PROJECT TIMELINE (Estimasi)

| Phase | Aktivitas | Durasi |
|-------|-----------|--------|
| 1 | Requirement Analysis & BRD | 1 minggu |
| 2 | Database Design & ERD | 1 minggu |
| 3 | UI/UX Design & Prototype | 1 minggu |
| 4 | Development - Backend | 3 minggu |
| 5 | Development - Frontend | 2 minggu |
| 6 | Integration & Testing | 2 minggu |
| 7 | User Acceptance Testing (UAT) | 1 minggu |
| 8 | Deployment & Training | 1 minggu |
| **Total** | | **12 minggu** |

---

## 12. RISK ANALYSIS

| Risk | Impact | Probability | Mitigation |
|------|--------|-------------|------------|
| Resistance to change dari user | High | Medium | Training & sosialisasi intensif |
| Data migration error | High | Low | Testing menyeluruh, backup data |
| Server downtime | Medium | Low | Backup server, monitoring 24/7 |
| Security breach | High | Low | Security audit, penetration testing |
| Scope creep | Medium | Medium | Clear requirement, change control process |

---

## APPROVAL

| Role | Name | Signature | Date |
|------|------|-----------|------|
| Project Sponsor | [Nama Kepala Wilayah] | _________ | ______ |
| Project Manager | [Nama PM] | _________ | ______ |
| Technical Lead | [Nama Tech Lead] | _________ | ______ |
| Business Analyst | [Nama BA] | _________ | ______ |

---

**Document Version**: 1.0  
**Last Updated**: 2024  
**Author**: [Nama Peneliti]  
**Status**: Draft/Final
