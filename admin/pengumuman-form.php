<?php
session_start();
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';
require_admin_login();

$halaman = 'pengumuman';
$id = $_GET['id'] ?? null;
$edit = null;

if ($id) {
    $stmt = $pdo->prepare("SELECT * FROM pengumuman WHERE id = ?");
    $stmt->execute([$id]);
    $edit = $stmt->fetch();
    if (!$edit) {
        header('Location: pengumuman.php');
        exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $judul = trim($_POST['judul']);
    $isi = trim($_POST['isi']);

    if ($edit) {
        $stmt = $pdo->prepare("UPDATE pengumuman SET judul=?, isi=? WHERE id=?");
        $stmt->execute([$judul, $isi, $id]);
    } else {
        $stmt = $pdo->prepare("INSERT INTO pengumuman (judul, isi) VALUES (?, ?)");
        $stmt->execute([$judul, $isi]);
    }
    header('Location: pengumuman.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= $edit ? 'Edit' : 'Tambah' ?> Pengumuman - SMAI MQ</title>
<link rel="icon" type="image/png" href="../assets/img/logo.png">
<link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
<div class="admin-shell">
    <?php require __DIR__ . '/includes_sidebar.php'; ?>
    <div class="admin-konten">
        <div class="admin-topbar">
            <h1><?= $edit ? 'Edit' : 'Tambah' ?> Pengumuman</h1>
            <a href="pengumuman.php" class="btn btn-luar">&larr; Kembali</a>
        </div>

        <div class="kartu-admin" style="max-width:700px;">
            <form method="post" action="">
                <div class="form-grup">
                    <label>Judul Pengumuman</label>
                    <input type="text" name="judul" class="form-input" value="<?= $edit ? e($edit['judul']) : '' ?>" required>
                </div>
                <div class="form-grup">
                    <label>Isi Pengumuman</label>
                    <textarea name="isi" class="form-input" style="height:200px;" required><?= $edit ? e($edit['isi']) : '' ?></textarea>
                </div>
                <button type="submit" class="btn btn-emas">Simpan Data</button>
            </form>
        </div>
    </div>
</div>
</body>
</html>
