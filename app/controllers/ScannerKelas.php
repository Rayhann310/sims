<?php

class ScannerKelas extends Controller {
    public function __construct()
    {
        // Hanya guru yang bisa menggunakan fitur ini
        if(!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'guru') {
            header('Location: ' . BASEURL . '/login');
            exit;
        }

        $db = new Database();
        $db->query("SELECT mode_siswa FROM pengaturan_absensi ORDER BY id ASC LIMIT 1");
        $pengaturan_absensi = $db->single();
        if ($pengaturan_absensi && $pengaturan_absensi['mode_siswa'] === 'Normal') {
            require_once 'app/core/HakAksesHelper.php';
            if (!hasMenuAccess('absensi_kelas')) {
                header('Location: ' . BASEURL . '/dashboard');
                exit;
            }
        }
    }

    public function index()
    {
        $data['judul'] = 'Absensi Kelas';

        // Load jadwal pelajaran untuk guru yang sedang login untuk hari ini
        $db = new Database();
        
        $db->query("SELECT id FROM guru WHERE user_id = :user_id");
        $db->bind('user_id', $_SESSION['user']['id']);
        $guru = $db->single();
        $guru_id = $guru ? $guru['id'] : 0;

        $hari_indo = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        $hari_ini = $hari_indo[date('w')];

        $db->query("
            SELECT jp.*, m.nama_mapel, r.nama_rombel, k.tingkat, k.jurusan, k.nama_kelas 
            FROM jadwal_pelajaran jp
            JOIN mata_pelajaran m ON jp.mapel_id = m.id
            JOIN rombel r ON jp.rombel_id = r.id
            JOIN kelas k ON r.kelas_id = k.id
            WHERE jp.guru_id = :guru_id
            ORDER BY 
                CASE jp.hari 
                    WHEN 'Senin' THEN 1
                    WHEN 'Selasa' THEN 2
                    WHEN 'Rabu' THEN 3
                    WHEN 'Kamis' THEN 4
                    WHEN 'Jumat' THEN 5
                    WHEN 'Sabtu' THEN 6
                    WHEN 'Minggu' THEN 7
                END,
                jp.jam_mulai ASC
        ");
        $db->bind('guru_id', $guru_id);
        $data['jadwal_semua'] = $db->resultSet();
        $data['hari_ini'] = $hari_ini;

        $this->view('templates/admin_header', $data);
        $this->view('absensi/scanner_kelas', $data);
        $this->view('templates/admin_footer');
    }

    public function getSiswaByRombel($rombel_id)
    {
        $jadwal_id = $_GET['jadwal_id'] ?? 0;
        $siswa = $this->model('AbsensiSiswaModel')->getSiswaWithStatus($rombel_id, $jadwal_id);
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
                        'jam_ke' => $post['jadwal_id'],
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
                    'jam_ke' => $post['jadwal_id'],
                    'status' => $post['status']
                ];
                $res = $this->model('AbsensiSiswaModel')->absenKelas($payload);
                echo json_encode($res);
            }
        }
    }
}
