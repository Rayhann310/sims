<?php

/**
 * SelfHealingMigrator
 * 
 * Secara otomatis mengecek dan menambahkan tabel/kolom yang dibutuhkan
 * sistem E-Rapor. Aman dijalankan berulang kali (idempotent).
 * 
 * Dipanggil sekali di app/init.php setelah Database class di-load.
 */
class SelfHealingMigrator
{
    private $pdo;
    private $db_name;

    public function __construct()
    {
        // Ambil koneksi PDO langsung tanpa instantiate model
        $host    = $_ENV['DB_HOST']    ?? 'localhost';
        $dbname  = $_ENV['DB_NAME']    ?? '';
        $user    = $_ENV['DB_USER']    ?? 'root';
        $pass    = $_ENV['DB_PASS']    ?? '';
        $this->db_name = $dbname;

        try {
            $this->pdo = new PDO(
                "mysql:host={$host};dbname={$dbname};charset=utf8mb4",
                $user, $pass,
                [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
            );
        } catch (Exception $e) {
            // Jika DB belum ada, skip — jangan crash aplikasi
            $this->pdo = null;
        }
    }

    public function run()
    {
        if (!$this->pdo) return;

        $this->ensureTables();
        $this->ensureColumns();
    }

    // ---------------------------------------------------------------
    // TABEL: buat jika belum ada
    // ---------------------------------------------------------------
    private function ensureTables()
    {
        $tables = [
            'kelompok_mapel' => "CREATE TABLE IF NOT EXISTS `kelompok_mapel` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `nama_kelompok` varchar(150) NOT NULL,
                `urutan` int(11) DEFAULT 1,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",

            'nilai_rapor' => "CREATE TABLE IF NOT EXISTS `nilai_rapor` (
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

            'nilai_ekskul' => "CREATE TABLE IF NOT EXISTS `nilai_ekskul` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `siswa_id` int(11) NOT NULL,
                `tahun_akademik_id` int(11) NOT NULL,
                `nama_ekskul` varchar(150) NOT NULL,
                `nilai` varchar(50) DEFAULT NULL,
                `keterangan` varchar(255) DEFAULT NULL,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",

            'absensi_rapor' => "CREATE TABLE IF NOT EXISTS `absensi_rapor` (
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

            'catatan_wali' => "CREATE TABLE IF NOT EXISTS `catatan_wali` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `siswa_id` int(11) NOT NULL,
                `tahun_akademik_id` int(11) NOT NULL,
                `catatan` text DEFAULT NULL,
                `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
                PRIMARY KEY (`id`),
                UNIQUE KEY `unique_catatan_wali` (`siswa_id`,`tahun_akademik_id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",

            'master_ekskul' => "CREATE TABLE IF NOT EXISTS `master_ekskul` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `nama_ekskul` varchar(150) NOT NULL,
                `pembina` varchar(100) DEFAULT NULL,
                `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4",
        ];

        foreach ($tables as $table => $sql) {
            try {
                $this->pdo->exec($sql);
            } catch (Exception $e) {
                // log silently — jangan crash
                error_log("[SelfHealing] Tabel {$table}: " . $e->getMessage());
            }
        }

        // Seed kelompok_mapel default jika kosong
        $count = $this->pdo->query("SELECT COUNT(*) FROM kelompok_mapel")->fetchColumn();
        if ($count == 0) {
            $this->pdo->exec("INSERT INTO `kelompok_mapel` (`id`,`nama_kelompok`,`urutan`) VALUES
                (1,'Kelompok A (Wajib)',1),
                (2,'Kelompok B (Wajib)',2),
                (3,'Kelompok C (Peminatan)',3),
                (4,'Lintas Minat',4)
            ");
        }
    }

    // ---------------------------------------------------------------
    // KOLOM: tambahkan kolom yang belum ada di tabel existing
    // ---------------------------------------------------------------
    private function ensureColumns()
    {
        $required_columns = [
            // [table, column, definition]
            ['mata_pelajaran', 'kelompok_id', 'int(11) DEFAULT NULL'],
            ['mata_pelajaran', 'kkm',         'int(11) DEFAULT 75'],
            ['mata_pelajaran', 'urutan',       'int(11) DEFAULT 1'],
            ['guru',           'nama_lengkap', 'varchar(100) DEFAULT NULL'],
        ];

        foreach ($required_columns as [$table, $column, $definition]) {
            if (!$this->columnExists($table, $column)) {
                try {
                    $this->pdo->exec("ALTER TABLE `{$table}` ADD COLUMN `{$column}` {$definition}");
                    error_log("[SelfHealing] Tambah kolom {$table}.{$column} berhasil.");
                } catch (Exception $e) {
                    error_log("[SelfHealing] Kolom {$table}.{$column}: " . $e->getMessage());
                }
            }
        }
    }

    private function columnExists($table, $column)
    {
        $stmt = $this->pdo->prepare("
            SELECT COUNT(*) FROM information_schema.COLUMNS
            WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ? AND COLUMN_NAME = ?
        ");
        $stmt->execute([$this->db_name, $table, $column]);
        return $stmt->fetchColumn() > 0;
    }
}
