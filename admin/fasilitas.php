<?php
session_start();
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';
require_admin_login();

$halaman = 'fasilitas';

if (isset($_GET['hapus'])) {
    $id = (int) $_GET['hapus'];
    // Ambil info gambar untuk dihapus jika ada
    $stmt = $pdo->prepare("SELECT gambar FROM fasilitas WHERE id = ?");
    $stmt->execute([$id]);
    $f = $stmt->fetch();
    if ($f && !empty($f['gambar'])) {
        $path = __DIR__ . '/../assets/img/fasilitas/' . $f['gambar'];
        if (file_exists($path)) { @unlink($path); }
    }
    $del = $pdo->prepare("DELETE FROM fasilitas WHERE id = ?");
    $del->execute([$id]);
    header('Location: fasilitas.php?dihapus=1');
    exit;
}

$fasilitas = $pdo->query("SELECT * FROM fasilitas ORDER BY urutan ASC, id ASC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Kelola Fasilitas - SMAI MQ</title>
<link rel="icon" type="image/png" href="../assets/img/logo.png">
<link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
<div class="admin-shell">
    <?php require __DIR__ . '/includes_sidebar.php'; ?>
    <div class="admin-konten">
        <div class="admin-topbar">
            <h1>Kelola Fasilitas Sekolah</h1>
            <a href="fasilitas-form.php" class="btn btn-emas">+ Tambah Fasilitas</a>
        </div>

        <?php if (isset($_GET['tersimpan'])): ?>
            <div class="pesan-status sukses">Data fasilitas berhasil disimpan!</div>
        <?php elseif (isset($_GET['dihapus'])): ?>
            <div class="pesan-status sukses">Fasilitas berhasil dihapus.</div>
        <?php endif; ?>

        <div class="kartu-admin">
            <table class="tabel-admin">
                <thead>
                    <tr>
                        <th style="width:60px;">No</th>
                        <th style="width:120px;">Foto</th>
                        <th>Nama Fasilitas</th>
                        <th>Deskripsi</th>
                        <th style="width:70px;">Urutan</th>
                        <th style="width:140px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($fasilitas): foreach ($fasilitas as $i => $f): ?>
                    <tr>
                        <td><?= $i + 1 ?></td>
                        <td>
                            <?php if (!empty($f['gambar']) && file_exists(__DIR__ . '/../assets/img/fasilitas/' . $f['gambar'])): ?>
                                <img src="../assets/img/fasilitas/<?= e($f['gambar']) ?>" alt="<?= e($f['nama_fasilitas']) ?>" style="width:80px; height:50px; object-fit:cover; border-radius:4px; border:1px solid #ddd;">
                            <?php else: ?>
                                <span style="color:#999; font-size:0.8rem;">Tanpa Foto</span>
                            <?php endif; ?>
                        </td>
                        <td><strong><?= e($f['nama_fasilitas']) ?></strong></td>
                        <td><?= e(mb_strimwidth($f['deskripsi'], 0, 80, '...')) ?></td>
                        <td><?= (int)$f['urutan'] ?></td>
                        <td>
                            <a href="fasilitas-form.php?id=<?= $f['id'] ?>" class="btn btn-luar btn-kecil">Ubah</a>
                            <a href="fasilitas.php?hapus=<?= $f['id'] ?>" class="btn btn-hapus btn-kecil" onclick="return confirm('Hapus fasilitas ini?');">Hapus</a>
                        </td>
                    </tr>
                    <?php endforeach; else: ?>
                    <tr><td colspan="6">Belum ada data fasilitas. Silakan tambah fasilitas baru.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</body>
</html>
