<?php
$host = 'localhost';
$dbname = 'db_mahasiswa';
$dbuser = 'root';
$dbpass = ''; // Sesuaikan jika MySQL Anda memakai password.

try {
    $pdo = new PDO(
        "mysql:host={$host};dbname={$dbname};charset=utf8mb4",
        $dbuser,
        $dbpass,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false
        ]
    );
} catch (PDOException $e) {
    exit('Koneksi database gagal. Periksa konfigurasi di config/database.php dan pastikan MySQL aktif.');
}
