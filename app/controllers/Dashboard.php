<?php

class Dashboard extends Controller {
    public function index()
    {
        // Pengecekan sesi, hanya yang sudah login yang bisa akses
        if(!isset($_SESSION['user'])) {
            header('Location: ' . BASEURL . '/login');
            exit;
        }

        $data['judul'] = 'Dashboard';
        $data['user'] = $_SESSION['user'];

        // Ambil data realtime dari database
        $db = new Database();

        if($_SESSION['user']['role'] == 'siswa') {
            $db->query("SELECT qr_token FROM siswa WHERE user_id = :user_id");
            $db->bind('user_id', $_SESSION['user']['id']);
            $siswa = $db->single();
            $data['qr_token'] = $siswa ? $siswa['qr_token'] : null;
        }

        
        // Tahun ajaran aktif
        $db->query("SELECT id, nama_tahun, semester FROM tahun_akademik WHERE status = 'Aktif' LIMIT 1");
        $thn = $db->single();
        $data['tahun_ajaran'] = $thn ? $thn['nama_tahun'] . ' - ' . $thn['semester'] : '-';

        // 1. Siswa Aktif Tahun Akademik Aktif
        $db->query("
            SELECT COUNT(DISTINCT ar.siswa_id) as total 
            FROM anggota_rombel ar 
            JOIN rombel r ON ar.rombel_id = r.id 
            JOIN tahun_akademik ta ON r.tahun_akademik_id = ta.id 
            WHERE ta.status = 'Aktif' 
            AND ar.siswa_id IN (SELECT id FROM siswa WHERE status = 'Aktif')
        ");
        $data['total_siswa_aktif'] = $db->single()['total'] ?? 0;

        // 2. Total Alumni
        $db->query("SELECT COUNT(id) as total FROM siswa WHERE status = 'Alumni'");
        $data['total_alumni'] = $db->single()['total'] ?? 0;

        // 3. Total Keseluruhan (Siswa + Alumni)
        $db->query("SELECT COUNT(id) as total FROM siswa WHERE status IN ('Aktif', 'Alumni')");
        $data['total_keseluruhan'] = $db->single()['total'] ?? 0;

        // 4. Total Guru
        $db->query("SELECT COUNT(id) as total FROM guru");
        $data['total_guru'] = $db->single()['total'] ?? 0;

        // Chart 1: Gender Ratio (Siswa Aktif)
        $db->query("
            SELECT jenis_kelamin, COUNT(id) as jumlah 
            FROM siswa 
            WHERE status = 'Aktif' 
            GROUP BY jenis_kelamin
        ");
        $data['chart_gender'] = $db->resultSet();

        // Chart 2: Distribusi Siswa per Kelas
        $db->query("
            SELECT k.nama_kelas, COUNT(DISTINCT ar.siswa_id) as jumlah 
            FROM kelas k 
            JOIN rombel r ON k.id = r.kelas_id 
            JOIN anggota_rombel ar ON r.id = ar.rombel_id 
            JOIN tahun_akademik ta ON r.tahun_akademik_id = ta.id 
            WHERE ta.status = 'Aktif' 
            AND ar.siswa_id IN (SELECT id FROM siswa WHERE status = 'Aktif')
            GROUP BY k.nama_kelas
            ORDER BY k.nama_kelas ASC
        ");
        $data['chart_kelas'] = $db->resultSet();

        $this->view('templates/admin_header', $data);
        $this->view('dashboard/index', $data);
        $this->view('templates/admin_footer');
    }
}
