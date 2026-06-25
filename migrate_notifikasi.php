<?php
require_once 'app/config/config.php';
require_once 'app/core/Database.php';

$db = new Database();

// Table notifikasi
$sql = "CREATE TABLE IF NOT EXISTS notifikasi (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    tipe VARCHAR(50) NOT NULL,
    pesan TEXT NOT NULL,
    link VARCHAR(255) DEFAULT NULL,
    is_read TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
)";
$db->query($sql);
$db->execute();
echo "Table notifikasi created.\n";

echo "Migration Notifikasi finished.\n";
