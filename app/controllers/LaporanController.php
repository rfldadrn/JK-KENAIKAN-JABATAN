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

    /**
     * Render standalone print view (without dashboard layout)
     */
    private function renderPrintView($view, $data = [])
    {
        extract($data);

        $viewFile = '../app/views/' . $view . '.php';
        if (file_exists($viewFile)) {
            require $viewFile;
            exit;
        }

        die('View not found: ' . $view);
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

    /**
     * Print report: Employee data
     */
    public function cetakDataKaryawan()
    {
        $this->requireRole('admin');

        $data = [
            'pageTitle' => 'Cetak Laporan Data Karyawan',
            'printedAt' => date('Y-m-d H:i:s'),
            'pekerja' => $this->pekerjaModel->getAllWithDetails()
        ];

        $this->renderPrintView('laporan/cetak_data_karyawan', $data);
    }

    /**
     * Print report: Employee performance
     */
    public function cetakKinerjaKaryawan()
    {
        $this->requireRole('admin');

        $kinerja = $this->pekerjaModel->getKinerjaLaporan();

        foreach ($kinerja as $item) {
            $nilai = (float) ($item->nilai_kinerja_terakhir ?? 0);
            if ($nilai >= 90) {
                $item->kategori_kinerja = 'Sangat Baik';
            } elseif ($nilai >= 80) {
                $item->kategori_kinerja = 'Baik';
            } elseif ($nilai >= 70) {
                $item->kategori_kinerja = 'Cukup';
            } elseif ($nilai > 0) {
                $item->kategori_kinerja = 'Perlu Pembinaan';
            } else {
                $item->kategori_kinerja = 'Belum Dinilai';
            }
        }

        $data = [
            'pageTitle' => 'Cetak Laporan Kinerja Karyawan',
            'printedAt' => date('Y-m-d H:i:s'),
            'kinerja' => $kinerja
        ];

        $this->renderPrintView('laporan/cetak_kinerja_karyawan', $data);
    }

    /**
     * Print report: Promotion submission history
     */
    public function cetakRiwayatPengajuan()
    {
        $role = Session::get('role');
        if (!in_array($role, ['admin', 'kepala_wilayah'])) {
            $this->setFlash('error', 'Anda tidak memiliki akses ke halaman ini');
            $this->redirect('dashboard');
            return;
        }

        $data = [
            'pageTitle' => 'Cetak Laporan Riwayat Pengajuan',
            'printedAt' => date('Y-m-d H:i:s'),
            'riwayatPengajuan' => $this->pengajuanModel->getRiwayatLaporan()
        ];

        $this->renderPrintView('laporan/cetak_riwayat_pengajuan', $data);
    }

    /**
     * Print report: Approved promotion results
     */
    public function cetakHasilKenaikanJabatan()
    {
        $role = Session::get('role');
        if (!in_array($role, ['admin', 'kepala_wilayah'])) {
            $this->setFlash('error', 'Anda tidak memiliki akses ke halaman ini');
            $this->redirect('dashboard');
            return;
        }

        $data = [
            'pageTitle' => 'Cetak Laporan Hasil Kenaikan Jabatan',
            'printedAt' => date('Y-m-d H:i:s'),
            'hasilKenaikan' => $this->pengajuanModel->getHasilKenaikanLaporan()
        ];

        $this->renderPrintView('laporan/cetak_hasil_kenaikan_jabatan', $data);
    }
}
