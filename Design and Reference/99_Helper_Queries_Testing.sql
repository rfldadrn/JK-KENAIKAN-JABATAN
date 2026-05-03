-- ============================================
-- HELPER QUERIES - Testing & Debugging
-- Sistem Kenaikan Golongan BRI
-- ============================================

USE sistem_kenaikan_golongan_bri;

-- ============================================
-- 1. CEK STRUKTUR ORGANISASI LENGKAP
-- ============================================

SELECT 
    p.id_pekerja,
    p.nip,
    p.nama_lengkap,
    u.role,
    p.status_kepegawaian,
    CONCAT(gol.kode_golongan, ' - ', gol.nama_golongan) as golongan,
    j.nama_jabatan,
    d.nama_divisi,
    p.id_atasan,
    atasan.nip as nip_atasan,
    atasan.nama_lengkap as nama_atasan,
    p.nilai_kinerja_terakhir
FROM pekerja p
LEFT JOIN users u ON p.id_pekerja = u.id_pekerja
LEFT JOIN golongan_jabatan gol ON p.id_golongan_saat_ini = gol.id_golongan
LEFT JOIN jabatan j ON p.id_jabatan = j.id_jabatan
LEFT JOIN divisi d ON p.id_divisi = d.id_divisi
LEFT JOIN pekerja atasan ON p.id_atasan = atasan.id_pekerja
WHERE p.status_kepegawaian = 'aktif'
ORDER BY p.id_pekerja;

-- ============================================
-- 2. CEK BAWAHAN PER ATASAN
-- ============================================

-- Bawahan Maya Sari (Supervisor CS) - id_pekerja=8
SELECT 
    'Maya Sari (20140008)' as atasan,
    p.nip,
    p.nama_lengkap as bawahan,
    gol.kode_golongan,
    j.nama_jabatan
FROM pekerja p
LEFT JOIN golongan_jabatan gol ON p.id_golongan_saat_ini = gol.id_golongan
LEFT JOIN jabatan j ON p.id_jabatan = j.id_jabatan
WHERE p.id_atasan = 8
  AND p.status_kepegawaian = 'aktif';

-- Bawahan Dedi Kurniawan (Supervisor IT) - id_pekerja=9
SELECT 
    'Dedi Kurniawan (20150009)' as atasan,
    p.nip,
    p.nama_lengkap as bawahan,
    gol.kode_golongan,
    j.nama_jabatan
FROM pekerja p
LEFT JOIN golongan_jabatan gol ON p.id_golongan_saat_ini = gol.id_golongan
LEFT JOIN jabatan j ON p.id_jabatan = j.id_jabatan
WHERE p.id_atasan = 9
  AND p.status_kepegawaian = 'aktif';

-- Bawahan Fadli Rahman (Supervisor Marketing) - id_pekerja=7
SELECT 
    'Fadli Rahman (20130007)' as atasan,
    p.nip,
    p.nama_lengkap as bawahan,
    gol.kode_golongan,
    j.nama_jabatan
FROM pekerja p
LEFT JOIN golongan_jabatan gol ON p.id_golongan_saat_ini = gol.id_golongan
LEFT JOIN jabatan j ON p.id_jabatan = j.id_jabatan
WHERE p.id_atasan = 7
  AND p.status_kepegawaian = 'aktif';

-- Bawahan Rina Marlina (Supervisor Operasional) - id_pekerja=6
SELECT 
    'Rina Marlina (20120006)' as atasan,
    p.nip,
    p.nama_lengkap as bawahan,
    gol.kode_golongan,
    j.nama_jabatan
FROM pekerja p
LEFT JOIN golongan_jabatan gol ON p.id_golongan_saat_ini = gol.id_golongan
LEFT JOIN jabatan j ON p.id_jabatan = j.id_jabatan
WHERE p.id_atasan = 6
  AND p.status_kepegawaian = 'aktif';

-- ============================================
-- 3. CEK ALUR APPROVAL PENGAJUAN TERTENTU
-- ============================================

