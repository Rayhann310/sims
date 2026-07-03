<?php

class BankSoalModel {
    private $table = 'cbt_bank_soal';
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function getAllSoal()
    {
        $this->db->query("SELECT * FROM " . $this->table . " ORDER BY created_at DESC");
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
        // Sementara di hardcode untuk id mapel dan guru agar cepat
        $this->db->bind('id_mapel', 1); 
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
