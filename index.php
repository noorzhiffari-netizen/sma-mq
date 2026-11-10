<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';

$halaman_aktif = 'beranda';
$judul_halaman = 'Beranda';

$profil  = $pdo->query("SELECT * FROM profil_sekolah LIMIT 1")->fetch();
$jurusan = $pdo->query("SELECT * FROM jurusan ORDER BY kode")->fetchAll();
$berita  = $pdo->query("SELECT * FROM berita WHERE status='terbit' ORDER BY dibuat_pada DESC LIMIT 3")->fetchAll();

require __DIR__ . '/includes/header.php';
?>

<section class="hero">
    <svg class="hero-pola" viewBox="0 0 400 400" preserveAspectRatio="xMaxYMid slice" xmlns="http://www.w3.org/2000/svg">
        <defs>
            <pattern id="bintang" width="50" height="50" patternUnits="userSpaceOnUse">
                <g fill="none" stroke="#D3A85C" stroke-width="1">
                    <path d="M25 2 L31 19 L48 19 L34 30 L39 47 L25 37 L11 47 L16 30 L2 19 L19 19 Z" />
                </g>
            </pattern>
        </defs>
        <rect width="400" height="400" fill="url(#bintang)" />
    </svg>
    <div class="wadah">
        <div>

            <h1>Mendidik Santri Berakhlak, Berilmu, dan Mandiri di Asrama</h1>
            <p class="lead">SMAI MQ adalah SMA berbasis pesantren yang mewajibkan seluruh santri tinggal di asrama, dengan program peminatan IPA dan IPS sejak berdiri tahun <?= e($profil['tahun_berdiri'] ?? '2022') ?>.</p>
            <div class="hero-aksi">
                <a href="https://spmb.ponpes-mhmq.my.id/" target="_blank" class="btn btn-emas">Daftar Santri Baru (SPMB)</a>
                <a href="profil.php" class="btn btn-luar">Kenali Kami Lebih Dekat</a>
            </div>
        </div>
        <div class="hero-banner-box">
            <img src="<?= BASE_URL ?>assets/img/hero.jpg" alt="Kampus Pesantren SMAI MQ">
        </div>
    </div>
    <div class="hero-stat-bar">
        <div class="wadah">
            <div class="item"><strong><?= e($profil['tahun_berdiri'] ?? '2022') ?></strong><span>Tahun berdiri</span></div>
            <div class="item"><strong>2</strong><span>Program peminatan</span></div>
            <div class="item"><strong>100%</strong><span>Santri tinggal di asrama</span></div>
            <div class="item"><strong>2</strong><span>Asrama putra &amp; putri</span></div>
        </div>
    </div>
</section>

<div class="pita-alamat">
    <div class="wadah">
        📍 <?= e($profil['alamat'] ?? '') ?>, Desa <?= e($profil['desa_kelurahan'] ?? '') ?>, <?= e($profil['kecamatan'] ?? '') ?>, <?= e($profil['kabupaten'] ?? '') ?>, <?= e($profil['provinsi'] ?? '') ?> <?= e($profil['kode_pos'] ?? '') ?>
    </div>
</div>

<section>
    <div class="wadah">
        <div class="intro-bagian">
            <div>
                <span class="label-bagian">Mengapa SMAI MQ</span>
                <h2 class="judul-bagian">Pendidikan formal yang menyatu dengan kehidupan pesantren</h2>
            </div>
            <p>Kurikulum nasional berjalan seiring dengan pembinaan akhlak dan kemandirian santri di dalam asrama, setiap hari.</p>
        </div>
        <div class="grid-3">
            <div class="kartu">
                <span class="angka">01</span>
                <h3>Sistem Asrama Penuh</h3>
                <p>Setiap santri wajib bermukim di asrama sehingga pembinaan karakter berlangsung sepanjang waktu, bukan hanya di jam sekolah.</p>
            </div>
            <div class="kartu">
                <span class="angka">02</span>
                <h3>Peminatan IPA &amp; IPS</h3>
                <p>Santri difasilitasi memilih jalur akademik sesuai minat, dengan pengajaran yang menekankan pemahaman, bukan hafalan.</p>
            </div>
            <div class="kartu">
                <span class="angka">03</span>
                <h3>Pembinaan Musyrif/ah</h3>
                <p>Pembina asrama mendampingi keseharian santri: ibadah, belajar malam, hingga kedisiplinan sehari-hari.</p>
            </div>
        </div>
    </div>
</section>

<section class="bg-krem">
    <div class="wadah">
        <div class="intro-bagian">
            <div>
                <span class="label-bagian">Program Peminatan</span>
                <h2 class="judul-bagian">Dua jalur akademik, satu pembinaan pesantren</h2>
            </div>
            <a href="jurusan.php" class="btn btn-luar">Lihat Detail Jurusan</a>
        </div>
        <div class="panel-jurusan">
            <?php foreach ($jurusan as $j): ?>
            <div class="panel">
                <span class="panel-kode"><?= e($j['kode']) ?></span>
                <h3><?= e($j['nama']) ?></h3>
                <p><?= e($j['deskripsi']) ?></p>
                <span class="panel-label">Mata pelajaran unggulan</span>
                <p style="margin-top:6px;"><?= e($j['mata_pelajaran_unggulan']) ?></p>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section>
    <div class="wadah">
        <div class="intro-bagian">
            <div>
                <span class="label-bagian">Berita &amp; Pengumuman</span>
                <h2 class="judul-bagian">Kabar terbaru dari SMAI MQ</h2>
            </div>
            <a href="berita.php" class="btn btn-luar">Semua Berita</a>
        </div>
        <div class="grid-berita">
            <?php if ($berita): foreach ($berita as $b): ?>
            <a class="kartu-berita" href="berita-detail.php?slug=<?= e($b['slug']) ?>">
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
    </div>
</section>

<section class="cta">
    <div class="wadah">
        <span class="label-bagian">Penerimaan Santri Baru</span>
        <h2>Siap menjadi bagian dari SMAI MQ?</h2>
        <p>Daftarkan putra-putri Anda untuk menempuh pendidikan SMA sekaligus kehidupan pesantren di asrama, dengan pilihan program IPA atau IPS.</p>
        <a href="https://spmb.ponpes-mhmq.my.id/" target="_blank" class="btn btn-emas">Formulir Pendaftaran (SPMB)</a>
    </div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
