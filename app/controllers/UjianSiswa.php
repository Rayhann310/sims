<?php

class UjianSiswa extends Controller {

    public function __construct()
    {
        if(!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'siswa') {
            header('Location: ' . BASEURL . '/login');
            exit;
        }
    }

    public function index()
    {
        $data['judul'] = 'Ujian CBT';
        $data['jadwal'] = $this->model('UjianSiswaModel')->getJadwalAktif();
        
        $this->view('templates/header', $data); // Asumsikan ada header siswa biasa
        $this->view('cbt/dashboard', $data);
        $this->view('templates/footer');
    }

    public function mulai($id_jadwal)
    {
        // Fitur lock screen dll ada di sini
        $data['judul'] = 'Mengerjakan Ujian';
        // Ambil info ujian
        // Validasi token (di-skip untuk demo cepat)
        
        // Load view khusus tanpa header/footer biasa agar bisa full screen murni
        $this->view('cbt/ruang_ujian', $data);
    }

    // Endpoint API yang dipanggil Javascript untuk mengunci siswa jika melanggar
    public function lockApi()
    {
        header('Content-Type: application/json');
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_peserta = $_POST['id_peserta'] ?? 0;
            $alasan = $_POST['alasan'] ?? 'Keluar Fullscreen / Pindah Tab';
            
            $this->model('UjianSiswaModel')->kunciPeserta($id_peserta, $alasan);
            
            echo json_encode(['status' => true, 'message' => 'Peserta dikunci']);
            exit;
        }
    }
}
