-- ============================================
-- DATA TESTING LENGKAP - ISOLATED
-- Untuk testing flow approval tanpa merusak data existing
-- Prefix: TEST-XXX untuk semua nomor
-- ============================================

USE sistem_kenaikan_golongan_bri;

SET FOREIGN_KEY_CHECKS = 0;

-- ============================================
-- 1. INSERT PEKERJA TESTING (3 PEKERJA BARU)
-- ============================================

-- ATASAN TESTING - Supervisor Testing
INSERT INTO pekerja (nip, nama_lengkap, tempat_lahir, tanggal_lahir, jenis_kelamin, alamat, no_telepon, email, id_divisi, id_jabatan, id_golongan_saat_ini, tanggal_bergabung, tanggal_golongan_terakhir, id_atasan, nilai_kinerja_terakhir, status_kepegawaian) VALUES
('TEST-SPV-001', 'Bambang Supervisor Testing', 'Padang', '1990-05-15', 'L', 'Jl. Testing Supervisor No. 1, Padang', '081299999001', 'bambang.test@bri.co.id', 8, 12, 9, '2015-01-10', '2020-01-10', 2, 87.50, 'aktif')
ON DUPLICATE KEY UPDATE 
    nama_lengkap = 'Bambang Supervisor Testing',
    status_kepegawaian = 'aktif';

-- Ambil id_pekerja atasan testing yang baru diinsert
SET @id_atasan_testing = (SELECT id_pekerja FROM pekerja WHERE nip = 'TEST-SPV-001');

-- PEKERJA TESTING 1 - untuk pengajuan pending
INSERT INTO pekerja (nip, nama_lengkap, tempat_lahir, tanggal_lahir, jenis_kelamin, alamat, no_telepon, email, id_divisi, id_jabatan, id_golongan_saat_ini, tanggal_bergabung, tanggal_golongan_terakhir, id_atasan, nilai_kinerja_terakhir, status_kepegawaian) VALUES
('TEST-PKJ-001', 'Ahmad Pekerja Testing 1', 'Bukittinggi', '1995-03-20', 'L', 'Jl. Testing No. 10, Padang', '081299999010', 'ahmad.test1@bri.co.id', 8, 1, 2, '2021-06-01', '2023-06-01', @id_atasan_testing, 85.00, 'aktif')
ON DUPLICATE KEY UPDATE 
    nama_lengkap = 'Ahmad Pekerja Testing 1',
    id_atasan = @id_atasan_testing,
    status_kepegawaian = 'aktif';

-- PEKERJA TESTING 2 - untuk pengajuan yang sedang diproses
INSERT INTO pekerja (nip, nama_lengkap, tempat_lahir, tanggal_lahir, jenis_kelamin, alamat, no_telepon, email, id_divisi, id_jabatan, id_golongan_saat_ini, tanggal_bergabung, tanggal_golongan_terakhir, id_atasan, nilai_kinerja_terakhir, status_kepegawaian) VALUES
('TEST-PKJ-002', 'Siti Pekerja Testing 2', 'Padang', '1996-07-12', 'P', 'Jl. Testing No. 11, Padang', '081299999011', 'siti.test2@bri.co.id', 8, 1, 3, '2020-09-15', '2022-09-15', @id_atasan_testing, 88.00, 'aktif')
ON DUPLICATE KEY UPDATE 
    nama_lengkap = 'Siti Pekerja Testing 2',
    id_atasan = @id_atasan_testing,
    status_kepegawaian = 'aktif';

-- ============================================
-- 2. INSERT USERS TESTING
-- ============================================

-- User untuk Supervisor Testing (role: atasan)
INSERT INTO users (username, password, email, role, id_pekerja, is_active) VALUES
('TEST-SPV-001', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'bambang.test@bri.co.id', 'atasan', @id_atasan_testing, 1)
ON DUPLICATE KEY UPDATE 
    role = 'atasan',
    is_active = 1;

-- Ambil id_pekerja untuk pekerja testing
SET @id_pekerja_test1 = (SELECT id_pekerja FROM pekerja WHERE nip = 'TEST-PKJ-001');
SET @id_pekerja_test2 = (SELECT id_pekerja FROM pekerja WHERE nip = 'TEST-PKJ-002');

-- User untuk Pekerja Testing 1
INSERT INTO users (username, password, email, role, id_pekerja, is_active) VALUES
('TEST-PKJ-001', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'ahmad.test1@bri.co.id', 'pekerja', @id_pekerja_test1, 1)
ON DUPLICATE KEY UPDATE 
    role = 'pekerja',
    is_active = 1;

