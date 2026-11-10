<?php
require_once __DIR__ . '/config/database.php';

$sql = "
ALTER TABLE admin ADD COLUMN role ENUM('superadmin', 'ppdb') DEFAULT 'superadmin' AFTER nama_lengkap;
ALTER TABLE profil_sekolah 
  ADD COLUMN logo VARCHAR(255) DEFAULT NULL,
  ADD COLUMN facebook VARCHAR(255) DEFAULT NULL,
  ADD COLUMN instagram VARCHAR(255) DEFAULT NULL,
  ADD COLUMN youtube VARCHAR(255) DEFAULT NULL;

CREATE TABLE prestasi (
    id INT AUTO_INCREMENT PRIMARY KEY,
    judul VARCHAR(200) NOT NULL,
    deskripsi TEXT,
    gambar VARCHAR(255) DEFAULT NULL,
    tanggal_meraih DATE,
    dibuat_pada TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE ekstrakurikuler (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    deskripsi TEXT,
    gambar VARCHAR(255) DEFAULT NULL,
    jadwal VARCHAR(100),
    dibuat_pada TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE pengumuman (
    id INT AUTO_INCREMENT PRIMARY KEY,
    judul VARCHAR(200) NOT NULL,
    isi TEXT NOT NULL,
    dibuat_pada TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE unduhan (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_file VARCHAR(200) NOT NULL,
    keterangan VARCHAR(255),
    path_file VARCHAR(255) NOT NULL,
    dibuat_pada TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE alumni (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_lengkap VARCHAR(150) NOT NULL,
    tahun_lulus YEAR NOT NULL,
    pekerjaan_kuliah VARCHAR(200),
    testimoni TEXT,
    foto VARCHAR(255) DEFAULT NULL,
    status ENUM('draft', 'terbit') DEFAULT 'draft',
    dibuat_pada TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;
";

try {
    $pdo->exec($sql);
    echo "Migration successful!\n";
} catch (PDOException $e) {
    echo "Migration failed: " . $e->getMessage() . "\n";
}
