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
        $model = $this->model('UjianSiswaModel');
        $id_rombel = $model->getSiswaRombelAktif($_SESSION['user']['id']);
        $data['jadwal'] = $model->getJadwalAktif($id_rombel);
        
        $this->view('templates/header', $data); 
        $this->view('cbt/dashboard', $data);
        $this->view('templates/footer');
    }

    public function mulai($id_jadwal)
    {
        $data['judul'] = 'Mengerjakan Ujian';
        
        $model = $this->model('UjianSiswaModel');
        $jadwalModel = $this->model('JadwalUjianModel');
        
        $id_siswa = $model->getIdSiswa($_SESSION['user']['id']);
        
        $peserta = $model->getPesertaData($id_jadwal, $id_siswa);
        if(!$peserta) {
            $model->daftarUjian($id_jadwal, $id_siswa);
            $peserta = $model->getPesertaData($id_jadwal, $id_siswa);
        }
        
        $data['jadwal'] = $jadwalModel->getJadwalById($id_jadwal);
        $data['soal'] = $model->getSoalUjian($id_jadwal);
        $data['peserta'] = $peserta;
        $data['nama_siswa'] = $_SESSION['user']['nama_lengkap'];
        
        $this->view('cbt/ruang_ujian', $data);
    }

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
    
    public function unlockApi()
    {
        header('Content-Type: application/json');
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_peserta = $_POST['id_peserta'] ?? 0;
            $id_jadwal = $_POST['id_jadwal'] ?? 0;
            $token = $_POST['token'] ?? '';
            
            $jadwal = $this->model('JadwalUjianModel')->getJadwalById($id_jadwal);
            if($jadwal && $jadwal['token_aktif'] === strtoupper($token)) {
                // Update status peserta menjadi mengerjakan (1)
                $db = new Database();
                $db->query("UPDATE cbt_peserta SET status_ujian = '1', alasan_terkunci = NULL WHERE id_peserta = :id_peserta");
                $db->bind('id_peserta', $id_peserta);
                $db->execute();
                
                echo json_encode(['status' => true, 'message' => 'Berhasil dibuka']);
            } else {
                echo json_encode(['status' => false, 'message' => 'Token tidak valid']);
            }
            exit;
        }
    }
}
