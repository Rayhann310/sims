<?php
/**
 * ONE-TIME MIGRATION RUNNER
 * Akses: /run_migration.php?token=SMANW_MIGRATE_2026
 * HAPUS FILE INI setelah migration berhasil!
 */

define('SECRET_TOKEN', 'SMANW_MIGRATE_2026');

if (!isset($_GET['token']) || $_GET['token'] !== SECRET_TOKEN) {
    http_response_code(403);
    die('403 Forbidden');
}

// Bootstrap
define('BASEPATH', __DIR__);
require_once __DIR__ . '/app/config/config.php';
require_once __DIR__ . '/app/core/Database.php';

$db = new Database();

$queries = [
    // Tabel kelompok mata pelajaran
    "CREATE TABLE IF NOT EXISTS `kelompok_mapel` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `nama_kelompok` varchar(150) NOT NULL,
        `urutan` int(11) DEFAULT 1,
        PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",

    // Tambah kolom ke mata_pelajaran (IF NOT EXISTS agar aman dijalankan ulang)
    "ALTER TABLE `mata_pelajaran`
        ADD COLUMN IF NOT EXISTS `kelompok_id` int(11) DEFAULT NULL,
        ADD COLUMN IF NOT EXISTS `kkm` int(11) DEFAULT 75,
        ADD COLUMN IF NOT EXISTS `urutan` int(11) DEFAULT 1",

    // Tabel nilai rapor
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
        UNIQUE KEY `unique_nilai_rapor` (`siswa_id`,`mapel_id`,`tahun_akademik_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",

    // Tabel ekskul
    "CREATE TABLE IF NOT EXISTS `nilai_ekskul` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `siswa_id` int(11) NOT NULL,
        `tahun_akademik_id` int(11) NOT NULL,
        `nama_ekskul` varchar(150) NOT NULL,
        `nilai` varchar(50) DEFAULT NULL,
        `keterangan` varchar(255) DEFAULT NULL,
        PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",

    // Tabel absensi rapor
    "CREATE TABLE IF NOT EXISTS `absensi_rapor` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `siswa_id` int(11) NOT NULL,
        `tahun_akademik_id` int(11) NOT NULL,
        `sakit` int(11) DEFAULT 0,
        `izin` int(11) DEFAULT 0,
        `alfa` int(11) DEFAULT 0,
        `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
        PRIMARY KEY (`id`),
        UNIQUE KEY `unique_absensi_rapor` (`siswa_id`,`tahun_akademik_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",

    // Tabel catatan wali
    "CREATE TABLE IF NOT EXISTS `catatan_wali` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `siswa_id` int(11) NOT NULL,
        `tahun_akademik_id` int(11) NOT NULL,
        `catatan` text DEFAULT NULL,
        `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
        PRIMARY KEY (`id`),
        UNIQUE KEY `unique_catatan_wali` (`siswa_id`,`tahun_akademik_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",

    // Seed kelompok mapel
    "INSERT IGNORE INTO `kelompok_mapel` (`id`, `nama_kelompok`, `urutan`) VALUES
        (1, 'Kelompok A (Wajib)', 1),
        (2, 'Kelompok B (Wajib)', 2),
        (3, 'Kelompok C (Peminatan)', 3),
        (4, 'Lintas Minat', 4)",

    // Master Ekskul
    "CREATE TABLE IF NOT EXISTS `master_ekskul` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `nama_ekskul` varchar(150) NOT NULL,
        `pembina` varchar(100) DEFAULT NULL,
        `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
        PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",
];

$results = [];
foreach ($queries as $i => $sql) {
    try {
        $db->exec($sql);
        $results[] = ['status' => 'âœ… OK', 'query' => substr(trim($sql), 0, 80) . '...'];
    } catch (Exception $e) {
        $results[] = ['status' => 'âŒ GAGAL', 'query' => substr(trim($sql), 0, 80) . '...', 'error' => $e->getMessage()];
    }
}
?>
<!DOCTYPE html>
<html>
<head><meta charset="utf-8"><title>Migration Runner</title>
<style>body{font-family:monospace;padding:20px;background:#1a1a2e;color:#eee} .ok{color:#00ff88} .err{color:#ff4757} table{width:100%;border-collapse:collapse} td,th{padding:10px;border:1px solid #333;text-align:left} th{background:#16213e}</style>
</head>
<body>
<h2>ðŸš€ E-Rapor Migration Runner</h2>
<p style="color:#ffa502">âš ï¸ HAPUS FILE INI SETELAH SELESAI: <code>run_migration.php</code></p>
<table>
<tr><th>#</th><th>Status</th><th>Query</th><th>Error</th></tr>
<?php foreach ($results as $i => $r): ?>
<tr>
    <td><?= $i+1 ?></td>
    <td class="<?= $r['status'][0] === 'âœ…' ? 'ok' : 'err' ?>"><?= $r['status'] ?></td>
    <td><?= htmlspecialchars($r['query']) ?></td>
    <td><?= htmlspecialchars($r['error'] ?? '-') ?></td>
</tr>
<?php endforeach; ?>
</table>
<br>
<p class="ok">âœ… Migration selesai! Silakan hapus file <strong>run_migration.php</strong> dari server.</p>
</body>
</html>
