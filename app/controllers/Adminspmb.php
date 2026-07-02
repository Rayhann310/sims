<?php

class Adminspmb extends Controller {

    public function __construct()
    {
        // Pastikan hanya admin yang bisa akses
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            header('Location: ' . BASEURL . '/login');
            exit;
        }
    }

    public function index()
    {
        $data['judul'] = 'Manajemen Gelombang SPMB';
        $data['gelombang'] = $this->model('SpmbModel')->getAllGelombang();
        $this->view('templates/admin_header', $data);
        $this->view('admin_spmb/index', $data);
        $this->view('templates/admin_footer');
    }

    public function tambahGelombang()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if ($this->model('SpmbModel')->tambahGelombang($_POST) > 0) {
                Flasher::setFlash('Berhasil', 'Gelombang pendaftaran berhasil ditambahkan', 'success');
            } else {
                Flasher::setFlash('Gagal', 'Gelombang pendaftaran gagal ditambahkan', 'danger');
            }
            header('Location: ' . BASEURL . '/adminspmb');
            exit;
        }
    }

    public function ubahGelombang()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if ($this->model('SpmbModel')->ubahGelombang($_POST) > 0) {
                Flasher::setFlash('Berhasil', 'Gelombang pendaftaran berhasil diubah', 'success');
            } else {
                Flasher::setFlash('Gagal', 'Gelombang pendaftaran gagal diubah', 'danger');
            }
            header('Location: ' . BASEURL . '/adminspmb');
            exit;
        }
    }

    public function hapusGelombang($id)
    {
        if ($this->model('SpmbModel')->hapusGelombang($id) > 0) {
            Flasher::setFlash('Berhasil', 'Gelombang pendaftaran berhasil dihapus', 'success');
        } else {
            Flasher::setFlash('Gagal', 'Gelombang pendaftaran gagal dihapus', 'danger');
        }
        header('Location: ' . BASEURL . '/adminspmb');
        exit;
    }

    // ===================================
    // PESERTA
    // ===================================
    public function peserta()
    {
        $data['judul'] = 'Data Peserta SPMB';
        $data['peserta'] = $this->model('SpmbModel')->getAllPeserta();
        $this->view('templates/admin_header', $data);
        $this->view('admin_spmb/peserta', $data);
        $this->view('templates/admin_footer');
    }

    public function ubahStatusSeleksi()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['id'];
            $status = $_POST['status_seleksi'];
            
            if ($this->model('SpmbModel')->updateStatusSeleksi($id, $status) > 0) {
                Flasher::setFlash('Berhasil', 'Status seleksi berhasil diubah', 'success');
            } else {
                Flasher::setFlash('Gagal', 'Status seleksi gagal diubah', 'danger');
            }
            header('Location: ' . BASEURL . '/adminspmb/peserta');
            exit;
        }
    }

    public function migrasiSiswa($id)
    {
        $result = $this->model('SpmbModel')->migrasiKeSiswa($id);
        if ($result['status']) {
            Flasher::setFlash('Berhasil', $result['pesan'], 'success');
        } else {
            Flasher::setFlash('Gagal', $result['pesan'], 'danger');
        }
        header('Location: ' . BASEURL . '/adminspmb/peserta');
        exit;
    }

    public function migrasiMassal($gelombang_id)
    {
        $result = $this->model('SpmbModel')->migrasiMassalKeSiswa($gelombang_id);
        if ($result['status']) {
            Flasher::setFlash('Berhasil', $result['pesan'], 'success');
        } else {
            Flasher::setFlash('Gagal', $result['pesan'], 'danger');
        }
        header('Location: ' . BASEURL . '/adminspmb');
        exit;
    }

    // ===================================
    // PEMBAYARAN
    // ===================================
    public function verifikasiPembayaran($id)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $status = $_POST['status'];
            $peserta_id = $_POST['peserta_id'];
            
            if ($this->model('SpmbModel')->verifikasiPembayaran($id, $status, $peserta_id)) {
                Flasher::setFlash('Berhasil', 'Verifikasi pembayaran berhasil disave', 'success');
            } else {
                Flasher::setFlash('Gagal', 'Gagal memverifikasi pembayaran', 'danger');
            }
            
            // Redirect back to referring page (bisa dari halaman peserta)
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            exit;
        }
    }
}
