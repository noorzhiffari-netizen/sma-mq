<?php
session_start();
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';
require_admin_login();

$halaman = 'pendaftar';

if (isset($_POST['ubah_status'])) {
    $stmt = $pdo->prepare("UPDATE pendaftar SET status = ? WHERE id = ?");
    $stmt->execute([$_POST['status'], (int) $_POST['id']]);
    header('Location: pendaftar.php?diperbarui=1');
    exit;
}

// Pagination
$per_page = 10;
$page = isset($_GET['p']) ? (int)$_GET['p'] : 1;
if ($page < 1) $page = 1;
$offset = ($page - 1) * $per_page;

$total_stmt = $pdo->query("SELECT COUNT(*) FROM pendaftar");
$total_rows = $total_stmt->fetchColumn();
$total_pages = ceil($total_rows / $per_page);

$stmt = $pdo->prepare("SELECT * FROM pendaftar ORDER BY tanggal_daftar DESC LIMIT ? OFFSET ?");
$stmt->bindValue(1, $per_page, PDO::PARAM_INT);
$stmt->bindValue(2, $offset, PDO::PARAM_INT);
$stmt->execute();
$daftar = $stmt->fetchAll();

function badge_status($status) {
    $peta = ['Menunggu Verifikasi' => 'badge-emas', 'Diterima' => 'badge-hijau', 'Ditolak' => 'badge-merah'];
    return $peta[$status] ?? 'badge-emas';
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Pendaftar SPMB - SMAI MQ</title>
<link rel="icon" type="image/png" href="../assets/img/logo.png">
<link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
<div class="admin-shell">
    <?php require __DIR__ . '/includes_sidebar.php'; ?>
    <div class="admin-konten">
        <div class="admin-topbar">
            <h1>Pendaftar SPMB</h1>
            <a href="export-pendaftar.php" class="btn btn-emas">Export CSV</a>
        </div>
        <?php if (isset($_GET['diperbarui'])): ?><div class="pesan-status sukses">Status pendaftar berhasil diperbarui.</div><?php endif; ?>

        <div class="kartu-admin">
            <table class="tabel-admin">
                <thead>
                    <tr><th>No. Pendaftaran</th><th>Nama</th><th>Jenis Kelamin</th><th>Jurusan</th><th>No. HP</th><th>Asal Sekolah</th><th>Status</th><th>Aksi</th></tr>
                </thead>
                <tbody>
                <?php foreach ($daftar as $p): ?>
                    <tr>
                        <td><?= e($p['nomor_pendaftaran']) ?></td>
                        <td><?= e($p['nama_lengkap']) ?></td>
                        <td><?= e($p['jenis_kelamin']) ?></td>
                        <td><?= e($p['jurusan_pilihan']) ?></td>
                        <td><?= e($p['no_hp']) ?></td>
                        <td><?= e($p['asal_sekolah']) ?></td>
                        <td><span class="badge <?= badge_status($p['status']) ?>"><?= e($p['status']) ?></span></td>
                        <td>
                            <form method="post" style="display:flex; gap:6px;">
                                <input type="hidden" name="id" value="<?= $p['id'] ?>">
                                <select name="status" style="padding:6px; border-radius:4px; border:1px solid var(--garis); font-size:0.78rem;">
                                    <?php foreach (['Menunggu Verifikasi','Diterima','Ditolak'] as $s): ?>
                                        <option value="<?= $s ?>" <?= $p['status'] === $s ? 'selected' : '' ?>><?= $s ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <button type="submit" name="ubah_status" class="btn btn-luar btn-kecil">Simpan</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if (!$daftar): ?><tr><td colspan="8">Belum ada pendaftar.</td></tr><?php endif; ?>
                </tbody>
            </table>

            <?php if ($total_pages > 1): ?>
            <div style="margin-top:20px; display:flex; gap:10px;">
                <?php for ($i=1; $i<=$total_pages; $i++): ?>
                    <a href="?p=<?= $i ?>" class="btn <?= $i === $page ? 'btn-emas' : 'btn-luar' ?>" style="padding: 5px 10px;"><?= $i ?></a>
                <?php endfor; ?>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>
</body>
</html>