-- Ganti <NOMOR_PENGAJUAN> dengan nomor pengajuan yang ingin dicek
SELECT 
    peng.nomor_pengajuan,
    peng.status,
    pekerja.nip as nip_pemohon,
    pekerja.nama_lengkap as nama_pemohon,
    atasan.nip as nip_atasan,
    atasan.nama_lengkap as nama_atasan,
    'Level 1' as approval_level,
    'Atasan Langsung' as role_approval
FROM pengajuan peng
JOIN pekerja ON peng.id_pekerja = pekerja.id_pekerja
LEFT JOIN pekerja atasan ON pekerja.id_atasan = atasan.id_pekerja
WHERE peng.nomor_pengajuan = '<NOMOR_PENGAJUAN>';

-- ============================================
-- 4. CEK PENDING APPROVAL UNTUK ATASAN TERTENTU
-- ============================================

-- Ganti <ID_PEKERJA_ATASAN> dengan id_pekerja atasan yang login
-- Contoh: 8 untuk Maya Sari
SELECT 
    peng.id_pengajuan,
    peng.nomor_pengajuan,
    peng.tanggal_pengajuan,
    peng.status,
    pekerja.nip,
    pekerja.nama_lengkap,
    gol_sekarang.kode_golongan as golongan_sekarang,
    gol_tujuan.kode_golongan as golongan_tujuan
FROM pengajuan peng
JOIN pekerja ON peng.id_pekerja = pekerja.id_pekerja
LEFT JOIN golongan_jabatan gol_sekarang ON peng.id_golongan_saat_ini = gol_sekarang.id_golongan
LEFT JOIN golongan_jabatan gol_tujuan ON peng.id_golongan_diajukan = gol_tujuan.id_golongan
WHERE pekerja.id_atasan = 8  -- Ganti dengan id_pekerja atasan
  AND peng.status = 'pending'
ORDER BY peng.tanggal_pengajuan ASC;

-- ============================================
-- 5. CEK PENDING APPROVAL UNTUK MANAGER
-- ============================================

SELECT 
    peng.id_pengajuan,
    peng.nomor_pengajuan,
    peng.tanggal_pengajuan,
    peng.status,
    pekerja.nip,
    pekerja.nama_lengkap,
    gol_sekarang.kode_golongan as golongan_sekarang,
    gol_tujuan.kode_golongan as golongan_tujuan,
    atasan.nama_lengkap as approved_by_atasan
FROM pengajuan peng
JOIN pekerja ON peng.id_pekerja = pekerja.id_pekerja
LEFT JOIN golongan_jabatan gol_sekarang ON peng.id_golongan_saat_ini = gol_sekarang.id_golongan
LEFT JOIN golongan_jabatan gol_tujuan ON peng.id_golongan_diajukan = gol_tujuan.id_golongan
LEFT JOIN pekerja atasan ON pekerja.id_atasan = atasan.id_pekerja
WHERE peng.status = 'disetujui_atasan'
ORDER BY peng.tanggal_pengajuan ASC;

-- ============================================
-- 6. CEK PENDING APPROVAL UNTUK KEPALA WILAYAH
-- ============================================

SELECT 
    peng.id_pengajuan,
    peng.nomor_pengajuan,
    peng.tanggal_pengajuan,
    peng.status,
    pekerja.nip,
    pekerja.nama_lengkap,
    divisi.nama_divisi,
    gol_sekarang.kode_golongan as golongan_sekarang,
    gol_tujuan.kode_golongan as golongan_tujuan
FROM pengajuan peng
JOIN pekerja ON peng.id_pekerja = pekerja.id_pekerja
LEFT JOIN divisi ON pekerja.id_divisi = divisi.id_divisi
LEFT JOIN golongan_jabatan gol_sekarang ON peng.id_golongan_saat_ini = gol_sekarang.id_golongan
LEFT JOIN golongan_jabatan gol_tujuan ON peng.id_golongan_diajukan = gol_tujuan.id_golongan
WHERE peng.status = 'disetujui_manager'
ORDER BY peng.tanggal_pengajuan ASC;

-- ============================================
-- 7. CEK RIWAYAT APPROVAL LENGKAP
-- ============================================

