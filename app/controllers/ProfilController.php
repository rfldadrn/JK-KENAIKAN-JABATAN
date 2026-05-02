<?php
/**
 * ProfilController.php - Profil Controller
 */

class ProfilController extends Controller
{
    private $userModel;
    private $pekerjaModel;

    public function __construct()
    {
        $this->requireLogin();
        $this->userModel = $this->model('User');
        $this->pekerjaModel = $this->model('Pekerja');
    }

    public function index()
    {
        $userId = Session::get('user_id');
        $user = $this->userModel->getById($userId);
        
        $pekerja = null;
        if ($user->id_pekerja) {
            $pekerja = $this->pekerjaModel->getWithDetails($user->id_pekerja);
        }

        $data = [
            'pageTitle' => 'Profil Saya',
            'currentPage' => 'profil',
            'user' => $user,
            'pekerja' => $pekerja
        ];

        $this->view('layouts/header', $data);
        $this->view('layouts/sidebar', $data);
        $this->view('profil/index', $data);
        $this->view('layouts/footer', $data);
    }

    public function changePassword()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('profil');
            return;
        }

        $oldPassword = $this->post('old_password');
        $newPassword = $this->post('new_password');
        $confirmPassword = $this->post('confirm_password');

        $userId = Session::get('user_id');
        $user = $this->userModel->getById($userId);

        // Validate
        if (!Helper::verifyPassword($oldPassword, $user->password)) {
            $this->setFlash('error', 'Password lama tidak sesuai');
            $this->redirect('profil');
            return;
        }

        if ($newPassword !== $confirmPassword) {
            $this->setFlash('error', 'Password baru dan konfirmasi tidak sama');
            $this->redirect('profil');
            return;
        }

        if (strlen($newPassword) < 6) {
            $this->setFlash('error', 'Password minimal 6 karakter');
            $this->redirect('profil');
            return;
        }

        // Update password
        $result = $this->userModel->updatePassword($userId, $newPassword);

        if ($result) {
            Helper::logActivity('Mengubah password', 'profil');
            $this->setFlash('success', 'Password berhasil diubah');
        } else {
            $this->setFlash('error', 'Gagal mengubah password');
        }

        $this->redirect('profil');
    }
}
