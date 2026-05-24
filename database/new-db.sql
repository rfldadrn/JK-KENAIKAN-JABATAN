-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               8.4.3 - MySQL Community Server - GPL
-- Server OS:                    Win64
-- HeidiSQL Version:             12.8.0.6908
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Dumping database structure for sistem_kenaikan_golongan_bri
CREATE DATABASE IF NOT EXISTS `sistem_kenaikan_golongan_bri` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `sistem_kenaikan_golongan_bri`;

-- Dumping structure for table sistem_kenaikan_golongan_bri.approval_history
CREATE TABLE IF NOT EXISTS `approval_history` (
  `id_approval` int NOT NULL AUTO_INCREMENT,
  `id_pengajuan` int NOT NULL,
  `level_approval` enum('atasan','manager','kepala_wilayah') COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_approver` int NOT NULL,
  `keputusan` enum('approved','rejected') COLLATE utf8mb4_unicode_ci NOT NULL,
  `catatan` text COLLATE utf8mb4_unicode_ci,
  `tanggal_approval` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_approval`),
  KEY `id_approver` (`id_approver`),
  KEY `idx_pengajuan` (`id_pengajuan`),
  KEY `idx_level` (`level_approval`),
  KEY `idx_pengajuan_level` (`id_pengajuan`,`level_approval`),
  CONSTRAINT `approval_history_ibfk_1` FOREIGN KEY (`id_pengajuan`) REFERENCES `pengajuan` (`id_pengajuan`) ON DELETE CASCADE,
  CONSTRAINT `approval_history_ibfk_2` FOREIGN KEY (`id_approver`) REFERENCES `users` (`id_user`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table sistem_kenaikan_golongan_bri.approval_history: ~1 rows (approximately)
REPLACE INTO `approval_history` (`id_approval`, `id_pengajuan`, `level_approval`, `id_approver`, `keputusan`, `catatan`, `tanggal_approval`) VALUES
	(11, 31, 'atasan', 21, 'approved', 'Pengajuan disetujui. Pekerja menunjukkan peningkatan kinerja yang sangat signifikan. Berhasil meningkatkan kepuasan nasabah dari 85% menjadi 95% dalam 1 tahun terakhir. Sangat layak untuk kenaikan golongan ke I-D. Testing approval.', '2026-05-02 00:00:00'),
	(12, 30, 'atasan', 21, 'approved', 'test', '2026-05-03 15:44:39'),
	(13, 32, 'atasan', 21, 'approved', 'boleh boleh', '2026-05-03 16:13:10'),
	(14, 32, 'manager', 3, 'approved', 'hemmmm bentar', '2026-05-03 16:13:55'),
	(15, 31, 'manager', 3, 'rejected', 'ga ah', '2026-05-03 16:14:28'),
	(16, 30, 'manager', 3, 'approved', 'asd', '2026-05-03 16:14:38'),
	(17, 30, 'kepala_wilayah', 2, 'approved', 'oke boleh', '2026-05-03 16:15:30'),
	(18, 32, 'kepala_wilayah', 2, 'approved', 'oke boleh', '2026-05-03 16:16:29'),
	(19, 33, 'atasan', 21, 'rejected', '', '2026-05-03 16:34:45'),
	(20, 34, 'atasan', 21, 'rejected', '', '2026-05-03 16:37:33'),
	(21, 35, 'atasan', 21, 'approved', 'oke sudah baik', '2026-05-03 16:46:08'),
	(22, 35, 'manager', 3, 'approved', 'oke sudah mantapbs', '2026-05-03 16:46:47'),
	(23, 35, 'kepala_wilayah', 2, 'approved', 'sip mantap', '2026-05-03 16:48:04');

-- Dumping structure for table sistem_kenaikan_golongan_bri.divisi
CREATE TABLE IF NOT EXISTS `divisi` (
  `id_divisi` int NOT NULL AUTO_INCREMENT,
  `kode_divisi` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_divisi` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deskripsi` text COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_divisi`),
  UNIQUE KEY `kode_divisi` (`kode_divisi`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table sistem_kenaikan_golongan_bri.divisi: ~10 rows (approximately)
REPLACE INTO `divisi` (`id_divisi`, `kode_divisi`, `nama_divisi`, `deskripsi`, `is_active`, `created_at`, `updated_at`) VALUES
	(1, 'OPR', 'Operasional', 'Divisi yang menangani operasional harian kantor', 1, '2026-04-17 10:59:22', '2026-04-17 10:59:22'),
	(2, 'MKT', 'Marketing', 'Divisi pemasaran dan pengembangan bisnis', 1, '2026-04-17 10:59:22', '2026-04-17 10:59:22'),
	(3, 'KRD', 'Kredit', 'Divisi pengelolaan kredit dan analisis risiko', 1, '2026-04-17 10:59:22', '2026-04-17 10:59:22'),
	(4, 'IT', 'Information Technology', 'Divisi teknologi informasi dan sistem', 1, '2026-04-17 10:59:22', '2026-04-17 10:59:22'),
	(5, 'HC', 'Human Capital', 'Divisi pengelolaan sumber daya manusia', 1, '2026-04-17 10:59:22', '2026-04-17 10:59:22'),
	(6, 'FIN', 'Finance', 'Divisi keuangan dan akuntansi', 1, '2026-04-17 10:59:22', '2026-04-17 10:59:22'),
	(7, 'RMG', 'Risk Management', 'Divisi manajemen risiko', 1, '2026-04-17 10:59:22', '2026-04-17 10:59:22'),
	(8, 'CS', 'Customer Service', 'Divisi layanan pelanggan', 1, '2026-04-17 10:59:22', '2026-04-17 10:59:22'),
	(9, 'ADM', 'Administration', 'Divisi administrasi umum', 1, '2026-04-17 10:59:22', '2026-04-17 10:59:22'),
	(10, 'AUD', 'Internal Audit', 'Divisi audit internal', 1, '2026-04-17 10:59:22', '2026-04-17 10:59:22');

-- Dumping structure for table sistem_kenaikan_golongan_bri.dokumen_pengajuan
CREATE TABLE IF NOT EXISTS `dokumen_pengajuan` (
  `id_dokumen` int NOT NULL AUTO_INCREMENT,
  `id_pengajuan` int NOT NULL,
  `jenis_dokumen` enum('surat_permohonan','penilaian_kinerja','sertifikat','lainnya') COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_dokumen` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_size` int NOT NULL,
  `mime_type` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `keterangan` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `uploaded_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_dokumen`),
  KEY `id_pengajuan` (`id_pengajuan`),
  CONSTRAINT `dokumen_pengajuan_ibfk_1` FOREIGN KEY (`id_pengajuan`) REFERENCES `pengajuan` (`id_pengajuan`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table sistem_kenaikan_golongan_bri.dokumen_pengajuan: ~10 rows (approximately)
REPLACE INTO `dokumen_pengajuan` (`id_dokumen`, `id_pengajuan`, `jenis_dokumen`, `nama_dokumen`, `file_path`, `file_size`, `mime_type`, `keterangan`, `uploaded_at`) VALUES
	(29, 30, 'surat_permohonan', 'TEST_Surat_Permohonan_Ahmad.pdf', '/uploads/documents/test/ahmad/surat_permohonan.pdf', 256789, 'application/pdf', 'Surat permohonan kenaikan golongan - Testing', '2026-05-02 17:00:00'),
	(30, 30, 'penilaian_kinerja', 'TEST_Penilaian_Kinerja_Ahmad_2021-2024.pdf', '/uploads/documents/test/ahmad/penilaian_kinerja.pdf', 423456, 'application/pdf', 'Hasil penilaian kinerja 3 tahun terakhir - Testing', '2026-05-02 17:00:00'),
	(31, 30, 'sertifikat', 'TEST_Sertifikat_CS_Excellence_Ahmad.pdf', '/uploads/documents/test/ahmad/sertifikat.pdf', 534567, 'application/pdf', 'Sertifikat Customer Service Excellence - Testing', '2026-05-02 17:00:00'),
	(32, 31, 'surat_permohonan', 'TEST_Surat_Permohonan_Siti.pdf', '/uploads/documents/test/siti/surat_permohonan.pdf', 267890, 'application/pdf', 'Surat permohonan kenaikan golongan - Testing', '2026-04-30 17:00:00'),
	(33, 31, 'penilaian_kinerja', 'TEST_Penilaian_Kinerja_Siti_2020-2024.pdf', '/uploads/documents/test/siti/penilaian_kinerja.pdf', 445678, 'application/pdf', 'Hasil penilaian kinerja 4 tahun terakhir - Testing', '2026-04-30 17:00:00'),
	(34, 31, 'sertifikat', 'TEST_Sertifikat_Service_Champion_Siti.pdf', '/uploads/documents/test/siti/sertifikat.pdf', 589012, 'application/pdf', 'Sertifikat Service Champion & Complaint Handling - Testing', '2026-04-30 17:00:00'),
	(35, 31, 'lainnya', 'TEST_Laporan_Peningkatan_Kepuasan_Nasabah.pdf', '/uploads/documents/test/siti/laporan_tambahan.pdf', 678901, 'application/pdf', 'Laporan peningkatan kepuasan nasabah 85% → 95% - Testing', '2026-04-30 17:00:00'),
	(36, 35, 'surat_permohonan', 'stuk_1777801331_69f7187369d37.pdf', 'documents/stuk_1777801331_69f7187369d37.pdf', 209159, 'application/pdf', NULL, '2026-05-03 09:42:11'),
	(37, 35, 'penilaian_kinerja', 'stuk_1777801331_69f718736c507.pdf', 'documents/stuk_1777801331_69f718736c507.pdf', 209159, 'application/pdf', NULL, '2026-05-03 09:42:11'),
	(38, 35, 'sertifikat', 'stuk_1777801331_69f718736da8e.pdf', 'documents/stuk_1777801331_69f718736da8e.pdf', 209159, 'application/pdf', NULL, '2026-05-03 09:42:11');

-- Dumping structure for table sistem_kenaikan_golongan_bri.golongan_jabatan
CREATE TABLE IF NOT EXISTS `golongan_jabatan` (
  `id_golongan` int NOT NULL AUTO_INCREMENT,
  `kode_golongan` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_golongan` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `level` int NOT NULL,
  `sub_level` char(1) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deskripsi` text COLLATE utf8mb4_unicode_ci,
  `syarat_minimal` text COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_golongan`),
  UNIQUE KEY `kode_golongan` (`kode_golongan`),
  KEY `idx_level_sub` (`level`,`sub_level`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table sistem_kenaikan_golongan_bri.golongan_jabatan: ~16 rows (approximately)
REPLACE INTO `golongan_jabatan` (`id_golongan`, `kode_golongan`, `nama_golongan`, `level`, `sub_level`, `deskripsi`, `syarat_minimal`, `is_active`, `created_at`, `updated_at`) VALUES
	(1, 'I-A', 'Golongan I-A (Junior Staff)', 1, 'A', 'Golongan terendah untuk staff junior', 'Pendidikan minimal D3, fresh graduate', 1, '2026-04-17 10:59:22', '2026-04-17 10:59:22'),
	(2, 'I-B', 'Golongan I-B (Junior Staff)', 1, 'B', 'Golongan untuk staff junior dengan pengalaman', 'Minimal 1 tahun pengalaman', 1, '2026-04-17 10:59:22', '2026-04-17 10:59:22'),
	(3, 'I-C', 'Golongan I-C (Junior Staff)', 1, 'C', 'Golongan untuk staff junior senior', 'Minimal 2 tahun pengalaman', 1, '2026-04-17 10:59:22', '2026-04-17 10:59:22'),
	(4, 'I-D', 'Golongan I-D (Junior Staff)', 1, 'D', 'Golongan tertinggi untuk staff junior', 'Minimal 3 tahun pengalaman, nilai kinerja baik', 1, '2026-04-17 10:59:22', '2026-04-17 10:59:22'),
	(5, 'II-A', 'Golongan II-A (Senior Staff)', 2, 'A', 'Golongan senior staff tingkat awal', 'Minimal 4 tahun pengalaman, S1', 1, '2026-04-17 10:59:22', '2026-04-17 10:59:22'),
	(6, 'II-B', 'Golongan II-B (Senior Staff)', 2, 'B', 'Golongan senior staff', 'Minimal 5 tahun pengalaman', 1, '2026-04-17 10:59:22', '2026-04-17 10:59:22'),
	(7, 'II-C', 'Golongan II-C (Senior Staff)', 2, 'C', 'Golongan senior staff tingkat lanjut', 'Minimal 6 tahun pengalaman', 1, '2026-04-17 10:59:22', '2026-04-17 10:59:22'),
	(8, 'II-D', 'Golongan II-D (Senior Staff)', 2, 'D', 'Golongan tertinggi senior staff', 'Minimal 7 tahun pengalaman, nilai kinerja sangat baik', 1, '2026-04-17 10:59:22', '2026-04-17 10:59:22'),
	(9, 'III-A', 'Golongan III-A (Supervisor)', 3, 'A', 'Golongan supervisor tingkat awal', 'Minimal 8 tahun pengalaman, kemampuan leadership', 1, '2026-04-17 10:59:22', '2026-04-17 10:59:22'),
	(10, 'III-B', 'Golongan III-B (Supervisor)', 3, 'B', 'Golongan supervisor', 'Minimal 9 tahun pengalaman', 1, '2026-04-17 10:59:22', '2026-04-17 10:59:22'),
	(11, 'III-C', 'Golongan III-C (Supervisor)', 3, 'C', 'Golongan supervisor tingkat lanjut', 'Minimal 10 tahun pengalaman', 1, '2026-04-17 10:59:22', '2026-04-17 10:59:22'),
	(12, 'III-D', 'Golongan III-D (Supervisor)', 3, 'D', 'Golongan tertinggi supervisor', 'Minimal 11 tahun pengalaman, sertifikasi manajerial', 1, '2026-04-17 10:59:22', '2026-04-17 10:59:22'),
	(13, 'IV-A', 'Golongan IV-A (Manager)', 4, 'A', 'Golongan manager tingkat awal', 'Minimal 12 tahun pengalaman, S2/MBA (preferred)', 1, '2026-04-17 10:59:22', '2026-04-17 10:59:22'),
	(14, 'IV-B', 'Golongan IV-B (Manager)', 4, 'B', 'Golongan manager', 'Minimal 13 tahun pengalaman', 1, '2026-04-17 10:59:22', '2026-04-17 10:59:22'),
	(15, 'IV-C', 'Golongan IV-C (Manager)', 4, 'C', 'Golongan manager senior', 'Minimal 14 tahun pengalaman', 1, '2026-04-17 10:59:22', '2026-04-17 10:59:22'),
	(16, 'IV-D', 'Golongan IV-D (Manager)', 4, 'D', 'Golongan tertinggi manager', 'Minimal 15 tahun pengalaman, track record sangat baik', 1, '2026-04-17 10:59:22', '2026-04-17 10:59:22'),
	(17, 'I-TESTs', 'Tester Manual', 1, 'A', 'TEsting', 'Syarat 2 tahun kerja', 1, '2026-05-03 06:53:55', '2026-05-03 06:54:03');

-- Dumping structure for table sistem_kenaikan_golongan_bri.jabatan
CREATE TABLE IF NOT EXISTS `jabatan` (
  `id_jabatan` int NOT NULL AUTO_INCREMENT,
  `kode_jabatan` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_jabatan` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_golongan_minimal` int DEFAULT NULL,
  `deskripsi` text COLLATE utf8mb4_unicode_ci,
  `is_active` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_jabatan`),
  UNIQUE KEY `kode_jabatan` (`kode_jabatan`),
  KEY `id_golongan_minimal` (`id_golongan_minimal`),
  CONSTRAINT `jabatan_ibfk_1` FOREIGN KEY (`id_golongan_minimal`) REFERENCES `golongan_jabatan` (`id_golongan`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table sistem_kenaikan_golongan_bri.jabatan: ~19 rows (approximately)
REPLACE INTO `jabatan` (`id_jabatan`, `kode_jabatan`, `nama_jabatan`, `id_golongan_minimal`, `deskripsi`, `is_active`, `created_at`, `updated_at`) VALUES
	(1, 'STF-CS', 'Customer Service', 1, 'Melayani nasabah di front office', 1, '2026-04-17 10:59:22', '2026-04-17 10:59:22'),
	(2, 'STF-TLR', 'Teller', 1, 'Menangani transaksi keuangan', 1, '2026-04-17 10:59:22', '2026-04-17 10:59:22'),
	(3, 'STF-ADM', 'Staff Administrasi', 1, 'Menangani administrasi dokumen', 1, '2026-04-17 10:59:22', '2026-04-17 10:59:22'),
	(4, 'STF-MKT', 'Marketing Staff', 1, 'Melakukan pemasaran produk', 1, '2026-04-17 10:59:22', '2026-04-17 10:59:22'),
	(5, 'STF-IT', 'IT Support', 1, 'Memberikan dukungan teknis IT', 1, '2026-04-17 10:59:22', '2026-04-17 10:59:22'),
	(6, 'SS-ACC', 'Account Officer', 5, 'Mengelola portfolio nasabah', 1, '2026-04-17 10:59:22', '2026-04-17 10:59:22'),
	(7, 'SS-KRD', 'Credit Analyst', 5, 'Menganalisis permohonan kredit', 1, '2026-04-17 10:59:22', '2026-04-17 10:59:22'),
	(8, 'SS-FIN', 'Finance Staff', 5, 'Mengelola laporan keuangan', 1, '2026-04-17 10:59:22', '2026-04-17 10:59:22'),
	(9, 'SS-HR', 'HR Staff', 5, 'Mengelola administrasi SDM', 1, '2026-04-17 10:59:22', '2026-04-17 10:59:22'),
	(10, 'SPV-OPR', 'Supervisor Operasional', 9, 'Mengawasi operasional harian', 1, '2026-04-17 10:59:22', '2026-04-17 10:59:22'),
	(11, 'SPV-MKT', 'Supervisor Marketing', 9, 'Mengawasi tim marketing', 1, '2026-04-17 10:59:22', '2026-04-17 10:59:22'),
	(12, 'SPV-CS', 'Supervisor Customer Service', 9, 'Mengawasi layanan nasabah', 1, '2026-04-17 10:59:22', '2026-04-17 10:59:22'),
	(13, 'SPV-IT', 'Supervisor IT', 9, 'Mengawasi sistem dan infrastruktur IT', 1, '2026-04-17 10:59:22', '2026-04-17 10:59:22'),
	(14, 'MGR-OPR', 'Manager Operasional', 13, 'Mengelola divisi operasional', 1, '2026-04-17 10:59:22', '2026-04-17 10:59:22'),
	(15, 'MGR-MKT', 'Manager Marketing', 13, 'Mengelola divisi marketing', 1, '2026-04-17 10:59:22', '2026-04-17 10:59:22'),
	(16, 'MGR-KRD', 'Manager Kredit', 13, 'Mengelola divisi kredit', 1, '2026-04-17 10:59:22', '2026-04-17 10:59:22'),
	(17, 'MGR-HC', 'Manager Human Capital', 13, 'Mengelola divisi HC', 1, '2026-04-17 10:59:22', '2026-04-17 10:59:22'),
	(18, 'MGR-IT', 'Manager IT', 13, 'Mengelola divisi IT', 1, '2026-04-17 10:59:22', '2026-04-17 10:59:22'),
	(19, 'KW', 'Kepala Wilayah', 15, 'Memimpin seluruh operasional wilayah', 1, '2026-04-17 10:59:22', '2026-04-17 10:59:22'),
	(20, 'SHM', 'Section Head Microservices', 17, 'test', 1, '2026-05-03 06:55:02', '2026-05-03 06:55:02');

-- Dumping structure for table sistem_kenaikan_golongan_bri.log_aktivitas
CREATE TABLE IF NOT EXISTS `log_aktivitas` (
  `id_log` int NOT NULL AUTO_INCREMENT,
  `id_user` int DEFAULT NULL,
  `aktivitas` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `modul` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_log`),
  KEY `idx_user` (`id_user`),
  KEY `idx_modul` (`modul`),
  KEY `idx_created` (`created_at`),
  CONSTRAINT `log_aktivitas_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=105 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table sistem_kenaikan_golongan_bri.log_aktivitas: ~12 rows (approximately)
REPLACE INTO `log_aktivitas` (`id_log`, `id_user`, `aktivitas`, `modul`, `ip_address`, `user_agent`, `created_at`) VALUES
	(30, 22, 'Membuat pengajuan kenaikan golongan TEST-PG-001', 'pengajuan', '192.168.1.100', 'Mozilla/5.0 (Testing Browser)', '2026-05-02 17:00:00'),
	(31, 23, 'Membuat pengajuan kenaikan golongan TEST-PG-002', 'pengajuan', '192.168.1.101', 'Mozilla/5.0 (Testing Browser)', '2026-04-30 17:00:00'),
	(32, 21, 'Menyetujui pengajuan TEST-PG-002 dari Siti Pekerja Testing 2', 'approval', '192.168.1.102', 'Mozilla/5.0 (Testing Browser)', '2026-05-01 17:00:00'),
	(33, 22, 'Login ke sistem', 'auth', '192.168.1.100', 'Mozilla/5.0 (Testing Browser)', '2026-05-02 17:00:00'),
	(34, 21, 'Login ke sistem', 'auth', '192.168.1.102', 'Mozilla/5.0 (Testing Browser)', '2026-05-02 17:00:00'),
	(35, 21, 'Login ke sistem', 'auth', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-03 08:43:59'),
	(36, 21, 'Pengajuan #30 disetujui', 'approval', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-03 08:44:39'),
	(37, 2, 'Login ke sistem', 'auth', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-03 08:55:56'),
	(38, 2, 'Logout dari sistem', 'auth', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-03 08:56:13'),
	(39, 1, 'Login ke sistem', 'auth', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-03 08:56:17'),
	(40, 1, 'Menambah pekerja: Verel Prawira', 'pekerja', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-03 08:58:14'),
	(41, 1, 'Logout dari sistem', 'auth', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-03 08:58:24'),
	(42, 21, 'Logout dari sistem', 'auth', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-03 09:07:26'),
	(43, 1, 'Login ke sistem', 'auth', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-03 09:07:31'),
	(44, 1, 'Logout dari sistem', 'auth', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-03 09:08:39'),
	(45, 21, 'Login ke sistem', 'auth', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-03 09:09:14'),
	(46, 1, 'Login ke sistem', 'auth', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-03 09:10:04'),
	(47, 1, 'Menambah pekerja dan akun user: rifaldi adrian', 'pekerja', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-03 09:11:07'),
	(48, 1, 'Logout dari sistem', 'auth', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-03 09:11:15'),
	(49, 24, 'Login ke sistem', 'auth', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-03 09:12:05'),
	(50, 24, 'Membuat pengajuan kenaikan golongan', 'pengajuan', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-03 09:12:25'),
	(51, 24, 'Logout dari sistem', 'auth', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-03 09:12:39'),
	(52, 21, 'Login ke sistem', 'auth', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-03 09:12:50'),
	(53, 21, 'Pengajuan #32 disetujui', 'approval', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-03 09:13:10'),
	(54, 21, 'Logout dari sistem', 'auth', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-03 09:13:27'),
	(55, 3, 'Login ke sistem', 'auth', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-03 09:13:36'),
	(56, 3, 'Pengajuan #32 disetujui', 'approval', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-03 09:13:55'),
	(57, 3, 'Pengajuan #31 ditolak', 'approval', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-03 09:14:28'),
	(58, 3, 'Pengajuan #30 disetujui', 'approval', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-03 09:14:38'),
	(59, 3, 'Logout dari sistem', 'auth', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-03 09:14:43'),
	(60, 2, 'Login ke sistem', 'auth', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-03 09:14:50'),
	(61, 2, 'Pengajuan #30 disetujui', 'approval', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-03 09:15:30'),
	(62, 2, 'Pengajuan #32 disetujui', 'approval', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-03 09:16:29'),
	(63, 2, 'Logout dari sistem', 'auth', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-03 09:22:08'),
	(64, 2, 'Login ke sistem', 'auth', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-03 09:22:25'),
	(65, 2, 'Logout dari sistem', 'auth', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-03 09:22:33'),
	(66, 24, 'Login ke sistem', 'auth', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-03 09:22:38'),
	(67, 24, 'Membuat pengajuan kenaikan golongan', 'pengajuan', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-03 09:23:05'),
	(68, 24, 'Logout dari sistem', 'auth', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-03 09:24:05'),
	(69, 3, 'Login ke sistem', 'auth', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-03 09:24:17'),
	(70, 3, 'Logout dari sistem', 'auth', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-03 09:24:21'),
	(71, 2, 'Login ke sistem', 'auth', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-03 09:24:27'),
	(72, 2, 'Logout dari sistem', 'auth', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-03 09:24:56'),
	(73, 1, 'Login ke sistem', 'auth', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-03 09:24:59'),
	(74, 1, 'Logout dari sistem', 'auth', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-03 09:26:53'),
	(75, 2, 'Login ke sistem', 'auth', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-03 09:27:08'),
	(76, 21, 'Logout dari sistem', 'auth', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-03 09:32:18'),
	(77, 1, 'Login ke sistem', 'auth', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-03 09:32:21'),
	(78, 1, 'Logout dari sistem', 'auth', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-03 09:34:16'),
	(79, 2, 'Logout dari sistem', 'auth', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-03 09:34:21'),
	(80, 24, 'Login ke sistem', 'auth', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-03 09:34:27'),
	(81, 24, 'Logout dari sistem', 'auth', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-03 09:34:32'),
	(82, 21, 'Login ke sistem', 'auth', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-03 09:34:39'),
	(83, 21, 'Pengajuan #33 ditolak', 'approval', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-03 09:34:45'),
	(84, 24, 'Login ke sistem', 'auth', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-03 09:35:35'),
	(85, 24, 'Membuat pengajuan kenaikan golongan', 'pengajuan', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-03 09:36:46'),
	(86, 21, 'Pengajuan #34 ditolak', 'approval', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-03 09:37:33'),
	(87, 24, 'Membuat pengajuan kenaikan golongan', 'pengajuan', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-03 09:42:11'),
	(88, 24, 'Melihat dokumen: stuk_1777801331_69f7187369d37.pdf', 'dokumen', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-03 09:45:47'),
	(89, 24, 'Mengunduh dokumen: stuk_1777801331_69f7187369d37.pdf', 'dokumen', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-03 09:45:49'),
	(90, 21, 'Melihat dokumen: stuk_1777801331_69f7187369d37.pdf', 'dokumen', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-03 09:45:59'),
	(91, 21, 'Melihat dokumen: stuk_1777801331_69f7187369d37.pdf', 'dokumen', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-03 09:46:01'),
	(92, 21, 'Pengajuan #35 disetujui', 'approval', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-03 09:46:08'),
	(93, 21, 'Logout dari sistem', 'auth', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-03 09:46:26'),
	(94, 3, 'Login ke sistem', 'auth', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-03 09:46:33'),
	(95, 3, 'Melihat dokumen: stuk_1777801331_69f7187369d37.pdf', 'dokumen', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-03 09:46:37'),
	(96, 3, 'Pengajuan #35 disetujui', 'approval', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-03 09:46:47'),
	(97, 3, 'Logout dari sistem', 'auth', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-03 09:47:13'),
	(98, 2, 'Login ke sistem', 'auth', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-03 09:47:18'),
	(99, 2, 'Melihat dokumen: stuk_1777801331_69f7187369d37.pdf', 'dokumen', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-03 09:47:27'),
	(100, 2, 'Pengajuan #35 disetujui', 'approval', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-03 09:48:04'),
	(101, 24, 'Logout dari sistem', 'auth', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-03 09:48:37'),
	(102, 1, 'Login ke sistem', 'auth', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-03 09:50:07'),
	(103, 2, 'Logout dari sistem', 'auth', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-03 09:53:28'),
	(104, 1, 'Login ke sistem', 'auth', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/147.0.0.0 Safari/537.36', '2026-05-03 09:53:30');

-- Dumping structure for table sistem_kenaikan_golongan_bri.notifikasi
CREATE TABLE IF NOT EXISTS `notifikasi` (
  `id_notifikasi` int NOT NULL AUTO_INCREMENT,
  `id_user` int NOT NULL,
  `judul` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pesan` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `link` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_read` tinyint(1) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_notifikasi`),
  KEY `idx_user_read` (`id_user`,`is_read`),
  KEY `idx_created` (`created_at`),
  CONSTRAINT `notifikasi_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table sistem_kenaikan_golongan_bri.notifikasi: ~4 rows (approximately)
REPLACE INTO `notifikasi` (`id_notifikasi`, `id_user`, `judul`, `pesan`, `link`, `is_read`, `created_at`) VALUES
	(9, 22, 'Pengajuan Berhasil Dibuat', 'Pengajuan kenaikan golongan Anda dengan nomor TEST-PG-001 telah berhasil dibuat dan sedang menunggu review atasan langsung (Bambang Supervisor Testing). [DATA TESTING]', '/pengajuan/detail/30', 0, '2026-05-02 17:00:00'),
	(10, 21, 'Pengajuan Perlu Review', 'Ada pengajuan baru dari Ahmad Pekerja Testing 1 (TEST-PG-001) yang memerlukan persetujuan Anda. [DATA TESTING]', '/approval/review/30', 0, '2026-05-02 17:00:00'),
	(11, 23, 'Pengajuan Disetujui Atasan', 'Pengajuan kenaikan golongan Anda (TEST-PG-002) telah disetujui oleh atasan langsung (Bambang Supervisor Testing) dan sedang menunggu review Manager. [DATA TESTING]', '/pengajuan/detail/31', 1, '2026-05-01 17:00:00'),
	(12, 3, 'Pengajuan Perlu Review (Manager)', 'Pengajuan dari Siti Pekerja Testing 2 (TEST-PG-002) telah disetujui atasan dan memerlukan review Anda sebagai Manager. [DATA TESTING]', '/approval/review/31', 0, '2026-05-01 17:00:00');

-- Dumping structure for table sistem_kenaikan_golongan_bri.pekerja
CREATE TABLE IF NOT EXISTS `pekerja` (
  `id_pekerja` int NOT NULL AUTO_INCREMENT,
  `nip` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_lengkap` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tempat_lahir` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `jenis_kelamin` enum('L','P') COLLATE utf8mb4_unicode_ci NOT NULL,
  `alamat` text COLLATE utf8mb4_unicode_ci,
  `no_telepon` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_divisi` int NOT NULL,
  `id_jabatan` int NOT NULL,
  `id_golongan_saat_ini` int NOT NULL,
  `tanggal_bergabung` date NOT NULL,
  `tanggal_golongan_terakhir` date DEFAULT NULL,
  `id_atasan` int DEFAULT NULL,
  `nilai_kinerja_terakhir` decimal(5,2) DEFAULT NULL,
  `foto` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status_kepegawaian` enum('aktif','cuti','nonaktif') COLLATE utf8mb4_unicode_ci DEFAULT 'aktif',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_pekerja`),
  UNIQUE KEY `nip` (`nip`),
  UNIQUE KEY `email` (`email`),
  KEY `id_jabatan` (`id_jabatan`),
  KEY `id_golongan_saat_ini` (`id_golongan_saat_ini`),
  KEY `id_atasan` (`id_atasan`),
  KEY `idx_divisi_status` (`id_divisi`,`status_kepegawaian`),
  CONSTRAINT `pekerja_ibfk_1` FOREIGN KEY (`id_divisi`) REFERENCES `divisi` (`id_divisi`),
  CONSTRAINT `pekerja_ibfk_2` FOREIGN KEY (`id_jabatan`) REFERENCES `jabatan` (`id_jabatan`),
  CONSTRAINT `pekerja_ibfk_3` FOREIGN KEY (`id_golongan_saat_ini`) REFERENCES `golongan_jabatan` (`id_golongan`),
  CONSTRAINT `pekerja_ibfk_4` FOREIGN KEY (`id_atasan`) REFERENCES `pekerja` (`id_pekerja`)
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table sistem_kenaikan_golongan_bri.pekerja: ~23 rows (approximately)
REPLACE INTO `pekerja` (`id_pekerja`, `nip`, `nama_lengkap`, `tempat_lahir`, `tanggal_lahir`, `jenis_kelamin`, `alamat`, `no_telepon`, `email`, `id_divisi`, `id_jabatan`, `id_golongan_saat_ini`, `tanggal_bergabung`, `tanggal_golongan_terakhir`, `id_atasan`, `nilai_kinerja_terakhir`, `foto`, `status_kepegawaian`, `created_at`, `updated_at`) VALUES
	(13, '19900001', 'Dr. Budi Santoso, MBA', 'Padang', '1975-01-15', 'L', 'Jl. Veteran No. 123, Padang', '081234567001', 'budi.santoso@bri.co.id', 1, 19, 16, '2000-01-10', '2020-01-10', NULL, 95.00, NULL, 'aktif', '2026-04-17 11:05:08', '2026-04-17 11:05:08'),
	(14, '20050002', 'Ir. Siti Rahmawati, M.M.', 'Bukittinggi', '1980-03-20', 'P', 'Jl. Sudirman No. 45, Padang', '081234567002', 'siti.rahmawati@bri.co.id', 1, 14, 14, '2005-06-15', '2018-06-15', 1, 92.50, NULL, 'aktif', '2026-04-17 11:05:17', '2026-04-17 11:05:17'),
	(15, '20060003', 'Ahmad Yani, S.E., M.M.', 'Padang', '1982-05-10', 'L', 'Jl. Pemuda No. 78, Padang', '081234567003', 'ahmad.yani@bri.co.id', 2, 15, 14, '2006-08-20', '2019-08-20', 1, 90.00, NULL, 'aktif', '2026-04-17 11:05:17', '2026-04-17 11:05:17'),
	(16, '20070004', 'Dewi Sartika, S.E.', 'Solok', '1983-07-25', 'P', 'Jl. Ahmad Yani No. 90, Padang', '081234567004', 'dewi.sartika@bri.co.id', 3, 16, 13, '2007-09-10', '2020-09-10', 1, 88.75, NULL, 'aktif', '2026-04-17 11:05:17', '2026-04-17 11:05:17'),
	(17, '20080005', 'Hendri Prasetyo, S.Kom., M.T.', 'Padang', '1984-11-30', 'L', 'Jl. Gajah Mada No. 12, Padang', '081234567005', 'hendri.prasetyo@bri.co.id', 4, 18, 13, '2008-10-15', '2021-10-15', 1, 91.25, NULL, 'aktif', '2026-04-17 11:05:17', '2026-04-17 11:05:17'),
	(18, '20120006', 'Rina Marlina, S.E.', 'Payakumbuh', '1988-02-14', 'P', 'Jl. Diponegoro No. 34, Padang', '081234567006', 'rina.marlina@bri.co.id', 1, 10, 10, '2012-03-20', '2020-03-20', 2, 87.50, NULL, 'aktif', '2026-04-17 11:05:25', '2026-04-17 11:05:25'),
	(19, '20130007', 'Fadli Rahman, S.Sos.', 'Padang', '1989-04-18', 'L', 'Jl. Proklamasi No. 56, Padang', '081234567007', 'fadli.rahman@bri.co.id', 2, 11, 10, '2013-05-10', '2021-05-10', 3, 86.00, NULL, 'aktif', '2026-04-17 11:05:25', '2026-04-17 11:05:25'),
	(20, '20140008', 'Maya Sari, S.E.', 'Bukittinggi', '1990-06-22', 'P', 'Jl. Khatib Sulaiman No. 67, Padang', '081234567008', 'maya.sari@bri.co.id', 8, 12, 9, '2014-07-15', '2022-07-15', 2, 85.50, NULL, 'aktif', '2026-04-17 11:05:25', '2026-04-17 11:05:25'),
	(21, '20150009', 'Dedi Kurniawan, S.Kom.', 'Padang', '1991-08-26', 'L', 'Jl. S. Parman No. 89, Padang', '081234567009', 'dedi.kurniawan@bri.co.id', 4, 13, 9, '2015-09-20', '2021-09-20', 5, 88.00, NULL, 'aktif', '2026-04-17 11:05:25', '2026-04-17 11:05:25'),
	(22, '20180010', 'Lusi Handayani, S.E.', 'Solok', '1993-01-12', 'P', 'Jl. Hayam Wuruk No. 23, Padang', '081234567010', 'lusi.handayani@bri.co.id', 2, 6, 7, '2018-02-15', '2022-02-15', 7, 84.00, NULL, 'aktif', '2026-04-17 11:05:35', '2026-04-17 11:05:35'),
	(23, '20180011', 'Eko Prasetyo, S.E.', 'Padang', '1993-03-16', 'L', 'Jl. Supomo No. 45, Padang', '081234567011', 'eko.prasetyo@bri.co.id', 3, 7, 7, '2018-04-20', '2022-04-20', 4, 85.75, NULL, 'aktif', '2026-04-17 11:05:35', '2026-04-17 11:05:35'),
	(24, '20190012', 'Putri Utami, S.E.', 'Payakumbuh', '1994-05-20', 'P', 'Jl. Cendana No. 67, Padang', '081234567012', 'putri.utami@bri.co.id', 6, 8, 6, '2019-06-10', '2021-06-10', 2, 83.50, NULL, 'aktif', '2026-04-17 11:05:35', '2026-04-17 11:05:35'),
	(25, '20190013', 'Rudi Hartono, S.Kom.', 'Padang', '1994-07-24', 'L', 'Jl. Flamboyan No. 89, Padang', '081234567013', 'rudi.hartono@bri.co.id', 4, 5, 6, '2019-08-15', '2021-08-15', 9, 84.25, NULL, 'aktif', '2026-04-17 11:05:35', '2026-04-17 11:05:35'),
	(26, '20210014', 'Sari Dewi, S.E.', 'Bukittinggi', '1996-09-28', 'P', 'Jl. Melati No. 12, Padang', '081234567014', 'sari.dewi@bri.co.id', 8, 1, 3, '2021-01-10', '2022-01-10', 8, 82.00, NULL, 'aktif', '2026-04-17 11:05:41', '2026-04-17 11:05:41'),
	(27, '20210015', 'Andi Wijaya, S.Kom.', 'Padang', '1996-11-02', 'L', 'Jl. Mawar No. 34, Padang', '081234567015', 'andi.wijaya@bri.co.id', 4, 5, 3, '2021-03-15', '2022-03-15', 9, 83.00, NULL, 'aktif', '2026-04-17 11:05:41', '2026-04-17 11:05:41'),
	(28, '20220016', 'Nia Kurnia, S.E.', 'Padang', '1997-01-06', 'P', 'Jl. Anggrek No. 56, Padang', '081234567016', 'nia.kurnia@bri.co.id', 8, 1, 2, '2022-05-20', '2023-05-20', 8, 81.50, NULL, 'aktif', '2026-04-17 11:05:41', '2026-04-17 11:05:41'),
	(29, '20220017', 'Budi Setiawan, S.E.', 'Solok', '1997-03-10', 'L', 'Jl. Kenanga No. 78, Padang', '081234567017', 'budi.setiawan@bri.co.id', 1, 3, 2, '2022-07-25', '2023-07-25', 6, 80.75, NULL, 'aktif', '2026-04-17 11:05:41', '2026-04-17 11:05:41'),
	(30, '20230018', 'Fitri Ramadhani, D3', 'Payakumbuh', '1998-05-14', 'P', 'Jl. Dahlia No. 90, Padang', '081234567018', 'fitri.ramadhani@bri.co.id', 8, 2, 1, '2023-09-01', '2023-09-01', 8, 80.00, NULL, 'aktif', '2026-04-17 11:05:41', '2026-04-17 11:05:41'),
	(31, '20230019', 'Rio Pratama, D3', 'Padang', '1998-07-18', 'L', 'Jl. Tulip No. 11, Padang', '081234567019', 'rio.pratama@bri.co.id', 2, 4, 1, '2023-11-05', '2023-11-05', 7, 81.25, NULL, 'aktif', '2026-04-17 11:05:41', '2026-04-17 11:05:41'),
	(32, 'TEST-SPV-001', 'Bambang Supervisor Testing', 'Padang', '1990-05-15', 'L', 'Jl. Testing Supervisor No. 1, Padang', '081299999001', 'bambang.test@bri.co.id', 8, 12, 9, '2015-01-10', '2020-01-10', 2, 87.50, NULL, 'aktif', '2026-05-03 08:25:02', '2026-05-03 08:25:02'),
	(33, 'TEST-PKJ-001', 'Ahmad Pekerja Testing 1', 'Bukittinggi', '1995-03-20', 'L', 'Jl. Testing No. 10, Padang', '081299999010', 'ahmad.test1@bri.co.id', 8, 1, 3, '2021-06-01', NULL, 32, 85.00, NULL, 'aktif', '2026-05-03 08:25:02', '2026-05-03 09:15:30'),
	(34, 'TEST-PKJ-002', 'Siti Pekerja Testing 2', 'Padang', '1996-07-12', 'P', 'Jl. Testing No. 11, Padang', '081299999011', 'siti.test2@bri.co.id', 8, 1, 3, '2020-09-15', '2022-09-15', 32, 88.00, NULL, 'aktif', '2026-05-03 08:25:02', '2026-05-03 08:25:02'),
	(35, '131231', 'Verel Prawira', NULL, '2026-05-03', 'L', 'Jl. Kamp Kalawi Padang', '081312312312', 'verel@gmail.com', 4, 5, 1, '2025-01-02', NULL, 32, 80.00, NULL, 'aktif', '2026-05-03 08:58:14', '2026-05-03 08:58:14'),
	(36, '131313', 'rifaldi adrian', NULL, '2009-06-10', 'L', 'Jl. Kamp Kalawi Padang', '081374988071', 'rifaldiadrian26@gmail.com', 4, 13, 11, '2024-02-02', NULL, 32, 85.00, NULL, 'aktif', '2026-05-03 09:11:07', '2026-05-03 09:48:04');

