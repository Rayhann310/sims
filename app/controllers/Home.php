<?php

class Home extends Controller {
    public function index()
    {
        $data['judul'] = 'Beranda SMA Nahdlatul Wathan Jakarta';
        $spmbModel = $this->model('SpmbModel');
        
        try {
            $data['gelombang_aktif'] = $spmbModel->getGelombangAktif();
        } catch (Exception $e) {
            $data['gelombang_aktif'] = false;
        }
        
        // Mengambil data kategori biaya pendaftaran
        try {
            $kategori_biaya = $spmbModel->getAllKategoriBiaya();
            if(is_array($kategori_biaya)) {
                foreach ($kategori_biaya as &$k) {
                    $k['rincian'] = $spmbModel->getRincianBiayaByKategori($k['id']);
                }
            } else {
                $kategori_biaya = [];
            }
        } catch (Exception $e) {
            $kategori_biaya = [];
        }
        $data['kategori_biaya'] = $kategori_biaya;
        $data['hide_navbar'] = true;
        
        $db = new Database();
        try {
            $db->query("SELECT * FROM pengaturan ORDER BY id ASC LIMIT 1");
            $pengaturan = $db->single();
            $data['pengaturan'] = $pengaturan ? $pengaturan : [];
        } catch (Exception $e) {
            $data['pengaturan'] = [];
        }

        require_once '../app/models/AkademikModel.php';
        $akademikModel = new AkademikModel();
        
        try {
            $data['tahun_akademik'] = $akademikModel->getTahunAktif();
        } catch (Exception $e) {
            $data['tahun_akademik'] = false;
        }

        $this->view('templates/header', $data);
        $this->view('home/index', $data);
        $this->view('templates/footer');
    }
}
