<?php

class ProctorModel {
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

            $this->db->query("CREATE TABLE IF NOT EXISTS cbt_peserta (
                id_peserta INT AUTO_INCREMENT PRIMARY KEY,
                id_jadwal INT NOT NULL,
                id_siswa INT NOT NULL,
                waktu_mulai DATETIME NULL,
                sisa_waktu_detik INT NULL,
                status_ujian ENUM('0', '1', '2', '3') DEFAULT '0',
                alasan_terkunci VARCHAR(255) NULL,
                nilai FLOAT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )");
            $this->db->execute();
        } catch (\Throwable $e) {}
    }

    public function getGuruIdByUserId($user_id)
    {
        $this->db->query("SELECT id FROM guru WHERE user_id = :user_id LIMIT 1");
        $this->db->bind('user_id', $user_id);
        $row = $this->db->single();
        return $row ? $row['id'] : 0;
    }

    public function getJadwalDiawasi($id_guru)
    {
        $this->db->query("SELECT j.*, m.nama_mapel, r.nama_rombel FROM cbt_jadwal j LEFT JOIN mata_pelajaran m ON j.id_mapel = m.id LEFT JOIN rombel r ON j.id_rombel = r.id WHERE j.id_guru_pengawas = :id_guru AND j.status = 'Aktif' ORDER BY j.waktu_mulai DESC");
        $this->db->bind('id_guru', $id_guru);
        return $this->db->resultSet();
    }

    public function getPesertaUjian($id_jadwal)
    {
        $this->db->query("
            SELECT p.*, s.nisn, u.nama_lengkap 
            FROM cbt_peserta p 
            JOIN siswa s ON p.id_siswa = s.id 
            JOIN users u ON s.user_id = u.id 
            WHERE p.id_jadwal = :id_jadwal
            ORDER BY u.nama_lengkap ASC
        ");
        $this->db->bind('id_jadwal', $id_jadwal);
        return $this->db->resultSet();
    }

    public function bukaKunciSiswa($id_peserta)
    {
        // Ubah status_ujian dari 2 (Terkunci) menjadi 1 (Mengerjakan) atau 0
        $this->db->query("UPDATE cbt_peserta SET status_ujian = '1', alasan_terkunci = NULL WHERE id_peserta = :id_peserta");
        $this->db->bind('id_peserta', $id_peserta);
        $this->db->execute();
        return $this->db->rowCount();
    }

    public function updateToken($id_jadwal, $token)
    {
        $this->db->query("UPDATE cbt_jadwal SET token_aktif = :token, token_last_update = CURRENT_TIMESTAMP WHERE id_jadwal = :id_jadwal");
        $this->db->bind('token', $token);
        $this->db->bind('id_jadwal', $id_jadwal);
        $this->db->execute();
        return $this->db->rowCount();
    }
}
