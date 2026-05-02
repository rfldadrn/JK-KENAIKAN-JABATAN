-- ============================================
-- SAMPLE DATA / DUMMY DATA
-- Database: sistem_kenaikan_golongan_bri
-- ============================================

USE sistem_kenaikan_golongan_bri;

-- Disable foreign key checks untuk menghindari error saat insert
SET FOREIGN_KEY_CHECKS = 0;

-- ============================================
-- 1. INSERT DATA GOLONGAN JABATAN
-- ============================================

INSERT INTO golongan_jabatan (kode_golongan, nama_golongan, level, sub_level, deskripsi, syarat_minimal) VALUES
-- Level I
('I-A', 'Golongan I-A (Junior Staff)', 1, 'A', 'Golongan terendah untuk staff junior', 'Pendidikan minimal D3, fresh graduate'),
('I-B', 'Golongan I-B (Junior Staff)', 1, 'B', 'Golongan untuk staff junior dengan pengalaman', 'Minimal 1 tahun pengalaman'),
('I-C', 'Golongan I-C (Junior Staff)', 1, 'C', 'Golongan untuk staff junior senior', 'Minimal 2 tahun pengalaman'),
('I-D', 'Golongan I-D (Junior Staff)', 1, 'D', 'Golongan tertinggi untuk staff junior', 'Minimal 3 tahun pengalaman, nilai kinerja baik'),

-- Level II
('II-A', 'Golongan II-A (Senior Staff)', 2, 'A', 'Golongan senior staff tingkat awal', 'Minimal 4 tahun pengalaman, S1'),
('II-B', 'Golongan II-B (Senior Staff)', 2, 'B', 'Golongan senior staff', 'Minimal 5 tahun pengalaman'),
('II-C', 'Golongan II-C (Senior Staff)', 2, 'C', 'Golongan senior staff tingkat lanjut', 'Minimal 6 tahun pengalaman'),
('II-D', 'Golongan II-D (Senior Staff)', 2, 'D', 'Golongan tertinggi senior staff', 'Minimal 7 tahun pengalaman, nilai kinerja sangat baik'),

-- Level III
('III-A', 'Golongan III-A (Supervisor)', 3, 'A', 'Golongan supervisor tingkat awal', 'Minimal 8 tahun pengalaman, kemampuan leadership'),
('III-B', 'Golongan III-B (Supervisor)', 3, 'B', 'Golongan supervisor', 'Minimal 9 tahun pengalaman'),
('III-C', 'Golongan III-C (Supervisor)', 3, 'C', 'Golongan supervisor tingkat lanjut', 'Minimal 10 tahun pengalaman'),
('III-D', 'Golongan III-D (Supervisor)', 3, 'D', 'Golongan tertinggi supervisor', 'Minimal 11 tahun pengalaman, sertifikasi manajerial'),

-- Level IV
('IV-A', 'Golongan IV-A (Manager)', 4, 'A', 'Golongan manager tingkat awal', 'Minimal 12 tahun pengalaman, S2/MBA (preferred)'),
('IV-B', 'Golongan IV-B (Manager)', 4, 'B', 'Golongan manager', 'Minimal 13 tahun pengalaman'),
('IV-C', 'Golongan IV-C (Manager)', 4, 'C', 'Golongan manager senior', 'Minimal 14 tahun pengalaman'),
('IV-D', 'Golongan IV-D (Manager)', 4, 'D', 'Golongan tertinggi manager', 'Minimal 15 tahun pengalaman, track record sangat baik');

-- ============================================
-- 2. INSERT DATA DIVISI
-- ============================================

