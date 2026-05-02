<?php
/**
 * JabatanController.php - Jabatan Controller
 * Handles CRUD for Jabatan (Admin only)
 */

class JabatanController extends Controller
{
    private $jabatanModel;
    private $golonganModel;

    public function __construct()
    {
        $this->requireRole('admin');
        $this->jabatanModel = $this->model('Jabatan');
        $this->golonganModel = $this->model('GolonganJabatan');
    }

    public function index()
    {
        $jabatan = $this->jabatanModel->getWithStats();

        $data = [
            'pageTitle' => 'Master Jabatan',
            'currentPage' => 'jabatan',
            'jabatan' => $jabatan
        ];

        $this->view('layouts/header', $data);
        $this->view('layouts/sidebar', $data);
        $this->view('jabatan/index', $data);
        $this->view('layouts/footer', $data);
    }

    public function create()
    {
        $golongan = $this->golonganModel->getAllActive();

        $data = [
            'pageTitle' => 'Tambah Jabatan',
            'currentPage' => 'jabatan',
            'golongan' => $golongan
        ];

        $this->view('layouts/header', $data);
        $this->view('layouts/sidebar', $data);
        $this->view('jabatan/create', $data);
        $this->view('layouts/footer', $data);
    }

    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('jabatan');
            return;
        }

        $kode = $this->post('kode_jabatan');
        $nama = $this->post('nama_jabatan');
        $golonganMin = $this->post('id_golongan_minimal');
        $deskripsi = $this->post('deskripsi');

        $validation = new Validation();
        $validation->required('kode_jabatan', $kode, 'Kode Jabatan')
                   ->required('nama_jabatan', $nama, 'Nama Jabatan');

        if ($this->jabatanModel->kodeExists($kode)) {
            $validation->addError('kode_jabatan', 'Kode jabatan sudah digunakan');
        }

        if ($validation->failed()) {
            Session::set('errors', $validation->getErrors());
            Session::set('old', $_POST);
            $this->redirect('jabatan/create');
            return;
        }

        $result = $this->jabatanModel->insert([
            'kode_jabatan' => $kode,
            'nama_jabatan' => $nama,
            'id_golongan_minimal' => $golonganMin ?: null,
            'deskripsi' => $deskripsi,
            'is_active' => 1
        ]);

        if ($result) {
            Helper::logActivity('Menambah jabatan: ' . $kode, 'jabatan');
            $this->setFlash('success', 'Jabatan berhasil ditambahkan');
        } else {
            $this->setFlash('error', 'Gagal menambahkan jabatan');
        }

        $this->redirect('jabatan');
    }

    public function edit($id)
    {
        $jabatan = $this->jabatanModel->getById($id);

        if (!$jabatan) {
            $this->setFlash('error', 'Jabatan tidak ditemukan');
            $this->redirect('jabatan');
            return;
        }

        $golongan = $this->golonganModel->getAllActive();

        $data = [
            'pageTitle' => 'Edit Jabatan',
            'currentPage' => 'jabatan',
            'jabatan' => $jabatan,
            'golongan' => $golongan
        ];

        $this->view('layouts/header', $data);
        $this->view('layouts/sidebar', $data);
        $this->view('jabatan/edit', $data);
        $this->view('layouts/footer', $data);
    }

    public function update($id)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('jabatan');
            return;
        }

        $jabatan = $this->jabatanModel->getById($id);
        if (!$jabatan) {
            $this->setFlash('error', 'Jabatan tidak ditemukan');
            $this->redirect('jabatan');
            return;
        }

        $kode = $this->post('kode_jabatan');
        $nama = $this->post('nama_jabatan');
        $golonganMin = $this->post('id_golongan_minimal');
        $deskripsi = $this->post('deskripsi');
        $isActive = $this->post('is_active', 1);

        $validation = new Validation();
        $validation->required('kode_jabatan', $kode, 'Kode Jabatan')
                   ->required('nama_jabatan', $nama, 'Nama Jabatan');

        if ($this->jabatanModel->kodeExists($kode, $id)) {
            $validation->addError('kode_jabatan', 'Kode jabatan sudah digunakan');
        }

        if ($validation->failed()) {
            Session::set('errors', $validation->getErrors());
            Session::set('old', $_POST);
            $this->redirect('jabatan/edit/' . $id);
            return;
        }

        $result = $this->jabatanModel->update($id, [
            'kode_jabatan' => $kode,
            'nama_jabatan' => $nama,
            'id_golongan_minimal' => $golonganMin ?: null,
            'deskripsi' => $deskripsi,
            'is_active' => $isActive
        ]);

        if ($result) {
            Helper::logActivity('Mengupdate jabatan: ' . $kode, 'jabatan');
            $this->setFlash('success', 'Jabatan berhasil diupdate');
        } else {
            $this->setFlash('error', 'Gagal mengupdate jabatan');
        }

        $this->redirect('jabatan');
    }

    public function delete($id)
    {
        $jabatan = $this->jabatanModel->getById($id);
        
        if (!$jabatan) {
            $this->setFlash('error', 'Jabatan tidak ditemukan');
            $this->redirect('jabatan');
            return;
        }

        if (!$this->jabatanModel->canDelete($id)) {
            $this->setFlash('error', 'Jabatan tidak dapat dihapus karena sedang digunakan');
            $this->redirect('jabatan');
            return;
        }

        $result = $this->jabatanModel->delete($id);

        if ($result) {
            Helper::logActivity('Menghapus jabatan: ' . $jabatan->kode_jabatan, 'jabatan');
            $this->setFlash('success', 'Jabatan berhasil dihapus');
        } else {
            $this->setFlash('error', 'Gagal menghapus jabatan');
        }

        $this->redirect('jabatan');
    }
}
