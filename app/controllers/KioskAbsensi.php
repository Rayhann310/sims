<?php

class KioskAbsensi extends Controller {
    public function __construct()
    {
        // Pastikan hanya admin dan guru yang bisa buka kiosk ini
        if(!isset($_SESSION['user']) || !in_array($_SESSION['user']['role'], ['admin', 'guru'])) {
            header('Location: ' . BASEURL . '/login');
            exit;
        }
    }

    public function index()
    {
        $data['judul'] = 'Kiosk Absensi Guru';
        $data['guru'] = $this->model('GuruModel')->getAllGuru();
        
        $absensiHariIni = $this->model('AbsensiGuruModel')->getAbsensiHariIni();
        
        // Map absensi berdasarkan guru_id
        $mapAbsensi = [];
        foreach($absensiHariIni as $abs) {
            $mapAbsensi[$abs['guru_id']] = $abs;
        }
        $data['absensi'] = $mapAbsensi;

        // Kita gunakan layout fullscreen khusus, tidak pakai header/footer admin biasa
        $this->view('absensi/kiosk_guru', $data);
    }
}