INSERT INTO divisi (kode_divisi, nama_divisi, deskripsi) VALUES
('OPR', 'Operasional', 'Divisi yang menangani operasional harian kantor'),
('MKT', 'Marketing', 'Divisi pemasaran dan pengembangan bisnis'),
('KRD', 'Kredit', 'Divisi pengelolaan kredit dan analisis risiko'),
('IT', 'Information Technology', 'Divisi teknologi informasi dan sistem'),
('HC', 'Human Capital', 'Divisi pengelolaan sumber daya manusia'),
('FIN', 'Finance', 'Divisi keuangan dan akuntansi'),
('RMG', 'Risk Management', 'Divisi manajemen risiko'),
('CS', 'Customer Service', 'Divisi layanan pelanggan'),
('ADM', 'Administration', 'Divisi administrasi umum'),
('AUD', 'Internal Audit', 'Divisi audit internal');

-- ============================================
-- 3. INSERT DATA JABATAN
-- ============================================

INSERT INTO jabatan (kode_jabatan, nama_jabatan, id_golongan_minimal, deskripsi) VALUES
-- Staff Level
('STF-CS', 'Customer Service', 1, 'Melayani nasabah di front office'),
('STF-TLR', 'Teller', 1, 'Menangani transaksi keuangan'),
('STF-ADM', 'Staff Administrasi', 1, 'Menangani administrasi dokumen'),
('STF-MKT', 'Marketing Staff', 1, 'Melakukan pemasaran produk'),
('STF-IT', 'IT Support', 1, 'Memberikan dukungan teknis IT'),

-- Senior Staff Level
('SS-ACC', 'Account Officer', 5, 'Mengelola portfolio nasabah'),
('SS-KRD', 'Credit Analyst', 5, 'Menganalisis permohonan kredit'),
('SS-FIN', 'Finance Staff', 5, 'Mengelola laporan keuangan'),
('SS-HR', 'HR Staff', 5, 'Mengelola administrasi SDM'),

-- Supervisor Level
('SPV-OPR', 'Supervisor Operasional', 9, 'Mengawasi operasional harian'),
('SPV-MKT', 'Supervisor Marketing', 9, 'Mengawasi tim marketing'),
('SPV-CS', 'Supervisor Customer Service', 9, 'Mengawasi layanan nasabah'),
('SPV-IT', 'Supervisor IT', 9, 'Mengawasi sistem dan infrastruktur IT'),

-- Manager Level
('MGR-OPR', 'Manager Operasional', 13, 'Mengelola divisi operasional'),
('MGR-MKT', 'Manager Marketing', 13, 'Mengelola divisi marketing'),
('MGR-KRD', 'Manager Kredit', 13, 'Mengelola divisi kredit'),
('MGR-HC', 'Manager Human Capital', 13, 'Mengelola divisi HC'),
('MGR-IT', 'Manager IT', 13, 'Mengelola divisi IT'),

-- Top Management
('KW', 'Kepala Wilayah', 15, 'Memimpin seluruh operasional wilayah');

-- ============================================
-- 4. INSERT DATA PEKERJA (SAMPLE)
-- ============================================

-- Kepala Wilayah
INSERT INTO pekerja (nip, nama_lengkap, tempat_lahir, tanggal_lahir, jenis_kelamin, alamat, no_telepon, email, id_divisi, id_jabatan, id_golongan_saat_ini, tanggal_bergabung, tanggal_golongan_terakhir, id_atasan, nilai_kinerja_terakhir, status_kepegawaian) VALUES
('19900001', 'Dr. Budi Santoso, MBA', 'Padang', '1975-01-15', 'L', 'Jl. Veteran No. 123, Padang', '081234567001', 'budi.santoso@bri.co.id', 1, 19, 16, '2000-01-10', '2020-01-10', NULL, 95.00, 'aktif');