-- Dumping structure for table sistem_kenaikan_golongan_bri.pengajuan
CREATE TABLE IF NOT EXISTS `pengajuan` (
  `id_pengajuan` int NOT NULL AUTO_INCREMENT,
  `nomor_pengajuan` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_pekerja` int NOT NULL,
  `id_golongan_saat_ini` int NOT NULL,
  `id_golongan_diajukan` int NOT NULL,
  `tanggal_pengajuan` date NOT NULL,
  `alasan_pengajuan` text COLLATE utf8mb4_unicode_ci NOT NULL,
	`nilai_kinerja_pengajuan` decimal(5,2) DEFAULT NULL,
  `status` enum('pending','disetujui_atasan','disetujui_manager','disetujui','ditolak_atasan','ditolak_manager','ditolak_kepala_wilayah','dibatalkan') COLLATE utf8mb4_unicode_ci DEFAULT 'pending',
  `catatan_pemohon` text COLLATE utf8mb4_unicode_ci,
  `tanggal_efektif` date DEFAULT NULL,
  `nomor_sk` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `file_sk` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_pengajuan`),
  UNIQUE KEY `nomor_pengajuan` (`nomor_pengajuan`),
  KEY `id_pekerja` (`id_pekerja`),
  KEY `id_golongan_saat_ini` (`id_golongan_saat_ini`),
  KEY `id_golongan_diajukan` (`id_golongan_diajukan`),
  CONSTRAINT `pengajuan_ibfk_1` FOREIGN KEY (`id_pekerja`) REFERENCES `pekerja` (`id_pekerja`),
  CONSTRAINT `pengajuan_ibfk_2` FOREIGN KEY (`id_golongan_saat_ini`) REFERENCES `golongan_jabatan` (`id_golongan`),
  CONSTRAINT `pengajuan_ibfk_3` FOREIGN KEY (`id_golongan_diajukan`) REFERENCES `golongan_jabatan` (`id_golongan`)
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table sistem_kenaikan_golongan_bri.pengajuan: ~2 rows (approximately)
REPLACE INTO `pengajuan` (`id_pengajuan`, `nomor_pengajuan`, `id_pekerja`, `id_golongan_saat_ini`, `id_golongan_diajukan`, `tanggal_pengajuan`, `alasan_pengajuan`, `status`, `catatan_pemohon`, `tanggal_efektif`, `nomor_sk`, `file_sk`, `created_at`, `updated_at`) VALUES
	(30, 'PG/2026/05/0001', 33, 2, 3, '2026-05-03', 'Pengajuan testing untuk kenaikan golongan dari I-B ke I-C. Telah bekerja selama 3 tahun dengan kinerja yang konsisten (nilai 85.00). Telah menyelesaikan pelatihan customer service dan mendapat apresiasi dari nasabah.', 'disetujui', 'Melampirkan dokumen: surat permohonan, hasil penilaian kinerja 3 tahun terakhir, dan sertifikat pelatihan.', NULL, NULL, NULL, '2026-05-03 08:26:28', '2026-05-03 09:15:30'),
	(31, 'PG/2026/05/0002', 34, 3, 4, '2026-05-01', 'Pengajuan testing untuk kenaikan golongan dari I-C ke I-D. Telah bekerja selama 4 tahun dengan kinerja sangat baik (nilai 88.00). Berhasil meningkatkan kepuasan nasabah dari 85% menjadi 95% dan mengelola komplain dengan sangat baik.', 'ditolak_manager', 'Melampirkan dokumen lengkap dan rekomendasi dari atasan langsung.', NULL, NULL, NULL, '2026-05-03 08:26:28', '2026-05-03 09:14:28'),
	(32, 'PG/2026/05/0003', 36, 9, 10, '2026-05-03', 'ingin naik gaji bang', 'disetujui', NULL, NULL, NULL, NULL, '2026-05-03 09:12:25', '2026-05-03 09:16:29'),
	(33, 'PG/2026/05/0004', 36, 10, 11, '2026-05-03', 'Saya ingin naik gaji plis', 'ditolak_atasan', NULL, NULL, NULL, NULL, '2026-05-03 09:23:05', '2026-05-03 09:34:45'),
	(34, 'PG/2026/05/0005', 36, 10, 11, '2026-05-03', 'ingin naik gaji', 'ditolak_atasan', NULL, NULL, NULL, NULL, '2026-05-03 09:36:46', '2026-05-03 09:37:33'),
	(35, 'PG/2026/05/0006', 36, 10, 11, '2026-05-03', 'ingin naik jabatan', 'disetujui', NULL, NULL, NULL, NULL, '2026-05-03 09:42:11', '2026-05-03 09:48:04');

