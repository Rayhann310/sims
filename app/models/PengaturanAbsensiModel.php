<?php

class PengaturanAbsensiModel {
    private $db;

    public function __construct()
    {
        $this->db = new Database();
        
        // Ensure database schema is up to date via self-healing
        require_once 'app/models/UserModel.php';
        new UserModel();
    }

    public function getPengaturanGlobal()
    {
        $this->db->query('SELECT * FROM pengaturan_absensi ORDER BY id ASC LIMIT 1');
        $result = $this->db->single();
        if (!$result) {
            // default settings
            $this->db->query("INSERT INTO pengaturan_absensi (mode_siswa, mode_absen_siswa, batas_jam_masuk_guru, batas_jam_keluar_guru, toleransi_terlambat_guru, min_jam_pelajaran_siswa, batas_jam_masuk_siswa, batas_jam_pulang_siswa) VALUES ('Normal', 'Masuk Saja', '07:00:00', '15:00:00', 15, 4, '07:00:00', '14:00:00')");
            $this->db->execute();
            
            $this->db->query('SELECT * FROM pengaturan_absensi ORDER BY id ASC LIMIT 1');
            $result = $this->db->single();
        }

        // Pastikan kolom baru punya nilai default jika NULL (migrasi dari instalasi lama)
        if (empty($result['mode_absen_siswa'])) {
            $result['mode_absen_siswa'] = 'Masuk Saja';
        }
        if (empty($result['batas_jam_masuk_siswa'])) {
            $result['batas_jam_masuk_siswa'] = '07:00:00';
        }
        if (empty($result['batas_jam_pulang_siswa'])) {
            $result['batas_jam_pulang_siswa'] = '14:00:00';
        }

        return $result;
    }

    public function updatePengaturanGlobal($data)
    {
        $query = "UPDATE pengaturan_absensi SET 
                    mode_siswa = :mode_siswa, 
                    mode_absen_siswa = :mode_absen_siswa,
                    batas_jam_masuk_guru = :batas_jam_masuk_guru, 
                    batas_jam_keluar_guru = :batas_jam_keluar_guru, 
                    toleransi_terlambat_guru = :toleransi_terlambat_guru, 
                    min_jam_pelajaran_siswa = :min_jam_pelajaran_siswa,
                    batas_jam_masuk_siswa = :batas_jam_masuk_siswa,
                    batas_jam_pulang_siswa = :batas_jam_pulang_siswa
                  ORDER BY id ASC LIMIT 1";
        
        $this->db->query($query);
        $this->db->bind('mode_siswa', $data['mode_siswa']);
        $this->db->bind('mode_absen_siswa', $data['mode_absen_siswa'] ?? 'Masuk Saja');
        $this->db->bind('batas_jam_masuk_guru', $data['batas_jam_masuk_guru']);
        $this->db->bind('batas_jam_keluar_guru', $data['batas_jam_keluar_guru']);
        $this->db->bind('toleransi_terlambat_guru', $data['toleransi_terlambat_guru']);
        $this->db->bind('min_jam_pelajaran_siswa', $data['min_jam_pelajaran_siswa']);
        $this->db->bind('batas_jam_masuk_siswa', $data['batas_jam_masuk_siswa'] ?? '07:00:00');
        $this->db->bind('batas_jam_pulang_siswa', $data['batas_jam_pulang_siswa'] ?? '14:00:00');

        $this->db->execute();
        return $this->db->rowCount();
    }

    public function getPengaturanGuruAll()
    {
        $query = "SELECT pg.*, g.nip, u.nama_lengkap 
                  FROM pengaturan_absensi_guru pg 
                  JOIN guru g ON pg.guru_id = g.id 
                  JOIN users u ON g.user_id = u.id";
        $this->db->query($query);
        return $this->db->resultSet();
    }

    public function getPengaturanGuruById($guru_id)
    {
        $this->db->query('SELECT * FROM pengaturan_absensi_guru WHERE guru_id = :guru_id');
        $this->db->bind('guru_id', $guru_id);
        return $this->db->single();
    }

