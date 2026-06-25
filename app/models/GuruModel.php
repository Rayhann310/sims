<?php

class GuruModel {
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function getAllGuru($filters = [])
    {
        $query = "SELECT guru.*, users.username, users.nama_lengkap 
                  FROM guru 
                  JOIN users ON guru.user_id = users.id 
                  WHERE 1=1";
        
        $binds = [];
        if (!empty($filters['jk'])) {
            $query .= " AND guru.jenis_kelamin = :jk";
            $binds['jk'] = $filters['jk'];
        }

        $query .= " ORDER BY users.nama_lengkap ASC";
        
        $this->db->query($query);
        foreach ($binds as $key => $val) {
            $this->db->bind($key, $val);
        }

        return $this->db->resultSet();
    }

    public function getGuruStats()
    {
        $stats = [
            'total' => 0,
            'laki' => 0,
            'perempuan' => 0,
            'wali_kelas' => 0
        ];

        $this->db->query("SELECT COUNT(id) as total, 
                                 SUM(CASE WHEN jenis_kelamin = 'L' THEN 1 ELSE 0 END) as laki,
                                 SUM(CASE WHEN jenis_kelamin = 'P' THEN 1 ELSE 0 END) as perempuan
                          FROM guru");
        $aktif = $this->db->single();
        if ($aktif) {
            $stats['total'] = $aktif['total'] ?? 0;
            $stats['laki'] = $aktif['laki'] ?? 0;
            $stats['perempuan'] = $aktif['perempuan'] ?? 0;
        }

        $this->db->query("SELECT COUNT(DISTINCT r.wali_kelas_id) as total 
                          FROM rombel r 
                          JOIN tahun_akademik ta ON r.tahun_akademik_id = ta.id 
                          WHERE ta.status = 'Aktif' AND r.wali_kelas_id IS NOT NULL");
        $wali = $this->db->single();
        $stats['wali_kelas'] = $wali['total'] ?? 0;

        return $stats;
    }

    public function getGuruChartStats()
    {
        // Distribusi guru berdasarkan jenis kelamin
        $this->db->query("SELECT jenis_kelamin as label, COUNT(id) as jumlah FROM guru GROUP BY jenis_kelamin");
        $result = $this->db->resultSet();
        
        // Map labels L/P to Laki-laki/Perempuan
        foreach($result as &$row) {
            $row['label'] = ($row['label'] == 'L') ? 'Laki-Laki' : 'Perempuan';
        }
        return $result;
    }

    public function getGuruById($id)
    {
        $this->db->query("SELECT guru.*, users.username, users.nama_lengkap FROM guru JOIN users ON guru.user_id = users.id WHERE guru.id = :id");
        $this->db->bind('id', $id);
        return $this->db->single();
    }


    public function tambahDataGuru($data)
    {
        try {
            // Kita harus memulai transaksi karena ada 2 insert
            $this->db->query("START TRANSACTION");
            $this->db->execute();

            // 1. Tambah user
            $queryUser = "INSERT INTO users (username, password, role, nama_lengkap) VALUES (:username, :password, 'guru', :nama_lengkap)";
            $this->db->query($queryUser);
            $this->db->bind('username', htmlspecialchars($data['username']));
            $this->db->bind('password', password_hash($data['password'], PASSWORD_DEFAULT));
            $this->db->bind('nama_lengkap', htmlspecialchars($data['nama_lengkap']));
            $this->db->execute();

            // Dapatkan ID user terakhir
            $this->db->query("SELECT LAST_INSERT_ID() as last_id");
            $userId = $this->db->single()['last_id'];

            // 2. Tambah guru
            $queryGuru = "INSERT INTO guru (user_id, nip, jenis_kelamin, no_hp, alamat) VALUES (:user_id, :nip, :jenis_kelamin, :no_hp, :alamat)";
            $this->db->query($queryGuru);
            $this->db->bind('user_id', $userId);
            $this->db->bind('nip', htmlspecialchars($data['nip']));
            $this->db->bind('jenis_kelamin', htmlspecialchars($data['jenis_kelamin']));
            $this->db->bind('no_hp', htmlspecialchars($data['no_hp']));
            $this->db->bind('alamat', htmlspecialchars($data['alamat']));
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
                } elseif (strpos($e->getMessage(), 'nip') !== false) {
                    $pesan = 'NIP/NUPTK tersebut sudah terdaftar.';
                }
            }
            return ['status' => false, 'pesan' => $pesan];
        }
    }

