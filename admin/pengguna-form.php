<?php
session_start();
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';
require_admin_login();

// Hanya superadmin
$admin_id = $_SESSION['admin_id'];
$stmt = $pdo->prepare("SELECT role FROM admin WHERE id = ?");
$stmt->execute([$admin_id]);
$current_admin = $stmt->fetch();
if ($current_admin['role'] !== 'superadmin') {
    die("Akses ditolak.");
}

$halaman = 'pengguna';
$id = $_GET['id'] ?? null;
$edit = null;

if ($id) {
    $stmt = $pdo->prepare("SELECT * FROM admin WHERE id = ?");
    $stmt->execute([$id]);
    $edit = $stmt->fetch();
    if (!$edit) {
        header('Location: pengguna.php');
        exit;
    }
}

$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $nama_lengkap = trim($_POST['nama_lengkap']);
    $role = $_POST['role'];
    $password = $_POST['password'];

    if ($edit) {
        if (!empty($password)) {
            $hashed = password_hash($password, PASSWORD_BCRYPT);
            $stmt = $pdo->prepare("UPDATE admin SET username=?, nama_lengkap=?, role=?, password=? WHERE id=?");
            $stmt->execute([$username, $nama_lengkap, $role, $hashed, $id]);
        } else {
            $stmt = $pdo->prepare("UPDATE admin SET username=?, nama_lengkap=?, role=? WHERE id=?");
            $stmt->execute([$username, $nama_lengkap, $role, $id]);
        }
    } else {
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM admin WHERE username = ?");
        $stmt->execute([$username]);
        if ($stmt->fetchColumn() > 0) {
            $error = "Username sudah digunakan!";
        } else {
            $hashed = password_hash($password, PASSWORD_BCRYPT);
            $stmt = $pdo->prepare("INSERT INTO admin (username, password, nama_lengkap, role) VALUES (?, ?, ?, ?)");
            $stmt->execute([$username, $hashed, $nama_lengkap, $role]);
        }
    }

    if (!$error) {
        header('Location: pengguna.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= $edit ? 'Edit' : 'Tambah' ?> Pengguna - SMAI MQ</title>
<link rel="icon" type="image/png" href="../assets/img/logo.png">
<link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
<div class="admin-shell">
    <?php require __DIR__ . '/includes_sidebar.php'; ?>
    <div class="admin-konten">
        <div class="admin-topbar">
            <h1><?= $edit ? 'Edit' : 'Tambah' ?> Pengguna</h1>
            <a href="pengguna.php" class="btn btn-luar">&larr; Kembali</a>
        </div>

        <div class="kartu-admin" style="max-width:600px;">
            <?php if ($error): ?>
                <div class="pesan-status gagal"><?= e($error) ?></div>
            <?php endif; ?>
            <form method="post" action="">
                <div class="form-grup">
                    <label>Username</label>
                    <input type="text" name="username" class="form-input" value="<?= $edit ? e($edit['username']) : '' ?>" required>
                </div>
                <div class="form-grup">
                    <label>Nama Lengkap</label>
                    <input type="text" name="nama_lengkap" class="form-input" value="<?= $edit ? e($edit['nama_lengkap']) : '' ?>" required>
                </div>
                <div class="form-grup">
                    <label>Role</label>
                    <select name="role" class="form-input" required>
                        <option value="superadmin" <?= ($edit && $edit['role'] === 'superadmin') ? 'selected' : '' ?>>Super Admin</option>
                        <option value="ppdb" <?= ($edit && $edit['role'] === 'ppdb') ? 'selected' : '' ?>>Panitia PPDB</option>
                    </select>
                </div>
                <div class="form-grup">
                    <label>Password <?= $edit ? '<small>(Kosongkan jika tidak ingin mengubah)</small>' : '' ?></label>
                    <input type="password" name="password" class="form-input" <?= $edit ? '' : 'required' ?>>
                </div>
                <button type="submit" class="btn btn-emas">Simpan Pengguna</button>
            </form>
        </div>
    </div>
</div>
</body>
</html>
