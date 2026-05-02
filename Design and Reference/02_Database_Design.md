# DATABASE DESIGN
## SISTEM INFORMASI PENGAJUAN KENAIKAN GOLONGAN JABATAN PEKERJA
### BRI WILAYAH PADANG

---

## 1. ENTITY RELATIONSHIP DIAGRAM (ERD)

### 1.1 Entities Overview

```
1. users (Pengguna sistem)
2. golongan_jabatan (Master golongan)
3. divisi (Master divisi/unit kerja)
4. jabatan (Master jabatan)
5. pekerja (Data karyawan)
6. pengajuan (Data pengajuan kenaikan golongan)
7. dokumen_pengajuan (Dokumen pendukung)
8. approval_history (Riwayat persetujuan)
9. notifikasi (Notifikasi sistem)
10. log_aktivitas (Audit trail)
```

---

## 2. TABLE STRUCTURE DETAILS

### 2.1 Table: users
**Deskripsi**: Menyimpan data pengguna sistem dengan berbagai role

| Column | Type | Length | Constraint | Description |
|--------|------|--------|------------|-------------|
| id_user | INT | - | PK, AI | Primary key |
| username | VARCHAR | 50 | UNIQUE, NOT NULL | Username login |
| password | VARCHAR | 255 | NOT NULL | Password (hashed) |
| email | VARCHAR | 100 | UNIQUE, NOT NULL | Email user |
| role | ENUM | - | NOT NULL | 'admin','pekerja','atasan','manager','kepala_wilayah' |
| id_pekerja | INT | - | FK, NULL | Foreign key ke tabel pekerja (jika role pekerja/atasan/manager/kepala) |
| is_active | TINYINT | 1 | DEFAULT 1 | Status aktif (1=aktif, 0=nonaktif) |
| last_login | DATETIME | - | NULL | Waktu login terakhir |
| created_at | TIMESTAMP | - | DEFAULT CURRENT_TIMESTAMP | Waktu dibuat |
| updated_at | TIMESTAMP | - | ON UPDATE | Waktu diupdate |

**Indexes**:
- PRIMARY KEY (id_user)
- UNIQUE KEY (username)
- UNIQUE KEY (email)
- FOREIGN KEY (id_pekerja) REFERENCES pekerja(id_pekerja)

---

### 2.2 Table: golongan_jabatan
**Deskripsi**: Master data golongan jabatan

| Column | Type | Length | Constraint | Description |
|--------|------|--------|------------|-------------|
| id_golongan | INT | - | PK, AI | Primary key |
| kode_golongan | VARCHAR | 10 | UNIQUE, NOT NULL | Kode golongan (I-A, II-B, dst) |
| nama_golongan | VARCHAR | 100 | NOT NULL | Nama golongan |
| level | INT | - | NOT NULL | Level golongan (1-4) |
| sub_level | CHAR | 1 | NOT NULL | Sub level (A-D) |
| deskripsi | TEXT | - | NULL | Deskripsi golongan |
| syarat_minimal | TEXT | - | NULL | Syarat minimal untuk golongan ini |
| is_active | TINYINT | 1 | DEFAULT 1 | Status aktif |
| created_at | TIMESTAMP | - | DEFAULT CURRENT_TIMESTAMP | Waktu dibuat |
| updated_at | TIMESTAMP | - | ON UPDATE | Waktu diupdate |

**Indexes**:
- PRIMARY KEY (id_golongan)
- UNIQUE KEY (kode_golongan)
- INDEX (level, sub_level)

---

### 2.3 Table: divisi
**Deskripsi**: Master data divisi/unit kerja

| Column | Type | Length | Constraint | Description |
|--------|------|--------|------------|-------------|
| id_divisi | INT | - | PK, AI | Primary key |
| kode_divisi | VARCHAR | 20 | UNIQUE, NOT NULL | Kode divisi |
| nama_divisi | VARCHAR | 100 | NOT NULL | Nama divisi |
| deskripsi | TEXT | - | NULL | Deskripsi divisi |
| is_active | TINYINT | 1 | DEFAULT 1 | Status aktif |
| created_at | TIMESTAMP | - | DEFAULT CURRENT_TIMESTAMP | Waktu dibuat |
| updated_at | TIMESTAMP | - | ON UPDATE | Waktu diupdate |

**Indexes**:
- PRIMARY KEY (id_divisi)
- UNIQUE KEY (kode_divisi)

---

### 2.4 Table: jabatan
**Deskripsi**: Master data jabatan