-- Manager Level
INSERT INTO pekerja (nip, nama_lengkap, tempat_lahir, tanggal_lahir, jenis_kelamin, alamat, no_telepon, email, id_divisi, id_jabatan, id_golongan_saat_ini, tanggal_bergabung, tanggal_golongan_terakhir, id_atasan, nilai_kinerja_terakhir, status_kepegawaian) VALUES
('20050002', 'Ir. Siti Rahmawati, M.M.', 'Bukittinggi', '1980-03-20', 'P', 'Jl. Sudirman No. 45, Padang', '081234567002', 'siti.rahmawati@bri.co.id', 1, 14, 14, '2005-06-15', '2018-06-15', 1, 92.50, 'aktif'),
('20060003', 'Ahmad Yani, S.E., M.M.', 'Padang', '1982-05-10', 'L', 'Jl. Pemuda No. 78, Padang', '081234567003', 'ahmad.yani@bri.co.id', 2, 15, 14, '2006-08-20', '2019-08-20', 1, 90.00, 'aktif'),
('20070004', 'Dewi Sartika, S.E.', 'Solok', '1983-07-25', 'P', 'Jl. Ahmad Yani No. 90, Padang', '081234567004', 'dewi.sartika@bri.co.id', 3, 16, 13, '2007-09-10', '2020-09-10', 1, 88.75, 'aktif'),
('20080005', 'Hendri Prasetyo, S.Kom., M.T.', 'Padang', '1984-11-30', 'L', 'Jl. Gajah Mada No. 12, Padang', '081234567005', 'hendri.prasetyo@bri.co.id', 4, 18, 13, '2008-10-15', '2021-10-15', 1, 91.25, 'aktif');

-- Supervisor Level
INSERT INTO pekerja (nip, nama_lengkap, tempat_lahir, tanggal_lahir, jenis_kelamin, alamat, no_telepon, email, id_divisi, id_jabatan, id_golongan_saat_ini, tanggal_bergabung, tanggal_golongan_terakhir, id_atasan, nilai_kinerja_terakhir, status_kepegawaian) VALUES
('20120006', 'Rina Marlina, S.E.', 'Payakumbuh', '1988-02-14', 'P', 'Jl. Diponegoro No. 34, Padang', '081234567006', 'rina.marlina@bri.co.id', 1, 10, 10, '2012-03-20', '2020-03-20', 2, 87.50, 'aktif'),
('20130007', 'Fadli Rahman, S.Sos.', 'Padang', '1989-04-18', 'L', 'Jl. Proklamasi No. 56, Padang', '081234567007', 'fadli.rahman@bri.co.id', 2, 11, 10, '2013-05-10', '2021-05-10', 3, 86.00, 'aktif'),
('20140008', 'Maya Sari, S.E.', 'Bukittinggi', '1990-06-22', 'P', 'Jl. Khatib Sulaiman No. 67, Padang', '081234567008', 'maya.sari@bri.co.id', 8, 12, 9, '2014-07-15', '2022-07-15', 2, 85.50, 'aktif'),
('20150009', 'Dedi Kurniawan, S.Kom.', 'Padang', '1991-08-26', 'L', 'Jl. S. Parman No. 89, Padang', '081234567009', 'dedi.kurniawan@bri.co.id', 4, 13, 9, '2015-09-20', '2021-09-20', 5, 88.00, 'aktif');

-- Senior Staff Level
INSERT INTO pekerja (nip, nama_lengkap, tempat_lahir, tanggal_lahir, jenis_kelamin, alamat, no_telepon, email, id_divisi, id_jabatan, id_golongan_saat_ini, tanggal_bergabung, tanggal_golongan_terakhir, id_atasan, nilai_kinerja_terakhir, status_kepegawaian) VALUES
('20180010', 'Lusi Handayani, S.E.', 'Solok', '1993-01-12', 'P', 'Jl. Hayam Wuruk No. 23, Padang', '081234567010', 'lusi.handayani@bri.co.id', 2, 6, 7, '2018-02-15', '2022-02-15', 7, 84.00, 'aktif'),
('20180011', 'Eko Prasetyo, S.E.', 'Padang', '1993-03-16', 'L', 'Jl. Supomo No. 45, Padang', '081234567011', 'eko.prasetyo@bri.co.id', 3, 7, 7, '2018-04-20', '2022-04-20', 4, 85.75, 'aktif'),
('20190012', 'Putri Utami, S.E.', 'Payakumbuh', '1994-05-20', 'P', 'Jl. Cendana No. 67, Padang', '081234567012', 'putri.utami@bri.co.id', 6, 8, 6, '2019-06-10', '2021-06-10', 2, 83.50, 'aktif'),
('20190013', 'Rudi Hartono, S.Kom.', 'Padang', '1994-07-24', 'L', 'Jl. Flamboyan No. 89, Padang', '081234567013', 'rudi.hartono@bri.co.id', 4, 5, 6, '2019-08-15', '2021-08-15', 9, 84.25, 'aktif');