-- User untuk Pekerja Testing 2
INSERT INTO users (username, password, email, role, id_pekerja, is_active) VALUES
('TEST-PKJ-002', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'siti.test2@bri.co.id', 'pekerja', @id_pekerja_test2, 1)
ON DUPLICATE KEY UPDATE 
    role = 'pekerja',
    is_active = 1;

-- ============================================
-- 3. INSERT PENGAJUAN TESTING
-- ============================================

-- PENGAJUAN 1: Status PENDING (baru diajukan)
-- Dari: Ahmad Pekerja Testing 1
-- Golongan: I-B (id=2) → I-C (id=3)
INSERT INTO pengajuan (nomor_pengajuan, id_pekerja, id_golongan_saat_ini, id_golongan_diajukan, tanggal_pengajuan, alasan_pengajuan, status, catatan_pemohon) VALUES
('TEST-PG-001', @id_pekerja_test1, 2, 3, CURDATE(), 
'Pengajuan testing untuk kenaikan golongan dari I-B ke I-C. Telah bekerja selama 3 tahun dengan kinerja yang konsisten (nilai 85.00). Telah menyelesaikan pelatihan customer service dan mendapat apresiasi dari nasabah.', 
'pending', 
'Melampirkan dokumen: surat permohonan, hasil penilaian kinerja 3 tahun terakhir, dan sertifikat pelatihan.')
ON DUPLICATE KEY UPDATE 
    status = 'pending',
    tanggal_pengajuan = CURDATE();

-- PENGAJUAN 2: Status DISETUJUI_ATASAN (sudah diapprove atasan, menunggu manager)
-- Dari: Siti Pekerja Testing 2
-- Golongan: I-C (id=3) → I-D (id=4)
INSERT INTO pengajuan (nomor_pengajuan, id_pekerja, id_golongan_saat_ini, id_golongan_diajukan, tanggal_pengajuan, alasan_pengajuan, status, catatan_pemohon) VALUES
('TEST-PG-002', @id_pekerja_test2, 3, 4, DATE_SUB(CURDATE(), INTERVAL 2 DAY), 
'Pengajuan testing untuk kenaikan golongan dari I-C ke I-D. Telah bekerja selama 4 tahun dengan kinerja sangat baik (nilai 88.00). Berhasil meningkatkan kepuasan nasabah dari 85% menjadi 95% dan mengelola komplain dengan sangat baik.', 
'disetujui_atasan', 
'Melampirkan dokumen lengkap dan rekomendasi dari atasan langsung.')
ON DUPLICATE KEY UPDATE 
    status = 'disetujui_atasan',
    tanggal_pengajuan = DATE_SUB(CURDATE(), INTERVAL 2 DAY);

-- Ambil id_pengajuan untuk referensi dokumen dan approval history
SET @id_pengajuan_test1 = (SELECT id_pengajuan FROM pengajuan WHERE nomor_pengajuan = 'TEST-PG-001');
SET @id_pengajuan_test2 = (SELECT id_pengajuan FROM pengajuan WHERE nomor_pengajuan = 'TEST-PG-002');

-- ============================================
-- 4. INSERT DOKUMEN PENDUKUNG
-- ============================================

-- Dokumen untuk PENGAJUAN 1 (Ahmad - Pending)
INSERT INTO dokumen_pengajuan (id_pengajuan, jenis_dokumen, nama_dokumen, file_path, file_size, mime_type, keterangan, uploaded_at) VALUES
(@id_pengajuan_test1, 'surat_permohonan', 'TEST_Surat_Permohonan_Ahmad.pdf', '/uploads/documents/test/ahmad/surat_permohonan.pdf', 256789, 'application/pdf', 'Surat permohonan kenaikan golongan - Testing', CURDATE()),
(@id_pengajuan_test1, 'penilaian_kinerja', 'TEST_Penilaian_Kinerja_Ahmad_2021-2024.pdf', '/uploads/documents/test/ahmad/penilaian_kinerja.pdf', 423456, 'application/pdf', 'Hasil penilaian kinerja 3 tahun terakhir - Testing', CURDATE()),
(@id_pengajuan_test1, 'sertifikat', 'TEST_Sertifikat_CS_Excellence_Ahmad.pdf', '/uploads/documents/test/ahmad/sertifikat.pdf', 534567, 'application/pdf', 'Sertifikat Customer Service Excellence - Testing', CURDATE())
ON DUPLICATE KEY UPDATE nama_dokumen = nama_dokumen;

