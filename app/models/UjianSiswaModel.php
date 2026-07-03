<?php

class UjianSiswaModel {
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function getJadwalAktif()
    {
        // Hanya ambil jadwal yang statusnya 'Aktif'
        $query = "SELECT * FROM cbt_jadwal WHERE status = 'Aktif' ORDER BY waktu_mulai ASC";
        $this->db->query($query);
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
