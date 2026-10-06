<?php
// Script untuk dijalankan lewat CLI atau di-include untuk migrasi database e-rapor.
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/../core/Database.php';

// Mock $_ENV just in case
if(!isset($_ENV['DB_HOST'])) {
    // try to load .env manually if needed or it will use defaults in config.php
}

$db = new Database();

$queries = [
    // Kelompok Mata Pelajaran (untuk pengelompokan di rapor)
    "CREATE TABLE IF NOT EXISTS `kelompok_mapel` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `nama_kelompok` varchar(150) NOT NULL,
      `urutan` int(11) DEFAULT 1,
      PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;",

    // Tambah kolom ke mata_pelajaran
    "ALTER TABLE `mata_pelajaran`
      ADD COLUMN IF NOT EXISTS `kelompok_id` int(11) DEFAULT NULL,
      ADD COLUMN IF NOT EXISTS `kkm` int(11) DEFAULT 75,
      ADD COLUMN IF NOT EXISTS `urutan` int(11) DEFAULT 1;",

    // Nilai Rapor Akhir
    "CREATE TABLE IF NOT EXISTS `nilai_rapor` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `siswa_id` int(11) NOT NULL,
      `mapel_id` int(11) NOT NULL,
      `guru_id` int(11) DEFAULT NULL,
      `tahun_akademik_id` int(11) NOT NULL,
      `nilai` decimal(5,2) DEFAULT NULL,
      `sikap` enum('A','B','C','D','E') DEFAULT NULL,
      `validasi` tinyint(1) DEFAULT 0,
      `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
      `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
      PRIMARY KEY (`id`),
      UNIQUE KEY `unique_nilai_rapor` (`siswa_id`,`mapel_id`,`tahun_akademik_id`),
      KEY `siswa_id` (`siswa_id`),
      KEY `mapel_id` (`mapel_id`),
      KEY `tahun_akademik_id` (`tahun_akademik_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;",

    // Nilai Ekstrakulikuler
    "CREATE TABLE IF NOT EXISTS `nilai_ekskul` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `siswa_id` int(11) NOT NULL,
      `tahun_akademik_id` int(11) NOT NULL,
      `nama_ekskul` varchar(150) NOT NULL,
      `nilai` varchar(50) DEFAULT NULL,
      `keterangan` varchar(255) DEFAULT NULL,
      PRIMARY KEY (`id`),
      KEY `siswa_id` (`siswa_id`),
      KEY `tahun_akademik_id` (`tahun_akademik_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;",

    // Absensi Semester (sakit/izin/alfa total per semester)
    "CREATE TABLE IF NOT EXISTS `absensi_rapor` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `siswa_id` int(11) NOT NULL,
      `tahun_akademik_id` int(11) NOT NULL,
      `sakit` int(11) DEFAULT 0,
      `izin` int(11) DEFAULT 0,
      `alfa` int(11) DEFAULT 0,
      `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
      PRIMARY KEY (`id`),
      UNIQUE KEY `unique_absensi_rapor` (`siswa_id`,`tahun_akademik_id`),
      KEY `siswa_id` (`siswa_id`),
      KEY `tahun_akademik_id` (`tahun_akademik_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;",

    // Catatan Wali Kelas per Siswa
    "CREATE TABLE IF NOT EXISTS `catatan_wali` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `siswa_id` int(11) NOT NULL,
      `tahun_akademik_id` int(11) NOT NULL,
      `catatan` text DEFAULT NULL,
      `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
      PRIMARY KEY (`id`),
      UNIQUE KEY `unique_catatan_wali` (`siswa_id`,`tahun_akademik_id`),
      KEY `siswa_id` (`siswa_id`),
      KEY `tahun_akademik_id` (`tahun_akademik_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;",
    
    // Seed Kelompok Mapel
    "INSERT IGNORE INTO `kelompok_mapel` (`id`, `nama_kelompok`, `urutan`) VALUES
    (1, 'Kelompok A (Wajib)', 1),
    (2, 'Kelompok B (Wajib)', 2),
    (3, 'Kelompok C (Peminatan)', 3),
    (4, 'Lintas Minat', 4);"
];

foreach ($queries as $sql) {
    try {
        $db->exec($sql);
        echo "Sukses eksekusi query.\n";
    } catch (Exception $e) {
        echo "Gagal: " . $e->getMessage() . "\n";
    }
}

echo "Migrasi database untuk E-Rapor selesai!\n";
