<?php
session_start();
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';

if (is_admin_logged_in()) {
    header('Location: dashboard.php');
    exit;
}

$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    $stmt = $pdo->prepare("SELECT * FROM admin WHERE username = ? LIMIT 1");
    $stmt->execute([$username]);
    $admin = $stmt->fetch();

    if ($admin && password_verify($password, $admin['password'])) {
        $_SESSION['admin_id'] = $admin['id'];
        $_SESSION['admin_nama'] = $admin['nama_lengkap'];
        header('Location: dashboard.php');
        exit;
    }
    $error = 'Username atau password salah.';
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Masuk Admin - SMAI MQ</title>
<link rel="icon" type="image/png" href="../assets/img/logo.png">
<link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
<div class="halaman-login">
    <div class="kotak-login">
        <img src="../assets/img/logo.png" alt="Logo SMAI MQ" style="width:64px; height:auto; display:block; margin:0 auto 16px; filter:drop-shadow(0 2px 6px rgba(0,0,0,0.15));">
        <h1>Masuk ke Panel Admin</h1>
        <p style="color:var(--abu); font-size:0.88rem; margin-top:-8px;">Website SMAI MQ</p>
        <?php if ($error): ?>
            <div class="pesan-status gagal"><?= e($error) ?></div>
        <?php endif; ?>
        <form method="post" class="form-admin">
            <div class="grup-form">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required autofocus>
            </div>
            <div class="grup-form">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-emas" style="width:100%;">Masuk</button>
        </form>
        <p style="font-size:0.78rem; color:var(--abu); margin-top:16px;">Default: admin / admin123 (segera ganti setelah instalasi).</p>
    </div>
</div>
</body>
</html>
