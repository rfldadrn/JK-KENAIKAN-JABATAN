-- ============================================
-- DATA TESTING - PENGAJUAN PENDING
-- Untuk testing flow approval lengkap
-- ============================================

USE sistem_kenaikan_golongan_bri;

-- ============================================
-- HAPUS PENGAJUAN LAMA UNTUK TESTING ULANG
-- ============================================

-- Backup: Jangan hapus pengajuan yang sudah disetujui, hanya reset yang pending
-- DELETE FROM approval_history WHERE id_pengajuan IN (3, 4, 7);
-- DELETE FROM dokumen_pengajuan WHERE id_pengajuan IN (3, 4, 7);
-- DELETE FROM pengajuan WHERE id_pengajuan IN (3, 4, 7);

-- ============================================
-- INSERT PENGAJUAN TESTING LENGKAP
-- ============================================

-- Pastikan ada pengajuan PENDING dari Sari Dewi (id_pekerja=14)
-- Atasan: Maya Sari (id_pekerja=8, NIP=20140008)

INSERT INTO pengajuan (nomor_pengajuan, id_pekerja, id_golongan_saat_ini, id_golongan_diajukan, tanggal_pengajuan, alasan_pengajuan, status, catatan_pemohon) VALUES
('PG-TEST-001', 14, 3, 4, CURDATE(), 'Testing pengajuan kenaikan golongan dari I-C ke I-D. Telah bekerja selama 3 tahun dengan kinerja konsisten dan mendapat apresiasi dari atasan serta nasabah.', 'pending', 'Melampirkan sertifikat pelatihan customer service excellence dan hasil penilaian kinerja 3 tahun terakhir.')
ON DUPLICATE KEY UPDATE 
    status = 'pending',
    tanggal_pengajuan = CURDATE();

-- Pengajuan dari Andi Wijaya (id_pekerja=15) - untuk testing flow lengkap
-- Atasan: Dedi Kurniawan (id_pekerja=9, NIP=20150009)
INSERT INTO pengajuan (nomor_pengajuan, id_pekerja, id_golongan_saat_ini, id_golongan_diajukan, tanggal_pengajuan, alasan_pengajuan, status, catatan_pemohon) VALUES
('PG-TEST-002', 15, 3, 4, CURDATE(), 'Testing pengajuan dari Andi Wijaya. Telah menyelesaikan berbagai project IT penting dan meningkatkan sistem keamanan perusahaan.', 'pending', 'Melampirkan sertifikat keamanan IT, hasil audit sistem, dan dokumentasi project yang telah diselesaikan.')
ON DUPLICATE KEY UPDATE 
    status = 'pending',
    tanggal_pengajuan = CURDATE();

-- Pengajuan dari Fitri Ramadhani (id_pekerja=18) - untuk testing
-- Atasan: Maya Sari (id_pekerja=8, NIP=20140008)
INSERT INTO pengajuan (nomor_pengajuan, id_pekerja, id_golongan_saat_ini, id_golongan_diajukan, tanggal_pengajuan, alasan_pengajuan, status, catatan_pemohon) VALUES
('PG-TEST-003', 18, 1, 2, CURDATE(), 'Testing pengajuan dari Fitri. Konsisten memberikan pelayanan terbaik kepada nasabah dengan rating kepuasan 95%.', 'pending', NULL)
ON DUPLICATE KEY UPDATE 
    status = 'pending',
    tanggal_pengajuan = CURDATE();

-- ============================================
-- VALIDASI DATA TESTING
-- ============================================

-- Cek pengajuan yang baru dibuat
SELECT 
    peng.id_pengajuan,
    peng.nomor_pengajuan,
    peng.status,
    p.nip,
    p.nama_lengkap as nama_pemohon,
    atasan.nip as nip_atasan,
    atasan.nama_lengkap as nama_atasan,
    u_atasan.username as username_atasan,
    u_atasan.role as role_atasan
