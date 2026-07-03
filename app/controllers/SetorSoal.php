<?php

class SetorSoal extends Controller {
    
    public function __construct()
    {
        // Pastikan user sudah login
        if(!isset($_SESSION['user'])) {
            header('Location: ' . BASEURL . '/Auth');
            exit;
        }
    }

    public function index()
    {
        $data['judul'] = 'Setor Soal Ujian';
        // Menampilkan semua jadwal ujian agar guru bisa memilih mapelnya
        $data['jadwal'] = $this->model('JadwalUjianModel')->getAllJadwal();
        
        $this->view('templates/admin_header', $data);
        $this->view('setor_soal/index', $data);
        $this->view('templates/admin_footer');
    }

    public function kelola($id)
    {
        $data['judul'] = 'Pilih Soal untuk Disetor';
        $data['jadwal'] = $this->model('JadwalUjianModel')->getJadwalById($id);
        
        if(!$data['jadwal']) {
            Flasher::setFlash('Jadwal Ujian tidak', 'ditemukan', 'danger');
            header('Location: ' . BASEURL . '/SetorSoal');
            exit;
        }
        
        // Ambil soal HANYA milik guru ini untuk mapel tersebut
        $id_mapel = $data['jadwal']['id_mapel'];
        $id_guru = $_SESSION['user']['id'];
        
        // Kita perlu filter getSoalByMapelAndGuru
        $data['soal'] = $this->model('SetorSoalModel')->getSoalByMapelAndGuru($id_mapel, $id_guru);
        $data['terpilih'] = $this->model('JadwalUjianModel')->getSoalTerpilih($id);
        
        $this->view('templates/admin_header', $data);
        $this->view('setor_soal/kelola', $data);
        $this->view('templates/admin_footer');
    }

    public function simpan()
    {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id_jadwal = $_POST['id_jadwal'];
            $soal_ids = $_POST['soal_ids'] ?? [];
            
            // Hapus dan simpan ulang di pivot table cbt_ujian_soal
            if($this->model('JadwalUjianModel')->simpanSoalUjian($id_jadwal, $soal_ids)) {
                Flasher::setFlash('Soal ujian berhasil', 'disetorkan ke operator', 'success');
            } else {
                Flasher::setFlash('Soal ujian gagal', 'disetorkan', 'danger');
            }
            header('Location: ' . BASEURL . '/SetorSoal');
            exit;
        }
    }
}
