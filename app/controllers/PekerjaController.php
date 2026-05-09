<?php
/**
 * PekerjaController.php - Pekerja Controller
 */

class PekerjaController extends Controller
{
    private $pekerjaModel;
    private $divisiModel;
    private $jabatanModel;
    private $golonganModel;
    private $userModel;

    public function __construct()
    {
        $this->requireRole('admin');
        $this->pekerjaModel = $this->model('Pekerja');
        $this->divisiModel = $this->model('Divisi');
        $this->jabatanModel = $this->model('Jabatan');
        $this->golonganModel = $this->model('GolonganJabatan');
        $this->userModel = $this->model('User');
    }

    public function index()
    {
        $pekerja = $this->pekerjaModel->getAllWithDetails();
        
        // Get filter options
        $divisi = $this->divisiModel->getAllActive();
        $jabatan = $this->jabatanModel->getAllActive();
        $golongan = $this->golonganModel->getAll();

        $data = [
            'pageTitle' => 'Data Karyawan',
            'currentPage' => 'pekerja',
            'pekerja' => $pekerja,
            'divisi' => $divisi,
            'jabatan' => $jabatan,
            'golongan' => $golongan
        ];

        $this->view('layouts/header', $data);
        $this->view('layouts/sidebar', $data);
        $this->view('pekerja/index', $data);
        $this->view('layouts/footer', $data);
    }

