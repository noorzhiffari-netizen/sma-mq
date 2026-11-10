<?php
session_start();
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';
require_admin_login();

$halaman = 'jurusan';
$id = $_GET['id'] ?? null;
$edit = null;

if ($id) {
    $stmt = $pdo->prepare("SELECT * FROM jurusan WHERE id = ?");
    $stmt->execute([$id]);
    $edit = $stmt->fetch();
    if (!$edit) {
        header('Location: jurusan.php');
        exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $kode = trim($_POST['kode']);
    $nama = trim($_POST['nama']);
    $deskripsi = trim($_POST['deskripsi']);
    $mapel = trim($_POST['mata_pelajaran_unggulan']);

    if ($edit) {
        $stmt = $pdo->prepare("UPDATE jurusan SET kode=?, nama=?, deskripsi=?, mata_pelajaran_unggulan=? WHERE id=?");
        $stmt->execute([$kode, $nama, $deskripsi, $mapel, $id]);
    } else {
        $stmt = $pdo->prepare("INSERT INTO jurusan (kode, nama, deskripsi, mata_pelajaran_unggulan) VALUES (?, ?, ?, ?)");
        $stmt->execute([$kode, $nama, $deskripsi, $mapel]);
    }
    header('Location: jurusan.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= $edit ? 'Edit' : 'Tambah' ?> Jurusan - SMAI MQ</title>
<link rel="icon" type="image/png" href="../assets/img/logo.png">
<link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
<div class="admin-shell">
    <?php require __DIR__ . '/includes_sidebar.php'; ?>
    <div class="admin-konten">
        <div class="admin-topbar">
            <h1><?= $edit ? 'Edit' : 'Tambah' ?> Jurusan</h1>
            <a href="jurusan.php" class="btn btn-luar">&larr; Kembali</a>
        </div>

        <div class="kartu-admin" style="max-width:700px;">
            <form method="post" action="">
                <div class="form-grup">
                    <label>Kode Jurusan</label>
                    <input type="text" name="kode" class="form-input" value="<?= $edit ? e($edit['kode']) : '' ?>" required>
                </div>
                <div class="form-grup">
                    <label>Nama Jurusan</label>
                    <input type="text" name="nama" class="form-input" value="<?= $edit ? e($edit['nama']) : '' ?>" required>
                </div>
                <div class="form-grup">
                    <label>Deskripsi Singkat</label>
                    <textarea name="deskripsi" class="form-input" style="height:100px;"><?= $edit ? e($edit['deskripsi']) : '' ?></textarea>
                </div>
                <div class="form-grup">
                    <label>Mata Pelajaran Unggulan</label>
                    <textarea name="mata_pelajaran_unggulan" class="form-input" style="height:80px;"><?= $edit ? e($edit['mata_pelajaran_unggulan']) : '' ?></textarea>
                </div>
                <button type="submit" class="btn btn-emas">Simpan Data</button>
            </form>
        </div>
    </div>
</div>
</body>
</html>
