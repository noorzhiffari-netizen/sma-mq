<?php
session_start();
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';
require_admin_login();

$halaman = 'karya';
$id = $_GET['id'] ?? null;
$edit = null;

if ($id) {
    $stmt = $pdo->prepare("SELECT * FROM karya_siswa WHERE id = ?");
    $stmt->execute([$id]);
    $edit = $stmt->fetch();
    if (!$edit) {
        header('Location: karya.php');
        exit;
    }
}

$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $judul = trim($_POST['judul']);
    $penulis = trim($_POST['penulis']);
    $kelas = trim($_POST['kelas']);
    $kategori = trim($_POST['kategori']);
    $konten = trim($_POST['konten']);
    $status = $_POST['status'];
    $tanggal = $_POST['tanggal'] ?: date('Y-m-d');
    
    // Slug generation
    $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $judul)));
    if (!$edit || $edit['judul'] !== $judul) {
        $slug .= '-' . time();
    } else {
        $slug = $edit['slug'];
    }

    $gambar = $edit['gambar'] ?? null;

    if (!empty($_FILES['gambar']['name'])) {
        $ext = strtolower(pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION));
        if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp'])) {
            $namaFile = 'karya-' . time() . '.' . $ext;
            $destDir = __DIR__ . '/../assets/img/karya';
            if (!is_dir($destDir)) { mkdir($destDir, 0755, true); }
            move_uploaded_file($_FILES['gambar']['tmp_name'], $destDir . '/' . $namaFile);
            $gambar = $namaFile;
        } else {
            $error = 'Format gambar harus JPG, PNG, atau WEBP.';
        }
    }

    if (!$error) {
        if ($edit) {
            $stmt = $pdo->prepare("UPDATE karya_siswa SET judul=?, slug=?, penulis=?, kelas=?, kategori=?, konten=?, gambar=?, status=?, tanggal=? WHERE id=?");
            $stmt->execute([$judul, $slug, $penulis, $kelas, $kategori, $konten, $gambar, $status, $tanggal, $id]);
        } else {
            $stmt = $pdo->prepare("INSERT INTO karya_siswa (judul, slug, penulis, kelas, kategori, konten, gambar, status, tanggal) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$judul, $slug, $penulis, $kelas, $kategori, $konten, $gambar, $status, $tanggal]);
        }
        header('Location: karya.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= $edit ? 'Edit' : 'Tambah' ?> Karya - SMAI MQ</title>
<link rel="icon" type="image/png" href="../assets/img/logo.png">
<link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
<div class="admin-shell">
    <?php require __DIR__ . '/includes_sidebar.php'; ?>
    <div class="admin-konten">
        <div class="admin-topbar">
            <h1><?= $edit ? 'Edit' : 'Tambah' ?> Karya Siswa</h1>
            <a href="karya.php" class="btn btn-luar">&larr; Kembali</a>
        </div>

        <div class="kartu-admin">
            <?php if ($error): ?>
                <div class="pesan-status gagal"><?= e($error) ?></div>
            <?php endif; ?>
            <form method="post" action="" enctype="multipart/form-data">
                <div style="display:grid; grid-template-columns: 2fr 1fr; gap: 20px;">
                    <div>
                        <div class="form-grup">
                            <label>Judul Karya</label>
                            <input type="text" name="judul" class="form-input" value="<?= $edit ? e($edit['judul']) : '' ?>" required>
                        </div>
                        <div class="form-grup">
                            <label>Konten / Isi Karya</label>
                            <textarea name="konten" class="form-input" style="height:300px;" required><?= $edit ? e($edit['konten']) : '' ?></textarea>
                            <small>Anda dapat memasukkan teks puisi, cerpen, artikel, dsb.</small>
                        </div>
                    </div>
                    <div>
                        <div class="form-grup">
                            <label>Kategori</label>
                            <select name="kategori" class="form-input">
                                <option value="Puisi" <?= ($edit['kategori'] ?? '') === 'Puisi' ? 'selected' : '' ?>>Puisi</option>
                                <option value="Cerpen" <?= ($edit['kategori'] ?? '') === 'Cerpen' ? 'selected' : '' ?>>Cerpen</option>
                                <option value="Artikel" <?= ($edit['kategori'] ?? '') === 'Artikel' ? 'selected' : '' ?>>Artikel/Opini</option>
                                <option value="Karya Seni" <?= ($edit['kategori'] ?? '') === 'Karya Seni' ? 'selected' : '' ?>>Karya Seni/Desain</option>
                            </select>
                        </div>
                        <div class="form-grup">
                            <label>Nama Penulis / Pembuat</label>
                            <input type="text" name="penulis" class="form-input" value="<?= $edit ? e($edit['penulis']) : '' ?>" required>
                        </div>
                        <div class="form-grup">
                            <label>Kelas (Opsional)</label>
                            <input type="text" name="kelas" class="form-input" value="<?= $edit ? e($edit['kelas']) : '' ?>" placeholder="Contoh: X IPA 1">
                        </div>
                        <div class="form-grup">
                            <label>Tanggal</label>
                            <input type="date" name="tanggal" class="form-input" value="<?= $edit ? e($edit['tanggal']) : date('Y-m-d') ?>">
                        </div>
                        <div class="form-grup">
                            <label>Gambar / Ilustrasi (Opsional)</label>
                            <?php if ($edit && $edit['gambar']): ?>
                                <div style="margin-bottom:10px;">
                                    <img src="../assets/img/karya/<?= e($edit['gambar']) ?>" alt="Cover" style="max-width:100%; border-radius:8px;">
                                </div>
                            <?php endif; ?>
                            <input type="file" name="gambar" class="form-input" accept="image/*">
                        </div>
                        <div class="form-grup">
                            <label>Status</label>
                            <select name="status" class="form-input">
                                <option value="terbit" <?= ($edit['status'] ?? '') === 'terbit' ? 'selected' : '' ?>>Terbit (Publik)</option>
                                <option value="draft" <?= ($edit['status'] ?? '') === 'draft' ? 'selected' : '' ?>>Draft (Sembunyikan)</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-emas" style="width:100%; margin-top:10px;">Simpan Karya</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
</body>
</html>
