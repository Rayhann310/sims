<?php

class Alumni extends Controller {
    public function __construct()
    {
        if(!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'admin') {
            header('Location: ' . BASEURL . '/login');
            exit;
        }
    }

    public function index()
    {
        $data['judul'] = 'Manajemen Data Alumni';
        $data['alumni'] = $this->model('AlumniModel')->getAllAlumni();

        $db = new Database();
        $db->query("SELECT COUNT(id) as total FROM siswa WHERE status = 'Alumni'");
        $data['total_alumni'] = $db->single()['total'] ?? 0;
        
        $db->query("SELECT COUNT(id) as total FROM siswa WHERE status = 'Alumni' AND jenis_kelamin = 'L'");
        $data['alumni_l'] = $db->single()['total'] ?? 0;
        
        $db->query("SELECT COUNT(id) as total FROM siswa WHERE status = 'Alumni' AND jenis_kelamin = 'P'");
        $data['alumni_p'] = $db->single()['total'] ?? 0;

        $this->view('templates/admin_header', $data);
        $this->view('alumni/index', $data);
        $this->view('templates/admin_footer');
    }
}