-- Dumping structure for procedure sistem_kenaikan_golongan_bri.sp_generate_nomor_pengajuan
DELIMITER //
CREATE PROCEDURE `sp_generate_nomor_pengajuan`(
    IN p_tanggal DATE,
    OUT p_nomor VARCHAR(50)
)
BEGIN
    DECLARE tahun VARCHAR(4);
    DECLARE bulan VARCHAR(2);
    DECLARE counter INT;
    SET tahun = YEAR(p_tanggal);
    SET bulan = LPAD(MONTH(p_tanggal), 2, '0');
    SELECT COALESCE(COUNT(*) + 1, 1) INTO counter FROM pengajuan 
    WHERE YEAR(tanggal_pengajuan) = tahun AND MONTH(tanggal_pengajuan) = MONTH(p_tanggal);
    SET p_nomor = CONCAT('PG/', tahun, '/', bulan, '/', LPAD(counter, 4, '0'));
END//
DELIMITER ;

-- Dumping structure for procedure sistem_kenaikan_golongan_bri.sp_get_pengajuan_for_approval
DELIMITER //
CREATE PROCEDURE `sp_get_pengajuan_for_approval`(
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
END//
DELIMITER ;

-- Dumping structure for procedure sistem_kenaikan_golongan_bri.sp_validasi_kenaikan_golongan
DELIMITER //
CREATE PROCEDURE `sp_validasi_kenaikan_golongan`(
    IN p_id_pekerja INT,
    IN p_id_golongan_diajukan INT,
    OUT p_valid BOOLEAN,
    OUT p_pesan VARCHAR(255)
)
proc_label: BEGIN
    DECLARE v_masa_golongan INT;
    DECLARE v_nilai_kinerja DECIMAL(5,2);
    DECLARE v_pengajuan_aktif INT;
    DECLARE v_level_sekarang INT;
    DECLARE v_level_diajukan INT;
    DECLARE v_sub_sekarang CHAR(1);
    DECLARE v_sub_diajukan CHAR(1);
    
    SET p_valid = TRUE;
    SET p_pesan = 'Memenuhi syarat';
    
    SELECT TIMESTAMPDIFF(YEAR, tanggal_golongan_terakhir, CURDATE()), nilai_kinerja_terakhir
    INTO v_masa_golongan, v_nilai_kinerja
    FROM pekerja WHERE id_pekerja = p_id_pekerja;
    
    IF v_masa_golongan < 2 THEN
        SET p_valid = FALSE;
        SET p_pesan = 'Masa kerja sejak golongan terakhir kurang dari 2 tahun';
        LEAVE proc_label;
    END IF;
    
    IF v_nilai_kinerja < 80 THEN
        SET p_valid = FALSE;
        SET p_pesan = 'Nilai kinerja terakhir kurang dari 80';
        LEAVE proc_label;
    END IF;
    
    SELECT COUNT(*) INTO v_pengajuan_aktif FROM pengajuan
    WHERE id_pekerja = p_id_pekerja AND status NOT IN ('disetujui', 'ditolak_atasan', 'ditolak_manager', 'ditolak_kepala_wilayah', 'dibatalkan');
    
    IF v_pengajuan_aktif > 0 THEN
        SET p_valid = FALSE;
        SET p_pesan = 'Masih ada pengajuan aktif';
        LEAVE proc_label;
    END IF;

    SELECT g1.level, g1.sub_level, g2.level, g2.sub_level
    INTO v_level_sekarang, v_sub_sekarang, v_level_diajukan, v_sub_diajukan
    FROM pekerja p
    JOIN golongan_jabatan g1 ON p.id_golongan_saat_ini = g1.id_golongan
    JOIN golongan_jabatan g2 ON g2.id_golongan = p_id_golongan_diajukan
    WHERE p.id_pekerja = p_id_pekerja;
    
    IF v_level_diajukan - v_level_sekarang > 1 THEN
        SET p_valid = FALSE;
        SET p_pesan = 'Kenaikan golongan maksimal 1 level';
        LEAVE proc_label;
    END IF;
END//
DELIMITER ;

-- Dumping structure for table sistem_kenaikan_golongan_bri.users
CREATE TABLE IF NOT EXISTS `users` (
  `id_user` int NOT NULL AUTO_INCREMENT,
  `username` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` enum('admin','pekerja','atasan','manager','kepala_wilayah') COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_pekerja` int DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT '1',
  `last_login` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_user`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`),
  KEY `id_pekerja` (`id_pekerja`),
  CONSTRAINT `users_ibfk_1` FOREIGN KEY (`id_pekerja`) REFERENCES `pekerja` (`id_pekerja`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table sistem_kenaikan_golongan_bri.users: ~24 rows (approximately)
REPLACE INTO `users` (`id_user`, `username`, `password`, `email`, `role`, `id_pekerja`, `is_active`, `last_login`, `created_at`, `updated_at`) VALUES
	(1, 'admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin@bri.co.id', 'admin', NULL, 1, '2026-05-03 16:53:30', '2026-04-17 11:06:03', '2026-05-03 09:53:30'),
	(2, '19900001', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'budi.santoso@bri.co.id', 'kepala_wilayah', 1, 1, '2026-05-03 16:47:18', '2026-04-17 11:06:03', '2026-05-03 09:47:18'),
	(3, '20050002', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'siti.rahmawati@bri.co.id', 'manager', 2, 1, '2026-05-03 16:46:33', '2026-04-17 11:06:03', '2026-05-03 09:46:33'),
	(4, '20060003', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'ahmad.yani@bri.co.id', 'manager', 3, 1, NULL, '2026-04-17 11:06:03', '2026-04-17 11:06:03'),
	(5, '20070004', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'dewi.sartika@bri.co.id', 'manager', 4, 1, NULL, '2026-04-17 11:06:03', '2026-04-17 11:06:03'),
	(6, '20080005', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'hendri.prasetyo@bri.co.id', 'manager', 5, 1, NULL, '2026-04-17 11:06:03', '2026-04-17 11:06:03'),
	(7, '20120006', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'rina.marlina@bri.co.id', 'atasan', 6, 1, '2026-05-03 14:19:19', '2026-04-17 11:06:03', '2026-05-03 07:19:19'),
	(8, '20130007', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'fadli.rahman@bri.co.id', 'atasan', 7, 1, NULL, '2026-04-17 11:06:03', '2026-04-17 11:06:03'),
	(9, '20140008', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'maya.sari@bri.co.id', 'atasan', 8, 1, '2026-05-03 14:27:29', '2026-04-17 11:06:03', '2026-05-03 07:27:29'),
	(10, '20150009', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'dedi.kurniawan@bri.co.id', 'atasan', 9, 1, NULL, '2026-04-17 11:06:03', '2026-04-17 11:06:03'),
	(11, '20180010', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'lusi.handayani@bri.co.id', 'pekerja', 10, 1, NULL, '2026-04-17 11:06:03', '2026-04-17 11:06:03'),
	(12, '20180011', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'eko.prasetyo@bri.co.id', 'pekerja', 11, 1, NULL, '2026-04-17 11:06:03', '2026-04-17 11:06:03'),
	(13, '20190012', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'putri.utami@bri.co.id', 'pekerja', 12, 1, NULL, '2026-04-17 11:06:03', '2026-04-17 11:06:03'),
	(14, '20190013', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'rudi.hartono@bri.co.id', 'pekerja', 13, 1, NULL, '2026-04-17 11:06:03', '2026-04-17 11:06:03'),
	(15, '20210014', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'sari.dewi@bri.co.id', 'pekerja', 14, 1, '2026-05-03 14:28:05', '2026-04-17 11:06:03', '2026-05-03 07:28:05'),
	(16, '20210015', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'andi.wijaya@bri.co.id', 'pekerja', 15, 1, NULL, '2026-04-17 11:06:03', '2026-04-17 11:06:03'),
	(17, '20220016', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'nia.kurnia@bri.co.id', 'pekerja', 16, 1, NULL, '2026-04-17 11:06:03', '2026-04-17 11:06:03'),
	(18, '20220017', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'budi.setiawan@bri.co.id', 'pekerja', 17, 1, NULL, '2026-04-17 11:06:03', '2026-04-17 11:06:03'),
	(19, '20230018', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'fitri.ramadhani@bri.co.id', 'pekerja', 18, 1, NULL, '2026-04-17 11:06:03', '2026-04-17 11:06:03'),
	(20, '20230019', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'rio.pratama@bri.co.id', 'pekerja', 19, 1, NULL, '2026-04-17 11:06:03', '2026-04-17 11:06:03'),
	(21, 'TEST-SPV-001', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'bambang.test@bri.co.id', 'atasan', 32, 1, '2026-05-03 16:34:39', '2026-05-03 08:26:14', '2026-05-03 09:34:39'),
	(22, 'TEST-PKJ-001', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'ahmad.test1@bri.co.id', 'pekerja', 33, 1, NULL, '2026-05-03 08:26:14', '2026-05-03 08:26:14'),
	(23, 'TEST-PKJ-002', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'siti.test2@bri.co.id', 'pekerja', 34, 1, NULL, '2026-05-03 08:26:14', '2026-05-03 08:26:14'),
	(24, '131313', '$2y$10$6qyBfjXdUpO8txCLkpOxiOaT6SyJrSdevTR7waUMCnfpP.T4sowuC', 'rifaldiadrian26@gmail.com', 'pekerja', 36, 1, '2026-05-03 16:35:35', '2026-05-03 09:11:07', '2026-05-03 09:35:35');

-- Dumping structure for view sistem_kenaikan_golongan_bri.v_pekerja_detail
-- Creating temporary table to overcome VIEW dependency errors
CREATE TABLE `v_pekerja_detail` (
	`id_pekerja` INT NOT NULL,
	`nip` VARCHAR(1) NOT NULL COLLATE 'utf8mb4_unicode_ci',
	`nama_lengkap` VARCHAR(1) NOT NULL COLLATE 'utf8mb4_unicode_ci',
	`tempat_lahir` VARCHAR(1) NULL COLLATE 'utf8mb4_unicode_ci',
	`tanggal_lahir` DATE NULL,
	`jenis_kelamin` ENUM('L','P') NOT NULL COLLATE 'utf8mb4_unicode_ci',
	`alamat` TEXT NULL COLLATE 'utf8mb4_unicode_ci',
	`no_telepon` VARCHAR(1) NULL COLLATE 'utf8mb4_unicode_ci',
	`email` VARCHAR(1) NOT NULL COLLATE 'utf8mb4_unicode_ci',
	`id_divisi` INT NOT NULL,
	`id_jabatan` INT NOT NULL,
	`id_golongan_saat_ini` INT NOT NULL,
	`tanggal_bergabung` DATE NOT NULL,
	`tanggal_golongan_terakhir` DATE NULL,
	`id_atasan` INT NULL,
	`nilai_kinerja_terakhir` DECIMAL(5,2) NULL,
	`foto` VARCHAR(1) NULL COLLATE 'utf8mb4_unicode_ci',
	`status_kepegawaian` ENUM('aktif','cuti','nonaktif') NULL COLLATE 'utf8mb4_unicode_ci',
	`created_at` TIMESTAMP NULL,
	`updated_at` TIMESTAMP NULL,
	`nama_divisi` VARCHAR(1) NULL COLLATE 'utf8mb4_unicode_ci',
	`kode_divisi` VARCHAR(1) NULL COLLATE 'utf8mb4_unicode_ci',
	`nama_jabatan` VARCHAR(1) NULL COLLATE 'utf8mb4_unicode_ci',
	`kode_jabatan` VARCHAR(1) NULL COLLATE 'utf8mb4_unicode_ci',
	`kode_golongan` VARCHAR(1) NULL COLLATE 'utf8mb4_unicode_ci',
	`nama_golongan` VARCHAR(1) NULL COLLATE 'utf8mb4_unicode_ci',
	`level_golongan` INT NULL,
	`sub_level_golongan` CHAR(1) NULL COLLATE 'utf8mb4_unicode_ci',
	`nama_atasan` VARCHAR(1) NULL COLLATE 'utf8mb4_unicode_ci',
	`nip_atasan` VARCHAR(1) NULL COLLATE 'utf8mb4_unicode_ci',
	`masa_kerja_tahun` BIGINT NULL,
	`masa_kerja_bulan` BIGINT NULL,
	`masa_golongan_tahun` BIGINT NULL,
	`masa_golongan_bulan` BIGINT NULL
) ENGINE=MyISAM;

-- Dumping structure for view sistem_kenaikan_golongan_bri.v_pengajuan_detail
-- Creating temporary table to overcome VIEW dependency errors
CREATE TABLE `v_pengajuan_detail` (
	`id_pengajuan` INT NOT NULL,
	`nomor_pengajuan` VARCHAR(1) NOT NULL COLLATE 'utf8mb4_unicode_ci',
	`id_pekerja` INT NOT NULL,
	`id_golongan_saat_ini` INT NOT NULL,
	`id_golongan_diajukan` INT NOT NULL,
	`tanggal_pengajuan` DATE NOT NULL,
	`alasan_pengajuan` TEXT NOT NULL COLLATE 'utf8mb4_unicode_ci',
	`status` ENUM('pending','disetujui_atasan','disetujui_manager','disetujui','ditolak_atasan','ditolak_manager','ditolak_kepala_wilayah','dibatalkan') NULL COLLATE 'utf8mb4_unicode_ci',
	`catatan_pemohon` TEXT NULL COLLATE 'utf8mb4_unicode_ci',
	`tanggal_efektif` DATE NULL,
	`nomor_sk` VARCHAR(1) NULL COLLATE 'utf8mb4_unicode_ci',
	`file_sk` VARCHAR(1) NULL COLLATE 'utf8mb4_unicode_ci',
	`created_at` TIMESTAMP NULL,
	`updated_at` TIMESTAMP NULL,
	`nip` VARCHAR(1) NOT NULL COLLATE 'utf8mb4_unicode_ci',
	`nama_lengkap` VARCHAR(1) NOT NULL COLLATE 'utf8mb4_unicode_ci',
	`email` VARCHAR(1) NOT NULL COLLATE 'utf8mb4_unicode_ci',
	`no_telepon` VARCHAR(1) NULL COLLATE 'utf8mb4_unicode_ci',
	`nama_divisi` VARCHAR(1) NULL COLLATE 'utf8mb4_unicode_ci',
	`kode_divisi` VARCHAR(1) NULL COLLATE 'utf8mb4_unicode_ci',
	`nama_jabatan` VARCHAR(1) NULL COLLATE 'utf8mb4_unicode_ci',
	`golongan_saat_ini` VARCHAR(1) NULL COLLATE 'utf8mb4_unicode_ci',
	`nama_golongan_saat_ini` VARCHAR(1) NULL COLLATE 'utf8mb4_unicode_ci',
	`golongan_diajukan` VARCHAR(1) NULL COLLATE 'utf8mb4_unicode_ci',
	`nama_golongan_diajukan` VARCHAR(1) NULL COLLATE 'utf8mb4_unicode_ci',
	`level_diajukan` INT NULL,
	`nama_atasan` VARCHAR(1) NULL COLLATE 'utf8mb4_unicode_ci',
	`nip_atasan` VARCHAR(1) NULL COLLATE 'utf8mb4_unicode_ci',
	`email_atasan` VARCHAR(1) NULL COLLATE 'utf8mb4_unicode_ci'
) ENGINE=MyISAM;

-- Dumping structure for trigger sistem_kenaikan_golongan_bri.trg_after_update_pengajuan
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';
DELIMITER //
CREATE TRIGGER `trg_after_update_pengajuan` AFTER UPDATE ON `pengajuan` FOR EACH ROW BEGIN
    IF NEW.status = 'disetujui' AND OLD.status != 'disetujui' THEN
        UPDATE pekerja
        SET id_golongan_saat_ini = NEW.id_golongan_diajukan,
            tanggal_golongan_terakhir = NEW.tanggal_efektif
        WHERE id_pekerja = NEW.id_pekerja;
    END IF;
END//
DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;

-- Dumping structure for trigger sistem_kenaikan_golongan_bri.trg_before_insert_pengajuan
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';
DELIMITER //
CREATE TRIGGER `trg_before_insert_pengajuan` BEFORE INSERT ON `pengajuan` FOR EACH ROW BEGIN
    DECLARE v_nomor VARCHAR(50);
    CALL sp_generate_nomor_pengajuan(NEW.tanggal_pengajuan, v_nomor);
    SET NEW.nomor_pengajuan = v_nomor;
END//
DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;

-- Removing temporary table and create final VIEW structure
DROP TABLE IF EXISTS `v_pekerja_detail`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `v_pekerja_detail` AS select `p`.`id_pekerja` AS `id_pekerja`,`p`.`nip` AS `nip`,`p`.`nama_lengkap` AS `nama_lengkap`,`p`.`tempat_lahir` AS `tempat_lahir`,`p`.`tanggal_lahir` AS `tanggal_lahir`,`p`.`jenis_kelamin` AS `jenis_kelamin`,`p`.`alamat` AS `alamat`,`p`.`no_telepon` AS `no_telepon`,`p`.`email` AS `email`,`p`.`id_divisi` AS `id_divisi`,`p`.`id_jabatan` AS `id_jabatan`,`p`.`id_golongan_saat_ini` AS `id_golongan_saat_ini`,`p`.`tanggal_bergabung` AS `tanggal_bergabung`,`p`.`tanggal_golongan_terakhir` AS `tanggal_golongan_terakhir`,`p`.`id_atasan` AS `id_atasan`,`p`.`nilai_kinerja_terakhir` AS `nilai_kinerja_terakhir`,`p`.`foto` AS `foto`,`p`.`status_kepegawaian` AS `status_kepegawaian`,`p`.`created_at` AS `created_at`,`p`.`updated_at` AS `updated_at`,`d`.`nama_divisi` AS `nama_divisi`,`d`.`kode_divisi` AS `kode_divisi`,`j`.`nama_jabatan` AS `nama_jabatan`,`j`.`kode_jabatan` AS `kode_jabatan`,`g`.`kode_golongan` AS `kode_golongan`,`g`.`nama_golongan` AS `nama_golongan`,`g`.`level` AS `level_golongan`,`g`.`sub_level` AS `sub_level_golongan`,`atasan`.`nama_lengkap` AS `nama_atasan`,`atasan`.`nip` AS `nip_atasan`,timestampdiff(YEAR,`p`.`tanggal_bergabung`,curdate()) AS `masa_kerja_tahun`,(timestampdiff(MONTH,`p`.`tanggal_bergabung`,curdate()) % 12) AS `masa_kerja_bulan`,timestampdiff(YEAR,`p`.`tanggal_golongan_terakhir`,curdate()) AS `masa_golongan_tahun`,(timestampdiff(MONTH,`p`.`tanggal_golongan_terakhir`,curdate()) % 12) AS `masa_golongan_bulan` from ((((`pekerja` `p` left join `divisi` `d` on((`p`.`id_divisi` = `d`.`id_divisi`))) left join `jabatan` `j` on((`p`.`id_jabatan` = `j`.`id_jabatan`))) left join `golongan_jabatan` `g` on((`p`.`id_golongan_saat_ini` = `g`.`id_golongan`))) left join `pekerja` `atasan` on((`p`.`id_atasan` = `atasan`.`id_pekerja`)));

-- Removing temporary table and create final VIEW structure
DROP TABLE IF EXISTS `v_pengajuan_detail`;
CREATE ALGORITHM=UNDEFINED SQL SECURITY DEFINER VIEW `v_pengajuan_detail` AS select `pg`.`id_pengajuan` AS `id_pengajuan`,`pg`.`nomor_pengajuan` AS `nomor_pengajuan`,`pg`.`id_pekerja` AS `id_pekerja`,`pg`.`id_golongan_saat_ini` AS `id_golongan_saat_ini`,`pg`.`id_golongan_diajukan` AS `id_golongan_diajukan`,`pg`.`tanggal_pengajuan` AS `tanggal_pengajuan`,`pg`.`alasan_pengajuan` AS `alasan_pengajuan`,`pg`.`status` AS `status`,`pg`.`catatan_pemohon` AS `catatan_pemohon`,`pg`.`tanggal_efektif` AS `tanggal_efektif`,`pg`.`nomor_sk` AS `nomor_sk`,`pg`.`file_sk` AS `file_sk`,`pg`.`created_at` AS `created_at`,`pg`.`updated_at` AS `updated_at`,`p`.`nip` AS `nip`,`p`.`nama_lengkap` AS `nama_lengkap`,`p`.`email` AS `email`,`p`.`no_telepon` AS `no_telepon`,`d`.`nama_divisi` AS `nama_divisi`,`d`.`kode_divisi` AS `kode_divisi`,`j`.`nama_jabatan` AS `nama_jabatan`,`g_saat_ini`.`kode_golongan` AS `golongan_saat_ini`,`g_saat_ini`.`nama_golongan` AS `nama_golongan_saat_ini`,`g_diajukan`.`kode_golongan` AS `golongan_diajukan`,`g_diajukan`.`nama_golongan` AS `nama_golongan_diajukan`,`g_diajukan`.`level` AS `level_diajukan`,`atasan`.`nama_lengkap` AS `nama_atasan`,`atasan`.`nip` AS `nip_atasan`,`atasan`.`email` AS `email_atasan` from ((((((`pengajuan` `pg` join `pekerja` `p` on((`pg`.`id_pekerja` = `p`.`id_pekerja`))) left join `divisi` `d` on((`p`.`id_divisi` = `d`.`id_divisi`))) left join `jabatan` `j` on((`p`.`id_jabatan` = `j`.`id_jabatan`))) left join `golongan_jabatan` `g_saat_ini` on((`pg`.`id_golongan_saat_ini` = `g_saat_ini`.`id_golongan`))) left join `golongan_jabatan` `g_diajukan` on((`pg`.`id_golongan_diajukan` = `g_diajukan`.`id_golongan`))) left join `pekerja` `atasan` on((`p`.`id_atasan` = `atasan`.`id_pekerja`)));

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
