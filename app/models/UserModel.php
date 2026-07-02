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
