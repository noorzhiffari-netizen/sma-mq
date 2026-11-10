<?php
/**
 * Header ini di-include oleh setiap halaman publik.
 * Halaman pemanggil wajib sudah:
 *  - require_once config/database.php
 *  - require_once includes/functions.php
 *  - mendefinisikan variabel $judul_halaman (opsional) dan $halaman_aktif
 */
if (!isset($pdo)) {
    require_once __DIR__ . '/../config/database.php';
}
$profil = $pdo->query("SELECT * FROM profil_sekolah LIMIT 1")->fetch();
$halaman_aktif = $halaman_aktif ?? '';
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= isset($judul_halaman) ? e($judul_halaman) . ' - ' : '' ?><?= e($profil['nama_sekolah'] ?? 'SMAI MQ') ?></title>
<meta name="description" content="Website resmi SMA Islam MQ (SMAI MQ), sekolah berbasis pondok pesantren dengan program IPA dan IPS di Jamanis, Kabupaten Tasikmalaya.">
<link rel="icon" type="image/png" href="<?= BASE_URL ?>assets/img/logo.png">
<link rel="stylesheet" href="<?= BASE_URL ?>assets/css/style.css">
</head>
<body>

<div class="topbar">
    <div class="wadah">
        <div>
            <span>📍 <?= e($profil['desa_kelurahan'] ?? '') ?>, <?= e($profil['kecamatan'] ?? '') ?>, <?= e($profil['kabupaten'] ?? '') ?></span>
            <span>✉ <?= e($profil['email'] ?? '') ?></span>
        </div>
        <div>
            <span>☎ <?= e($profil['telepon'] ?? '') ?></span>
            <div style="display:inline-flex; align-items:center; gap:10px; margin-left:15px; border-left:1px solid rgba(255,255,255,0.3); padding-left:15px;">
                <?php if(!empty($profil['facebook'])): ?><a href="<?= e($profil['facebook']) ?>" target="_blank" style="color:white; text-decoration:none; font-weight:bold;">FB</a><?php endif; ?>
                <?php if(!empty($profil['instagram'])): ?><a href="<?= e($profil['instagram']) ?>" target="_blank" style="color:white; text-decoration:none; font-weight:bold;">IG</a><?php endif; ?>
                <?php if(!empty($profil['youtube'])): ?><a href="<?= e($profil['youtube']) ?>" target="_blank" style="color:white; text-decoration:none; font-weight:bold;">YT</a><?php endif; ?>
            </div>
        </div>
    </div>
</div>

<header class="situs">
    <div class="navbar wadah">
        <a href="<?= BASE_URL ?>index.php" class="merek">
            <img src="<?= BASE_URL ?>assets/img/logo.png" alt="Logo SMAI MQ" class="logo-sekolah">
            <span class="merek-teks">
                <strong>SMAI MQ</strong>

            </span>
        </a>
        <button class="tombol-menu" aria-label="Buka menu">&#9776;</button>
        <nav class="menu-utama">
            <ul>
                <li><a href="<?= BASE_URL ?>index.php" class="<?= $halaman_aktif === 'beranda' ? 'aktif' : '' ?>">Beranda</a></li>
                
                <li class="menu-dropdown <?= in_array($halaman_aktif, ['profil', 'jurusan', 'asrama', 'fasilitas', 'guru', 'prestasi', 'ekstrakurikuler']) ? 'aktif' : '' ?>">
                    <a href="javascript:void(0)" class="dropdown-toggle">Tentang Kami <span class="panah-bawah">&#9662;</span></a>
                    <ul class="dropdown-menu">
                        <li><a href="<?= BASE_URL ?>profil.php" class="<?= $halaman_aktif === 'profil' ? 'aktif' : '' ?>">Profil Sekolah</a></li>
                        <li><a href="<?= BASE_URL ?>guru-staff.php" class="<?= $halaman_aktif === 'guru' ? 'aktif' : '' ?>">Guru &amp; Tenaga Kependidikan</a></li>
                        <li><a href="<?= BASE_URL ?>prestasi.php" class="<?= $halaman_aktif === 'prestasi' ? 'aktif' : '' ?>">Prestasi</a></li>
                        <li><a href="<?= BASE_URL ?>ekstrakurikuler.php" class="<?= $halaman_aktif === 'ekstrakurikuler' ? 'aktif' : '' ?>">Ekstrakurikuler</a></li>
                        <li><a href="<?= BASE_URL ?>jurusan.php" class="<?= $halaman_aktif === 'jurusan' ? 'aktif' : '' ?>">Program Jurusan</a></li>
                        <li><a href="<?= BASE_URL ?>asrama.php" class="<?= $halaman_aktif === 'asrama' ? 'aktif' : '' ?>">Kehidupan Asrama</a></li>
                        <li><a href="<?= BASE_URL ?>fasilitas.php" class="<?= $halaman_aktif === 'fasilitas' ? 'aktif' : '' ?>">Fasilitas Sekolah</a></li>
                    </ul>
                </li>

                <li class="menu-dropdown <?= in_array($halaman_aktif, ['berita', 'galeri', 'pengumuman', 'unduhan', 'alumni', 'kalender', 'karya']) ? 'aktif' : '' ?>">
                    <a href="javascript:void(0)" class="dropdown-toggle">Informasi <span class="panah-bawah">&#9662;</span></a>
                    <ul class="dropdown-menu">
                        <li><a href="<?= BASE_URL ?>berita.php" class="<?= $halaman_aktif === 'berita' ? 'aktif' : '' ?>">Berita &amp; Artikel</a></li>
                        <li><a href="<?= BASE_URL ?>pengumuman.php" class="<?= $halaman_aktif === 'pengumuman' ? 'aktif' : '' ?>">Pengumuman &amp; Agenda</a></li>
                        <li><a href="<?= BASE_URL ?>kalender.php" class="<?= $halaman_aktif === 'kalender' ? 'aktif' : '' ?>">Kalender Akademik</a></li>
                        <li><a href="<?= BASE_URL ?>karya.php" class="<?= $halaman_aktif === 'karya' ? 'aktif' : '' ?>">Karya Siswa</a></li>
                        <li><a href="<?= BASE_URL ?>galeri.php" class="<?= $halaman_aktif === 'galeri' ? 'aktif' : '' ?>">Galeri Kegiatan</a></li>
                        <li><a href="<?= BASE_URL ?>unduhan.php" class="<?= $halaman_aktif === 'unduhan' ? 'aktif' : '' ?>">Pusat Unduhan</a></li>
                        <li><a href="<?= BASE_URL ?>alumni.php" class="<?= $halaman_aktif === 'alumni' ? 'aktif' : '' ?>">Portal Alumni</a></li>
                    </ul>
                </li>

                <li><a href="<?= BASE_URL ?>kontak.php" class="<?= $halaman_aktif === 'kontak' ? 'aktif' : '' ?>">Kontak</a></li>
                <li><a href="https://spmb.ponpes-mhmq.my.id/" target="_blank" class="btn btn-emas" style="padding:8px 18px;">SPMB</a></li>
            </ul>
        </nav>
    </div>
</header>
