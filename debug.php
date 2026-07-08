<?php
require_once 'app/config/config.php';

try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $stmt = $pdo->query("SELECT id, qr_token FROM siswa LIMIT 10");
    $students = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    var_dump($students);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
