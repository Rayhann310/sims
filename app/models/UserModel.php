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
        $queries = [
            "CREATE TABLE IF NOT EXISTS users (
                id INT AUTO_INCREMENT PRIMARY KEY,
                username VARCHAR(50) NOT NULL UNIQUE,
                password VARCHAR(255) NOT NULL,
                role ENUM('admin', 'guru', 'siswa') NOT NULL DEFAULT 'siswa',
                nama_lengkap VARCHAR(100) NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )",
            "CREATE TABLE IF NOT EXISTS guru (
                id INT AUTO_INCREMENT PRIMARY KEY,
                user_id INT NOT NULL,
                nip VARCHAR(25) UNIQUE NOT NULL,
                jenis_kelamin ENUM('L', 'P') NOT NULL,
                tanggal_lahir DATE NULL,
                mata_pelajaran VARCHAR(100) NULL,
                nomor_telepon VARCHAR(15),
                alamat TEXT,
                FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
            )",
            "CREATE TABLE IF NOT EXISTS siswa (
                id INT AUTO_INCREMENT PRIMARY KEY,
                user_id INT NOT NULL,
                nisn VARCHAR(20) UNIQUE NOT NULL,
                jenis_kelamin ENUM('L', 'P') DEFAULT 'L',
                tanggal_lahir DATE NULL,
                alamat TEXT NULL,
                nama_wali VARCHAR(100) NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
            )",
            "CREATE TABLE IF NOT EXISTS tahun_akademik (
                id INT AUTO_INCREMENT PRIMARY KEY,
                nama_tahun VARCHAR(20) NOT NULL,
                semester ENUM('Ganjil', 'Genap') NOT NULL,
                status ENUM('Aktif', 'Tidak Aktif') DEFAULT 'Tidak Aktif',
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )",
            "CREATE TABLE IF NOT EXISTS mata_pelajaran (
                id INT AUTO_INCREMENT PRIMARY KEY,
                kode_mapel VARCHAR(20) UNIQUE NOT NULL,
                nama_mapel VARCHAR(100) NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )",
            "CREATE TABLE IF NOT EXISTS kelas (
                id INT AUTO_INCREMENT PRIMARY KEY,
                nama_kelas VARCHAR(20) NOT NULL,
                jurusan VARCHAR(50) NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )",
            "CREATE TABLE IF NOT EXISTS rombel (
                id INT AUTO_INCREMENT PRIMARY KEY,
                tahun_akademik_id INT NOT NULL,
                kelas_id INT NOT NULL,
                nama_rombel VARCHAR(50) NOT NULL,
                wali_kelas_id INT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (tahun_akademik_id) REFERENCES tahun_akademik(id) ON DELETE CASCADE,
                FOREIGN KEY (kelas_id) REFERENCES kelas(id) ON DELETE CASCADE,
                FOREIGN KEY (wali_kelas_id) REFERENCES guru(id) ON DELETE SET NULL
            )",
            "CREATE TABLE IF NOT EXISTS anggota_rombel (
                id INT AUTO_INCREMENT PRIMARY KEY,
                rombel_id INT NOT NULL,
                siswa_id INT NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (rombel_id) REFERENCES rombel(id) ON DELETE CASCADE,
                FOREIGN KEY (siswa_id) REFERENCES siswa(id) ON DELETE CASCADE
            )",
            "CREATE TABLE IF NOT EXISTS jadwal_pelajaran (
                id INT AUTO_INCREMENT PRIMARY KEY,
                rombel_id INT NOT NULL,
                mapel_id INT NOT NULL,
                guru_id INT NOT NULL,
                hari VARCHAR(20) NOT NULL,
                jam_mulai TIME NOT NULL,
                jam_selesai TIME NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (rombel_id) REFERENCES rombel(id) ON DELETE CASCADE,
                FOREIGN KEY (mapel_id) REFERENCES mata_pelajaran(id) ON DELETE CASCADE,
                FOREIGN KEY (guru_id) REFERENCES guru(id) ON DELETE CASCADE
            )"
        ];

        foreach($queries as $q) {
            $this->db->query($q);
            $this->db->execute();
        }

        // SELF-HEALING: Cek jika belum ada admin, buat otomatis
        $this->db->query("SELECT id FROM users WHERE role = 'admin'");
        $this->db->execute();
        
        if($this->db->rowCount() == 0) {
            // Insert default admin (username: admin, password: admin123)
            $password = password_hash('admin123', PASSWORD_DEFAULT);
            $this->db->query("INSERT INTO users (username, password, role, nama_lengkap) VALUES ('admin', :password, 'admin', 'Administrator Sistem')");
            $this->db->bind('password', $password);
            $this->db->execute();
        }
    }

    public function login($username, $password)
    {
        $this->db->query("SELECT * FROM users WHERE username = :username");
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