-- Dokumen untuk PENGAJUAN 2 (Siti - Disetujui Atasan)
INSERT INTO dokumen_pengajuan (id_pengajuan, jenis_dokumen, nama_dokumen, file_path, file_size, mime_type, keterangan, uploaded_at) VALUES
(@id_pengajuan_test2, 'surat_permohonan', 'TEST_Surat_Permohonan_Siti.pdf', '/uploads/documents/test/siti/surat_permohonan.pdf', 267890, 'application/pdf', 'Surat permohonan kenaikan golongan - Testing', DATE_SUB(CURDATE(), INTERVAL 2 DAY)),
(@id_pengajuan_test2, 'penilaian_kinerja', 'TEST_Penilaian_Kinerja_Siti_2020-2024.pdf', '/uploads/documents/test/siti/penilaian_kinerja.pdf', 445678, 'application/pdf', 'Hasil penilaian kinerja 4 tahun terakhir - Testing', DATE_SUB(CURDATE(), INTERVAL 2 DAY)),
(@id_pengajuan_test2, 'sertifikat', 'TEST_Sertifikat_Service_Champion_Siti.pdf', '/uploads/documents/test/siti/sertifikat.pdf', 589012, 'application/pdf', 'Sertifikat Service Champion & Complaint Handling - Testing', DATE_SUB(CURDATE(), INTERVAL 2 DAY)),
(@id_pengajuan_test2, 'lainnya', 'TEST_Laporan_Peningkatan_Kepuasan_Nasabah.pdf', '/uploads/documents/test/siti/laporan_tambahan.pdf', 678901, 'application/pdf', 'Laporan peningkatan kepuasan nasabah 85% → 95% - Testing', DATE_SUB(CURDATE(), INTERVAL 2 DAY))
ON DUPLICATE KEY UPDATE nama_dokumen = nama_dokumen;

-- ============================================
-- 5. INSERT APPROVAL HISTORY
-- ============================================

-- Ambil id_user supervisor testing
SET @id_user_spv = (SELECT id_user FROM users WHERE username = 'TEST-SPV-001');

-- Approval history untuk PENGAJUAN 2 (Siti - sudah disetujui atasan)
-- Level 1: Atasan (Bambang Supervisor Testing) - APPROVED
INSERT INTO approval_history (id_pengajuan, level_approval, id_approver, keputusan, catatan, tanggal_approval) VALUES
(@id_pengajuan_test2, 'atasan', @id_user_spv, 'approved', 
'Pengajuan disetujui. Pekerja menunjukkan peningkatan kinerja yang sangat signifikan. Berhasil meningkatkan kepuasan nasabah dari 85% menjadi 95% dalam 1 tahun terakhir. Sangat layak untuk kenaikan golongan ke I-D. Testing approval.', 
DATE_SUB(CURDATE(), INTERVAL 1 DAY))
ON DUPLICATE KEY UPDATE 
    keputusan = 'approved',
    tanggal_approval = DATE_SUB(CURDATE(), INTERVAL 1 DAY);

-- ============================================
-- 6. INSERT NOTIFIKASI
-- ============================================

-- Ambil id_user untuk pekerja testing
SET @id_user_test1 = (SELECT id_user FROM users WHERE username = 'TEST-PKJ-001');
SET @id_user_test2 = (SELECT id_user FROM users WHERE username = 'TEST-PKJ-002');
SET @id_user_manager = (SELECT id_user FROM users WHERE role = 'manager' LIMIT 1);

-- Notifikasi untuk Ahmad (Pengajuan 1 - Pending)
INSERT INTO notifikasi (id_user, judul, pesan, link, is_read, created_at) VALUES
(@id_user_test1, 'Pengajuan Berhasil Dibuat', 
'Pengajuan kenaikan golongan Anda dengan nomor TEST-PG-001 telah berhasil dibuat dan sedang menunggu review atasan langsung (Bambang Supervisor Testing). [DATA TESTING]', 
'/pengajuan/detail/' || @id_pengajuan_test1, 
0, 
CURDATE())
ON DUPLICATE KEY UPDATE is_read = 0;

-- Notifikasi untuk Supervisor Testing (ada pengajuan pending dari Ahmad)
INSERT INTO notifikasi (id_user, judul, pesan, link, is_read, created_at) VALUES
(@id_user_spv, 'Pengajuan Perlu Review', 
'Ada pengajuan baru dari Ahmad Pekerja Testing 1 (TEST-PG-001) yang memerlukan persetujuan Anda. [DATA TESTING]', 
'/approval/review/' || @id_pengajuan_test1, 
0, 
CURDATE())
ON DUPLICATE KEY UPDATE is_read = 0;

