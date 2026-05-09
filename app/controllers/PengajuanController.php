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
        
        if ($role === 'admin' || $role === 'manager' || $role === 'kepala_wilayah') {
            // Admin, Manager, Kepala Wilayah: show all submissions
            $pengajuan = $this->pengajuanModel->getAllWithDetails();
        } elseif ($role === 'pekerja') {
            // Pekerja: show only own submissions
            $id_pekerja = Session::get('id_pekerja');
            $pengajuan = $this->pengajuanModel->getByPekerja($id_pekerja);
        } else {
            // Atasan: should use /approval/semua instead
            $pengajuan = [];
        }

        // Get pending count for approver roles
        $pendingCount = 0;
        if (in_array($role, ['atasan', 'manager', 'kepala_wilayah'])) {
            $id_pekerja = Session::get('id_pekerja');
            if ($role === 'atasan') {
                $pendingCount = count($this->pengajuanModel->getPendingForAtasan($id_pekerja));
            } elseif ($role === 'manager') {
                $pendingCount = count($this->pengajuanModel->getPendingForManager());
            } elseif ($role === 'kepala_wilayah') {
                $pendingCount = count($this->pengajuanModel->getPendingForKepalaWilayah());
            }
        }

        $data = [
            'pageTitle' => 'Daftar Pengajuan',
            'currentPage' => 'pengajuan',
            'pengajuan' => $pengajuan,
            'pendingCount' => $pendingCount
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
            $this->setFlash('error', 'Data Karyawan tidak ditemukan');
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
            $uploadedCount = 0;

            foreach ($requiredDocs as $docType) {
                if (isset($_FILES[$docType]) && $_FILES[$docType]['error'] === 0) {
                    $allowedMimes = ['application/pdf', 'image/jpeg', 'image/jpg', 'image/png'];
                    $uploadResult = $upload->uploadFile($_FILES[$docType], 'documents/', $allowedMimes);
                    
                    if ($uploadResult) {
                        $this->dokumenModel->insert([
                            'id_pengajuan' => $id_pengajuan,
                            'jenis_dokumen' => $docType,
                            'nama_dokumen' => basename($uploadResult),
                            'file_path' => $uploadResult,
                            'file_size' => $_FILES[$docType]['size'],
                            'mime_type' => $_FILES[$docType]['type'],
                            'uploaded_at' => date('Y-m-d H:i:s')
                        ]);
                        $uploadedCount++;
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
        
        // Allow access for:
        // 1. Admin (full access)
        // 2. Owner of the pengajuan
        // 3. Atasan who is the direct superior
        // 4. Manager and Kepala Wilayah (can view all for approval)
        $hasAccess = false;
        
        if ($role === 'admin') {
            $hasAccess = true;
        } elseif ($pengajuan->id_pekerja == $id_pekerja) {
            $hasAccess = true;
        } elseif ($role === 'atasan' && $pengajuan->id_atasan == $id_pekerja) {
            $hasAccess = true;
        } elseif (in_array($role, ['manager', 'kepala_wilayah'])) {
            $hasAccess = true;
        }
        
        if (!$hasAccess) {
            $this->setFlash('error', 'Anda tidak memiliki akses ke pengajuan ini');
            $this->redirect('pengajuan');
            return;
        }

        $dokumen = $this->dokumenModel->getByPengajuan($id);
        $approvalHistory = $this->model('ApprovalHistory')->getByPengajuan($id);

        // Get pending count for approver roles
        $pendingCount = 0;
        if (in_array($role, ['atasan', 'manager', 'kepala_wilayah'])) {
            if ($role === 'atasan') {
                $pendingCount = count($this->pengajuanModel->getPendingForAtasan($id_pekerja));
            } elseif ($role === 'manager') {
                $pendingCount = count($this->pengajuanModel->getPendingForManager());
            } elseif ($role === 'kepala_wilayah') {
                $pendingCount = count($this->pengajuanModel->getPendingForKepalaWilayah());
            }
        }

        $data = [
            'pageTitle' => 'Detail Pengajuan',
            'currentPage' => 'pengajuan',
            'pengajuan' => $pengajuan,
            'dokumen' => $dokumen,
            'approvalHistory' => $approvalHistory,
            'pendingCount' => $pendingCount
        ];

        $this->view('layouts/header', $data);
        $this->view('layouts/sidebar', $data);
        $this->view('pengajuan/detail', $data);
        $this->view('layouts/footer', $data);
    }

    public function riwayat()
    {
        $this->requireRole('pekerja');
        
        $id_pekerja = Session::get('id_pekerja');
        
        // Get completed/rejected submissions
        $riwayat = $this->pengajuanModel->query(
            "SELECT pen.*, 
            g_sekarang.kode_golongan as golongan_sekarang,
            g_tujuan.kode_golongan as golongan_tujuan
            FROM pengajuan pen
            LEFT JOIN golongan_jabatan g_sekarang ON pen.id_golongan_saat_ini = g_sekarang.id_golongan
            LEFT JOIN golongan_jabatan g_tujuan ON pen.id_golongan_diajukan = g_tujuan.id_golongan
            WHERE pen.id_pekerja = :id_pekerja
            AND pen.status IN ('disetujui', 'ditolak_atasan', 'ditolak_manager', 'ditolak_kepala_wilayah', 'dibatalkan')
            ORDER BY pen.tanggal_pengajuan DESC",
            [':id_pekerja' => $id_pekerja]
        );

        $data = [
            'pageTitle' => 'Riwayat Pengajuan',
            'currentPage' => 'riwayat',
            'riwayat' => $riwayat
        ];

        $this->view('layouts/header', $data);
        $this->view('layouts/sidebar', $data);
        $this->view('pengajuan/riwayat', $data);
        $this->view('layouts/footer', $data);
    }
}
