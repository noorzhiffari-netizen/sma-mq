<?php
/**
 * Skrip bantuan untuk inisialisasi / impor database smai_mq secara instan melalui browser.
 */
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'smai_mq');

$pesan = '';
$status = false;

$sqlFile = __DIR__ . '/database/smai_mq.sql';

if (!file_exists($sqlFile)) {
    $pesan = 'File database/smai_mq.sql tidak ditemukan!';
} else {
    try {
        $pdo = new PDO(
            'mysql:host=' . DB_HOST . ';charset=utf8mb4',
            DB_USER,
            DB_PASS,
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::MYSQL_ATTR_MULTI_STATEMENTS => true,
            ]
        );
        $sql = file_get_contents($sqlFile);
        $pdo->exec($sql);
        $status = true;
        $pesan = 'Database <strong>' . DB_NAME . '</strong> beserta seluruh tabel dan data awal berhasil dibuat/diimpor!';
    } catch (PDOException $e) {
        $pesan = 'Terjadi kesalahan saat mengimpor database: ' . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Inisialisasi Database - SMAI MQ</title>
<style>
    body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif; background: #f4f6f9; display: flex; align-items: center; justify-content: center; min-height: 100vh; margin: 0; }
    .card { background: #fff; padding: 35px 30px; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.08); max-width: 500px; width: 90%; text-align: center; }
    .icon { font-size: 48px; margin-bottom: 15px; }
    h2 { margin: 0 0 10px; color: #1e3a5f; }
    p { color: #555; line-height: 1.5; }
    .btn { display: inline-block; background: #0b4f3b; color: #fff; text-decoration: none; padding: 12px 24px; border-radius: 8px; font-weight: 600; margin-top: 20px; transition: background 0.2s; }
    .btn:hover { background: #073829; }
    .alert-sukses { color: #0f5132; background-color: #d1e7dd; border: 1px solid #badbcc; padding: 14px; border-radius: 8px; margin-top: 15px; text-align: left; }
    .alert-gagal { color: #842029; background-color: #f8d7da; border: 1px solid #f5c2c7; padding: 14px; border-radius: 8px; margin-top: 15px; text-align: left; }
</style>
</head>
<body>
<div class="card">
    <div class="icon"><?= $status ? '✅' : '⚠️' ?></div>
    <h2>Status Database SMAI MQ</h2>
    <div class="<?= $status ? 'alert-sukses' : 'alert-gagal' ?>">
        <?= $pesan ?>
    </div>
    <?php if ($status): ?>
        <a href="index.php" class="btn">Kunjungi Website SMAI MQ &rarr;</a>
    <?php else: ?>
        <a href="install_db.php" class="btn" style="background:#c0392b;">Coba Lagi</a>
    <?php endif; ?>
</div>
</body>
</html>
