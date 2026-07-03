<?php

class JadwalUjianModel {
    private $table = 'cbt_jadwal';
    private $db;

    public function __construct()
    {
        $this->db = new Database();
        $this->selfHealing();
    }

    private function selfHealing()
    {
        try {
            $this->db->query("CREATE TABLE IF NOT EXISTS cbt_jadwal (
                id_jadwal INT AUTO_INCREMENT PRIMARY KEY,
                nama_ujian VARCHAR(100) NOT NULL,
                id_mapel INT NOT NULL,
                waktu_mulai DATETIME NOT NULL,
                waktu_selesai DATETIME NOT NULL,
                durasi_menit INT NOT NULL,
                id_guru_pengawas INT NOT NULL,
                token_aktif VARCHAR(10) NULL,
                token_last_update TIMESTAMP NULL,
                status ENUM('Draft', 'Aktif', 'Selesai') DEFAULT 'Draft',
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )");
            $this->db->execute();
        } catch (\Throwable $e) {}
    }

    public function getAllJadwal()
    {
        // Join dengan tabel mapel dan guru pengawas
        $query = "SELECT j.*, g.nama_lengkap AS nama_pengawas 
                  FROM " . $this->table . " j 
                  LEFT JOIN guru g ON j.id_guru_pengawas = g.id 
                  ORDER BY j.waktu_mulai DESC";
        $this->db->query($query);
        return $this->db->resultSet();
    }

    public function tambahDataJadwal($data)
    {
        $query = "INSERT INTO " . $this->table . "
                    (nama_ujian, id_mapel, waktu_mulai, waktu_selesai, durasi_menit, id_guru_pengawas, status)
                  VALUES
                    (:nama_ujian, :id_mapel, :waktu_mulai, :waktu_selesai, :durasi_menit, :id_guru_pengawas, :status)";
        
        $this->db->query($query);
        
        $this->db->bind('nama_ujian', $data['nama_ujian']);
        $this->db->bind('id_mapel', 1); // hardcode sementara 
        $this->db->bind('waktu_mulai', $data['waktu_mulai']);
        $this->db->bind('waktu_selesai', $data['waktu_selesai']);
        $this->db->bind('durasi_menit', $data['durasi_menit']);
        $this->db->bind('id_guru_pengawas', $data['id_guru_pengawas']);
        $this->db->bind('status', $data['status']);

        $this->db->execute();
        return $this->db->rowCount();
    }

    public function hapusDataJadwal($id)
    {
        $query = "DELETE FROM " . $this->table . " WHERE id_jadwal = :id_jadwal";
        $this->db->query($query);
        $this->db->bind('id_jadwal', $id);
        $this->db->execute();
        return $this->db->rowCount();
    }

    // Mengambil daftar semua guru untuk dropdown pengawas
    public function getAllGuru()
    {
        $this->db->query("SELECT id, nama_lengkap FROM guru ORDER BY nama_lengkap ASC");
        return $this->db->resultSet();
    }
}
