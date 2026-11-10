<?php
session_start();
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';
require_admin_login();

$halaman = 'unduhan';

if (isset($_GET['hapus'])) {
    $id_hapus = (int) $_GET['hapus'];
    $stmt = $pdo->prepare("SELECT path_file FROM unduhan WHERE id = ?");
    $stmt->execute([$id_hapus]);
    $file = $stmt->fetchColumn();
    if ($file && file_exists(__DIR__ . '/../assets/unduhan/' . $file)) {
        unlink(__DIR__ . '/../assets/unduhan/' . $file);
    }
    
    $stmt = $pdo->prepare("DELETE FROM unduhan WHERE id = ?");
    $stmt->execute([$id_hapus]);
    header('Location: unduhan.php?pesan=hapus_sukses');
    exit;
}

$unduhan = $pdo->query("SELECT * FROM unduhan ORDER BY dibuat_pada DESC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Manajemen Unduhan - SMAI MQ</title>
<link rel="icon" type="image/png" href="../assets/img/logo.png">
<link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
<div class="admin-shell">
    <?php require __DIR__ . '/includes_sidebar.php'; ?>
    <div class="admin-konten">
        <div class="admin-topbar">
            <h1>Manajemen Unduhan Area</h1>
            <a href="unduhan-form.php" class="btn btn-emas">+ Tambah File</a>
        </div>
        
        <?php if (isset($_GET['pesan']) && $_GET['pesan'] === 'hapus_sukses'): ?>
            <div class="pesan-status sukses">File berhasil dihapus.</div>
        <?php endif; ?>

        <div class="kartu-admin">
            <table class="tabel-admin">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Nama File / Dokumen</th>
                        <th>Keterangan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($unduhan as $u): ?>
                    <tr>
                        <td><?= date('d M Y', strtotime($u['dibuat_pada'])) ?></td>
                        <td>
                            <strong><?= e($u['nama_file']) ?></strong><br>
                            <a href="../assets/unduhan/<?= e($u['path_file']) ?>" target="_blank" style="font-size:12px; color:var(--emas);">Lihat File</a>
                        </td>
                        <td><?= e($u['keterangan']) ?></td>
                        <td>
                            <a href="unduhan-form.php?id=<?= $u['id'] ?>" class="btn btn-luar btn-kecil">Edit</a>
                            <a href="?hapus=<?= $u['id'] ?>" class="btn btn-merah btn-kecil" onclick="return confirm('Hapus file ini?');">Hapus</a>
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
