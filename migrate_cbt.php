<?php
require_once 'app/config/config.php';

try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // 1. Table Bank Soal
    $sql_bank_soal = "CREATE TABLE IF NOT EXISTS cbt_bank_soal (
        id_soal INT AUTO_INCREMENT PRIMARY KEY,
        id_mapel INT NOT NULL,
        id_guru INT NOT NULL,
        tipe_soal VARCHAR(20) NOT NULL DEFAULT 'PG', -- PG, PG_KOMPLEKS, ESSAY
        pertanyaan TEXT NOT NULL,
        file_media VARCHAR(255) NULL, -- Gambar/Audio soal
        opsi_a TEXT NULL,
        opsi_b TEXT NULL,
        opsi_c TEXT NULL,
        opsi_d TEXT NULL,
        opsi_e TEXT NULL,
        kunci_jawaban VARCHAR(255) NOT NULL,
        tingkat_kesulitan ENUM('Mudah', 'Sedang', 'Sulit') DEFAULT 'Sedang',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    $pdo->exec($sql_bank_soal);
    echo "Tabel cbt_bank_soal berhasil dibuat.<br>\n";

    // 2. Table Jadwal Ujian (dengan id_guru_pengawas dan token)
    $sql_jadwal = "CREATE TABLE IF NOT EXISTS cbt_jadwal (
        id_jadwal INT AUTO_INCREMENT PRIMARY KEY,
        nama_ujian VARCHAR(100) NOT NULL,
        id_mapel INT NOT NULL,
        waktu_mulai DATETIME NOT NULL,
        waktu_selesai DATETIME NOT NULL,
        durasi_menit INT NOT NULL,
        id_guru_pengawas INT NOT NULL, -- Pengawas yang berhak unlock
        token_aktif VARCHAR(10) NULL,
        token_last_update TIMESTAMP NULL,
        status ENUM('Draft', 'Aktif', 'Selesai') DEFAULT 'Draft',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    $pdo->exec($sql_jadwal);
    echo "Tabel cbt_jadwal berhasil dibuat.<br>\n";

    // 3. Table Peserta Ujian (termasuk status pelanggaran)
    $sql_peserta = "CREATE TABLE IF NOT EXISTS cbt_peserta (
        id_peserta INT AUTO_INCREMENT PRIMARY KEY,
        id_jadwal INT NOT NULL,
        id_siswa INT NOT NULL,
        waktu_mulai DATETIME NULL,
        sisa_waktu_detik INT NULL,
        status_ujian ENUM('0', '1', '2', '3') DEFAULT '0', -- 0:belum, 1:mengerjakan, 2:TERKUNCI, 3:selesai
        alasan_terkunci VARCHAR(255) NULL,
        nilai FLOAT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    $pdo->exec($sql_peserta);
    echo "Tabel cbt_peserta berhasil dibuat.<br>\n";

    // 4. Table Jawaban Siswa
    $sql_jawaban = "CREATE TABLE IF NOT EXISTS cbt_jawaban (
        id_jawaban INT AUTO_INCREMENT PRIMARY KEY,
        id_peserta INT NOT NULL,
        id_soal INT NOT NULL,
        jawaban_siswa TEXT NULL,
        ragu_ragu TINYINT(1) DEFAULT 0,
        skor FLOAT DEFAULT 0,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )";
    $pdo->exec($sql_jawaban);
    echo "Tabel cbt_jawaban berhasil dibuat.<br>\n";

    echo "<br><b>Proses Migrasi Database CBT Selesai!</b>";

} catch (PDOException $e) {
    die("Database Error: " . $e->getMessage());
}
