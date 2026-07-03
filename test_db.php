<?php
$db = new PDO('mysql:host=localhost;dbname=db_smanw', 'root', '');
$tables = ['siswa', 'rombel', 'anggota_rombel', 'kelas'];
foreach($tables as $table) {
    echo "TABLE: $table\n";
    $stmt = $db->query("DESCRIBE $table");
    print_r($stmt->fetchAll(PDO::FETCH_ASSOC));
    echo "\n";
}
