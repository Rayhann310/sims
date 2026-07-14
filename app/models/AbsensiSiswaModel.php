<?php

class AbsensiSiswaModel {
    private $db;

    public function __construct()
    {
        $this->db = new Database();
        
        // Ensure tables exist via self-healing
        require_once 'app/models/UserModel.php';
        new UserModel();
    }

    public function absenScan($data)
    {
        $qr_token = $data['qr_token'];
        $waktu_scan = $data['waktu_scan'] ?? date('H:i:s');
        $tanggal = date('Y-m-d');

        // Cari siswa berdasarkan qr_token
        $this->db->query("SELECT id FROM siswa WHERE qr_token = :qr_token");
        $this->db->bind('qr_token', $qr_token);
        $siswa = $this->db->single();

        if (!$siswa) {
            return ['status' => false, 'pesan' => 'QR Code tidak dikenali.'];
        }

        $siswa_id = $siswa['id'];

        // Cek apakah sudah absen hari ini
        $this->db->query("SELECT id FROM absensi_siswa WHERE siswa_id = :siswa_id AND tanggal = :tanggal");
        $this->db->bind('siswa_id', $siswa_id);
        $this->db->bind('tanggal', $tanggal);
        if ($this->db->single()) {
            return ['status' => true, 'pesan' => 'Siswa sudah melakukan presensi hari ini.']; // Anggap success
        }

        // Simpan absensi
        $query = "INSERT INTO absensi_siswa (siswa_id, tanggal, waktu_scan, status) VALUES (:siswa_id, :tanggal, :waktu_scan, 'Hadir')";
        $this->db->query($query);
        $this->db->bind('siswa_id', $siswa_id);
        $this->db->bind('tanggal', $tanggal);
        $this->db->bind('waktu_scan', $waktu_scan);
        
        try {
            $this->db->execute();
            
            // Get nama siswa
            $this->db->query("SELECT nama_lengkap FROM users JOIN siswa ON users.id = siswa.user_id WHERE siswa.id = :id");
            $this->db->bind('id', $siswa_id);
            $user = $this->db->single();

            return ['status' => true, 'pesan' => 'Presensi berhasil: ' . $user['nama_lengkap']];
        } catch (Exception $e) {
            return ['status' => false, 'pesan' => 'Gagal mencatat presensi.'];
        }
    }

    public function getSiswaByRombel($rombel_id)
    {
        $query = "SELECT s.id, s.nisn, u.nama_lengkap, s.qr_token 
                  FROM anggota_rombel ar 
                  JOIN siswa s ON ar.siswa_id = s.id 
                  JOIN users u ON s.user_id = u.id 
                  WHERE ar.rombel_id = :rombel_id 
                  ORDER BY u.nama_lengkap ASC";
        $this->db->query($query);
        $this->db->bind('rombel_id', $rombel_id);
        return $this->db->resultSet();
    }

