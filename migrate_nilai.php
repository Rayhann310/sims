<?php
require_once 'app/config/config.php';
require_once 'app/core/Database.php';

$db = new Database();

// 1. Table presensi_siswa
$sql1 = "CREATE TABLE IF NOT EXISTS presensi_siswa (
    id INT AUTO_INCREMENT PRIMARY KEY,
    jadwal_id INT NOT NULL,
    tanggal DATE NOT NULL,
    siswa_id INT NOT NULL,
    status ENUM('Hadir', 'Izin', 'Sakit', 'Alpa') DEFAULT 'Hadir',
    keterangan VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (jadwal_id) REFERENCES jadwal_pelajaran(id) ON DELETE CASCADE,
    FOREIGN KEY (siswa_id) REFERENCES siswa(id) ON DELETE CASCADE
)";
$db->query($sql1);
$db->execute();
echo "Table presensi_siswa created.\n";

// 2. Table nilai_siswa
$sql2 = "CREATE TABLE IF NOT EXISTS nilai_siswa (
    id INT AUTO_INCREMENT PRIMARY KEY,
    jadwal_id INT NOT NULL,
    siswa_id INT NOT NULL,
    jenis_nilai VARCHAR(50) NOT NULL, -- (e.g. Tugas 1, UTS, UAS, dll)
    nilai DECIMAL(5,2) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (jadwal_id) REFERENCES jadwal_pelajaran(id) ON DELETE CASCADE,
    FOREIGN KEY (siswa_id) REFERENCES siswa(id) ON DELETE CASCADE
)";
$db->query($sql2);
$db->execute();
echo "Table nilai_siswa created.\n";

echo "Migration Phase 3 finished.\n";
