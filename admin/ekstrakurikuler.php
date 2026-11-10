<?php
session_start();
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';
require_admin_login();

$halaman = 'ekstrakurikuler';

if (isset($_GET['hapus'])) {
    $id_hapus = (int) $_GET['hapus'];
    $stmt = $pdo->prepare("SELECT gambar FROM ekstrakurikuler WHERE id = ?");
    $stmt->execute([$id_hapus]);
    $foto = $stmt->fetchColumn();
    if ($foto && file_exists(__DIR__ . '/../assets/img/ekstrakurikuler/' . $foto)) {
        unlink(__DIR__ . '/../assets/img/ekstrakurikuler/' . $foto);
    }
    
    $stmt = $pdo->prepare("DELETE FROM ekstrakurikuler WHERE id = ?");
    $stmt->execute([$id_hapus]);
    header('Location: ekstrakurikuler.php?pesan=hapus_sukses');
    exit;
}

$ekstrakurikuler = $pdo->query("SELECT * FROM ekstrakurikuler ORDER BY nama ASC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Manajemen Ekstrakurikuler - SMAI MQ</title>
<link rel="icon" type="image/png" href="../assets/img/logo.png">
<link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
<div class="admin-shell">
    <?php require __DIR__ . '/includes_sidebar.php'; ?>
    <div class="admin-konten">
        <div class="admin-topbar">
            <h1>Manajemen Ekstrakurikuler</h1>
            <a href="ekstrakurikuler-form.php" class="btn btn-emas">+ Tambah Ekstrakurikuler</a>
        </div>
        
        <?php if (isset($_GET['pesan']) && $_GET['pesan'] === 'hapus_sukses'): ?>
            <div class="pesan-status sukses">Data berhasil dihapus.</div>
        <?php endif; ?>

        <div class="kartu-admin">
            <table class="tabel-admin">
                <thead>
                    <tr>
                        <th>Gambar</th>
                        <th>Nama Ekstrakurikuler</th>
                        <th>Jadwal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($ekstrakurikuler as $e): ?>
                    <tr>
                        <td>
                            <?php if ($e['gambar']): ?>
                                <img src="../assets/img/ekstrakurikuler/<?= e($e['gambar']) ?>" alt="Foto" style="width:60px; height:60px; object-fit:cover; border-radius:5px;">
                            <?php else: ?>
                                <div style="width:60px; height:60px; background:#ddd; border-radius:5px; display:flex; align-items:center; justify-content:center; font-size:12px; color:#888;">No Img</div>
                            <?php endif; ?>
                        </td>
                        <td><strong><?= e($e['nama']) ?></strong></td>
                        <td><?= e($e['jadwal']) ?></td>
                        <td>
                            <a href="ekstrakurikuler-form.php?id=<?= $e['id'] ?>" class="btn btn-luar btn-kecil">Edit</a>
                            <a href="?hapus=<?= $e['id'] ?>" class="btn btn-merah btn-kecil" onclick="return confirm('Hapus data ini?');">Hapus</a>
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
