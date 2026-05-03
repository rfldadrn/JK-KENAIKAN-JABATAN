    /**
     * Riwayat Approval untuk atasan, manager, kepala_wilayah
     */
    
<?php
/**
 * ApprovalController.php - Approval Controller
 */

class ApprovalController extends Controller
{
    private $pengajuanModel;
    private $approvalModel;
    private $pekerjaModel;

    public function __construct()
    {
        $this->requireLogin();
        $this->pengajuanModel = $this->model('Pengajuan');
        $this->approvalModel = $this->model('ApprovalHistory');
        $this->pekerjaModel = $this->model('Pekerja');
    }

    // Semua pengajuan bawahan untuk atasan, atau semua pengajuan untuk admin
    public function semua()
    {
        $role = Session::get('role');
        $id_pekerja = Session::get('id_pekerja');
        
        if ($role === 'atasan') {
            // Atasan: show only bawahan submissions
            $allPengajuan = $this->pengajuanModel->getAllByBawahan($id_pekerja);
            $pendingCount = count($this->pengajuanModel->getPendingForAtasan($id_pekerja));
            $pageTitle = 'Semua Pengajuan Bawahan';
        } elseif ($role === 'admin') {
            // Admin: show all submissions
            $allPengajuan = $this->pengajuanModel->getAllWithDetails();
            $pendingCount = 0; // Admin doesn't have pending count
            $pageTitle = 'Semua Pengajuan';
        } else {
            $this->setFlash('error', 'Anda tidak memiliki akses ke halaman ini');
            $this->redirect('dashboard');
            return;
        }
        
        $data = [
            'pageTitle' => $pageTitle,
            'currentPage' => 'approval-semua',
            'allPengajuan' => $allPengajuan,
            'pendingCount' => $pendingCount
        ];
        
        $this->view('layouts/header', $data);
        $this->view('layouts/sidebar', $data);
        $this->view('approval/semua', $data);
        $this->view('layouts/footer', $data);
    }

    public function index()
    {
        $role = Session::get('role');
        $id_pekerja = Session::get('id_pekerja');

        if ($role === 'atasan') {
            $pending = $this->pengajuanModel->getPendingForAtasan($id_pekerja);
        } elseif ($role === 'manager') {
            $pending = $this->pengajuanModel->getPendingForManager();
        } elseif ($role === 'kepala_wilayah') {
            $pending = $this->pengajuanModel->getPendingForKepalaWilayah();
        } else {
            $this->redirect('dashboard');
            return;
        }

        $data = [
            'pageTitle' => 'Persetujuan Pengajuan',
            'currentPage' => 'approval',
            'pending' => $pending,
            'pendingCount' => count($pending)
        ];

        $this->view('layouts/header', $data);
        $this->view('layouts/sidebar', $data);
        $this->view('approval/index', $data);
        $this->view('layouts/footer', $data);
    }

    public function review($id)
    {
        $pengajuan = $this->pengajuanModel->getWithDetails($id);

        if (!$pengajuan) {
            $this->setFlash('error', 'Pengajuan tidak ditemukan');
            $this->redirect('approval');
            return;
        }

        $role = Session::get('role');
        
        // Check if user can review
        if (!$this->canReview($pengajuan, $role)) {
            $this->setFlash('error', 'Anda tidak memiliki akses untuk mereview pengajuan ini');
            $this->redirect('approval');
            return;
        }

        $dokumen = $this->model('DokumenPengajuan')->getByPengajuan($id);
        $approvalHistory = $this->approvalModel->getByPengajuan($id);

        // Get pending count for sidebar
        $id_pekerja = Session::get('id_pekerja');
        if ($role === 'atasan') {
            $pendingCount = count($this->pengajuanModel->getPendingForAtasan($id_pekerja));
        } elseif ($role === 'manager') {
            $pendingCount = count($this->pengajuanModel->getPendingForManager());
        } elseif ($role === 'kepala_wilayah') {
            $pendingCount = count($this->pengajuanModel->getPendingForKepalaWilayah());
        } else {
            $pendingCount = 0;
        }

        $data = [
            'pageTitle' => 'Review Pengajuan',
            'currentPage' => 'approval',
            'pengajuan' => $pengajuan,
            'dokumen' => $dokumen,
            'approvalHistory' => $approvalHistory,
            'pendingCount' => $pendingCount
        ];

        $this->view('layouts/header', $data);
        $this->view('layouts/sidebar', $data);
        $this->view('approval/review', $data);
        $this->view('layouts/footer', $data);
    }

