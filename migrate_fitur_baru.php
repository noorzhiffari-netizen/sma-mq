<?php
require_once __DIR__ . '/config/database.php';

$sql = "
CREATE TABLE IF NOT EXISTS kalender_akademik (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tanggal_mulai DATE NOT NULL,
    tanggal_selesai DATE,
    nama_kegiatan VARCHAR(255) NOT NULL,
    keterangan TEXT,
    dibuat_pada TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS karya_siswa (
    id INT AUTO_INCREMENT PRIMARY KEY,
    judul VARCHAR(200) NOT NULL,
    slug VARCHAR(255) NOT NULL UNIQUE,
    penulis VARCHAR(100) NOT NULL,
    kelas VARCHAR(50),
    kategori VARCHAR(100) DEFAULT 'Umum',
    konten TEXT NOT NULL,
    gambar VARCHAR(255) DEFAULT NULL,
    status ENUM('draft','terbit') DEFAULT 'terbit',
    tanggal DATE NOT NULL,
    dibuat_pada TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;
";

try {
    $pdo->exec($sql);
    echo "Migrasi fitur Kalender Akademik dan Karya Siswa berhasil!\n";
} catch (PDOException $e) {
    echo "Gagal melakukan migrasi: " . $e->getMessage() . "\n";
}
