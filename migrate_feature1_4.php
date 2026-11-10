<?php
require_once __DIR__ . '/config/database.php';

try {
    echo "Starting migration...\n";

    // 1. Add columns to profil_sekolah
    $sql_profil = "ALTER TABLE profil_sekolah 
                   ADD COLUMN IF NOT EXISTS whatsapp VARCHAR(20) DEFAULT NULL,
                   ADD COLUMN IF NOT EXISTS video_profil VARCHAR(255) DEFAULT NULL";
    $pdo->exec($sql_profil);
    echo "profil_sekolah updated.\n";

    // 2. Create faq table
    $sql_faq = "CREATE TABLE IF NOT EXISTS faq (
        id INT AUTO_INCREMENT PRIMARY KEY,
        pertanyaan VARCHAR(255) NOT NULL,
        jawaban TEXT NOT NULL,
        urutan INT DEFAULT 0
    ) ENGINE=InnoDB;";
    $pdo->exec($sql_faq);
    echo "faq table created.\n";

    echo "Migration completed successfully!\n";
} catch (PDOException $e) {
    echo "Migration failed: " . $e->getMessage() . "\n";
}
