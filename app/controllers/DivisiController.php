<?php
/**
 * DivisiController.php - Divisi Controller
 * Handles CRUD for Divisi (Admin only)
 */

class DivisiController extends Controller
{
    private $divisiModel;

    public function __construct()
    {
        $this->requireRole('admin');
        $this->divisiModel = $this->model('Divisi');
    }

    public function index()
    {
        $divisi = $this->divisiModel->getWithStats();

        $data = [
            'pageTitle' => 'Master Divisi',
            'currentPage' => 'divisi',
            'divisi' => $divisi
        ];

        $this->view('layouts/header', $data);
        $this->view('layouts/sidebar', $data);
        $this->view('divisi/index', $data);
        $this->view('layouts/footer', $data);
    }

    public function create()
    {
        $data = [
            'pageTitle' => 'Tambah Divisi',
            'currentPage' => 'divisi'
        ];

        $this->view('layouts/header', $data);
        $this->view('layouts/sidebar', $data);
        $this->view('divisi/create', $data);
        $this->view('layouts/footer', $data);
    }

    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('divisi');
            return;
        }

        $kode = $this->post('kode_divisi');
        $nama = $this->post('nama_divisi');
        $deskripsi = $this->post('deskripsi');

        $validation = new Validation();
        $validation->required('kode_divisi', $kode, 'Kode Divisi')
                   ->required('nama_divisi', $nama, 'Nama Divisi');

        if ($this->divisiModel->kodeExists($kode)) {
            $validation->addError('kode_divisi', 'Kode divisi sudah digunakan');
        }

        if ($validation->failed()) {
            Session::set('errors', $validation->getErrors());
            Session::set('old', $_POST);
            $this->redirect('divisi/create');
            return;
        }

        $result = $this->divisiModel->insert([
            'kode_divisi' => $kode,
            'nama_divisi' => $nama,
            'deskripsi' => $deskripsi,
            'is_active' => 1
        ]);

        if ($result) {
            Helper::logActivity('Menambah divisi: ' . $kode, 'divisi');
            $this->setFlash('success', 'Divisi berhasil ditambahkan');
        } else {
            $this->setFlash('error', 'Gagal menambahkan divisi');
        }

        $this->redirect('divisi');
    }

    public function edit($id)
    {
        $divisi = $this->divisiModel->getById($id);

        if (!$divisi) {
            $this->setFlash('error', 'Divisi tidak ditemukan');
            $this->redirect('divisi');
            return;
        }

        $data = [
            'pageTitle' => 'Edit Divisi',
            'currentPage' => 'divisi',
            'divisi' => $divisi
        ];

        $this->view('layouts/header', $data);
        $this->view('layouts/sidebar', $data);
        $this->view('divisi/edit', $data);
        $this->view('layouts/footer', $data);
    }

    public function update($id)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('divisi');
            return;
        }

        $divisi = $this->divisiModel->getById($id);
        if (!$divisi) {
            $this->setFlash('error', 'Divisi tidak ditemukan');
            $this->redirect('divisi');
            return;
        }

        $kode = $this->post('kode_divisi');
        $nama = $this->post('nama_divisi');
        $deskripsi = $this->post('deskripsi');
        $isActive = $this->post('is_active', 1);

        $validation = new Validation();
        $validation->required('kode_divisi', $kode, 'Kode Divisi')
                   ->required('nama_divisi', $nama, 'Nama Divisi');

        if ($this->divisiModel->kodeExists($kode, $id)) {
            $validation->addError('kode_divisi', 'Kode divisi sudah digunakan');
        }

        if ($validation->failed()) {
            Session::set('errors', $validation->getErrors());
            Session::set('old', $_POST);
            $this->redirect('divisi/edit/' . $id);
            return;
        }

        $result = $this->divisiModel->update($id, [
            'kode_divisi' => $kode,
            'nama_divisi' => $nama,
            'deskripsi' => $deskripsi,
            'is_active' => $isActive
        ]);

        if ($result) {
            Helper::logActivity('Mengupdate divisi: ' . $kode, 'divisi');
            $this->setFlash('success', 'Divisi berhasil diupdate');
        } else {
            $this->setFlash('error', 'Gagal mengupdate divisi');
        }

        $this->redirect('divisi');
    }

    public function delete($id)
    {
        $divisi = $this->divisiModel->getById($id);
        
        if (!$divisi) {
            $this->setFlash('error', 'Divisi tidak ditemukan');
            $this->redirect('divisi');
            return;
        }

        if (!$this->divisiModel->canDelete($id)) {
            $this->setFlash('error', 'Divisi tidak dapat dihapus karena sedang digunakan');
            $this->redirect('divisi');
            return;
        }

        $result = $this->divisiModel->delete($id);

        if ($result) {
            Helper::logActivity('Menghapus divisi: ' . $divisi->kode_divisi, 'divisi');
            $this->setFlash('success', 'Divisi berhasil dihapus');
        } else {
            $this->setFlash('error', 'Gagal menghapus divisi');
        }

        $this->redirect('divisi');
    }
}