    public function setPengaturanGuru($data)
    {
        // Check if exists
        $existing = $this->getPengaturanGuruById($data['guru_id']);
        if ($existing) {
            $query = "UPDATE pengaturan_absensi_guru SET 
                        batas_jam_masuk = :batas_jam_masuk, 
                        batas_jam_keluar = :batas_jam_keluar, 
                        toleransi_terlambat = :toleransi_terlambat 
                      WHERE guru_id = :guru_id";
        } else {
            $query = "INSERT INTO pengaturan_absensi_guru (guru_id, batas_jam_masuk, batas_jam_keluar, toleransi_terlambat) 
                      VALUES (:guru_id, :batas_jam_masuk, :batas_jam_keluar, :toleransi_terlambat)";
        }

        $this->db->query($query);
        $this->db->bind('guru_id', $data['guru_id']);
        $this->db->bind('batas_jam_masuk', $data['batas_jam_masuk']);
        $this->db->bind('batas_jam_keluar', $data['batas_jam_keluar']);
        $this->db->bind('toleransi_terlambat', $data['toleransi_terlambat']);

        $this->db->execute();
        return $this->db->rowCount();
    }

    public function deletePengaturanGuru($guru_id)
    {
        $this->db->query('DELETE FROM pengaturan_absensi_guru WHERE guru_id = :guru_id');
        $this->db->bind('guru_id', $guru_id);
        $this->db->execute();
        return $this->db->rowCount();
    }

    // Mendapatkan aturan spesifik untuk seorang guru
    public function getAturanAbsensiGuru($guru_id)
    {
        $global = $this->getPengaturanGlobal();
        $custom = $this->getPengaturanGuruById($guru_id);

        if ($custom) {
            return [
                'batas_jam_masuk' => $custom['batas_jam_masuk'] ?? $global['batas_jam_masuk_guru'],
                'batas_jam_keluar' => $custom['batas_jam_keluar'] ?? $global['batas_jam_keluar_guru'],
                'toleransi_terlambat' => $custom['toleransi_terlambat'] !== null ? $custom['toleransi_terlambat'] : $global['toleransi_terlambat_guru'],
            ];
        }

        return [
            'batas_jam_masuk' => $global['batas_jam_masuk_guru'],
            'batas_jam_keluar' => $global['batas_jam_keluar_guru'],
            'toleransi_terlambat' => $global['toleransi_terlambat_guru'],
        ];
    }

    public function resetAbsensiSiswa($ta_id)
    {
        $this->db->query("SELECT * FROM tahun_akademik WHERE id = :id");
        $this->db->bind('id', $ta_id);
        $ta = $this->db->single();
        if (!$ta) return false;

        $years = explode('/', $ta['nama_tahun']);
        $y1 = $years[0];
        $y2 = $years[1];
        
        if ($ta['semester'] == '1') {
            $start = $y1 . '-07-01';
            $end = $y1 . '-12-31';
        } else {
            $start = $y2 . '-01-01';
            $end = $y2 . '-06-30';
        }

        $this->db->query("DELETE FROM absensi_siswa WHERE tanggal BETWEEN :start AND :end");
        $this->db->bind('start', $start);
        $this->db->bind('end', $end);
        $this->db->execute();
        $c1 = $this->db->rowCount();

        $this->db->query("DELETE FROM absensi_siswa_detail WHERE tanggal BETWEEN :start AND :end");
        $this->db->bind('start', $start);
        $this->db->bind('end', $end);
        $this->db->execute();
        $c2 = $this->db->rowCount();

        return $c1 + $c2;
    }

    public function resetAbsensiGuru($ta_id)
    {
        $this->db->query("SELECT * FROM tahun_akademik WHERE id = :id");
        $this->db->bind('id', $ta_id);
        $ta = $this->db->single();
        if (!$ta) return false;

        $years = explode('/', $ta['nama_tahun']);
        $y1 = $years[0];
        $y2 = $years[1];
        
        if ($ta['semester'] == '1') {
            $start = $y1 . '-07-01';
            $end = $y1 . '-12-31';
        } else {
            $start = $y2 . '-01-01';
            $end = $y2 . '-06-30';
        }

        $this->db->query("DELETE FROM absensi_guru WHERE tanggal BETWEEN :start AND :end");
        $this->db->bind('start', $start);
        $this->db->bind('end', $end);
        $this->db->execute();
        return $this->db->rowCount();
    }
}
