<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';

$judul_halaman = 'Unduhan - SMAI MQ';
require_once __DIR__ . '/includes/header.php';

$unduhan = $pdo->query("SELECT * FROM unduhan ORDER BY dibuat_pada DESC")->fetchAll();
?>

<div class="kontainer">
    <div style="text-align:center; padding: 40px 0;">
        <h1 style="color:var(--biru-tua); font-size:2.5rem; margin-bottom:10px;">Pusat Unduhan</h1>
        <p style="color:#555; max-width:600px; margin:0 auto;">Download dokumen-dokumen penting, brosur, formulir, atau tata tertib sekolah.</p>
    </div>

    <?php if (!$unduhan): ?>
        <div style="text-align:center; padding: 50px; background:#f9f9f9; border-radius:10px; margin-bottom:40px;">
            <p>Belum ada file yang tersedia untuk diunduh.</p>
        </div>
    <?php else: ?>
        <div style="max-width:800px; margin:0 auto 40px auto;">
            <div style="background:#fff; border:1px solid #ddd; border-radius:8px; overflow:hidden;">
                <table style="width:100%; border-collapse:collapse; text-align:left;">
                    <thead style="background:var(--biru-tua); color:#fff;">
                        <tr>
                            <th style="padding:15px; border-bottom:2px solid #ddd;">Nama File / Dokumen</th>
                            <th style="padding:15px; border-bottom:2px solid #ddd;">Keterangan</th>
                            <th style="padding:15px; border-bottom:2px solid #ddd; text-align:center;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($unduhan as $u): ?>
                            <tr style="border-bottom:1px solid #eee;">
                                <td style="padding:15px; color:var(--biru-tua); font-weight:bold;"><?= e($u['nama_file']) ?></td>
                                <td style="padding:15px; color:#666; font-size:0.9rem;"><?= e($u['keterangan']) ?></td>
                                <td style="padding:15px; text-align:center;">
                                    <a href="assets/unduhan/<?= e($u['path_file']) ?>" download class="btn btn-emas" style="padding:8px 15px; font-size:0.85rem; border-radius:4px; display:inline-block;">Download</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
