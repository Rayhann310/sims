<?php

class AlumniModel {
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function getAllAlumni()
    {
        $this->db->query("SELECT siswa.*, users.username, users.nama_lengkap 
                          FROM siswa 
                          JOIN users ON siswa.user_id = users.id 
                          WHERE siswa.status = 'Alumni'
                          ORDER BY users.nama_lengkap ASC");
        return $this->db->resultSet();
    }
}
