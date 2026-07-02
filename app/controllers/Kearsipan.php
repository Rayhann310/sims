<?php

class Kearsipan extends Controller {
    public function __construct()
    {
        requireAccess('kearsipan');
    }

    public function index()
    {
        $data['judul'] = 'Kearsipan & Tata Usaha';
        $model = $this->model('KearsipanModel');
        $data['surat'] = $model->getAllSurat(); // uncategorized
        $data['kategori'] = $model->getAllKategori(); // folders

        $db = new Database();
        $db->query("SELECT COUNT(id) as total FROM kearsipan");
        $data['total_surat'] = $db->single()['total'] ?? 0;
        
        $db->query("SELECT COUNT(id) as total FROM kearsipan WHERE jenis_surat = 'Masuk'");
        $data['surat_masuk'] = $db->single()['total'] ?? 0;
        
        $db->query("SELECT COUNT(id) as total FROM kearsipan WHERE jenis_surat = 'Keluar'");
        $data['surat_keluar'] = $db->single()['total'] ?? 0;

        $this->view('templates/admin_header', $data);
        $this->view('kearsipan/index', $data);
        $this->view('templates/admin_footer');
    }

    public function folder($id)
    {
        $data['judul'] = 'Isi Folder Kearsipan';
        $model = $this->model('KearsipanModel');
        $data['kategori_aktif'] = $model->getKategoriById($id);
        
        if(!$data['kategori_aktif']) {
            header('Location: ' . BASEURL . '/kearsipan');
            exit;
        }

        $data['surat'] = $model->getSuratByKategori($id);

        $this->view('templates/admin_header', $data);
        $this->view('kearsipan/folder', $data);
        $this->view('templates/admin_footer');
    }

    public function tambahKategori()
    {
        if(isset($_POST['nama_kategori']) && !empty(trim($_POST['nama_kategori']))) {
            if($this->model('KearsipanModel')->tambahKategori(trim($_POST['nama_kategori'])) > 0) {
                Flasher::setFlash('Folder berhasil', 'dibuat', 'success');
            } else {
                Flasher::setFlash('Folder gagal', 'dibuat', 'danger');
            }
        }
        header('Location: ' . BASEURL . '/kearsipan');
        exit;
    }

    public function ubahKategori()
    {
        if(isset($_POST['id']) && isset($_POST['nama_kategori']) && !empty(trim($_POST['nama_kategori']))) {
            if($this->model('KearsipanModel')->ubahKategori($_POST) > 0) {
                Flasher::setFlash('Folder berhasil', 'diubah', 'success');
            } else {
                Flasher::setFlash('Folder gagal', 'diubah', 'danger');
            }
        }
        header('Location: ' . BASEURL . '/kearsipan');
        exit;
    }

    public function hapusKategori($id)
    {
        if($this->model('KearsipanModel')->hapusKategori($id) > 0) {
            Flasher::setFlash('Folder berhasil', 'dihapus', 'success');
        } else {
            Flasher::setFlash('Folder gagal', 'dihapus', 'danger');
        }
        header('Location: ' . BASEURL . '/kearsipan');
        exit;
    }

    public function tambah()
    {
        $model = $this->model('KearsipanModel');
        
        // Resolve category name for fallback string
        if(!empty($_POST['kategori_id'])) {
            $kat = $model->getKategoriById($_POST['kategori_id']);
            $_POST['kategori_nama'] = $kat ? $kat['nama_kategori'] : '-';
        } else {
            $_POST['kategori_nama'] = 'Tanpa Kategori';
        }

        if($model->tambahSurat($_POST, $_FILES) > 0) {
            Flasher::setFlash('Surat/Dokumen berhasil', 'ditambahkan', 'success');
        } else {
            Flasher::setFlash('Surat/Dokumen gagal', 'ditambahkan', 'danger');
        }
        
        // redirect back to folder if it was in a folder
        if(!empty($_POST['kategori_id'])) {
            header('Location: ' . BASEURL . '/kearsipan/folder/' . $_POST['kategori_id']);
        } else {
            header('Location: ' . BASEURL . '/kearsipan');
        }
        exit;
    }

    public function hapus($id)
    {
        // Find which category it belonged to, to redirect back properly
        $model = $this->model('KearsipanModel');
        $db = new Database();
        $db->query("SELECT kategori_id FROM kearsipan WHERE id = :id");
        $db->bind('id', $id);
        $surat = $db->single();
        $kategori_id = $surat ? $surat['kategori_id'] : null;

        if($model->hapusSurat($id) > 0) {
            Flasher::setFlash('Surat berhasil', 'dihapus', 'success');
        } else {
            Flasher::setFlash('Surat gagal', 'dihapus', 'danger');
        }

        if($kategori_id) {
            header('Location: ' . BASEURL . '/kearsipan/folder/' . $kategori_id);
        } else {
            header('Location: ' . BASEURL . '/kearsipan');
        }
        exit;
    }
}
