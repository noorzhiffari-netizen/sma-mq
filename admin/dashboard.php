<?php
session_start();
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';
require_admin_login();

$halaman = 'dashboard';

$jumlah_guru      = $pdo->query("SELECT COUNT(*) c FROM guru")->fetch()['c'] ?? 0;
$jumlah_berita    = $pdo->query("SELECT COUNT(*) c FROM berita")->fetch()['c'] ?? 0;
$jumlah_galeri    = $pdo->query("SELECT COUNT(*) c FROM galeri")->fetch()['c'] ?? 0;
$jumlah_fasilitas = $pdo->query("SELECT COUNT(*) c FROM fasilitas")->fetch()['c'] ?? 0;
$jumlah_pendaftar = $pdo->query("SELECT COUNT(*) c FROM pendaftar")->fetch()['c'] ?? 0;
$jumlah_pesan     = $pdo->query("SELECT COUNT(*) c FROM pesan WHERE sudah_dibaca = 0")->fetch()['c'] ?? 0;

$pendaftar_terbaru = $pdo->query("SELECT * FROM pendaftar ORDER BY tanggal_daftar DESC LIMIT 5")->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dasbor Admin - SMAI MQ</title>
<link rel="icon" type="image/png" href="../assets/img/logo.png">
<link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
<div class="admin-shell">
    <?php require __DIR__ . '/includes_sidebar.php'; ?>
    <div class="admin-konten">
        <div class="admin-topbar">
            <h1>Dasbor Administrasi</h1>
            <span>Halo, <strong><?= e($_SESSION['admin_nama']) ?></strong></span>
        </div>

        <!-- Quick Access Kelola Konten & Foto -->
        <div style="background:linear-gradient(135deg, var(--hijau-tua), var(--hijau)); color:#fff; border-radius:var(--radius); padding:24px 28px; margin-bottom:24px; display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:16px;">
            <div style="display:flex; align-items:center; gap:18px;">
                <img src="../assets/img/logo.png" alt="Logo" style="width:52px; height:auto; filter:drop-shadow(0 2px 6px rgba(0,0,0,0.3));">
                <div>
                    <h2 style="margin:0 0 4px; font-size:1.3rem; color:#fff;">Manajemen Website SMAI MQ</h2>
                    <p style="margin:0; color:#C9D3C6; font-size:0.9rem;">Kelola seluruh personil guru, foto, logo, fasilitas, berita, galeri, dan data sekolah langsung dari panel ini.</p>
                </div>
            </div>
            <div style="display:flex; gap:10px; flex-wrap:wrap;">
                <a href="guru.php" class="btn btn-emas" style="font-size:0.88rem; padding:9px 18px;">Kelola Guru &amp; Staff</a>
                <a href="profil.php" class="btn btn-luar" style="font-size:0.88rem; padding:9px 18px; color:#fff; border-color:rgba(255,255,255,0.4);">Foto &amp; Profil</a>
                <a href="fasilitas.php" class="btn btn-luar" style="font-size:0.88rem; padding:9px 18px; color:#fff; border-color:rgba(255,255,255,0.4);">Fasilitas</a>
            </div>
        </div>

        <div class="grid-ringkas" style="grid-template-columns:repeat(auto-fit, minmax(160px, 1fr));">
            <div class="ringkas"><strong><?= $jumlah_guru ?></strong><span>Guru &amp; Staff</span></div>
            <div class="ringkas"><strong><?= $jumlah_berita ?></strong><span>Total Berita</span></div>
            <div class="ringkas"><strong><?= $jumlah_fasilitas ?></strong><span>Fasilitas</span></div>
            <div class="ringkas"><strong><?= $jumlah_galeri ?></strong><span>Foto Galeri</span></div>
            <div class="ringkas"><strong><?= $jumlah_pendaftar ?></strong><span>Pendaftar SPMB</span></div>
            <div class="ringkas"><strong><?= $jumlah_pesan ?></strong><span>Pesan Baru</span></div>
        </div>

        <div class="kartu-admin">
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:16px;">
                <h3 style="margin:0;">Pendaftar SPMB Terbaru</h3>
                <a href="pendaftar.php" class="btn btn-luar btn-kecil">Lihat Semua Pendaftar &rarr;</a>
            </div>
            <table class="tabel-admin">
                <thead><tr><th>No. Pendaftaran</th><th>Nama</th><th>Jurusan</th><th>No. HP</th><th>Status</th><th>Tanggal</th></tr></thead>
                <tbody>
                <?php foreach ($pendaftar_terbaru as $p): ?>
                    <tr>
                        <td><?= e($p['nomor_pendaftaran']) ?></td>
                        <td><?= e($p['nama_lengkap']) ?></td>
                        <td><?= e($p['jurusan_pilihan']) ?></td>
                        <td><?= e($p['no_hp']) ?></td>
                        <td><span class="badge badge-emas"><?= e($p['status']) ?></span></td>
                        <td><?= format_tanggal_indo($p['tanggal_daftar']) ?></td>
                    </tr>
                <?php endforeach; ?>
                <?php if (!$pendaftar_terbaru): ?><tr><td colspan="6">Belum ada pendaftar.</td></tr><?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</body>
</html>
