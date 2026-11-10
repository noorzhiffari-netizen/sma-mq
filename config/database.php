<?php
/**
 * Konfigurasi koneksi database - SMAI MQ
 * Sesuaikan DB_HOST, DB_NAME, DB_USER, DB_PASS dengan lingkungan hosting Anda.
 */

define('DB_HOST', 'localhost');
define('DB_NAME', 'u611937664_smaimq'); // Ganti dengan Nama DB Hostinger Anda
define('DB_USER', 'u611937664_smaimq'); // Ganti dengan User DB Hostinger Anda
define('DB_PASS', 'Mzn230368$'); // Ganti dengan Password DB Hostinger Anda

// Alamat dasar (base URL) situs
if (!defined('BASE_URL')) {
    if (php_sapi_name() === 'cli') {
        define('BASE_URL', '/');
    } else {
        $scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? ''));
        $base = preg_replace('#/admin$#', '', $scriptDir);
        $base = rtrim($base, '/') . '/';
        define('BASE_URL', $base === '' ? '/' : $base);
    }
}

try {
    $pdo = new PDO(
        'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4',
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ]
    );
} catch (PDOException $e) {
    die('Koneksi database gagal. Pastikan konfigurasi di config/database.php sudah benar. Detail: ' . $e->getMessage());
}
