<?php

class SiswaModel {
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function getAllSiswa()
    {
        $this->db->query("SELECT siswa.*, users.username, users.nama_lengkap 
                          FROM siswa 
                          JOIN users ON siswa.user_id = users.id 
                          ORDER BY users.nama_lengkap ASC");
        return $this->db->resultSet();
    }

    public function getSiswaById($id)
    {
        $this->db->query("SELECT siswa.*, users.username, users.nama_lengkap FROM siswa JOIN users ON siswa.user_id = users.id WHERE siswa.id = :id");
        $this->db->bind('id', $id);
        return $this->db->single();
    }

    public function getSiswaStats()
    {
        $stats = [
            'total' => 0,
            'laki' => 0,
            'perempuan' => 0,
            'alumni' => 0,
            'ultah' => 0,
            'tahun_akademik' => 'Tidak Ada'
        ];

        // Dapatkan Tahun Akademik Aktif
        $this->db->query("SELECT id, nama_tahun, semester FROM tahun_akademik WHERE status = 'Aktif' LIMIT 1");
        $ta = $this->db->single();
        
        if ($ta) {
            $stats['tahun_akademik'] = $ta['nama_tahun'] . ' (' . $ta['semester'] . ')';
            $ta_id = $ta['id'];

            // Total Siswa Aktif di Rombel Tahun Ini
            $this->db->query("SELECT COUNT(DISTINCT ar.siswa_id) as total, 
                                     SUM(CASE WHEN s.jenis_kelamin = 'L' THEN 1 ELSE 0 END) as laki,
                                     SUM(CASE WHEN s.jenis_kelamin = 'P' THEN 1 ELSE 0 END) as perempuan
                              FROM anggota_rombel ar 
                              JOIN rombel r ON ar.rombel_id = r.id 
                              JOIN siswa s ON ar.siswa_id = s.id
                              WHERE r.tahun_akademik_id = :ta_id");
            $this->db->bind('ta_id', $ta_id);
            $aktif = $this->db->single();
            if ($aktif) {
                $stats['total'] = $aktif['total'] ?? 0;
                $stats['laki'] = $aktif['laki'] ?? 0;
                $stats['perempuan'] = $aktif['perempuan'] ?? 0;
            }
        }

        // Alumni (Semua siswa yang status = 'Alumni')
        $this->db->query("SELECT COUNT(id) as total FROM siswa WHERE status = 'Alumni'");
        $alumni = $this->db->single();
        $stats['alumni'] = $alumni['total'] ?? 0;

        // Ulang Tahun Hari Ini
        $this->db->query("SELECT COUNT(id) as total FROM siswa WHERE MONTH(tanggal_lahir) = MONTH(CURDATE()) AND DAY(tanggal_lahir) = DAY(CURDATE())");
        $ultah = $this->db->single();
        $stats['ultah'] = $ultah['total'] ?? 0;

        return $stats;
    }

    public function getSiswaPerKelasStats()
    {
        $this->db->query("SELECT r.nama_rombel as label, COUNT(ar.siswa_id) as jumlah 
                          FROM rombel r 
                          LEFT JOIN anggota_rombel ar ON r.id = ar.rombel_id 
                          JOIN tahun_akademik ta ON r.tahun_akademik_id = ta.id 
                          WHERE ta.status = 'Aktif' 
                          GROUP BY r.id 
                          ORDER BY r.nama_rombel");
        return $this->db->resultSet();
    }

    public function tambahDataSiswa($data)
    {
        try {
            $this->db->query("START TRANSACTION");
            $this->db->execute();

            // 1. Tambah user
            $queryUser = "INSERT INTO users (username, password, role, nama_lengkap) VALUES (:username, :password, 'siswa', :nama_lengkap)";
            $this->db->query($queryUser);
            $this->db->bind('username', htmlspecialchars($data['username']));
            $this->db->bind('password', password_hash($data['password'], PASSWORD_DEFAULT));
            $this->db->bind('nama_lengkap', htmlspecialchars($data['nama_lengkap']));
            $this->db->execute();

            $this->db->query("SELECT LAST_INSERT_ID() as last_id");
            $userId = $this->db->single()['last_id'];

            // 2. Tambah siswa
            $querySiswa = "INSERT INTO siswa (user_id, nisn, jenis_kelamin, tanggal_lahir, alamat, nama_wali) VALUES (:user_id, :nisn, :jenis_kelamin, :tanggal_lahir, :alamat, :nama_wali)";
            $this->db->query($querySiswa);
            $this->db->bind('user_id', $userId);
            $this->db->bind('nisn', htmlspecialchars($data['nisn']));
            $this->db->bind('jenis_kelamin', htmlspecialchars($data['jenis_kelamin']));
            $this->db->bind('tanggal_lahir', !empty($data['tanggal_lahir']) ? $data['tanggal_lahir'] : null);
            $this->db->bind('alamat', htmlspecialchars($data['alamat']));
            $this->db->bind('nama_wali', htmlspecialchars($data['nama_wali']));
            $this->db->execute();

            $this->db->query("COMMIT");
            $this->db->execute();
            return ['status' => true];
        } catch (PDOException $e) {
            $this->db->query("ROLLBACK");
            $this->db->execute();
            $pesan = 'Terjadi kesalahan sistem.';
            if ($e->getCode() == 23000) {
                if (strpos($e->getMessage(), 'username') !== false) {
                    $pesan = 'Username sudah digunakan oleh pengguna lain.';
                } elseif (strpos($e->getMessage(), 'nisn') !== false) {
                    $pesan = 'NISN tersebut sudah terdaftar.';
                }
            }
            return ['status' => false, 'pesan' => $pesan];
        }
    }


    public function ubahDataSiswa($data)
    {
        try {
            $this->db->query("START TRANSACTION");
            $this->db->execute();

            // 1. Ubah data users
            $this->db->query("UPDATE users SET nama_lengkap = :nama_lengkap WHERE id = :user_id");
            $this->db->bind('nama_lengkap', htmlspecialchars($data['nama_lengkap']));
            $this->db->bind('user_id', $data['user_id']);
            $this->db->execute();

            // 2. Ubah data siswa
            $this->db->query("UPDATE siswa SET jenis_kelamin = :jenis_kelamin, tanggal_lahir = :tanggal_lahir, alamat = :alamat, nama_wali = :nama_wali WHERE id = :id");
            $this->db->bind('jenis_kelamin', htmlspecialchars($data['jenis_kelamin']));
            $this->db->bind('tanggal_lahir', !empty($data['tanggal_lahir']) ? $data['tanggal_lahir'] : null);
            $this->db->bind('alamat', htmlspecialchars($data['alamat']));
            $this->db->bind('nama_wali', htmlspecialchars($data['nama_wali']));
            $this->db->bind('id', $data['id']);
            $this->db->execute();

            $this->db->query("COMMIT");
            $this->db->execute();
            return true;
        } catch (Exception $e) {
            $this->db->query("ROLLBACK");
            $this->db->execute();
            return false;
        }
    }

    public function hapusDataSiswa($id)
    {
        $this->db->query("SELECT user_id FROM siswa WHERE id = :id");
        $this->db->bind('id', $id);
        $siswa = $this->db->single();

        if($siswa) {
            $this->db->query("DELETE FROM users WHERE id = :user_id");
            $this->db->bind('user_id', $siswa['user_id']);
            $this->db->execute();
            return $this->db->rowCount();
        }
        return 0;
    }

    public function importData($dataArray)
    {
        $sukses = 0;
        $gagal = 0;
        
        foreach($dataArray as $data) {
            // Validasi data dasar
            if(empty($data['nisn']) || empty($data['nama_lengkap'])) continue;

            try {
                $this->db->query("START TRANSACTION");
                $this->db->execute();

                // Cek apakah NISN sudah ada di tabel siswa
                $this->db->query("SELECT id FROM siswa WHERE nisn = :nisn");
                $this->db->bind('nisn', $data['nisn']);
                $exists = $this->db->single();
                if($exists) {
                    throw new Exception("NISN sudah ada");
                }

                // Cek apakah Username (NISN) sudah dipakai di tabel users
                $username = trim($data['nisn']);
                $this->db->query("SELECT id FROM users WHERE username = :username");
                $this->db->bind('username', $username);
                if($this->db->single()) {
                    throw new Exception("Username sudah dipakai");
                }

                $password = password_hash($username, PASSWORD_DEFAULT); // Password default = NISN
                $nama_lengkap = trim($data['nama_lengkap']);

                // 1. Tambah user
                $queryUser = "INSERT INTO users (username, password, role, nama_lengkap) VALUES (:username, :password, 'siswa', :nama_lengkap)";
                $this->db->query($queryUser);
                $this->db->bind('username', $username);
                $this->db->bind('password', $password);
                $this->db->bind('nama_lengkap', $nama_lengkap);
                $this->db->execute();

                $this->db->query("SELECT LAST_INSERT_ID() as last_id");
                $userId = $this->db->single()['last_id'];

                // 2. Tambah siswa
                $querySiswa = "INSERT INTO siswa (user_id, nisn, jenis_kelamin, tanggal_lahir, alamat, nama_wali) VALUES (:user_id, :nisn, :jenis_kelamin, :tanggal_lahir, :alamat, :nama_wali)";
                $this->db->query($querySiswa);
                $this->db->bind('user_id', $userId);
                $this->db->bind('nisn', $username);
                $this->db->bind('jenis_kelamin', strtoupper($data['jenis_kelamin']) == 'P' ? 'P' : 'L');
                $this->db->bind('tanggal_lahir', !empty($data['tanggal_lahir']) ? $data['tanggal_lahir'] : null);
                $this->db->bind('alamat', $data['alamat'] ?? '');
                $this->db->bind('nama_wali', $data['nama_wali'] ?? '');
                $this->db->execute();

                $this->db->query("COMMIT");
                $this->db->execute();
                $sukses++;
            } catch (Exception $e) {
                $this->db->query("ROLLBACK");
                $this->db->execute();
                $gagal++;
            }
        }
        return ['sukses' => $sukses, 'gagal' => $gagal];
    }
}
