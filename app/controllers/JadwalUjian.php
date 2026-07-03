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
        $data['mapel'] = $this->model('JadwalUjianModel')->getAllMapel();
        
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

    public function kelolaSoal($id)
    {
        $data['judul'] = 'Kelola Soal Ujian';
        $data['jadwal'] = $this->model('JadwalUjianModel')->getJadwalById($id);
        
        if(!$data['jadwal']) {
            Flasher::setFlash('Jadwal Ujian tidak', 'ditemukan', 'danger');
            header('Location: ' . BASEURL . '/JadwalUjian');
            exit;
        }
        
        $data['soal'] = $this->model('JadwalUjianModel')->getSoalByMapel($data['jadwal']['id_mapel']);
        $data['terpilih'] = $this->model('JadwalUjianModel')->getSoalTerpilih($id);
        
        $this->view('templates/admin_header', $data);
        $this->view('jadwal_ujian/kelola_soal', $data);
        $this->view('templates/admin_footer');
    }
    
    public function simpanSoal()
    {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id_jadwal = $_POST['id_jadwal'];
            $soal_ids = $_POST['soal_ids'] ?? [];
            
            if($this->model('JadwalUjianModel')->simpanSoalUjian($id_jadwal, $soal_ids)) {
                Flasher::setFlash('Soal ujian berhasil', 'disimpan', 'success');
            } else {
                Flasher::setFlash('Soal ujian gagal', 'disimpan', 'danger');
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
