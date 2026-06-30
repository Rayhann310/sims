<?php

class PengaturanModel {
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function getPengaturan()
    {
        $this->db->query("SELECT * FROM pengaturan ORDER BY id ASC LIMIT 1");
        return $this->db->single();
    }

    public function updatePengaturan($data)
    {
        $query = "UPDATE pengaturan SET nama_aplikasi = :nama_aplikasi, logo_teks = :logo_teks, teks_footer = :teks_footer, fonnte_token = :fonnte_token";
        
        if(isset($data['logo_sekolah'])) {
            $query .= ", logo_sekolah = :logo_sekolah";
        }
        
        $query .= " WHERE id = :id";
        
        $this->db->query($query);
        $this->db->bind('nama_aplikasi', $data['nama_aplikasi']);
        $this->db->bind('logo_teks', $data['logo_teks']);
        $this->db->bind('teks_footer', $data['teks_footer']);
        $this->db->bind('fonnte_token', $data['fonnte_token'] ?? null);
        
        if(isset($data['logo_sekolah'])) {
            $this->db->bind('logo_sekolah', $data['logo_sekolah']);
        }
        
        $this->db->bind('id', 1);
        
        try {
            $this->db->execute();
        } catch (PDOException $e) {
            // Self healing jika kolom fonnte_token belum ada
            if(strpos($e->getMessage(), 'Unknown column') !== false) {
                $db_heal = new Database();
                $db_heal->query("ALTER TABLE pengaturan ADD COLUMN fonnte_token VARCHAR(255) NULL DEFAULT NULL AFTER logo_sekolah");
                $db_heal->execute();
                
                // Coba eksekusi ulang
                $this->db->execute();
            } else {
                throw $e;
            }
        }
        
        return $this->db->rowCount();
    }
}
