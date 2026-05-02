<?php
/**
 * DashboardController.php - Dashboard Controller
 * Displays dashboard based on user role
 */

class DashboardController extends Controller
{
    public function __construct()
    {
        // Check if user is logged in
        $this->requireLogin();
    }

    /**
     * Main dashboard index
     * Routes to appropriate dashboard based on role
     */
    public function index()
    {
        $role = Session::getRole();
        
        switch ($role) {
            case 'admin':
                $this->adminDashboard();
                break;
            case 'pekerja':
                $this->pekerjaDashboard();
                break;
            case 'atasan':
                $this->atasanDashboard();
                break;
            case 'manager':
                $this->managerDashboard();
                break;
            case 'kepala_wilayah':
                $this->kepalaWilayahDashboard();
                break;
            default:
                $this->setFlash('error', 'Role tidak valid');
                $this->redirect('auth/logout');
        }
    }

    /**
     * Admin Dashboard
     */
    private function adminDashboard()
    {
        $db = Database::getInstance()->getConnection();
        
        // Get statistics
        $stats = [];
        
        // Total pekerja
        $stmt = $db->query("SELECT COUNT(*) as total FROM pekerja WHERE status_kepegawaian = 'aktif'");
        $stats['total_pekerja'] = $stmt->fetch(PDO::FETCH_OBJ)->total;
        
        // Total pengajuan
        $stmt = $db->query("SELECT COUNT(*) as total FROM pengajuan");
        $stats['total_pengajuan'] = $stmt->fetch(PDO::FETCH_OBJ)->total;
        
        // Pending pengajuan
        $stmt = $db->query("SELECT COUNT(*) as total FROM pengajuan WHERE status = 'pending'");
        $stats['pending_pengajuan'] = $stmt->fetch(PDO::FETCH_OBJ)->total;
        
        // Approved pengajuan this year
        $stmt = $db->query("SELECT COUNT(*) as total FROM pengajuan 
                            WHERE status = 'disetujui' AND YEAR(tanggal_pengajuan) = YEAR(CURDATE())");
        $stats['approved_tahun_ini'] = $stmt->fetch(PDO::FETCH_OBJ)->total;
        
        // Recent pengajuan
        $stmt = $db->query("SELECT p.*, pk.nama_lengkap, pk.nip,
                            g1.kode_golongan as golongan_saat_ini,
                            g2.kode_golongan as golongan_diajukan
                            FROM pengajuan p
                            JOIN pekerja pk ON p.id_pekerja = pk.id_pekerja
                            LEFT JOIN golongan_jabatan g1 ON p.id_golongan_saat_ini = g1.id_golongan
                            LEFT JOIN golongan_jabatan g2 ON p.id_golongan_diajukan = g2.id_golongan
                            ORDER BY p.tanggal_pengajuan DESC LIMIT 5");
        $recentPengajuan = $stmt->fetchAll(PDO::FETCH_OBJ);
        
        // Pengajuan by status
        $stmt = $db->query("SELECT status, COUNT(*) as total FROM pengajuan GROUP BY status");
        $pengajuanByStatus = $stmt->fetchAll(PDO::FETCH_OBJ);
        
        // Pekerja by golongan
        $stmt = $db->query("SELECT g.kode_golongan, g.nama_golongan, COUNT(p.id_pekerja) as total
                            FROM golongan_jabatan g
                            LEFT JOIN pekerja p ON g.id_golongan = p.id_golongan_saat_ini AND p.status_kepegawaian = 'aktif'
                            GROUP BY g.id_golongan
                            ORDER BY g.level, g.sub_level");
        $pekerjaByGolongan = $stmt->fetchAll(PDO::FETCH_OBJ);
        
        $data = [
            'pageTitle' => 'Dashboard Admin',
            'currentPage' => 'dashboard',
            'stats' => $stats,
            'recentPengajuan' => $recentPengajuan,
            'pengajuanByStatus' => $pengajuanByStatus,
            'pekerjaByGolongan' => $pekerjaByGolongan
        ];
        
        $this->view('layouts/header', $data);
        $this->view('layouts/sidebar', $data);
        $this->view('dashboard/admin', $data);
        $this->view('layouts/footer', $data);
    }

    /**
     * Pekerja Dashboard
     */
    private function pekerjaDashboard()
    {
        $db = Database::getInstance()->getConnection();
        $idPekerja = Session::getPekerjaId();
        
        // Get pekerja detail
        $stmt = $db->prepare("SELECT p.*, d.nama_divisi, j.nama_jabatan,
                              g.kode_golongan, g.nama_golongan, g.level, g.sub_level,
                              TIMESTAMPDIFF(YEAR, p.tanggal_bergabung, CURDATE()) as masa_kerja_tahun,
                              TIMESTAMPDIFF(MONTH, p.tanggal_bergabung, CURDATE()) % 12 as masa_kerja_bulan
                              FROM pekerja p
                              LEFT JOIN divisi d ON p.id_divisi = d.id_divisi
                              LEFT JOIN jabatan j ON p.id_jabatan = j.id_jabatan
                              LEFT JOIN golongan_jabatan g ON p.id_golongan_saat_ini = g.id_golongan
                              WHERE p.id_pekerja = :id_pekerja");
        $stmt->execute([':id_pekerja' => $idPekerja]);
        $pekerjaDetail = $stmt->fetch(PDO::FETCH_OBJ);
        
        // Get pengajuan stats
        $stmt = $db->prepare("SELECT COUNT(*) as total FROM pengajuan WHERE id_pekerja = :id_pekerja");
        $stmt->execute([':id_pekerja' => $idPekerja]);
        $totalPengajuan = $stmt->fetch(PDO::FETCH_OBJ)->total;
        
        $stmt = $db->prepare("SELECT COUNT(*) as total FROM pengajuan 
                              WHERE id_pekerja = :id_pekerja AND status IN ('pending', 'disetujui_atasan', 'disetujui_manager')");
        $stmt->execute([':id_pekerja' => $idPekerja]);
        $pengajuanAktif = $stmt->fetch(PDO::FETCH_OBJ)->total;
        
        $stmt = $db->prepare("SELECT COUNT(*) as total FROM pengajuan 
                              WHERE id_pekerja = :id_pekerja AND status = 'disetujui'");
        $stmt->execute([':id_pekerja' => $idPekerja]);
        $pengajuanDisetujui = $stmt->fetch(PDO::FETCH_OBJ)->total;
        
        // Get recent pengajuan
        $stmt = $db->prepare("SELECT p.*, g1.kode_golongan as golongan_saat_ini,
                              g2.kode_golongan as golongan_diajukan
                              FROM pengajuan p
                              LEFT JOIN golongan_jabatan g1 ON p.id_golongan_saat_ini = g1.id_golongan
                              LEFT JOIN golongan_jabatan g2 ON p.id_golongan_diajukan = g2.id_golongan
                              WHERE p.id_pekerja = :id_pekerja
                              ORDER BY p.tanggal_pengajuan DESC LIMIT 5");
        $stmt->execute([':id_pekerja' => $idPekerja]);
        $recentPengajuan = $stmt->fetchAll(PDO::FETCH_OBJ);
        
        $data = [
            'pageTitle' => 'Dashboard Pekerja',
            'currentPage' => 'dashboard',
            'pekerjaDetail' => $pekerjaDetail,
            'totalPengajuan' => $totalPengajuan,
            'pengajuanAktif' => $pengajuanAktif,
            'pengajuanDisetujui' => $pengajuanDisetujui,
            'recentPengajuan' => $recentPengajuan
        ];
        
        $this->view('layouts/header', $data);
        $this->view('layouts/sidebar', $data);
        $this->view('dashboard/pekerja', $data);
        $this->view('layouts/footer', $data);
    }

    /**
     * Atasan Dashboard
     */
    private function atasanDashboard()
    {
        $db = Database::getInstance()->getConnection();
        $idPekerja = Session::getPekerjaId();
        
        // Get pending approval count
        $stmt = $db->prepare("SELECT COUNT(*) as total FROM pengajuan p
                              JOIN pekerja pk ON p.id_pekerja = pk.id_pekerja
                              WHERE p.status = 'pending' AND pk.id_atasan = :id_atasan");
        $stmt->execute([':id_atasan' => $idPekerja]);
        $pendingApproval = $stmt->fetch(PDO::FETCH_OBJ)->total;
        
        // Get total bawahan
        $stmt = $db->prepare("SELECT COUNT(*) as total FROM pekerja 
                              WHERE id_atasan = :id_atasan AND status_kepegawaian = 'aktif'");
        $stmt->execute([':id_atasan' => $idPekerja]);
        $totalBawahan = $stmt->fetch(PDO::FETCH_OBJ)->total;
        
        // Get approved count this year
        $stmt = $db->prepare("SELECT COUNT(*) as total FROM approval_history ah
                              WHERE ah.id_approver = (SELECT id_user FROM users WHERE id_pekerja = :id_pekerja)
                              AND ah.keputusan = 'approved'
                              AND YEAR(ah.tanggal_approval) = YEAR(CURDATE())");
        $stmt->execute([':id_pekerja' => $idPekerja]);
        $approvedTahunIni = $stmt->fetch(PDO::FETCH_OBJ)->total;
        
        // Get pending pengajuan
        $stmt = $db->prepare("SELECT p.*, pk.nama_lengkap, pk.nip,
                              g1.kode_golongan as golongan_saat_ini,
                              g2.kode_golongan as golongan_diajukan
                              FROM pengajuan p
                              JOIN pekerja pk ON p.id_pekerja = pk.id_pekerja
                              LEFT JOIN golongan_jabatan g1 ON p.id_golongan_saat_ini = g1.id_golongan
                              LEFT JOIN golongan_jabatan g2 ON p.id_golongan_diajukan = g2.id_golongan
                              WHERE p.status = 'pending' AND pk.id_atasan = :id_atasan
                              ORDER BY p.tanggal_pengajuan ASC");
        $stmt->execute([':id_atasan' => $idPekerja]);
        $pendingPengajuan = $stmt->fetchAll(PDO::FETCH_OBJ);
        
        $data = [
            'pageTitle' => 'Dashboard Atasan',
            'currentPage' => 'dashboard',
            'pendingApproval' => $pendingApproval,
            'totalBawahan' => $totalBawahan,
            'approvedTahunIni' => $approvedTahunIni,
            'pendingPengajuan' => $pendingPengajuan,
            'pendingCount' => $pendingApproval
        ];
        
        $this->view('layouts/header', $data);
        $this->view('layouts/sidebar', $data);
        $this->view('dashboard/atasan', $data);
        $this->view('layouts/footer', $data);
    }

    /**
     * Manager Dashboard
     */
    private function managerDashboard()
    {
        $db = Database::getInstance()->getConnection();
        
        // Get pending approval (level 2)
        $stmt = $db->query("SELECT COUNT(*) as total FROM pengajuan WHERE status = 'disetujui_atasan'");
        $pendingApproval = $stmt->fetch(PDO::FETCH_OBJ)->total;
        
        // Get approved count this year
        $stmt = $db->prepare("SELECT COUNT(*) as total FROM approval_history ah
                              WHERE ah.id_approver = :id_user
                              AND ah.keputusan = 'approved'
                              AND YEAR(ah.tanggal_approval) = YEAR(CURDATE())");
        $stmt->execute([':id_user' => Session::getUserId()]);
        $approvedTahunIni = $stmt->fetch(PDO::FETCH_OBJ)->total;
        
        // Get pending pengajuan
        $stmt = $db->query("SELECT p.*, pk.nama_lengkap, pk.nip,
                           g1.kode_golongan as golongan_saat_ini,
                           g2.kode_golongan as golongan_diajukan,
                           d.nama_divisi
                           FROM pengajuan p
                           JOIN pekerja pk ON p.id_pekerja = pk.id_pekerja
                           LEFT JOIN divisi d ON pk.id_divisi = d.id_divisi
                           LEFT JOIN golongan_jabatan g1 ON p.id_golongan_saat_ini = g1.id_golongan
                           LEFT JOIN golongan_jabatan g2 ON p.id_golongan_diajukan = g2.id_golongan
                           WHERE p.status = 'disetujui_atasan'
                           ORDER BY p.tanggal_pengajuan ASC");
        $pendingPengajuan = $stmt->fetchAll(PDO::FETCH_OBJ);
        
        $data = [
            'pageTitle' => 'Dashboard Manager',
            'currentPage' => 'dashboard',
            'pendingApproval' => $pendingApproval,
            'approvedTahunIni' => $approvedTahunIni,
            'pendingPengajuan' => $pendingPengajuan,
            'pendingCount' => $pendingApproval
        ];
        
        $this->view('layouts/header', $data);
        $this->view('layouts/sidebar', $data);
        $this->view('dashboard/manager', $data);
        $this->view('layouts/footer', $data);
    }

    /**
     * Kepala Wilayah Dashboard
     */
    private function kepalaWilayahDashboard()
    {
        $db = Database::getInstance()->getConnection();
        
        // Get pending approval (level 3)
        $stmt = $db->query("SELECT COUNT(*) as total FROM pengajuan WHERE status = 'disetujui_manager'");
        $pendingApproval = $stmt->fetch(PDO::FETCH_OBJ)->total;
        
        // Get total approved
        $stmt = $db->prepare("SELECT COUNT(*) as total FROM approval_history ah
                              WHERE ah.id_approver = :id_user
                              AND ah.keputusan = 'approved'
                              AND YEAR(ah.tanggal_approval) = YEAR(CURDATE())");
        $stmt->execute([':id_user' => Session::getUserId()]);
        $approvedTahunIni = $stmt->fetch(PDO::FETCH_OBJ)->total;
        
        // Total pengajuan this year
        $stmt = $db->query("SELECT COUNT(*) as total FROM pengajuan 
                           WHERE YEAR(tanggal_pengajuan) = YEAR(CURDATE())");
        $totalPengajuanTahunIni = $stmt->fetch(PDO::FETCH_OBJ)->total;
        
        // Get pending pengajuan
        $stmt = $db->query("SELECT p.*, pk.nama_lengkap, pk.nip,
                           g1.kode_golongan as golongan_saat_ini,
                           g2.kode_golongan as golongan_diajukan,
                           d.nama_divisi
                           FROM pengajuan p
                           JOIN pekerja pk ON p.id_pekerja = pk.id_pekerja
                           LEFT JOIN divisi d ON pk.id_divisi = d.id_divisi
                           LEFT JOIN golongan_jabatan g1 ON p.id_golongan_saat_ini = g1.id_golongan
                           LEFT JOIN golongan_jabatan g2 ON p.id_golongan_diajukan = g2.id_golongan
                           WHERE p.status = 'disetujui_manager'
                           ORDER BY p.tanggal_pengajuan ASC");
        $pendingPengajuan = $stmt->fetchAll(PDO::FETCH_OBJ);
        
        $data = [
            'pageTitle' => 'Dashboard Kepala Wilayah',
            'currentPage' => 'dashboard',
            'pendingApproval' => $pendingApproval,
            'approvedTahunIni' => $approvedTahunIni,
            'totalPengajuanTahunIni' => $totalPengajuanTahunIni,
            'pendingPengajuan' => $pendingPengajuan,
            'pendingCount' => $pendingApproval
        ];
        
        $this->view('layouts/header', $data);
        $this->view('layouts/sidebar', $data);
        $this->view('dashboard/kepala_wilayah', $data);
        $this->view('layouts/footer', $data);
    }
}
