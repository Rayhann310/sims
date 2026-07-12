<?php

class UserModel {
    private $db;

    public function __construct()
    {
        $this->db = new Database();
        $this->selfHealing();
    }

    public function selfHealing()
    {
        try {
            $schemaPath = dirname(__DIR__) . '/config/database_schema.php';
            if (file_exists($schemaPath)) {
                $schema = require $schemaPath;

                foreach ($schema as $tableName => $definition) {
                    // 1. Buat tabel jika belum ada
                    $this->db->query("SHOW TABLES LIKE :table_name");
                    $this->db->bind('table_name', $tableName);
                    $this->db->execute();
                    
                    if ($this->db->rowCount() == 0) {
                        $this->db->query($definition['create_sql']);
                        $this->db->execute();
                    } else {
                        // 2. Tambah kolom jika belum ada (Tanpa mengubah/menghapus yang sudah ada)
                        $this->db->query("SHOW COLUMNS FROM `$tableName`");
                        $existingColsRaw = $this->db->resultSet();
                        $existingCols = array_map(function($c) { return $c['Field']; }, $existingColsRaw);

                        foreach ($definition['columns'] as $colName => $colType) {
                            if (!in_array($colName, $existingCols)) {
                                // Tambahkan kolom baru
                                try {
                                    $this->db->query("ALTER TABLE `$tableName` ADD COLUMN `$colName` $colType");
                                    $this->db->execute();
                                } catch (Exception $e) {
                                    // Log or ignore individual column addition failures
                                    error_log("Self-healing failed to add $colName to $tableName: " . $e->getMessage());
                                }
                            }
                        }
                    }
                }
            }

            // SELF-HEALING: Pastikan kolom password cukup panjang untuk bcrypt
            $this->db->query("ALTER TABLE users MODIFY password VARCHAR(255)");
            $this->db->execute();

            // SELF-HEALING: Cek jika belum ada admin, buat otomatis
            $this->db->query("SELECT id FROM users WHERE role = 'admin'");
            $this->db->execute();
            
            $password = password_hash('admin123', PASSWORD_DEFAULT);
            if($this->db->rowCount() == 0) {
                // Insert default admin (username: admin, password: admin123)
                $this->db->query("INSERT INTO users (username, password, role, nama_lengkap) VALUES ('admin', :password, 'admin', 'Administrator Sistem')");
                $this->db->bind('password', $password);
                $this->db->execute();
            } else {
                // Fix admin password if it was truncated by previous column size (bcrypt is 60 chars)
                $this->db->query("UPDATE users SET password = :password WHERE role = 'admin' AND LENGTH(password) < 60");
                $this->db->bind('password', $password);
                $this->db->execute();
            }
            // SELF-HEALING: Ubah tipe data enum jurusan menjadi varchar di tabel kelas dan alokasi_mapel
            $this->db->query("ALTER TABLE kelas MODIFY jurusan VARCHAR(50) NOT NULL");
            $this->db->execute();
            $this->db->query("ALTER TABLE alokasi_mapel MODIFY jurusan VARCHAR(50) NOT NULL");
            $this->db->execute();

            // SELF-HEALING: Populate master_jurusan jika masih kosong
            $this->db->query("SELECT COUNT(*) as count FROM master_jurusan");
            $this->db->execute();
            $countJurusan = $this->db->single()['count'];
            if ($countJurusan == 0) {
                $defaultJurusan = ['MIPA', 'IPS', 'BAHASA', 'UMUM'];
                foreach ($defaultJurusan as $j) {
                    $this->db->query("INSERT INTO master_jurusan (nama_jurusan) VALUES (:nama)");
                    $this->db->bind('nama', $j);
                    $this->db->execute();
                }
            }

            // SELF-HEALING: Refactor alokasi_mapel to use kelas_id instead of tingkat/jurusan
            try {
                // Check if kelas_id already exists
                $this->db->query("SHOW COLUMNS FROM alokasi_mapel LIKE 'kelas_id'");
                $res = $this->db->resultSet();
                if (count($res) == 0) {
                    // Empty table first because the structure is changing fundamentally
                    $this->db->query("TRUNCATE TABLE alokasi_mapel");
                    $this->db->execute();

                    $this->db->query("ALTER TABLE alokasi_mapel DROP INDEX mapel_tingkat_jurusan, DROP COLUMN tingkat, DROP COLUMN jurusan, ADD COLUMN kelas_id INT(11) NOT NULL AFTER mapel_id");
                    $this->db->execute();

                    $this->db->query("ALTER TABLE alokasi_mapel ADD UNIQUE KEY mapel_kelas (mapel_id, kelas_id)");
                    $this->db->execute();

                    $this->db->query("ALTER TABLE alokasi_mapel ADD CONSTRAINT alokasi_mapel_ibfk_2 FOREIGN KEY (kelas_id) REFERENCES kelas (id) ON DELETE CASCADE");
                    $this->db->execute();
                }
            } catch (Exception $e) {
                // Ignore errors if columns are already dropped or if it runs multiple times
            }
            try {
                // Check if is_locked already exists in jadwal_pelajaran
                $this->db->query("SHOW COLUMNS FROM jadwal_pelajaran LIKE 'is_locked'");
                $res2 = $this->db->resultSet();
                if (count($res2) == 0) {
                    $this->db->query("ALTER TABLE jadwal_pelajaran ADD COLUMN is_locked TINYINT(1) DEFAULT 0 AFTER jam_selesai");
                    $this->db->execute();
                }
            } catch (Exception $e) {
                // Ignore errors
            }
        } catch (Exception $e) {
            error_log("Self-healing encountered a critical error: " . $e->getMessage());
        }
    }

    public function login($username, $password)
    {
        $this->db->query("
            SELECT u.* 
            FROM users u
            LEFT JOIN guru g ON u.id = g.user_id 
            WHERE u.username = :username OR g.no_hp = :username
            LIMIT 1
        ");
        $this->db->bind('username', $username);
        $user = $this->db->single();

        if($user) {
            if(password_verify($password, $user['password'])) {
                return $user;
            }
        }
        return false;
    }
}
