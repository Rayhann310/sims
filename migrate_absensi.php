<?php
require_once 'app/config/config.php';

try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // 1. Create absensi_guru
    $pdo->exec("CREATE TABLE IF NOT EXISTS absensi_guru (
        id INT(11) AUTO_INCREMENT PRIMARY KEY,
        guru_id INT(11) NOT NULL,
        tanggal DATE NOT NULL,
        waktu_masuk TIME NOT NULL,
        waktu_pulang TIME NULL DEFAULT NULL,
        status ENUM('Hadir','Sakit','Izin','Dinas Luar','Alpa') NOT NULL DEFAULT 'Hadir',
        sync_status BOOLEAN DEFAULT 1
    )");
    echo "Table absensi_guru created or already exists.\n";

    // 2. Create absensi_siswa
    $pdo->exec("CREATE TABLE IF NOT EXISTS absensi_siswa (
        id INT(11) AUTO_INCREMENT PRIMARY KEY,
        siswa_id INT(11) NOT NULL,
        tanggal DATE NOT NULL,
        waktu_scan TIME NOT NULL,
        status ENUM('Hadir','Sakit','Izin','Alpa') NOT NULL DEFAULT 'Hadir',
        sync_status BOOLEAN DEFAULT 1
    )");
    echo "Table absensi_siswa created or already exists.\n";

    // 3. Add qr_token to siswa
    $stmt = $pdo->query("SHOW COLUMNS FROM siswa LIKE 'qr_token'");
    if($stmt->rowCount() == 0) {
        $pdo->exec("ALTER TABLE siswa ADD COLUMN qr_token VARCHAR(255) NULL DEFAULT NULL");
        echo "Column qr_token added to siswa successfully.\n";
        
        // Generate QR token for existing students
        $stmt_students = $pdo->query("SELECT id FROM siswa");
        $students = $stmt_students->fetchAll(PDO::FETCH_ASSOC);
        
        $update_stmt = $pdo->prepare("UPDATE siswa SET qr_token = ? WHERE id = ?");
        foreach($students as $s) {
            $token = 'STUDENT_' . $s['id'] . '_' . uniqid();
            $update_stmt->execute([$token, $s['id']]);
        }
        echo "Generated qr_token for existing students.\n";
    } else {
        echo "Column qr_token already exists in siswa.\n";
    }

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
