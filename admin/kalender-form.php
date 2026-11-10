<?php
session_start();
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';
require_admin_login();

$halaman = 'kalender';
$id = $_GET['id'] ?? null;
$edit = null;

if ($id) {
    $stmt = $pdo->prepare("SELECT * FROM kalender_akademik WHERE id = ?");
    $stmt->execute([$id]);
    $edit = $stmt->fetch();
    if (!$edit) {
        header('Location: kalender.php');
        exit;
    }
}

$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = trim($_POST['nama_kegiatan']);
    $mulai = trim($_POST['tanggal_mulai']);
    $selesai = trim($_POST['tanggal_selesai']);
    $keterangan = trim($_POST['keterangan']);
    
    if (empty($selesai)) {
        $selesai = $mulai;
    }

    if (!$error) {
        if ($edit) {
            $stmt = $pdo->prepare("UPDATE kalender_akademik SET tanggal_mulai=?, tanggal_selesai=?, nama_kegiatan=?, keterangan=? WHERE id=?");
            $stmt->execute([$mulai, $selesai, $nama, $keterangan, $id]);
        } else {
            $stmt = $pdo->prepare("INSERT INTO kalender_akademik (tanggal_mulai, tanggal_selesai, nama_kegiatan, keterangan) VALUES (?, ?, ?, ?)");
            $stmt->execute([$mulai, $selesai, $nama, $keterangan]);
        }
        header('Location: kalender.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= $edit ? 'Edit' : 'Tambah' ?> Agenda - SMAI MQ</title>
<link rel="icon" type="image/png" href="../assets/img/logo.png">
<link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
<div class="admin-shell">
    <?php require __DIR__ . '/includes_sidebar.php'; ?>
    <div class="admin-konten">
        <div class="admin-topbar">
            <h1><?= $edit ? 'Edit' : 'Tambah' ?> Agenda</h1>
            <a href="kalender.php" class="btn btn-luar">&larr; Kembali</a>
        </div>

        <div class="kartu-admin" style="max-width:700px;">
            <?php if ($error): ?>
                <div class="pesan-status gagal"><?= e($error) ?></div>
            <?php endif; ?>
            <form method="post" action="">
                <div class="form-grup">
                    <label>Nama Kegiatan</label>
                    <input type="text" name="nama_kegiatan" class="form-input" value="<?= $edit ? e($edit['nama_kegiatan']) : '' ?>" required>
                </div>
                <div style="display:flex; gap:20px;">
                    <div class="form-grup" style="flex:1;">
                        <label>Tanggal Mulai</label>
                        <input type="date" name="tanggal_mulai" class="form-input" value="<?= $edit ? e($edit['tanggal_mulai']) : '' ?>" required>
                    </div>
                    <div class="form-grup" style="flex:1;">
                        <label>Tanggal Selesai (Opsional)</label>
                        <input type="date" name="tanggal_selesai" class="form-input" value="<?= $edit ? e($edit['tanggal_selesai']) : '' ?>">
                        <small>Kosongkan jika hanya 1 hari</small>
                    </div>
                </div>
                <div class="form-grup">
                    <label>Keterangan / Tempat (Opsional)</label>
                    <textarea name="keterangan" class="form-input" style="height:100px;"><?= $edit ? e($edit['keterangan']) : '' ?></textarea>
                </div>
                
                <button type="submit" class="btn btn-emas">Simpan Agenda</button>
            </form>
        </div>
    </div>
</div>
</body>
</html>
