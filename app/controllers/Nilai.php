<?php

class Nilai extends Controller {

    public function __construct()
    {
        if(!isset($_SESSION['user'])) {
            header('Location: ' . BASEURL . '/login');
            exit;
        }
    }

    public function index()
    {
        $data['judul'] = 'Presensi & Nilai Siswa';
        $user_id = $_SESSION['user']['id'];
        $role = $_SESSION['user']['role'];
        
        if($role == 'guru') {
            $data['jadwal'] = $this->model('NilaiModel')->getJadwalByGuru($user_id);
        } else {
            // Admin bisa melihat semua, kita asumsikan guru 1
            $data['jadwal'] = $this->model('NilaiModel')->getJadwalByGuru(1);
        }
        
        $this->view('templates/admin_header', $data);
        $this->view('nilai/index', $data);
        $this->view('templates/admin_footer');
    }

    public function detail($jadwal_id)
    {
        $data['judul'] = 'Kelola Presensi & Nilai';
        $data['jadwal_id'] = $jadwal_id;
        
        $data['siswa'] = $this->model('NilaiModel')->getSiswaByJadwal($jadwal_id);
        
        // Default tanggal hari ini
        $data['tanggal'] = isset($_GET['tanggal']) ? $_GET['tanggal'] : date('Y-m-d');
        
        // Default jenis nilai
        $data['jenis_nilai'] = isset($_GET['jenis']) ? $_GET['jenis'] : 'Tugas 1';
        $data['list_jenis_nilai'] = ['Tugas 1', 'Tugas 2', 'Tugas 3', 'UTS', 'UAS'];
        
        // Ambil presensi existing
        $presensi_raw = $this->model('NilaiModel')->getPresensiByTanggal($jadwal_id, $data['tanggal']);
        $data['presensi'] = [];
        foreach($presensi_raw as $p) {
            $data['presensi'][$p['siswa_id']] = $p['status'];
        }

        // Ambil nilai existing
        $data['nilai'] = $this->model('NilaiModel')->getNilaiByJenis($jadwal_id, $data['jenis_nilai']);
        
        $this->view('templates/admin_header', $data);
        $this->view('nilai/detail', $data);
        $this->view('templates/admin_footer');
    }

    public function simpanPresensi()
    {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $jadwal_id = $_POST['jadwal_id'];
            $tanggal = $_POST['tanggal'];
            $presensi = isset($_POST['presensi']) ? $_POST['presensi'] : [];
            
            $inserted = $this->model('NilaiModel')->simpanPresensiMassal($jadwal_id, $tanggal, $presensi);
            
            $_SESSION['flash'] = ['pesan' => 'Data presensi berhasil', 'aksi' => 'disimpan untuk tanggal ' . $tanggal, 'tipe' => 'success'];
            header('Location: ' . BASEURL . '/nilai/detail/' . $jadwal_id . '?tab=presensi&tanggal=' . $tanggal);
            exit;
        }
    }

    public function simpanNilai()
    {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $jadwal_id = $_POST['jadwal_id'];
            $jenis_nilai = $_POST['jenis_nilai'];
            $nilai = isset($_POST['nilai']) ? $_POST['nilai'] : [];
            
            $inserted = $this->model('NilaiModel')->simpanNilaiMassal($jadwal_id, $jenis_nilai, $nilai);
            
            $_SESSION['flash'] = ['pesan' => 'Data nilai berhasil', 'aksi' => 'disimpan untuk ' . $jenis_nilai, 'tipe' => 'success'];
            header('Location: ' . BASEURL . '/nilai/detail/' . $jadwal_id . '?tab=nilai&jenis=' . urlencode($jenis_nilai));
            exit;
        }
    }
}
