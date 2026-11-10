<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';

$judul_halaman = 'Prestasi - SMAI MQ';
require_once __DIR__ . '/includes/header.php';

$prestasi = $pdo->query("SELECT * FROM prestasi ORDER BY tanggal_meraih DESC")->fetchAll();
?>

<div class="kontainer">
    <div style="text-align:center; padding: 40px 0;">
        <h1 style="color:var(--biru-tua); font-size:2.5rem; margin-bottom:10px;">Prestasi Santri & Sekolah</h1>
        <p style="color:#555; max-width:600px; margin:0 auto;">Daftar penghargaan dan prestasi yang diraih oleh santri serta lembaga SMAI MQ.</p>
    </div>

    <?php if (!$prestasi): ?>
        <div style="text-align:center; padding: 50px; background:#f9f9f9; border-radius:10px; margin-bottom:40px;">
            <p>Belum ada data prestasi yang ditampilkan.</p>
        </div>
    <?php else: ?>
        <div style="display:grid; grid-template-columns:repeat(auto-fill, minmax(300px, 1fr)); gap:20px; margin-bottom:40px;">
            <?php foreach ($prestasi as $p): ?>
                <div class="kartu-berita" style="border:1px solid #eee;">
                    <?php if ($p['gambar']): ?>
                        <img src="assets/img/prestasi/<?= e($p['gambar']) ?>" alt="<?= e($p['judul']) ?>" style="width:100%; height:200px; object-fit:cover; border-bottom:1px solid #eee;">
                    <?php else: ?>
                        <div style="width:100%; height:200px; background:#f0f0f0; display:flex; align-items:center; justify-content:center; border-bottom:1px solid #eee;">
                            <span style="color:#aaa;">Gambar Tidak Tersedia</span>
                        </div>
                    <?php endif; ?>
                    <div style="padding:20px;">
                        <h3 style="margin-bottom:10px; color:var(--biru-tua);"><?= e($p['judul']) ?></h3>
                        <p style="font-size:0.9rem; color:#888; margin-bottom:10px;">
                            <strong>Tanggal:</strong> <?= date('d M Y', strtotime($p['tanggal_meraih'])) ?>
                        </p>
                        <?php if ($p['deskripsi']): ?>
                            <p style="color:#555; line-height:1.6; font-size:0.95rem;"><?= nl2br(e($p['deskripsi'])) ?></p>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
