<?php
/**
 * PengajuanController.php - Pengajuan Controller
 */

class PengajuanController extends Controller
{
    private $pengajuanModel;
    private $pekerjaModel;
    private $golonganModel;
    private $dokumenModel;

    public function __construct()
    {
        $this->requireLogin();
        $this->pengajuanModel = $this->model('Pengajuan');
        $this->pekerjaModel = $this->model('Pekerja');
        $this->golonganModel = $this->model('GolonganJabatan');
        $this->dokumenModel = $this->model('DokumenPengajuan');
    }

    public function index()
    {
        $role = Session::get('role');
        
        if ($role === 'admin') {
            $pengajuan = $this->pengajuanModel->getAllWithDetails();
        } else {
            $id_pekerja = Session::get('id_pekerja');
            $pengajuan = $this->pengajuanModel->getByPekerja($id_pekerja);
        }

        $data = [
            'pageTitle' => 'Daftar Pengajuan',
            'currentPage' => 'pengajuan',
            'pengajuan' => $pengajuan
        ];

        $this->view('layouts/header', $data);
        $this->view('layouts/sidebar', $data);
        $this->view('pengajuan/index', $data);
        $this->view('layouts/footer', $data);
    }

    public function create()
    {
        $this->requireRole('pekerja');
        
        $id_pekerja = Session::get('id_pekerja');
        
        // Check active submission
        if ($this->pengajuanModel->hasActiveSubmission($id_pekerja)) {
            $this->setFlash('error', 'Anda masih memiliki pengajuan yang sedang diproses');
            $this->redirect('pengajuan');
            return;
        }

        $pekerja = $this->pekerjaModel->getWithDetails($id_pekerja);
        
        if (!$pekerja) {
            $this->setFlash('error', 'Data pekerja tidak ditemukan');
            $this->redirect('dashboard');
            return;
        }

        // Check masa kerja
        $masaKerja = Helper::calculateWorkPeriod($pekerja->tanggal_bergabung);
        if ($masaKerja['years'] < MIN_MASA_KERJA_TAHUN) {
            $this->setFlash('error', 'Masa kerja minimal ' . MIN_MASA_KERJA_TAHUN . ' tahun untuk mengajukan kenaikan golongan');
            $this->redirect('pengajuan');
            return;
        }

        // Check nilai kinerja
        if ($pekerja->nilai_kinerja_terakhir < MIN_NILAI_KINERJA) {
            $this->setFlash('error', 'Nilai kinerja minimal ' . MIN_NILAI_KINERJA . ' untuk mengajukan kenaikan golongan');
            $this->redirect('pengajuan');
            return;
        }

        // Get next golongan
        $nextGolongan = $this->golonganModel->getNextGolongan($pekerja->id_golongan_saat_ini);

        $data = [
            'pageTitle' => 'Buat Pengajuan Kenaikan Golongan',
            'currentPage' => 'pengajuan',
            'pekerja' => $pekerja,
            'nextGolongan' => $nextGolongan,
            'masaKerja' => $masaKerja
        ];

        $this->view('layouts/header', $data);
        $this->view('layouts/sidebar', $data);
        $this->view('pengajuan/create', $data);
        $this->view('layouts/footer', $data);
    }

    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('pengajuan');
            return;
        }

        $this->requireRole('pekerja');
        $id_pekerja = Session::get('id_pekerja');

        // Validate
        if ($this->pengajuanModel->hasActiveSubmission($id_pekerja)) {
            $this->setFlash('error', 'Anda masih memiliki pengajuan yang sedang diproses');
            $this->redirect('pengajuan');
            return;
        }

        // Insert pengajuan
        $pengajuanData = [
            'nomor_pengajuan' => 'PG-' . date('Ymd') . '-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT),
            'id_pekerja' => $id_pekerja,
            'id_golongan_saat_ini' => $this->post('id_golongan_saat_ini'),
            'id_golongan_diajukan' => $this->post('id_golongan_diajukan'),
            'alasan_pengajuan' => $this->post('alasan_pengajuan'),
            'tanggal_pengajuan' => date('Y-m-d'),
            'status' => 'pending'
        ];

        $this->pengajuanModel->beginTransaction();

        try {
            $id_pengajuan = $this->pengajuanModel->insert($pengajuanData);

            if (!$id_pengajuan) {
                throw new Exception('Gagal menyimpan pengajuan');
            }

            // Handle file uploads
            $upload = new Upload();
            $requiredDocs = ['surat_permohonan', 'penilaian_kinerja', 'sertifikat'];

            foreach ($requiredDocs as $docType) {
                if (isset($_FILES[$docType]) && $_FILES[$docType]['error'] === 0) {
                    $uploadResult = $upload->uploadFile($_FILES[$docType], 'uploads/dokumen/', ['pdf', 'jpg', 'jpeg', 'png']);
                    
                    if ($uploadResult['success']) {
                        $this->dokumenModel->insert([
                            'id_pengajuan' => $id_pengajuan,
                            'jenis_dokumen' => $docType,
                            'nama_dokumen' => $uploadResult['filename'],
                            'file_path' => $uploadResult['path'],
                            'file_size' => $_FILES[$docType]['size'],
                            'mime_type' => $_FILES[$docType]['type'],
                            'uploaded_at' => date('Y-m-d H:i:s')
                        ]);
                    }
                }
            }

            $this->pengajuanModel->commit();

            Helper::logActivity('Membuat pengajuan kenaikan golongan', 'pengajuan');
            $this->setFlash('success', 'Pengajuan berhasil dibuat');
            $this->redirect('pengajuan');

        } catch (Exception $e) {
            $this->pengajuanModel->rollback();
            $this->setFlash('error', 'Gagal membuat pengajuan: ' . $e->getMessage());
            $this->redirect('pengajuan/create');
        }
    }

    public function detail($id)
    {
        $pengajuan = $this->pengajuanModel->getWithDetails($id);

        if (!$pengajuan) {
            $this->setFlash('error', 'Pengajuan tidak ditemukan');
            $this->redirect('pengajuan');
            return;
        }

        // Check access
        $role = Session::get('role');
        $id_pekerja = Session::get('id_pekerja');
        
        if ($role !== 'admin' && $pengajuan->id_pekerja != $id_pekerja) {
            $this->setFlash('error', 'Anda tidak memiliki akses ke pengajuan ini');
            $this->redirect('pengajuan');
            return;
        }

        $dokumen = $this->dokumenModel->getByPengajuan($id);
        $approvalHistory = $this->model('ApprovalHistory')->getByPengajuan($id);

        $data = [
            'pageTitle' => 'Detail Pengajuan',
            'currentPage' => 'pengajuan',
            'pengajuan' => $pengajuan,
            'dokumen' => $dokumen,
            'approvalHistory' => $approvalHistory
        ];

        $this->view('layouts/header', $data);
        $this->view('layouts/sidebar', $data);
        $this->view('pengajuan/detail', $data);
        $this->view('layouts/footer', $data);
    }
}