| Column | Type | Length | Constraint | Description |
|--------|------|--------|------------|-------------|
| id_jabatan | INT | - | PK, AI | Primary key |
| kode_jabatan | VARCHAR | 20 | UNIQUE, NOT NULL | Kode jabatan |
| nama_jabatan | VARCHAR | 100 | NOT NULL | Nama jabatan |
| id_golongan_minimal | INT | - | FK, NULL | Golongan minimal untuk jabatan ini |
| deskripsi | TEXT | - | NULL | Deskripsi jabatan |
| is_active | TINYINT | 1 | DEFAULT 1 | Status aktif |
| created_at | TIMESTAMP | - | DEFAULT CURRENT_TIMESTAMP | Waktu dibuat |
| updated_at | TIMESTAMP | - | ON UPDATE | Waktu diupdate |

**Indexes**:
- PRIMARY KEY (id_jabatan)
- UNIQUE KEY (kode_jabatan)
- FOREIGN KEY (id_golongan_minimal) REFERENCES golongan_jabatan(id_golongan)

---

### 2.5 Table: pekerja
**Deskripsi**: Master data pekerja/karyawan BRI

| Column | Type | Length | Constraint | Description |
|--------|------|--------|------------|-------------|
| id_pekerja | INT | - | PK, AI | Primary key |
| nip | VARCHAR | 20 | UNIQUE, NOT NULL | Nomor Induk Pekerja |
| nama_lengkap | VARCHAR | 100 | NOT NULL | Nama lengkap |
| tempat_lahir | VARCHAR | 50 | NULL | Tempat lahir |
| tanggal_lahir | DATE | - | NULL | Tanggal lahir |
| jenis_kelamin | ENUM | - | NOT NULL | 'L','P' |
| alamat | TEXT | - | NULL | Alamat lengkap |
| no_telepon | VARCHAR | 20 | NULL | Nomor telepon |
| email | VARCHAR | 100 | NOT NULL | Email |
| id_divisi | INT | - | FK, NOT NULL | Foreign key ke divisi |
| id_jabatan | INT | - | FK, NOT NULL | Foreign key ke jabatan |
| id_golongan_saat_ini | INT | - | FK, NOT NULL | Golongan saat ini |
| tanggal_bergabung | DATE | - | NOT NULL | Tanggal mulai bekerja |
| tanggal_golongan_terakhir | DATE | - | NULL | Tanggal kenaikan golongan terakhir |
| id_atasan | INT | - | FK, NULL | Foreign key ke pekerja (atasan langsung) |
| nilai_kinerja_terakhir | DECIMAL | 5,2 | NULL | Nilai kinerja terakhir (0-100) |
| foto | VARCHAR | 255 | NULL | Path foto pekerja |
| status_kepegawaian | ENUM | - | DEFAULT 'aktif' | 'aktif','cuti','nonaktif' |
| created_at | TIMESTAMP | - | DEFAULT CURRENT_TIMESTAMP | Waktu dibuat |
| updated_at | TIMESTAMP | - | ON UPDATE | Waktu diupdate |

**Indexes**:
- PRIMARY KEY (id_pekerja)
- UNIQUE KEY (nip)
- UNIQUE KEY (email)
- FOREIGN KEY (id_divisi) REFERENCES divisi(id_divisi)
- FOREIGN KEY (id_jabatan) REFERENCES jabatan(id_jabatan)
- FOREIGN KEY (id_golongan_saat_ini) REFERENCES golongan_jabatan(id_golongan)
- FOREIGN KEY (id_atasan) REFERENCES pekerja(id_pekerja)
- INDEX (status_kepegawaian)

---

### 2.6 Table: pengajuan
**Deskripsi**: Data pengajuan kenaikan golongan

