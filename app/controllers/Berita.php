<?php

/**
 * Controller: Berita (Publik)
 * Halaman berita / artikel yang dapat diakses oleh publik tanpa login.
 * Route: /berita           → daftar berita
 *        /berita/{slug}    → detail berita
 */
class Berita extends Controller {

    public function __construct()
    {
        // Tidak perlu autentikasi — halaman publik
    }

    /**
     * Daftar semua berita (publik)
     */
    public function index()
    {
        $artikelModel = $this->model('ArtikelModel');

        // Filter dari query string
        $filter = [
            'search'     => $_GET['search'] ?? '',
            'kategori_id' => $_GET['kategori_id'] ?? '',
            'tag_id'     => $_GET['tag_id'] ?? '',
            'status'     => 'Dipublikasi', // Hanya tampilkan yang sudah dipublikasi
            'is_featured' => (isset($_GET['filter']) && $_GET['filter'] === 'unggulan') ? '1' : '',
        ];

        $data['judul']         = 'Berita & Artikel — SMA Nahdlatul Wathan Jakarta';
        $data['hide_navbar']   = false;
        $data['artikels']      = $artikelModel->getAllArtikel($filter);
        $data['kategori_list'] = $artikelModel->getAllKategori();
        $data['tag_list']      = $artikelModel->getAllTag();
        $data['filter']        = $filter;
        $data['filter_label']  = (isset($_GET['filter']) && $_GET['filter'] === 'unggulan') ? 'Berita Unggulan' : '';

        $this->view('templates/header', $data);
        $this->view('berita/index', $data);
        $this->view('templates/footer');
    }

    /**
     * Detail satu artikel (publik) — diakses via slug
     * URL: /berita/{slug}
     */
    public function detail($slug = null)
    {
        if (!$slug) {
            header('Location: ' . BASEURL . '/berita');
            exit;
        }

        $artikelModel = $this->model('ArtikelModel');
        $artikel = $artikelModel->getArtikelBySlug($slug);

        // Artikel tidak ditemukan atau masih draft
        if (!$artikel || $artikel['status'] !== 'Dipublikasi') {
            $data['judul']       = 'Artikel Tidak Ditemukan';
            $data['hide_navbar'] = false;
            $this->view('templates/header', $data);
            $this->view('berita/not_found', $data);
            $this->view('templates/footer');
            return;
        }

        // Tambah view counter
        $artikelModel->incrementViews($artikel['id']);

        // Ambil artikel terkait (same category atau shared tag, exclude current)
        $artikelTerkait = $artikelModel->getArtikelTerkait(
            $artikel['id'],
            $artikel['kategori_id'],
            array_column($artikel['tags'] ?? [], 'id'),
            5
        );

        $data['judul']          = htmlspecialchars($artikel['judul']) . ' — SMA NW Jakarta';
        $data['hide_navbar']    = false;
        $data['artikel']        = $artikel;
        $data['artikel_terkait'] = $artikelTerkait;

        $this->view('templates/header', $data);
        $this->view('berita/detail', $data);
        $this->view('templates/footer');
    }
}