    public function absenKelas($data)
    {
        $siswa_id = $data['siswa_id'];
        $guru_id = $data['guru_id'];
        $jam_ke = $data['jam_ke'];
        $status = $data['status'];
        $tanggal = date('Y-m-d');
        $waktu_scan = date('H:i:s');

        // Check if exists
        $this->db->query("SELECT id FROM absensi_siswa_detail WHERE siswa_id = :siswa_id AND tanggal = :tanggal AND jam_ke = :jam_ke");
        $this->db->bind('siswa_id', $siswa_id);
        $this->db->bind('tanggal', $tanggal);
        $this->db->bind('jam_ke', $jam_ke);
        $exist = $this->db->single();

        if ($exist) {
            // Update
            $this->db->query("UPDATE absensi_siswa_detail SET status = :status, guru_id = :guru_id, waktu_scan = :waktu_scan WHERE id = :id");
            $this->db->bind('status', $status);
            $this->db->bind('guru_id', $guru_id);
            $this->db->bind('waktu_scan', $waktu_scan);
            $this->db->bind('id', $exist['id']);
        } else {
            // Insert
            $this->db->query("INSERT INTO absensi_siswa_detail (siswa_id, tanggal, jam_ke, guru_id, waktu_scan, status) VALUES (:siswa_id, :tanggal, :jam_ke, :guru_id, :waktu_scan, :status)");
            $this->db->bind('siswa_id', $siswa_id);
            $this->db->bind('tanggal', $tanggal);
            $this->db->bind('jam_ke', $jam_ke);
            $this->db->bind('guru_id', $guru_id);
            $this->db->bind('waktu_scan', $waktu_scan);
            $this->db->bind('status', $status);
        }
        
        $this->db->execute();
        
        // Coba evaluasi absensi harian (Opsional, bisa dilakukan via Cron / trigger khusus)
        // Kita hitung apakah dia sudah memenuhi min_jam_pelajaran_siswa
        $this->evaluateDailyAbsensi($siswa_id, $tanggal);

        // Jika mode absen adalah Normal (Absen Sekali), langsung catat ke absensi_siswa harian
        require_once 'app/models/PengaturanAbsensiModel.php';
        $pam = new PengaturanAbsensiModel();
        $global = $pam->getPengaturanGlobal();
        if ($global['mode_siswa'] === 'Normal') {
            $this->db->query("SELECT id FROM absensi_siswa WHERE siswa_id = :siswa_id AND tanggal = :tanggal");
            $this->db->bind('siswa_id', $siswa_id);
            $this->db->bind('tanggal', $tanggal);
            $existDaily = $this->db->single();

            if ($existDaily) {
                $this->db->query("UPDATE absensi_siswa SET status = :status WHERE id = :id");
                $this->db->bind('status', $status);
                $this->db->bind('id', $existDaily['id']);
            } else {
                $this->db->query("INSERT INTO absensi_siswa (siswa_id, tanggal, waktu_scan, status) VALUES (:siswa_id, :tanggal, :waktu_scan, :status)");
                $this->db->bind('siswa_id', $siswa_id);
                $this->db->bind('tanggal', $tanggal);
                $this->db->bind('waktu_scan', $waktu_scan);
                $this->db->bind('status', $status);
            }
            $this->db->execute();
        }

        return ['status' => true, 'pesan' => 'Berhasil mencatat absensi kelas.'];
    }

    private function evaluateDailyAbsensi($siswa_id, $tanggal)
    {
        require_once 'app/models/PengaturanAbsensiModel.php';
        $pam = new PengaturanAbsensiModel();
        $global = $pam->getPengaturanGlobal();

        if ($global['mode_siswa'] !== 'Per Jam Pelajaran') {
            return;
        }

        $min_jam = (int)$global['min_jam_pelajaran_siswa'];

        // Hitung berapa kali hadir hari ini
        $this->db->query("SELECT COUNT(id) as total_hadir FROM absensi_siswa_detail WHERE siswa_id = :siswa_id AND tanggal = :tanggal AND status = 'Hadir'");
        $this->db->bind('siswa_id', $siswa_id);
        $this->db->bind('tanggal', $tanggal);
        $hadirCount = $this->db->single()['total_hadir'];

        $final_status = ($hadirCount >= $min_jam) ? 'Hadir' : 'Alpa';

        // Cek apakah di absensi_siswa sudah ada
        $this->db->query("SELECT id FROM absensi_siswa WHERE siswa_id = :siswa_id AND tanggal = :tanggal");
        $this->db->bind('siswa_id', $siswa_id);
        $this->db->bind('tanggal', $tanggal);
        $existDaily = $this->db->single();

        if ($existDaily) {
            $this->db->query("UPDATE absensi_siswa SET status = :status WHERE id = :id");
            $this->db->bind('status', $final_status);
            $this->db->bind('id', $existDaily['id']);
        } else {
            $this->db->query("INSERT INTO absensi_siswa (siswa_id, tanggal, waktu_scan, status) VALUES (:siswa_id, :tanggal, :waktu_scan, :status)");
            $this->db->bind('siswa_id', $siswa_id);
            $this->db->bind('tanggal', $tanggal);
            $this->db->bind('waktu_scan', date('H:i:s'));
            $this->db->bind('status', $final_status);
        }
        $this->db->execute();
    }
}
