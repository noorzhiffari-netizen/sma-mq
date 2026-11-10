<?php
session_start();
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';
require_admin_login();

$halaman = 'jurusan';

if (isset($_GET['hapus'])) {
    $id_hapus = (int) $_GET['hapus'];
    $stmt = $pdo->prepare("DELETE FROM jurusan WHERE id = ?");
    $stmt->execute([$id_hapus]);
    header('Location: jurusan.php?pesan=hapus_sukses');
    exit;
}

$jurusan = $pdo->query("SELECT * FROM jurusan ORDER BY id ASC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Manajemen Jurusan - SMAI MQ</title>
<link rel="icon" type="image/png" href="../assets/img/logo.png">
<link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
<div class="admin-shell">
    <?php require __DIR__ . '/includes_sidebar.php'; ?>
    <div class="admin-konten">
        <div class="admin-topbar">
            <h1>Manajemen Jurusan</h1>
            <a href="jurusan-form.php" class="btn btn-emas">+ Tambah Jurusan</a>
        </div>
        
        <?php if (isset($_GET['pesan']) && $_GET['pesan'] === 'hapus_sukses'): ?>
            <div class="pesan-status sukses">Data berhasil dihapus.</div>
        <?php endif; ?>

        <div class="kartu-admin">
            <table class="tabel-admin">
                <thead>
                    <tr>
                        <th>Kode</th>
                        <th>Nama Jurusan</th>
                        <th>Mapel Unggulan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($jurusan as $j): ?>
                    <tr>
                        <td><strong><?= e($j['kode']) ?></strong></td>
                        <td><?= e($j['nama']) ?></td>
                        <td><?= e($j['mata_pelajaran_unggulan']) ?></td>
                        <td>
                            <a href="jurusan-form.php?id=<?= $j['id'] ?>" class="btn btn-luar btn-kecil">Edit</a>
                            <a href="?hapus=<?= $j['id'] ?>" class="btn btn-merah btn-kecil" onclick="return confirm('Hapus jurusan ini?');">Hapus</a>
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
