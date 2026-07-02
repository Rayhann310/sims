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
        if(isset($data['gambar_hero_spmb'])) {
            $query .= ", gambar_hero_spmb = :gambar_hero_spmb";
        }
        if(isset($data['brosur_spmb'])) {
            $query .= ", brosur_spmb = :brosur_spmb";
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
        if(isset($data['gambar_hero_spmb'])) {
            $this->db->bind('gambar_hero_spmb', $data['gambar_hero_spmb']);
        }
        if(isset($data['brosur_spmb'])) {
            $this->db->bind('brosur_spmb', $data['brosur_spmb']);
        }
        
        $this->db->bind('id', 1);
        
        try {
            $this->db->execute();
        } catch (PDOException $e) {
            // Self healing
            $db_heal = new Database();
            try {
                $db_heal->query("ALTER TABLE pengaturan ADD COLUMN fonnte_token VARCHAR(255) NULL DEFAULT NULL AFTER logo_sekolah");
                $db_heal->execute();
            } catch (Exception $ex) {} // Abaikan jika sudah ada
            
            try {
                $db_heal->query("ALTER TABLE pengaturan ADD COLUMN gambar_hero_spmb LONGTEXT NULL DEFAULT NULL AFTER fonnte_token");
                $db_heal->execute();
            } catch (Exception $ex) {}
            
            try {
                $db_heal->query("ALTER TABLE pengaturan ADD COLUMN brosur_spmb LONGTEXT NULL DEFAULT NULL AFTER gambar_hero_spmb");
                $db_heal->execute();
            } catch (Exception $ex) {}
            
            try {
                // Ubah logo_sekolah jadi LONGTEXT agar aman buat base64
                $db_heal->query("ALTER TABLE pengaturan MODIFY COLUMN logo_sekolah LONGTEXT");
                $db_heal->execute();
            } catch (Exception $ex) {}
            
            // Coba eksekusi ulang setelah self-healing
            $this->db->execute();
        }
        
        return $this->db->rowCount();
    }
}
