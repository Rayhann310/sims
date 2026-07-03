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
        $data['rombel'] = $this->model('JadwalUjianModel')->getAllRombel();
        
        $this->view('templates/admin_header', $data);
        $this->view('jadwal_ujian/form', $data);
        $this->view('templates/admin_footer');
    }

    public function simpan()
    {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $rombels = $_POST['id_rombel'] ?? []; // Can be array of IDs
            $success = false;

            if (is_array($rombels) && count($rombels) > 0) {
                // Opsi B: Gandakan jadwal per rombel
                foreach ($rombels as $r_id) {
                    $_POST['id_rombel'] = $r_id;
                    if ($this->model('JadwalUjianModel')->tambahDataJadwal($_POST) > 0) {
                        $success = true;
                    }
                }
            } else {
                // Tidak ada rombel yang dipilih, simpan as null
                $_POST['id_rombel'] = null;
                if ($this->model('JadwalUjianModel')->tambahDataJadwal($_POST) > 0) {
                    $success = true;
                }
            }

            if($success) {
                Flasher::setFlash('Jadwal Ujian berhasil', 'ditambahkan', 'success');
            } else {
                Flasher::setFlash('Jadwal Ujian gagal', 'ditambahkan', 'danger');
            }
            header('Location: ' . BASEURL . '/JadwalUjian');
            exit;
        }
    }

    public function edit($id)
    {
        $data['judul'] = 'Edit Jadwal Ujian';
        $data['jadwal'] = $this->model('JadwalUjianModel')->getJadwalById($id);
        
        if(!$data['jadwal']) {
            Flasher::setFlash('Jadwal Ujian tidak', 'ditemukan', 'danger');
            header('Location: ' . BASEURL . '/JadwalUjian');
            exit;
        }

        $data['guru'] = $this->model('JadwalUjianModel')->getAllGuru();
        $data['mapel'] = $this->model('JadwalUjianModel')->getAllMapel();
        $data['rombel'] = $this->model('JadwalUjianModel')->getAllRombel();
        
        $this->view('templates/admin_header', $data);
        $this->view('jadwal_ujian/form_edit', $data);
        $this->view('templates/admin_footer');
    }

    public function update()
    {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            if($this->model('JadwalUjianModel')->editDataJadwal($_POST) > 0) {
                Flasher::setFlash('Jadwal Ujian berhasil', 'diperbarui', 'success');
            } else {
                Flasher::setFlash('Jadwal Ujian gagal', 'diperbarui', 'danger');
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

    public function hasil($id)
    {
        $data['judul'] = 'Hasil Ujian Siswa';
        $data['jadwal'] = $this->model('JadwalUjianModel')->getJadwalById($id);
        
        if(!$data['jadwal']) {
            Flasher::setFlash('Jadwal Ujian tidak', 'ditemukan', 'danger');
            header('Location: ' . BASEURL . '/JadwalUjian');
            exit;
        }
        
        $data['hasil'] = $this->model('JadwalUjianModel')->getHasilUjian($id);
        
        $this->view('templates/admin_header', $data);
        $this->view('jadwal_ujian/hasil', $data);
        $this->view('templates/admin_footer');
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