SELECT 
    peng.nomor_pengajuan,
    pekerja.nama_lengkap as pemohon,
    ah.level_approval,
    approver.nama_lengkap as approver,
    ah.keputusan,
    ah.catatan,
    ah.tanggal_approval,
    peng.status as status_terakhir
FROM pengajuan peng
JOIN pekerja ON peng.id_pekerja = pekerja.id_pekerja
LEFT JOIN approval_history ah ON peng.id_pengajuan = ah.id_pengajuan
LEFT JOIN users u ON ah.id_approver = u.id_user
LEFT JOIN pekerja approver ON u.id_pekerja = approver.id_pekerja
ORDER BY peng.nomor_pengajuan, ah.tanggal_approval;

-- ============================================
-- 8. CEK STATUS SEMUA PENGAJUAN
-- ============================================

SELECT 
    peng.id_pengajuan,
    peng.nomor_pengajuan,
    peng.tanggal_pengajuan,
    pekerja.nip,
    pekerja.nama_lengkap,
    peng.status,
    CASE 
        WHEN peng.status = 'pending' THEN 'Menunggu Atasan Langsung'
        WHEN peng.status = 'disetujui_atasan' THEN 'Menunggu Manager'
        WHEN peng.status = 'disetujui_manager' THEN 'Menunggu Kepala Wilayah'
        WHEN peng.status = 'disetujui' THEN 'Selesai - Disetujui'
        WHEN peng.status LIKE 'ditolak%' THEN 'Ditolak'
        ELSE peng.status
    END as keterangan_status,
    atasan.nama_lengkap as atasan_langsung
FROM pengajuan peng
JOIN pekerja ON peng.id_pekerja = pekerja.id_pekerja
LEFT JOIN pekerja atasan ON pekerja.id_atasan = atasan.id_pekerja
ORDER BY peng.tanggal_pengajuan DESC;

-- ============================================
-- 9. CEK HIERARKI ORGANISASI (TREE)
-- ============================================

-- Level 1: Kepala Wilayah
SELECT 
    '1. Kepala Wilayah' as level,
    p.nip,
    p.nama_lengkap,
    u.role,
    gol.kode_golongan
FROM pekerja p
LEFT JOIN users u ON p.id_pekerja = u.id_pekerja
LEFT JOIN golongan_jabatan gol ON p.id_golongan_saat_ini = gol.id_golongan
WHERE u.role = 'kepala_wilayah'

UNION ALL

-- Level 2: Manager
SELECT 
    '2. Manager' as level,
    p.nip,
    p.nama_lengkap,
    u.role,
    gol.kode_golongan
FROM pekerja p
LEFT JOIN users u ON p.id_pekerja = u.id_pekerja
LEFT JOIN golongan_jabatan gol ON p.id_golongan_saat_ini = gol.id_golongan
WHERE u.role = 'manager'

UNION ALL

-- Level 3: Atasan (Supervisor)
SELECT 
    '3. Supervisor/Atasan' as level,
    p.nip,
    p.nama_lengkap,
    u.role,
    gol.kode_golongan
FROM pekerja p
LEFT JOIN users u ON p.id_pekerja = u.id_pekerja
LEFT JOIN golongan_jabatan gol ON p.id_golongan_saat_ini = gol.id_golongan
WHERE u.role = 'atasan'

UNION ALL

-- Level 4: Pekerja
SELECT 
    '4. Pekerja/Staff' as level,
    p.nip,
    p.nama_lengkap,
    u.role,
    gol.kode_golongan
FROM pekerja p
LEFT JOIN users u ON p.id_pekerja = u.id_pekerja
LEFT JOIN golongan_jabatan gol ON p.id_golongan_saat_ini = gol.id_golongan
WHERE u.role = 'pekerja'

ORDER BY level, nip;

-- ============================================
-- 10. CEK USER LOGIN & ROLE
-- ============================================

SELECT 
    u.id_user,
    u.username,
    u.email,
    u.role,
    u.is_active,
    u.last_login,
    p.nip,
    p.nama_lengkap,
    p.status_kepegawaian
