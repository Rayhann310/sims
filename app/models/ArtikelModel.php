<?php

class ArtikelModel {
    private $db;

    public function __construct()
    {
        $this->db = new Database();
        $this->selfHealing();
    }

    /**
     * Self-healing: Memastikan folder upload dan tabel database serta data awal siap
     */
    public function selfHealing()
    {
        try {
            // 1. Buat folder upload jika belum ada
            $dirs = [
                'public/uploads/artikel/',
                'public/uploads/artikel/content/'
            ];
            foreach ($dirs as $dir) {
                if (!file_exists($dir)) {
                    @mkdir($dir, 0777, true);
                }
            }

            // 2. Jalankan skema database dari config jika tabel belum ada
            $schemaPath = dirname(__DIR__) . '/config/database_schema.php';
            if (file_exists($schemaPath)) {
                $schema = require $schemaPath;
                $targetTables = ['artikel_kategori', 'artikel_tag', 'artikel', 'artikel_tag_pivot'];

                foreach ($targetTables as $tableName) {
                    if (isset($schema[$tableName])) {
                        $this->db->query("SHOW TABLES LIKE :table_name");
                        $this->db->bind('table_name', $tableName);
                        $this->db->execute();

                        if ($this->db->rowCount() == 0) {
                            $this->db->query($schema[$tableName]['create_sql']);
                            $this->db->execute();
                        }
                    }
                }
            }

            // 3. Seed kategori bawaan jika masih kosong
            $this->db->query("SELECT COUNT(*) as count FROM artikel_kategori");
            $countKat = $this->db->single()['count'] ?? 0;
            if ($countKat == 0) {
                $defaultKategori = [
                    ['nama' => 'Berita Utama', 'deskripsi' => 'Informasi dan berita penting seputar sekolah'],
                    ['nama' => 'Pengumuman', 'deskripsi' => 'Pengumuman resmi dari pihak sekolah'],
                    ['nama' => 'Kegiatan Sekolah', 'deskripsi' => 'Liputan acara dan kegiatan para siswa'],
                    ['nama' => 'Prestasi', 'deskripsi' => 'Pencapaian dan prestasi siswa serta guru']
                ];
                foreach ($defaultKategori as $kat) {
                    $slug = $this->makeSlug($kat['nama']);
                    $this->db->query("INSERT INTO artikel_kategori (nama_kategori, slug, deskripsi) VALUES (:nama, :slug, :deskripsi)");
                    $this->db->bind('nama', $kat['nama']);
                    $this->db->bind('slug', $slug);
                    $this->db->bind('deskripsi', $kat['deskripsi']);
                    $this->db->execute();
                }
            }

            // 4. Seed tag bawaan jika masih kosong
            $this->db->query("SELECT COUNT(*) as count FROM artikel_tag");
            $countTag = $this->db->single()['count'] ?? 0;
            if ($countTag == 0) {
                $defaultTags = ['Sekolah', 'Pendidikan', 'Prestasi', 'NW', 'PPDB'];
                foreach ($defaultTags as $tag) {
                    $slug = $this->makeSlug($tag);
                    $this->db->query("INSERT INTO artikel_tag (nama_tag, slug) VALUES (:nama, :slug)");
                    $this->db->bind('nama', $tag);
                    $this->db->bind('slug', $slug);
                    $this->db->execute();
                }
            }
        } catch (Exception $e) {
            error_log("ArtikelModel selfHealing error: " . $e->getMessage());
        }
    }

    /**
     * Membuat slug dari string teks
     */
    public function makeSlug($text)
    {
        $text = preg_replace('~[^\pL\d]+~u', '-', $text);
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
        $text = preg_replace('~[^-\w]+~', '', $text);
        $text = trim($text, '-');
        $text = preg_replace('~-+~', '-', $text);
        $text = strtolower($text);
        return empty($text) ? 'n-a-' . time() : $text;
    }

    // ==========================================
    // CRUD ARTIKEL
    // ==========================================

