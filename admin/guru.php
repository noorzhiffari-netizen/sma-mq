<?php
session_start();
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';
require_admin_login();

$halaman = 'guru';

// Hapus
if (isset($_GET['hapus'])) {
    $id = (int) $_GET['hapus'];
    $stmt = $pdo->prepare("SELECT foto FROM guru WHERE id = ?");
    $stmt->execute([$id]);
    $g = $stmt->fetch();
    if ($g && !empty($g['foto']) && $g['foto'] !== 'kepsek.jpg') {
        $path = __DIR__ . '/../assets/img/guru/' . $g['foto'];
        if (file_exists($path)) { @unlink($path); }
    }
    $del = $pdo->prepare("DELETE FROM guru WHERE id = ?");
    $del->execute([$id]);
    header('Location: guru.php?dihapus=1');
    exit;
}

// Filter
$kategoriFilter = $_GET['kategori'] ?? 'Semua';
if (in_array($kategoriFilter, ['Guru', 'Tata Usaha', 'Karyawan'])) {
    $stmt = $pdo->prepare("SELECT * FROM guru WHERE kategori = ? ORDER BY urutan ASC, id ASC");
    $stmt->execute([$kategoriFilter]);
    $daftar = $stmt->fetchAll();
} else {
    $daftar = $pdo->query("SELECT * FROM guru ORDER BY urutan ASC, id ASC")->fetchAll();
    $kategoriFilter = 'Semua';
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Kelola Guru &amp; Staff - SMAI MQ</title>
<link rel="icon" type="image/png" href="../assets/img/logo.png">
<link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
<div class="admin-shell">
    <?php require __DIR__ . '/includes_sidebar.php'; ?>
    <div class="admin-konten">
        <div class="admin-topbar">
            <h1>Kelola Guru &amp; Tenaga Kependidikan</h1>
            <a href="guru-form.php" class="btn btn-emas">+ Tambah Personil</a>
        </div>

        <?php if (isset($_GET['tersimpan'])): ?>
            <div class="pesan-status sukses">Data guru/staff berhasil disimpan!</div>
        <?php elseif (isset($_GET['dihapus'])): ?>
            <div class="pesan-status sukses">Data guru/staff berhasil dihapus.</div>
        <?php endif; ?>

        <!-- Filter Tab Kategori -->
        <div style="display:flex; gap:10px; margin-bottom:20px; flex-wrap:wrap;">
            <a href="guru.php" class="btn btn-kecil <?= $kategoriFilter === 'Semua' ? 'btn-emas' : 'btn-luar' ?>">Semua</a>
            <a href="guru.php?kategori=Guru" class="btn btn-kecil <?= $kategoriFilter === 'Guru' ? 'btn-emas' : 'btn-luar' ?>">Dewan Guru</a>
            <a href="guru.php?kategori=Tata+Usaha" class="btn btn-kecil <?= $kategoriFilter === 'Tata Usaha' ? 'btn-emas' : 'btn-luar' ?>">Tata Usaha</a>
            <a href="guru.php?kategori=Karyawan" class="btn btn-kecil <?= $kategoriFilter === 'Karyawan' ? 'btn-emas' : 'btn-luar' ?>">Karyawan &amp; Musyrif</a>
        </div>

        <div class="kartu-admin">
            <table class="tabel-admin">
                <thead>
                    <tr>
                        <th style="width:50px;">No</th>
                        <th style="width:80px;">Foto</th>
                        <th>Nama &amp; Gelar</th>
                        <th>Kategori</th>
                        <th>Jabatan</th>
                        <th>Bidang / Tugas</th>
                        <th style="width:60px;">Urut</th>
                        <th style="width:140px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($daftar): foreach ($daftar as $i => $d): 
                        $badgeStyle = 'background:#eaf4ed; color:#1e5a3b;';
                        if ($d['kategori'] === 'Tata Usaha') {
                            $badgeStyle = 'background:#fdf5e6; color:#b4863a;';
                        } elseif ($d['kategori'] === 'Karyawan') {
                            $badgeStyle = 'background:#ebf3f7; color:#235d7a;';
                        }
                    ?>
                    <tr>
                        <td><?= $i + 1 ?></td>
                        <td>
                            <?php if (!empty($d['foto']) && file_exists(__DIR__ . '/../assets/img/guru/' . $d['foto'])): ?>
                                <img src="../assets/img/guru/<?= e($d['foto']) ?>" alt="" style="width:54px; height:54px; object-fit:cover; border-radius:50%; border:2px solid #ddd;">
                            <?php else: ?>
                                <div style="width:54px; height:54px; border-radius:50%; background:#eee; display:flex; align-items:center; justify-content:center; font-size:1.2rem;">👤</div>
                            <?php endif; ?>
                        </td>
                        <td>
                            <strong><?= e($d['nama']) ?></strong>
                            <?php if (!empty($d['pendidikan'])): ?>
                                <small style="display:block; color:#777;">🎓 <?= e($d['pendidikan']) ?></small>
                            <?php endif; ?>
                        </td>
                        <td><span style="font-size:0.75rem; font-weight:700; padding:4px 10px; border-radius:12px; <?= $badgeStyle ?>"><?= e($d['kategori'] ?? 'Guru') ?></span></td>
                        <td><?= e($d['jabatan']) ?></td>
                        <td><small><?= e($d['bidang_studi']) ?></small></td>
                        <td><?= (int)$d['urutan'] ?></td>
                        <td>
                            <a href="guru-form.php?id=<?= $d['id'] ?>" class="btn btn-luar btn-kecil">Ubah</a>
                            <a href="guru.php?hapus=<?= $d['id'] ?>" class="btn btn-hapus btn-kecil" onclick="return confirm('Hapus data ini?');">Hapus</a>
                        </td>
                    </tr>
                    <?php endforeach; else: ?>
                    <tr><td colspan="8">Belum ada data guru / staff pada kategori ini.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</body>
</html>
