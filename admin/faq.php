<?php
session_start();
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';
require_admin_login();

$halaman = 'faq';

if (isset($_GET['hapus'])) {
    $id_hapus = (int) $_GET['hapus'];
    $stmt = $pdo->prepare("DELETE FROM faq WHERE id = ?");
    $stmt->execute([$id_hapus]);
    header('Location: faq.php?pesan=hapus_sukses');
    exit;
}

$faq = $pdo->query("SELECT * FROM faq ORDER BY urutan ASC, id DESC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Manajemen FAQ - SMAI MQ</title>
<link rel="icon" type="image/png" href="../assets/img/logo.png">
<link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
<div class="admin-shell">
    <?php require __DIR__ . '/includes_sidebar.php'; ?>
    <div class="admin-konten">
        <div class="admin-topbar">
            <h1>Manajemen FAQ (Tanya Jawab)</h1>
            <a href="faq-form.php" class="btn btn-emas">+ Tambah FAQ</a>
        </div>
        
        <?php if (isset($_GET['pesan']) && $_GET['pesan'] === 'hapus_sukses'): ?>
            <div class="pesan-status sukses">FAQ berhasil dihapus.</div>
        <?php endif; ?>

        <div class="kartu-admin">
            <table class="tabel-admin">
                <thead>
                    <tr>
                        <th width="10%">Urutan</th>
                        <th width="30%">Pertanyaan</th>
                        <th width="40%">Jawaban</th>
                        <th width="20%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($faq as $f): ?>
                    <tr>
                        <td style="text-align:center;"><?= e($f['urutan']) ?></td>
                        <td><strong><?= e($f['pertanyaan']) ?></strong></td>
                        <td><?= mb_strimwidth(e($f['jawaban']), 0, 80, "...") ?></td>
                        <td>
                            <a href="faq-form.php?id=<?= $f['id'] ?>" class="btn btn-luar btn-kecil">Edit</a>
                            <a href="?hapus=<?= $f['id'] ?>" class="btn btn-merah btn-kecil" onclick="return confirm('Hapus FAQ ini?');">Hapus</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if (!$faq): ?>
                    <tr><td colspan="4" style="text-align:center; padding:20px;">Belum ada data FAQ.</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</body>
</html>
