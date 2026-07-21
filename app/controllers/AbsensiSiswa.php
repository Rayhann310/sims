<?php

class AbsensiSiswa extends Controller {

    public function __construct()
    {
        if (!isset($_SESSION['user']) || !in_array($_SESSION['user']['role'], ['admin', 'guru'])) {
            header('Location: ' . BASEURL . '/login');
            exit;
        }
        requireAccess('absensi_siswa');
    }

    /**
     * Halaman utama absensi harian siswa (manual + opsional scan QR)
     */
    public function index()
    {
        $data['judul'] = 'Absensi Siswa Harian';

        // Ambil setting mode absen
        $pam = $this->model('PengaturanAbsensiModel');
        $data['pengaturan'] = $pam->getPengaturanGlobal();

        // Ambil daftar rombel aktif (berdasarkan tahun akademik aktif)
        $db = new Database();
        $db->query("
            SELECT r.id, r.nama_rombel, k.tingkat, k.jurusan
            FROM rombel r
            JOIN kelas k ON r.kelas_id = k.id
            JOIN tahun_akademik t ON r.tahun_akademik_id = t.id
            WHERE t.status = 'Aktif'
            ORDER BY k.tingkat ASC, r.nama_rombel ASC
        ");
        $data['rombels'] = $db->resultSet();

        $this->view('templates/admin_header', $data);
        $this->view('absensi_siswa/index', $data);
        $this->view('templates/admin_footer');
    }

    /**
     * API: Ambil daftar siswa + status absen harian hari ini
     * GET /AbsensiSiswa/getSiswa?rombel_id=X&tanggal=Y
     */
    public function getSiswa()
    {
        header('Content-Type: application/json');

        $rombel_id = $_GET['rombel_id'] ?? 0;
        $tanggal   = $_GET['tanggal']   ?? date('Y-m-d');

        if (!$rombel_id) {
            echo json_encode(['status' => false, 'data' => []]);
            return;
        }

        $db = new Database();

        // Ambil daftar siswa di rombel
        $db->query("
            SELECT s.id, s.nisn, u.nama_lengkap, s.qr_token
            FROM anggota_rombel ar
            JOIN siswa s ON ar.siswa_id = s.id
            JOIN users u ON s.user_id = u.id
            WHERE ar.rombel_id = :rombel_id
            ORDER BY u.nama_lengkap ASC
        ");
        $db->bind('rombel_id', $rombel_id);
        $siswaList = $db->resultSet();

        if (empty($siswaList)) {
            echo json_encode(['status' => true, 'data' => []]);
            return;
        }

        // Ambil status absensi harian hari ini (tipe masuk)
        $ids = implode(',', array_map('intval', array_column($siswaList, 'id')));
        $db->query("
            SELECT siswa_id, tipe_absen, status, waktu_scan
            FROM absensi_siswa
            WHERE siswa_id IN ($ids) AND tanggal = :tanggal
        ");
        $db->bind('tanggal', $tanggal);
        $absensiRows = $db->resultSet();

        // Map status per siswa (masuk & pulang)
        $statusMap = [];
        foreach ($absensiRows as $row) {
            $sid = $row['siswa_id'];
            if (!isset($statusMap[$sid])) {
                $statusMap[$sid] = ['masuk' => null, 'pulang' => null, 'status' => null, 'waktu' => null];
            }
            $statusMap[$sid][$row['tipe_absen']] = $row['status'];
            if ($row['tipe_absen'] === 'masuk') {
                $statusMap[$sid]['status'] = $row['status'];
                $statusMap[$sid]['waktu']  = $row['waktu_scan'];
            }
        }

        foreach ($siswaList as &$s) {
            $sid          = $s['id'];
            $s['status']  = $statusMap[$sid]['status']  ?? null;
            $s['waktu']   = $statusMap[$sid]['waktu']   ?? null;
            $s['sudah_masuk']  = $statusMap[$sid]['masuk']  !== null;
            $s['sudah_pulang'] = $statusMap[$sid]['pulang'] !== null;
            $s['status_pulang'] = $statusMap[$sid]['pulang'] ?? null;
        }

        echo json_encode(['status' => true, 'data' => $siswaList]);
    }

    /**
     * API: Submit absensi manual harian
     * POST /AbsensiSiswa/submitManual
     * body: { siswa_id, status, tipe_absen, tanggal }
     */
    public function submitManual()
    {
        header('Content-Type: application/json');

        $input     = json_decode(file_get_contents('php://input'), true);
        $siswa_id  = intval($input['siswa_id']  ?? 0);
        $status    = $input['status']    ?? 'Hadir';
        $tipe      = $input['tipe_absen'] ?? 'masuk';
        $tanggal   = $input['tanggal']   ?? date('Y-m-d');
        $waktu     = date('H:i:s');

        if (!$siswa_id) {
            echo json_encode(['status' => false, 'pesan' => 'siswa_id tidak valid']);
            return;
        }

        $db = new Database();

        // Cek apakah sudah ada record untuk tipe ini
        $db->query("SELECT id FROM absensi_siswa WHERE siswa_id = :sid AND tanggal = :tgl AND tipe_absen = :tipe");
        $db->bind('sid', $siswa_id);
        $db->bind('tgl', $tanggal);
        $db->bind('tipe', $tipe);
        $existing = $db->single();

        if ($existing) {
            $db->query("UPDATE absensi_siswa SET status = :status, waktu_scan = :waktu WHERE id = :id");
            $db->bind('status', $status);
            $db->bind('waktu', $waktu);
            $db->bind('id', $existing['id']);
        } else {
            $db->query("INSERT INTO absensi_siswa (siswa_id, tanggal, waktu_scan, tipe_absen, status) VALUES (:sid, :tgl, :waktu, :tipe, :status)");
            $db->bind('sid', $siswa_id);
            $db->bind('tgl', $tanggal);
            $db->bind('waktu', $waktu);
            $db->bind('tipe', $tipe);
            $db->bind('status', $status);
        }

        try {
            $db->execute();
            echo json_encode(['status' => true, 'pesan' => 'Berhasil disimpan.']);
        } catch (Exception $e) {
            echo json_encode(['status' => false, 'pesan' => 'Gagal menyimpan: ' . $e->getMessage()]);
        }
    }

    /**
     * API: Submit absensi via QR scan (opsional)
     * POST /AbsensiSiswa/submitScan
     * body: { qr_token, tipe_absen, tanggal }
     */
    public function submitScan()
    {
        header('Content-Type: application/json');

        $input     = json_decode(file_get_contents('php://input'), true);
        $qr_token  = $input['qr_token']   ?? '';
        $tipe      = $input['tipe_absen'] ?? 'masuk';
        $tanggal   = $input['tanggal']    ?? date('Y-m-d');
        $waktu     = date('H:i:s');

        if (!$qr_token) {
            echo json_encode(['status' => false, 'pesan' => 'QR token kosong.']);
            return;
        }

        $db = new Database();

        // Cari siswa
        $db->query("SELECT s.id, u.nama_lengkap FROM siswa s JOIN users u ON s.user_id = u.id WHERE s.qr_token = :qr_token");
        $db->bind('qr_token', $qr_token);
        $siswa = $db->single();

        if (!$siswa) {
            echo json_encode(['status' => false, 'pesan' => 'QR Code tidak dikenali.']);
            return;
        }

        $siswa_id = $siswa['id'];
        $nama     = $siswa['nama_lengkap'];

        // Cek existing
        $db->query("SELECT id FROM absensi_siswa WHERE siswa_id = :sid AND tanggal = :tgl AND tipe_absen = :tipe");
        $db->bind('sid', $siswa_id);
        $db->bind('tgl', $tanggal);
        $db->bind('tipe', $tipe);
        $existing = $db->single();

        if ($existing) {
            echo json_encode(['status' => true, 'pesan' => "$nama sudah diabsen ($tipe) hari ini.", 'nama' => $nama, 'tipe' => $tipe]);
            return;
        }

        $db->query("INSERT INTO absensi_siswa (siswa_id, tanggal, waktu_scan, tipe_absen, status) VALUES (:sid, :tgl, :waktu, :tipe, 'Hadir')");
        $db->bind('sid', $siswa_id);
        $db->bind('tgl', $tanggal);
        $db->bind('waktu', $waktu);
        $db->bind('tipe', $tipe);

        try {
            $db->execute();
            echo json_encode(['status' => true, 'pesan' => "Berhasil: $nama (" . ucfirst($tipe) . ")", 'nama' => $nama, 'tipe' => $tipe]);
        } catch (Exception $e) {
            echo json_encode(['status' => false, 'pesan' => 'Gagal: ' . $e->getMessage()]);
        }
    }
}
