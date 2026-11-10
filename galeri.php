<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';

$halaman_aktif = 'galeri';
$judul_halaman = 'Galeri';

// Pagination logic
$per_page = 12;
$page = isset($_GET['p']) ? (int)$_GET['p'] : 1;
if ($page < 1) $page = 1;
$offset = ($page - 1) * $per_page;

$total_stmt = $pdo->query("SELECT COUNT(*) FROM galeri");
$total_rows = $total_stmt->fetchColumn();
$total_pages = ceil($total_rows / $per_page);

$stmt = $pdo->prepare("SELECT * FROM galeri ORDER BY dibuat_pada DESC LIMIT ? OFFSET ?");
$stmt->bindValue(1, $per_page, PDO::PARAM_INT);
$stmt->bindValue(2, $offset, PDO::PARAM_INT);
$stmt->execute();
$galeri = $stmt->fetchAll();

require __DIR__ . '/includes/header.php';
?>

<div class="header-halaman">
    <div class="wadah">
        <span class="jejak"><a href="index.php">Beranda</a> / Galeri</span>
        <h1>Galeri Kegiatan</h1>
        <p style="color:#C9D3C6; max-width:60ch;">Dokumentasi kegiatan akademik, kepesantrenan, dan keseharian santri di asrama.</p>
    </div>
</div>

<section>
    <div class="wadah">
        <div class="grid-galeri">
            <?php foreach ($galeri as $g): ?>
            <div class="item-galeri">
                <?php if (!empty($g['gambar']) && file_exists(__DIR__ . '/assets/img/galeri/' . $g['gambar'])): ?>
                    <img src="<?= BASE_URL ?>assets/img/galeri/<?= e($g['gambar']) ?>" alt="<?= e($g['judul']) ?>">
                <?php else: ?>
                    <img src="<?= BASE_URL ?>assets/img/hero.jpg" alt="<?= e($g['judul']) ?>">
                <?php endif; ?>
                <span><?= e($g['judul']) ?></span>
            </div>
            <?php endforeach; ?>
            <?php if (!$galeri): ?>
                <p>Belum ada galeri yang diunggah.</p>
            <?php endif; ?>
        </div>
        
        <?php if ($total_pages > 1): ?>
        <div style="margin-top:40px; display:flex; gap:10px; justify-content:center;">
            <?php for ($i=1; $i<=$total_pages; $i++): ?>
                <a href="?p=<?= $i ?>" class="btn <?= $i === $page ? 'btn-emas' : 'btn-luar' ?>" style="padding: 8px 15px;"><?= $i ?></a>
            <?php endfor; ?>
        </div>
        <?php endif; ?>

    </div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
