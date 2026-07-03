<?php

class JadwalUjianModel {
    private $table = 'cbt_jadwal';
    private $db;

    public function __construct()
    {
        $this->db = new Database();
        $this->selfHealing();
    }

    private function selfHealing()
    {
        try {
            $this->db->query("CREATE TABLE IF NOT EXISTS cbt_jadwal (
                id_jadwal INT AUTO_INCREMENT PRIMARY KEY,
                nama_ujian VARCHAR(100) NOT NULL,
                id_mapel INT NOT NULL,
                waktu_mulai DATETIME NOT NULL,
                waktu_selesai DATETIME NOT NULL,
                durasi_menit INT NOT NULL,
                id_guru_pengawas INT NOT NULL,
                id_rombel INT NULL,
                token_aktif VARCHAR(10) NULL,
                token_last_update TIMESTAMP NULL,
                status ENUM('Draft', 'Aktif', 'Selesai') DEFAULT 'Draft',
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )");
            $this->db->execute();
            
            // Alter table if id_rombel doesn't exist
            try {
                $this->db->query("ALTER TABLE cbt_jadwal ADD COLUMN id_rombel INT NULL AFTER id_guru_pengawas");
                $this->db->execute();
            } catch (\Throwable $e) {}
            
            // Pivot table untuk relasi Jadwal Ujian dan Soal yang dipilih
            $this->db->query("CREATE TABLE IF NOT EXISTS cbt_ujian_soal (
                id_jadwal INT NOT NULL,
                id_soal INT NOT NULL,
                PRIMARY KEY (id_jadwal, id_soal)
            )");
            $this->db->execute();
        } catch (\Throwable $e) {}
    }

    public function getAllJadwal()
    {
        // Join dengan tabel mapel (sementara id_mapel = 1) dan guru pengawas (lewat users)
        $query = "SELECT j.*, u.nama_lengkap AS nama_pengawas, m.nama_mapel, r.nama_rombel 
                  FROM " . $this->table . " j 
                  LEFT JOIN guru g ON j.id_guru_pengawas = g.id 
                  LEFT JOIN users u ON g.user_id = u.id
                  LEFT JOIN mata_pelajaran m ON j.id_mapel = m.id
                  LEFT JOIN rombel r ON j.id_rombel = r.id
                  ORDER BY j.waktu_mulai DESC";
        $this->db->query($query);
        return $this->db->resultSet();
    }

    public function tambahDataJadwal($data)
    {
        $query = "INSERT INTO " . $this->table . "
                    (nama_ujian, id_mapel, waktu_mulai, waktu_selesai, durasi_menit, id_guru_pengawas, id_rombel, status)
                  VALUES
                    (:nama_ujian, :id_mapel, :waktu_mulai, :waktu_selesai, :durasi_menit, :id_guru_pengawas, :id_rombel, :status)";
        
        $this->db->query($query);
        
        $this->db->bind('nama_ujian', $data['nama_ujian']);
        $this->db->bind('id_mapel', $data['id_mapel']); 
        $this->db->bind('waktu_mulai', $data['waktu_mulai']);
        $this->db->bind('waktu_selesai', $data['waktu_selesai']);
        $this->db->bind('durasi_menit', $data['durasi_menit']);
        $this->db->bind('id_guru_pengawas', $data['id_guru_pengawas']);
        $this->db->bind('id_rombel', $data['id_rombel'] ?: null);
        $this->db->bind('status', $data['status']);

        $this->db->execute();
        return $this->db->rowCount();
    }

