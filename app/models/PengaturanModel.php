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
        $this->db->query("UPDATE pengaturan SET nama_aplikasi = :nama_aplikasi, logo_teks = :logo_teks, teks_footer = :teks_footer WHERE id = :id");
        $this->db->bind('nama_aplikasi', $data['nama_aplikasi']);
        $this->db->bind('logo_teks', $data['logo_teks']);
        $this->db->bind('teks_footer', $data['teks_footer']);
        $this->db->bind('id', 1);
        $this->db->execute();
        return $this->db->rowCount();
    }
}