FROM pengajuan peng
JOIN pekerja p ON peng.id_pekerja = p.id_pekerja
LEFT JOIN pekerja atasan ON p.id_atasan = atasan.id_pekerja
LEFT JOIN users u_atasan ON atasan.id_pekerja = u_atasan.id_pekerja
WHERE peng.nomor_pengajuan LIKE 'PG-TEST-%'
ORDER BY peng.id_pengajuan;

-- ============================================
-- CEK PENDING APPROVAL PER ATASAN
-- ============================================

-- Untuk Maya Sari (id_pekerja=8, username=20140008)
-- Harus muncul: Sari Dewi dan Fitri Ramadhani
SELECT 
    'PENDING UNTUK MAYA SARI (20140008)' as info,
    peng.nomor_pengajuan,
    peng.status,
    p.nip,
    p.nama_lengkap
FROM pengajuan peng
JOIN pekerja p ON peng.id_pekerja = p.id_pekerja
WHERE p.id_atasan = 8
  AND peng.status = 'pending'
ORDER BY peng.tanggal_pengajuan;

-- Untuk Dedi Kurniawan (id_pekerja=9, username=20150009)
-- Harus muncul: Andi Wijaya
SELECT 
    'PENDING UNTUK DEDI KURNIAWAN (20150009)' as info,
    peng.nomor_pengajuan,
    peng.status,
    p.nip,
    p.nama_lengkap
FROM pengajuan peng
JOIN pekerja p ON peng.id_pekerja = p.id_pekerja
WHERE p.id_atasan = 9
  AND peng.status = 'pending'
ORDER BY peng.tanggal_pengajuan;

-- ============================================
-- INSERT DOKUMEN PENDUKUNG (OPTIONAL)
-- ============================================

-- Dokumen untuk pengajuan Sari Dewi (PG-TEST-001)
-- Ambil id_pengajuan terlebih dahulu
SET @id_pengajuan_sari = (SELECT id_pengajuan FROM pengajuan WHERE nomor_pengajuan = 'PG-TEST-001');

INSERT INTO dokumen_pengajuan (id_pengajuan, jenis_dokumen, nama_dokumen, file_path, file_size, mime_type, keterangan) VALUES
(@id_pengajuan_sari, 'surat_permohonan', 'Surat_Permohonan_Test.pdf', '/uploads/documents/test/surat_permohonan_test.pdf', 245678, 'application/pdf', 'Surat permohonan testing'),
(@id_pengajuan_sari, 'penilaian_kinerja', 'Penilaian_Kinerja_Test.pdf', '/uploads/documents/test/penilaian_kinerja_test.pdf', 189456, 'application/pdf', 'Penilaian kinerja testing'),
(@id_pengajuan_sari, 'sertifikat', 'Sertifikat_Test.pdf', '/uploads/documents/test/sertifikat_test.pdf', 523789, 'application/pdf', 'Sertifikat testing')
ON DUPLICATE KEY UPDATE nama_dokumen = nama_dokumen;

-- ============================================
-- TESTING QUERIES
-- ============================================

-- 1. Validasi relasi atasan-bawahan untuk Sari Dewi
SELECT 
    'VALIDASI SARI DEWI' as info,
    p.id_pekerja,
    p.nip,
    p.nama_lengkap,
    p.id_atasan,
    atasan.nip as nip_atasan,
    atasan.nama_lengkap as nama_atasan
FROM pekerja p
LEFT JOIN pekerja atasan ON p.id_atasan = atasan.id_pekerja
WHERE p.nip = '20210014';

-- 2. Cek user dan role Maya Sari
SELECT 
    'VALIDASI MAYA SARI (ATASAN)' as info,
    u.id_user,
    u.username,
    u.role,
    p.id_pekerja,
    p.nip,
    p.nama_lengkap
FROM users u
JOIN pekerja p ON u.id_pekerja = p.id_pekerja
WHERE u.username = '20140008';

