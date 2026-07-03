<?php

class SetorSoalModel {
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function getSoalByMapelAndGuru($id_mapel, $id_guru)
    {
        $this->db->query("SELECT s.*, 
                          (SELECT nama_lengkap FROM users u JOIN guru g ON g.user_id = u.id WHERE g.id = s.id_guru LIMIT 1) as nama_pembuat 
                          FROM cbt_bank_soal s 
                          WHERE s.id_mapel = :id_mapel AND s.id_guru = :id_guru 
                          ORDER BY s.created_at DESC");
        $this->db->bind('id_mapel', $id_mapel);
        $this->db->bind('id_guru', $id_guru);
        return $this->db->resultSet();
    }
}
