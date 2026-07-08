<?php

class ScannerAbsensi extends Controller {
    public function __construct()
    {
        // Pastikan hanya admin atau guru piket yang bisa buka scanner
        if(!isset($_SESSION['user']) || !in_array($_SESSION['user']['role'], ['admin', 'guru'])) {
            header('Location: ' . BASEURL . '/login');
            exit;
        }
    }

    public function index()
    {
        $data['judul'] = 'Scanner QR Presensi Siswa';
        // Kita gunakan layout fullscreen juga
        $this->view('absensi/scanner_siswa', $data);
    }
}