-- Junior Staff Level
INSERT INTO pekerja (nip, nama_lengkap, tempat_lahir, tanggal_lahir, jenis_kelamin, alamat, no_telepon, email, id_divisi, id_jabatan, id_golongan_saat_ini, tanggal_bergabung, tanggal_golongan_terakhir, id_atasan, nilai_kinerja_terakhir, status_kepegawaian) VALUES
('20210014', 'Sari Dewi, S.E.', 'Bukittinggi', '1996-09-28', 'P', 'Jl. Melati No. 12, Padang', '081234567014', 'sari.dewi@bri.co.id', 8, 1, 3, '2021-01-10', '2022-01-10', 8, 82.00, 'aktif'),
('20210015', 'Andi Wijaya, S.Kom.', 'Padang', '1996-11-02', 'L', 'Jl. Mawar No. 34, Padang', '081234567015', 'andi.wijaya@bri.co.id', 4, 5, 3, '2021-03-15', '2022-03-15', 9, 83.00, 'aktif'),
('20220016', 'Nia Kurnia, S.E.', 'Padang', '1997-01-06', 'P', 'Jl. Anggrek No. 56, Padang', '081234567016', 'nia.kurnia@bri.co.id', 8, 1, 2, '2022-05-20', '2023-05-20', 8, 81.50, 'aktif'),
('20220017', 'Budi Setiawan, S.E.', 'Solok', '1997-03-10', 'L', 'Jl. Kenanga No. 78, Padang', '081234567017', 'budi.setiawan@bri.co.id', 1, 3, 2, '2022-07-25', '2023-07-25', 6, 80.75, 'aktif'),
('20230018', 'Fitri Ramadhani, D3', 'Payakumbuh', '1998-05-14', 'P', 'Jl. Dahlia No. 90, Padang', '081234567018', 'fitri.ramadhani@bri.co.id', 8, 2, 1, '2023-09-01', '2023-09-01', 8, 80.00, 'aktif'),
('20230019', 'Rio Pratama, D3', 'Padang', '1998-07-18', 'L', 'Jl. Tulip No. 11, Padang', '081234567019', 'rio.pratama@bri.co.id', 2, 4, 1, '2023-11-05', '2023-11-05', 7, 81.25, 'aktif');

-- ============================================
-- 5. INSERT DATA USERS
-- ============================================
-- Password default: "password123" (dalam implementasi harus di-hash)

INSERT INTO users (username, password, email, role, id_pekerja, is_active) VALUES
-- Admin
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin@bri.co.id', 'admin', NULL, 1),

-- Kepala Wilayah
('19900001', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'budi.santoso@bri.co.id', 'kepala_wilayah', 1, 1),

-- Manager (sebagai manager wilayah untuk approval level 2)
('20050002', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'siti.rahmawati@bri.co.id', 'manager', 2, 1),
('20060003', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'ahmad.yani@bri.co.id', 'manager', 3, 1),
('20070004', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'dewi.sartika@bri.co.id', 'manager', 4, 1),
('20080005', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'hendri.prasetyo@bri.co.id', 'manager', 5, 1),

-- Supervisor (sebagai atasan langsung)
('20120006', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'rina.marlina@bri.co.id', 'atasan', 6, 1),
('20130007', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'fadli.rahman@bri.co.id', 'atasan', 7, 1),
('20140008', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'maya.sari@bri.co.id', 'atasan', 8, 1),
('20150009', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'dedi.kurniawan@bri.co.id', 'atasan', 9, 1),

