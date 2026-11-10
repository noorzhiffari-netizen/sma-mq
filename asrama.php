<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';

$halaman_aktif = 'asrama';
$judul_halaman = 'Asrama';

$asrama = $pdo->query("SELECT * FROM asrama ORDER BY jenis DESC")->fetchAll();

require __DIR__ . '/includes/header.php';
?>

<div class="header-halaman">
    <div class="wadah">
        <span class="jejak"><a href="index.php">Beranda</a> / Asrama</span>
        <h1>Kehidupan Asrama Santri</h1>
        <p style="color:#C9D3C6; max-width:60ch;">Sebagai sekolah berbasis pondok pesantren, SMAI MQ mewajibkan seluruh santri untuk bermukim di asrama sepanjang masa pendidikan.</p>
    </div>
</div>

<section>
    <div class="wadah dua-kolom">
        <div>
            <span class="label-bagian">Kebijakan Wajib Mukim</span>
            <h2 class="judul-bagian">Mengapa seluruh santri harus tinggal di asrama</h2>
            <p>Pendidikan di SMAI MQ tidak berhenti di jam pelajaran kelas. Dengan tinggal di asrama, santri mendapatkan pembinaan akhlak, kedisiplinan waktu, dan penguatan ibadah secara konsisten setiap hari, di bawah pengawasan langsung musyrif (pembina asrama putra) dan musyrifah (pembina asrama putri).</p>
            <p>Kebijakan wajib asrama berlaku bagi seluruh santri tanpa terkecuali, baik yang berasal dari dalam maupun luar Kabupaten Tasikmalaya, sejak hari pertama diterima sebagai santri SMAI MQ.</p>

            <div class="catatan-wajib">
                <strong>Wajib bagi seluruh santri:</strong> Tidak tersedia opsi pulang-pergi (non-asrama) di SMAI MQ. Setiap santri yang diterima otomatis ditempatkan di salah satu asrama sesuai jenis kelamin.
            </div>
        </div>
        <div>
            <div class="kartu">
                <span class="panel-label">Jadwal Harian (Ringkas)</span>
                <ul class="panel-daftar" style="margin-top:14px;">
                    <li>03.30 &ndash; Bangun malam &amp; qiyamul lail</li>
                    <li>04.30 &ndash; Shalat Subuh berjamaah &amp; kajian pagi</li>
                    <li>06.30 &ndash; Persiapan &amp; sekolah formal</li>
                    <li>15.30 &ndash; Kegiatan sore &amp; ekstrakurikuler</li>
                    <li>18.00 &ndash; Shalat Maghrib &amp; kajian malam</li>
                    <li>20.00 &ndash; Belajar malam terbimbing</li>
                    <li>22.00 &ndash; Istirahat</li>
                </ul>
            </div>
        </div>
    </div>
</section>

<section class="bg-krem">
    <div class="wadah">
        <div class="intro-bagian">
            <div>
                <span class="label-bagian">Unit Asrama</span>
                <h2 class="judul-bagian">Asrama putra dan putri terpisah</h2>
            </div>
        </div>
        <div class="grid-2">
            <?php foreach ($asrama as $a): 
                $fotoAsrama = $a['jenis'] === 'Putra' ? 'asrama-putra.jpg' : 'asrama-putri.jpg';
                if (!empty($a['gambar']) && file_exists(__DIR__ . '/assets/img/asrama/' . $a['gambar'])) {
                    $fotoAsrama = $a['gambar'];
                }
            ?>
            <div class="kartu" style="overflow:hidden; padding:0;">
                <div style="height:210px; overflow:hidden; background:var(--krem);">
                    <img src="<?= BASE_URL ?>assets/img/asrama/<?= e($fotoAsrama) ?>" alt="<?= e($a['nama_asrama']) ?>" style="width:100%; height:100%; object-fit:cover; display:block;">
                </div>
                <div style="padding:28px;">
                    <span class="panel-label"><?= e($a['jenis']) ?></span>
                    <h3><?= e($a['nama_asrama']) ?></h3>
                    <p>Kapasitas &plusmn; <?= e($a['kapasitas']) ?> santri.</p>
                <span class="panel-label">Fasilitas</span>
                <ul class="daftar-fasilitas">
                    <?php foreach (explode(',', $a['fasilitas']) as $f): ?>
                        <li><?= e(trim($f)) ?></li>
                    <?php endforeach; ?>
                </ul>
                <p style="margin-top:14px; font-size:0.88rem; color:var(--abu);"><?= e($a['keterangan']) ?></p>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
