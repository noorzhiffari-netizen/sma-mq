<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';

$halaman_aktif = 'jurusan';
$judul_halaman = 'Jurusan';

$jurusan = $pdo->query("SELECT * FROM jurusan ORDER BY kode")->fetchAll();

require __DIR__ . '/includes/header.php';
?>

<div class="header-halaman">
    <div class="wadah">
        <span class="jejak"><a href="index.php">Beranda</a> / Jurusan</span>
        <h1>Program Peminatan IPA &amp; IPS</h1>
        <p style="color:#C9D3C6; max-width:60ch;">Setiap santri memilih salah satu jalur peminatan sejak kelas X, dengan pendampingan akademik yang berjalan bersama kehidupan di asrama.</p>
    </div>
</div>

<section>
    <div class="wadah">
        <?php foreach ($jurusan as $i => $j): ?>
        <div class="panel-jurusan" style="margin-bottom: <?= $i < count($jurusan) - 1 ? '30px' : '0' ?>;">
            <div class="panel" style="<?= $i % 2 === 1 ? 'order:2;' : '' ?>">
                <span class="panel-kode"><?= e($j['kode']) ?></span>
                <h3><?= e($j['nama']) ?></h3>
                <p><?= e($j['deskripsi']) ?></p>
                <span class="panel-label">Prospek lanjutan</span>
                <p style="margin-top:6px;"><?= e($j['prospek']) ?></p>
            </div>
            <div class="panel" style="<?= $i % 2 === 1 ? 'order:1;' : '' ?>">
                <div style="border-radius:var(--radius); overflow:hidden; margin-bottom:18px; height:180px; box-shadow:0 4px 12px rgba(0,0,0,0.08);">
                    <img src="<?= BASE_URL ?>assets/img/jurusan/<?= !empty($j['gambar']) && file_exists(__DIR__ . '/assets/img/jurusan/' . $j['gambar']) ? e($j['gambar']) : (strtolower($j['kode']) . '.jpg') ?>" alt="<?= e($j['nama']) ?>" style="width:100%; height:100%; object-fit:cover; display:block;">
                </div>
                <span class="panel-label">Mata Pelajaran Peminatan</span>
                <ul class="panel-daftar">
                    <?php foreach (explode(',', $j['mata_pelajaran_unggulan']) as $mp): ?>
                        <li><?= e(trim($mp)) ?></li>
                    <?php endforeach; ?>
                </ul>
                <p style="margin-top:18px; font-size:0.88rem;">Ditambah mata pelajaran wajib nasional dan kurikulum kepesantrenan (Al-Qur'an, Bahasa Arab, dan kajian keislaman).</p>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</section>

<section class="bg-krem">
    <div class="wadah">
        <div class="intro-bagian">
            <div>
                <span class="label-bagian">Alur Peminatan</span>
                <h2 class="judul-bagian">Bagaimana santri memilih jurusan</h2>
            </div>
        </div>
        <div class="grid-3">
            <div class="kartu">
                <span class="angka">01</span>
                <h3>Pendaftaran</h3>
                <p>Calon santri mengisi formulir SPMB (Sistem Penerimaan Murid Baru) dan menyatakan minat awal pada program IPA atau IPS.</p>
            </div>
            <div class="kartu">
                <span class="angka">02</span>
                <h3>Penempatan</h3>
                <p>Sekolah menetapkan penempatan kelas peminatan berdasarkan minat, nilai rapor, dan hasil tes penempatan.</p>
            </div>
            <div class="kartu">
                <span class="angka">03</span>
                <h3>Pembelajaran &amp; Pembinaan</h3>
                <p>Santri menjalani pembelajaran sesuai jurusan di kelas, sekaligus pembinaan kepesantrenan penuh di asrama.</p>
            </div>
        </div>
    </div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
