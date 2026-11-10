<?php
session_start();
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';
require_admin_login();

$halaman = 'prestasi';
$id = $_GET['id'] ?? null;
$edit = null;

if ($id) {
    $stmt = $pdo->prepare("SELECT * FROM prestasi WHERE id = ?");
    $stmt->execute([$id]);
    $edit = $stmt->fetch();
    if (!$edit) {
        header('Location: prestasi.php');
        exit;
    }
}

$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $judul = trim($_POST['judul']);
    $tanggal = trim($_POST['tanggal_meraih']);
    $deskripsi = trim($_POST['deskripsi']);
    $gambar = $edit['gambar'] ?? null;

    if (!empty($_FILES['gambar']['name'])) {
        $ext = strtolower(pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION));
        if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp'])) {
            $namaFile = 'prestasi-' . time() . '.' . $ext;
            $destDir = __DIR__ . '/../assets/img/prestasi';
            if (!is_dir($destDir)) { mkdir($destDir, 0755, true); }
            move_uploaded_file($_FILES['gambar']['tmp_name'], $destDir . '/' . $namaFile);
            $gambar = $namaFile;
        } else {
            $error = 'Format file gambar harus JPG, PNG, atau WEBP.';
        }
    }

    if (!$error) {
        if ($edit) {
            $stmt = $pdo->prepare("UPDATE prestasi SET judul=?, tanggal_meraih=?, deskripsi=?, gambar=? WHERE id=?");
            $stmt->execute([$judul, $tanggal, $deskripsi, $gambar, $id]);
        } else {
            $stmt = $pdo->prepare("INSERT INTO prestasi (judul, tanggal_meraih, deskripsi, gambar) VALUES (?, ?, ?, ?)");
            $stmt->execute([$judul, $tanggal, $deskripsi, $gambar]);
        }
        header('Location: prestasi.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= $edit ? 'Edit' : 'Tambah' ?> Prestasi - SMAI MQ</title>
<link rel="icon" type="image/png" href="../assets/img/logo.png">
<link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
<div class="admin-shell">
    <?php require __DIR__ . '/includes_sidebar.php'; ?>
    <div class="admin-konten">
        <div class="admin-topbar">
            <h1><?= $edit ? 'Edit' : 'Tambah' ?> Prestasi</h1>
            <a href="prestasi.php" class="btn btn-luar">&larr; Kembali</a>
        </div>

        <div class="kartu-admin" style="max-width:700px;">
            <?php if ($error): ?>
                <div class="pesan-status gagal"><?= e($error) ?></div>
            <?php endif; ?>
            <form method="post" action="" enctype="multipart/form-data">
                <div class="form-grup">
                    <label>Judul Prestasi</label>
                    <input type="text" name="judul" class="form-input" value="<?= $edit ? e($edit['judul']) : '' ?>" required>
                </div>
                <div class="form-grup">
                    <label>Tanggal Meraih</label>
                    <input type="date" name="tanggal_meraih" class="form-input" value="<?= $edit ? e($edit['tanggal_meraih']) : '' ?>" required>
                </div>
                <div class="form-grup">
                    <label>Deskripsi (Opsional)</label>
                    <textarea name="deskripsi" class="form-input" style="height:100px;"><?= $edit ? e($edit['deskripsi']) : '' ?></textarea>
                </div>
                <div class="form-grup">
                    <label>Gambar / Foto Prestasi <?= $edit ? '<small>(Biarkan kosong jika tidak ingin mengubah)</small>' : '' ?></label>
                    <?php if ($edit && $edit['gambar']): ?>
                        <div style="margin-bottom:10px;">
                            <img src="../assets/img/prestasi/<?= e($edit['gambar']) ?>" alt="Foto Saat Ini" style="max-width:200px; border-radius:8px;">
                        </div>
                    <?php endif; ?>
                    <input type="file" name="gambar" class="form-input" accept="image/*">
                </div>
                <button type="submit" class="btn btn-emas">Simpan Data</button>
            </form>
        </div>
    </div>
</div>
</body>
</html>
