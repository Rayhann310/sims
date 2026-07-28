<?php

class Artikel extends Controller {

    public function __construct()
    {
        // Enforce strict access control
        requireAccess('artikel');
    }

    public function index()
    {
        $data['judul'] = 'Manajemen Artikel & Berita';
        $artikelModel = $this->model('ArtikelModel');

        $filter = [
            'search' => $_GET['search'] ?? '',
            'kategori_id' => $_GET['kategori_id'] ?? '',
            'status' => $_GET['status'] ?? '',
            'is_featured' => $_GET['is_featured'] ?? ''
        ];

        $data['artikels'] = $artikelModel->getAllArtikel($filter);
        $data['kategori_list'] = $artikelModel->getAllKategori();
        $data['filter'] = $filter;

        // Statistics
        $all = $artikelModel->getAllArtikel();
        $data['stats'] = [
            'total' => count($all),
            'dipublikasi' => count(array_filter($all, function($a) { return $a['status'] === 'Dipublikasi'; })),
            'draft' => count(array_filter($all, function($a) { return $a['status'] === 'Draft'; })),
            'featured' => count(array_filter($all, function($a) { return $a['is_featured'] == 1; }))
        ];

        $this->view('templates/admin_header', $data);
        $this->view('artikel/index', $data);
        $this->view('templates/admin_footer');
    }

    public function tambah()
    {
        $data['judul'] = 'Tulis Artikel Baru';
        $artikelModel = $this->model('ArtikelModel');

        $data['kategori_list'] = $artikelModel->getAllKategori();
        $data['tag_list'] = $artikelModel->getAllTag();

        $this->view('templates/admin_header', $data);
        $this->view('artikel/tambah', $data);
        $this->view('templates/admin_footer');
    }

    public function simpan()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $artikelModel = $this->model('ArtikelModel');
            $res = $artikelModel->tambahArtikel($_POST, $_FILES);

