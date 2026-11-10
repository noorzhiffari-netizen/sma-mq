<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';

$halaman_aktif = 'profil';
$judul_halaman = 'Profil Sekolah';

$profil = $pdo->query("SELECT * FROM profil_sekolah LIMIT 1")->fetch();
$guru   = $pdo->query("SELECT * FROM guru ORDER BY urutan")->fetchAll();
$misi   = array_filter(array_map('trim', explode("\n", $profil['misi'])));

require __DIR__ . '/includes/header.php';
?>

<div class="header-halaman">
    <div class="wadah">
        <span class="jejak"><a href="index.php">Beranda</a> / Profil</span>
        <h1>Profil SMAI MQ</h1>
        <p style="color:#C9D3C6; max-width:60ch;">Mengenal lebih dekat sejarah, visi-misi, dan arah pendidikan SMAI MQ sebagai sekolah berbasis pondok pesantren.</p>
    </div>
</div>

<section>
    <div class="wadah">
        <!-- Sambutan Kepala Sekolah -->
        <div class="sambutan-box">
            <div class="sambutan-foto">
                <img src="<?= BASE_URL ?>assets/img/profil/<?= !empty($profil['foto_kepsek']) && file_exists(__DIR__ . '/assets/img/profil/' . $profil['foto_kepsek']) ? e($profil['foto_kepsek']) : 'kepsek.jpg' ?>" alt="Kepala Sekolah SMAI MQ">
            </div>
            <div>
                <span class="label-bagian">Sambutan Pimpinan</span>
                <h2 style="font-size:1.8rem; margin-bottom:14px; color:var(--hijau-tua);">K.H. Ahmad Fauzi, S.Pd.I.</h2>
                <p style="color:var(--emas); font-weight:600; margin-bottom:12px;">Kepala SMAI MQ &middot; Musyrif Pondok Pesantren</p>
                <p style="font-size:1.02rem; line-height:1.7; color:var(--abu-tua); font-style:italic;">
                    "<?= !empty($profil['sambutan_kepsek']) ? nl2br(e($profil['sambutan_kepsek'])) : 'Alhamdulillah, segala puji bagi Allah SWT. SMAI MQ hadir untuk melahirkan generasi yang tidak hanya unggul dalam kecakapan akademik IPA dan IPS, namun juga kokoh dalam aqidah, akhlak mulia, dan mandiri melalui tempaan kehidupan berasrama.' ?>"
                </p>
            </div>
        </div>

        <div class="dua-kolom" style="margin-top:40px;">
            <div>
                <span class="label-bagian">Sejarah</span>
                <h2 class="judul-bagian">Berdiri tahun <?= e($profil['tahun_berdiri'] ?? '2022') ?> di Jamanis, Tasikmalaya</h2>
                <p><?= nl2br(e($profil['sejarah'] ?? '')) ?></p>

                <div class="catatan-wajib">
                    Seluruh santri SMAI MQ, baik putra maupun putri, <strong>wajib tinggal di asrama</strong> selama menempuh pendidikan. Kebijakan ini menjadi bagian tak terpisahkan dari identitas SMAI MQ sebagai sekolah berbasis pondok pesantren.
                </div>

                <span class="label-bagian">Visi</span>
                <h3 style="margin-bottom:14px;"><?= e($profil['visi'] ?? '') ?></h3>

                <span class="label-bagian">Misi</span>
                <ul class="daftar-fasilitas">
                    <?php foreach ($misi as $m): ?>
                        <li><?= e(rtrim($m, ';')) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <div>
                <div style="border-radius:var(--radius); overflow:hidden; border:1px solid var(--garis); margin-bottom:24px; box-shadow:0 8px 20px rgba(0,0,0,0.06);">
                    <img src="<?= BASE_URL ?>assets/img/profil/<?= !empty($profil['foto_sekolah']) && file_exists(__DIR__ . '/assets/img/profil/' . $profil['foto_sekolah']) ? e($profil['foto_sekolah']) : 'gedung.jpg' ?>" alt="Gedung Kampus SMAI MQ" style="width:100%; height:240px; object-fit:cover; display:block;">
                </div>

                <div class="kartu" style="margin-bottom:24px;">
                    <span class="panel-label">Data Singkat</span>
                    <table class="tabel-rapi" style="margin-top:14px;">
                        <tr><th>Nama Sekolah</th><td><?= e($profil['nama_sekolah'] ?? '') ?></td></tr>
                        <tr><th>Tahun Berdiri</th><td><?= e($profil['tahun_berdiri'] ?? '') ?></td></tr>
                        <tr><th>Alamat</th><td><?= e($profil['alamat'] ?? '') ?>, <?= e($profil['desa_kelurahan'] ?? '') ?></td></tr>
                        <tr><th>Kecamatan</th><td><?= e($profil['kecamatan'] ?? '') ?></td></tr>
                        <tr><th>Kabupaten</th><td><?= e($profil['kabupaten'] ?? '') ?></td></tr>
                        <tr><th>Provinsi</th><td><?= e($profil['provinsi'] ?? '') ?></td></tr>
                        <tr><th>Kode Pos</th><td><?= e($profil['kode_pos'] ?? '') ?></td></tr>
                        <tr><th>Basis Sekolah</th><td>Pondok Pesantren (wajib asrama)</td></tr>
                        <tr><th>Jurusan</th><td>IPA &amp; IPS</td></tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="bg-krem">
    <div class="wadah">
        <div class="intro-bagian">
            <div>
                <span class="label-bagian">Struktur Pembina &amp; Pendidik</span>
                <h2 class="judul-bagian">Pimpinan dan dewan guru SMAI MQ</h2>
            </div>
            <a href="guru-staff.php" class="btn btn-luar">Lihat Seluruh Guru &amp; Staff &rarr;</a>
        </div>
        <div class="grid-personil">
            <?php 
            // Tampilkan 4 personil pimpinan utama di halaman profil
            $pimpinanUtama = array_slice($guru, 0, 4);
            foreach ($pimpinanUtama as $g): 
                $fotoG = !empty($g['foto']) && file_exists(__DIR__ . '/assets/img/guru/' . $g['foto']) 
                    ? $g['foto'] 
                    : 'kepsek.jpg';
            ?>
            <div class="kartu-personil">
                <div class="foto-box">
                    <img src="<?= BASE_URL ?>assets/img/guru/<?= e($fotoG) ?>" alt="<?= e($g['nama']) ?>">
                </div>
                <div class="konten">
                    <span class="badge-kat badge-guru"><?= e($g['kategori'] ?? 'Guru') ?></span>
                    <h3><?= e($g['nama']) ?></h3>
                    <div class="jabatan"><?= e($g['jabatan']) ?></div>
                    <div class="tugas"><?= e($g['bidang_studi']) ?></div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
