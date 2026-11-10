<?php
session_start();
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';
require_admin_login();

$halaman = 'kalender';

// Hapus agenda
if (isset($_GET['hapus'])) {
    $stmt = $pdo->prepare("DELETE FROM kalender_akademik WHERE id = ?");
    $stmt->execute([$_GET['hapus']]);
    header('Location: kalender.php');
    exit;
}

$agenda = $pdo->query("SELECT * FROM kalender_akademik ORDER BY tanggal_mulai ASC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Kalender Akademik - Admin SMAI MQ</title>
<link rel="icon" type="image/png" href="../assets/img/logo.png">
<link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
<div class="admin-shell">
    <?php require __DIR__ . '/includes_sidebar.php'; ?>
    <div class="admin-konten">
        <div class="admin-topbar">
            <h1>Kelola Kalender Akademik</h1>
            <a href="kalender-form.php" class="btn btn-emas">+ Tambah Agenda</a>
        </div>

        <div class="kartu-admin">
            <div style="overflow-x:auto;">
                <table class="tabel-admin">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Nama Kegiatan</th>
                            <th>Keterangan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($agenda as $item): ?>
                            <tr>
                                <td style="white-space: nowrap;">
                                    <?php 
                                        if ($item['tanggal_selesai'] && $item['tanggal_selesai'] !== $item['tanggal_mulai']) {
                                            echo date('d M Y', strtotime($item['tanggal_mulai'])) . ' - ' . date('d M Y', strtotime($item['tanggal_selesai']));
                                        } else {
                                            echo date('d M Y', strtotime($item['tanggal_mulai']));
                                        }
                                    ?>
                                </td>
                                <td><?= e($item['nama_kegiatan']) ?></td>
                                <td><?= e($item['keterangan']) ?></td>
                                <td style="white-space: nowrap;">
                                    <a href="kalender-form.php?id=<?= $item['id'] ?>" class="btn btn-luar btn-kecil">Edit</a>
                                    <a href="kalender.php?hapus=<?= $item['id'] ?>" class="btn btn-luar btn-kecil" style="color:var(--merah); border-color:var(--merah);" onclick="return confirm('Hapus agenda ini?')">Hapus</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        <?php if(!$agenda): ?>
                            <tr><td colspan="4" style="text-align:center;">Belum ada data agenda.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
</body>
</html>