    public function getAllArtikel($filter = [])
    {
        $sql = "SELECT a.*, k.nama_kategori, k.slug as kategori_slug, u.nama_lengkap as nama_penulis 
                FROM artikel a 
                LEFT JOIN artikel_kategori k ON a.kategori_id = k.id 
                LEFT JOIN users u ON a.penulis_id = u.id 
                WHERE 1=1";

        if (!empty($filter['search'])) {
            $sql .= " AND (a.judul LIKE :search OR a.ringkasan LIKE :search OR a.isi LIKE :search)";
        }
        if (!empty($filter['kategori_id'])) {
            $sql .= " AND a.kategori_id = :kategori_id";
        }
        if (!empty($filter['status'])) {
            $sql .= " AND a.status = :status";
        }
        if (isset($filter['is_featured']) && $filter['is_featured'] !== '') {
            $sql .= " AND a.is_featured = :is_featured";
        }

        $sql .= " ORDER BY a.is_featured DESC, a.created_at DESC";

        $this->db->query($sql);

        if (!empty($filter['search'])) {
            $this->db->bind('search', '%' . $filter['search'] . '%');
        }
        if (!empty($filter['kategori_id'])) {
            $this->db->bind('kategori_id', $filter['kategori_id']);
        }
        if (!empty($filter['status'])) {
            $this->db->bind('status', $filter['status']);
        }
        if (isset($filter['is_featured']) && $filter['is_featured'] !== '') {
            $this->db->bind('is_featured', $filter['is_featured']);
        }

        $artikels = $this->db->resultSet();

        // Attach tag names to each artikel
        foreach ($artikels as &$item) {
            $item['tags'] = $this->getTagsByArtikelId($item['id']);
        }

        return $artikels;
    }

