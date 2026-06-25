<?php

class Jabatan extends Controller {
    public function __construct()
    {
        if(!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'admin') {
            header('Location: ' . BASEURL . '/login');
            exit;
        }
    }

    public function index()
    {
        $data['judul'] = 'Master Jabatan Guru';
        $data['jabatan'] = $this->model('JabatanModel')->getAll();
        $this->view('templates/admin_header', $data);
        $this->view('jabatan/index', $data);
        $this->view('templates/admin_footer');
    }

    public function tambah()
    {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $model = $this->model('JabatanModel');
            if($model->tambah($_POST) > 0) {
                $_SESSION['flash'] = ['pesan' => 'Jabatan berhasil', 'aksi' => 'ditambahkan', 'tipe' => 'success'];
            } else {
                $_SESSION['flash'] = ['pesan' => 'Jabatan gagal', 'aksi' => 'ditambahkan', 'tipe' => 'danger'];
            }
        }
        header('Location: ' . BASEURL . '/jabatan');
        exit;
    }

    public function ubah()
    {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $model = $this->model('JabatanModel');
            $model->ubah($_POST);
            $_SESSION['flash'] = ['pesan' => 'Jabatan berhasil', 'aksi' => 'diperbarui', 'tipe' => 'success'];
        }
        header('Location: ' . BASEURL . '/jabatan');
        exit;
    }

    public function hapus($id)
    {
        $model = $this->model('JabatanModel');
        // Cek apakah jabatan dipakai oleh guru
        $count = $model->countGuruByJabatan($id);
        if($count > 0) {
            $_SESSION['flash'] = ['pesan' => 'Jabatan tidak dapat dihapus karena masih dipakai oleh ' . $count . ' guru', 'aksi' => '', 'tipe' => 'danger'];
        } else {
            $model->hapus($id);
            $_SESSION['flash'] = ['pesan' => 'Jabatan berhasil', 'aksi' => 'dihapus', 'tipe' => 'success'];
        }
        header('Location: ' . BASEURL . '/jabatan');
        exit;
    }

    public function getJson()
    {
        header('Content-Type: application/json');
        echo json_encode($this->model('JabatanModel')->getAll());
        exit;
    }
}