-- Senior Staff & Junior Staff (sebagai pekerja biasa)
('20180010', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'lusi.handayani@bri.co.id', 'pekerja', 10, 1),
('20180011', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'eko.prasetyo@bri.co.id', 'pekerja', 11, 1),
('20190012', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'putri.utami@bri.co.id', 'pekerja', 12, 1),
('20190013', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'rudi.hartono@bri.co.id', 'pekerja', 13, 1),
('20210014', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'sari.dewi@bri.co.id', 'pekerja', 14, 1),
('20210015', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'andi.wijaya@bri.co.id', 'pekerja', 15, 1),
('20220016', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'nia.kurnia@bri.co.id', 'pekerja', 16, 1),
('20220017', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'budi.setiawan@bri.co.id', 'pekerja', 17, 1),
('20230018', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'fitri.ramadhani@bri.co.id', 'pekerja', 18, 1),
('20230019', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'rio.pratama@bri.co.id', 'pekerja', 19, 1);

-- ============================================
-- 6. INSERT DATA PENGAJUAN (SAMPLE)
-- ============================================

-- Pengajuan yang sudah disetujui
INSERT INTO pengajuan (nomor_pengajuan, id_pekerja, id_golongan_saat_ini, id_golongan_diajukan, tanggal_pengajuan, alasan_pengajuan, status, catatan_pemohon, tanggal_efektif, nomor_sk) VALUES
('PG/2024/01/0001', 14, 2, 3, '2024-01-15', 'Telah bekerja selama 2 tahun dengan nilai kinerja baik, ingin meningkatkan kontribusi untuk perusahaan', 'disetujui', 'Melampirkan sertifikat pelatihan customer service', '2024-02-01', 'SK/2024/001'),
('PG/2024/02/0002', 16, 1, 2, '2024-02-10', 'Sudah memenuhi masa kerja minimal dan mengikuti pelatihan yang diperlukan', 'disetujui', NULL, '2024-03-01', 'SK/2024/002');

-- Pengajuan pending (menunggu approval atasan)
INSERT INTO pengajuan (nomor_pengajuan, id_pekerja, id_golongan_saat_ini, id_golongan_diajukan, tanggal_pengajuan, alasan_pengajuan, status, catatan_pemohon) VALUES
('PG/2024/03/0003', 15, 3, 4, '2024-03-20', 'Telah menyelesaikan berbagai project IT penting dan meningkatkan sistem keamanan', 'pending', 'Melampirkan sertifikat keamanan IT dan hasil audit sistem'),
('PG/2024/03/0004', 18, 1, 2, '2024-03-25', 'Konsisten memberikan pelayanan terbaik kepada nasabah dan mendapat apresiasi positif', 'pending', NULL);

-- Pengajuan disetujui atasan (menunggu manager)
INSERT INTO pengajuan (nomor_pengajuan, id_pekerja, id_golongan_saat_ini, id_golongan_diajukan, tanggal_pengajuan, alasan_pengajuan, status, catatan_pemohon) VALUES
('PG/2024/02/0005', 10, 7, 8, '2024-02-28', 'Berhasil meningkatkan portfolio klien sebesar 30% dan mempertahankan loyalitas nasabah', 'disetujui_atasan', 'Melampirkan laporan kinerja dan pencapaian target');

-- Pengajuan disetujui manager (menunggu kepala wilayah)
INSERT INTO pengajuan (nomor_pengajuan, id_pekerja, id_golongan_saat_ini, id_golongan_diajukan, tanggal_pengajuan, alasan_pengajuan, status, catatan_pemohon) VALUES
('PG/2024/01/0006', 11, 7, 8, '2024-01-20', 'Sukses mengelola risiko kredit bermasalah dan menurunkan NPL sebesar 15%', 'disetujui_manager', 'Melampirkan hasil analisis risiko dan strategi penyelesaian');

