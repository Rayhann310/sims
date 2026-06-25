<?php
require_once 'app/config/config.php';
require_once 'app/core/Database.php';

$db = new Database();

// 1. Table elearning_materi
$sql1 = "CREATE TABLE IF NOT EXISTS elearning_materi (
    id INT AUTO_INCREMENT PRIMARY KEY,
    jadwal_id INT NOT NULL,
    judul VARCHAR(255) NOT NULL,
    deskripsi TEXT,
    file_path VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (jadwal_id) REFERENCES jadwal_pelajaran(id) ON DELETE CASCADE
)";
$db->query($sql1);
$db->execute();
echo "Table elearning_materi created.\n";

// 2. Table elearning_tugas
$sql2 = "CREATE TABLE IF NOT EXISTS elearning_tugas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    jadwal_id INT NOT NULL,
    judul VARCHAR(255) NOT NULL,
    deskripsi TEXT,
    tenggat_waktu DATETIME NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (jadwal_id) REFERENCES jadwal_pelajaran(id) ON DELETE CASCADE
)";
$db->query($sql2);
$db->execute();
echo "Table elearning_tugas created.\n";

// 3. Table elearning_pengumpulan
$sql3 = "CREATE TABLE IF NOT EXISTS elearning_pengumpulan (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tugas_id INT NOT NULL,
    siswa_id INT NOT NULL,
    file_path VARCHAR(255) NOT NULL,
    nilai INT DEFAULT NULL,
    waktu_kumpul TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (tugas_id) REFERENCES elearning_tugas(id) ON DELETE CASCADE,
    FOREIGN KEY (siswa_id) REFERENCES siswa(id) ON DELETE CASCADE
)";
$db->query($sql3);
$db->execute();
echo "Table elearning_pengumpulan created.\n";

echo "Migration finished.\n";
