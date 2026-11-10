<?php
session_start();
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';
require_admin_login();

$halaman = 'alumni';
$id = $_GET['id'] ?? null;
$edit = null;

if ($id) {
    $stmt = $pdo->prepare("SELECT * FROM alumni WHERE id = ?");
    $stmt->execute([$id]);
    $edit = $stmt->fetch();
    if (!$edit) {
        header('Location: alumni.php');
        exit;
    }
}

$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = trim($_POST['nama_lengkap']);
    $tahun = (int) $_POST['tahun_lulus'];
    $pekerjaan = trim($_POST['pekerjaan_kuliah']);
    $testimoni = trim($_POST['testimoni']);
    $status = $_POST['status'] === 'terbit' ? 'terbit' : 'draft';
    $foto = $edit['foto'] ?? null;

    if (!empty($_FILES['foto']['name'])) {
        $ext = strtolower(pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION));
        if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp'])) {
            $namaFile = 'alumni-' . time() . '.' . $ext;
            $destDir = __DIR__ . '/../assets/img/alumni';
            if (!is_dir($destDir)) { mkdir($destDir, 0755, true); }
            move_uploaded_file($_FILES['foto']['tmp_name'], $destDir . '/' . $namaFile);
            $foto = $namaFile;
        } else {
            $error = 'Format file gambar harus JPG, PNG, atau WEBP.';
        }
    }

    if (!$error) {
        if ($edit) {
            $stmt = $pdo->prepare("UPDATE alumni SET nama_lengkap=?, tahun_lulus=?, pekerjaan_kuliah=?, testimoni=?, status=?, foto=? WHERE id=?");
            $stmt->execute([$nama, $tahun, $pekerjaan, $testimoni, $status, $foto, $id]);
        } else {
            $stmt = $pdo->prepare("INSERT INTO alumni (nama_lengkap, tahun_lulus, pekerjaan_kuliah, testimoni, status, foto) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([$nama, $tahun, $pekerjaan, $testimoni, $status, $foto]);
        }
        header('Location: alumni.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= $edit ? 'Edit' : 'Tambah' ?> Testimoni Alumni - SMAI MQ</title>
<link rel="icon" type="image/png" href="../assets/img/logo.png">
<link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
<div class="admin-shell">
    <?php require __DIR__ . '/includes_sidebar.php'; ?>
    <div class="admin-konten">
        <div class="admin-topbar">
            <h1><?= $edit ? 'Edit' : 'Tambah' ?> Testimoni Alumni</h1>
            <a href="alumni.php" class="btn btn-luar">&larr; Kembali</a>
        </div>

        <div class="kartu-admin" style="max-width:700px;">
            <?php if ($error): ?>
                <div class="pesan-status gagal"><?= e($error) ?></div>
            <?php endif; ?>
            <form method="post" action="" enctype="multipart/form-data">
                <div class="form-grup">
                    <label>Nama Lengkap Alumni</label>
                    <input type="text" name="nama_lengkap" class="form-input" value="<?= $edit ? e($edit['nama_lengkap']) : '' ?>" required>
                </div>
                <div class="form-grup">
                    <label>Tahun Lulus (YYYY)</label>
                    <input type="number" name="tahun_lulus" class="form-input" value="<?= $edit ? e($edit['tahun_lulus']) : date('Y') ?>" required>
                </div>
                <div class="form-grup">
                    <label>Pekerjaan / Tempat Kuliah</label>
                    <input type="text" name="pekerjaan_kuliah" class="form-input" value="<?= $edit ? e($edit['pekerjaan_kuliah']) : '' ?>" placeholder="Cth: Mahasiswa UGM, Wiraswasta, dll">
                </div>
                <div class="form-grup">
                    <label>Pesan Testimoni (Opsional)</label>
                    <textarea name="testimoni" class="form-input" style="height:120px;"><?= $edit ? e($edit['testimoni']) : '' ?></textarea>
                </div>
                <div class="form-grup">
                    <label>Status Tampil</label>
                    <select name="status" class="form-input">
                        <option value="terbit" <?= ($edit && $edit['status'] === 'terbit') ? 'selected' : '' ?>>Terbit (Tampil di web)</option>
                        <option value="draft" <?= ($edit && $edit['status'] === 'draft') ? 'selected' : '' ?>>Draft (Sembunyikan)</option>
                    </select>
                </div>
                <div class="form-grup">
                    <label>Foto Profil / Wajah <?= $edit ? '<small>(Biarkan kosong jika tidak ingin mengubah)</small>' : '' ?></label>
                    <?php if ($edit && $edit['foto']): ?>
                        <div style="margin-bottom:10px;">
                            <img src="../assets/img/alumni/<?= e($edit['foto']) ?>" alt="Foto" style="max-width:150px; border-radius:50%;">
                        </div>
                    <?php endif; ?>
                    <input type="file" name="foto" class="form-input" accept="image/*">
                </div>
                <button type="submit" class="btn btn-emas">Simpan Data</button>
            </form>
        </div>
    </div>
</div>
</body>
</html>
