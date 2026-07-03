<?php

class JadwalUjian extends Controller {

    public function __construct()
    {
        requireAccess('cbt_jadwal');
    }

    public function index()
    {
        $data['judul'] = 'Manajemen Jadwal Ujian & Pengawas';
        $data['jadwal'] = $this->model('JadwalUjianModel')->getAllJadwal();
        
        $this->view('templates/admin_header', $data);
        $this->view('jadwal_ujian/index', $data);
        $this->view('templates/admin_footer');
    }

    public function tambah()
    {
        $data['judul'] = 'Tambah Jadwal Ujian';
        $data['guru'] = $this->model('JadwalUjianModel')->getAllGuru();
        
        $this->view('templates/admin_header', $data);
        $this->view('jadwal_ujian/form', $data);
        $this->view('templates/admin_footer');
    }

    public function simpan()
    {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            if($this->model('JadwalUjianModel')->tambahDataJadwal($_POST) > 0) {
                Flasher::setFlash('Jadwal Ujian berhasil', 'ditambahkan', 'success');
            } else {
                Flasher::setFlash('Jadwal Ujian gagal', 'ditambahkan', 'danger');
            }
            header('Location: ' . BASEURL . '/JadwalUjian');
            exit;
        }
    }

    public function hapus($id)
    {
        if($this->model('JadwalUjianModel')->hapusDataJadwal($id) > 0) {
            Flasher::setFlash('Jadwal Ujian berhasil', 'dihapus', 'success');
        } else {
            Flasher::setFlash('Jadwal Ujian gagal', 'dihapus', 'danger');
        }
        header('Location: ' . BASEURL . '/JadwalUjian');
        exit;
    }
}
