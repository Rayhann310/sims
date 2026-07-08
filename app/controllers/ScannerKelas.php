<?php

class ScannerKelas extends Controller {
    public function __construct()
    {
        // Hanya guru yang bisa menggunakan fitur ini
        if(!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'guru') {
            header('Location: ' . BASEURL . '/login');
            exit;
        }
    }

    public function index()
    {
        $data['judul'] = 'Scanner & Absensi Kelas';

        // Load kelas/rombel yang diajar oleh guru ini (misal ambil semua rombel aktif)
        // Sebenarnya idealnya ambil dari jadwal, tapi untuk kesederhanaan kita ambil semua rombel aktif
        $this->model('SiswaModel'); // To initialize DB
        $db = new Database();
        $db->query("SELECT * FROM rombel ORDER BY nama_kelas ASC, jurusan ASC, grade ASC");
        $data['rombel'] = $db->resultSet();

        $this->view('templates/admin_header', $data);
        $this->view('absensi/scanner_kelas', $data);
        $this->view('templates/admin_footer');
    }

    public function getSiswaByRombel($rombel_id)
    {
        $siswa = $this->model('AbsensiSiswaModel')->getSiswaByRombel($rombel_id);
        echo json_encode(['status' => true, 'data' => $siswa]);
    }

    public function submitAbsen()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Cek apakah mode = scan atau manual
            // Pastikan kita dapat ID Guru dari session
            $db = new Database();
            $db->query("SELECT id FROM guru WHERE user_id = :user_id");
            $db->bind('user_id', $_SESSION['user']['id']);
            $guru = $db->single();
            $guru_id = $guru ? $guru['id'] : 0;

            $json = file_get_contents('php://input');
            $post = json_decode($json, true);

            if (!$post) {
                echo json_encode(['status' => false, 'pesan' => 'Invalid data.']);
                return;
            }

            if (isset($post['qr_token'])) {
                // Mode Scan QR
                $db->query("SELECT id FROM siswa WHERE qr_token = :qr_token");
                $db->bind('qr_token', $post['qr_token']);
                $siswa = $db->single();

                if ($siswa) {
                    $payload = [
                        'siswa_id' => $siswa['id'],
                        'guru_id' => $guru_id,
                        'jam_ke' => $post['jam_ke'],
                        'status' => 'Hadir'
                    ];
                    $res = $this->model('AbsensiSiswaModel')->absenKelas($payload);
                    echo json_encode($res);
                } else {
                    echo json_encode(['status' => false, 'pesan' => 'QR Code tidak dikenali.']);
                }
            } else {
                // Mode Manual
                $payload = [
                    'siswa_id' => $post['siswa_id'],
                    'guru_id' => $guru_id,
                    'jam_ke' => $post['jam_ke'],
                    'status' => $post['status']
                ];
                $res = $this->model('AbsensiSiswaModel')->absenKelas($payload);
                echo json_encode($res);
            }
        }
    }
}
