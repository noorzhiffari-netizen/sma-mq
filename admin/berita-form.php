<?php
session_start();
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';
require_admin_login();

$halaman = 'berita';
$id = isset($_GET['id']) ? (int) $_GET['id'] : null;
$data = ['judul' => '', 'ringkasan' => '', 'konten' => '', 'penulis' => $_SESSION['admin_nama'], 'status' => 'terbit', 'gambar' => ''];
$error = null;

if ($id) {
    $stmt = $pdo->prepare("SELECT * FROM berita WHERE id = ?");
    $stmt->execute([$id]);
    $existing = $stmt->fetch();
    if ($existing) { $data = $existing; }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data['judul']     = trim($_POST['judul'] ?? '');
    $data['ringkasan'] = trim($_POST['ringkasan'] ?? '');
    $data['konten']    = trim($_POST['konten'] ?? '');
    $data['penulis']   = trim($_POST['penulis'] ?? '');
    $data['status']    = $_POST['status'] ?? 'draft';

    if ($data['judul'] === '' || $data['konten'] === '') {
        $error = 'Judul dan konten wajib diisi.';
    } else {
        $namaGambar = $data['gambar'] ?? null;

        // Upload gambar jika ada
        if (!empty($_FILES['gambar']['name'])) {
            $ekstensiDiizinkan = ['jpg', 'jpeg', 'png', 'webp'];
            $ekstensi = strtolower(pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION));
            if (!in_array($ekstensi, $ekstensiDiizinkan, true)) {
                $error = 'Format gambar harus JPG, PNG, atau WEBP.';
            } else {
                $namaGambar = 'berita-' . time() . '-' . mt_rand(100, 999) . '.' . $ekstensi;
                $tujuanDir = __DIR__ . '/../assets/img/berita';
                if (!is_dir($tujuanDir)) {
                    mkdir($tujuanDir, 0755, true);
                }
                move_uploaded_file($_FILES['gambar']['tmp_name'], $tujuanDir . '/' . $namaGambar);
            }
        }

        if (!$error) {
            $slug = buat_slug($data['judul']);
            if ($id) {
                $stmt = $pdo->prepare("UPDATE berita SET judul=?, slug=?, ringkasan=?, konten=?, penulis=?, status=?, gambar=? WHERE id=?");
                $stmt->execute([$data['judul'], $slug, $data['ringkasan'], $data['konten'], $data['penulis'], $data['status'], $namaGambar, $id]);
            } else {
                // pastikan slug unik
                $cek = $pdo->prepare("SELECT COUNT(*) c FROM berita WHERE slug = ?");
                $slugAsli = $slug; $i = 1;
                do {
                    $cek->execute([$slug]);
                    if ($cek->fetch()['c'] > 0) { $slug = $slugAsli . '-' . $i; $i++; } else { break; }
                } while (true);

                $stmt = $pdo->prepare("INSERT INTO berita (judul, slug, ringkasan, konten, penulis, status, gambar) VALUES (?, ?, ?, ?, ?, ?, ?)");
                $stmt->execute([$data['judul'], $slug, $data['ringkasan'], $data['konten'], $data['penulis'], $data['status'], $namaGambar]);
            }
            header('Location: berita.php?tersimpan=1');
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= $id ? 'Ubah' : 'Tulis' ?> Berita - SMAI MQ</title>
<link rel="icon" type="image/png" href="../assets/img/logo.png">
<link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
<div class="admin-shell">
    <?php require __DIR__ . '/includes_sidebar.php'; ?>
    <div class="admin-konten">
        <div class="admin-topbar">
            <h1><?= $id ? 'Ubah Berita' : 'Tulis Berita Baru' ?></h1>
            <a href="berita.php" class="btn btn-luar">&larr; Kembali</a>
        </div>

        <div class="kartu-admin">
            <?php if ($error): ?><div class="pesan-status gagal"><?= e($error) ?></div><?php endif; ?>
            <form method="post" enctype="multipart/form-data" class="form-admin">
                <div class="grup-form">
                    <label for="judul">Judul Berita</label>
                    <input type="text" id="judul" name="judul" required value="<?= e($data['judul']) ?>">
                </div>
                <div class="grup-form">
                    <label for="gambar">Foto / Gambar Sampul</label>
                    <?php if (!empty($data['gambar']) && file_exists(__DIR__ . '/../assets/img/berita/' . $data['gambar'])): ?>
                        <div style="margin-bottom:10px;">
                            <img src="../assets/img/berita/<?= e($data['gambar']) ?>" alt="Foto Berita" style="max-height:160px; border-radius:6px; border:1px solid #ddd; display:block;">
                            <small style="color:var(--abu);">Foto saat ini: <?= e($data['gambar']) ?> (Pilih file baru jika ingin mengganti)</small>
                        </div>
                    <?php endif; ?>
                    <input type="file" id="gambar" name="gambar" accept="image/*">
                </div>
                <div class="grup-form">
                    <label for="ringkasan">Ringkasan Singkat</label>
                    <input type="text" id="ringkasan" name="ringkasan" maxlength="300" value="<?= e($data['ringkasan']) ?>">
                </div>
                <div class="grup-form">
                    <label for="konten">Konten Berita</label>
                    <textarea id="konten" name="konten" style="min-height:180px;" required><?= e($data['konten']) ?></textarea>
                </div>
                <div class="grup-form">
                    <label for="penulis">Penulis</label>
                    <input type="text" id="penulis" name="penulis" value="<?= e($data['penulis']) ?>">
                </div>
                <div class="grup-form">
                    <label for="status">Status Publikasi</label>
                    <select id="status" name="status">
                        <option value="terbit" <?= $data['status'] === 'terbit' ? 'selected' : '' ?>>Terbit</option>
                        <option value="draft" <?= $data['status'] === 'draft' ? 'selected' : '' ?>>Draft</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-emas">Simpan Berita</button>
            </form>
        </div>
    </div>
</div>
</body>
</html>
