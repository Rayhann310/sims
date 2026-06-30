<?php
require_once 'app/config/config.php';
try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
    // Check if column exists first
    $stmt = $pdo->query("SHOW COLUMNS FROM pengaturan LIKE 'fonnte_token'");
    if($stmt->rowCount() == 0) {
        $pdo->exec("ALTER TABLE pengaturan ADD COLUMN fonnte_token VARCHAR(255) NULL DEFAULT NULL AFTER logo_sekolah");
        echo "Column added successfully";
    } else {
        echo "Column already exists";
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
