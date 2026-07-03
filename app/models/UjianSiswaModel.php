<?php

class UjianSiswaModel {
    private $db;

    public function __construct()
    {
        $this->db = new Database();
        $this->selfHealing();
    }

    private function selfHealing()
    {
        try {
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

            $this->db->query("CREATE TABLE IF NOT EXISTS cbt_jawaban (
                id_jawaban INT AUTO_INCREMENT PRIMARY KEY,
                id_peserta INT NOT NULL,
                id_soal INT NOT NULL,
                jawaban_siswa TEXT NULL,
                ragu_ragu TINYINT(1) DEFAULT 0,
                skor FLOAT DEFAULT 0,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            )");
            $this->db->execute();
        } catch (\Throwable $e) {}
    }

    public function getSiswaRombelAktif($user_id)
    {
        $this->db->query("
            SELECT r.id as id_rombel 
            FROM siswa s 
            JOIN anggota_rombel ar ON s.id = ar.siswa_id 
            JOIN rombel r ON ar.rombel_id = r.id 
            JOIN tahun_akademik ta ON r.tahun_akademik_id = ta.id 
            WHERE s.user_id = :user_id AND ta.status = 'Aktif'
            LIMIT 1
        ");
        $this->db->bind('user_id', $user_id);
        $row = $this->db->single();
        return $row ? $row['id_rombel'] : 0;
    }

    public function getIdSiswa($user_id)
    {
        $this->db->query("SELECT id FROM siswa WHERE user_id = :user_id");
        $this->db->bind('user_id', $user_id);
        $row = $this->db->single();
        return $row ? $row['id'] : 0;
    }

    public function getJadwalAktif($id_rombel)
    {
        $query = "SELECT j.*, m.nama_mapel 
                  FROM cbt_jadwal j 
                  LEFT JOIN mata_pelajaran m ON j.id_mapel = m.id 
                  WHERE j.status = 'Aktif' 
                  AND (j.id_rombel IS NULL OR j.id_rombel = 0 OR j.id_rombel = :id_rombel) 
                  ORDER BY j.waktu_mulai ASC";
        $this->db->query($query);
        $this->db->bind('id_rombel', $id_rombel);
        return $this->db->resultSet();
    }

    public function getSoalUjian($id_jadwal)
    {
        $query = "SELECT bs.* 
                  FROM cbt_ujian_soal us
                  JOIN cbt_bank_soal bs ON us.id_soal = bs.id_soal
                  WHERE us.id_jadwal = :id_jadwal";
        $this->db->query($query);
        $this->db->bind('id_jadwal', $id_jadwal);
        return $this->db->resultSet();
    }

    public function getPesertaData($id_jadwal, $id_siswa)
    {
        $this->db->query("SELECT * FROM cbt_peserta WHERE id_jadwal = :id_jadwal AND id_siswa = :id_siswa");
        $this->db->bind('id_jadwal', $id_jadwal);
        $this->db->bind('id_siswa', $id_siswa);
        return $this->db->single();
    }

    public function daftarUjian($id_jadwal, $id_siswa)
    {
        $this->db->query("INSERT INTO cbt_peserta (id_jadwal, id_siswa, status_ujian) VALUES (:id_jadwal, :id_siswa, '0')");
        $this->db->bind('id_jadwal', $id_jadwal);
        $this->db->bind('id_siswa', $id_siswa);
        $this->db->execute();
        return $this->db->rowCount();
    }

    public function kunciPeserta($id_peserta, $alasan)
    {
        $this->db->query("UPDATE cbt_peserta SET status_ujian = '2', alasan_terkunci = :alasan WHERE id_peserta = :id_peserta");
        $this->db->bind('alasan', $alasan);
        $this->db->bind('id_peserta', $id_peserta);
        $this->db->execute();
        return $this->db->rowCount();
    }
}
