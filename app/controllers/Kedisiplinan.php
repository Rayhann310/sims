<?php

class Kedisiplinan extends Controller {
    public function __construct()
    {
        if(!isset($_SESSION['user'])) {
            header('Location: ' . BASEURL . '/login');
            exit;
        }
    }

    public function kategori()
    {
        if($_SESSION['user']['role'] != 'admin' && $_SESSION['user']['role'] != 'guru') {
            header('Location: ' . BASEURL . '/dashboard');
            exit;
        }

        $data['judul'] = 'Kategori Kedisiplinan';
        $data['kategori'] = $this->model('KedisiplinanModel')->getAllKategori();
        
        $this->view('templates/admin_header', $data);
        $this->view('kedisiplinan/kategori', $data);
        $this->view('templates/admin_footer');
    }

    public function tambahKategori()
    {
        if($this->model('KedisiplinanModel')->tambahKategori($_POST) > 0) {
            Flasher::setFlash('berhasil', 'ditambahkan', 'success');
        } else {
            Flasher::setFlash('gagal', 'ditambahkan', 'danger');
        }
        header('Location: ' . BASEURL . '/kedisiplinan/kategori');
        exit;
    }

    public function hapusKategori($id)
    {
        if($this->model('KedisiplinanModel')->hapusKategori($id) > 0) {
            Flasher::setFlash('berhasil', 'dihapus', 'success');
        } else {
            Flasher::setFlash('gagal', 'dihapus', 'danger');
        }
        header('Location: ' . BASEURL . '/kedisiplinan/kategori');
        exit;
    }

    public function rekap()
    {
        if($_SESSION['user']['role'] != 'admin' && $_SESSION['user']['role'] != 'guru') {
            header('Location: ' . BASEURL . '/dashboard');
            exit;
        }

        $data['judul'] = 'Rekap Kedisiplinan Siswa';
        $data['siswa'] = $this->model('KedisiplinanModel')->getRekapSiswa();
        
        $this->view('templates/admin_header', $data);
        $this->view('kedisiplinan/rekap', $data);
        $this->view('templates/admin_footer');
    }

    public function riwayat($siswa_id = null)
    {
        $role = $_SESSION['user']['role'];
        
        // Cek jika siswa, hanya bisa melihat riwayatnya sendiri
        if($role == 'siswa') {
            $db = new Database();
            $db->query("SELECT id FROM siswa WHERE user_id = :user_id");
            $db->bind('user_id', $_SESSION['user']['id']);
            $s = $db->single();
            if($s) {
                $siswa_id = $s['id'];
            } else {
                header('Location: ' . BASEURL . '/dashboard');
                exit;
            }
        } else if($role != 'admin' && $role != 'guru') {
            header('Location: ' . BASEURL . '/dashboard');
            exit;
        }

        if(!$siswa_id) {
            header('Location: ' . BASEURL . '/kedisiplinan/rekap');
            exit;
        }

        // Get Siswa detail
        $db = new Database();
        $db->query("SELECT s.*, u.nama_lengkap FROM siswa s LEFT JOIN users u ON s.user_id = u.id WHERE s.id = :id");
        $db->bind('id', $siswa_id);
        $data['siswa'] = $db->single();

        if(!$data['siswa']) {
            Flasher::setFlash('Siswa', 'tidak ditemukan', 'danger');
            header('Location: ' . BASEURL . '/kedisiplinan/rekap');
            exit;
        }

        $nama_lengkap = $data['siswa']['nama_lengkap'] ?? 'Siswa (Tanpa Nama)';
        $data['siswa']['nama_lengkap'] = $nama_lengkap;
        $data['judul'] = 'Riwayat Kedisiplinan: ' . $nama_lengkap;
        $data['riwayat'] = $this->model('KedisiplinanModel')->getRiwayatBySiswa($siswa_id);
        $data['kategori'] = $this->model('KedisiplinanModel')->getAllKategori();
        
        $this->view('templates/admin_header', $data);
        $this->view('kedisiplinan/riwayat', $data);
        $this->view('templates/admin_footer');
    }

    public function tambahCatatan()
    {
        if($this->model('KedisiplinanModel')->tambahCatatan($_POST) > 0) {
            Flasher::setFlash('Catatan berhasil', 'ditambahkan', 'success');
        } else {
            Flasher::setFlash('Catatan gagal', 'ditambahkan', 'danger');
        }
        header('Location: ' . BASEURL . '/kedisiplinan/riwayat/' . $_POST['siswa_id']);
        exit;
    }
}
