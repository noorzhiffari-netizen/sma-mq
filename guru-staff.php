<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';

$halaman_aktif = 'guru';
$judul_halaman = 'Guru & Tenaga Kependidikan';

// Ambil personil
$kategoriFilter = $_GET['kategori'] ?? 'Semua';

if (in_array($kategoriFilter, ['Guru', 'Tata Usaha', 'Karyawan'])) {
    $stmt = $pdo->prepare("SELECT * FROM guru WHERE kategori = ? ORDER BY urutan ASC, id ASC");
    $stmt->execute([$kategoriFilter]);
    $personil = $stmt->fetchAll();
} else {
    $personil = $pdo->query("SELECT * FROM guru ORDER BY urutan ASC, id ASC")->fetchAll();
    $kategoriFilter = 'Semua';
}

// Hitung jumlah per kategori
$jmlSemua = $pdo->query("SELECT COUNT(*) c FROM guru")->fetch()['c'] ?? 0;
$jmlGuru  = $pdo->query("SELECT COUNT(*) c FROM guru WHERE kategori = 'Guru'")->fetch()['c'] ?? 0;
$jmlTU    = $pdo->query("SELECT COUNT(*) c FROM guru WHERE kategori = 'Tata Usaha'")->fetch()['c'] ?? 0;
$jmlKary  = $pdo->query("SELECT COUNT(*) c FROM guru WHERE kategori = 'Karyawan'")->fetch()['c'] ?? 0;

require __DIR__ . '/includes/header.php';
?>

<div class="header-halaman">
    <div class="wadah">
        <span class="jejak"><a href="index.php">Beranda</a> / Tentang Kami / Guru &amp; Staff</span>
        <h1>Guru &amp; Tenaga Kependidikan</h1>
        <p style="color:#C9D3C6; max-width:65ch;">Para pendidik berdedikasi, tenaga administrasi, dan pembina asrama yang membimbing santri SMAI MQ dalam menuntut ilmu dan membina akhlak mulia.</p>
    </div>
</div>

<section>
    <div class="wadah">
        <!-- Filter Tabs -->
        <div class="filter-personil">
            <a href="guru-staff.php" class="btn-filter <?= $kategoriFilter === 'Semua' ? 'aktif' : '' ?>">
                Semua Personil (<?= $jmlSemua ?>)
            </a>
            <a href="guru-staff.php?kategori=Guru" class="btn-filter <?= $kategoriFilter === 'Guru' ? 'aktif' : '' ?>">
                Dewan Guru (<?= $jmlGuru ?>)
            </a>
            <a href="guru-staff.php?kategori=Tata+Usaha" class="btn-filter <?= $kategoriFilter === 'Tata Usaha' ? 'aktif' : '' ?>">
                Staff Tata Usaha (<?= $jmlTU ?>)
            </a>
            <a href="guru-staff.php?kategori=Karyawan" class="btn-filter <?= $kategoriFilter === 'Karyawan' ? 'aktif' : '' ?>">
                Karyawan &amp; Musyrif (<?= $jmlKary ?>)
            </a>
        </div>

        <!-- Grid Kartu Personil -->
        <div class="grid-personil">
            <?php if ($personil): foreach ($personil as $p): 
                $badgeClass = 'badge-guru';
                $katLabel = 'Dewan Guru';
                if ($p['kategori'] === 'Tata Usaha') {
                    $badgeClass = 'badge-tu';
                    $katLabel = 'Tata Usaha';
                } elseif ($p['kategori'] === 'Karyawan') {
                    $badgeClass = 'badge-karyawan';
                    $katLabel = 'Karyawan / Musyrif';
                }

                $fotoPersonil = !empty($p['foto']) && file_exists(__DIR__ . '/assets/img/guru/' . $p['foto']) 
                    ? $p['foto'] 
                    : 'kepsek.jpg';
            ?>
            <div class="kartu-personil">
                <div class="foto-box">
                    <img src="assets/img/guru/<?= e($fotoPersonil) ?>" alt="<?= e($p['nama']) ?>">
                </div>
                <div class="konten">
                    <span class="badge-kat <?= $badgeClass ?>"><?= $katLabel ?></span>
                    <h3><?= e($p['nama']) ?></h3>
                    <div class="jabatan"><?= e($p['jabatan']) ?></div>
                    <div class="tugas"><?= e($p['bidang_studi']) ?></div>
                    <?php if (!empty($p['pendidikan'])): ?>
                        <div class="pendidikan">🎓 <?= e($p['pendidikan']) ?></div>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; else: ?>
                <div style="grid-column:1/-1; text-align:center; padding:40px 0; color:var(--abu);">
                    <p>Belum ada data personil pada kategori ini.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