-- Notifikasi untuk Siti (Pengajuan 2 - sudah disetujui atasan)
INSERT INTO notifikasi (id_user, judul, pesan, link, is_read, created_at) VALUES
(@id_user_test2, 'Pengajuan Disetujui Atasan', 
'Pengajuan kenaikan golongan Anda (TEST-PG-002) telah disetujui oleh atasan langsung (Bambang Supervisor Testing) dan sedang menunggu review Manager. [DATA TESTING]', 
'/pengajuan/detail/' || @id_pengajuan_test2, 
1, 
DATE_SUB(CURDATE(), INTERVAL 1 DAY))
ON DUPLICATE KEY UPDATE is_read = 1;

-- Notifikasi untuk Manager (pengajuan Siti menunggu review)
INSERT INTO notifikasi (id_user, judul, pesan, link, is_read, created_at) VALUES
(@id_user_manager, 'Pengajuan Perlu Review (Manager)', 
'Pengajuan dari Siti Pekerja Testing 2 (TEST-PG-002) telah disetujui atasan dan memerlukan review Anda sebagai Manager. [DATA TESTING]', 
'/approval/review/' || @id_pengajuan_test2, 
0, 
DATE_SUB(CURDATE(), INTERVAL 1 DAY))
ON DUPLICATE KEY UPDATE is_read = 0;

-- ============================================
-- 7. INSERT LOG AKTIVITAS
-- ============================================

-- Log untuk Ahmad (membuat pengajuan)
INSERT INTO log_aktivitas (id_user, aktivitas, modul, ip_address, user_agent, created_at) VALUES
(@id_user_test1, 'Membuat pengajuan kenaikan golongan TEST-PG-001', 'pengajuan', '192.168.1.100', 'Mozilla/5.0 (Testing Browser)', CURDATE());

-- Log untuk Siti (membuat pengajuan)
INSERT INTO log_aktivitas (id_user, aktivitas, modul, ip_address, user_agent, created_at) VALUES
(@id_user_test2, 'Membuat pengajuan kenaikan golongan TEST-PG-002', 'pengajuan', '192.168.1.101', 'Mozilla/5.0 (Testing Browser)', DATE_SUB(CURDATE(), INTERVAL 2 DAY));

-- Log untuk Supervisor (approve pengajuan Siti)
INSERT INTO log_aktivitas (id_user, aktivitas, modul, ip_address, user_agent, created_at) VALUES
(@id_user_spv, 'Menyetujui pengajuan TEST-PG-002 dari Siti Pekerja Testing 2', 'approval', '192.168.1.102', 'Mozilla/5.0 (Testing Browser)', DATE_SUB(CURDATE(), INTERVAL 1 DAY));

-- Log untuk Ahmad (login)
INSERT INTO log_aktivitas (id_user, aktivitas, modul, ip_address, user_agent, created_at) VALUES
(@id_user_test1, 'Login ke sistem', 'auth', '192.168.1.100', 'Mozilla/5.0 (Testing Browser)', CURDATE());

-- Log untuk Supervisor (login)
INSERT INTO log_aktivitas (id_user, aktivitas, modul, ip_address, user_agent, created_at) VALUES
(@id_user_spv, 'Login ke sistem', 'auth', '192.168.1.102', 'Mozilla/5.0 (Testing Browser)', CURDATE());

SET FOREIGN_KEY_CHECKS = 1;

-- ============================================
-- VALIDASI DATA TESTING
-- ============================================

SELECT '====== VALIDASI PEKERJA TESTING ======' as info;

SELECT 
    p.nip,
    p.nama_lengkap,
    u.username,
    u.role,
    gol.kode_golongan as golongan,
    atasan.nama_lengkap as nama_atasan,
    p.nilai_kinerja_terakhir
FROM pekerja p
LEFT JOIN users u ON p.id_pekerja = u.id_pekerja
LEFT JOIN golongan_jabatan gol ON p.id_golongan_saat_ini = gol.id_golongan
LEFT JOIN pekerja atasan ON p.id_atasan = atasan.id_pekerja
WHERE p.nip LIKE 'TEST-%'
ORDER BY p.nip;

SELECT '====== VALIDASI PENGAJUAN TESTING ======' as info;

