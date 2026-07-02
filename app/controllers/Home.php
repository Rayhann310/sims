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

        $this->view('templates/header', $data);
        $this->view('home/index', $data);
        $this->view('templates/footer');
    }
}
