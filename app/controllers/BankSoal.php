<?php

class BankSoal extends Controller {

    public function __construct()
    {
        requireAccess('cbt_bank_soal');
    }

    public function index()
    {
        requireAccess('cbt_bank_soal');
        $data['judul'] = 'Bank Soal CBT';
        $data['soal'] = $this->model('BankSoalModel')->getAllSoal();
        
        $this->view('templates/admin_header', $data);
        $this->view('bank_soal/index', $data);
        $this->view('templates/admin_footer');
    }

    public function tambah()
    {
        $data['judul'] = 'Tambah Soal Baru';
        
        $this->view('templates/admin_header', $data);
        $this->view('bank_soal/form', $data);
        $this->view('templates/admin_footer');
    }

    public function simpan()
    {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            if($this->model('BankSoalModel')->tambahDataSoal($_POST) > 0) {
                Flasher::setFlash('Soal berhasil', 'ditambahkan', 'success');
                header('Location: ' . BASEURL . '/BankSoal');
                exit;
            } else {
                Flasher::setFlash('Soal gagal', 'ditambahkan', 'danger');
                header('Location: ' . BASEURL . '/BankSoal');
                exit;
            }
        }
    }

    public function hapus($id)
    {
        if($this->model('BankSoalModel')->hapusDataSoal($id) > 0) {
            Flasher::setFlash('Soal berhasil', 'dihapus', 'success');
            header('Location: ' . BASEURL . '/BankSoal');
            exit;
        } else {
            Flasher::setFlash('Soal gagal', 'dihapus', 'danger');
            header('Location: ' . BASEURL . '/BankSoal');
            exit;
        }
    }
}
