<?php

class Komunikasi extends Controller {

    public function __construct()
    {
        if(!isset($_SESSION['user'])) {
            header('Location: ' . BASEURL . '/login');
            exit;
        }
    }

    public function index()
    {
        header('Location: ' . BASEURL . '/komunikasi/pengumuman');
        exit;
    }

    public function pengumuman()
    {
        $data['judul'] = 'Papan Pengumuman';
        $data['pengumuman'] = $this->model('KomunikasiModel')->getAllPengumuman();
        
        $this->view('templates/admin_header', $data);
        $this->view('komunikasi/pengumuman', $data);
        $this->view('templates/admin_footer');
    }

    public function tambahPengumuman()
    {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            if($this->model('KomunikasiModel')->tambahPengumuman($_POST) > 0) {
                $_SESSION['flash'] = ['pesan' => 'Pengumuman baru', 'aksi' => 'berhasil di-posting', 'tipe' => 'success'];
            } else {
                $_SESSION['flash'] = ['pesan' => 'Pengumuman', 'aksi' => 'gagal di-posting', 'tipe' => 'danger'];
            }
            header('Location: ' . BASEURL . '/komunikasi/pengumuman');
            exit;
        }
    }

    public function pesan()
    {
        $data['judul'] = 'Pesan Pribadi';
        $user_id = $_SESSION['user']['id'];
        
        $data['inbox'] = $this->model('KomunikasiModel')->getInbox($user_id);
        $data['sent'] = $this->model('KomunikasiModel')->getSentItems($user_id);
        $data['users'] = $this->model('KomunikasiModel')->getAllUsersForDropdown($user_id);
        
        $this->view('templates/admin_header', $data);
        $this->view('komunikasi/pesan', $data);
        $this->view('templates/admin_footer');
    }

    public function kirimPesan()
    {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            if($this->model('KomunikasiModel')->kirimPesan($_POST) > 0) {
                $_SESSION['flash'] = ['pesan' => 'Pesan', 'aksi' => 'berhasil dikirim', 'tipe' => 'success'];
            } else {
                $_SESSION['flash'] = ['pesan' => 'Pesan', 'aksi' => 'gagal dikirim', 'tipe' => 'danger'];
            }
            header('Location: ' . BASEURL . '/komunikasi/pesan?tab=sent');
            exit;
        }
    }

    public function baca($id)
    {
        // Tandai dibaca
        $this->model('KomunikasiModel')->markAsRead($id, $_SESSION['user']['id']);
        // Redirect kembali ke tab inbox
        header('Location: ' . BASEURL . '/komunikasi/pesan?tab=inbox');
        exit;
    }
}
