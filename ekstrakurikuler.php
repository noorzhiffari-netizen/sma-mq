<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';

$judul_halaman = 'Ekstrakurikuler - SMAI MQ';
require_once __DIR__ . '/includes/header.php';

$ekstrakurikuler = $pdo->query("SELECT * FROM ekstrakurikuler ORDER BY nama ASC")->fetchAll();
?>

<div class="kontainer">
    <div style="text-align:center; padding: 40px 0;">
        <h1 style="color:var(--biru-tua); font-size:2.5rem; margin-bottom:10px;">Kegiatan Ekstrakurikuler</h1>
        <p style="color:#555; max-width:600px; margin:0 auto;">Daftar kegiatan pengembangan diri santri di luar jam pelajaran formal.</p>
    </div>

    <?php if (!$ekstrakurikuler): ?>
        <div style="text-align:center; padding: 50px; background:#f9f9f9; border-radius:10px; margin-bottom:40px;">
            <p>Belum ada data ekstrakurikuler yang ditampilkan.</p>
        </div>
    <?php else: ?>
        <div style="display:grid; grid-template-columns:repeat(auto-fill, minmax(300px, 1fr)); gap:20px; margin-bottom:40px;">
            <?php foreach ($ekstrakurikuler as $e): ?>
                <div class="kartu-berita" style="border:1px solid #eee;">
                    <?php if ($e['gambar']): ?>
                        <img src="assets/img/ekstrakurikuler/<?= e($e['gambar']) ?>" alt="<?= e($e['nama']) ?>" style="width:100%; height:200px; object-fit:cover; border-bottom:1px solid #eee;">
                    <?php else: ?>
                        <div style="width:100%; height:200px; background:#f0f0f0; display:flex; align-items:center; justify-content:center; border-bottom:1px solid #eee;">
                            <span style="color:#aaa;">Gambar Tidak Tersedia</span>
                        </div>
                    <?php endif; ?>
                    <div style="padding:20px;">
                        <h3 style="margin-bottom:10px; color:var(--biru-tua);"><?= e($e['nama']) ?></h3>
                        <?php if ($e['jadwal']): ?>
                            <p style="font-size:0.9rem; color:var(--emas); margin-bottom:10px; font-weight:bold;">
                                🕒 Jadwal: <?= e($e['jadwal']) ?>
                            </p>
                        <?php endif; ?>
                        <?php if ($e['deskripsi']): ?>
                            <p style="color:#555; line-height:1.6; font-size:0.95rem;"><?= nl2br(e($e['deskripsi'])) ?></p>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
