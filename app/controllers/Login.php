<?php

class Login extends Controller {
    public function index()
    {
        if(isset($_SESSION['user'])) {
            header('Location: ' . BASEURL . '/dashboard');
            exit;
        }

        $data['judul'] = 'Portal Login - SIAKAD';
        $this->view('auth/login', $data);
    }

    public function proses()
    {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $username = $_POST['username'];
            $password = $_POST['password'];

            // Initialize UserModel (ini otomatis menjalankan self-healing jika tabel/admin belum ada)
            $userModel = $this->model('UserModel');
            $user = $userModel->login($username, $password);

            if($user) {
                $_SESSION['user'] = $user;
                header('Location: ' . BASEURL . '/dashboard');
                exit;
            } else {
                $_SESSION['flash'] = 'Username atau password salah!';
                header('Location: ' . BASEURL . '/login');
                exit;
            }
        } else {
            header('Location: ' . BASEURL . '/login');
            exit;
        }
    }

    public function logout()
    {
        session_destroy();
        header('Location: ' . BASEURL . '/login');
        exit;
    }
}
