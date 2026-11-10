<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';

$halaman_aktif = 'berita';
$judul_halaman = 'Berita';

// Pagination logic
$per_page = 9;
$page = isset($_GET['p']) ? (int)$_GET['p'] : 1;
if ($page < 1) $page = 1;
$offset = ($page - 1) * $per_page;

$total_stmt = $pdo->query("SELECT COUNT(*) FROM berita WHERE status='terbit'");
$total_rows = $total_stmt->fetchColumn();
$total_pages = ceil($total_rows / $per_page);

$stmt = $pdo->prepare("SELECT * FROM berita WHERE status='terbit' ORDER BY dibuat_pada DESC LIMIT ? OFFSET ?");
$stmt->bindValue(1, $per_page, PDO::PARAM_INT);
$stmt->bindValue(2, $offset, PDO::PARAM_INT);
$stmt->execute();
$berita = $stmt->fetchAll();

require __DIR__ . '/includes/header.php';
?>

<div class="header-halaman">
    <div class="wadah">
        <span class="jejak"><a href="index.php">Beranda</a> / Berita</span>
        <h1>Berita &amp; Pengumuman</h1>
        <p style="color:#C9D3C6; max-width:60ch;">Kabar dan informasi resmi seputar kegiatan SMAI MQ.</p>
    </div>
</div>

<section>
    <div class="wadah">
        <div class="grid-berita">
            <?php if ($berita): foreach ($berita as $b): ?>
                <div class="thumb">
                    <?php if (!empty($b['gambar']) && file_exists(__DIR__ . '/assets/img/berita/' . $b['gambar'])): ?>
                        <img src="<?= BASE_URL ?>assets/img/berita/<?= e($b['gambar']) ?>" alt="<?= e($b['judul']) ?>">
                    <?php else: ?>
                        SMAI MQ
                    <?php endif; ?>
                </div>
                <div class="isi">
                    <time><?= format_tanggal_indo($b['dibuat_pada']) ?></time>
                    <h3><?= e($b['judul']) ?></h3>
                    <p><?= e($b['ringkasan']) ?></p>
                    <span class="baca">Baca selengkapnya</span>
                </div>
            </a>
            <?php endforeach; else: ?>
                <p>Belum ada berita yang diterbitkan.</p>
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