FROM users u
LEFT JOIN pekerja p ON u.id_pekerja = p.id_pekerja
ORDER BY 
    CASE u.role
        WHEN 'kepala_wilayah' THEN 1
        WHEN 'manager' THEN 2
        WHEN 'atasan' THEN 3
        WHEN 'pekerja' THEN 4
        WHEN 'admin' THEN 0
    END,
    u.username;

-- ============================================
-- 11. VALIDASI: CEK PEKERJA TANPA ATASAN
-- ============================================

SELECT 
    p.nip,
    p.nama_lengkap,
    u.role,
    p.id_atasan
FROM pekerja p
LEFT JOIN users u ON p.id_pekerja = u.id_pekerja
WHERE p.id_atasan IS NULL
  AND u.role NOT IN ('kepala_wilayah', 'admin');
-- Jika ada data, berarti ada pekerja yang tidak punya atasan (HARUS DIPERBAIKI)

-- ============================================
-- 12. VALIDASI: CEK ATASAN YANG TIDAK VALID
-- ============================================

SELECT 
    p.nip,
    p.nama_lengkap,
    p.id_atasan,
    'Atasan tidak ditemukan di database' as error
FROM pekerja p
LEFT JOIN pekerja atasan ON p.id_atasan = atasan.id_pekerja
WHERE p.id_atasan IS NOT NULL
  AND atasan.id_pekerja IS NULL;
-- Jika ada data, berarti ada id_atasan yang tidak valid

-- ============================================
-- 13. CEK PENGAJUAN SARI DEWI (TESTING USER)
-- ============================================

SELECT 
    peng.id_pengajuan,
    peng.nomor_pengajuan,
    peng.tanggal_pengajuan,
    peng.status,
    gol_sekarang.kode_golongan as dari_golongan,
    gol_tujuan.kode_golongan as ke_golongan,
    peng.alasan_pengajuan,
    atasan.nip as nip_atasan,
    atasan.nama_lengkap as nama_atasan
FROM pengajuan peng
JOIN pekerja ON peng.id_pekerja = pekerja.id_pekerja
LEFT JOIN golongan_jabatan gol_sekarang ON peng.id_golongan_saat_ini = gol_sekarang.id_golongan
LEFT JOIN golongan_jabatan gol_tujuan ON peng.id_golongan_diajukan = gol_tujuan.id_golongan
LEFT JOIN pekerja atasan ON pekerja.id_atasan = atasan.id_pekerja
WHERE pekerja.nip = '20210014'
ORDER BY peng.tanggal_pengajuan DESC;

-- ============================================
-- 14. STATISTIK PENGAJUAN
-- ============================================

SELECT 
    peng.status,
    COUNT(*) as jumlah,
    CONCAT(ROUND(COUNT(*) * 100.0 / (SELECT COUNT(*) FROM pengajuan), 2), '%') as persentase
FROM pengajuan peng
GROUP BY peng.status
ORDER BY jumlah DESC;

-- ============================================
-- 15. TOP 5 ATASAN DENGAN BAWAHAN TERBANYAK
-- ============================================

SELECT 
    atasan.nip,
    atasan.nama_lengkap as nama_atasan,
    u.role,
    COUNT(bawahan.id_pekerja) as jumlah_bawahan,
    GROUP_CONCAT(bawahan.nama_lengkap SEPARATOR ', ') as daftar_bawahan
FROM pekerja atasan
LEFT JOIN users u ON atasan.id_pekerja = u.id_pekerja
LEFT JOIN pekerja bawahan ON atasan.id_pekerja = bawahan.id_atasan
WHERE bawahan.status_kepegawaian = 'aktif'
GROUP BY atasan.id_pekerja
ORDER BY jumlah_bawahan DESC
LIMIT 5;

-- ============================================
-- END OF HELPER QUERIES
-- ============================================

-- CARA MENGGUNAKAN:
-- 1. Copy query yang dibutuhkan
-- 2. Jalankan di phpMyAdmin atau MySQL client
-- 3. Lihat hasilnya untuk debugging/testing
