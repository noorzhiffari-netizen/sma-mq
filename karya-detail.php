<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';

$slug = $_GET['slug'] ?? '';
$stmt = $pdo->prepare("SELECT * FROM karya_siswa WHERE slug = ? AND status='terbit'");
$stmt->execute([$slug]);
$karya = $stmt->fetch();

if (!$karya) {
    header('Location: karya.php');
    exit;
}

$judul_halaman = e($karya['judul']);
$halaman_aktif = 'karya';
require_once __DIR__ . '/includes/header.php';
?>

<div class="kontainer" style="max-width: 800px; padding: 40px 15px;">
    <a href="karya.php" class="btn btn-luar" style="margin-bottom:20px; display:inline-block;">&larr; Kembali ke Mading</a>
    
    <div style="text-align:center; margin-bottom:30px;">
        <span style="display:inline-block; padding:4px 10px; background:var(--emas); color:white; font-size:0.9rem; border-radius:4px; margin-bottom:15px;"><?= e($karya['kategori']) ?></span>
        <h1 style="color:var(--biru-tua); font-size:2.5rem; margin-bottom:15px;"><?= e($karya['judul']) ?></h1>
        <p style="color:#888;">
            Oleh: <strong><?= e($karya['penulis']) ?></strong> <?= $karya['kelas'] ? '('.e($karya['kelas']).')' : '' ?> &nbsp;|&nbsp; 
            <?= date('d M Y', strtotime($karya['tanggal'])) ?>
        </p>
    </div>

    <?php if ($karya['gambar']): ?>
        <img src="assets/img/karya/<?= e($karya['gambar']) ?>" alt="<?= e($karya['judul']) ?>" style="width:100%; border-radius:10px; margin-bottom:30px; box-shadow: 0 4px 10px rgba(0,0,0,0.1);">
    <?php endif; ?>

    <div class="konten-artikel" style="line-height:1.8; color:#444; font-size:1.05rem;">
        <?= nl2br(e($karya['konten'])) ?>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
