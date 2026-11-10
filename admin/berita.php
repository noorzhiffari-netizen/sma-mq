<?php
session_start();
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';
require_admin_login();

$halaman = 'berita';

if (isset($_GET['hapus'])) {
    $stmt = $pdo->prepare("DELETE FROM berita WHERE id = ?");
    $stmt->execute([(int) $_GET['hapus']]);
    header('Location: berita.php?dihapus=1');
    exit;
}

$daftar = $pdo->query("SELECT * FROM berita ORDER BY dibuat_pada DESC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Kelola Berita - SMAI MQ</title>
<link rel="icon" type="image/png" href="../assets/img/logo.png">
<link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
<div class="admin-shell">
    <?php require __DIR__ . '/includes_sidebar.php'; ?>
    <div class="admin-konten">
        <div class="admin-topbar">
            <h1>Kelola Berita</h1>
            <a href="berita-form.php" class="btn btn-emas">+ Tulis Berita</a>
        </div>

        <?php if (isset($_GET['dihapus'])): ?>
            <div class="pesan-status sukses">Berita berhasil dihapus.</div>
        <?php endif; ?>
        <?php if (isset($_GET['tersimpan'])): ?>
            <div class="pesan-status sukses">Berita berhasil disimpan.</div>
        <?php endif; ?>

        <div class="kartu-admin">
            <table class="tabel-admin">
                <thead><tr><th style="width:90px;">Foto</th><th>Judul</th><th>Status</th><th>Tanggal</th><th style="width:140px;">Aksi</th></tr></thead>
                <tbody>
                <?php foreach ($daftar as $b): ?>
                    <tr>
                        <td>
                            <?php if (!empty($b['gambar']) && file_exists(__DIR__ . '/../assets/img/berita/' . $b['gambar'])): ?>
                                <img src="../assets/img/berita/<?= e($b['gambar']) ?>" alt="" style="width:70px; height:45px; object-fit:cover; border-radius:4px; border:1px solid #ddd;">
                            <?php else: ?>
                                <span style="color:#aaa; font-size:0.75rem;">Tanpa Foto</span>
                            <?php endif; ?>
                        </td>
                        <td><strong><?= e($b['judul']) ?></strong></td>
                        <td><span class="badge <?= $b['status'] === 'terbit' ? 'badge-hijau' : 'badge-emas' ?>"><?= e($b['status']) ?></span></td>
                        <td><?= format_tanggal_indo($b['dibuat_pada']) ?></td>
                        <td>
                            <a class="btn btn-luar btn-kecil" href="berita-form.php?id=<?= $b['id'] ?>">Ubah</a>
                            <a class="btn btn-hapus btn-kecil" href="berita.php?hapus=<?= $b['id'] ?>" onclick="return confirm('Hapus berita ini?');">Hapus</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if (!$daftar): ?><tr><td colspan="5">Belum ada berita.</td></tr><?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</body>
</html>