-- 3. Test query yang digunakan di getPendingForAtasan
SELECT 
    'QUERY GETPENDINGFORATASAN (id_atasan=8)' as info,
    pen.id_pengajuan,
    pen.nomor_pengajuan,
    pen.status,
    p.nip,
    p.nama_lengkap,
    p.id_atasan,
    g_sekarang.kode_golongan as golongan_sekarang,
    g_tujuan.kode_golongan as golongan_tujuan
FROM pengajuan pen
INNER JOIN pekerja p ON pen.id_pekerja = p.id_pekerja
LEFT JOIN golongan_jabatan g_sekarang ON pen.id_golongan_saat_ini = g_sekarang.id_golongan
LEFT JOIN golongan_jabatan g_tujuan ON pen.id_golongan_diajukan = g_tujuan.id_golongan
WHERE p.id_atasan = 8
  AND pen.status = 'pending'
ORDER BY pen.tanggal_pengajuan ASC;

-- ============================================
-- INSTRUKSI TESTING
-- ============================================

/*
CARA TESTING LENGKAP:

1. JALANKAN FILE SQL INI di phpMyAdmin atau MySQL client
   - Akan membuat 3 pengajuan testing dengan status PENDING

2. LOGIN SEBAGAI MAYA SARI (Atasan)
   Username: 20140008
   Password: password123
   
   Menu: Pending Approval
   HARUS MUNCUL:
   - Pengajuan dari Sari Dewi (PG-TEST-001)
   - Pengajuan dari Fitri Ramadhani (PG-TEST-003)
   
   KLIK REVIEW → APPROVE salah satu (misalnya Sari Dewi)
   Status berubah: pending → disetujui_atasan

3. LOGIN SEBAGAI MANAGER (pilih salah satu)
   Username: 20050002 (Siti Rahmawati)
   Password: password123
   
   Menu: Pending Approval
   HARUS MUNCUL:
   - Pengajuan dari Sari Dewi (yang tadi di-approve Maya Sari)
   
   KLIK REVIEW → APPROVE
   Status berubah: disetujui_atasan → disetujui_manager

4. LOGIN SEBAGAI KEPALA WILAYAH
   Username: 19900001
   Password: password123
   
   Menu: Pending Approval
   HARUS MUNCUL:
   - Pengajuan dari Sari Dewi (yang tadi di-approve Manager)
   
   KLIK REVIEW → APPROVE
   Status berubah: disetujui_manager → disetujui
   Golongan Sari Dewi otomatis update dari I-C → I-D

5. VALIDASI HASIL
   - Cek golongan Sari Dewi di database harus berubah
   - Cek approval_history harus ada 3 record (atasan, manager, kepala_wilayah)
   - Status pengajuan harus 'disetujui'

QUERY VALIDASI HASIL:
*/

-- Cek golongan Sari Dewi setelah disetujui
SELECT 
    p.nip,
    p.nama_lengkap,
    gol.kode_golongan,
    gol.nama_golongan
FROM pekerja p
JOIN golongan_jabatan gol ON p.id_golongan_saat_ini = gol.id_golongan
WHERE p.nip = '20210014';

-- Cek approval history untuk pengajuan Sari Dewi
SELECT 
    peng.nomor_pengajuan,
    peng.status as status_pengajuan,
    ah.level_approval,
    approver.nama_lengkap as nama_approver,
    ah.keputusan,
    ah.catatan,
    ah.tanggal_approval
FROM pengajuan peng
LEFT JOIN approval_history ah ON peng.id_pengajuan = ah.id_pengajuan
LEFT JOIN users u ON ah.id_approver = u.id_user
LEFT JOIN pekerja approver ON u.id_pekerja = approver.id_pekerja
WHERE peng.nomor_pengajuan = 'PG-TEST-001'
ORDER BY ah.tanggal_approval;
