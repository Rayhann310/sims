<?php
require 'app/config/config.php';
require 'app/core/Database.php';

$db = new Database();
$rombel_id = 14;
$db->query("
    SELECT s.*, u.nama_lengkap 
    FROM siswa s
    JOIN anggota_rombel ar ON s.id = ar.siswa_id
    JOIN users u ON s.user_id = u.id
    WHERE ar.rombel_id = :rombel_id
    ORDER BY u.nama_lengkap ASC
");
$db->bind('rombel_id', $rombel_id);
print_r($db->resultSet());

$db->query("SELECT s.*, u.nama_lengkap, r.nama_rombel as nama_kelas, r.id as id_kelas, r.wali_kelas_id FROM siswa s JOIN anggota_rombel ar ON s.id = ar.siswa_id JOIN rombel r ON ar.rombel_id = r.id JOIN users u ON s.user_id = u.id WHERE s.id = 1 LIMIT 1");
print_r($db->single());
