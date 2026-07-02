<?php
require_once 'app/config/config.php';
require_once 'app/core/Database.php';

$db = new Database();

// 1. Table spmb_gelombang
$sql1 = "CREATE TABLE IF NOT EXISTS spmb_gelombang (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_gelombang VARCHAR(100) NOT NULL,
    tanggal_mulai DATE NOT NULL,
    tanggal_selesai DATE NOT NULL,
    harga_formulir DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    status ENUM('Buka', 'Tutup') DEFAULT 'Tutup',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";
$db->query($sql1);
$db->execute();
echo "Table spmb_gelombang created.\n";

// 2. Table spmb_peserta
$sql2 = "CREATE TABLE IF NOT EXISTS spmb_peserta (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    gelombang_id INT NOT NULL,
    nisn VARCHAR(20) NOT NULL,
    nama_lengkap VARCHAR(150) NOT NULL,
    asal_sekolah VARCHAR(150) NOT NULL,
    no_hp VARCHAR(20) NOT NULL,
    status_pembayaran ENUM('Belum Bayar', 'Lunas') DEFAULT 'Belum Bayar',
    status_seleksi ENUM('Menunggu', 'Lulus', 'Tidak Lulus') DEFAULT 'Menunggu',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (gelombang_id) REFERENCES spmb_gelombang(id) ON DELETE RESTRICT
)";
$db->query($sql2);
$db->execute();
echo "Table spmb_peserta created.\n";

// 3. Table spmb_pembayaran
$sql3 = "CREATE TABLE IF NOT EXISTS spmb_pembayaran (
    id INT AUTO_INCREMENT PRIMARY KEY,
    peserta_id INT NOT NULL,
    jumlah_bayar DECIMAL(10,2) NOT NULL,
    metode VARCHAR(50) NOT NULL,
    bukti VARCHAR(255) NOT NULL,
    status ENUM('Pending', 'Diterima', 'Ditolak') DEFAULT 'Pending',
    tanggal_bayar TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (peserta_id) REFERENCES spmb_peserta(id) ON DELETE CASCADE
)";
$db->query($sql3);
$db->execute();
echo "Table spmb_pembayaran created.\n";

echo "SPMB Migration finished.\n";
