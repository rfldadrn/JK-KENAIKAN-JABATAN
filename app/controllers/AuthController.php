<?php
/**
 * AuthController.php - Authentication Controller
 * Handles login, logout, and authentication related actions
 */

class AuthController extends Controller
{
    private $userModel;

    public function __construct()
    {
        $this->userModel = $this->model('User');
    }

    /**
     * Default index - redirect to login
     */
    public function index()
    {
        $this->login();
    }

    /**
     * Display login page
     */
    public function login()
    {
        // If already logged in, redirect to dashboard
        if ($this->isLoggedIn()) {
            $this->redirect('dashboard');
        }

        // Handle form submission
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->processLogin();
        } else {
            $this->view('auth/login');
        }
    }

    /**
     * Process login
     */
    private function processLogin()
    {
        $username = $this->post('username');
        $password = $this->post('password');

        // Validate input
        $validation = new Validation();
        $validation->required('username', $username, 'Username')
                   ->required('password', $password, 'Password');

        if ($validation->failed()) {
            $this->view('auth/login', [
                'errors' => $validation->getErrors(),
                'old' => $_POST
            ]);
            return;
        }

        // Authenticate user
        $user = $this->userModel->authenticate($username, $password);

        if ($user) {
            // Set session data
            Session::setUserData([
                'id_user' => $user->id_user,
                'username' => $user->username,
                'email' => $user->email,
                'role' => $user->role,
                'id_pekerja' => $user->id_pekerja,
                'nama_lengkap' => $user->nama_lengkap ?? null
            ]);

            // Log activity
            Helper::logActivity('Login ke sistem', 'auth');

            // Redirect to dashboard
            $this->redirect('dashboard');
        } else {
            $this->view('auth/login', [
                'error' => 'Username atau password salah',
                'old' => $_POST
            ]);
        }
    }

    /**
     * Logout user
     */
    public function logout()
    {
        // Log activity before destroying session
        if ($this->isLoggedIn()) {
            Helper::logActivity('Logout dari sistem', 'auth');
        }

        // Destroy session
        Session::destroy();

        // Redirect to login
        $this->setFlash('success', 'Anda telah berhasil logout');
        $this->redirect('auth/login');
    }

    /**
     * Change password page
     */
    public function changePassword()
    {
        $this->requireLogin();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->processChangePassword();
        } else {
            $this->view('auth/change_password');
        }
    }

    /**
     * Process change password
     */
    private function processChangePassword()
    {
        $userId = Session::getUserId();
        $oldPassword = $this->post('old_password');
        $newPassword = $this->post('new_password');
        $confirmPassword = $this->post('confirm_password');

        // Validate input
        $validation = new Validation();
        $validation->required('old_password', $oldPassword, 'Password Lama')
                   ->required('new_password', $newPassword, 'Password Baru')
                   ->minLength('new_password', $newPassword, 8, 'Password Baru')
                   ->required('confirm_password', $confirmPassword, 'Konfirmasi Password')
                   ->match('confirm_password', $confirmPassword, $newPassword, 'Konfirmasi Password');

        if ($validation->failed()) {
            $this->view('auth/change_password', [
                'errors' => $validation->getErrors()
            ]);
            return;
        }

        // Verify old password
        $user = $this->userModel->getById($userId);
        if (!Helper::verifyPassword($oldPassword, $user->password)) {
            $this->view('auth/change_password', [
                'error' => 'Password lama tidak sesuai'
            ]);
            return;
        }

        // Update password
        if ($this->userModel->updatePassword($userId, $newPassword)) {
            Helper::logActivity('Mengubah password', 'auth');
            $this->setFlash('success', 'Password berhasil diubah');
            $this->redirect('dashboard');
        } else {
            $this->view('auth/change_password', [
                'error' => 'Gagal mengubah password'
            ]);
        }
    }

    /**
     * Forgot password page
     */
    public function forgotPassword()
    {
        if ($this->isLoggedIn()) {
            $this->redirect('dashboard');
        }

        $this->view('auth/forgot_password');
    }

    /**
     * Check session (for AJAX calls)
     */
    public function checkSession()
    {
        if (Session::isExpired()) {
            $this->json([
                'status' => 'expired',
                'message' => 'Session expired'
            ], 401);
        } else {
            $this->json([
                'status' => 'active',
                'message' => 'Session active'
            ]);
        }
    }
}