SELECT 
    peng.nomor_pengajuan,
    peng.tanggal_pengajuan,
    peng.status,
    p.nip,
    p.nama_lengkap as pemohon,
    g_sekarang.kode_golongan as dari_golongan,
    g_tujuan.kode_golongan as ke_golongan,
    atasan.nama_lengkap as atasan
FROM pengajuan peng
JOIN pekerja p ON peng.id_pekerja = p.id_pekerja
LEFT JOIN pekerja atasan ON p.id_atasan = atasan.id_pekerja
LEFT JOIN golongan_jabatan g_sekarang ON peng.id_golongan_saat_ini = g_sekarang.id_golongan
LEFT JOIN golongan_jabatan g_tujuan ON peng.id_golongan_diajukan = g_tujuan.id_golongan
WHERE peng.nomor_pengajuan LIKE 'TEST-%'
ORDER BY peng.nomor_pengajuan;

SELECT '====== VALIDASI DOKUMEN TESTING ======' as info;

SELECT 
    peng.nomor_pengajuan,
    dok.jenis_dokumen,
    dok.nama_dokumen,
    ROUND(dok.file_size/1024, 2) as ukuran_kb
FROM dokumen_pengajuan dok
JOIN pengajuan peng ON dok.id_pengajuan = peng.id_pengajuan
WHERE peng.nomor_pengajuan LIKE 'TEST-%'
ORDER BY peng.nomor_pengajuan, dok.jenis_dokumen;

SELECT '====== VALIDASI APPROVAL HISTORY TESTING ======' as info;

SELECT 
    peng.nomor_pengajuan,
    ah.level_approval,
    approver.nama_lengkap as approver,
    ah.keputusan,
    ah.tanggal_approval
FROM approval_history ah
JOIN pengajuan peng ON ah.id_pengajuan = peng.id_pengajuan
JOIN users u ON ah.id_approver = u.id_user
JOIN pekerja approver ON u.id_pekerja = approver.id_pekerja
WHERE peng.nomor_pengajuan LIKE 'TEST-%'
ORDER BY peng.nomor_pengajuan, ah.tanggal_approval;

SELECT '====== VALIDASI NOTIFIKASI TESTING ======' as info;

SELECT 
    u.username,
    n.judul,
    n.is_read,
    n.created_at
FROM notifikasi n
JOIN users u ON n.id_user = u.id_user
WHERE n.judul LIKE '%TESTING%' OR n.pesan LIKE '%TESTING%'
ORDER BY n.created_at DESC;

SELECT '====== VALIDASI LOG AKTIVITAS TESTING ======' as info;

SELECT 
    u.username,
    la.aktivitas,
    la.modul,
    la.created_at
FROM log_aktivitas la
JOIN users u ON la.id_user = u.id_user
WHERE la.aktivitas LIKE '%TEST-%'
ORDER BY la.created_at DESC;

-- ============================================
-- INSTRUKSI TESTING
-- ============================================

