<?php

class ElearningModel {
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function getJadwalByGuru($guru_id)
    {
        $this->db->query("
            SELECT jp.*, 
                   m.nama_mapel, m.kode_mapel, 
                   r.nama_rombel,
                   k.nama_kelas
            FROM jadwal_pelajaran jp
            JOIN mata_pelajaran m ON jp.mapel_id = m.id
            JOIN rombel r ON jp.rombel_id = r.id
            JOIN kelas k ON r.kelas_id = k.id
            WHERE jp.guru_id = :guru_id
            ORDER BY r.nama_rombel ASC, m.nama_mapel ASC
        ");
        $this->db->bind('guru_id', $guru_id);
        return $this->db->resultSet();
    }

    public function getJadwalBySiswa($siswa_id)
    {
        $this->db->query("
            SELECT jp.*, 
                   m.nama_mapel, m.kode_mapel, 
                   g.nama_lengkap as nama_guru
            FROM jadwal_pelajaran jp
            JOIN mata_pelajaran m ON jp.mapel_id = m.id
            JOIN guru g ON jp.guru_id = g.id
            JOIN anggota_rombel ar ON jp.rombel_id = ar.rombel_id
            WHERE ar.siswa_id = :siswa_id
            ORDER BY m.nama_mapel ASC
        ");
        $this->db->bind('siswa_id', $siswa_id);
        return $this->db->resultSet();
    }

    public function getMateriByJadwal($jadwal_id)
    {
        $this->db->query("SELECT * FROM elearning_materi WHERE jadwal_id = :jadwal_id ORDER BY created_at DESC");
        $this->db->bind('jadwal_id', $jadwal_id);
        return $this->db->resultSet();
    }

    public function getTugasByJadwal($jadwal_id)
    {
        $this->db->query("SELECT * FROM elearning_tugas WHERE jadwal_id = :jadwal_id ORDER BY tenggat_waktu ASC");
        $this->db->bind('jadwal_id', $jadwal_id);
        return $this->db->resultSet();
    }

    public function tambahMateri($data)
    {
        $this->db->query("INSERT INTO elearning_materi (jadwal_id, judul, deskripsi, file_path) VALUES (:jadwal_id, :judul, :deskripsi, :file_path)");
        $this->db->bind('jadwal_id', $data['jadwal_id']);
        $this->db->bind('judul', $data['judul']);
        $this->db->bind('deskripsi', $data['deskripsi']);
        $this->db->bind('file_path', $data['file_path']);
        $this->db->execute();
        return $this->db->rowCount();
    }
    
    public function tambahTugas($data)
    {
        $this->db->query("INSERT INTO elearning_tugas (jadwal_id, judul, deskripsi, tenggat_waktu) VALUES (:jadwal_id, :judul, :deskripsi, :tenggat_waktu)");
        $this->db->bind('jadwal_id', $data['jadwal_id']);
        $this->db->bind('judul', $data['judul']);
        $this->db->bind('deskripsi', $data['deskripsi']);
        $this->db->bind('tenggat_waktu', $data['tenggat_waktu']);
        $this->db->execute();
        return $this->db->rowCount();
    }
}
