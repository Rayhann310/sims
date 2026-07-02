<?php

class Spmb extends Controller {

    public function __construct()
    {
        // Controller khusus untuk Frontend SPMB (Pendaftaran dan Dashboard Peserta)
    }

    public function index()
    {
        // Cek jika sudah login sebagai siswa/peserta, arahkan ke dashboard
        if (isset($_SESSION['user_id'])) {
            header('Location: ' . BASEURL . '/spmb/dashboard');
            exit;
        }

        $data['judul'] = 'Pendaftaran Siswa Baru - SPMB';
        $data['gelombang_aktif'] = $this->model('SpmbModel')->getGelombangAktif();

        if (empty($data['gelombang_aktif'])) {
            Flasher::setFlash('Mohon Maaf', 'Saat ini tidak ada gelombang pendaftaran yang dibuka.', 'warning');
        }

        $this->view('templates/header', $data);
        $this->view('spmb/register', $data);
        $this->view('templates/footer');
    }

    public function daftar()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $gelombang = $this->model('SpmbModel')->getGelombangAktif();
            if (!$gelombang) {
                Flasher::setFlash('Gagal', 'Pendaftaran sedang ditutup.', 'danger');
                header('Location: ' . BASEURL . '/spmb');
                exit;
            }

            $_POST['gelombang_id'] = $gelombang['id'];
            
            $result = $this->model('SpmbModel')->daftarPeserta($_POST);

            if ($result['status']) {
                Flasher::setFlash('Berhasil', $result['pesan'], 'success');
                header('Location: ' . BASEURL . '/login');
                exit;
            } else {
                Flasher::setFlash('Gagal', $result['pesan'], 'danger');
                header('Location: ' . BASEURL . '/spmb');
                exit;
            }
        }
    }

    public function dashboard()
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . BASEURL . '/login');
            exit;
        }

        $data['judul'] = 'Dashboard Calon Siswa';
        $data['user'] = $this->model('SiswaModel')->getSiswaById($_SESSION['user_id']); // This is for full student, but they might be peserta
        
        $data['peserta'] = $this->model('SpmbModel')->getPesertaByUserId($_SESSION['user_id']);

        if (!$data['peserta']) {
            // Jika bukan peserta SPMB, mungkin dia admin atau siswa biasa
            header('Location: ' . BASEURL . '/dashboard');
            exit;
        }

        // Ambil riwayat pembayaran
        $data['pembayaran'] = $this->model('SpmbModel')->getPembayaranByPeserta($data['peserta']['id']);

        $this->view('templates/header', $data);
        $this->view('spmb/dashboard', $data);
        $this->view('templates/footer');
    }

    public function bayar()
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . BASEURL . '/login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $peserta = $this->model('SpmbModel')->getPesertaByUserId($_SESSION['user_id']);
            
            // Upload bukti
            $bukti = '';
            if (isset($_FILES['bukti_bayar']) && $_FILES['bukti_bayar']['error'] == 0) {
                $target_dir = "img/bukti/";
                if (!is_dir($target_dir)) {
                    mkdir($target_dir, 0777, true);
                }
                $file_extension = pathinfo($_FILES["bukti_bayar"]["name"], PATHINFO_EXTENSION);
                $new_filename = 'bukti_' . $peserta['id'] . '_' . time() . '.' . $file_extension;
                $target_file = $target_dir . $new_filename;
                
                if (move_uploaded_file($_FILES["bukti_bayar"]["tmp_name"], $target_file)) {
                    $bukti = $new_filename;
                }
            }

            if ($bukti != '') {
                $dataBayar = [
                    'peserta_id' => $peserta['id'],
                    'jumlah_bayar' => $peserta['harga_formulir'],
                    'metode' => $_POST['metode'],
                    'bukti' => $bukti
                ];
                $this->model('SpmbModel')->tambahPembayaran($dataBayar);
                Flasher::setFlash('Berhasil', 'Bukti pembayaran berhasil diunggah. Silakan tunggu verifikasi admin.', 'success');
            } else {
                Flasher::setFlash('Gagal', 'Gagal mengunggah bukti pembayaran.', 'danger');
            }

            header('Location: ' . BASEURL . '/spmb/dashboard');
            exit;
        }
    }
}
