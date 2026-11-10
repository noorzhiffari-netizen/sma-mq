<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';

$judul_halaman = 'Karya Siswa (Mading Online)';
$halaman_aktif = 'karya';
require_once __DIR__ . '/includes/header.php';

// Cek apakah tabel ada
try {
    $karya = $pdo->query("SELECT * FROM karya_siswa WHERE status='terbit' ORDER BY tanggal DESC")->fetchAll();
} catch (PDOException $e) {
    $karya = [];
}
?>

<div class="kontainer">
    <div style="text-align:center; padding: 40px 0;">
        <h1 style="color:var(--biru-tua); font-size:2.5rem; margin-bottom:10px;">Mading Online & Karya Santri</h1>
        <p style="color:#555; max-width:600px; margin:0 auto;">Wadah publikasi karya tulis, puisi, opini, dan seni dari santri SMAI MQ.</p>
    </div>

    <?php if (!$karya): ?>
        <div style="text-align:center; padding: 50px; background:#f9f9f9; border-radius:10px; margin-bottom:40px;">
            <p>Belum ada karya santri yang dipublikasikan.</p>
        </div>
    <?php else: ?>
        <div style="display:grid; grid-template-columns:repeat(auto-fill, minmax(300px, 1fr)); gap:20px; margin-bottom:40px;">
            <?php foreach ($karya as $k): ?>
                <div class="kartu-berita" style="border:1px solid #eee; border-radius:8px; overflow:hidden;">
                    <?php if ($k['gambar']): ?>
                        <img src="assets/img/karya/<?= e($k['gambar']) ?>" alt="<?= e($k['judul']) ?>" style="width:100%; height:200px; object-fit:cover; border-bottom:1px solid #eee;">
                    <?php else: ?>
                        <div style="width:100%; height:200px; background:#f0f0f0; display:flex; align-items:center; justify-content:center; border-bottom:1px solid #eee;">
                            <span style="color:#aaa;">Karya Tulis</span>
                        </div>
                    <?php endif; ?>
                    <div style="padding:20px;">
                        <span style="display:inline-block; padding:4px 10px; background:var(--emas); color:white; font-size:0.8rem; border-radius:4px; margin-bottom:10px;"><?= e($k['kategori']) ?></span>
                        <h3 style="margin-bottom:10px; color:var(--biru-tua);">
                            <a href="karya-detail.php?slug=<?= e($k['slug']) ?>" style="text-decoration:none; color:inherit;"><?= e($k['judul']) ?></a>
                        </h3>
                        <p style="font-size:0.9rem; color:#888; margin-bottom:10px;">
                            Oleh: <strong><?= e($k['penulis']) ?></strong> <?= $k['kelas'] ? '('.e($k['kelas']).')' : '' ?><br>
                            <?= date('d M Y', strtotime($k['tanggal'])) ?>
                        </p>
                        <p style="color:#555; line-height:1.6; font-size:0.95rem;">
                            <?= e(substr(strip_tags($k['konten']), 0, 100)) ?>...
                        </p>
                        <a href="karya-detail.php?slug=<?= e($k['slug']) ?>" class="btn btn-luar" style="margin-top:15px; display:inline-block;">Baca Selengkapnya</a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
