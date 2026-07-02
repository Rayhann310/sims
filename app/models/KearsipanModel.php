<?php

class KearsipanModel {
    private $db;

    public function __construct()
    {
        $this->db = new Database();
        $this->selfHealing();
    }

    private function selfHealing()
    {
        $db_heal = new Database();
        
        // 1. Create table kearsipan_kategori
        try {
            $db_heal->query("CREATE TABLE IF NOT EXISTS `kearsipan_kategori` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `nama_kategori` varchar(100) NOT NULL,
                `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");
            $db_heal->execute();
        } catch (Exception $e) {}

        // 2. Add kategori_id to kearsipan table if not exists
        try {
            $db_heal->query("ALTER TABLE kearsipan ADD COLUMN kategori_id INT NULL AFTER id");
            $db_heal->execute();
            
            // Populate old data with default category string to new kategori_id if needed
            // For simplicity, we just leave it null for old records, or we could map them.
        } catch (Exception $e) {}
    }

    // ==========================================
    // KATEGORI / FOLDER
    // ==========================================
    public function getAllKategori()
    {
        $this->db->query("SELECT * FROM kearsipan_kategori ORDER BY nama_kategori ASC");
        return $this->db->resultSet();
    }

    public function getKategoriById($id)
    {
        $this->db->query("SELECT * FROM kearsipan_kategori WHERE id = :id");
        $this->db->bind('id', $id);
        return $this->db->single();
    }

    public function tambahKategori($nama)
    {
        $this->db->query("INSERT INTO kearsipan_kategori (nama_kategori) VALUES (:nama)");
        $this->db->bind('nama', $nama);
        $this->db->execute();
        return $this->db->rowCount();
    }

    public function hapusKategori($id)
    {
        $this->db->query("DELETE FROM kearsipan_kategori WHERE id = :id");
        $this->db->bind('id', $id);
        $this->db->execute();
        return $this->db->rowCount();
    }

    // ==========================================
    // SURAT / DOKUMEN
    // ==========================================
    public function getAllSurat()
    {
        // Get files that are NOT in a specific category (or all files)
        // Let's get files where kategori_id IS NULL or 0 for the root view
        $this->db->query("SELECT * FROM kearsipan WHERE kategori_id IS NULL OR kategori_id = 0 ORDER BY tanggal_surat DESC, id DESC");
        return $this->db->resultSet();
    }

    public function getSuratByKategori($kategori_id)
    {
        $this->db->query("SELECT * FROM kearsipan WHERE kategori_id = :kategori_id ORDER BY tanggal_surat DESC, id DESC");
        $this->db->bind('kategori_id', $kategori_id);
        return $this->db->resultSet();
    }

    public function getSuratById($id)
    {
        $this->db->query("SELECT * FROM kearsipan WHERE id = :id");
        $this->db->bind('id', $id);
        return $this->db->single();
    }

    public function tambahSurat($data, $file)
    {
        // Handle File Upload
        $fileName = null;
        if($file['file_surat']['error'] === 0) {
            $ext = pathinfo($file['file_surat']['name'], PATHINFO_EXTENSION);
            $fileName = uniqid() . '.' . $ext;
            
            // Create folder if not exists
            $uploadDir = 'public/uploads/kearsipan/';
            if(!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            
            move_uploaded_file($file['file_surat']['tmp_name'], $uploadDir . $fileName);
        }

        $query = "INSERT INTO kearsipan (kategori_id, nomor_surat, tanggal_surat, jenis_surat, kategori, pengirim_penerima, perihal, keterangan, file_surat) 
                  VALUES (:kategori_id, :nomor_surat, :tanggal_surat, :jenis_surat, :kategori, :pengirim_penerima, :perihal, :keterangan, :file_surat)";
        
        $this->db->query($query);
        
        // Handle root category
        $kategori_id = (isset($data['kategori_id']) && $data['kategori_id'] !== '') ? $data['kategori_id'] : null;
        $this->db->bind('kategori_id', $kategori_id);
        
        $this->db->bind('nomor_surat', $data['nomor_surat']);
        $this->db->bind('tanggal_surat', $data['tanggal_surat']);
        $this->db->bind('jenis_surat', $data['jenis_surat']);
        // Store category name as fallback or string
        $this->db->bind('kategori', isset($data['kategori_nama']) ? $data['kategori_nama'] : '-');
        $this->db->bind('pengirim_penerima', $data['pengirim_penerima']);
        $this->db->bind('perihal', $data['perihal']);
        $this->db->bind('keterangan', $data['keterangan']);
        $this->db->bind('file_surat', $fileName);

        $this->db->execute();
        return $this->db->rowCount();
    }

    public function hapusSurat($id)
    {
        $surat = $this->getSuratById($id);
        if($surat && $surat['file_surat']) {
            $filePath = 'public/uploads/kearsipan/' . $surat['file_surat'];
            if(file_exists($filePath)) {
                unlink($filePath);
            }
        }

        $this->db->query("DELETE FROM kearsipan WHERE id = :id");
        $this->db->bind('id', $id);
        $this->db->execute();
        return $this->db->rowCount();
    }
}
