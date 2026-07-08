<?php

class AbsensiGuruModel {
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function getAbsensiHariIni()
    {
        $query = "SELECT * FROM absensi_guru WHERE tanggal = CURDATE()";
        $this->db->query($query);
        return $this->db->resultSet();
    }

    public function absen($data)
    {
        $guru_id = $data['guru_id'];
        $status = $data['status'] ?? 'Hadir';
        $waktu = date('H:i:s');
        $tanggal = date('Y-m-d');

        // Cek apakah sudah absen masuk hari ini
        $this->db->query("SELECT * FROM absensi_guru WHERE guru_id = :guru_id AND tanggal = :tanggal");
        $this->db->bind('guru_id', $guru_id);
        $this->db->bind('tanggal', $tanggal);
        $exist = $this->db->single();

        if ($exist) {
            // Sudah absen masuk, lakukan absen pulang
            // Jika statusnya bukan hadir saat masuk (misal sakit), maka jangan update waktu pulang
            if($exist['status'] != 'Hadir') {
                return ['status' => false, 'pesan' => 'Guru ini sudah tercatat ' . $exist['status'] . ' hari ini.'];
            }
            if($exist['waktu_pulang'] != null) {
                return ['status' => false, 'pesan' => 'Sudah absen pulang hari ini.'];
            }

            $query = "UPDATE absensi_guru SET waktu_pulang = :waktu WHERE id = :id";
            $this->db->query($query);
            $this->db->bind('waktu', $waktu);
            $this->db->bind('id', $exist['id']);
            $this->db->execute();
            return ['status' => true, 'pesan' => 'Berhasil absen pulang.'];
        } else {
            // Belum absen, lakukan absen masuk
            $query = "INSERT INTO absensi_guru (guru_id, tanggal, waktu_masuk, status) VALUES (:guru_id, :tanggal, :waktu_masuk, :status)";
            $this->db->query($query);
            $this->db->bind('guru_id', $guru_id);
            $this->db->bind('tanggal', $tanggal);
            $this->db->bind('waktu_masuk', $waktu);
            $this->db->bind('status', $status);
            $this->db->execute();
            return ['status' => true, 'pesan' => 'Berhasil absen masuk.'];
        }
    }
}
