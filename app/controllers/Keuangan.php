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
        $data['judul'] = 'Data Tagihan & Pembayaran';
        $data['tagihan'] = $this->model('KeuanganModel')->getAllTagihan();
        $data['kategori'] = $this->model('KeuanganModel')->getAllKategori();
        
        $this->view('templates/admin_header', $data);
        $this->view('keuangan/tagihan', $data);
        $this->view('templates/admin_footer');
    }

    public function tarif()
    {
        $data['judul'] = 'Master Tarif Keuangan';
        $data['kategori'] = $this->model('KeuanganModel')->getAllKategori();
        
        $this->view('templates/admin_header', $data);
        $this->view('keuangan/tarif', $data);
        $this->view('templates/admin_footer');
    }

    public function tambahTarif()
    {
        if(isset($_POST['nama_kategori'])) {
            if($this->model('KeuanganModel')->tambahKategori($_POST) > 0) {
                Flasher::setFlash('Tarif berhasil', 'ditambahkan', 'success');
            } else {
                Flasher::setFlash('Tarif gagal', 'ditambahkan', 'danger');
            }
        }
        header('Location: ' . BASEURL . '/keuangan/tarif');
        exit;
    }

    public function ubahTarif()
    {
        if(isset($_POST['id']) && isset($_POST['nama_kategori'])) {
            if($this->model('KeuanganModel')->ubahKategori($_POST) > 0) {
                Flasher::setFlash('Tarif berhasil', 'diubah', 'success');
            } else {
                Flasher::setFlash('Tarif gagal', 'diubah', 'danger');
            }
        }
        header('Location: ' . BASEURL . '/keuangan/tarif');
        exit;
    }

    public function hapusTarif($id)
    {
        if($this->model('KeuanganModel')->hapusKategori($id) > 0) {
            Flasher::setFlash('Tarif berhasil', 'dihapus', 'success');
        } else {
            Flasher::setFlash('Tarif gagal', 'dihapus', 'danger');
        }
        header('Location: ' . BASEURL . '/keuangan/tarif');
        exit;
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

    public function kirimTagihanWA($tagihan_id)
    {
        if(isset($tagihan_id)) {
            if($this->model('KeuanganModel')->sendFonnteTagihanWA($tagihan_id)) {
                $_SESSION['flash'] = ['pesan' => 'Tagihan WA', 'aksi' => 'berhasil dikirim ke orang tua', 'tipe' => 'success'];
            } else {
                $_SESSION['flash'] = ['pesan' => 'Tagihan WA', 'aksi' => 'gagal dikirim (cek token/nomor)', 'tipe' => 'danger'];
            }
        }
        header('Location: ' . BASEURL . '/keuangan/tagihan');
        exit;
    }

    // ==========================================
    // BUKU KAS & ANALISA KEUANGAN
    // ==========================================

    public function bukuKas()
    {
        $data['judul'] = 'Buku Kas & Analisa Keuangan';
        
        $bulan = isset($_GET['bulan']) ? $_GET['bulan'] : date('m');
        $tahun = isset($_GET['tahun']) ? $_GET['tahun'] : date('Y');
        
        if (isset($_GET['filter']) && $_GET['filter'] == 'semua') {
            $bulan = '';
            $tahun = '';
        }

        $data['kas'] = $this->model('KeuanganModel')->getAllKas($bulan, $tahun);
        $data['statistik'] = $this->model('KeuanganModel')->getStatistikKas();
        $data['chart'] = $this->model('KeuanganModel')->getChartData(date('Y'));
        
        $data['filter_bulan'] = $bulan;
        $data['filter_tahun'] = $tahun ?: date('Y');

        $this->view('templates/admin_header', $data);
        $this->view('keuangan/buku_kas', $data);
        $this->view('templates/admin_footer');
    }

    public function prosesTambahKas()
    {
        if(isset($_POST['jenis'])) {
            if($this->model('KeuanganModel')->tambahKas($_POST) > 0) {
                Flasher::setFlash('Data Kas berhasil', 'ditambahkan', 'success');
            } else {
                Flasher::setFlash('Data Kas gagal', 'ditambahkan', 'danger');
            }
        }
        header('Location: ' . BASEURL . '/keuangan/bukuKas');
        exit;
    }

    public function hapusKas($id)
    {
        if($this->model('KeuanganModel')->hapusKas($id) > 0) {
            Flasher::setFlash('Data Kas berhasil', 'dihapus', 'success');
        } else {
            Flasher::setFlash('Data Kas gagal', 'dihapus (Mungkin terikat dengan SPP)', 'danger');
        }
        header('Location: ' . BASEURL . '/keuangan/bukuKas');
        exit;
    }

    public function exportExcelKas()
    {
        $bulan = isset($_GET['bulan']) ? $_GET['bulan'] : '';
        $tahun = isset($_GET['tahun']) ? $_GET['tahun'] : '';
        
        $kas = $this->model('KeuanganModel')->getAllKas($bulan, $tahun);
        
        $filename = "Buku_Kas_" . ($bulan ? $bulan . "_" : "") . ($tahun ? $tahun : "Semua") . ".csv";
        
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=' . $filename);
        
        $output = fopen('php://output', 'w');
        fputcsv($output, ['ID', 'Tanggal', 'Jenis', 'Sumber', 'Keterangan', 'Nominal']);
        
        foreach($kas as $row) {
            fputcsv($output, [
                $row['id'],
                $row['tanggal'],
                $row['jenis'],
                $row['sumber'],
                $row['keterangan'],
                $row['nominal']
            ]);
        }
        fclose($output);
        exit;
    }
}
