<?php
/**
 * GolonganController.php - Golongan Jabatan Controller
 * Handles CRUD for Golongan Jabatan (Admin only)
 */

class GolonganController extends Controller
{
    private $golonganModel;

    public function __construct()
    {
        $this->requireRole('admin');
        $this->golonganModel = $this->model('GolonganJabatan');
    }

    /**
     * Display list of golongan
     */
    public function index()
    {
        $golongan = $this->golonganModel->getWithStats();

        $data = [
            'pageTitle' => 'Master Golongan Jabatan',
            'currentPage' => 'golongan',
            'golongan' => $golongan
        ];

        $this->view('layouts/header', $data);
        $this->view('layouts/sidebar', $data);
        $this->view('golongan/index', $data);
        $this->view('layouts/footer', $data);
    }

    /**
     * Show create form
     */
    public function create()
    {
        $data = [
            'pageTitle' => 'Tambah Golongan Jabatan',
            'currentPage' => 'golongan'
        ];

        $this->view('layouts/header', $data);
        $this->view('layouts/sidebar', $data);
        $this->view('golongan/create', $data);
        $this->view('layouts/footer', $data);
    }

    /**
     * Store new golongan
     */
    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('golongan');
            return;
        }

        $kode = $this->post('kode_golongan');
        $nama = $this->post('nama_golongan');
        $level = $this->post('level');
        $subLevel = $this->post('sub_level');
        $deskripsi = $this->post('deskripsi');
        $syarat = $this->post('syarat_minimal');

        // Validation
        $validation = new Validation();
        $validation->required('kode_golongan', $kode, 'Kode Golongan')
                   ->required('nama_golongan', $nama, 'Nama Golongan')
                   ->required('level', $level, 'Level')
                   ->required('sub_level', $subLevel, 'Sub Level');

        // Check unique kode
        if ($this->golonganModel->kodeExists($kode)) {
            $validation->addError('kode_golongan', 'Kode golongan sudah digunakan');
        }

        if ($validation->failed()) {
            Session::set('errors', $validation->getErrors());
            Session::set('old', $_POST);
            $this->redirect('golongan/create');
            return;
        }

        // Insert data
        $result = $this->golonganModel->insert([
            'kode_golongan' => $kode,
            'nama_golongan' => $nama,
            'level' => $level,
            'sub_level' => $subLevel,
            'deskripsi' => $deskripsi,
            'syarat_minimal' => $syarat,
            'is_active' => 1
        ]);

        if ($result) {
            Helper::logActivity('Menambah golongan jabatan: ' . $kode, 'golongan');
            $this->setFlash('success', 'Golongan jabatan berhasil ditambahkan');
        } else {
            $this->setFlash('error', 'Gagal menambahkan golongan jabatan');
        }

        $this->redirect('golongan');
    }

    /**
     * Show edit form
     */
    public function edit($id)
    {
        $golongan = $this->golonganModel->getById($id);

        if (!$golongan) {
            $this->setFlash('error', 'Golongan tidak ditemukan');
            $this->redirect('golongan');
            return;
        }

        $data = [
            'pageTitle' => 'Edit Golongan Jabatan',
            'currentPage' => 'golongan',
            'golongan' => $golongan
        ];

        $this->view('layouts/header', $data);
        $this->view('layouts/sidebar', $data);
        $this->view('golongan/edit', $data);
        $this->view('layouts/footer', $data);
    }

    /**
     * Update golongan
     */
    public function update($id)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('golongan');
            return;
        }

        $golongan = $this->golonganModel->getById($id);
        if (!$golongan) {
            $this->setFlash('error', 'Golongan tidak ditemukan');
            $this->redirect('golongan');
            return;
        }

        $kode = $this->post('kode_golongan');
        $nama = $this->post('nama_golongan');
        $level = $this->post('level');
        $subLevel = $this->post('sub_level');
        $deskripsi = $this->post('deskripsi');
        $syarat = $this->post('syarat_minimal');
        $isActive = $this->post('is_active', 1);

        // Validation
        $validation = new Validation();
        $validation->required('kode_golongan', $kode, 'Kode Golongan')
                   ->required('nama_golongan', $nama, 'Nama Golongan')
                   ->required('level', $level, 'Level')
                   ->required('sub_level', $subLevel, 'Sub Level');

        // Check unique kode
        if ($this->golonganModel->kodeExists($kode, $id)) {
            $validation->addError('kode_golongan', 'Kode golongan sudah digunakan');
        }

        if ($validation->failed()) {
            Session::set('errors', $validation->getErrors());
            Session::set('old', $_POST);
            $this->redirect('golongan/edit/' . $id);
            return;
        }

        // Update data
        $result = $this->golonganModel->update($id, [
            'kode_golongan' => $kode,
            'nama_golongan' => $nama,
            'level' => $level,
            'sub_level' => $subLevel,
            'deskripsi' => $deskripsi,
            'syarat_minimal' => $syarat,
            'is_active' => $isActive
        ]);

        if ($result) {
            Helper::logActivity('Mengupdate golongan jabatan: ' . $kode, 'golongan');
            $this->setFlash('success', 'Golongan jabatan berhasil diupdate');
        } else {
            $this->setFlash('error', 'Gagal mengupdate golongan jabatan');
        }

        $this->redirect('golongan');
    }

    /**
     * Delete golongan
     */
    public function delete($id)
    {
        $golongan = $this->golonganModel->getById($id);
        
        if (!$golongan) {
            $this->setFlash('error', 'Golongan tidak ditemukan');
            $this->redirect('golongan');
            return;
        }

        // Check if used by pekerja
        $sql = "SELECT COUNT(*) as count FROM pekerja WHERE id_golongan_saat_ini = :id";
        $result = $this->golonganModel->queryOne($sql, [':id' => $id]);
        
        if ($result && $result->count > 0) {
            $this->setFlash('error', 'Golongan tidak dapat dihapus karena sedang digunakan oleh pekerja');
            $this->redirect('golongan');
            return;
        }

        $result = $this->golonganModel->delete($id);

        if ($result) {
            Helper::logActivity('Menghapus golongan jabatan: ' . $golongan->kode_golongan, 'golongan');
            $this->setFlash('success', 'Golongan jabatan berhasil dihapus');
        } else {
            $this->setFlash('error', 'Gagal menghapus golongan jabatan');
        }

        $this->redirect('golongan');
    }
}
