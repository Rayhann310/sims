<?php

class PengaturanAbsensi extends Controller {
    public function __construct()
    {
        // Only allow admin
        if(!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            header('Location: ' . BASEURL . '/login');
            exit;
        }
    }

    public function index()
    {
        $data['judul'] = 'Pengaturan Absensi';
        
        $model = $this->model('PengaturanAbsensiModel');
        $data['global'] = $model->getPengaturanGlobal();
        $data['guru'] = $model->getPengaturanGuruAll();

        // Get list of all gurus for the add-override dropdown
        $this->model('SiswaModel'); // load the db via something else if needed
        $db = new Database();
        $db->query("SELECT g.id, g.nip, u.nama_lengkap FROM guru g JOIN users u ON g.user_id = u.id");
        $data['list_guru'] = $db->resultSet();

        $this->view('templates/admin_header', $data);
        $this->view('pengaturan_absensi/index', $data);
        $this->view('templates/admin_footer');
    }

    public function updateGlobal()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if ($this->model('PengaturanAbsensiModel')->updatePengaturanGlobal($_POST) >= 0) {
                $_SESSION['flash'] = ['pesan' => 'berhasil', 'aksi' => 'diperbarui', 'tipe' => 'success'];
            } else {
                $_SESSION['flash'] = ['pesan' => 'gagal', 'aksi' => 'diperbarui', 'tipe' => 'red'];
            }
        }
        header('Location: ' . BASEURL . '/PengaturanAbsensi');
        exit;
    }

    public function setGuru()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if ($this->model('PengaturanAbsensiModel')->setPengaturanGuru($_POST) > 0) {
                $_SESSION['flash'] = ['pesan' => 'berhasil', 'aksi' => 'ditambahkan/diubah', 'tipe' => 'success'];
            } else {
                $_SESSION['flash'] = ['pesan' => 'gagal', 'aksi' => 'ditambahkan', 'tipe' => 'red'];
            }
        }
        header('Location: ' . BASEURL . '/PengaturanAbsensi');
        exit;
    }

    public function deleteGuru($guru_id)
    {
        if ($this->model('PengaturanAbsensiModel')->deletePengaturanGuru($guru_id) > 0) {
            $_SESSION['flash'] = ['pesan' => 'berhasil', 'aksi' => 'dihapus', 'tipe' => 'success'];
        } else {
            $_SESSION['flash'] = ['pesan' => 'gagal', 'aksi' => 'dihapus', 'tipe' => 'red'];
        }
        header('Location: ' . BASEURL . '/PengaturanAbsensi');
        exit;
    }
}
