<?php

class Profil extends Controller {
    
    public function __construct()
    {
        if(!isset($_SESSION['user'])) {
            header('Location: ' . BASEURL . '/login');
            exit;
        }
    }

    public function index()
    {
        $data['judul'] = 'Pengaturan Profil';
        $user_id = $_SESSION['user']['id'];
        $role = $_SESSION['user']['role'];

        $profilModel = $this->model('ProfilModel');
        $data['user'] = $profilModel->getUserData($user_id);
        
        $data['profil_detail'] = null;
        if($role == 'siswa') {
            $data['profil_detail'] = $profilModel->getSiswaData($user_id);
        } else if($role == 'guru' || $role == 'staf') {
            $data['profil_detail'] = $profilModel->getGuruData($user_id);
        }

        $this->view('templates/admin_header', $data);
        $this->view('profil/index', $data);
        $this->view('templates/admin_footer');
    }

    public function update()
    {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $user_id = $_SESSION['user']['id'];
            $role = $_SESSION['user']['role'];
            $profilModel = $this->model('ProfilModel');

            // Cek username unik
            if($profilModel->checkUsernameExists($_POST['username'], $user_id)) {
                Flasher::setFlash('Username sudah digunakan', 'oleh pengguna lain', 'danger');
                header('Location: ' . BASEURL . '/profil');
                exit;
            }

            // Update user account table
            $profilModel->updateUsersTable($_POST, $user_id);

            // Update session if name changed
            $_SESSION['user']['nama_lengkap'] = $_POST['nama_lengkap'];
            $_SESSION['user']['username'] = $_POST['username'];

            // Update detail based on role
            if($role == 'siswa') {
                $profilModel->updateSiswaTable($_POST, $user_id);
            } else if($role == 'guru' || $role == 'staf') {
                $profilModel->updateGuruTable($_POST, $user_id);
            }

            Flasher::setFlash('Profil berhasil', 'diperbarui', 'success');
            header('Location: ' . BASEURL . '/profil');
            exit;
        }
    }
}
