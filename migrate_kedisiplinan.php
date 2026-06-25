<?php
require_once 'app/config/config.php';
require_once 'app/core/Database.php';

$db = new Database();

// Table Kategori Kedisiplinan
$db->query("CREATE TABLE IF NOT EXISTS kategori_kedisiplinan (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    nama_kategori VARCHAR(100) NOT NULL,
    jenis ENUM('Pelanggaran', 'Penghargaan') NOT NULL,
    tingkatan ENUM('Ringan', 'Sedang', 'Berat', 'Prestasi') NOT NULL,
    poin INT(11) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)");
$db->execute();
echo "Table kategori_kedisiplinan created.\n";

// Table Catatan Kedisiplinan
$db->query("CREATE TABLE IF NOT EXISTS catatan_kedisiplinan (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    siswa_id INT(11) NOT NULL,
    kategori_id INT(11) NOT NULL,
    tanggal DATE NOT NULL,
    poin_dicatat INT(11) NOT NULL,
    keterangan TEXT,
    dicatat_oleh INT(11) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (siswa_id) REFERENCES siswa(id) ON DELETE CASCADE,
    FOREIGN KEY (kategori_id) REFERENCES kategori_kedisiplinan(id) ON DELETE CASCADE,
    FOREIGN KEY (dicatat_oleh) REFERENCES users(id) ON DELETE CASCADE
)");
$db->execute();
echo "Table catatan_kedisiplinan created.\n";

// Insert some default categories if empty
$db->query("SELECT COUNT(*) as total FROM kategori_kedisiplinan");
$res = $db->single();
if ($res['total'] == 0) {
    $defaults = [
        ['Datang Terlambat', 'Pelanggaran', 'Ringan', 5],
        ['Membolos', 'Pelanggaran', 'Sedang', 20],
        ['Berkelahi', 'Pelanggaran', 'Berat', 50],
        ['Juara Kelas', 'Penghargaan', 'Prestasi', 30],
        ['Menjadi Petugas Upacara', 'Penghargaan', 'Prestasi', 10],
        ['Berbuat Kebisingan', 'Pelanggaran', 'Ringan', 5]
    ];
    foreach($defaults as $d) {
        $db->query("INSERT INTO kategori_kedisiplinan (nama_kategori, jenis, tingkatan, poin) VALUES (:nama, :jenis, :tingkatan, :poin)");
        $db->bind('nama', $d[0]);
        $db->bind('jenis', $d[1]);
        $db->bind('tingkatan', $d[2]);
        $db->bind('poin', $d[3]);
        $db->execute();
    }
    echo "Default kategori inserted.\n";
}

echo "Migration Kedisiplinan finished.\n";
