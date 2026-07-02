<?php

class Pengaturan extends Controller {
    public function __construct()
    {
        requireAccess('pengaturan');
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
    public function fonntelog()
    {
        $data['judul'] = 'Debug API Fonnte';
        
        try {
            $db = new Database();
            $db->query("SELECT * FROM log_fonnte ORDER BY tanggal DESC LIMIT 50");
            $data['logs'] = $db->resultSet();
        } catch (PDOException $e) {
            $data['logs'] = [];
        }
        
        $this->view('templates/admin_header', $data);
        $this->view('pengaturan/fonntelog', $data);
        $this->view('templates/admin_footer');
    }
}