/*
╔════════════════════════════════════════════════════════════════╗
║           PANDUAN TESTING - DATA ISOLATED                      ║
╚════════════════════════════════════════════════════════════════╝

✅ DATA YANG DIBUAT:

1. PEKERJA TESTING (3 orang):
   - TEST-SPV-001: Bambang Supervisor Testing (Atasan) - Role: atasan
   - TEST-PKJ-001: Ahmad Pekerja Testing 1 (Pekerja) - Role: pekerja
   - TEST-PKJ-002: Siti Pekerja Testing 2 (Pekerja) - Role: pekerja

2. USER ACCOUNTS (semua password: password123):
   - Username: TEST-SPV-001 (Supervisor/Atasan)
   - Username: TEST-PKJ-001 (Pekerja 1)
   - Username: TEST-PKJ-002 (Pekerja 2)

3. PENGAJUAN TESTING:
   - TEST-PG-001: Ahmad (Status: PENDING) - Baru diajukan
   - TEST-PG-002: Siti (Status: DISETUJUI_ATASAN) - Sudah approve atasan

4. DOKUMEN PENDUKUNG:
   - 3 dokumen untuk Ahmad (surat, penilaian, sertifikat)
   - 4 dokumen untuk Siti (surat, penilaian, sertifikat, laporan tambahan)

5. APPROVAL HISTORY:
   - 1 record approval dari Bambang untuk pengajuan Siti

6. NOTIFIKASI:
   - 4 notifikasi terkait pengajuan testing

7. LOG AKTIVITAS:
   - 5 log aktivitas (login, submit, approve)

═══════════════════════════════════════════════════════════════

🧪 SKENARIO TESTING 1 - PENGAJUAN PENDING (Ahmad):

1. LOGIN sebagai Supervisor Testing:
   Username: TEST-SPV-001
   Password: password123
   
   ✓ Menu: Pending Approval
   ✓ HARUS MUNCUL: Pengajuan dari Ahmad (TEST-PG-001)
   ✓ Action: Review → Approve
   ✓ Status berubah: pending → disetujui_atasan

2. LOGIN sebagai Manager (pilih salah satu existing):
   Username: 20050002
   Password: password123
   
   ✓ Menu: Pending Approval
   ✓ HARUS MUNCUL: Pengajuan dari Ahmad
   ✓ Action: Review → Approve
   ✓ Status berubah: disetujui_atasan → disetujui_manager

3. LOGIN sebagai Kepala Wilayah:
   Username: 19900001
   Password: password123
   
   ✓ Menu: Pending Approval
   ✓ HARUS MUNCUL: Pengajuan dari Ahmad
   ✓ Action: Review → Approve
   ✓ Status berubah: disetujui_manager → disetujui
   ✓ Golongan Ahmad update: I-B → I-C

═══════════════════════════════════════════════════════════════

🧪 SKENARIO TESTING 2 - PENGAJUAN DISETUJUI ATASAN (Siti):

1. LOGIN sebagai Manager:
   Username: 20050002
   Password: password123
   
   ✓ Menu: Pending Approval
   ✓ HARUS MUNCUL: Pengajuan dari Siti (TEST-PG-002)
   ✓ Lihat approval history: sudah ada 1 approval dari Bambang
   ✓ Action: Review → Approve
   ✓ Status berubah: disetujui_atasan → disetujui_manager

2. LOGIN sebagai Kepala Wilayah:
   Username: 19900001
   Password: password123
   
   ✓ Menu: Pending Approval
   ✓ HARUS MUNCUL: Pengajuan dari Siti
   ✓ Action: Review → Approve
   ✓ Status berubah: disetujui_manager → disetujui
   ✓ Golongan Siti update: I-C → I-D

═══════════════════════════════════════════════════════════════

📊 QUERY VALIDASI HASIL:

-- Cek status pengajuan
SELECT nomor_pengajuan, status FROM pengajuan 
WHERE nomor_pengajuan LIKE 'TEST-%';

-- Cek golongan pekerja testing
SELECT p.nip, p.nama_lengkap, gol.kode_golongan
FROM pekerja p
JOIN golongan_jabatan gol ON p.id_golongan_saat_ini = gol.id_golongan
WHERE p.nip LIKE 'TEST-%';

-- Cek approval history lengkap
SELECT peng.nomor_pengajuan, ah.level_approval, 
       approver.nama_lengkap, ah.keputusan
FROM approval_history ah
JOIN pengajuan peng ON ah.id_pengajuan = peng.id_pengajuan
JOIN users u ON ah.id_approver = u.id_user
JOIN pekerja approver ON u.id_pekerja = approver.id_pekerja
WHERE peng.nomor_pengajuan LIKE 'TEST-%'
ORDER BY peng.nomor_pengajuan, ah.tanggal_approval;

═══════════════════════════════════════════════════════════════

🗑️ HAPUS DATA TESTING (jika ingin reset):

DELETE FROM log_aktivitas WHERE aktivitas LIKE '%TEST-%';
DELETE FROM notifikasi WHERE judul LIKE '%TESTING%' OR pesan LIKE '%TESTING%';
DELETE FROM approval_history WHERE id_pengajuan IN 
    (SELECT id_pengajuan FROM pengajuan WHERE nomor_pengajuan LIKE 'TEST-%');
DELETE FROM dokumen_pengajuan WHERE id_pengajuan IN 
    (SELECT id_pengajuan FROM pengajuan WHERE nomor_pengajuan LIKE 'TEST-%');
DELETE FROM pengajuan WHERE nomor_pengajuan LIKE 'TEST-%';
DELETE FROM users WHERE username LIKE 'TEST-%';
DELETE FROM pekerja WHERE nip LIKE 'TEST-%';

═══════════════════════════════════════════════════════════════

✅ DATA TESTING INI:
- Isolated (tidak mengganggu data existing)
- Prefix jelas (TEST-XXX)
- Lengkap (users, pekerja, pengajuan, dokumen, approval, notif, log)
- Siap pakai untuk testing 1 cycle lengkap
- Mudah dihapus jika ingin reset

Happy Testing! 🚀
*/
