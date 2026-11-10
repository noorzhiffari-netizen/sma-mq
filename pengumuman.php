<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';

$judul_halaman = 'Pengumuman & Agenda - SMAI MQ';
require_once __DIR__ . '/includes/header.php';

$pengumuman = $pdo->query("SELECT * FROM pengumuman ORDER BY dibuat_pada DESC")->fetchAll();
?>

<div class="kontainer">
    <div style="text-align:center; padding: 40px 0;">
        <h1 style="color:var(--biru-tua); font-size:2.5rem; margin-bottom:10px;">Pengumuman & Agenda Akademik</h1>
        <p style="color:#555; max-width:600px; margin:0 auto;">Papan informasi seputar agenda sekolah, jadwal ujian, libur, dan info penting lainnya untuk wali murid dan santri.</p>
    </div>

    <?php if (!$pengumuman): ?>
        <div style="text-align:center; padding: 50px; background:#f9f9f9; border-radius:10px; margin-bottom:40px;">
            <p>Belum ada pengumuman yang diterbitkan.</p>
        </div>
    <?php else: ?>
        <div style="max-width:800px; margin:0 auto 40px auto; display:flex; flex-direction:column; gap:20px;">
            <?php foreach ($pengumuman as $p): ?>
                <div style="background:#fff; border:1px solid #ddd; border-left:5px solid var(--emas); padding:20px; border-radius:8px; box-shadow:0 2px 8px rgba(0,0,0,0.05);">
                    <div style="color:#888; font-size:0.85rem; margin-bottom:5px;">
                        🕒 <?= date('d F Y, H:i', strtotime($p['dibuat_pada'])) ?>
                    </div>
                    <h3 style="color:var(--biru-tua); margin-bottom:15px; font-size:1.3rem;"><?= e($p['judul']) ?></h3>
                    <div style="color:#444; line-height:1.6; font-size:0.95rem;">
                        <?= nl2br(e($p['isi'])) ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
