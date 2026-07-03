<?php

class ProctorModel {
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function getJadwalDiawasi($id_guru)
    {
        $this->db->query("SELECT * FROM cbt_jadwal WHERE id_guru_pengawas = :id_guru AND status = 'Aktif' ORDER BY waktu_mulai DESC");
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
}