    public function editDataJadwal($data)
    {
        $query = "UPDATE " . $this->table . " SET 
                    nama_ujian = :nama_ujian,
                    id_mapel = :id_mapel,
                    waktu_mulai = :waktu_mulai,
                    waktu_selesai = :waktu_selesai,
                    durasi_menit = :durasi_menit,
                    id_guru_pengawas = :id_guru_pengawas,
                    id_rombel = :id_rombel,
                    status = :status
                  WHERE id_jadwal = :id_jadwal";
                  
        $this->db->query($query);
        
        $this->db->bind('nama_ujian', $data['nama_ujian']);
        $this->db->bind('id_mapel', $data['id_mapel']); 
        $this->db->bind('waktu_mulai', $data['waktu_mulai']);
        $this->db->bind('waktu_selesai', $data['waktu_selesai']);
        $this->db->bind('durasi_menit', $data['durasi_menit']);
        $this->db->bind('id_guru_pengawas', $data['id_guru_pengawas']);
        $this->db->bind('id_rombel', $data['id_rombel'] ?: null);
        $this->db->bind('status', $data['status']);
        $this->db->bind('id_jadwal', $data['id_jadwal']);

        $this->db->execute();
        return $this->db->rowCount();
    }

    public function hapusDataJadwal($id)
    {
        $query = "DELETE FROM " . $this->table . " WHERE id_jadwal = :id_jadwal";
        $this->db->query($query);
        $this->db->bind('id_jadwal', $id);
        $this->db->execute();
        return $this->db->rowCount();
    }

    public function getAllGuru()
    {
        $this->db->query("SELECT guru.id, users.nama_lengkap FROM guru JOIN users ON guru.user_id = users.id ORDER BY users.nama_lengkap ASC");
        return $this->db->resultSet();
    }
    
    public function getAllMapel()
    {
        $this->db->query("SELECT * FROM mata_pelajaran ORDER BY nama_mapel ASC");
        return $this->db->resultSet();
    }
    
    public function getAllRombel()
    {
        $this->db->query("SELECT * FROM rombel ORDER BY nama_rombel ASC");
        return $this->db->resultSet();
    }
    
    public function getJadwalById($id)
    {
        $this->db->query("SELECT j.*, m.nama_mapel FROM " . $this->table . " j LEFT JOIN mata_pelajaran m ON j.id_mapel = m.id WHERE j.id_jadwal = :id");
        $this->db->bind('id', $id);
        return $this->db->single();
    }
    
    public function getSoalByMapel($id_mapel)
    {
        $this->db->query("SELECT s.*, (SELECT nama_lengkap FROM users u JOIN guru g ON g.user_id = u.id WHERE g.id = s.id_guru LIMIT 1) as nama_pembuat FROM cbt_bank_soal s WHERE s.id_mapel = :id_mapel ORDER BY s.created_at DESC");
        $this->db->bind('id_mapel', $id_mapel);
        return $this->db->resultSet();
    }
    
    public function getSoalTerpilih($id_jadwal)
    {
        $this->db->query("SELECT id_soal FROM cbt_ujian_soal WHERE id_jadwal = :id_jadwal");
        $this->db->bind('id_jadwal', $id_jadwal);
        $result = $this->db->resultSet();
        $terpilih = [];
        foreach($result as $row) {
            $terpilih[] = $row['id_soal'];
        }
        return $terpilih;
    }
    
    public function simpanSoalUjian($id_jadwal, $soal_ids)
    {
        try {
            $this->db->query("START TRANSACTION");
            $this->db->execute();
            
            // Hapus yang lama
            $this->db->query("DELETE FROM cbt_ujian_soal WHERE id_jadwal = :id_jadwal");
            $this->db->bind('id_jadwal', $id_jadwal);
            $this->db->execute();
            
            // Insert yang baru
            if(!empty($soal_ids)) {
                foreach($soal_ids as $id_soal) {
                    $this->db->query("INSERT INTO cbt_ujian_soal (id_jadwal, id_soal) VALUES (:id_jadwal, :id_soal)");
                    $this->db->bind('id_jadwal', $id_jadwal);
                    $this->db->bind('id_soal', $id_soal);
                    $this->db->execute();
                }
            }
            
            $this->db->query("COMMIT");
            $this->db->execute();
            return true;
        } catch (\Exception $e) {
            $this->db->query("ROLLBACK");
            $this->db->execute();
            return false;
        }
    }
}
