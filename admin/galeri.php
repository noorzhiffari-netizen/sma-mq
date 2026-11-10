<?php
session_start();
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';
require_admin_login();

$halaman = 'galeri';
$error = null;

if (isset($_GET['hapus'])) {
    $stmt = $pdo->prepare("DELETE FROM galeri WHERE id = ?");
    $stmt->execute([(int) $_GET['hapus']]);
    header('Location: galeri.php?dihapus=1');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $judul     = trim($_POST['judul'] ?? '');
    $kategori  = trim($_POST['kategori'] ?? 'Kegiatan');
    $keterangan = trim($_POST['keterangan'] ?? '');
    $namaFile  = null;

    if ($judul === '') {
        $error = 'Judul foto wajib diisi.';
    } elseif (!empty($_FILES['gambar']['name'])) {
        $ekstensiDiizinkan = ['jpg', 'jpeg', 'png', 'webp'];
        $ekstensi = strtolower(pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION));
        if (!in_array($ekstensi, $ekstensiDiizinkan, true)) {
            $error = 'Format gambar harus JPG, PNG, atau WEBP.';
        } else {
            $namaFile = 'galeri-' . time() . '-' . mt_rand(100, 999) . '.' . $ekstensi;
            $tujuan = __DIR__ . '/../assets/img/galeri/' . $namaFile;
            if (!is_dir(__DIR__ . '/../assets/img/galeri')) {
                mkdir(__DIR__ . '/../assets/img/galeri', 0755, true);
            }
            move_uploaded_file($_FILES['gambar']['tmp_name'], $tujuan);
        }
    } else {
        $error = 'Silakan pilih berkas gambar.';
    }

    if (!$error) {
        $stmt = $pdo->prepare("INSERT INTO galeri (judul, kategori, gambar, keterangan) VALUES (?, ?, ?, ?)");
        $stmt->execute([$judul, $kategori, $namaFile, $keterangan]);
        header('Location: galeri.php?tersimpan=1');
        exit;
    }
}

$daftar = $pdo->query("SELECT * FROM galeri ORDER BY dibuat_pada DESC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Kelola Galeri - SMAI MQ</title>
<link rel="icon" type="image/png" href="../assets/img/logo.png">
<link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
<div class="admin-shell">
    <?php require __DIR__ . '/includes_sidebar.php'; ?>
    <div class="admin-konten">
        <div class="admin-topbar"><h1>Kelola Galeri</h1></div>

        <?php if (isset($_GET['dihapus'])): ?><div class="pesan-status sukses">Foto berhasil dihapus.</div><?php endif; ?>
        <?php if (isset($_GET['tersimpan'])): ?><div class="pesan-status sukses">Foto berhasil ditambahkan.</div><?php endif; ?>
        <?php if ($error): ?><div class="pesan-status gagal"><?= e($error) ?></div><?php endif; ?>

        <div class="kartu-admin">
            <h3 style="margin-top:0;">Tambah Foto Baru</h3>
            <form method="post" enctype="multipart/form-data" class="form-admin">
                <div class="grup-form"><label>Judul Foto</label><input type="text" name="judul" required></div>
                <div class="grup-form"><label>Kategori</label><input type="text" name="kategori" value="Kegiatan"></div>
                <div class="grup-form"><label>Keterangan</label><input type="text" name="keterangan"></div>
                <div class="grup-form"><label>Berkas Gambar (JPG/PNG/WEBP)</label><input type="file" name="gambar" accept=".jpg,.jpeg,.png,.webp" required></div>
                <button type="submit" class="btn btn-emas">Unggah Foto</button>
            </form>
        </div>

        <div class="kartu-admin">
            <table class="tabel-admin">
                <thead><tr><th style="width:90px;">Foto</th><th>Judul</th><th>Kategori</th><th>Berkas</th><th style="width:100px;">Aksi</th></tr></thead>
                <tbody>
                <?php foreach ($daftar as $g): ?>
                    <tr>
                        <td>
                            <?php if (!empty($g['gambar']) && file_exists(__DIR__ . '/../assets/img/galeri/' . $g['gambar'])): ?>
                                <img src="../assets/img/galeri/<?= e($g['gambar']) ?>" alt="" style="width:70px; height:45px; object-fit:cover; border-radius:4px; border:1px solid #ddd;">
                            <?php else: ?>
                                <span style="color:#aaa; font-size:0.75rem;">Tanpa Foto</span>
                            <?php endif; ?>
                        </td>
                        <td><strong><?= e($g['judul']) ?></strong></td>
                        <td><?= e($g['kategori']) ?></td>
                        <td><small><?= e($g['gambar']) ?></small></td>
                        <td><a class="btn btn-hapus btn-kecil" href="galeri.php?hapus=<?= $g['id'] ?>" onclick="return confirm('Hapus foto ini?');">Hapus</a></td>
                    </tr>
                <?php endforeach; ?>
                <?php if (!$daftar): ?><tr><td colspan="4">Belum ada foto.</td></tr><?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</body>
</html>