            if ($res['status']) {
                Flasher::setFlash('Berhasil', $res['pesan'], 'success');
                header('Location: ' . BASEURL . '/artikel');
                exit;
            } else {
                Flasher::setFlash('Gagal', $res['pesan'], 'danger');
                header('Location: ' . BASEURL . '/artikel/tambah');
                exit;
            }
        }
    }

    public function edit($id)
    {
        $artikelModel = $this->model('ArtikelModel');
        $artikel = $artikelModel->getArtikelById($id);

        if (!$artikel) {
            Flasher::setFlash('Gagal', 'Artikel tidak ditemukan.', 'danger');
            header('Location: ' . BASEURL . '/artikel');
            exit;
        }

        $data['judul'] = 'Edit Artikel';
        $data['artikel'] = $artikel;
        $data['kategori_list'] = $artikelModel->getAllKategori();
        $data['tag_list'] = $artikelModel->getAllTag();

        $this->view('templates/admin_header', $data);
        $this->view('artikel/edit', $data);
        $this->view('templates/admin_footer');
    }

    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $artikelModel = $this->model('ArtikelModel');
            $res = $artikelModel->updateArtikel($_POST, $_FILES);

            if ($res['status']) {
                Flasher::setFlash('Berhasil', $res['pesan'], 'success');
                header('Location: ' . BASEURL . '/artikel');
                exit;
            } else {
                Flasher::setFlash('Gagal', $res['pesan'], 'danger');
                header('Location: ' . BASEURL . '/artikel/edit/' . $_POST['id']);
                exit;
            }
        }
    }

    public function hapus($id)
    {
        $artikelModel = $this->model('ArtikelModel');
        if ($artikelModel->hapusArtikel($id) > 0) {
            Flasher::setFlash('Berhasil', 'Artikel berhasil dihapus.', 'success');
        } else {
            Flasher::setFlash('Gagal', 'Gagal menghapus artikel.', 'danger');
        }
        header('Location: ' . BASEURL . '/artikel');
        exit;
    }

    public function toggleFeatured($id)
    {
        $artikelModel = $this->model('ArtikelModel');
        if ($artikelModel->toggleFeatured($id) > 0) {
            Flasher::setFlash('Berhasil', 'Status Berita Unggulan berhasil diperbarui.', 'success');
        } else {
            Flasher::setFlash('Gagal', 'Gagal memperbarui status Berita Unggulan.', 'danger');
        }
        header('Location: ' . BASEURL . '/artikel');
        exit;
    }

    // ==========================================
    // MANAGEMENT KATEGORI
    // ==========================================

    public function kategori()
    {
        $data['judul'] = 'Manajemen Kategori Artikel';
        $artikelModel = $this->model('ArtikelModel');

        $data['kategori_list'] = $artikelModel->getAllKategori();

        $this->view('templates/admin_header', $data);
        $this->view('artikel/kategori', $data);
        $this->view('templates/admin_footer');
    }

    public function tambahKategori()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $artikelModel = $this->model('ArtikelModel');
            if ($artikelModel->tambahKategori($_POST) > 0) {
                Flasher::setFlash('Berhasil', 'Kategori baru berhasil ditambahkan.', 'success');
            } else {
                Flasher::setFlash('Gagal', 'Gagal menambahkan kategori.', 'danger');
            }
        }
        header('Location: ' . BASEURL . '/artikel/kategori');
        exit;
    }

    public function updateKategori()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $artikelModel = $this->model('ArtikelModel');
            if ($artikelModel->updateKategori($_POST) > 0) {
                Flasher::setFlash('Berhasil', 'Kategori berhasil diperbarui.', 'success');
            } else {
                Flasher::setFlash('Gagal', 'Gagal memperbarui kategori.', 'danger');
            }
        }
        header('Location: ' . BASEURL . '/artikel/kategori');
        exit;
    }

    public function hapusKategori($id)
    {
        $artikelModel = $this->model('ArtikelModel');
        if ($artikelModel->hapusKategori($id) > 0) {
            Flasher::setFlash('Berhasil', 'Kategori berhasil dihapus.', 'success');
        } else {
            Flasher::setFlash('Gagal', 'Gagal menghapus kategori.', 'danger');
        }
        header('Location: ' . BASEURL . '/artikel/kategori');
        exit;
    }

    // ==========================================
    // MANAGEMENT TAGS
    // ==========================================

    public function tag()
    {
        $data['judul'] = 'Manajemen Tag Artikel';
        $artikelModel = $this->model('ArtikelModel');

        $data['tag_list'] = $artikelModel->getAllTag();

        $this->view('templates/admin_header', $data);
        $this->view('artikel/tag', $data);
        $this->view('templates/admin_footer');
    }

    public function tambahTag()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $artikelModel = $this->model('ArtikelModel');
            if ($artikelModel->tambahTag($_POST) > 0) {
                Flasher::setFlash('Berhasil', 'Tag baru berhasil ditambahkan.', 'success');
            } else {
                Flasher::setFlash('Gagal', 'Gagal menambahkan tag.', 'danger');
            }
        }
        header('Location: ' . BASEURL . '/artikel/tag');
        exit;
    }

    public function updateTag()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $artikelModel = $this->model('ArtikelModel');
            if ($artikelModel->updateTag($_POST) > 0) {
                Flasher::setFlash('Berhasil', 'Tag berhasil diperbarui.', 'success');
            } else {
                Flasher::setFlash('Gagal', 'Gagal memperbarui tag.', 'danger');
            }
        }
        header('Location: ' . BASEURL . '/artikel/tag');
        exit;
    }

    public function hapusTag($id)
    {
        $artikelModel = $this->model('ArtikelModel');
        if ($artikelModel->hapusTag($id) > 0) {
            Flasher::setFlash('Berhasil', 'Tag berhasil dihapus.', 'success');
        } else {
            Flasher::setFlash('Gagal', 'Gagal menghapus tag.', 'danger');
        }
        header('Location: ' . BASEURL . '/artikel/tag');
        exit;
    }

    // ==========================================
    // UPLOAD GAMBAR CONTENT (AJAX)
    // ==========================================

    public function uploadImage()
    {
        header('Content-Type: application/json');
        if (isset($_FILES['file'])) {
            $artikelModel = $this->model('ArtikelModel');
            $res = $artikelModel->uploadGambarContent($_FILES['file']);
            echo json_encode($res);
            exit;
        }
        echo json_encode(['status' => false, 'pesan' => 'Tidak ada gambar diunggah.']);
        exit;
    }
}
