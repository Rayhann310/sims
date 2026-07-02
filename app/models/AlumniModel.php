<?php

class AlumniModel {
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function getAllAlumni($tahun = '')
    {
        $query = "SELECT siswa.*, users.username, users.nama_lengkap 
                  FROM siswa 
                  JOIN users ON siswa.user_id = users.id 
                  WHERE siswa.status = 'Alumni'";
        if (!empty($tahun)) {
            $query .= " AND siswa.tahun_lulus = :tahun";
        }
        $query .= " ORDER BY siswa.tahun_lulus DESC, users.nama_lengkap ASC";
        
        $this->db->query($query);
        if (!empty($tahun)) {
            $this->db->bind('tahun', $tahun);
        }
        return $this->db->resultSet();
    }
    
    public function getTahunLulusList()
    {
        $this->db->query("SELECT DISTINCT tahun_lulus FROM siswa WHERE status = 'Alumni' AND tahun_lulus IS NOT NULL ORDER BY tahun_lulus DESC");
        return $this->db->resultSet();
    }

    public function getAlumniById($id)
    {
        $this->db->query("SELECT siswa.*, users.username, users.nama_lengkap 
                          FROM siswa 
                          JOIN users ON siswa.user_id = users.id 
                          WHERE siswa.id = :id AND siswa.status = 'Alumni'");
        $this->db->bind('id', $id);
        return $this->db->single();
    }

    public function tambahDataAlumni($data)
    {
        try {
            $this->db->query("SELECT id FROM users WHERE username = :username");
            $this->db->bind('username', $data['nisn']);
            if($this->db->single()) {
                return ['status' => false, 'pesan' => 'NISN sudah terdaftar'];
            }

            $password_default = password_hash($data['nisn'], PASSWORD_DEFAULT);
            $this->db->query("INSERT INTO users (username, password, nama_lengkap, role) VALUES (:username, :password, :nama, 'siswa')");
            $this->db->bind('username', $data['nisn']);
            $this->db->bind('password', $password_default);
            $this->db->bind('nama', $data['nama_lengkap']);
            $this->db->execute();
            $user_id = $this->db->lastInsertId();

            $this->db->query("INSERT INTO siswa (user_id, nisn, jenis_kelamin, tanggal_lahir, alamat, nama_wali, no_hp, status, tahun_lulus) 
                              VALUES (:user_id, :nisn, :jk, :tgl, :alamat, :wali, :hp, 'Alumni', :tahun)");
            $this->db->bind('user_id', $user_id);
            $this->db->bind('nisn', $data['nisn']);
            $this->db->bind('jk', $data['jenis_kelamin']);
            $this->db->bind('tgl', !empty($data['tanggal_lahir']) ? $data['tanggal_lahir'] : null);
            $this->db->bind('alamat', $data['alamat']);
            $this->db->bind('wali', $data['nama_wali']);
            $this->db->bind('hp', $data['no_hp']);
            $this->db->bind('tahun', !empty($data['tahun_lulus']) ? $data['tahun_lulus'] : null);
            $this->db->execute();

            return ['status' => true];
        } catch(PDOException $e) {
            return ['status' => false, 'pesan' => $e->getMessage()];
        }
    }

    public function ubahDataAlumni($data)
    {
        try {
            $this->db->query("SELECT user_id FROM siswa WHERE id = :id");
            $this->db->bind('id', $data['id']);
            $siswa = $this->db->single();

            if($siswa) {
                $this->db->query("UPDATE users SET nama_lengkap = :nama WHERE id = :user_id");
                $this->db->bind('nama', $data['nama_lengkap']);
                $this->db->bind('user_id', $siswa['user_id']);
                $this->db->execute();

                $this->db->query("UPDATE siswa SET 
                                  jenis_kelamin = :jk,
                                  tanggal_lahir = :tgl,
                                  alamat = :alamat,
                                  nama_wali = :wali,
                                  no_hp = :hp,
                                  tahun_lulus = :tahun
                                  WHERE id = :id AND status = 'Alumni'");
                $this->db->bind('jk', $data['jenis_kelamin']);
                $this->db->bind('tgl', !empty($data['tanggal_lahir']) ? $data['tanggal_lahir'] : null);
                $this->db->bind('alamat', $data['alamat']);
                $this->db->bind('wali', $data['nama_wali']);
                $this->db->bind('hp', $data['no_hp']);
                $this->db->bind('tahun', !empty($data['tahun_lulus']) ? $data['tahun_lulus'] : null);
                $this->db->bind('id', $data['id']);
                $this->db->execute();
                return true;
            }
            return false;
        } catch(PDOException $e) {
            return false;
        }
    }

    public function hapusDataAlumni($id)
    {
        $this->db->query("SELECT user_id FROM siswa WHERE id = :id AND status = 'Alumni'");
        $this->db->bind('id', $id);
        $siswa = $this->db->single();

        if ($siswa) {
            $this->db->query("DELETE FROM users WHERE id = :user_id");
            $this->db->bind('user_id', $siswa['user_id']);
            $this->db->execute();
            return $this->db->rowCount();
        }
        return 0;
    }

    public function pindahKeSiswa($id)
    {
        $this->db->query("UPDATE siswa SET status = 'Aktif' WHERE id = :id AND status = 'Alumni'");
        $this->db->bind('id', $id);
        $this->db->execute();
        return $this->db->rowCount();
    }

    public function importData($dataArray)
    {
        $berhasil = 0;
        $gagal = 0;

        foreach($dataArray as $data) {
            $res = $this->tambahDataAlumni($data);
            if(isset($res['status']) && $res['status'] === true) {
                $berhasil++;
            } else {
                $gagal++;
            }
        }
        return ['sukses' => $berhasil, 'gagal' => $gagal];
    }
}