| Column | Type | Length | Constraint | Description |
|--------|------|--------|------------|-------------|
| id_pengajuan | INT | - | PK, AI | Primary key |
| nomor_pengajuan | VARCHAR | 50 | UNIQUE, NOT NULL | Nomor pengajuan (auto-generated) |
| id_pekerja | INT | - | FK, NOT NULL | Foreign key ke pekerja |
| id_golongan_saat_ini | INT | - | FK, NOT NULL | Golongan saat pengajuan |
| id_golongan_diajukan | INT | - | FK, NOT NULL | Golongan yang diajukan |
| tanggal_pengajuan | DATE | - | NOT NULL | Tanggal pengajuan |
| alasan_pengajuan | TEXT | - | NOT NULL | Alasan/justifikasi pengajuan |
| status | ENUM | - | DEFAULT 'pending' | 'pending','disetujui_atasan','disetujui_manager','disetujui','ditolak_atasan','ditolak_manager','ditolak_kepala_wilayah','dibatalkan' |
| catatan_pemohon | TEXT | - | NULL | Catatan tambahan dari pemohon |
| tanggal_efektif | DATE | - | NULL | Tanggal efektif kenaikan golongan |
| nomor_sk | VARCHAR | 100 | NULL | Nomor Surat Keputusan |
| file_sk | VARCHAR | 255 | NULL | Path file SK (jika sudah approved) |
| created_at | TIMESTAMP | - | DEFAULT CURRENT_TIMESTAMP | Waktu dibuat |
| updated_at | TIMESTAMP | - | ON UPDATE | Waktu diupdate |

**Indexes**:
- PRIMARY KEY (id_pengajuan)
- UNIQUE KEY (nomor_pengajuan)
- FOREIGN KEY (id_pekerja) REFERENCES pekerja(id_pekerja)
- FOREIGN KEY (id_golongan_saat_ini) REFERENCES golongan_jabatan(id_golongan)
- FOREIGN KEY (id_golongan_diajukan) REFERENCES golongan_jabatan(id_golongan)
- INDEX (status)
- INDEX (tanggal_pengajuan)

---

### 2.7 Table: dokumen_pengajuan
**Deskripsi**: Dokumen pendukung pengajuan

| Column | Type | Length | Constraint | Description |
|--------|------|--------|------------|-------------|
| id_dokumen | INT | - | PK, AI | Primary key |
| id_pengajuan | INT | - | FK, NOT NULL | Foreign key ke pengajuan |
| jenis_dokumen | ENUM | - | NOT NULL | 'surat_permohonan','penilaian_kinerja','sertifikat','lainnya' |
| nama_dokumen | VARCHAR | 200 | NOT NULL | Nama file dokumen |
| file_path | VARCHAR | 255 | NOT NULL | Path file di server |
| file_size | INT | - | NOT NULL | Ukuran file (bytes) |
| mime_type | VARCHAR | 100 | NOT NULL | Tipe MIME file |
| keterangan | VARCHAR | 255 | NULL | Keterangan dokumen |
| uploaded_at | TIMESTAMP | - | DEFAULT CURRENT_TIMESTAMP | Waktu upload |

**Indexes**:
- PRIMARY KEY (id_dokumen)
- FOREIGN KEY (id_pengajuan) REFERENCES pengajuan(id_pengajuan) ON DELETE CASCADE
- INDEX (id_pengajuan)

---

### 2.8 Table: approval_history
**Deskripsi**: Riwayat persetujuan pengajuan

| Column | Type | Length | Constraint | Description |
|--------|------|--------|------------|-------------|
| id_approval | INT | - | PK, AI | Primary key |
| id_pengajuan | INT | - | FK, NOT NULL | Foreign key ke pengajuan |
| level_approval | ENUM | - | NOT NULL | 'atasan','manager','kepala_wilayah' |
| id_approver | INT | - | FK, NOT NULL | Foreign key ke user (approver) |
| keputusan | ENUM | - | NOT NULL | 'approved','rejected' |
| catatan | TEXT | - | NULL | Catatan/komentar approver |
| tanggal_approval | DATETIME | - | DEFAULT CURRENT_TIMESTAMP | Waktu approval/reject |

**Indexes**:
- PRIMARY KEY (id_approval)
- FOREIGN KEY (id_pengajuan) REFERENCES pengajuan(id_pengajuan) ON DELETE CASCADE
- FOREIGN KEY (id_approver) REFERENCES users(id_user)
- INDEX (id_pengajuan)
- INDEX (level_approval)

---

### 2.9 Table: notifikasi
**Deskripsi**: Notifikasi in-app untuk user

| Column | Type | Length | Constraint | Description |
|--------|------|--------|------------|-------------|
| id_notifikasi | INT | - | PK, AI | Primary key |
| id_user | INT | - | FK, NOT NULL | Foreign key ke user penerima |
| judul | VARCHAR | 200 | NOT NULL | Judul notifikasi |
| pesan | TEXT | - | NOT NULL | Isi pesan |
| link | VARCHAR | 255 | NULL | Link terkait (URL detail) |
| is_read | TINYINT | 1 | DEFAULT 0 | Status baca (0=unread, 1=read) |
| created_at | TIMESTAMP | - | DEFAULT CURRENT_TIMESTAMP | Waktu dibuat |

