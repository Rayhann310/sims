<?php

class Kearsipan extends Controller {
    public function __construct()
    {
        if(!isset($_SESSION['user'])) {
            header('Location: ' . BASEURL . '/login');
            exit;
        }

        // Only Admin/TU can access Kearsipan
        if($_SESSION['user']['role'] != 'admin') {
            header('Location: ' . BASEURL . '/dashboard');
            exit;
        }
    }

    public function index()
    {
        $data['judul'] = 'Manajemen Kearsipan';
        $data['surat'] = $this->model('KearsipanModel')->getAllSurat();

        $db = new Database();
        $db->query("SELECT COUNT(id) as total FROM kearsipan");
        $data['total_surat'] = $db->single()['total'] ?? 0;
        
        $db->query("SELECT COUNT(id) as total FROM kearsipan WHERE jenis_surat = 'Masuk'");
        $data['surat_masuk'] = $db->single()['total'] ?? 0;
        
        $db->query("SELECT COUNT(id) as total FROM kearsipan WHERE jenis_surat = 'Keluar'");
        $data['surat_keluar'] = $db->single()['total'] ?? 0;

        $this->view('templates/admin_header', $data);
        $this->view('kearsipan/index', $data);
        $this->view('templates/admin_footer');
    }

    public function tambah()
    {
        if($this->model('KearsipanModel')->tambahSurat($_POST, $_FILES) > 0) {
            Flasher::setFlash('Surat berhasil', 'ditambahkan', 'success');
        } else {
            Flasher::setFlash('Surat gagal', 'ditambahkan', 'danger');
        }
        header('Location: ' . BASEURL . '/kearsipan');
        exit;
    }

    public function hapus($id)
    {
        if($this->model('KearsipanModel')->hapusSurat($id) > 0) {
            Flasher::setFlash('Surat berhasil', 'dihapus', 'success');
        } else {
            Flasher::setFlash('Surat gagal', 'dihapus', 'danger');
        }
        header('Location: ' . BASEURL . '/kearsipan');
        exit;
    }
}
