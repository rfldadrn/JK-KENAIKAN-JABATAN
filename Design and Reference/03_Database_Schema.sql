-- ============================================
-- DATABASE: sistem_kenaikan_golongan_bri
-- Created: 2024
-- Description: Database untuk Sistem Informasi Pengajuan Kenaikan Golongan Jabatan Pekerja BRI Wilayah Padang
-- ============================================

-- Create Database
CREATE DATABASE IF NOT EXISTS sistem_kenaikan_golongan_bri 
CHARACTER SET utf8mb4 
COLLATE utf8mb4_unicode_ci;

USE sistem_kenaikan_golongan_bri;

-- ============================================
-- 1. TABLE: golongan_jabatan
-- ============================================
CREATE TABLE golongan_jabatan (
    id_golongan INT AUTO_INCREMENT PRIMARY KEY,
    kode_golongan VARCHAR(10) NOT NULL UNIQUE,
    nama_golongan VARCHAR(100) NOT NULL,
    level INT NOT NULL,
    sub_level CHAR(1) NOT NULL,
    deskripsi TEXT,
    syarat_minimal TEXT,
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_level_sub (level, sub_level)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- 2. TABLE: divisi
-- ============================================
CREATE TABLE divisi (
    id_divisi INT AUTO_INCREMENT PRIMARY KEY,
    kode_divisi VARCHAR(20) NOT NULL UNIQUE,
    nama_divisi VARCHAR(100) NOT NULL,
    deskripsi TEXT,
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- 3. TABLE: jabatan
-- ============================================
CREATE TABLE jabatan (
    id_jabatan INT AUTO_INCREMENT PRIMARY KEY,
    kode_jabatan VARCHAR(20) NOT NULL UNIQUE,
    nama_jabatan VARCHAR(100) NOT NULL,
    id_golongan_minimal INT,
    deskripsi TEXT,
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (id_golongan_minimal) REFERENCES golongan_jabatan(id_golongan)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- 4. TABLE: pekerja
-- ============================================
CREATE TABLE pekerja (
    id_pekerja INT AUTO_INCREMENT PRIMARY KEY,
    nip VARCHAR(20) NOT NULL UNIQUE,
    nama_lengkap VARCHAR(100) NOT NULL,
    tempat_lahir VARCHAR(50),
    tanggal_lahir DATE,
    jenis_kelamin ENUM('L', 'P') NOT NULL,
    alamat TEXT,
    no_telepon VARCHAR(20),
    email VARCHAR(100) NOT NULL UNIQUE,
    id_divisi INT NOT NULL,
    id_jabatan INT NOT NULL,
    id_golongan_saat_ini INT NOT NULL,
    tanggal_bergabung DATE NOT NULL,
    tanggal_golongan_terakhir DATE,
    id_atasan INT,
    nilai_kinerja_terakhir DECIMAL(5,2),
    foto VARCHAR(255),
    status_kepegawaian ENUM('aktif', 'cuti', 'nonaktif') DEFAULT 'aktif',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (id_divisi) REFERENCES divisi(id_divisi),
    FOREIGN KEY (id_jabatan) REFERENCES jabatan(id_jabatan),
    FOREIGN KEY (id_golongan_saat_ini) REFERENCES golongan_jabatan(id_golongan),
    FOREIGN KEY (id_atasan) REFERENCES pekerja(id_pekerja),
    INDEX idx_divisi_status (id_divisi, status_kepegawaian),
    INDEX idx_status (status_kepegawaian)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- 5. TABLE: users
-- ============================================
CREATE TABLE users (
    id_user INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    role ENUM('admin', 'pekerja', 'atasan', 'manager', 'kepala_wilayah') NOT NULL,
    id_pekerja INT,
    is_active TINYINT(1) DEFAULT 1,
    last_login DATETIME,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (id_pekerja) REFERENCES pekerja(id_pekerja)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- 6. TABLE: pengajuan
-- ============================================
CREATE TABLE pengajuan (
    id_pengajuan INT AUTO_INCREMENT PRIMARY KEY,
    nomor_pengajuan VARCHAR(50) NOT NULL UNIQUE,
    id_pekerja INT NOT NULL,
    id_golongan_saat_ini INT NOT NULL,
    id_golongan_diajukan INT NOT NULL,
    tanggal_pengajuan DATE NOT NULL,
    alasan_pengajuan TEXT NOT NULL,
    status ENUM('pending', 'disetujui_atasan', 'disetujui_manager', 'disetujui', 
                'ditolak_atasan', 'ditolak_manager', 'ditolak_kepala_wilayah', 'dibatalkan') 
           DEFAULT 'pending',
    catatan_pemohon TEXT,
    tanggal_efektif DATE,
    nomor_sk VARCHAR(100),
    file_sk VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (id_pekerja) REFERENCES pekerja(id_pekerja),
    FOREIGN KEY (id_golongan_saat_ini) REFERENCES golongan_jabatan(id_golongan),
    FOREIGN KEY (id_golongan_diajukan) REFERENCES golongan_jabatan(id_golongan),
    INDEX idx_status (status),
    INDEX idx_tanggal (tanggal_pengajuan),
    INDEX idx_status_tanggal (status, tanggal_pengajuan)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- 7. TABLE: dokumen_pengajuan
-- ============================================
CREATE TABLE dokumen_pengajuan (
    id_dokumen INT AUTO_INCREMENT PRIMARY KEY,
    id_pengajuan INT NOT NULL,
    jenis_dokumen ENUM('surat_permohonan', 'penilaian_kinerja', 'sertifikat', 'lainnya') NOT NULL,
    nama_dokumen VARCHAR(200) NOT NULL,
    file_path VARCHAR(255) NOT NULL,
    file_size INT NOT NULL,
    mime_type VARCHAR(100) NOT NULL,
    keterangan VARCHAR(255),
    uploaded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_pengajuan) REFERENCES pengajuan(id_pengajuan) ON DELETE CASCADE,
    INDEX idx_pengajuan (id_pengajuan)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- 8. TABLE: approval_history
-- ============================================
CREATE TABLE approval_history (
    id_approval INT AUTO_INCREMENT PRIMARY KEY,
    id_pengajuan INT NOT NULL,
    level_approval ENUM('atasan', 'manager', 'kepala_wilayah') NOT NULL,
    id_approver INT NOT NULL,
    keputusan ENUM('approved', 'rejected') NOT NULL,
    catatan TEXT,
    tanggal_approval DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_pengajuan) REFERENCES pengajuan(id_pengajuan) ON DELETE CASCADE,
    FOREIGN KEY (id_approver) REFERENCES users(id_user),
    INDEX idx_pengajuan (id_pengajuan),
    INDEX idx_level (level_approval),
    INDEX idx_pengajuan_level (id_pengajuan, level_approval)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- 9. TABLE: notifikasi
-- ============================================
CREATE TABLE notifikasi (
    id_notifikasi INT AUTO_INCREMENT PRIMARY KEY,
    id_user INT NOT NULL,
    judul VARCHAR(200) NOT NULL,
    pesan TEXT NOT NULL,
    link VARCHAR(255),
    is_read TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_user) REFERENCES users(id_user) ON DELETE CASCADE,
    INDEX idx_user_read (id_user, is_read),
    INDEX idx_created (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- 10. TABLE: log_aktivitas
-- ============================================
CREATE TABLE log_aktivitas (
    id_log INT AUTO_INCREMENT PRIMARY KEY,
    id_user INT,
    aktivitas VARCHAR(255) NOT NULL,
    modul VARCHAR(50) NOT NULL,
    ip_address VARCHAR(45),
    user_agent TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_user) REFERENCES users(id_user) ON DELETE SET NULL,
    INDEX idx_user (id_user),
    INDEX idx_modul (modul),
    INDEX idx_created (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- VIEWS
-- ============================================

-- View: Pekerja Detail
CREATE VIEW v_pekerja_detail AS
SELECT 
    p.*,
    d.nama_divisi,
    d.kode_divisi,
    j.nama_jabatan,
    j.kode_jabatan,
    g.kode_golongan,
    g.nama_golongan,
    g.level AS level_golongan,
    g.sub_level AS sub_level_golongan,
    atasan.nama_lengkap AS nama_atasan,
    atasan.nip AS nip_atasan,
    TIMESTAMPDIFF(YEAR, p.tanggal_bergabung, CURDATE()) AS masa_kerja_tahun,
    TIMESTAMPDIFF(MONTH, p.tanggal_bergabung, CURDATE()) % 12 AS masa_kerja_bulan,
    TIMESTAMPDIFF(YEAR, p.tanggal_golongan_terakhir, CURDATE()) AS masa_golongan_tahun,
    TIMESTAMPDIFF(MONTH, p.tanggal_golongan_terakhir, CURDATE()) % 12 AS masa_golongan_bulan
FROM pekerja p
LEFT JOIN divisi d ON p.id_divisi = d.id_divisi
LEFT JOIN jabatan j ON p.id_jabatan = j.id_jabatan
LEFT JOIN golongan_jabatan g ON p.id_golongan_saat_ini = g.id_golongan
LEFT JOIN pekerja atasan ON p.id_atasan = atasan.id_pekerja;

-- View: Pengajuan Detail
CREATE VIEW v_pengajuan_detail AS
SELECT 
    pg.*,
    p.nip,
    p.nama_lengkap,
    p.email,
    p.no_telepon,
    d.nama_divisi,
    d.kode_divisi,
    j.nama_jabatan,
    g_saat_ini.kode_golongan AS golongan_saat_ini,
    g_saat_ini.nama_golongan AS nama_golongan_saat_ini,
    g_diajukan.kode_golongan AS golongan_diajukan,
    g_diajukan.nama_golongan AS nama_golongan_diajukan,
    g_diajukan.level AS level_diajukan,
    atasan.nama_lengkap AS nama_atasan,
    atasan.nip AS nip_atasan,
    atasan.email AS email_atasan
FROM pengajuan pg
JOIN pekerja p ON pg.id_pekerja = p.id_pekerja
LEFT JOIN divisi d ON p.id_divisi = d.id_divisi
LEFT JOIN jabatan j ON p.id_jabatan = j.id_jabatan
LEFT JOIN golongan_jabatan g_saat_ini ON pg.id_golongan_saat_ini = g_saat_ini.id_golongan
LEFT JOIN golongan_jabatan g_diajukan ON pg.id_golongan_diajukan = g_diajukan.id_golongan
LEFT JOIN pekerja atasan ON p.id_atasan = atasan.id_pekerja;

-- ============================================
-- STORED PROCEDURES
-- ============================================

-- SP: Generate Nomor Pengajuan
DELIMITER //
CREATE PROCEDURE sp_generate_nomor_pengajuan(
    IN p_tanggal DATE,
    OUT p_nomor VARCHAR(50)
)
BEGIN
    DECLARE tahun VARCHAR(4);
    DECLARE bulan VARCHAR(2);
    DECLARE counter INT;
    
    SET tahun = YEAR(p_tanggal);
    SET bulan = LPAD(MONTH(p_tanggal), 2, '0');
    
    -- Hitung counter untuk bulan ini
    SELECT COALESCE(COUNT(*) + 1, 1) INTO counter
    FROM pengajuan
    WHERE YEAR(tanggal_pengajuan) = tahun 
    AND MONTH(tanggal_pengajuan) = MONTH(p_tanggal);
    
    -- Format: PG/2024/01/0001
    SET p_nomor = CONCAT('PG/', tahun, '/', bulan, '/', LPAD(counter, 4, '0'));
END //
DELIMITER ;

-- SP: Get Pengajuan untuk Approval (berdasarkan role)
DELIMITER //
CREATE PROCEDURE sp_get_pengajuan_for_approval(
    IN p_role VARCHAR(50),
    IN p_id_pekerja INT
)
BEGIN
    IF p_role = 'atasan' THEN
        -- Untuk atasan: ambil pengajuan dengan status pending dari bawahan langsung
        SELECT pg.* FROM pengajuan pg
        JOIN pekerja p ON pg.id_pekerja = p.id_pekerja
        WHERE pg.status = 'pending' 
        AND p.id_atasan = p_id_pekerja
        ORDER BY pg.tanggal_pengajuan ASC;
        
    ELSEIF p_role = 'manager' THEN
        -- Untuk manager: ambil pengajuan dengan status disetujui_atasan
        SELECT * FROM pengajuan 
        WHERE status = 'disetujui_atasan'
        ORDER BY tanggal_pengajuan ASC;
        
    ELSEIF p_role = 'kepala_wilayah' THEN
        -- Untuk kepala wilayah: ambil pengajuan dengan status disetujui_manager
        SELECT * FROM pengajuan 
        WHERE status = 'disetujui_manager'
        ORDER BY tanggal_pengajuan ASC;
    END IF;
END //
DELIMITER ;

-- SP: Validasi Syarat Kenaikan Golongan
DELIMITER //
CREATE PROCEDURE sp_validasi_kenaikan_golongan(
    IN p_id_pekerja INT,
    IN p_id_golongan_diajukan INT,
    OUT p_valid BOOLEAN,
    OUT p_pesan VARCHAR(255)
)
BEGIN
    DECLARE v_masa_golongan INT;
    DECLARE v_nilai_kinerja DECIMAL(5,2);
    DECLARE v_pengajuan_aktif INT;
    DECLARE v_level_sekarang INT;
    DECLARE v_level_diajukan INT;
    DECLARE v_sub_sekarang CHAR(1);
    DECLARE v_sub_diajukan CHAR(1);
    
    SET p_valid = TRUE;
    SET p_pesan = 'Memenuhi syarat';
    
    -- Cek masa kerja sejak golongan terakhir (minimal 2 tahun)
    SELECT TIMESTAMPDIFF(YEAR, tanggal_golongan_terakhir, CURDATE()),
           nilai_kinerja_terakhir
    INTO v_masa_golongan, v_nilai_kinerja
    FROM pekerja
    WHERE id_pekerja = p_id_pekerja;
    
    IF v_masa_golongan < 2 THEN
        SET p_valid = FALSE;
        SET p_pesan = 'Masa kerja sejak golongan terakhir kurang dari 2 tahun';
        LEAVE proc_label;
    END IF;
    
    -- Cek nilai kinerja (minimal 80)
    IF v_nilai_kinerja < 80 THEN
        SET p_valid = FALSE;
        SET p_pesan = 'Nilai kinerja terakhir kurang dari 80';
        LEAVE proc_label;
    END IF;
    
    -- Cek apakah ada pengajuan aktif
    SELECT COUNT(*) INTO v_pengajuan_aktif
    FROM pengajuan
    WHERE id_pekerja = p_id_pekerja
    AND status NOT IN ('disetujui', 'ditolak_atasan', 'ditolak_manager', 'ditolak_kepala_wilayah', 'dibatalkan');
    
    IF v_pengajuan_aktif > 0 THEN
        SET p_valid = FALSE;
        SET p_pesan = 'Masih ada pengajuan aktif yang sedang diproses';
        LEAVE proc_label;
    END IF;
    
    -- Cek kenaikan level (maksimal 1 level)
    SELECT g1.level, g1.sub_level, g2.level, g2.sub_level
    INTO v_level_sekarang, v_sub_sekarang, v_level_diajukan, v_sub_diajukan
    FROM pekerja p
    JOIN golongan_jabatan g1 ON p.id_golongan_saat_ini = g1.id_golongan
    JOIN golongan_jabatan g2 ON g2.id_golongan = p_id_golongan_diajukan
    WHERE p.id_pekerja = p_id_pekerja;
    
    -- Validasi: hanya boleh naik 1 level atau dalam level yang sama
    IF v_level_diajukan - v_level_sekarang > 1 THEN
        SET p_valid = FALSE;
        SET p_pesan = 'Kenaikan golongan maksimal 1 level';
        LEAVE proc_label;
    END IF;
    
    -- Jika level sama, sub_level harus lebih tinggi
    IF v_level_diajukan = v_level_sekarang AND v_sub_diajukan <= v_sub_sekarang THEN
        SET p_valid = FALSE;
        SET p_pesan = 'Golongan yang diajukan tidak lebih tinggi dari golongan saat ini';
        LEAVE proc_label;
    END IF;
    
    proc_label: BEGIN END;
END //
DELIMITER ;

-- ============================================
-- TRIGGERS
-- ============================================

-- Trigger: Auto-generate nomor pengajuan
DELIMITER //
CREATE TRIGGER trg_before_insert_pengajuan
BEFORE INSERT ON pengajuan
FOR EACH ROW
BEGIN
    DECLARE v_nomor VARCHAR(50);
    CALL sp_generate_nomor_pengajuan(NEW.tanggal_pengajuan, v_nomor);
    SET NEW.nomor_pengajuan = v_nomor;
END //
DELIMITER ;

-- Trigger: Update golongan pekerja setelah final approval
DELIMITER //
CREATE TRIGGER trg_after_update_pengajuan
AFTER UPDATE ON pengajuan
FOR EACH ROW
BEGIN
    IF NEW.status = 'disetujui' AND OLD.status != 'disetujui' THEN
        UPDATE pekerja
        SET id_golongan_saat_ini = NEW.id_golongan_diajukan,
            tanggal_golongan_terakhir = NEW.tanggal_efektif
        WHERE id_pekerja = NEW.id_pekerja;
    END IF;
END //
DELIMITER ;

-- ============================================
-- END OF SQL SCRIPT
-- ============================================
