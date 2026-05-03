<?php
/**
 * DokumenController.php - Dokumen Controller
 * Handles document viewing and downloading with access control
 */

class DokumenController extends Controller
{
    private $dokumenModel;
    private $pengajuanModel;

    public function __construct()
    {
        $this->requireLogin();
        $this->dokumenModel = $this->model('DokumenPengajuan');
        $this->pengajuanModel = $this->model('Pengajuan');
    }

    /**
     * Display document in browser
     * @param int $id_dokumen
     */
    public function display($id_dokumen)
    {
        $dokumen = $this->dokumenModel->getById($id_dokumen);

        if (!$dokumen) {
            $this->setFlash('error', 'Dokumen tidak ditemukan');
            $this->redirect('dashboard');
            return;
        }

        // Get pengajuan details
        $pengajuan = $this->pengajuanModel->getWithDetails($dokumen->id_pengajuan);

        if (!$pengajuan) {
            $this->setFlash('error', 'Pengajuan tidak ditemukan');
            $this->redirect('dashboard');
            return;
        }

        // Check access rights
        if (!$this->canAccessDocument($pengajuan)) {
            $this->setFlash('error', 'Anda tidak memiliki akses untuk melihat dokumen ini');
            $this->redirect('dashboard');
            return;
        }

        // Build file path
        $filePath = UPLOAD_PATH . '/' . $dokumen->file_path;

        if (!file_exists($filePath)) {
            $this->setFlash('error', 'File dokumen tidak ditemukan di server');
            $this->redirect('dashboard');
            return;
        }

        // Log activity
        Helper::logActivity('Melihat dokumen: ' . $dokumen->nama_dokumen, 'dokumen');

        // Output file
        $this->outputFile($filePath, $dokumen->nama_dokumen, $dokumen->mime_type);
    }

    /**
     * Download document to user
     * @param int $id_dokumen
     */
    public function downloadFile($id_dokumen)
    {
        $dokumen = $this->dokumenModel->getById($id_dokumen);

        if (!$dokumen) {
            $this->setFlash('error', 'Dokumen tidak ditemukan');
            $this->redirect('dashboard');
            return;
        }

        $pengajuan = $this->pengajuanModel->getWithDetails($dokumen->id_pengajuan);

        if (!$pengajuan) {
            $this->setFlash('error', 'Pengajuan tidak ditemukan');
            $this->redirect('dashboard');
            return;
        }

        if (!$this->canAccessDocument($pengajuan)) {
            $this->setFlash('error', 'Anda tidak memiliki akses untuk mengunduh dokumen ini');
            $this->redirect('dashboard');
            return;
        }

        $filePath = UPLOAD_PATH . '/' . $dokumen->file_path;

        if (!file_exists($filePath)) {
            $this->setFlash('error', 'File dokumen tidak ditemukan di server');
            $this->redirect('dashboard');
            return;
        }

        Helper::logActivity('Mengunduh dokumen: ' . $dokumen->nama_dokumen, 'dokumen');

        // Force download
        $this->forceDownload($filePath, $dokumen->nama_dokumen);
    }

    /**
     * Check if user can access document
     * @param object $pengajuan
     * @return bool
     */
    private function canAccessDocument($pengajuan)
    {
        $role = Session::get('role');
        $id_pekerja = Session::get('id_pekerja');

        // Admin can access all documents
        if ($role === 'admin') {
            return true;
        }

        // Owner can access their own documents
        if ($pengajuan->id_pekerja == $id_pekerja) {
            return true;
        }

        // Atasan can access documents from their bawahan
        if ($role === 'atasan' && $pengajuan->id_atasan == $id_pekerja) {
            return true;
        }

        // Manager can access all documents (for approval level 2)
        if ($role === 'manager') {
            return true;
        }

        // Kepala Wilayah can access all documents (for final approval)
        if ($role === 'kepala_wilayah') {
            return true;
        }

        return false;
    }

    /**
     * Output file to browser
     * @param string $filePath
     * @param string $filename
     * @param string $mimeType
     */
    private function outputFile($filePath, $filename, $mimeType)
    {
        // Clear output buffer
        if (ob_get_level()) {
            ob_end_clean();
        }

        // Set headers
        header('Content-Type: ' . $mimeType);
        header('Content-Length: ' . filesize($filePath));
        header('Content-Disposition: inline; filename="' . $filename . '"');
        header('Cache-Control: public, must-revalidate, max-age=0');
        header('Pragma: public');
        header('Expires: 0');

        // Output file
        readfile($filePath);
        exit;
    }

    /**
     * Download file to user
     * @param string $filePath
     * @param string $filename
     */
    private function forceDownload($filePath, $filename)
    {
        // Clear output buffer
        if (ob_get_level()) {
            ob_end_clean();
        }

        // Set headers for download
        header('Content-Type: application/octet-stream');
        header('Content-Length: ' . filesize($filePath));
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Cache-Control: public, must-revalidate, max-age=0');
        header('Pragma: public');
        header('Expires: 0');

        // Output file
        readfile($filePath);
        exit;
    }
}
