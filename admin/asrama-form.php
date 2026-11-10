<?php
session_start();
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';
require_admin_login();

$halaman = 'asrama';
$id = $_GET['id'] ?? null;
$edit = null;

if ($id) {
    $stmt = $pdo->prepare("SELECT * FROM asrama WHERE id = ?");
    $stmt->execute([$id]);
    $edit = $stmt->fetch();
    if (!$edit) {
        header('Location: asrama.php');
        exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = trim($_POST['nama_asrama']);
    $deskripsi = trim($_POST['deskripsi']);
    $kapasitas = (int) $_POST['kapasitas'];
    $fasilitas = trim($_POST['fasilitas_utama']);
    $kegiatan = trim($_POST['kegiatan_harian']);

    if ($edit) {
        $stmt = $pdo->prepare("UPDATE asrama SET nama_asrama=?, deskripsi=?, kapasitas=?, fasilitas_utama=?, kegiatan_harian=? WHERE id=?");
        $stmt->execute([$nama, $deskripsi, $kapasitas, $fasilitas, $kegiatan, $id]);
    } else {
        $stmt = $pdo->prepare("INSERT INTO asrama (nama_asrama, deskripsi, kapasitas, fasilitas_utama, kegiatan_harian) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$nama, $deskripsi, $kapasitas, $fasilitas, $kegiatan]);
    }
    header('Location: asrama.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= $edit ? 'Edit' : 'Tambah' ?> Asrama - SMAI MQ</title>
<link rel="icon" type="image/png" href="../assets/img/logo.png">
<link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
<div class="admin-shell">
    <?php require __DIR__ . '/includes_sidebar.php'; ?>
    <div class="admin-konten">
        <div class="admin-topbar">
            <h1><?= $edit ? 'Edit' : 'Tambah' ?> Asrama</h1>
            <a href="asrama.php" class="btn btn-luar">&larr; Kembali</a>
        </div>

        <div class="kartu-admin" style="max-width:700px;">
            <form method="post" action="">
                <div class="form-grup">
                    <label>Nama Asrama</label>
                    <input type="text" name="nama_asrama" class="form-input" value="<?= $edit ? e($edit['nama_asrama']) : '' ?>" required>
                </div>
                <div class="form-grup">
                    <label>Kapasitas (Jumlah Santri)</label>
                    <input type="number" name="kapasitas" class="form-input" value="<?= $edit ? e($edit['kapasitas']) : '' ?>" required>
                </div>
                <div class="form-grup">
                    <label>Deskripsi</label>
                    <textarea name="deskripsi" class="form-input" style="height:100px;"><?= $edit ? e($edit['deskripsi']) : '' ?></textarea>
                </div>
                <div class="form-grup">
                    <label>Fasilitas Utama</label>
                    <textarea name="fasilitas_utama" class="form-input" style="height:80px;"><?= $edit ? e($edit['fasilitas_utama']) : '' ?></textarea>
                </div>
                <div class="form-grup">
                    <label>Kegiatan Harian</label>
                    <textarea name="kegiatan_harian" class="form-input" style="height:80px;"><?= $edit ? e($edit['kegiatan_harian']) : '' ?></textarea>
                </div>
                <button type="submit" class="btn btn-emas">Simpan Data</button>
            </form>
        </div>
    </div>
</div>
</body>
</html>
