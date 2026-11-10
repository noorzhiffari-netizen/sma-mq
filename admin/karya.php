<?php
session_start();
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';
require_admin_login();

$halaman = 'karya';

// Hapus karya
if (isset($_GET['hapus'])) {
    $stmt = $pdo->prepare("SELECT gambar FROM karya_siswa WHERE id = ?");
    $stmt->execute([$_GET['hapus']]);
    $karya = $stmt->fetch();
    
    if ($karya && $karya['gambar']) {
        $file = __DIR__ . '/../assets/img/karya/' . $karya['gambar'];
        if (file_exists($file)) @unlink($file);
    }
    
    $stmt = $pdo->prepare("DELETE FROM karya_siswa WHERE id = ?");
    $stmt->execute([$_GET['hapus']]);
    header('Location: karya.php');
    exit;
}

$karya = $pdo->query("SELECT * FROM karya_siswa ORDER BY tanggal DESC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Karya Siswa - Admin SMAI MQ</title>
<link rel="icon" type="image/png" href="../assets/img/logo.png">
<link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
<div class="admin-shell">
    <?php require __DIR__ . '/includes_sidebar.php'; ?>
    <div class="admin-konten">
        <div class="admin-topbar">
            <h1>Kelola Karya Siswa</h1>
            <a href="karya-form.php" class="btn btn-emas">+ Tambah Karya</a>
        </div>

        <div class="kartu-admin">
            <div style="overflow-x:auto;">
                <table class="tabel-admin">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Judul Karya</th>
                            <th>Penulis</th>
                            <th>Kategori</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($karya as $k): ?>
                            <tr>
                                <td><?= date('d M Y', strtotime($k['tanggal'])) ?></td>
                                <td><strong><?= e($k['judul']) ?></strong></td>
                                <td><?= e($k['penulis']) ?> <?= $k['kelas'] ? '('.e($k['kelas']).')' : '' ?></td>
                                <td><?= e($k['kategori']) ?></td>
                                <td>
                                    <?php if ($k['status'] === 'terbit'): ?>
                                        <span style="color: green; font-weight: bold;">Terbit</span>
                                    <?php else: ?>
                                        <span style="color: orange; font-weight: bold;">Draft</span>
                                    <?php endif; ?>
                                </td>
                                <td style="white-space: nowrap;">
                                    <a href="karya-form.php?id=<?= $k['id'] ?>" class="btn btn-luar btn-kecil">Edit</a>
                                    <a href="karya.php?hapus=<?= $k['id'] ?>" class="btn btn-luar btn-kecil" style="color:var(--merah); border-color:var(--merah);" onclick="return confirm('Hapus karya ini?')">Hapus</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        <?php if(!$karya): ?>
                            <tr><td colspan="6" style="text-align:center;">Belum ada data karya.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
</body>
</html>
