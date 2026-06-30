<?php

class OrangTua extends Controller {
    public function __construct()
    {
        if(!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'admin') {
            header('Location: ' . BASEURL . '/login');
            exit;
        }
    }

    public function index()
    {
        $data['judul'] = 'Manajemen Data Orang Tua / Wali';
        
        $filters = [
            'kelas' => $_GET['kelas'] ?? '',
            'jk' => $_GET['jk'] ?? '',
            'status' => $_GET['status'] ?? 'Aktif'
        ];
        
        $data['filters'] = $filters;
        $data['siswa'] = $this->model('SiswaModel')->getAllSiswa($filters);
        $data['filter_options'] = $this->model('SiswaModel')->getFilterOptions();

        $this->view('templates/admin_header', $data);
        $this->view('orangtua/index', $data);
        $this->view('templates/admin_footer');
    }

    public function ubah()
    {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['id'] ?? '';
            $nama_wali = $_POST['nama_wali'] ?? '';
            $no_hp_wali = $_POST['no_hp_wali'] ?? '';
            
            $db = new Database();
            $db->query("UPDATE siswa SET nama_wali = :nama_wali, no_hp_wali = :no_hp_wali WHERE id = :id");
            $db->bind('nama_wali', htmlspecialchars($nama_wali));
            $db->bind('no_hp_wali', htmlspecialchars($no_hp_wali));
            $db->bind('id', $id);
            
            if($db->execute()) {
                $_SESSION['flash'] = ['pesan' => 'Data Wali berhasil diperbarui', 'aksi' => '', 'tipe' => 'success'];
            } else {
                $_SESSION['flash'] = ['pesan' => 'Gagal', 'aksi' => 'memperbarui data wali', 'tipe' => 'danger'];
            }
            header('Location: ' . BASEURL . '/orangtua');
            exit;
        }
    }
}
