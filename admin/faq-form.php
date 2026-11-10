<?php
session_start();
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';
require_admin_login();

$halaman = 'faq';
$id = $_GET['id'] ?? null;
$edit = null;

if ($id) {
    $stmt = $pdo->prepare("SELECT * FROM faq WHERE id = ?");
    $stmt->execute([$id]);
    $edit = $stmt->fetch();
    if (!$edit) {
        header('Location: faq.php');
        exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pertanyaan = trim($_POST['pertanyaan']);
    $jawaban = trim($_POST['jawaban']);
    $urutan = (int) $_POST['urutan'];

    if ($edit) {
        $stmt = $pdo->prepare("UPDATE faq SET pertanyaan=?, jawaban=?, urutan=? WHERE id=?");
        $stmt->execute([$pertanyaan, $jawaban, $urutan, $id]);
    } else {
        $stmt = $pdo->prepare("INSERT INTO faq (pertanyaan, jawaban, urutan) VALUES (?, ?, ?)");
        $stmt->execute([$pertanyaan, $jawaban, $urutan]);
    }
    header('Location: faq.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= $edit ? 'Edit' : 'Tambah' ?> FAQ - SMAI MQ</title>
<link rel="icon" type="image/png" href="../assets/img/logo.png">
<link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
<div class="admin-shell">
    <?php require __DIR__ . '/includes_sidebar.php'; ?>
    <div class="admin-konten">
        <div class="admin-topbar">
            <h1><?= $edit ? 'Edit' : 'Tambah' ?> FAQ</h1>
            <a href="faq.php" class="btn btn-luar">&larr; Kembali</a>
        </div>

        <div class="kartu-admin" style="max-width:700px;">
            <form method="post" action="">
                <div class="form-grup">
                    <label>Pertanyaan</label>
                    <input type="text" name="pertanyaan" class="form-input" value="<?= $edit ? e($edit['pertanyaan']) : '' ?>" required>
                </div>
                <div class="form-grup">
                    <label>Jawaban</label>
                    <textarea name="jawaban" class="form-input" style="height:150px;" required><?= $edit ? e($edit['jawaban']) : '' ?></textarea>
                </div>
                <div class="form-grup">
                    <label>Urutan Tampil (Makin kecil makin di atas)</label>
                    <input type="number" name="urutan" class="form-input" value="<?= $edit ? e($edit['urutan']) : '0' ?>" style="width:100px;">
                </div>
                <button type="submit" class="btn btn-emas">Simpan Data</button>
            </form>
        </div>
    </div>
</div>
</body>
</html>
