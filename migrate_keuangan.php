<?php
require_once 'app/config/config.php';
require_once 'app/core/Database.php';

$db = new Database();

// 1. Table tagihan_spp
$sql1 = "CREATE TABLE IF NOT EXISTS tagihan_spp (
    id INT AUTO_INCREMENT PRIMARY KEY,
    siswa_id INT NOT NULL,
    bulan VARCHAR(20) NOT NULL,
    tahun INT NOT NULL,
    nominal DECIMAL(10,2) NOT NULL,
    status ENUM('Belum Lunas', 'Lunas') DEFAULT 'Belum Lunas',
    jatuh_tempo DATE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (siswa_id) REFERENCES siswa(id) ON DELETE CASCADE
)";
$db->query($sql1);
$db->execute();
echo "Table tagihan_spp created.\n";

// 2. Table pembayaran_spp
$sql2 = "CREATE TABLE IF NOT EXISTS pembayaran_spp (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tagihan_id INT NOT NULL,
    tanggal_bayar DATE NOT NULL,
    jumlah_bayar DECIMAL(10,2) NOT NULL,
    metode VARCHAR(50) DEFAULT 'Cash',
    keterangan TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (tagihan_id) REFERENCES tagihan_spp(id) ON DELETE CASCADE
)";
$db->query($sql2);
$db->execute();
echo "Table pembayaran_spp created.\n";

echo "Migration Keuangan finished.\n";
