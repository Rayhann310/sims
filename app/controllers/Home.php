<?php

class Home extends Controller {
    public function index()
    {
        $data['judul'] = 'Beranda SMA Nahdlatul Wathan Jakarta';
        $spmbModel = $this->model('SpmbModel');
        $data['gelombang_aktif'] = $spmbModel->getGelombangAktif();
        // Mengambil data kategori biaya pendaftaran
        $kategori_biaya = $this->model('SpmbModel')->getAllKategoriBiaya();
        foreach ($kategori_biaya as &$k) {
            $k['rincian'] = $this->model('SpmbModel')->getRincianBiayaByKategori($k['id']);
        }
        $data['kategori_biaya'] = $kategori_biaya;
        $data['hide_navbar'] = true;
        
        $db = new Database();
        $db->query("SELECT * FROM pengaturan ORDER BY id ASC LIMIT 1");
        $data['pengaturan'] = $db->single();

        require_once '../app/models/AkademikModel.php';
        $akademikModel = new AkademikModel();
        $data['tahun_akademik'] = $akademikModel->getTahunAktif();

        $this->view('templates/header', $data);
        $this->view('home/index', $data);
        $this->view('templates/footer');
    }
}
