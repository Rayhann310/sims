<?php

class Proctor extends Controller {

    public function __construct()
    {
        requireAccess('cbt_proctor');
    }

    public function index()
    {
        $data['judul'] = 'Dashboard Pengawas Ruangan';
        $user_id = $_SESSION['user']['id'] ?? 0;
        $id_guru = $this->model('ProctorModel')->getGuruIdByUserId($user_id);
        
        $data['jadwal_diawasi'] = $this->model('ProctorModel')->getJadwalDiawasi($id_guru);
        
        $this->view('templates/admin_header', $data);
        $this->view('proctor/index', $data);
        $this->view('templates/admin_footer');
    }

    public function monitor($id_jadwal)
    {
        $data['judul'] = 'Monitor Siswa Ujian';
        $data['peserta'] = $this->model('ProctorModel')->getPesertaUjian($id_jadwal);
        $data['id_jadwal'] = $id_jadwal;
        $data['jadwal'] = $this->model('JadwalUjianModel')->getJadwalById($id_jadwal);
        
        $this->view('templates/admin_header', $data);
        $this->view('proctor/monitor', $data);
        $this->view('templates/admin_footer');
    }

    public function unlockSiswa($id_peserta, $id_jadwal)
    {
        if($this->model('ProctorModel')->bukaKunciSiswa($id_peserta) > 0) {
            Flasher::setFlash('Akses Ujian Siswa', 'berhasil dibuka kembali', 'success');
        } else {
            Flasher::setFlash('Akses Ujian Siswa', 'gagal dibuka', 'danger');
        }
        header('Location: ' . BASEURL . '/Proctor/monitor/' . $id_jadwal);
        exit;
    }

    public function getMonitorData($id_jadwal)
    {
        // AJAX endpoint
        header('Content-Type: application/json');
        
        $peserta = $this->model('ProctorModel')->getPesertaUjian($id_jadwal);
        $jadwal = $this->model('JadwalUjianModel')->getJadwalById($id_jadwal);
        
        echo json_encode([
            'status' => 'success',
            'peserta' => $peserta,
            'token' => $jadwal['token_aktif'] ?? '------',
            'token_last_update' => $jadwal['token_last_update'] ?? '-'
        ]);
        exit;
    }

    public function refreshToken($id_jadwal)
    {
        // AJAX endpoint
        header('Content-Type: application/json');
        
        // Generate random 6 character alphanumeric token
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $token = '';
        for ($i = 0; $i < 6; $i++) {
            $token .= $characters[rand(0, strlen($characters) - 1)];
        }
        
        if($this->model('ProctorModel')->updateToken($id_jadwal, $token) > 0) {
            echo json_encode(['status' => 'success', 'token' => $token]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Gagal mengupdate token']);
        }
        exit;
    }
}
