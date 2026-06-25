<?php

class JabatanModel {
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function getAll()
    {
        $this->db->query("SELECT * FROM jabatan ORDER BY nama_jabatan ASC");
        return $this->db->resultSet();
    }

    public function getById($id)
    {
        $this->db->query("SELECT * FROM jabatan WHERE id = :id");
        $this->db->bind('id', $id);
        return $this->db->single();
    }

    public function tambah($data)
    {
        $this->db->query("INSERT INTO jabatan (nama_jabatan, deskripsi) VALUES (:nama_jabatan, :deskripsi)");
        $this->db->bind('nama_jabatan', htmlspecialchars(trim($data['nama_jabatan'])));
        $this->db->bind('deskripsi', htmlspecialchars(trim($data['deskripsi'] ?? '')));
        $this->db->execute();
        return $this->db->rowCount();
    }

    public function ubah($data)
    {
        $this->db->query("UPDATE jabatan SET nama_jabatan = :nama_jabatan, deskripsi = :deskripsi WHERE id = :id");
        $this->db->bind('nama_jabatan', htmlspecialchars(trim($data['nama_jabatan'])));
        $this->db->bind('deskripsi', htmlspecialchars(trim($data['deskripsi'] ?? '')));
        $this->db->bind('id', $data['id']);
        $this->db->execute();
        return $this->db->rowCount();
    }

    public function hapus($id)
    {
        $this->db->query("DELETE FROM jabatan WHERE id = :id");
        $this->db->bind('id', $id);
        $this->db->execute();
        return $this->db->rowCount();
    }

    public function countGuruByJabatan($jabatan_id)
    {
        $this->db->query("SELECT COUNT(id) as total FROM guru WHERE jabatan_id = :jabatan_id");
        $this->db->bind('jabatan_id', $jabatan_id);
        $row = $this->db->single();
        return $row['total'] ?? 0;
    }
}
