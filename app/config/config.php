<?php

// Deteksi Protocol (Bekerja untuk HTTP biasa, HTTPS, dan Reverse Proxy seperti Localtunnel/Ngrok)
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
if (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') {
    $protocol = 'https';
}

// Deteksi Hostname dinamis
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';

// Deteksi Script path untuk mendapatkan root direktori dinamis
$scriptPath = dirname($_SERVER['SCRIPT_NAME']);
// Hapus karakter backslash jika dijalankan di environment Windows root
$scriptPath = str_replace('\\', '/', $scriptPath);
// Hilangkan '/public' dari path untuk mencegah muncul di URL
$scriptPath = str_replace('/public', '', $scriptPath);
$scriptPath = rtrim($scriptPath, '/');

// Gabungkan menjadi Base URL yang dinamis
$baseurl = $protocol . '://' . $host . $scriptPath;

define('BASEURL', $baseurl);

// DB Constants
define('DB_HOST', $_ENV['DB_HOST'] ?? 'localhost');
define('DB_USER', $_ENV['DB_USER'] ?? 'root');
define('DB_PASS', $_ENV['DB_PASS'] ?? '');
define('DB_NAME', $_ENV['DB_NAME'] ?? 'db_smanw');