    public function create()
    {
        $divisi = $this->divisiModel->getAllActive();
        $jabatan = $this->jabatanModel->getAllActive();
        $golongan = $this->golonganModel->getAllActive();
        $atasan = $this->pekerjaModel->getActiveForDropdown();

        $data = [
            'pageTitle' => 'Tambah Karyawan',
            'currentPage' => 'pekerja',
            'divisi' => $divisi,
            'jabatan' => $jabatan,
            'golongan' => $golongan,
            'atasan' => $atasan
        ];

        $this->view('layouts/header', $data);
        $this->view('layouts/sidebar', $data);
        $this->view('pekerja/create', $data);
        $this->view('layouts/footer', $data);
    }

    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('pekerja');
            return;
        }

        $validation = new Validation();
        $validation->required('nip', $this->post('nip'), 'NIP')
                   ->required('nama_lengkap', $this->post('nama_lengkap'), 'Nama Lengkap')
                   ->email('email', $this->post('email'))
                   ->required('id_divisi', $this->post('id_divisi'), 'Divisi')
                   ->required('id_jabatan', $this->post('id_jabatan'), 'Jabatan')
                   ->required('id_golongan_saat_ini', $this->post('id_golongan_saat_ini'), 'Golongan');

        // Check NIP unique
        if ($this->pekerjaModel->nipExists($this->post('nip'))) {
            $validation->addError('nip', 'NIP sudah digunakan');
        }

        if ($validation->failed()) {
            Session::set('errors', $validation->getErrors());
            Session::set('old', $_POST);
            $this->redirect('pekerja/create');
            return;
        }

        // Handle photo upload
        $fotoPath = null;
        if (isset($_FILES['foto']) && $_FILES['foto']['error'] === 0) {
            $upload = new Upload();
            $uploadResult = $upload->uploadFile($_FILES['foto'], 'foto/', ['image/jpeg', 'image/jpg', 'image/png']);
            
            if ($uploadResult) {
                $fotoPath = $uploadResult;
                // Resize image
                $fullPath = UPLOAD_PATH . '/' . $uploadResult;
                $upload->resizeImage($fullPath, 300, 300);
            }
        }

        $data = [
            'nip' => $this->post('nip'),
            'nama_lengkap' => $this->post('nama_lengkap'),
            'email' => $this->post('email'),
            'no_telepon' => $this->post('no_telepon'),
            'tanggal_lahir' => $this->post('tanggal_lahir'),
            'alamat' => $this->post('alamat'),
            'id_divisi' => $this->post('id_divisi'),
            'id_jabatan' => $this->post('id_jabatan'),
            'id_golongan_saat_ini' => $this->post('id_golongan_saat_ini'),
            'id_atasan' => $this->post('id_atasan') ?: null,
            'tanggal_bergabung' => $this->post('tanggal_bergabung'),
            'nilai_kinerja_terakhir' => $this->post('nilai_kinerja_terakhir'),
            'status_kepegawaian' => $this->post('status_kepegawaian', 'aktif'),
            'foto' => $fotoPath
        ];

        $result = $this->pekerjaModel->insert($data);

        if ($result) {
            // Auto-create user account for new employee
            $userData = [
                'username' => $this->post('nip'),
                'password' => $this->post('nip'), // Default password = NIP
                'email' => $this->post('email'),
                'role' => 'pekerja', // Default role
                'id_pekerja' => $result,
                'is_active' => 1
            ];
            
            $userCreated = $this->userModel->createUser($userData);
            
            if ($userCreated) {
                Helper::logActivity('Menambah Karyawan dan akun user: ' . $this->post('nama_lengkap'), 'pekerja');
                $this->setFlash('success', 'Data karyawan dan akun user berhasil ditambahkan. Username: ' . $this->post('nip') . ', Password default: ' . $this->post('nip'));
            } else {
                Helper::logActivity('Menambah karyawan: ' . $this->post('nama_lengkap') . ' (akun user gagal dibuat)', 'pekerja');
                $this->setFlash('warning', 'Data karyawan berhasil ditambahkan, namun gagal membuat akun user. Silakan buat manual.');
            }
        } else {
            $this->setFlash('error', 'Gagal menambahkan data karyawan');
        }

        $this->redirect('pekerja');
    }

    public function edit($id)
    {
        $pekerja = $this->pekerjaModel->getById($id);

        if (!$pekerja) {
            $this->setFlash('error', 'Karyawan tidak ditemukan');
            $this->redirect('pekerja');
            return;
        }

        $divisi = $this->divisiModel->getAllActive();
        $jabatan = $this->jabatanModel->getAllActive();
        $golongan = $this->golonganModel->getAllActive();
        $atasan = $this->pekerjaModel->getActiveForDropdown();

        $data = [
            'pageTitle' => 'Edit Karyawan',
            'currentPage' => 'pekerja',
            'pekerja' => $pekerja,
            'divisi' => $divisi,
            'jabatan' => $jabatan,
            'golongan' => $golongan,
            'atasan' => $atasan
        ];

        $this->view('layouts/header', $data);
        $this->view('layouts/sidebar', $data);
        $this->view('pekerja/edit', $data);
        $this->view('layouts/footer', $data);
    }

    public function update($id)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('pekerja');
            return;
        }

        $pekerja = $this->pekerjaModel->getById($id);
        if (!$pekerja) {
            $this->setFlash('error', 'Karyawan tidak ditemukan');
            $this->redirect('pekerja');
            return;
        }

        $validation = new Validation();
        $validation->required('nip', $this->post('nip'), 'NIP')
                   ->required('nama_lengkap', $this->post('nama_lengkap'), 'Nama Lengkap')
                   ->email('email', $this->post('email'));

        if ($this->pekerjaModel->nipExists($this->post('nip'), $id)) {
            $validation->addError('nip', 'NIP sudah digunakan');
        }

        if ($validation->failed()) {
            Session::set('errors', $validation->getErrors());
            Session::set('old', $_POST);
            $this->redirect('pekerja/edit/' . $id);
            return;
        }

        // Handle photo upload
        $fotoPath = $pekerja->foto;
        if (isset($_FILES['foto']) && $_FILES['foto']['error'] === 0) {
            $upload = new Upload();
            $uploadResult = $upload->uploadFile($_FILES['foto'], 'foto/', ['image/jpeg', 'image/jpg', 'image/png']);
            
            if ($uploadResult) {
                // Delete old photo
                if ($pekerja->foto) {
                    $upload->deleteFile($pekerja->foto);
                }
                $fotoPath = $uploadResult;
                $fullPath = UPLOAD_PATH . '/' . $uploadResult;
                $upload->resizeImage($fullPath, 300, 300);
            }
        }

        $data = [
            'nip' => $this->post('nip'),
            'nama_lengkap' => $this->post('nama_lengkap'),
            'email' => $this->post('email'),
            'no_telepon' => $this->post('no_telepon'),
            'tanggal_lahir' => $this->post('tanggal_lahir'),
            'alamat' => $this->post('alamat'),
            'id_divisi' => $this->post('id_divisi'),
            'id_jabatan' => $this->post('id_jabatan'),
            'id_golongan_saat_ini' => $this->post('id_golongan_saat_ini'),
            'id_atasan' => $this->post('id_atasan') ?: null,
            'tanggal_bergabung' => $this->post('tanggal_bergabung'),
            'nilai_kinerja_terakhir' => $this->post('nilai_kinerja_terakhir'),
            'status_kepegawaian' => $this->post('status_kepegawaian'),
            'foto' => $fotoPath
        ];

        $result = $this->pekerjaModel->update($id, $data);

        if ($result) {
            Helper::logActivity('Mengupdate karyawan: ' . $this->post('nama_lengkap'), 'karyawan');
            $this->setFlash('success', 'Data karyawan berhasil diupdate');
        } else {
            $this->setFlash('error', 'Gagal mengupdate data karyawan');
        }

        $this->redirect('karyawan');
    }

    public function detail($id)
    {
        $pekerja = $this->pekerjaModel->getWithDetails($id);

        if (!$pekerja) {
            $this->setFlash('error', 'Karyawan tidak ditemukan');
            $this->redirect('pekerja');
            return;
        }

        $bawahan = $this->pekerjaModel->getSubordinates($id);

        $data = [
            'pageTitle' => 'Detail Karyawan',
            'currentPage' => 'pekerja',
            'pekerja' => $pekerja,
            'bawahan' => $bawahan
        ];

        $this->view('layouts/header', $data);
        $this->view('layouts/sidebar', $data);
        $this->view('pekerja/detail', $data);
        $this->view('layouts/footer', $data);
    }

    public function delete($id)
    {
        $pekerja = $this->pekerjaModel->getById($id);
        
        if (!$pekerja) {
            $this->setFlash('error', 'Karyawan tidak ditemukan');
            $this->redirect('pekerja');
            return;
        }

        // Delete photo
        if ($pekerja->foto) {
            $upload = new Upload();
            $upload->deleteFile($pekerja->foto);
        }

        $result = $this->pekerjaModel->delete($id);

        if ($result) {
            Helper::logActivity('Menghapus Karyawan: ' . $pekerja->nama_lengkap, 'pekerja');
            $this->setFlash('success', 'Data Karyawan berhasil dihapus');
        } else {
            $this->setFlash('error', 'Gagal menghapus data Karyawan');
        }

        $this->redirect('pekerja');
    }
}
