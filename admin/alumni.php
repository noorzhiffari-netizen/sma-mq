<?php
session_start();
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';
require_admin_login();

$halaman = 'alumni';

if (isset($_GET['hapus'])) {
    $id_hapus = (int) $_GET['hapus'];
    $stmt = $pdo->prepare("SELECT foto FROM alumni WHERE id = ?");
    $stmt->execute([$id_hapus]);
    $foto = $stmt->fetchColumn();
    if ($foto && file_exists(__DIR__ . '/../assets/img/alumni/' . $foto)) {
        unlink(__DIR__ . '/../assets/img/alumni/' . $foto);
    }
    
    $stmt = $pdo->prepare("DELETE FROM alumni WHERE id = ?");
    $stmt->execute([$id_hapus]);
    header('Location: alumni.php?pesan=hapus_sukses');
    exit;
}

$alumni = $pdo->query("SELECT * FROM alumni ORDER BY tahun_lulus DESC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Manajemen Alumni - SMAI MQ</title>
<link rel="icon" type="image/png" href="../assets/img/logo.png">
<link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
<div class="admin-shell">
    <?php require __DIR__ . '/includes_sidebar.php'; ?>
    <div class="admin-konten">
        <div class="admin-topbar">
            <h1>Manajemen Testimoni Alumni</h1>
            <a href="alumni-form.php" class="btn btn-emas">+ Tambah Testimoni</a>
        </div>
        
        <?php if (isset($_GET['pesan']) && $_GET['pesan'] === 'hapus_sukses'): ?>
            <div class="pesan-status sukses">Data berhasil dihapus.</div>
        <?php endif; ?>

        <div class="kartu-admin">
            <table class="tabel-admin">
                <thead>
                    <tr>
                        <th>Foto</th>
                        <th>Nama Alumni</th>
                        <th>Tahun Lulus</th>
                        <th>Pekerjaan / Kuliah</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($alumni as $a): ?>
                    <tr>
                        <td>
                            <?php if ($a['foto']): ?>
                                <img src="../assets/img/alumni/<?= e($a['foto']) ?>" alt="Foto" style="width:50px; height:50px; object-fit:cover; border-radius:50%;">
                            <?php else: ?>
                                <div style="width:50px; height:50px; background:#ddd; border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:10px; color:#888;">No Img</div>
                            <?php endif; ?>
                        </td>
                        <td><strong><?= e($a['nama_lengkap']) ?></strong></td>
                        <td><?= e($a['tahun_lulus']) ?></td>
                        <td><?= e($a['pekerjaan_kuliah']) ?></td>
                        <td>
                            <?php if ($a['status'] === 'terbit'): ?>
                                <span class="badge badge-hijau">Terbit</span>
                            <?php else: ?>
                                <span class="badge badge-emas">Draft</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="alumni-form.php?id=<?= $a['id'] ?>" class="btn btn-luar btn-kecil">Edit</a>
                            <a href="?hapus=<?= $a['id'] ?>" class="btn btn-merah btn-kecil" onclick="return confirm('Hapus data alumni ini?');">Hapus</a>
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
