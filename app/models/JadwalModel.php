<?php

class JadwalModel {
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function getJadwalByRombel($rombel_id)
    {
        $this->db->query("
            SELECT jp.*, 
                   m.nama_mapel, m.kode_mapel, 
                   g.nama_lengkap as nama_guru,
                   r.nama_rombel,
                   k.nama_kelas,
                   t.nama_tahun, t.semester
            FROM jadwal_pelajaran jp
            JOIN mata_pelajaran m ON jp.mapel_id = m.id
            JOIN guru g ON jp.guru_id = g.id
            JOIN rombel r ON jp.rombel_id = r.id
            JOIN kelas k ON r.kelas_id = k.id
            JOIN tahun_akademik t ON r.tahun_akademik_id = t.id
            WHERE jp.rombel_id = :rombel_id
            ORDER BY FIELD(jp.hari, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'), jp.jam_mulai ASC
        ");
        $this->db->bind('rombel_id', $rombel_id);
        return $this->db->resultSet();
    }

    public function importJadwalMassal($data)
    {
        $inserted = 0;
        foreach($data as $row) {
            $this->db->query("INSERT INTO jadwal_pelajaran (rombel_id, mapel_id, guru_id, hari, jam_mulai, jam_selesai) VALUES (:rombel_id, :mapel_id, :guru_id, :hari, :jam_mulai, :jam_selesai)");
            $this->db->bind('rombel_id', $row['rombel_id']);
            $this->db->bind('mapel_id', $row['mapel_id']);
            $this->db->bind('guru_id', $row['guru_id']);
            $this->db->bind('hari', $row['hari']);
            $this->db->bind('jam_mulai', $row['jam_mulai']);
            $this->db->bind('jam_selesai', $row['jam_selesai']);
            $this->db->execute();
            $inserted += $this->db->rowCount();
        }
        return $inserted;
    }
}
