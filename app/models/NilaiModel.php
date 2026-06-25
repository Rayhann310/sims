<?php

class NilaiModel {
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
                   k.nama_kelas,
                   t.nama_tahun, t.semester
            FROM jadwal_pelajaran jp
            JOIN mata_pelajaran m ON jp.mapel_id = m.id
            JOIN rombel r ON jp.rombel_id = r.id
            JOIN kelas k ON r.kelas_id = k.id
            JOIN tahun_akademik t ON r.tahun_akademik_id = t.id
            WHERE jp.guru_id = :guru_id
            ORDER BY t.id DESC, r.nama_rombel ASC, m.nama_mapel ASC
        ");
        $this->db->bind('guru_id', $guru_id);
        return $this->db->resultSet();
    }

    public function getSiswaByJadwal($jadwal_id)
    {
        // Ambil siswa berdasarkan rombel dari jadwal tersebut
        $this->db->query("
            SELECT s.*, ar.id as anggota_id 
            FROM siswa s
            JOIN anggota_rombel ar ON s.id = ar.siswa_id
            JOIN jadwal_pelajaran jp ON ar.rombel_id = jp.rombel_id
            WHERE jp.id = :jadwal_id
            ORDER BY s.nama_lengkap ASC
        ");
        $this->db->bind('jadwal_id', $jadwal_id);
        return $this->db->resultSet();
    }

    public function getPresensiByTanggal($jadwal_id, $tanggal)
    {
        $this->db->query("SELECT * FROM presensi_siswa WHERE jadwal_id = :jadwal_id AND tanggal = :tanggal");
        $this->db->bind('jadwal_id', $jadwal_id);
        $this->db->bind('tanggal', $tanggal);
        return $this->db->resultSet();
    }

    public function simpanPresensiMassal($jadwal_id, $tanggal, $data_presensi)
    {
        // $data_presensi is array: [siswa_id => status]
        $inserted = 0;
        
        // Hapus presensi lama di tanggal tersebut (replace all)
        $this->db->query("DELETE FROM presensi_siswa WHERE jadwal_id = :jadwal_id AND tanggal = :tanggal");
        $this->db->bind('jadwal_id', $jadwal_id);
        $this->db->bind('tanggal', $tanggal);
        $this->db->execute();

        foreach($data_presensi as $siswa_id => $status) {
            $this->db->query("INSERT INTO presensi_siswa (jadwal_id, tanggal, siswa_id, status) VALUES (:jadwal_id, :tanggal, :siswa_id, :status)");
            $this->db->bind('jadwal_id', $jadwal_id);
            $this->db->bind('tanggal', $tanggal);
            $this->db->bind('siswa_id', $siswa_id);
            $this->db->bind('status', $status);
            $this->db->execute();
            $inserted++;
        }
        return $inserted;
    }

    public function getNilaiByJenis($jadwal_id, $jenis_nilai)
    {
        $this->db->query("SELECT * FROM nilai_siswa WHERE jadwal_id = :jadwal_id AND jenis_nilai = :jenis_nilai");
        $this->db->bind('jadwal_id', $jadwal_id);
        $this->db->bind('jenis_nilai', $jenis_nilai);
        
        $results = $this->db->resultSet();
        $formatted = [];
        foreach($results as $r) {
            $formatted[$r['siswa_id']] = $r['nilai'];
        }
        return $formatted;
    }

    public function simpanNilaiMassal($jadwal_id, $jenis_nilai, $data_nilai)
    {
        // $data_nilai is array: [siswa_id => nilai]
        $inserted = 0;
        
        // Hapus nilai lama di jenis tersebut (replace all)
        $this->db->query("DELETE FROM nilai_siswa WHERE jadwal_id = :jadwal_id AND jenis_nilai = :jenis_nilai");
        $this->db->bind('jadwal_id', $jadwal_id);
        $this->db->bind('jenis_nilai', $jenis_nilai);
        $this->db->execute();

        // Get mapel name
        $this->db->query("SELECT m.nama_mapel FROM jadwal_pelajaran jp JOIN mata_pelajaran m ON jp.mapel_id = m.id WHERE jp.id = :jadwal_id");
        $this->db->bind('jadwal_id', $jadwal_id);
        $mapel = $this->db->single();
        $nama_mapel = $mapel ? $mapel['nama_mapel'] : 'Mata Pelajaran';

        require_once 'NotifikasiModel.php';
        $notifModel = new NotifikasiModel();

        foreach($data_nilai as $siswa_id => $nilai) {
            $this->db->query("INSERT INTO nilai_siswa (jadwal_id, siswa_id, jenis_nilai, nilai) VALUES (:jadwal_id, :siswa_id, :jenis_nilai, :nilai)");
            $this->db->bind('jadwal_id', $jadwal_id);
            $this->db->bind('siswa_id', $siswa_id);
            $this->db->bind('jenis_nilai', $jenis_nilai);
            $this->db->bind('nilai', $nilai);
            $this->db->execute();
            $inserted++;
            
            // Notification
            $this->db->query("SELECT user_id FROM siswa WHERE id = :siswa_id");
            $this->db->bind('siswa_id', $siswa_id);
            $siswa = $this->db->single();
            if ($siswa && $siswa['user_id']) {
                $notifModel->createNotifikasi(
                    $siswa['user_id'], 
                    'nilai', 
                    "Nilai {$jenis_nilai} untuk {$nama_mapel} telah diperbarui: {$nilai}", 
                    BASEURL . '/nilai'
                );
            }
        }
        return $inserted;
    }
}
