<?php

class Home extends Controller {
    public function index()
    {
        $data['judul'] = 'Beranda SMA Nahdlatul Wathan Jakarta';
        $spmbModel = $this->model('SpmbModel');
        $data['gelombang_aktif'] = $spmbModel->getGelombangAktif();

        $this->view('templates/header', $data);
        $this->view('home/index', $data);
        $this->view('templates/footer');
    }
}
