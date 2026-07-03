<?php

class Proctor extends Controller {

    public function __construct()
    {
        requireAccess('cbt_proctor');
    }

    public function index()
    {
        $data['judul'] = 'Dashboard Pengawas Ruangan';
        // Ambil data guru yang login (asumsi id guru tersimpan di session, untuk demo kita ambil id_guru dari profil user)
        $id_guru = $_SESSION['user']['id_guru'] ?? 1; // Sesuaikan dengan struktur session asli
        
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