-- Pengajuan ditolak
INSERT INTO pengajuan (nomor_pengajuan, id_pekerja, id_golongan_saat_ini, id_golongan_diajukan, tanggal_pengajuan, alasan_pengajuan, status, catatan_pemohon) VALUES
('PG/2024/03/0007', 19, 1, 2, '2024-03-10', 'Ingin meningkatkan golongan untuk motivasi kerja', 'ditolak_atasan', NULL);

-- ============================================
-- 7. INSERT DATA APPROVAL HISTORY
-- ============================================

-- Untuk pengajuan yang sudah disetujui penuh (PG/2024/01/0001)
INSERT INTO approval_history (id_pengajuan, level_approval, id_approver, keputusan, catatan, tanggal_approval) VALUES
(1, 'atasan', 9, 'approved', 'Pekerja menunjukkan peningkatan kinerja yang baik', '2024-01-17 10:30:00'),
(1, 'manager', 3, 'approved', 'Memenuhi kriteria untuk kenaikan golongan', '2024-01-20 14:15:00'),
(1, 'kepala_wilayah', 2, 'approved', 'Disetujui untuk kenaikan golongan menjadi I-C', '2024-01-25 09:00:00');

-- Untuk pengajuan yang sudah disetujui penuh (PG/2024/02/0002)
INSERT INTO approval_history (id_pengajuan, level_approval, id_approver, keputusan, catatan, tanggal_approval) VALUES
(2, 'atasan', 9, 'approved', 'Kinerja konsisten dan memenuhi syarat', '2024-02-12 11:00:00'),
(2, 'manager', 3, 'approved', 'Layak untuk kenaikan', '2024-02-15 15:30:00'),
(2, 'kepala_wilayah', 2, 'approved', 'Disetujui', '2024-02-20 10:45:00');

-- Untuk pengajuan disetujui atasan (PG/2024/02/0005)
INSERT INTO approval_history (id_pengajuan, level_approval, id_approver, keputusan, catatan, tanggal_approval) VALUES
(5, 'atasan', 8, 'approved', 'Pencapaian luar biasa dalam mengelola portfolio, direkomendasikan untuk naik golongan', '2024-03-01 13:20:00');

-- Untuk pengajuan disetujui manager (PG/2024/01/0006)
INSERT INTO approval_history (id_pengajuan, level_approval, id_approver, keputusan, catatan, tanggal_approval) VALUES
(6, 'atasan', 5, 'approved', 'Kontribusi signifikan dalam risk management', '2024-01-22 16:00:00'),
(6, 'manager', 4, 'approved', 'Strategi yang diterapkan sangat efektif', '2024-01-28 11:30:00');

-- Untuk pengajuan ditolak (PG/2024/03/0007)
INSERT INTO approval_history (id_pengajuan, level_approval, id_approver, keputusan, catatan, tanggal_approval) VALUES
(7, 'atasan', 8, 'rejected', 'Masa kerja belum mencukupi, baru 6 bulan sejak golongan terakhir. Minimal 2 tahun. Silakan ajukan kembali setelah memenuhi masa kerja minimal.', '2024-03-12 14:45:00');

-- ============================================
-- 8. INSERT DATA DOKUMEN PENGAJUAN (SAMPLE)
-- ============================================

INSERT INTO dokumen_pengajuan (id_pengajuan, jenis_dokumen, nama_dokumen, file_path, file_size, mime_type, keterangan) VALUES
(1, 'surat_permohonan', 'Surat_Permohonan_Sari_Dewi.pdf', '/uploads/documents/2024/01/20210014/surat_permohonan_001.pdf', 245678, 'application/pdf', 'Surat permohonan kenaikan golongan'),
(1, 'penilaian_kinerja', 'Penilaian_Kinerja_2023.pdf', '/uploads/documents/2024/01/20210014/penilaian_kinerja_001.pdf', 189456, 'application/pdf', 'Hasil penilaian kinerja tahun 2023'),
(1, 'sertifikat', 'Sertifikat_Customer_Service.pdf', '/uploads/documents/2024/01/20210014/sertifikat_001.pdf', 523789, 'application/pdf', 'Sertifikat pelatihan customer service excellence'),

