<?php
/**
 * LaporanController.php - Laporan Controller
 */

class LaporanController extends Controller
{
    private $pengajuanModel;
    private $pekerjaModel;

    public function __construct()
    {
        $this->requireLogin();
        $this->pengajuanModel = $this->model('Pengajuan');
        $this->pekerjaModel = $this->model('Pekerja');
    }

    public function pengajuan()
    {
        // Allow admin and kepala_wilayah
        $role = Session::get('role');
        if (!in_array($role, ['admin', 'kepala_wilayah'])) {
            $this->setFlash('error', 'Anda tidak memiliki akses ke halaman ini');
            $this->redirect('dashboard');
            return;
        }
        
        $pengajuan = $this->pengajuanModel->getAllWithDetails();
        $stats = $this->pengajuanModel->getStatistics();

        $data = [
            'pageTitle' => 'Laporan Pengajuan',
            'currentPage' => 'laporan',
            'pengajuan' => $pengajuan,
            'stats' => $stats
        ];

        $this->view('layouts/header', $data);
        $this->view('layouts/sidebar', $data);
        $this->view('laporan/pengajuan', $data);
        $this->view('layouts/footer', $data);
    }

    public function pekerja()
    {
        $this->requireRole('admin');
        
        $pekerja = $this->pekerjaModel->getAllWithDetails();

        $data = [
            'pageTitle' => 'Laporan Data Karyawan',
            'currentPage' => 'laporan-pekerja',
            'pekerja' => $pekerja
        ];

        $this->view('layouts/header', $data);
        $this->view('layouts/sidebar', $data);
        $this->view('laporan/pekerja', $data);
        $this->view('layouts/footer', $data);
    }
}
