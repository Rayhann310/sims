<?php
require_once 'app/config/config.php';

try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $stmt = $pdo->query("SELECT id FROM siswa WHERE qr_token IS NULL OR qr_token = ''");
    $students = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $update_stmt = $pdo->prepare("UPDATE siswa SET qr_token = ? WHERE id = ?");
    $count = 0;
    foreach($students as $s) {
        $token = 'STUDENT_' . $s['id'] . '_' . uniqid();
        $update_stmt->execute([$token, $s['id']]);
        $count++;
    }
    echo "Updated " . $count . " students.";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