(3, 'surat_permohonan', 'Surat_Permohonan_Andi_Wijaya.pdf', '/uploads/documents/2024/03/20210015/surat_permohonan_003.pdf', 234567, 'application/pdf', 'Surat permohonan kenaikan golongan'),
(3, 'penilaian_kinerja', 'Penilaian_Kinerja_Andi.pdf', '/uploads/documents/2024/03/20210015/penilaian_kinerja_003.pdf', 198765, 'application/pdf', 'Hasil penilaian kinerja'),
(3, 'sertifikat', 'Sertifikat_IT_Security.pdf', '/uploads/documents/2024/03/20210015/sertifikat_003.pdf', 445678, 'application/pdf', 'Sertifikat keamanan IT'),

(5, 'surat_permohonan', 'Surat_Permohonan_Lusi.pdf', '/uploads/documents/2024/02/20180010/surat_permohonan_005.pdf', 267890, 'application/pdf', 'Surat permohonan kenaikan golongan'),
(5, 'penilaian_kinerja', 'Penilaian_Kinerja_Lusi_2023.pdf', '/uploads/documents/2024/02/20180010/penilaian_kinerja_005.pdf', 201234, 'application/pdf', 'Hasil penilaian kinerja 2023'),
(5, 'lainnya', 'Laporan_Portfolio_Klien.pdf', '/uploads/documents/2024/02/20180010/laporan_portfolio.pdf', 678901, 'application/pdf', 'Laporan pencapaian portfolio klien');

-- ============================================
-- 9. INSERT DATA NOTIFIKASI (SAMPLE)
-- ============================================

INSERT INTO notifikasi (id_user, judul, pesan, link, is_read, created_at) VALUES
-- Untuk pekerja
(15, 'Pengajuan Berhasil Dibuat', 'Pengajuan kenaikan golongan Anda dengan nomor PG/2024/03/0003 telah berhasil dibuat dan sedang menunggu review atasan.', '/pengajuan/detail/3', 0, '2024-03-20 10:15:00'),
(16, 'Pengajuan Berhasil Dibuat', 'Pengajuan kenaikan golongan Anda dengan nomor PG/2024/03/0004 telah berhasil dibuat.', '/pengajuan/detail/4', 0, '2024-03-25 14:30:00'),

-- Untuk atasan (pending approval)
(10, 'Pengajuan Perlu Review', 'Ada pengajuan baru dari Andi Wijaya yang memerlukan persetujuan Anda.', '/approval/review/3', 0, '2024-03-20 10:16:00'),
(9, 'Pengajuan Perlu Review', 'Ada pengajuan baru dari Fitri Ramadhani yang memerlukan persetujuan Anda.', '/approval/review/4', 0, '2024-03-25 14:31:00'),

-- Untuk manager (pengajuan forwarded)
(4, 'Pengajuan Perlu Review', 'Pengajuan dari Lusi Handayani telah disetujui atasan dan memerlukan review Anda.', '/approval/review/5', 0, '2024-03-01 13:21:00'),

-- Untuk kepala wilayah
(2, 'Pengajuan Perlu Final Approval', 'Pengajuan dari Eko Prasetyo memerlukan persetujuan final Anda.', '/approval/review/6', 0, '2024-01-28 11:31:00'),

-- Notifikasi approval
(15, 'Pengajuan Disetujui', 'Selamat! Pengajuan kenaikan golongan Anda telah disetujui. Golongan Anda sekarang: I-C', '/pengajuan/detail/1', 1, '2024-01-25 09:05:00');

-- ============================================
-- END OF SAMPLE DATA
-- ============================================

-- Enable kembali foreign key checks
SET FOREIGN_KEY_CHECKS = 1;

-- Query untuk cek data
-- SELECT * FROM users;
-- SELECT * FROM v_pekerja_detail;
-- SELECT * FROM v_pengajuan_detail;
