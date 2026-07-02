<?php

class HakAkses extends Controller {
    public function __construct()
    {
        requireAccess('hak_akses');
    }

    public function index()
    {
        $data['judul'] = 'Manajemen Hak Akses Menu';
        $result = $this->model('HakAksesModel')->getAllAksesGrouped();
        $data['jabatans'] = $result['jabatans'];
        $data['lookup'] = $result['lookup'];
        $data['menu_list'] = HakAksesModel::$MENU_LIST;

        $this->view('templates/admin_header', $data);
        $this->view('hak_akses/index', $data);
        $this->view('templates/admin_footer');
    }

    // AJAX endpoint: toggle satu menu untuk satu jabatan
    public function apiToggle()
    {
        header('Content-Type: application/json');
        if($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['status' => false, 'message' => 'Method not allowed']);
            exit;
        }

        $jabatan_id = $_POST['jabatan_id'] ?? null;
        $menu_key   = $_POST['menu_key'] ?? null;
        $is_active  = isset($_POST['is_active']) ? (int)$_POST['is_active'] : 0;

        if (!$jabatan_id || !$menu_key) {
            echo json_encode(['status' => false, 'message' => 'Parameter tidak valid']);
            exit;
        }

        $result = $this->model('HakAksesModel')->toggleMenu($jabatan_id, $menu_key, $is_active);
        echo json_encode(['status' => $result, 'message' => $result ? 'Berhasil diperbarui' : 'Gagal']);
        exit;
    }

    public function reset()
    {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->model('HakAksesModel')->resetSemua();
            $_SESSION['flash'] = ['pesan' => 'Semua hak akses berhasil di-reset', 'aksi' => '(semua menu dikembalikan ke default/nonaktif)', 'tipe' => 'success'];
            header('Location: ' . BASEURL . '/hakAkses');
            exit;
        }
    }
}
