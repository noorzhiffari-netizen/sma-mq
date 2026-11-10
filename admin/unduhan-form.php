<?php
session_start();
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';
require_admin_login();

$halaman = 'unduhan';
$id = $_GET['id'] ?? null;
$edit = null;

if ($id) {
    $stmt = $pdo->prepare("SELECT * FROM unduhan WHERE id = ?");
    $stmt->execute([$id]);
    $edit = $stmt->fetch();
    if (!$edit) {
        header('Location: unduhan.php');
        exit;
    }
}

$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = trim($_POST['nama_file']);
    $keterangan = trim($_POST['keterangan']);
    $path_file = $edit['path_file'] ?? null;

    if (!empty($_FILES['file_unduhan']['name'])) {
        $ext = strtolower(pathinfo($_FILES['file_unduhan']['name'], PATHINFO_EXTENSION));
        if (in_array($ext, ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'zip', 'rar', 'jpg', 'png'])) {
            $namaFileAsli = str_replace([' ', '/', '\\'], '_', strtolower($_FILES['file_unduhan']['name']));
            $namaFile = time() . '-' . $namaFileAsli;
            $destDir = __DIR__ . '/../assets/unduhan';
            if (!is_dir($destDir)) { mkdir($destDir, 0755, true); }
            move_uploaded_file($_FILES['file_unduhan']['tmp_name'], $destDir . '/' . $namaFile);
            $path_file = $namaFile;
        } else {
            $error = 'Format file tidak diizinkan.';
        }
    }

    if (!$error && !$path_file) {
        $error = 'Anda harus mengunggah file.';
    }

    if (!$error) {
        if ($edit) {
            $stmt = $pdo->prepare("UPDATE unduhan SET nama_file=?, keterangan=?, path_file=? WHERE id=?");
            $stmt->execute([$nama, $keterangan, $path_file, $id]);
        } else {
            $stmt = $pdo->prepare("INSERT INTO unduhan (nama_file, keterangan, path_file) VALUES (?, ?, ?)");
            $stmt->execute([$nama, $keterangan, $path_file]);
        }
        header('Location: unduhan.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= $edit ? 'Edit' : 'Tambah' ?> Unduhan - SMAI MQ</title>
<link rel="icon" type="image/png" href="../assets/img/logo.png">
<link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
<div class="admin-shell">
    <?php require __DIR__ . '/includes_sidebar.php'; ?>
    <div class="admin-konten">
        <div class="admin-topbar">
            <h1><?= $edit ? 'Edit' : 'Tambah' ?> Unduhan</h1>
            <a href="unduhan.php" class="btn btn-luar">&larr; Kembali</a>
        </div>

        <div class="kartu-admin" style="max-width:700px;">
            <?php if ($error): ?>
                <div class="pesan-status gagal"><?= e($error) ?></div>
            <?php endif; ?>
            <form method="post" action="" enctype="multipart/form-data">
                <div class="form-grup">
                    <label>Nama / Judul Dokumen</label>
                    <input type="text" name="nama_file" class="form-input" value="<?= $edit ? e($edit['nama_file']) : '' ?>" required>
                </div>
                <div class="form-grup">
                    <label>Keterangan Tambahan (Opsional)</label>
                    <input type="text" name="keterangan" class="form-input" value="<?= $edit ? e($edit['keterangan']) : '' ?>">
                </div>
                <div class="form-grup">
                    <label>File Dokumen <?= $edit ? '<small>(Biarkan kosong jika tidak ingin mengubah file)</small>' : '' ?></label>
                    <?php if ($edit && $edit['path_file']): ?>
                        <div style="margin-bottom:10px; font-size:14px;">
                            File saat ini: <strong><?= e($edit['path_file']) ?></strong>
                        </div>
                    <?php endif; ?>
                    <input type="file" name="file_unduhan" class="form-input" <?= $edit ? '' : 'required' ?>>
                </div>
                <button type="submit" class="btn btn-emas">Simpan Data</button>
            </form>
        </div>
    </div>
</div>
</body>
</html>
