<?php
session_start();
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';
require_admin_login();

$halaman = 'fasilitas';
$id = isset($_GET['id']) ? (int) $_GET['id'] : null;
$data = ['nama_fasilitas' => '', 'deskripsi' => '', 'urutan' => 0, 'gambar' => ''];
$error = null;

if ($id) {
    $stmt = $pdo->prepare("SELECT * FROM fasilitas WHERE id = ?");
    $stmt->execute([$id]);
    $existing = $stmt->fetch();
    if ($existing) { $data = $existing; }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data['nama_fasilitas'] = trim($_POST['nama_fasilitas'] ?? '');
    $data['deskripsi']      = trim($_POST['deskripsi'] ?? '');
    $data['urutan']         = (int) ($_POST['urutan'] ?? 0);

    if ($data['nama_fasilitas'] === '') {
        $error = 'Nama fasilitas wajib diisi.';
    } else {
        $namaGambar = $data['gambar'] ?? null;

        // Upload gambar fasilitas
        if (!empty($_FILES['gambar']['name'])) {
            $ekstensiDiizinkan = ['jpg', 'jpeg', 'png', 'webp'];
            $ekstensi = strtolower(pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION));
            if (!in_array($ekstensi, $ekstensiDiizinkan, true)) {
                $error = 'Format gambar harus JPG, PNG, atau WEBP.';
            } else {
                $namaGambar = 'fasilitas-' . time() . '-' . mt_rand(100, 999) . '.' . $ekstensi;
                $tujuanDir = __DIR__ . '/../assets/img/fasilitas';
                if (!is_dir($tujuanDir)) {
                    mkdir($tujuanDir, 0755, true);
                }
                move_uploaded_file($_FILES['gambar']['tmp_name'], $tujuanDir . '/' . $namaGambar);
            }
        }

        if (!$error) {
            if ($id) {
                $stmt = $pdo->prepare("UPDATE fasilitas SET nama_fasilitas=?, deskripsi=?, urutan=?, gambar=? WHERE id=?");
                $stmt->execute([$data['nama_fasilitas'], $data['deskripsi'], $data['urutan'], $namaGambar, $id]);
            } else {
                $stmt = $pdo->prepare("INSERT INTO fasilitas (nama_fasilitas, deskripsi, urutan, gambar) VALUES (?, ?, ?, ?)");
                $stmt->execute([$data['nama_fasilitas'], $data['deskripsi'], $data['urutan'], $namaGambar]);
            }
            header('Location: fasilitas.php?tersimpan=1');
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= $id ? 'Ubah' : 'Tambah' ?> Fasilitas - SMAI MQ</title>
<link rel="icon" type="image/png" href="../assets/img/logo.png">
<link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
<div class="admin-shell">
    <?php require __DIR__ . '/includes_sidebar.php'; ?>
    <div class="admin-konten">
        <div class="admin-topbar">
            <h1><?= $id ? 'Ubah Data Fasilitas' : 'Tambah Fasilitas Baru' ?></h1>
            <a href="fasilitas.php" class="btn btn-luar">&larr; Kembali</a>
        </div>

        <div class="kartu-admin">
            <?php if ($error): ?><div class="pesan-status gagal"><?= e($error) ?></div><?php endif; ?>
            <form method="post" enctype="multipart/form-data" class="form-admin">
                <div class="grup-form">
                    <label for="nama_fasilitas">Nama Fasilitas</label>
                    <input type="text" id="nama_fasilitas" name="nama_fasilitas" required value="<?= e($data['nama_fasilitas']) ?>" placeholder="Contoh: Laboratorium IPA, Masjid, Lapangan...">
                </div>
                <div class="grup-form">
                    <label for="gambar">Foto Fasilitas</label>
                    <?php if (!empty($data['gambar']) && file_exists(__DIR__ . '/../assets/img/fasilitas/' . $data['gambar'])): ?>
                        <div style="margin-bottom:10px;">
                            <img src="../assets/img/fasilitas/<?= e($data['gambar']) ?>" alt="Foto Fasilitas" style="max-height:160px; border-radius:6px; border:1px solid #ddd; display:block;">
                            <small style="color:var(--abu);">Foto saat ini: <?= e($data['gambar']) ?> (Pilih file baru jika ingin mengganti)</small>
                        </div>
                    <?php endif; ?>
                    <input type="file" id="gambar" name="gambar" accept="image/*">
                </div>
                <div class="grup-form">
                    <label for="deskripsi">Deskripsi Fasilitas</label>
                    <textarea id="deskripsi" name="deskripsi" style="min-height:120px;" placeholder="Penjelasan sarana dan kegunaan fasilitas bagi santri..."><?= e($data['deskripsi']) ?></textarea>
                </div>
                <div class="grup-form">
                    <label for="urutan">Nomor Urut Tampilan</label>
                    <input type="number" id="urutan" name="urutan" value="<?= (int)$data['urutan'] ?>">
                </div>
                <button type="submit" class="btn btn-emas">Simpan Fasilitas</button>
            </form>
        </div>
    </div>
</div>
</body>
</html>
