<?php

class Elearning extends Controller {

    public function __construct()
    {
        if(!isset($_SESSION['user'])) {
            header('Location: ' . BASEURL . '/login');
            exit;
        }
    }

    public function index()
    {
        $data['judul'] = 'E-Learning';
        $user_id = $_SESSION['user']['id'];
        $role = $_SESSION['user']['role'];
        
        if($role == 'guru') {
            // Ambil guru_id dari user yang login
            $guru_id = $this->model('ElearningModel')->getGuruIdByUserId($user_id);
            if ($guru_id) {
                $data['jadwal'] = $this->model('ElearningModel')->getJadwalByGuru($guru_id);
            } else {
                $data['jadwal'] = [];
            }
        } else if($role == 'siswa') {
            $data['jadwal'] = $this->model('ElearningModel')->getJadwalBySiswa($user_id);
        } else {
            // Admin bisa melihat semua, atau kita handle beda. Untuk saat ini kita asumsikan bisa lihat semua mapel jika butuh
            // Untuk simplifikasi kita pakai getJadwalByGuru 1 sebagai contoh
            $data['jadwal'] = $this->model('ElearningModel')->getJadwalByGuru(1); // placeholder admin
        }
        
        $this->view('templates/admin_header', $data);
        $this->view('elearning/index', $data);
        $this->view('templates/admin_footer');
    }

    public function detail($jadwal_id)
    {
        $data['judul'] = 'Detail Kelas E-Learning';
        $data['jadwal_id'] = $jadwal_id;
        
        $data['materi'] = $this->model('ElearningModel')->getMateriByJadwal($jadwal_id);
        $data['tugas'] = $this->model('ElearningModel')->getTugasByJadwal($jadwal_id);
        $data['diskusi'] = $this->model('ElearningModel')->getDiskusiByJadwal($jadwal_id);
        
        $this->view('templates/admin_header', $data);
        $this->view('elearning/detail', $data);
        $this->view('templates/admin_footer');
    }

    public function tambahMateri()
    {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $jadwal_id = $_POST['jadwal_id'];
            $judul = $_POST['judul'];
            $deskripsi = $_POST['deskripsi'];
            
            // Handle file upload
            $file_path = null;
            if(isset($_FILES['file_materi']) && $_FILES['file_materi']['error'] == 0) {
                $target_dir = "public/uploads/materi/";
                if (!is_dir($target_dir)) {
                    mkdir($target_dir, 0777, true);
                }
                $file_name = time() . '_' . basename($_FILES["file_materi"]["name"]);
                $target_file = $target_dir . $file_name;
                
                if(move_uploaded_file($_FILES["file_materi"]["tmp_name"], $target_file)) {
                    $file_path = $target_file;
                }
            }

            $data = [
                'jadwal_id' => $jadwal_id,
                'judul' => $judul,
                'deskripsi' => $deskripsi,
                'file_path' => $file_path
            ];

            if($this->model('ElearningModel')->tambahMateri($data) > 0) {
                $_SESSION['flash'] = ['pesan' => 'berhasil', 'aksi' => 'ditambahkan', 'tipe' => 'success'];
            } else {
                $_SESSION['flash'] = ['pesan' => 'gagal', 'aksi' => 'ditambahkan', 'tipe' => 'danger'];
            }
            
            header('Location: ' . BASEURL . '/elearning/detail/' . $jadwal_id);
            exit;
        }
    }

    public function tambahTugas()
    {
        if($_SERVER['REQUEST_METHOD'] == 'POST' && $_SESSION['user']['role'] == 'guru') {
            $jadwal_id = $_POST['jadwal_id'];
            $judul = $_POST['judul'];
            $deskripsi = $_POST['deskripsi'];
            $tenggat_waktu = $_POST['tenggat_waktu'];
            
            $data = [
                'jadwal_id' => $jadwal_id,
                'judul' => $judul,
                'deskripsi' => $deskripsi,
                'tenggat_waktu' => $tenggat_waktu
            ];

            if($this->model('ElearningModel')->tambahTugas($data) > 0) {
                $_SESSION['flash'] = ['pesan' => 'Tugas berhasil', 'aksi' => 'ditambahkan', 'tipe' => 'success'];
            } else {
                $_SESSION['flash'] = ['pesan' => 'Tugas gagal', 'aksi' => 'ditambahkan', 'tipe' => 'danger'];
            }
            
            header('Location: ' . BASEURL . '/elearning/detail/' . $jadwal_id . '?tab=tugas');
            exit;
        }
    }

    public function tambahDiskusi()
    {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $jadwal_id = $_POST['jadwal_id'];
            $data = [
                'jadwal_id' => $jadwal_id,
                'user_id' => $_SESSION['user']['id'],
                'pesan' => $_POST['pesan']
            ];
            $this->model('ElearningModel')->tambahDiskusi($data);
            header('Location: ' . BASEURL . '/elearning/detail/' . $jadwal_id . '?tab=diskusi');
            exit;
        }
    }

    public function getAbsensiAjax($jadwal_id, $tanggal)
    {
        header('Content-Type: application/json');
        try {
            $data = $this->model('ElearningModel')->getAbsensiByJadwalTanggal($jadwal_id, $tanggal);
            echo json_encode($data);
        } catch (Exception $e) {
            echo json_encode(['error' => $e->getMessage()]);
        }
        exit;
    }

    public function simpanAbsensi()
    {
        if($_SERVER['REQUEST_METHOD'] == 'POST' && $_SESSION['user']['role'] == 'guru') {
            header('Content-Type: application/json');
            $jadwal_id = $_POST['jadwal_id'];
            $tanggal = $_POST['tanggal'];
            $absensi = $_POST['absensi'] ?? []; // Array of siswa_id => status

            $sukses = 0;
            foreach ($absensi as $siswa_id => $status) {
                $sukses += $this->model('ElearningModel')->simpanAbsensi($jadwal_id, $tanggal, $siswa_id, $status);
            }

            echo json_encode(['status' => 'success', 'message' => 'Absensi berhasil disimpan']);
            exit;
        }
    }
}