    public function getArtikelById($id)
    {
        $this->db->query("SELECT a.*, k.nama_kategori, u.nama_lengkap as nama_penulis 
                          FROM artikel a 
                          LEFT JOIN artikel_kategori k ON a.kategori_id = k.id 
                          LEFT JOIN users u ON a.penulis_id = u.id 
                          WHERE a.id = :id");
        $this->db->bind('id', $id);
        $artikel = $this->db->single();
        if ($artikel) {
            $artikel['tags'] = $this->getTagsByArtikelId($artikel['id']);
            $artikel['tag_ids'] = array_column($artikel['tags'], 'id');
        }
        return $artikel;
    }

    public function getArtikelBySlug($slug)
    {
        $this->db->query("SELECT a.*, k.nama_kategori, k.slug as kategori_slug, u.nama_lengkap as nama_penulis 
                          FROM artikel a 
                          LEFT JOIN artikel_kategori k ON a.kategori_id = k.id 
                          LEFT JOIN users u ON a.penulis_id = u.id 
                          WHERE a.slug = :slug");
        $this->db->bind('slug', $slug);
        $artikel = $this->db->single();
        if ($artikel) {
            $artikel['tags'] = $this->getTagsByArtikelId($artikel['id']);
        }
        return $artikel;
    }

    public function getFeaturedArtikel($limit = 3)
    {
        $this->db->query("SELECT a.*, k.nama_kategori, u.nama_lengkap as nama_penulis 
                          FROM artikel a 
                          LEFT JOIN artikel_kategori k ON a.kategori_id = k.id 
                          LEFT JOIN users u ON a.penulis_id = u.id 
                          WHERE a.status = 'Dipublikasi' AND a.is_featured = 1 
                          ORDER BY a.created_at DESC LIMIT :limit");
        $this->db->bind('limit', $limit);
        $artikels = $this->db->resultSet();
        foreach ($artikels as &$item) {
            $item['tags'] = $this->getTagsByArtikelId($item['id']);
        }
        return $artikels;
    }

    public function getLatestArtikel($limit = 6)
    {
        $this->db->query("SELECT a.*, k.nama_kategori, u.nama_lengkap as nama_penulis 
                          FROM artikel a 
                          LEFT JOIN artikel_kategori k ON a.kategori_id = k.id 
                          LEFT JOIN users u ON a.penulis_id = u.id 
                          WHERE a.status = 'Dipublikasi' 
                          ORDER BY a.created_at DESC LIMIT :limit");
        $this->db->bind('limit', $limit);
        $artikels = $this->db->resultSet();
        foreach ($artikels as &$item) {
            $item['tags'] = $this->getTagsByArtikelId($item['id']);
        }
        return $artikels;
    }

    public function tambahArtikel($data, $files = null)
    {
        try {
            $this->db->query("START TRANSACTION");
            $this->db->execute();

            // 1. Generate Slug
            $slug = $this->makeSlug($data['judul']);
            // Pastikan slug unik
            $this->db->query("SELECT id FROM artikel WHERE slug = :slug");
            $this->db->bind('slug', $slug);
            if ($this->db->single()) {
                $slug .= '-' . time();
            }

            // 2. Upload Gambar Sampul
            $gambar_sampul = null;
            if (isset($files['gambar_sampul']) && $files['gambar_sampul']['error'] == UPLOAD_ERR_OK) {
                $gambar_sampul = $this->uploadGambarSampul($files['gambar_sampul']);
            } elseif (!empty($data['gambar_url'])) {
                $gambar_sampul = $data['gambar_url'];
            }

            // 3. Ringkasan Otomatis jika kosong
            $ringkasan = !empty($data['ringkasan']) ? $data['ringkasan'] : substr(strip_tags($data['isi']), 0, 200) . '...';

            $kategori_id = !empty($data['kategori_id']) ? $data['kategori_id'] : null;
            $penulis_id = $_SESSION['user']['id'] ?? 1;
            $status = $data['status'] ?? 'Dipublikasi';
            $is_featured = isset($data['is_featured']) ? 1 : 0;

            // 4. Insert Artikel
            $this->db->query("INSERT INTO artikel (judul, slug, kategori_id, penulis_id, ringkasan, isi, gambar_sampul, status, is_featured) 
                              VALUES (:judul, :slug, :kategori_id, :penulis_id, :ringkasan, :isi, :gambar_sampul, :status, :is_featured)");
            $this->db->bind('judul', $data['judul']);
            $this->db->bind('slug', $slug);
            $this->db->bind('kategori_id', $kategori_id);
            $this->db->bind('penulis_id', $penulis_id);
            $this->db->bind('ringkasan', $ringkasan);
            $this->db->bind('isi', $data['isi']);
            $this->db->bind('gambar_sampul', $gambar_sampul);
            $this->db->bind('status', $status);
            $this->db->bind('is_featured', $is_featured);
            $this->db->execute();

            $this->db->query("SELECT LAST_INSERT_ID() as last_id");
            $artikel_id = $this->db->single()['last_id'];

            // 5. Simpan Tags (Pivot)
            if (!empty($data['tags']) && is_array($data['tags'])) {
                $this->syncTags($artikel_id, $data['tags']);
            }

            $this->db->query("COMMIT");
            $this->db->execute();
            return ['status' => true, 'pesan' => 'Artikel berhasil diterbitkan.'];
        } catch (Exception $e) {
            $this->db->query("ROLLBACK");
            $this->db->execute();
            return ['status' => false, 'pesan' => 'Gagal menyimpan artikel: ' . $e->getMessage()];
        }
    }

    public function updateArtikel($data, $files = null)
    {
        try {
            $this->db->query("START TRANSACTION");
            $this->db->execute();

            $artikel_id = $data['id'];
            $oldArtikel = $this->getArtikelById($artikel_id);
            if (!$oldArtikel) {
                throw new Exception("Artikel tidak ditemukan.");
            }

            // 1. Slug
            $slug = $oldArtikel['slug'];
            if ($oldArtikel['judul'] != $data['judul']) {
                $slug = $this->makeSlug($data['judul']);
                $this->db->query("SELECT id FROM artikel WHERE slug = :slug AND id != :id");
                $this->db->bind('slug', $slug);
                $this->db->bind('id', $artikel_id);
                if ($this->db->single()) {
                    $slug .= '-' . time();
                }
            }

            // 2. Gambar Sampul
            $gambar_sampul = $oldArtikel['gambar_sampul'];
            if (isset($files['gambar_sampul']) && $files['gambar_sampul']['error'] == UPLOAD_ERR_OK) {
                $gambar_sampul = $this->uploadGambarSampul($files['gambar_sampul']);
            } elseif (!empty($data['gambar_url'])) {
                $gambar_sampul = $data['gambar_url'];
            }

            // 3. Ringkasan
            $ringkasan = !empty($data['ringkasan']) ? $data['ringkasan'] : substr(strip_tags($data['isi']), 0, 200) . '...';
            $kategori_id = !empty($data['kategori_id']) ? $data['kategori_id'] : null;
            $status = $data['status'] ?? 'Dipublikasi';
            $is_featured = isset($data['is_featured']) ? 1 : 0;

            // 4. Update Artikel
            $this->db->query("UPDATE artikel SET 
                judul = :judul,
                slug = :slug,
                kategori_id = :kategori_id,
                ringkasan = :ringkasan,
                isi = :isi,
                gambar_sampul = :gambar_sampul,
                status = :status,
                is_featured = :is_featured
                WHERE id = :id");
            $this->db->bind('judul', $data['judul']);
            $this->db->bind('slug', $slug);
            $this->db->bind('kategori_id', $kategori_id);
            $this->db->bind('ringkasan', $ringkasan);
            $this->db->bind('isi', $data['isi']);
            $this->db->bind('gambar_sampul', $gambar_sampul);
            $this->db->bind('status', $status);
            $this->db->bind('is_featured', $is_featured);
            $this->db->bind('id', $artikel_id);
            $this->db->execute();

            // 5. Sync Tags
            $tags = !empty($data['tags']) && is_array($data['tags']) ? $data['tags'] : [];
            $this->syncTags($artikel_id, $tags);

            $this->db->query("COMMIT");
            $this->db->execute();
            return ['status' => true, 'pesan' => 'Artikel berhasil diperbarui.'];
        } catch (Exception $e) {
            $this->db->query("ROLLBACK");
            $this->db->execute();
            return ['status' => false, 'pesan' => 'Gagal memperbarui artikel: ' . $e->getMessage()];
        }
    }

    public function hapusArtikel($id)
    {
        $artikel = $this->getArtikelById($id);
        if ($artikel) {
            // Hapus file gambar fisik jika disimpan lokal
            if (!empty($artikel['gambar_sampul']) && strpos($artikel['gambar_sampul'], 'uploads/artikel/') !== false) {
                $filePath = 'public/' . strstr($artikel['gambar_sampul'], 'uploads/artikel/');
                if (file_exists($filePath)) {
                    @unlink($filePath);
                }
            }
            $this->db->query("DELETE FROM artikel WHERE id = :id");
            $this->db->bind('id', $id);
            $this->db->execute();
            return $this->db->rowCount();
        }
        return 0;
    }

    public function toggleFeatured($id)
    {
        $this->db->query("UPDATE artikel SET is_featured = IF(is_featured = 1, 0, 1) WHERE id = :id");
        $this->db->bind('id', $id);
        $this->db->execute();
        return $this->db->rowCount();
    }

    public function incrementViews($id)
    {
        $this->db->query("UPDATE artikel SET views = views + 1 WHERE id = :id");
        $this->db->bind('id', $id);
        $this->db->execute();
    }

    // ==========================================
    // MANAJEMEN TAGS & PIVOT
    // ==========================================

    private function getTagsByArtikelId($artikel_id)
    {
        $this->db->query("SELECT t.* FROM artikel_tag t 
                          JOIN artikel_tag_pivot p ON t.id = p.tag_id 
                          WHERE p.artikel_id = :artikel_id ORDER BY t.nama_tag ASC");
        $this->db->bind('artikel_id', $artikel_id);
        return $this->db->resultSet();
    }

    private function syncTags($artikel_id, array $tag_ids)
    {
        // Hapus relasi lama
        $this->db->query("DELETE FROM artikel_tag_pivot WHERE artikel_id = :artikel_id");
        $this->db->bind('artikel_id', $artikel_id);
        $this->db->execute();

        // Tambah relasi baru
        foreach ($tag_ids as $tag_id) {
            $this->db->query("INSERT IGNORE INTO artikel_tag_pivot (artikel_id, tag_id) VALUES (:artikel_id, :tag_id)");
            $this->db->bind('artikel_id', $artikel_id);
            $this->db->bind('tag_id', $tag_id);
            $this->db->execute();
        }
    }

    private function uploadGambarSampul($file)
    {
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
        if (!in_array($ext, $allowed)) {
            throw new Exception("Format gambar sampul tidak didukung (harus JPG, PNG, WEBP, GIF).");
        }
        if ($file['size'] > 5 * 1024 * 1024) { // Max 5MB
            throw new Exception("Ukuran gambar sampul terlalu besar (Maksimal 5MB).");
        }

        $dir = 'public/uploads/artikel/';
        if (!file_exists($dir)) {
            @mkdir($dir, 0777, true);
        }

        $filename = 'sampul_' . time() . '_' . rand(100, 999) . '.' . $ext;
        $target = $dir . $filename;

        if (move_uploaded_file($file['tmp_name'], $target)) {
            return BASEURL . '/public/uploads/artikel/' . $filename;
        } else {
            throw new Exception("Gagal mengunggah berkas gambar sampul.");
        }
    }

    public function uploadGambarContent($file)
    {
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
        if (!in_array($ext, $allowed)) {
            return ['status' => false, 'pesan' => 'Format gambar tidak didukung.'];
        }

        $dir = 'public/uploads/artikel/content/';
        if (!file_exists($dir)) {
            @mkdir($dir, 0777, true);
        }

        $filename = 'img_' . time() . '_' . rand(100, 999) . '.' . $ext;
        $target = $dir . $filename;

        if (move_uploaded_file($file['tmp_name'], $target)) {
            return ['status' => true, 'url' => BASEURL . '/public/uploads/artikel/content/' . $filename];
        }
        return ['status' => false, 'pesan' => 'Gagal mengunggah gambar.'];
    }

    // ==========================================
    // CRUD KATEGORI
    // ==========================================

    public function getAllKategori()
    {
        $this->db->query("SELECT k.*, COUNT(a.id) as total_artikel 
                          FROM artikel_kategori k 
                          LEFT JOIN artikel a ON k.id = a.kategori_id 
                          GROUP BY k.id 
                          ORDER BY k.nama_kategori ASC");
        return $this->db->resultSet();
    }

    public function getKategoriById($id)
    {
        $this->db->query("SELECT * FROM artikel_kategori WHERE id = :id");
        $this->db->bind('id', $id);
        return $this->db->single();
    }

    public function tambahKategori($data)
    {
        $slug = $this->makeSlug($data['nama_kategori']);
        $this->db->query("INSERT INTO artikel_kategori (nama_kategori, slug, deskripsi) VALUES (:nama, :slug, :deskripsi)");
        $this->db->bind('nama', $data['nama_kategori']);
        $this->db->bind('slug', $slug);
        $this->db->bind('deskripsi', $data['deskripsi'] ?? null);
        $this->db->execute();
        return $this->db->rowCount();
    }

    public function updateKategori($data)
    {
        $slug = $this->makeSlug($data['nama_kategori']);
        $this->db->query("UPDATE artikel_kategori SET nama_kategori = :nama, slug = :slug, deskripsi = :deskripsi WHERE id = :id");
        $this->db->bind('nama', $data['nama_kategori']);
        $this->db->bind('slug', $slug);
        $this->db->bind('deskripsi', $data['deskripsi'] ?? null);
        $this->db->bind('id', $data['id']);
        $this->db->execute();
        return $this->db->rowCount();
    }

    public function hapusKategori($id)
    {
        $this->db->query("DELETE FROM artikel_kategori WHERE id = :id");
        $this->db->bind('id', $id);
        $this->db->execute();
        return $this->db->rowCount();
    }

    // ==========================================
    // CRUD TAG
    // ==========================================

    public function getAllTag()
    {
        $this->db->query("SELECT t.*, COUNT(p.artikel_id) as total_artikel 
                          FROM artikel_tag t 
                          LEFT JOIN artikel_tag_pivot p ON t.id = p.tag_id 
                          GROUP BY t.id 
                          ORDER BY t.nama_tag ASC");
        return $this->db->resultSet();
    }

    public function getTagById($id)
    {
        $this->db->query("SELECT * FROM artikel_tag WHERE id = :id");
        $this->db->bind('id', $id);
        return $this->db->single();
    }

    public function tambahTag($data)
    {
        $slug = $this->makeSlug($data['nama_tag']);
        $this->db->query("INSERT INTO artikel_tag (nama_tag, slug) VALUES (:nama, :slug)");
        $this->db->bind('nama', $data['nama_tag']);
        $this->db->bind('slug', $slug);
        $this->db->execute();
        return $this->db->rowCount();
    }

    public function updateTag($data)
    {
        $slug = $this->makeSlug($data['nama_tag']);
        $this->db->query("UPDATE artikel_tag SET nama_tag = :nama, slug = :slug WHERE id = :id");
        $this->db->bind('nama', $data['nama_tag']);
        $this->db->bind('slug', $slug);
        $this->db->bind('id', $data['id']);
        $this->db->execute();
        return $this->db->rowCount();
    }

    public function hapusTag($id)
    {
        $this->db->query("DELETE FROM artikel_tag WHERE id = :id");
        $this->db->bind('id', $id);
        $this->db->execute();
        return $this->db->rowCount();
    }
}
