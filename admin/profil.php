<?php
session_start();
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';
require_admin_login();

$halaman = 'profil';
$pesanSukses = null;
$error = null;

// Ambil profil
$profil = $pdo->query("SELECT * FROM profil_sekolah LIMIT 1")->fetch();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $namaSekolah    = trim($_POST['nama_sekolah'] ?? '');
    $tahunBerdiri   = (int) ($_POST['tahun_berdiri'] ?? 2022);
    $npsn           = trim($_POST['npsn'] ?? '');
    $telepon        = trim($_POST['telepon'] ?? '');
    $email          = trim($_POST['email'] ?? '');
    $alamat         = trim($_POST['alamat'] ?? '');
    $desa           = trim($_POST['desa_kelurahan'] ?? '');
    $kecamatan      = trim($_POST['kecamatan'] ?? '');
    $kabupaten      = trim($_POST['kabupaten'] ?? '');
    $provinsi       = trim($_POST['provinsi'] ?? '');
    $kodePos        = trim($_POST['kode_pos'] ?? '');
    $sambutan       = trim($_POST['sambutan_kepsek'] ?? '');
    $visi           = trim($_POST['visi'] ?? '');
    $misi           = trim($_POST['misi'] ?? '');
    $sejarah        = trim($_POST['sejarah'] ?? '');
    $facebook       = trim($_POST['facebook'] ?? '');
    $instagram      = trim($_POST['instagram'] ?? '');
    $youtube        = trim($_POST['youtube'] ?? '');
    $whatsapp       = trim($_POST['whatsapp'] ?? '');
    $video_profil   = trim($_POST['video_profil'] ?? '');

    $fotoKepsek     = $profil['foto_kepsek'] ?? 'kepsek.jpg';
    $fotoSekolah    = $profil['foto_sekolah'] ?? 'gedung.jpg';

    $ekstensiDiizinkan = ['jpg', 'jpeg', 'png', 'webp'];

    // 1. Upload Logo Sekolah baru (jika ada)
    if (!empty($_FILES['logo']['name'])) {
        $ext = strtolower(pathinfo($_FILES['logo']['name'], PATHINFO_EXTENSION));
        if (in_array($ext, $ekstensiDiizinkan, true)) {
            $destPng = __DIR__ . '/../assets/img/logo.png';
            $destJpg = __DIR__ . '/../assets/img/logo.jpg';
            move_uploaded_file($_FILES['logo']['tmp_name'], $destPng);
            @copy($destPng, $destJpg);
        } else {
            $error = 'Format file logo harus JPG, PNG, atau WEBP.';
        }
    }

    // 2. Upload Foto Kepala Sekolah
    if (!empty($_FILES['foto_kepsek']['name'])) {
        $ext = strtolower(pathinfo($_FILES['foto_kepsek']['name'], PATHINFO_EXTENSION));
        if (in_array($ext, $ekstensiDiizinkan, true)) {
            $namaFileKepsek = 'kepsek-' . time() . '.' . $ext;
            $destDir = __DIR__ . '/../assets/img/profil';
            if (!is_dir($destDir)) { mkdir($destDir, 0755, true); }
            move_uploaded_file($_FILES['foto_kepsek']['tmp_name'], $destDir . '/' . $namaFileKepsek);
            $fotoKepsek = $namaFileKepsek;
        } else {
            $error = 'Format file foto kepala sekolah harus JPG, PNG, atau WEBP.';
        }
    }

    // 3. Upload Foto Gedung / Kampus
    if (!empty($_FILES['foto_sekolah']['name'])) {
        $ext = strtolower(pathinfo($_FILES['foto_sekolah']['name'], PATHINFO_EXTENSION));
        if (in_array($ext, $ekstensiDiizinkan, true)) {
            $namaFileSekolah = 'gedung-' . time() . '.' . $ext;
            $destDir = __DIR__ . '/../assets/img/profil';
            if (!is_dir($destDir)) { mkdir($destDir, 0755, true); }
            move_uploaded_file($_FILES['foto_sekolah']['tmp_name'], $destDir . '/' . $namaFileSekolah);
            $fotoSekolah = $namaFileSekolah;
        } else {
            $error = 'Format file foto gedung harus JPG, PNG, atau WEBP.';
        }
    }

    // 4. Upload Foto Banner Hero Beranda
    if (!empty($_FILES['foto_hero']['name'])) {
        $ext = strtolower(pathinfo($_FILES['foto_hero']['name'], PATHINFO_EXTENSION));
        if (in_array($ext, $ekstensiDiizinkan, true)) {
            $destHero = __DIR__ . '/../assets/img/hero.jpg';
            move_uploaded_file($_FILES['foto_hero']['tmp_name'], $destHero);
        } else {
            $error = 'Format file foto banner hero harus JPG, PNG, atau WEBP.';
        }
    }

    if (!$error) {
        $stmt = $pdo->prepare("UPDATE profil_sekolah SET 
            nama_sekolah=?, tahun_berdiri=?, npsn=?, telepon=?, email=?, 
            alamat=?, desa_kelurahan=?, kecamatan=?, kabupaten=?, provinsi=?, kode_pos=?, 
            sambutan_kepsek=?, visi=?, misi=?, sejarah=?, foto_kepsek=?, foto_sekolah=?,
            facebook=?, instagram=?, youtube=?, whatsapp=?, video_profil=? 
            WHERE id=?");
        $stmt->execute([
            $namaSekolah, $tahunBerdiri, $npsn, $telepon, $email,
            $alamat, $desa, $kecamatan, $kabupaten, $provinsi, $kodePos,
            $sambutan, $visi, $misi, $sejarah, $fotoKepsek, $fotoSekolah,
            $facebook, $instagram, $youtube, $whatsapp, $video_profil,
            $profil['id']
        ]);
        $pesanSukses = 'Profil dan foto sekolah berhasil diperbarui!';
        // Refresh profil terbaru
        $profil = $pdo->query("SELECT * FROM profil_sekolah LIMIT 1")->fetch();
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Profil &amp; Identitas Sekolah - SMAI MQ</title>
<link rel="icon" type="image/png" href="../assets/img/logo.png">
<link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
<div class="admin-shell">
    <?php require __DIR__ . '/includes_sidebar.php'; ?>
    <div class="admin-konten">
        <div class="admin-topbar">
            <h1>Profil &amp; Foto Sekolah</h1>
            <a href="../index.php" target="_blank" class="btn btn-luar">Lihat Website &#8599;</a>
        </div>

        <?php if ($pesanSukses): ?><div class="pesan-status sukses"><?= e($pesanSukses) ?></div><?php endif; ?>
        <?php if ($error): ?><div class="pesan-status gagal"><?= e($error) ?></div><?php endif; ?>

        <form method="post" enctype="multipart/form-data">
            <!-- Panel Kelola Foto & Logo -->
            <div class="kartu-admin" style="margin-bottom:28px;">
                <h2 style="font-size:1.2rem; margin-bottom:18px; color:var(--hijau-tua); border-bottom:1px solid #eee; padding-bottom:10px;">
                    🖼️ Kelola Logo &amp; Foto Utama Website
                </h2>
                
                <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(240px, 1fr)); gap:24px;">
                    <!-- Logo Sekolah -->
                    <div style="background:#f9f9f9; padding:18px; border-radius:8px; border:1px solid #e0e0e0; text-align:center;">
                        <strong style="display:block; margin-bottom:10px; font-size:0.95rem;">Logo Sekolah</strong>
                        <div style="height:100px; display:flex; align-items:center; justify-content:center; margin-bottom:12px;">
                            <img src="../assets/img/logo.png?v=<?= time() ?>" alt="Logo" style="max-height:90px; max-width:100px; object-fit:contain;">
                        </div>
                        <label class="btn btn-luar btn-kecil" style="cursor:pointer; display:inline-block;">
                            Ganti Logo
                            <input type="file" name="logo" accept="image/*" style="display:none;" onchange="this.parentElement.nextElementSibling.innerText = this.files[0].name">
                        </label>
                        <small style="display:block; color:#777; margin-top:6px; font-size:0.75rem;">Format PNG disarankan</small>
                    </div>

                    <!-- Foto Kepala Sekolah -->
                    <div style="background:#f9f9f9; padding:18px; border-radius:8px; border:1px solid #e0e0e0; text-align:center;">
                        <strong style="display:block; margin-bottom:10px; font-size:0.95rem;">Foto Kepala Sekolah</strong>
                        <div style="height:100px; display:flex; align-items:center; justify-content:center; margin-bottom:12px;">
                            <img src="../assets/img/profil/<?= !empty($profil['foto_kepsek']) && file_exists(__DIR__ . '/../assets/img/profil/' . $profil['foto_kepsek']) ? e($profil['foto_kepsek']) : 'kepsek.jpg' ?>?v=<?= time() ?>" alt="Foto Kepsek" style="max-height:95px; width:80px; object-fit:cover; border-radius:6px;">
                        </div>
                        <label class="btn btn-luar btn-kecil" style="cursor:pointer; display:inline-block;">
                            Ganti Foto Kepsek
                            <input type="file" name="foto_kepsek" accept="image/*" style="display:none;" onchange="this.parentElement.nextElementSibling.innerText = this.files[0].name">
                        </label>
                        <small style="display:block; color:#777; margin-top:6px; font-size:0.75rem;">Ditampilkan di halaman Profil</small>
                    </div>

                    <!-- Foto Gedung Kampus -->
                    <div style="background:#f9f9f9; padding:18px; border-radius:8px; border:1px solid #e0e0e0; text-align:center;">
                        <strong style="display:block; margin-bottom:10px; font-size:0.95rem;">Foto Gedung / Kampus</strong>
                        <div style="height:100px; display:flex; align-items:center; justify-content:center; margin-bottom:12px;">
                            <img src="../assets/img/profil/<?= !empty($profil['foto_sekolah']) && file_exists(__DIR__ . '/../assets/img/profil/' . $profil['foto_sekolah']) ? e($profil['foto_sekolah']) : 'gedung.jpg' ?>?v=<?= time() ?>" alt="Foto Gedung" style="max-height:95px; width:130px; object-fit:cover; border-radius:6px;">
                        </div>
                        <label class="btn btn-luar btn-kecil" style="cursor:pointer; display:inline-block;">
                            Ganti Foto Gedung
                            <input type="file" name="foto_sekolah" accept="image/*" style="display:none;" onchange="this.parentElement.nextElementSibling.innerText = this.files[0].name">
                        </label>
                        <small style="display:block; color:#777; margin-top:6px; font-size:0.75rem;">Ditampilkan di halaman Profil</small>
                    </div>

                    <!-- Foto Banner Hero Beranda -->
                    <div style="background:#f9f9f9; padding:18px; border-radius:8px; border:1px solid #e0e0e0; text-align:center;">
                        <strong style="display:block; margin-bottom:10px; font-size:0.95rem;">Banner Hero Beranda</strong>
                        <div style="height:100px; display:flex; align-items:center; justify-content:center; margin-bottom:12px;">
                            <img src="../assets/img/hero.jpg?v=<?= time() ?>" alt="Banner Hero" style="max-height:95px; width:140px; object-fit:cover; border-radius:6px;">
                        </div>
                        <label class="btn btn-luar btn-kecil" style="cursor:pointer; display:inline-block;">
                            Ganti Banner Hero
                            <input type="file" name="foto_hero" accept="image/*" style="display:none;" onchange="this.parentElement.nextElementSibling.innerText = this.files[0].name">
                        </label>
                        <small style="display:block; color:#777; margin-top:6px; font-size:0.75rem;">Ditampilkan di halaman depan</small>
                    </div>
                </div>
            </div>

            <!-- Panel Identitas & Narasi -->
            <div class="kartu-admin form-admin">
                <h2 style="font-size:1.2rem; margin-bottom:18px; color:var(--hijau-tua); border-bottom:1px solid #eee; padding-bottom:10px;">
                    📝 Data Identitas &amp; Sambutan
                </h2>

                <div class="grup-form">
                    <label for="nama_sekolah">Nama Sekolah</label>
                    <input type="text" id="nama_sekolah" name="nama_sekolah" required value="<?= e($profil['nama_sekolah']) ?>">
                </div>

                <div style="display:grid; grid-template-columns:1fr 1fr; gap:18px;">
                    <div class="grup-form">
                        <label for="tahun_berdiri">Tahun Berdiri</label>
                        <input type="number" id="tahun_berdiri" name="tahun_berdiri" value="<?= e($profil['tahun_berdiri']) ?>">
                    </div>
                    <div class="grup-form">
                        <label for="npsn">NPSN (Opsional)</label>
                        <input type="text" id="npsn" name="npsn" value="<?= e($profil['npsn'] ?? '') ?>">
                    </div>
                </div>

                <div style="display:grid; grid-template-columns:1fr 1fr; gap:18px;">
                    <div class="grup-form">
                        <label for="telepon">Nomor Telepon</label>
                        <input type="text" id="telepon" name="telepon" value="<?= e($profil['telepon']) ?>">
                    </div>
                    <div class="grup-form">
                        <label for="email">Alamat Email</label>
                        <input type="email" id="email" name="email" value="<?= e($profil['email']) ?>">
                    </div>
                </div>

                <div class="grup-form">
                    <label for="alamat">Alamat Jalan</label>
                    <input type="text" id="alamat" name="alamat" value="<?= e($profil['alamat']) ?>">
                </div>

                <div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(140px, 1fr)); gap:14px;">
                    <div class="grup-form">
                        <label for="desa_kelurahan">Desa/Kelurahan</label>
                        <input type="text" id="desa_kelurahan" name="desa_kelurahan" value="<?= e($profil['desa_kelurahan']) ?>">
                    </div>
                    <div class="grup-form">
                        <label for="kecamatan">Kecamatan</label>
                        <input type="text" id="kecamatan" name="kecamatan" value="<?= e($profil['kecamatan']) ?>">
                    </div>
                    <div class="grup-form">
                        <label for="kabupaten">Kabupaten</label>
                        <input type="text" id="kabupaten" name="kabupaten" value="<?= e($profil['kabupaten']) ?>">
                    </div>
                    <div class="grup-form">
                        <label for="provinsi">Provinsi</label>
                        <input type="text" id="provinsi" name="provinsi" value="<?= e($profil['provinsi']) ?>">
                    </div>
                    <div class="grup-form">
                        <label for="kode_pos">Kode Pos</label>
                        <input type="text" id="kode_pos" name="kode_pos" value="<?= e($profil['kode_pos']) ?>">
                    </div>
                </div>

                <div class="grup-form">
                    <label for="sambutan_kepsek">Sambutan Kepala Sekolah / Pimpinan</label>
                    <textarea id="sambutan_kepsek" name="sambutan_kepsek" style="min-height:100px;"><?= e($profil['sambutan_kepsek'] ?? '') ?></textarea>
                </div>

                <div class="grup-form">
                    <label for="visi">Visi Sekolah</label>
                    <textarea id="visi" name="visi" style="min-height:80px;"><?= e($profil['visi']) ?></textarea>
                </div>

                <div class="grup-form">
                    <label for="misi">Misi Sekolah (Gunakan baris baru untuk setiap poin misi)</label>
                    <textarea id="misi" name="misi" style="min-height:120px;"><?= e($profil['misi']) ?></textarea>
                </div>

                <div class="grup-form">
                    <label for="sejarah">Sejarah Singkat Sekolah</label>
                    <textarea id="sejarah" name="sejarah" style="min-height:100px;"><?= e($profil['sejarah']) ?></textarea>
                </div>
            
            <h2 class="judul-bagian-admin" style="margin-top:30px;">Tautan Sosial Media</h2>
            <div class="form-grup">
                <label>Facebook URL</label>
                <input type="url" name="facebook" class="form-input" value="<?= e($profil['facebook'] ?? '') ?>">
            </div>
            <div class="form-grup">
                <label>Instagram URL</label>
                <input type="url" name="instagram" class="form-input" value="<?= e($profil['instagram'] ?? '') ?>">
            </div>
            <div class="form-grup">
                <label>YouTube URL</label>
                <input type="url" name="youtube" class="form-input" value="<?= e($profil['youtube'] ?? '') ?>">
            </div>
            <div class="form-grup">
                <label>Nomor WhatsApp (Live Chat) <small>(Gunakan format 628xxx)</small></label>
                <input type="text" name="whatsapp" class="form-input" value="<?= e($profil['whatsapp'] ?? '') ?>" placeholder="628123456789">
            </div>
            
            <h2 class="judul-bagian-admin" style="margin-top:30px;">Pengaturan Video Beranda</h2>
            <div class="form-grup">
                <label>Tautan Video Profil (YouTube Embed URL / ID)</label>
                <input type="text" name="video_profil" class="form-input" value="<?= e($profil['video_profil'] ?? '') ?>" placeholder="Contoh: https://www.youtube.com/embed/XXXXXX atau ID Video">
            </div>

            <button type="submit" class="btn btn-emas" style="margin-top:20px;">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>
</body>
</html>
