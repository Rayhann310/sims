<?php

class RombelModel {
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function getAllRombel()
    {
        // Asumsi wali_kelas_id merujuk ke tabel guru, dan guru memiliki user_id
        $this->db->query("
            SELECT r.*, 
                   t.nama_tahun, t.semester, 
                   k.nama_kelas, 
                   u.nama_lengkap as nama_wali 
            FROM rombel r 
            JOIN tahun_akademik t ON r.tahun_akademik_id = t.id 
            JOIN kelas k ON r.kelas_id = k.id 
            LEFT JOIN guru g ON r.wali_kelas_id = g.id
            LEFT JOIN users u ON g.user_id = u.id
            ORDER BY t.status ASC, t.nama_tahun DESC, k.nama_kelas ASC, r.nama_rombel ASC
        ");
        return $this->db->resultSet();
    }

    public function getRombelById($id)
    {
        $this->db->query("
            SELECT r.*, 
                   t.nama_tahun, t.semester, 
                   k.nama_kelas, k.tingkat
            FROM rombel r 
            JOIN tahun_akademik t ON r.tahun_akademik_id = t.id 
            JOIN kelas k ON r.kelas_id = k.id 
            WHERE r.id = :id
        ");
        $this->db->bind('id', $id);
        return $this->db->single();
    }

    public function tambahRombel($data)
    {
        $this->db->query("INSERT INTO rombel (tahun_akademik_id, kelas_id, nama_rombel, wali_kelas_id) 
                          VALUES (:tahun_akademik_id, :kelas_id, :nama_rombel, :wali_kelas_id)");
        $this->db->bind('tahun_akademik_id', $data['tahun_akademik_id']);
        $this->db->bind('kelas_id', $data['kelas_id']);
        $this->db->bind('nama_rombel', $data['nama_rombel']);
        $this->db->bind('wali_kelas_id', !empty($data['wali_kelas_id']) ? $data['wali_kelas_id'] : null);
        $this->db->execute();
        return $this->db->rowCount();
    }

    public function hapusRombel($id)
    {
        $this->db->query("DELETE FROM rombel WHERE id = :id");
        $this->db->bind('id', $id);
        $this->db->execute();
        return $this->db->rowCount();
    }

    public function ubahRombel($data)
    {
        $this->db->query("UPDATE rombel SET 
                            tahun_akademik_id = :tahun_akademik_id, 
                            kelas_id = :kelas_id, 
                            nama_rombel = :nama_rombel, 
                            wali_kelas_id = :wali_kelas_id 
                          WHERE id = :id");
        $this->db->bind('tahun_akademik_id', $data['tahun_akademik_id']);
        $this->db->bind('kelas_id', $data['kelas_id']);
        $this->db->bind('nama_rombel', $data['nama_rombel']);
        $this->db->bind('wali_kelas_id', !empty($data['wali_kelas_id']) ? $data['wali_kelas_id'] : null);
        $this->db->bind('id', $data['id']);
        $this->db->execute();
        return $this->db->rowCount();
    }

    public function getAllGuru()
    {
        $this->db->query("SELECT g.id, u.nama_lengkap, g.nip FROM guru g JOIN users u ON g.user_id = u.id");
        return $this->db->resultSet();
    }

    // -- ANGGOTA ROMBEL --
    public function getAnggotaRombel($rombel_id)
    {
        $this->db->query("
            SELECT a.id as anggota_id, s.*, u.nama_lengkap as nama_siswa 
            FROM anggota_rombel a 
            JOIN siswa s ON a.siswa_id = s.id 
            JOIN users u ON s.user_id = u.id
            WHERE a.rombel_id = :rombel_id
            ORDER BY u.nama_lengkap ASC
        ");
        $this->db->bind('rombel_id', $rombel_id);
        return $this->db->resultSet();
    }

    public function getSiswaBelumAdaRombel($tahun_akademik_id)
    {
        // Cari siswa yang belum ada di anggota_rombel untuk tahun akademik tertentu
        $this->db->query("
            SELECT s.id, u.nama_lengkap, s.nisn, k.nama_kelas
            FROM siswa s
            JOIN users u ON s.user_id = u.id
            LEFT JOIN kelas k ON s.kelas_id = k.id
            WHERE s.id NOT IN (
                SELECT a.siswa_id FROM anggota_rombel a
                JOIN rombel r ON a.rombel_id = r.id
                WHERE r.tahun_akademik_id = :tahun_akademik_id
            )
            ORDER BY u.nama_lengkap ASC
        ");
        $this->db->bind('tahun_akademik_id', $tahun_akademik_id);
        return $this->db->resultSet();
    }

    public function tambahAnggotaMasal($rombel_id, $siswa_ids)
    {
        $inserted = 0;
        foreach($siswa_ids as $siswa_id) {
            $this->db->query("INSERT INTO anggota_rombel (rombel_id, siswa_id) VALUES (:rombel_id, :siswa_id)");
            $this->db->bind('rombel_id', $rombel_id);
            $this->db->bind('siswa_id', $siswa_id);
            $this->db->execute();
            $inserted += $this->db->rowCount();
        }
        return $inserted;
    }

    public function hapusAnggota($anggota_id)
    {
        $this->db->query("DELETE FROM anggota_rombel WHERE id = :id");
        $this->db->bind('id', $anggota_id);
        $this->db->execute();
        return $this->db->rowCount();
    }

    // -- NAIK KELAS --
    public function getRombelByTahunAkademik($tahun_akademik_id)
    {
        $this->db->query("
            SELECT r.*, k.nama_kelas
            FROM rombel r
            JOIN kelas k ON r.kelas_id = k.id
            WHERE r.tahun_akademik_id = :tahun_akademik_id
            ORDER BY k.nama_kelas ASC, r.nama_rombel ASC
        ");
        $this->db->bind('tahun_akademik_id', $tahun_akademik_id);
        return $this->db->resultSet();
    }

    public function prosesNaikKelas($dest_rombel_id, $siswa_ids)
    {
        $inserted = 0;
        foreach($siswa_ids as $siswa_id) {
            // Cek apakah siswa sudah ada di rombel tujuan agar tidak duplicate
            $this->db->query("SELECT id FROM anggota_rombel WHERE rombel_id = :rombel_id AND siswa_id = :siswa_id");
            $this->db->bind('rombel_id', $dest_rombel_id);
            $this->db->bind('siswa_id', $siswa_id);
            $this->db->single();
            
            if($this->db->rowCount() == 0) {
                $this->db->query("INSERT INTO anggota_rombel (rombel_id, siswa_id) VALUES (:rombel_id, :siswa_id)");
                $this->db->bind('rombel_id', $dest_rombel_id);
                $this->db->bind('siswa_id', $siswa_id);
                $this->db->execute();
                $inserted += $this->db->rowCount();
            }
        }
        return $inserted;
    }

    public function luluskanSiswa($siswa_ids)
    {
        $lulus = 0;
        foreach($siswa_ids as $siswa_id) {
            $this->db->query("UPDATE siswa SET status = 'Alumni' WHERE id = :siswa_id");
            $this->db->bind('siswa_id', $siswa_id);
            $this->db->execute();
            // count even if status is already Alumni so we just say 1 per ID
            $lulus++; 
        }
        return $lulus;
    }
}
