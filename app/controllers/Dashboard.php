<?php

class Dashboard extends Controller {
    public function index()
    {
        // Pengecekan sesi, hanya yang sudah login yang bisa akses
        if(!isset($_SESSION['user'])) {
            header('Location: ' . BASEURL . '/login');
            exit;
        }

        $data['judul'] = 'Dasbor - SIAKAD';
        $data['user'] = $_SESSION['user'];

        // Ambil data realtime dari database
        $db = new Database();
        
        $db->query("SELECT COUNT(id) as total FROM siswa");
        $data['total_siswa'] = $db->single()['total'] ?? 0;

        $db->query("SELECT COUNT(id) as total FROM guru");
        $data['total_guru'] = $db->single()['total'] ?? 0;

        $db->query("SELECT COUNT(id) as total FROM rombel");
        $data['total_kelas'] = $db->single()['total'] ?? 0;

        $db->query("SELECT COUNT(id) as total FROM kearsipan");
        $data['total_surat'] = $db->single()['total'] ?? 0;

        // Tahun ajaran aktif
        $db->query("SELECT nama_tahun, semester FROM tahun_akademik WHERE status = 'Aktif' LIMIT 1");
        $thn = $db->single();
        $data['tahun_ajaran'] = $thn ? $thn['nama_tahun'] . ' - ' . $thn['semester'] : '-';

        $this->view('templates/admin_header', $data);
        $this->view('dashboard/index', $data);
        $this->view('templates/admin_footer');
    }
}
