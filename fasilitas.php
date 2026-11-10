<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';

$halaman_aktif = 'fasilitas';
$judul_halaman = 'Fasilitas';

$fasilitas = $pdo->query("SELECT * FROM fasilitas ORDER BY urutan")->fetchAll();

require __DIR__ . '/includes/header.php';
?>

<div class="header-halaman">
    <div class="wadah">
        <span class="jejak"><a href="index.php">Beranda</a> / Fasilitas</span>
        <h1>Fasilitas SMAI MQ</h1>
        <p style="color:#C9D3C6; max-width:60ch;">Sarana penunjang kegiatan akademik dan kepesantrenan yang mendukung keseharian santri di sekolah maupun di asrama.</p>
    </div>
</div>

<section>
    <div class="wadah">
        <div class="grid-3">
            <?php foreach ($fasilitas as $i => $f): ?>
            <div class="kartu-fasilitas">
                <div class="foto-wrapper">
                    <?php if (!empty($f['gambar']) && file_exists(__DIR__ . '/assets/img/fasilitas/' . $f['gambar'])): ?>
                        <img src="<?= BASE_URL ?>assets/img/fasilitas/<?= e($f['gambar']) ?>" alt="<?= e($f['nama_fasilitas']) ?>">
                    <?php else: ?>
                        <img src="<?= BASE_URL ?>assets/img/hero.jpg" alt="<?= e($f['nama_fasilitas']) ?>">
                    <?php endif; ?>
                </div>
                <div class="konten">
                    <span class="angka" style="font-family:'Fraunces',serif; font-style:italic; color:var(--emas); font-size:1.3rem; display:block; margin-bottom:8px;"><?= str_pad($i + 1, 2, '0', STR_PAD_LEFT) ?></span>
                    <h3><?= e($f['nama_fasilitas']) ?></h3>
                    <p><?= e($f['deskripsi']) ?></p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
