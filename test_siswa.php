<?php
require 'app/config/config.php';
require 'app/core/Database.php';
require 'app/models/SiswaModel.php';

$sm = new SiswaModel();
$siswa = $sm->getAllSiswa();
$count = count($siswa);
$hasToken = 0;
foreach($siswa as $s) {
    if(!empty($s['qr_token'])) {
        $hasToken++;
    }
}
echo "Total Siswa: $count, Memiliki Token: $hasToken\n";

if($count > 0) {
    echo "Siswa pertama:\n";
    print_r($siswa[0]);
}
