<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';

$slug = $_GET['slug'] ?? '';
$stmt = $pdo->prepare("SELECT * FROM berita WHERE slug = ? AND status='terbit' LIMIT 1");
$stmt->execute([$slug]);
$b = $stmt->fetch();

if (!$b) {
    http_response_code(404);
    $halaman_aktif = 'berita';
    $judul_halaman = 'Berita Tidak Ditemukan';
    require __DIR__ . '/includes/header.php';
    echo '<section class="wadah"><h1>Berita tidak ditemukan</h1><p><a href="berita.php">Kembali ke daftar berita</a></p></section>';
    require __DIR__ . '/includes/footer.php';
    exit;
}

$halaman_aktif = 'berita';
$judul_halaman = $b['judul'];

$lainnya = $pdo->prepare("SELECT * FROM berita WHERE status='terbit' AND id != ? ORDER BY dibuat_pada DESC LIMIT 3");
$lainnya->execute([$b['id']]);
$lainnya = $lainnya->fetchAll();

require __DIR__ . '/includes/header.php';
?>

<div class="header-halaman">
    <div class="wadah">
        <span class="jejak"><a href="index.php">Beranda</a> / <a href="berita.php">Berita</a> / <?= e($b['judul']) ?></span>
        <h1><?= e($b['judul']) ?></h1>
        <p style="color:#C9D3C6;"><?= format_tanggal_indo($b['dibuat_pada']) ?> &middot; oleh <?= e($b['penulis']) ?></p>
    </div>
</div>

<section>
    <div class="wadah dua-kolom">
        <article>
            <?php if (!empty($b['gambar']) && file_exists(__DIR__ . '/assets/img/berita/' . $b['gambar'])): ?>
            <div style="border-radius:var(--radius); overflow:hidden; margin-bottom:28px; box-shadow:0 8px 24px rgba(0,0,0,0.08);">
                <img src="<?= BASE_URL ?>assets/img/berita/<?= e($b['gambar']) ?>" alt="<?= e($b['judul']) ?>" style="width:100%; max-height:420px; object-fit:cover; display:block;">
            </div>
            <?php endif; ?>
            <p style="font-size:1.05rem; line-height:1.75;"><?= nl2br(e($b['konten'])) ?></p>
            <p style="margin-top:32px;"><a href="berita.php" class="btn btn-luar">&larr; Kembali ke daftar berita</a></p>
        </article>
        <aside>
            <div class="kartu">
                <span class="panel-label">Berita Lainnya</span>
                <ul class="panel-daftar" style="margin-top:14px;">
                    <?php foreach ($lainnya as $l): ?>
                        <li><a href="berita-detail.php?slug=<?= e($l['slug']) ?>"><?= e($l['judul']) ?></a></li>
                    <?php endforeach; ?>
                    <?php if (!$lainnya): ?><li>Belum ada berita lain.</li><?php endif; ?>
                </ul>
            </div>
        </aside>
    </div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
