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
        $this->db->bind('id_guru', $data['id_guru'] ?? 1);
        $this->db->bind('tipe_soal', $data['tipe_soal']);
        $this->db->bind('pertanyaan', $data['pertanyaan']);
        $this->db->bind('opsi_a', $data['opsi_a'] ?? '');
        $this->db->bind('opsi_b', $data['opsi_b'] ?? '');
        $this->db->bind('opsi_c', $data['opsi_c'] ?? '');
        $this->db->bind('opsi_d', $data['opsi_d'] ?? '');
        $this->db->bind('opsi_e', $data['opsi_e'] ?? '');
        $this->db->bind('kunci_jawaban', $data['kunci_jawaban'] ?? '');
        $this->db->bind('tingkat_kesulitan', $data['tingkat_kesulitan'] ?? 'Sedang');

        $this->db->execute();
        return $this->db->rowCount();
    }

    public function tambahBanyakSoal($id_mapel, $id_guru, $soal_array)
    {
        if (empty($soal_array)) return 0;
        
        $inserted = 0;
        $query = "INSERT INTO " . $this->table . "
                    (id_mapel, id_guru, tipe_soal, pertanyaan, opsi_a, opsi_b, opsi_c, opsi_d, opsi_e, kunci_jawaban, tingkat_kesulitan)
                  VALUES
                    (:id_mapel, :id_guru, :tipe_soal, :pertanyaan, :opsi_a, :opsi_b, :opsi_c, :opsi_d, :opsi_e, :kunci_jawaban, :tingkat_kesulitan)";
                    
        foreach ($soal_array as $soal) {
            if (empty(trim(strip_tags($soal['pertanyaan'])))) continue; // skip if question is totally empty
            
            $this->db->query($query);
            $this->db->bind('id_mapel', $id_mapel);
            $this->db->bind('id_guru', $id_guru);
            $this->db->bind('tipe_soal', $soal['tipe_soal'] ?? 'PG');
            $this->db->bind('pertanyaan', $soal['pertanyaan']);
            $this->db->bind('opsi_a', $soal['opsi_a'] ?? '');
            $this->db->bind('opsi_b', $soal['opsi_b'] ?? '');
            $this->db->bind('opsi_c', $soal['opsi_c'] ?? '');
            $this->db->bind('opsi_d', $soal['opsi_d'] ?? '');
            $this->db->bind('opsi_e', $soal['opsi_e'] ?? '');
            
            // Format kunci jawaban if it's an array (from checkboxes)
            $kunci = $soal['kunci_jawaban'] ?? '';
            if (is_array($kunci)) {
                $kunci = implode(',', $kunci);
            }
            $this->db->bind('kunci_jawaban', $kunci);
            $this->db->bind('tingkat_kesulitan', $soal['tingkat_kesulitan'] ?? 'Sedang');
            $this->db->execute();
            $inserted++;
        }
        return $inserted;
    }

    public function importSoalMassal($id_mapel, $id_guru, $dataSoal)
    {
        $inserted = 0;
        $query = "INSERT INTO " . $this->table . "
                    (id_mapel, id_guru, tipe_soal, pertanyaan, opsi_a, opsi_b, opsi_c, opsi_d, opsi_e, kunci_jawaban, tingkat_kesulitan)
                  VALUES
                    (:id_mapel, :id_guru, :tipe_soal, :pertanyaan, :opsi_a, :opsi_b, :opsi_c, :opsi_d, :opsi_e, :kunci_jawaban, :tingkat_kesulitan)";

        foreach ($dataSoal as $soal) {
            $this->db->query($query);
            $this->db->bind('id_mapel', $id_mapel);
            $this->db->bind('id_guru', $id_guru);
            $this->db->bind('tipe_soal', $soal['tipe_soal'] ?? 'PG');
            $this->db->bind('pertanyaan', $soal['pertanyaan']);
            $this->db->bind('opsi_a', $soal['opsi_a'] ?? '');
            $this->db->bind('opsi_b', $soal['opsi_b'] ?? '');
            $this->db->bind('opsi_c', $soal['opsi_c'] ?? '');
            $this->db->bind('opsi_d', $soal['opsi_d'] ?? '');
            $this->db->bind('opsi_e', $soal['opsi_e'] ?? '');
            $this->db->bind('kunci_jawaban', $soal['kunci_jawaban'] ?? '');
            $this->db->bind('tingkat_kesulitan', $soal['tingkat_kesulitan'] ?? 'Sedang');
            $this->db->execute();
            $inserted++;
        }
        return $inserted;
    }

    public function hapusDataSoal($id)
    {
        $query = "DELETE FROM " . $this->table . " WHERE id_soal = :id_soal";
        $this->db->query($query);
        $this->db->bind('id_soal', $id);
        $this->db->execute();
        return $this->db->rowCount();
    }

    public function hapusMassalSoal($ids)
    {
        if (empty($ids)) return 0;
        
        $placeholders = str_repeat('?,', count($ids) - 1) . '?';
        $query = "DELETE FROM " . $this->table . " WHERE id_soal IN ($placeholders)";
        
        // PDO direct execute is required for dynamic IN array
        $stmt = $this->db->dbh->prepare($query);
        $stmt->execute($ids);
        return $stmt->rowCount();
    }

    public function getSoalByIds($ids)
    {
        if (empty($ids)) return [];
        
        $placeholders = str_repeat('?,', count($ids) - 1) . '?';
        $query = "SELECT bs.*, m.nama_mapel FROM " . $this->table . " bs 
                  LEFT JOIN mata_pelajaran m ON bs.id_mapel = m.id 
                  WHERE bs.id_soal IN ($placeholders)";
                  
        $stmt = $this->db->dbh->prepare($query);
        $stmt->execute($ids);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateDataSoal($data)
    {
        $query = "UPDATE " . $this->table . " SET 
                    id_mapel = :id_mapel,
                    tipe_soal = :tipe_soal,
                    pertanyaan = :pertanyaan,
                    opsi_a = :opsi_a,
                    opsi_b = :opsi_b,
                    opsi_c = :opsi_c,
                    opsi_d = :opsi_d,
                    opsi_e = :opsi_e,
                    kunci_jawaban = :kunci_jawaban,
                    tingkat_kesulitan = :tingkat_kesulitan
                  WHERE id_soal = :id_soal";
                  
        $this->db->query($query);
        $this->db->bind('id_soal', $data['id_soal']);
        $this->db->bind('id_mapel', $data['id_mapel']);
        $this->db->bind('tipe_soal', $data['tipe_soal']);
        $this->db->bind('pertanyaan', $data['pertanyaan']);
        $this->db->bind('opsi_a', $data['opsi_a'] ?? '');
        $this->db->bind('opsi_b', $data['opsi_b'] ?? '');
        $this->db->bind('opsi_c', $data['opsi_c'] ?? '');
        $this->db->bind('opsi_d', $data['opsi_d'] ?? '');
        $this->db->bind('opsi_e', $data['opsi_e'] ?? '');
        
        $kunci = $data['kunci_jawaban'] ?? '';
        if (is_array($kunci)) {
            $kunci = implode(',', $kunci);
        }
        $this->db->bind('kunci_jawaban', $kunci);
        $this->db->bind('tingkat_kesulitan', $data['tingkat_kesulitan'] ?? 'Sedang');

        $this->db->execute();
        return $this->db->rowCount();
    }
}