**Indexes**:
- PRIMARY KEY (id_notifikasi)
- FOREIGN KEY (id_user) REFERENCES users(id_user) ON DELETE CASCADE
- INDEX (id_user, is_read)
- INDEX (created_at)

---

### 2.10 Table: log_aktivitas
**Deskripsi**: Log aktivitas user untuk audit trail

| Column | Type | Length | Constraint | Description |
|--------|------|--------|------------|-------------|
| id_log | INT | - | PK, AI | Primary key |
| id_user | INT | - | FK, NULL | Foreign key ke user |
| aktivitas | VARCHAR | 255 | NOT NULL | Deskripsi aktivitas |
| modul | VARCHAR | 50 | NOT NULL | Modul sistem (pengajuan, approval, dll) |
| ip_address | VARCHAR | 45 | NULL | IP address user |
| user_agent | TEXT | - | NULL | Browser/device info |
| created_at | TIMESTAMP | - | DEFAULT CURRENT_TIMESTAMP | Waktu aktivitas |

**Indexes**:
- PRIMARY KEY (id_log)
- FOREIGN KEY (id_user) REFERENCES users(id_user) ON DELETE SET NULL
- INDEX (id_user)
- INDEX (modul)
- INDEX (created_at)

---

## 3. RELATIONSHIPS

```
users (1) ←→ (0..1) pekerja
users (1) ←→ (0..*) notifikasi
users (1) ←→ (0..*) log_aktivitas
users (1) ←→ (0..*) approval_history

golongan_jabatan (1) ←→ (0..*) jabatan (golongan_minimal)
golongan_jabatan (1) ←→ (0..*) pekerja (golongan_saat_ini)
golongan_jabatan (1) ←→ (0..*) pengajuan (golongan_saat_ini)
golongan_jabatan (1) ←→ (0..*) pengajuan (golongan_diajukan)

divisi (1) ←→ (0..*) pekerja

jabatan (1) ←→ (0..*) pekerja

pekerja (1) ←→ (0..*) pekerja (atasan)
pekerja (1) ←→ (0..*) pengajuan

pengajuan (1) ←→ (0..*) dokumen_pengajuan
pengajuan (1) ←→ (0..*) approval_history
```

---

## 4. BUSINESS LOGIC & TRIGGERS

### 4.1 Auto-Generate Nomor Pengajuan
```
Format: PG/{TAHUN}/{BULAN}/{COUNTER}
Contoh: PG/2024/01/0001

Trigger: BEFORE INSERT pengajuan
Logic: 
- Ambil tahun dan bulan dari tanggal pengajuan
- Hitung counter berdasarkan pengajuan di bulan yang sama
- Generate nomor dengan format di atas
```

### 4.2 Update Golongan Pekerja (After Final Approval)
```
Trigger: AFTER UPDATE pengajuan (status = 'disetujui')
Logic:
- Update pekerja.id_golongan_saat_ini = pengajuan.id_golongan_diajukan
- Update pekerja.tanggal_golongan_terakhir = pengajuan.tanggal_efektif
```

### 4.3 Validasi Kenaikan Golongan
```
Logic (di aplikasi):
1. Cek masa kerja: 
   - (tanggal_pengajuan - pekerja.tanggal_golongan_terakhir) >= 2 tahun
   
2. Cek nilai kinerja:
   - pekerja.nilai_kinerja_terakhir >= 80
   
3. Cek pengajuan aktif:
   - COUNT pengajuan WHERE id_pekerja = X AND status NOT IN ('disetujui','ditolak_*','dibatalkan') = 0
   
4. Cek level kenaikan:
   - Golongan diajukan hanya boleh 1 level lebih tinggi
   - Contoh: II-C → III-A (valid), II-C → IV-A (invalid)
```

---

## 5. VIEWS (Optional - untuk kemudahan query)

### 5.1 View: v_pekerja_detail
```sql
CREATE VIEW v_pekerja_detail AS
SELECT 
    p.*,
    d.nama_divisi,
    j.nama_jabatan,
    g.kode_golongan,
    g.nama_golongan,
    atasan.nama_lengkap AS nama_atasan,
    TIMESTAMPDIFF(YEAR, p.tanggal_bergabung, CURDATE()) AS masa_kerja_tahun,
    TIMESTAMPDIFF(YEAR, p.tanggal_golongan_terakhir, CURDATE()) AS masa_golongan_tahun
FROM pekerja p
LEFT JOIN divisi d ON p.id_divisi = d.id_divisi
LEFT JOIN jabatan j ON p.id_jabatan = j.id_jabatan
LEFT JOIN golongan_jabatan g ON p.id_golongan_saat_ini = g.id_golongan
LEFT JOIN pekerja atasan ON p.id_atasan = atasan.id_pekerja;
```

