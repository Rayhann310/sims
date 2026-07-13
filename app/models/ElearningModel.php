<?php

class ElearningModel {
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function getGuruIdByUserId($user_id)
    {
        $this->db->query("SELECT id FROM guru WHERE user_id = :user_id");
        $this->db->bind('user_id', $user_id);
        $result = $this->db->single();
        return $result ? $result['id'] : null;
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

    public function getDiskusiByJadwal($jadwal_id)
    {
        $this->db->query("
            SELECT d.*, u.nama_lengkap, u.role, 
            CASE 
                WHEN u.role = 'guru' THEN g.foto
                WHEN u.role = 'siswa' THEN s.foto
                ELSE NULL
            END as foto
            FROM elearning_diskusi d 
            JOIN users u ON d.user_id = u.id 
            LEFT JOIN guru g ON u.id = g.user_id AND u.role = 'guru'
            LEFT JOIN siswa s ON u.id = s.user_id AND u.role = 'siswa'
            WHERE d.jadwal_id = :jadwal_id 
            ORDER BY d.created_at ASC
        ");
        $this->db->bind('jadwal_id', $jadwal_id);
        return $this->db->resultSet();
    }

    public function tambahDiskusi($data)
    {
        $this->db->query("INSERT INTO elearning_diskusi (jadwal_id, user_id, pesan) VALUES (:jadwal_id, :user_id, :pesan)");
        $this->db->bind('jadwal_id', $data['jadwal_id']);
        $this->db->bind('user_id', $data['user_id']);
        $this->db->bind('pesan', $data['pesan']);
        $this->db->execute();
        return $this->db->rowCount();
    }

    public function getAbsensiByJadwalTanggal($jadwal_id, $tanggal)
    {
        // Get all students in the rombel associated with this jadwal
        $this->db->query("
            SELECT s.id as siswa_id, s.nisn, u.nama_lengkap, a.status_kehadiran
            FROM jadwal_pelajaran jp
            JOIN anggota_rombel ar ON jp.rombel_id = ar.rombel_id
            JOIN siswa s ON ar.siswa_id = s.id
            JOIN users u ON s.user_id = u.id
            LEFT JOIN elearning_absensi a ON jp.id = a.jadwal_id AND s.id = a.siswa_id AND a.tanggal = :tanggal
            WHERE jp.id = :jadwal_id
            ORDER BY u.nama_lengkap ASC
        ");
        $this->db->bind('jadwal_id', $jadwal_id);
        $this->db->bind('tanggal', $tanggal);
        return $this->db->resultSet();
    }

    public function simpanAbsensi($jadwal_id, $tanggal, $siswa_id, $status_kehadiran)
    {
        $this->db->query("
            INSERT INTO elearning_absensi (jadwal_id, siswa_id, tanggal, status_kehadiran) 
            VALUES (:jadwal_id, :siswa_id, :tanggal, :status_kehadiran)
            ON DUPLICATE KEY UPDATE status_kehadiran = VALUES(status_kehadiran)
        ");
        $this->db->bind('jadwal_id', $jadwal_id);
        $this->db->bind('siswa_id', $siswa_id);
        $this->db->bind('tanggal', $tanggal);
        $this->db->bind('status_kehadiran', $status_kehadiran);
        $this->db->execute();
        return $this->db->rowCount();
    }
}
