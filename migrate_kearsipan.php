<?php
require_once 'app/init.php';

$db = new Database();

$query = "
CREATE TABLE IF NOT EXISTS kearsipan (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nomor_surat VARCHAR(100) NOT NULL,
    tanggal_surat DATE NOT NULL,
    jenis_surat ENUM('Masuk', 'Keluar') NOT NULL,
    kategori VARCHAR(100) NOT NULL,
    pengirim_penerima VARCHAR(255) NOT NULL,
    perihal TEXT NOT NULL,
    file_surat VARCHAR(255) DEFAULT NULL,
    keterangan TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
";

$db->query($query);
$db->execute();

echo "Tabel kearsipan berhasil dibuat atau sudah ada.\n";
