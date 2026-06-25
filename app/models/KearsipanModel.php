<?php

class KearsipanModel {
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function getAllSurat()
    {
        $this->db->query("SELECT * FROM kearsipan ORDER BY tanggal_surat DESC, id DESC");
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

        $query = "INSERT INTO kearsipan (nomor_surat, tanggal_surat, jenis_surat, kategori, pengirim_penerima, perihal, keterangan, file_surat) 
                  VALUES (:nomor_surat, :tanggal_surat, :jenis_surat, :kategori, :pengirim_penerima, :perihal, :keterangan, :file_surat)";
        
        $this->db->query($query);
        $this->db->bind('nomor_surat', $data['nomor_surat']);
        $this->db->bind('tanggal_surat', $data['tanggal_surat']);
        $this->db->bind('jenis_surat', $data['jenis_surat']);
        $this->db->bind('kategori', $data['kategori']);
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