    public function hapusDataGuru($id)
    {
        // Karena relasi ON DELETE CASCADE, kita cukup menghapus user_id nya, maka otomatis guru juga terhapus.
        // Tapi kita perlu dapatkan user_id dari guru.id
        $this->db->query("SELECT user_id FROM guru WHERE id = :id");
        $this->db->bind('id', $id);
        $guru = $this->db->single();

        if($guru) {
            $this->db->query("DELETE FROM users WHERE id = :user_id");
            $this->db->bind('user_id', $guru['user_id']);
            $this->db->execute();
            return $this->db->rowCount();
        }
        return 0;
    }



    public function ubahDataGuru($data)
    {
        try {
            $this->db->query("START TRANSACTION");
            $this->db->execute();

            // 1. Ubah data users
            $this->db->query("UPDATE users SET nama_lengkap = :nama_lengkap WHERE id = :user_id");
            $this->db->bind('nama_lengkap', htmlspecialchars($data['nama_lengkap']));
            $this->db->bind('user_id', $data['user_id']);
            $this->db->execute();

            // 2. Ubah data guru
            if(!empty($data['foto'])) {
                $this->db->query("UPDATE guru SET jenis_kelamin = :jenis_kelamin, no_hp = :no_hp, alamat = :alamat, foto = :foto WHERE id = :id");
                $this->db->bind('foto', $data['foto']);
            } else {
                $this->db->query("UPDATE guru SET jenis_kelamin = :jenis_kelamin, no_hp = :no_hp, alamat = :alamat WHERE id = :id");
            }
            $this->db->bind('jenis_kelamin', htmlspecialchars($data['jenis_kelamin']));
            $this->db->bind('no_hp', htmlspecialchars($data['no_hp']));
            $this->db->bind('alamat', htmlspecialchars($data['alamat']));
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

    public function importData($dataArray)
    {
        $sukses = 0;
        $gagal = 0;
        
        foreach($dataArray as $data) {
            // Validasi data dasar
            if(empty($data['nip']) || empty($data['nama_lengkap'])) continue;

            try {
                $this->db->query("START TRANSACTION");
                $this->db->execute();

                // Cek apakah NIP sudah ada di tabel guru
                $this->db->query("SELECT id FROM guru WHERE nip = :nip");
                $this->db->bind('nip', $data['nip']);
                $exists = $this->db->single();
                if($exists) {
                    throw new Exception("NIP sudah ada");
                }

                // Cek apakah Username (NIP) sudah dipakai di tabel users
                $username = trim($data['nip']);
                $this->db->query("SELECT id FROM users WHERE username = :username");
                $this->db->bind('username', $username);
                if($this->db->single()) {
                    throw new Exception("Username sudah dipakai");
                }

                $password = password_hash($username, PASSWORD_DEFAULT); // Password default = NIP
                $nama_lengkap = trim($data['nama_lengkap']);

                // 1. Tambah user
                $queryUser = "INSERT INTO users (username, password, role, nama_lengkap) VALUES (:username, :password, 'guru', :nama_lengkap)";
                $this->db->query($queryUser);
                $this->db->bind('username', $username);
                $this->db->bind('password', $password);
                $this->db->bind('nama_lengkap', $nama_lengkap);
                $this->db->execute();

                $this->db->query("SELECT LAST_INSERT_ID() as last_id");
                $userId = $this->db->single()['last_id'];

                // 2. Tambah guru
                $queryGuru = "INSERT INTO guru (user_id, nip, jenis_kelamin, no_hp, alamat) VALUES (:user_id, :nip, :jenis_kelamin, :no_hp, :alamat)";
                $this->db->query($queryGuru);
                $this->db->bind('user_id', $userId);
                $this->db->bind('nip', $username);
                $this->db->bind('jenis_kelamin', strtoupper($data['jenis_kelamin']) == 'P' ? 'P' : 'L');
                $this->db->bind('no_hp', $data['no_hp'] ?? '');
                $this->db->bind('alamat', $data['alamat'] ?? '');
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
