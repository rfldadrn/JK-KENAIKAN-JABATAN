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
        $this->requireRole('admin');
        
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
            'pageTitle' => 'Laporan Data Pekerja',
            'currentPage' => 'laporan-pekerja',
            'pekerja' => $pekerja
        ];

        $this->view('layouts/header', $data);
        $this->view('layouts/sidebar', $data);
        $this->view('laporan/pekerja', $data);
        $this->view('layouts/footer', $data);
    }
}
