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
}
