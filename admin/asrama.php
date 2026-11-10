<?php
session_start();
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';
require_admin_login();

$halaman = 'asrama';

if (isset($_GET['hapus'])) {
    $id_hapus = (int) $_GET['hapus'];
    $stmt = $pdo->prepare("DELETE FROM asrama WHERE id = ?");
    $stmt->execute([$id_hapus]);
    header('Location: asrama.php?pesan=hapus_sukses');
    exit;
}

$asrama = $pdo->query("SELECT * FROM asrama ORDER BY id ASC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Manajemen Asrama - SMAI MQ</title>
<link rel="icon" type="image/png" href="../assets/img/logo.png">
<link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
<div class="admin-shell">
    <?php require __DIR__ . '/includes_sidebar.php'; ?>
    <div class="admin-konten">
        <div class="admin-topbar">
            <h1>Manajemen Asrama</h1>
            <a href="asrama-form.php" class="btn btn-emas">+ Tambah Asrama</a>
        </div>
        
        <?php if (isset($_GET['pesan']) && $_GET['pesan'] === 'hapus_sukses'): ?>
            <div class="pesan-status sukses">Data berhasil dihapus.</div>
        <?php endif; ?>

        <div class="kartu-admin">
            <table class="tabel-admin">
                <thead>
                    <tr>
                        <th>Nama Asrama</th>
                        <th>Kapasitas</th>
                        <th>Fasilitas Utama</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($asrama as $a): ?>
                    <tr>
                        <td><strong><?= e($a['nama_asrama']) ?></strong></td>
                        <td><?= e($a['kapasitas']) ?> Santri</td>
                        <td><?= e($a['fasilitas_utama']) ?></td>
                        <td>
                            <a href="asrama-form.php?id=<?= $a['id'] ?>" class="btn btn-luar btn-kecil">Edit</a>
                            <a href="?hapus=<?= $a['id'] ?>" class="btn btn-merah btn-kecil" onclick="return confirm('Hapus asrama ini?');">Hapus</a>
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
