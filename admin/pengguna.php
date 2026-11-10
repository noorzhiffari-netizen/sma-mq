<?php
session_start();
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';
require_admin_login();

// Hanya superadmin yang boleh akses halaman ini
$admin_id = $_SESSION['admin_id'];
$stmt = $pdo->prepare("SELECT role FROM admin WHERE id = ?");
$stmt->execute([$admin_id]);
$current_admin = $stmt->fetch();
if ($current_admin['role'] !== 'superadmin') {
    die("Akses ditolak. Anda tidak memiliki izin untuk halaman ini.");
}

$halaman = 'pengguna';

if (isset($_GET['hapus'])) {
    $id_hapus = (int) $_GET['hapus'];
    if ($id_hapus !== $admin_id) { // Jangan biarkan hapus diri sendiri
        $stmt = $pdo->prepare("DELETE FROM admin WHERE id = ?");
        $stmt->execute([$id_hapus]);
        header('Location: pengguna.php?pesan=hapus_sukses');
        exit;
    }
}

$pengguna = $pdo->query("SELECT * FROM admin ORDER BY created_at DESC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Manajemen Pengguna Admin - SMAI MQ</title>
<link rel="icon" type="image/png" href="../assets/img/logo.png">
<link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
<div class="admin-shell">
    <?php require __DIR__ . '/includes_sidebar.php'; ?>
    <div class="admin-konten">
        <div class="admin-topbar">
            <h1>Manajemen Pengguna Admin</h1>
            <a href="pengguna-form.php" class="btn btn-emas">+ Tambah Pengguna</a>
        </div>
        
        <?php if (isset($_GET['pesan']) && $_GET['pesan'] === 'hapus_sukses'): ?>
            <div class="pesan-status sukses">Pengguna berhasil dihapus.</div>
        <?php endif; ?>

        <div class="kartu-admin">
            <table class="tabel-admin">
                <thead>
                    <tr>
                        <th>Username</th>
                        <th>Nama Lengkap</th>
                        <th>Role</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($pengguna as $p): ?>
                    <tr>
                        <td><?= e($p['username']) ?></td>
                        <td><?= e($p['nama_lengkap']) ?></td>
                        <td>
                            <?php if($p['role'] === 'superadmin'): ?>
                                <span class="badge badge-hijau">Super Admin</span>
                            <?php else: ?>
                                <span class="badge badge-emas">Panitia PPDB</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="pengguna-form.php?id=<?= $p['id'] ?>" class="btn btn-luar btn-kecil">Edit</a>
                            <?php if ($p['id'] !== $admin_id): ?>
                            <a href="?hapus=<?= $p['id'] ?>" class="btn btn-merah btn-kecil" onclick="return confirm('Yakin ingin menghapus pengguna ini?');">Hapus</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</body>
</html>
