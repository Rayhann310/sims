<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/../core/Database.php';

$db = new Database();
$menus = [
    ['erapor_input_nilai', 'Input Nilai Rapor', 1, 1],
    ['erapor_ekskul', 'Input Ekskul', 1, 1],
    ['erapor_cetak', 'Cetak Rapor', 1, 1],
    ['erapor', 'Menu E-Rapor (Global)', 1, 1]
];

foreach ($menus as $m) {
    $db->query("INSERT IGNORE INTO hak_akses_menu (menu_key, nama_menu, jabatan_id, is_active) VALUES (:key, :nama, :jabatan, :aktif)");
    $db->bind('key', $m[0]);
    $db->bind('nama', $m[1]);
    $db->bind('jabatan', $m[2]);
    $db->bind('aktif', $m[3]);
    try {
        $db->execute();
        echo "Inserted " . $m[0] . "\n";
    } catch(Exception $e) {
        echo "Error " . $m[0] . "\n";
    }
}
echo "Done";
