<?php

class Pengaturan extends Controller {
    public function __construct()
    {
        if(!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'admin') {
            header('Location: ' . BASEURL . '/login');
            exit;
        }
    }

    public function index()
    {
        $data['judul'] = 'Pengaturan Sistem';
        $data['pengaturan'] = $this->model('PengaturanModel')->getPengaturan();
        
        $this->view('templates/admin_header', $data);
        $this->view('pengaturan/index', $data);
        $this->view('templates/admin_footer');
    }

    public function update()
    {
        if($this->model('PengaturanModel')->updatePengaturan($_POST) > 0) {
            $_SESSION['flash'] = ['pesan' => 'Pengaturan berhasil', 'aksi' => 'diperbarui', 'tipe' => 'success'];
        } else {
            $_SESSION['flash'] = ['pesan' => 'Pengaturan gagal', 'aksi' => 'diperbarui', 'tipe' => 'danger'];
        }
        header('Location: ' . BASEURL . '/pengaturan');
        exit;
    }

    public function repair()
    {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            try {
                // Jalankan self-healing manual
                $userModel = $this->model('UserModel');
                $userModel->selfHealing();
                
                $_SESSION['flash'] = ['pesan' => 'Database berhasil', 'aksi' => 'diperbaiki & self-healing dijalankan', 'tipe' => 'success'];
            } catch (Exception $e) {
                $_SESSION['flash'] = ['pesan' => 'Gagal repair: ' . $e->getMessage(), 'aksi' => 'ditolak', 'tipe' => 'danger'];
            }
            header('Location: ' . BASEURL . '/pengaturan');
            exit;
        }
    }
}
