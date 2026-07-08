<?php

class AbsensiSiswaModel {
    private $db;

    public function __construct()
    {
        $this->db = new Database();
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
}
