<?php
require_once 'app/config/config.php';
require_once 'app/core/Database.php';

$db = new Database();

$db->query("CREATE TABLE IF NOT EXISTS pengaturan (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    nama_aplikasi VARCHAR(100) NOT NULL,
    logo_teks VARCHAR(10) NOT NULL,
    teks_footer VARCHAR(255) NOT NULL,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)");
$db->execute();
echo "Table pengaturan created.\n";

$db->query("SELECT COUNT(*) as total FROM pengaturan");
$res = $db->single();
if ($res['total'] == 0) {
    $db->query("INSERT INTO pengaturan (nama_aplikasi, logo_teks, teks_footer) VALUES (:nama, :logo, :footer)");
    $db->bind('nama', 'Narasui');
    $db->bind('logo', 'N');
    $db->bind('footer', '&copy; ' . date('Y') . ' SMA Nahdlatul Wathan Jakarta. All rights reserved.');
    $db->execute();
    echo "Default data inserted.\n";
}

echo "Migration Pengaturan finished.\n";
