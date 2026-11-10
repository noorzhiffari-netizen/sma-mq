<?php
session_start();
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';
require_admin_login();

$halaman = 'pengumuman';

if (isset($_GET['hapus'])) {
    $id_hapus = (int) $_GET['hapus'];
    $stmt = $pdo->prepare("DELETE FROM pengumuman WHERE id = ?");
    $stmt->execute([$id_hapus]);
    header('Location: pengumuman.php?pesan=hapus_sukses');
    exit;
}

$pengumuman = $pdo->query("SELECT * FROM pengumuman ORDER BY dibuat_pada DESC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Manajemen Pengumuman - SMAI MQ</title>
<link rel="icon" type="image/png" href="../assets/img/logo.png">
<link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
<div class="admin-shell">
    <?php require __DIR__ . '/includes_sidebar.php'; ?>
    <div class="admin-konten">
        <div class="admin-topbar">
            <h1>Manajemen Pengumuman</h1>
            <a href="pengumuman-form.php" class="btn btn-emas">+ Tambah Pengumuman</a>
        </div>
        
        <?php if (isset($_GET['pesan']) && $_GET['pesan'] === 'hapus_sukses'): ?>
            <div class="pesan-status sukses">Pengumuman berhasil dihapus.</div>
        <?php endif; ?>

        <div class="kartu-admin">
            <table class="tabel-admin">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Judul Pengumuman</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($pengumuman as $p): ?>
                    <tr>
                        <td><?= date('d M Y, H:i', strtotime($p['dibuat_pada'])) ?></td>
                        <td><strong><?= e($p['judul']) ?></strong></td>
                        <td>
                            <a href="pengumuman-form.php?id=<?= $p['id'] ?>" class="btn btn-luar btn-kecil">Edit</a>
                            <a href="?hapus=<?= $p['id'] ?>" class="btn btn-merah btn-kecil" onclick="return confirm('Hapus pengumuman ini?');">Hapus</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</body>
</html>
