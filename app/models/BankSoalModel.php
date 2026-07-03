<?php

class BankSoalModel {
    private $table = 'cbt_bank_soal';
    private $db;

    public function __construct()
    {
        $this->db = new Database();
        $this->selfHealing();
    }

    private function selfHealing()
    {
        try {
            // 1. Table Bank Soal
            $this->db->query("CREATE TABLE IF NOT EXISTS cbt_bank_soal (
                id_soal INT AUTO_INCREMENT PRIMARY KEY,
                id_mapel INT NOT NULL,
                id_guru INT NOT NULL,
                tipe_soal VARCHAR(20) NOT NULL DEFAULT 'PG',
                pertanyaan TEXT NOT NULL,
                file_media VARCHAR(255) NULL,
                opsi_a TEXT NULL,
                opsi_b TEXT NULL,
                opsi_c TEXT NULL,
                opsi_d TEXT NULL,
                opsi_e TEXT NULL,
                kunci_jawaban VARCHAR(255) NOT NULL,
                tingkat_kesulitan ENUM('Mudah', 'Sedang', 'Sulit') DEFAULT 'Sedang',
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )");
            $this->db->execute();
        } catch (\Throwable $e) {}
    }

    public function getAllSoal()
    {
        $role = $_SESSION['user']['role'] ?? '';
        $user_id = $_SESSION['user']['id'] ?? 0;
        
        $query = "SELECT s.*, m.nama_mapel 
                  FROM " . $this->table . " s 
                  LEFT JOIN mata_pelajaran m ON s.id_mapel = m.id ";
                  
        if ($role == 'guru') {
            $query .= " WHERE s.id_guru = :id_guru ";
            $query .= " ORDER BY s.created_at DESC";
            $this->db->query($query);
            $this->db->bind('id_guru', $user_id);
        } else {
            $query .= " ORDER BY s.created_at DESC";
            $this->db->query($query);
        }
        
        return $this->db->resultSet();
    }
    
    public function getAllMapel()
    {
        $this->db->query("SELECT * FROM mata_pelajaran ORDER BY nama_mapel ASC");
        return $this->db->resultSet();
    }

    public function getSoalById($id)
    {
        $this->db->query("SELECT * FROM " . $this->table . " WHERE id_soal = :id_soal");
        $this->db->bind('id_soal', $id);
        return $this->db->single();
    }

    public function tambahDataSoal($data)
    {
        $query = "INSERT INTO " . $this->table . "
                    (id_mapel, id_guru, tipe_soal, pertanyaan, opsi_a, opsi_b, opsi_c, opsi_d, opsi_e, kunci_jawaban, tingkat_kesulitan)
                  VALUES
                    (:id_mapel, :id_guru, :tipe_soal, :pertanyaan, :opsi_a, :opsi_b, :opsi_c, :opsi_d, :opsi_e, :kunci_jawaban, :tingkat_kesulitan)";
        
        $this->db->query($query);
        $this->db->bind('id_mapel', $data['id_mapel']); 
        $this->db->bind('id_guru', $_SESSION['user']['id'] ?? 1);
        
        $this->db->bind('tipe_soal', $data['tipe_soal']);
        $this->db->bind('pertanyaan', $data['pertanyaan']);
        $this->db->bind('opsi_a', $data['opsi_a'] ?? '');
        $this->db->bind('opsi_b', $data['opsi_b'] ?? '');
        $this->db->bind('opsi_c', $data['opsi_c'] ?? '');
        $this->db->bind('opsi_d', $data['opsi_d'] ?? '');
        $this->db->bind('opsi_e', $data['opsi_e'] ?? '');
        $this->db->bind('kunci_jawaban', $data['kunci_jawaban'] ?? '');
        $this->db->bind('tingkat_kesulitan', $data['tingkat_kesulitan']);

        $this->db->execute();
        return $this->db->rowCount();
    }

    public function hapusDataSoal($id)
    {
        $query = "DELETE FROM " . $this->table . " WHERE id_soal = :id_soal";
        $this->db->query($query);
        $this->db->bind('id_soal', $id);
        $this->db->execute();
        return $this->db->rowCount();
    }
}
