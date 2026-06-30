<?php

class Keuangan extends Controller {

    public function __construct()
    {
        if(!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'admin') {
            header('Location: ' . BASEURL . '/login');
            exit;
        }
    }

    public function index()
    {
        // Default ke halaman tagihan
        header('Location: ' . BASEURL . '/keuangan/tagihan');
        exit;
    }

    public function tagihan()
    {
        $data['judul'] = 'Data Tagihan SPP';
        $data['tagihan'] = $this->model('KeuanganModel')->getAllTagihan();
        
        $this->view('templates/admin_header', $data);
        $this->view('keuangan/tagihan', $data);
        $this->view('templates/admin_footer');
    }

    public function riwayat()
    {
        $data['judul'] = 'Riwayat Pembayaran';
        
        $tahun_list = $this->model('KeuanganModel')->getTahunPembayaran();
        $data['tahun_tersedia'] = array_column($tahun_list, 'tahun');
        
        $tahun = isset($_GET['tahun']) ? $_GET['tahun'] : (isset($data['tahun_tersedia'][0]) ? $data['tahun_tersedia'][0] : date('Y'));
        $data['tahun_aktif'] = $tahun;
        
        $data['riwayat_siswa'] = $this->model('KeuanganModel')->getRiwayatPembayaranBySiswa($tahun);
        
        $this->view('templates/admin_header', $data);
        $this->view('keuangan/riwayat', $data);
        $this->view('templates/admin_footer');
    }

    public function generateTagihan()
    {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $inserted = $this->model('KeuanganModel')->generateTagihanMasal($_POST);
            
            if($inserted > 0) {
                $_SESSION['flash'] = ['pesan' => "$inserted tagihan baru", 'aksi' => 'berhasil digenerate', 'tipe' => 'success'];
            } else {
                $_SESSION['flash'] = ['pesan' => 'Generate gagal', 'aksi' => 'atau tagihan untuk bulan tersebut sudah ada untuk semua siswa', 'tipe' => 'warning'];
            }
            header('Location: ' . BASEURL . '/keuangan/tagihan');
            exit;
        }
    }

    public function bayar()
    {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            if($this->model('KeuanganModel')->prosesPembayaran($_POST) > 0) {
                $_SESSION['flash'] = ['pesan' => 'Pembayaran', 'aksi' => 'berhasil diproses', 'tipe' => 'success'];
            } else {
                $_SESSION['flash'] = ['pesan' => 'Pembayaran', 'aksi' => 'gagal diproses', 'tipe' => 'danger'];
            }
            header('Location: ' . BASEURL . '/keuangan/tagihan');
            exit;
        }
    }
    public function kirimWA($tagihan_id)
    {
        if(isset($tagihan_id)) {
            $this->model('KeuanganModel')->sendFonnteWA($tagihan_id);
            $_SESSION['flash'] = ['pesan' => 'Notifikasi WA', 'aksi' => 'sedang dikirim di latar belakang', 'tipe' => 'success'];
        }
        header('Location: ' . BASEURL . '/keuangan/tagihan');
        exit;
    }
}
