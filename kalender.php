<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';

$judul_halaman = 'Kalender Akademik';
$halaman_aktif = 'kalender';
require_once __DIR__ . '/includes/header.php';

// Cek apakah tabel kalender_akademik ada
try {
    $agenda = $pdo->query("SELECT * FROM kalender_akademik ORDER BY tanggal_mulai ASC")->fetchAll();
} catch (PDOException $e) {
    $agenda = [];
}
?>

<div class="kontainer">
    <div style="text-align:center; padding: 40px 0;">
        <h1 style="color:var(--biru-tua); font-size:2.5rem; margin-bottom:10px;">Kalender Akademik & Agenda</h1>
        <p style="color:#555; max-width:600px; margin:0 auto;">Jadwal kegiatan akademik dan kepesantrenan SMAI MQ.</p>
    </div>

    <?php if (!$agenda): ?>
        <div style="text-align:center; padding: 50px; background:#f9f9f9; border-radius:10px; margin-bottom:40px;">
            <p>Belum ada data agenda yang ditampilkan.</p>
        </div>
    <?php else: ?>
        <div style="margin-bottom: 40px; overflow-x:auto;">
            <table style="width: 100%; border-collapse: collapse; background: #fff; box-shadow: 0 4px 6px rgba(0,0,0,0.05); border-radius: 8px; overflow: hidden;">
                <thead>
                    <tr style="background: var(--hijau-tua); color: white; text-align: left;">
                        <th style="padding: 15px;">Tanggal</th>
                        <th style="padding: 15px;">Kegiatan</th>
                        <th style="padding: 15px;">Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($agenda as $item): ?>
                        <tr style="border-bottom: 1px solid #eee;">
                            <td style="padding: 15px; white-space: nowrap;">
                                <?php 
                                    if ($item['tanggal_selesai'] && $item['tanggal_selesai'] !== $item['tanggal_mulai']) {
                                        echo date('d M Y', strtotime($item['tanggal_mulai'])) . ' - ' . date('d M Y', strtotime($item['tanggal_selesai']));
                                    } else {
                                        echo date('d M Y', strtotime($item['tanggal_mulai']));
                                    }
                                ?>
                            </td>
                            <td style="padding: 15px; font-weight: 600; color: var(--biru-tua);">
                                <?= e($item['nama_kegiatan']) ?>
                            </td>
                            <td style="padding: 15px; color: #555;">
                                <?= nl2br(e($item['keterangan'])) ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
