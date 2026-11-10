<?php
session_start();
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';
require_admin_login();

$halaman = 'pesan';

if (isset($_GET['baca'])) {
    $stmt = $pdo->prepare("UPDATE pesan SET sudah_dibaca = 1 WHERE id = ?");
    $stmt->execute([(int) $_GET['baca']]);
    header('Location: pesan.php');
    exit;
}
if (isset($_GET['hapus'])) {
    $stmt = $pdo->prepare("DELETE FROM pesan WHERE id = ?");
    $stmt->execute([(int) $_GET['hapus']]);
    header('Location: pesan.php?dihapus=1');
    exit;
}

$daftar = $pdo->query("SELECT * FROM pesan ORDER BY dikirim_pada DESC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Pesan Masuk - SMAI MQ</title>
<link rel="icon" type="image/png" href="../assets/img/logo.png">
<link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
<div class="admin-shell">
    <?php require __DIR__ . '/includes_sidebar.php'; ?>
    <div class="admin-konten">
        <div class="admin-topbar"><h1>Pesan Masuk</h1></div>
        <?php if (isset($_GET['dihapus'])): ?><div class="pesan-status sukses">Pesan berhasil dihapus.</div><?php endif; ?>

        <div class="kartu-admin">
            <?php foreach ($daftar as $p): ?>
                <div style="padding:16px 0; border-bottom:1px solid var(--garis);">
                    <div style="display:flex; justify-content:space-between; flex-wrap:wrap; gap:6px;">
                        <strong><?= e($p['nama']) ?></strong>
                        <span style="font-size:0.8rem; color:var(--abu);"><?= format_tanggal_indo($p['dikirim_pada']) ?></span>
                    </div>
                    <p style="font-size:0.85rem; color:var(--abu); margin:4px 0;"><?= e($p['email']) ?> &middot; <?= e($p['subjek'] ?: '(tanpa subjek)') ?></p>
                    <p style="margin:8px 0;"><?= nl2br(e($p['isi_pesan'])) ?></p>
                    <div style="display:flex; gap:8px;">
                        <?php if (!$p['sudah_dibaca']): ?>
                            <a class="btn btn-luar btn-kecil" href="pesan.php?baca=<?= $p['id'] ?>">Tandai Dibaca</a>
                        <?php else: ?>
                            <span class="badge badge-hijau">Sudah dibaca</span>
                        <?php endif; ?>
                        <a class="btn btn-hapus btn-kecil" href="pesan.php?hapus=<?= $p['id'] ?>" onclick="return confirm('Hapus pesan ini?');">Hapus</a>
                    </div>
                </div>
            <?php endforeach; ?>
            <?php if (!$daftar): ?><p>Belum ada pesan masuk.</p><?php endif; ?>
        </div>
    </div>
</div>
</body>
</html>
