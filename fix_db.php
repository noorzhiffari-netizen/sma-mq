<?php
require_once __DIR__ . '/config/database.php';

$sql = "
ALTER TABLE profil_sekolah 
ADD COLUMN IF NOT EXISTS whatsapp VARCHAR(20) DEFAULT NULL,
ADD COLUMN IF NOT EXISTS video_profil VARCHAR(255) DEFAULT NULL;
";

try {
    $pdo->exec($sql);
    echo "<h1>Perbaikan Database Berhasil!</h1>";
    echo "<p>Kolom 'whatsapp' dan 'video_profil' berhasil ditambahkan ke tabel profil_sekolah.</p>";
    echo '<a href="admin/profil.php">Kembali ke Profil Admin</a>';
} catch (PDOException $e) {
    echo "Gagal: " . $e->getMessage();
}