    public function process($id)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('approval');
            return;
        }

        $pengajuan = $this->pengajuanModel->getById($id);

        if (!$pengajuan) {
            $this->setFlash('error', 'Pengajuan tidak ditemukan');
            $this->redirect('approval');
            return;
        }

        $action = $this->post('action'); // approve or reject
        $catatan = $this->post('catatan');
        $role = Session::get('role');
        $id_user = Session::get('user_id');

        if (!in_array($action, ['approve', 'reject'])) {
            $this->setFlash('error', 'Aksi tidak valid');
            $this->redirect('approval/review/' . $id);
            return;
        }

        $this->pengajuanModel->beginTransaction();

        try {
            // Determine new status
            $newStatus = $this->getNewStatus($pengajuan->status, $role, $action);

            // Update pengajuan status
            $this->pengajuanModel->update($id, ['status' => $newStatus]);

            // Add approval history
            $this->approvalModel->addApproval([
                'id_pengajuan' => $id,
                'level_approval' => $role,
                'id_approver' => $id_user,
                'keputusan' => $action === 'approve' ? 'approved' : 'rejected',
                'catatan' => $catatan,
                'tanggal_approval' => date('Y-m-d H:i:s')
            ]);

            // If final approval, update pekerja golongan
            if ($newStatus === 'disetujui') {
                $this->pekerjaModel->update($pengajuan->id_pekerja, [
                    'id_golongan_saat_ini' => $pengajuan->id_golongan_diajukan
                ]);
            }

            $this->pengajuanModel->commit();

            $actionText = $action === 'approve' ? 'disetujui' : 'ditolak';
            Helper::logActivity("Pengajuan #{$id} {$actionText}", 'approval');
            $this->setFlash('success', 'Pengajuan berhasil ' . $actionText);
            $this->redirect('approval');

        } catch (Exception $e) {
            $this->pengajuanModel->rollback();
            $this->setFlash('error', 'Gagal memproses pengajuan: ' . $e->getMessage());
            $this->redirect('approval/review/' . $id);
        }
    }

    private function canReview($pengajuan, $role)
    {
        if ($role === 'atasan' && $pengajuan->status === 'pending') {
            return true;
        }
        if ($role === 'manager' && $pengajuan->status === 'disetujui_atasan') {
            return true;
        }
        if ($role === 'kepala_wilayah' && $pengajuan->status === 'disetujui_manager') {
            return true;
        }
        return false;
    }

    private function getNewStatus($currentStatus, $role, $action)
    {
        if ($action === 'reject') {
            return 'ditolak_' . $role;
        }

        if ($role === 'atasan' && $currentStatus === 'pending') {
            return 'disetujui_atasan';
        }
        if ($role === 'manager' && $currentStatus === 'disetujui_atasan') {
            return 'disetujui_manager';
        }
        if ($role === 'kepala_wilayah' && $currentStatus === 'disetujui_manager') {
            return 'disetujui';
        }

        return $currentStatus;
    }

    private function getApprovalLevel($role)
    {
        $levels = [
            'atasan' => 1,
            'manager' => 2,
            'kepala_wilayah' => 3
        ];
        return $levels[$role] ?? 0;
    }

    public function riwayat()
    {
        $role = Session::get('role');
        $id_pekerja = Session::get('id_pekerja');

        // Hanya untuk atasan, manager, kepala_wilayah
        if (!in_array($role, ['atasan', 'manager', 'kepala_wilayah'])) {
            $this->setFlash('error', 'Anda tidak memiliki akses ke halaman ini');
            $this->redirect('dashboard');
            return;
        }

        // Get pending count for sidebar
        if ($role === 'atasan') {
            $pendingCount = count($this->pengajuanModel->getPendingForAtasan($id_pekerja));
        } elseif ($role === 'manager') {
            $pendingCount = count($this->pengajuanModel->getPendingForManager());
        } elseif ($role === 'kepala_wilayah') {
            $pendingCount = count($this->pengajuanModel->getPendingForKepalaWilayah());
        } else {
            $pendingCount = 0;
        }

        // Ambil riwayat approval yang pernah dilakukan user ini
        $approvalHistory = $this->approvalModel->query(
            "SELECT ah.*, p.nomor_pengajuan, p.tanggal_pengajuan, pekerja.nama_lengkap as nama_pemohon
             FROM approval_history ah
             JOIN pengajuan p ON ah.id_pengajuan = p.id_pengajuan
             JOIN pekerja ON p.id_pekerja = pekerja.id_pekerja
             WHERE ah.id_approver = :id_user
             ORDER BY ah.tanggal_approval DESC",
            [':id_user' => Session::get('user_id')]
        );

        $data = [
            'pageTitle' => 'Riwayat Persetujuan',
            'currentPage' => 'approval-riwayat',
            'approvalHistory' => $approvalHistory,
            'pendingCount' => $pendingCount
        ];

        $this->view('layouts/header', $data);
        $this->view('layouts/sidebar', $data);
        $this->view('approval/riwayat', $data);
        $this->view('layouts/footer', $data);
    }
}