### 5.2 View: v_pengajuan_detail
```sql
CREATE VIEW v_pengajuan_detail AS
SELECT 
    pg.*,
    p.nip,
    p.nama_lengkap,
    p.email,
    d.nama_divisi,
    j.nama_jabatan,
    g_saat_ini.kode_golongan AS golongan_saat_ini,
    g_diajukan.kode_golongan AS golongan_diajukan,
    g_diajukan.nama_golongan AS nama_golongan_diajukan,
    atasan.nama_lengkap AS nama_atasan
FROM pengajuan pg
JOIN pekerja p ON pg.id_pekerja = p.id_pekerja
LEFT JOIN divisi d ON p.id_divisi = d.id_divisi
LEFT JOIN jabatan j ON p.id_jabatan = j.id_jabatan
LEFT JOIN golongan_jabatan g_saat_ini ON pg.id_golongan_saat_ini = g_saat_ini.id_golongan
LEFT JOIN golongan_jabatan g_diajukan ON pg.id_golongan_diajukan = g_diajukan.id_golongan
LEFT JOIN pekerja atasan ON p.id_atasan = atasan.id_pekerja;
```

---

## 6. STORED PROCEDURES (Optional)

### 6.1 SP: get_pengajuan_pending_by_role
```sql
-- Untuk mengambil pengajuan yang perlu direview berdasarkan role
DELIMITER //
CREATE PROCEDURE get_pengajuan_pending_by_role(
    IN p_role VARCHAR(50),
    IN p_id_user INT
)
BEGIN
    IF p_role = 'atasan' THEN
        -- Ambil pengajuan yang status pending dan user adalah atasan langsung
        SELECT pg.* FROM pengajuan pg
        JOIN pekerja p ON pg.id_pekerja = p.id_pekerja
        WHERE pg.status = 'pending' AND p.id_atasan IN (
            SELECT id_pekerja FROM pekerja WHERE id_pekerja = (
                SELECT id_pekerja FROM users WHERE id_user = p_id_user
            )
        );
    ELSEIF p_role = 'manager' THEN
        SELECT * FROM pengajuan WHERE status = 'disetujui_atasan';
    ELSEIF p_role = 'kepala_wilayah' THEN
        SELECT * FROM pengajuan WHERE status = 'disetujui_manager';
    END IF;
END //
DELIMITER ;
```

---

## 7. INDEXES OPTIMIZATION

### Recommended Indexes:
```sql
-- Composite indexes untuk performa query
CREATE INDEX idx_pekerja_divisi_status ON pekerja(id_divisi, status_kepegawaian);
CREATE INDEX idx_pengajuan_status_tanggal ON pengajuan(status, tanggal_pengajuan);
CREATE INDEX idx_notifikasi_user_read ON notifikasi(id_user, is_read, created_at);
CREATE INDEX idx_approval_pengajuan_level ON approval_history(id_pengajuan, level_approval);
```

---

## 8. DATA MIGRATION NOTES

### Initial Data yang Perlu Disiapkan:

1. **Golongan Jabatan**:
   - Level I: I-A, I-B, I-C, I-D
   - Level II: II-A, II-B, II-C, II-D
   - Level III: III-A, III-B, III-C, III-D
   - Level IV: IV-A, IV-B, IV-C, IV-D

2. **Divisi**:
   - Operasional
   - Marketing
   - Kredit
   - IT
   - Human Capital
   - Risk Management
   - dll.

3. **User Admin**:
   - Username: admin
   - Password: (hashed)
   - Role: admin

---

## 9. BACKUP & MAINTENANCE

### Backup Strategy:
- **Daily**: Full backup database
- **Weekly**: Export ke SQL file
- **Monthly**: Offsite backup

### Maintenance:
- **Weekly**: Optimize tables
- **Monthly**: Check indexes
- **Quarterly**: Archive old data (pengajuan > 2 tahun)

---

**Document Version**: 1.0  
**Last Updated**: 2024  
**Database**: MySQL 5.7+
