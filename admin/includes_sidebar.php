<?php 
$halaman = $halaman ?? '';
$admin_role = 'superadmin';
if (isset($_SESSION['admin_id'])) {
    $stmt_role = $pdo->prepare("SELECT role FROM admin WHERE id = ?");
    $stmt_role->execute([$_SESSION['admin_id']]);
    $admin_role = $stmt_role->fetchColumn() ?: 'superadmin';
}
?>
<div class="admin-sidebar">
    <div class="merek" style="display:flex; flex-direction:column; align-items:center; text-align:center; gap:8px; padding-bottom:16px;">
        <img src="../assets/img/logo.png" alt="Logo SMAI MQ" style="width:50px; height:auto; filter:drop-shadow(0 2px 4px rgba(0,0,0,0.3));">
        <div>
            <strong style="font-size:1.1rem; display:block; color:#fff;">SMAI MQ</strong>
            <small style="color:var(--emas-terang);">Panel Admin</small>
        </div>
    </div>
    <nav>
        <ul>
            <li><a href="dashboard.php" class="<?= $halaman === 'dashboard' ? 'aktif' : '' ?>">Dasbor</a></li>
            <?php if ($admin_role === 'superadmin'): ?>
            <li><a href="profil.php" class="<?= $halaman === 'profil' ? 'aktif' : '' ?>">Profil &amp; Identitas</a></li>
            <li><a href="guru.php" class="<?= $halaman === 'guru' ? 'aktif' : '' ?>">Guru &amp; Staff</a></li>
            <li><a href="fasilitas.php" class="<?= $halaman === 'fasilitas' ? 'aktif' : '' ?>">Fasilitas</a></li>
            <li><a href="jurusan.php" class="<?= $halaman === 'jurusan' ? 'aktif' : '' ?>">Jurusan</a></li>
            <li><a href="asrama.php" class="<?= $halaman === 'asrama' ? 'aktif' : '' ?>">Asrama</a></li>
            <li><a href="prestasi.php" class="<?= $halaman === 'prestasi' ? 'aktif' : '' ?>">Prestasi</a></li>
            <li><a href="ekstrakurikuler.php" class="<?= $halaman === 'ekstrakurikuler' ? 'aktif' : '' ?>">Ekstrakurikuler</a></li>
            <li><a href="berita.php" class="<?= $halaman === 'berita' ? 'aktif' : '' ?>">Berita</a></li>
            <li><a href="pengumuman.php" class="<?= $halaman === 'pengumuman' ? 'aktif' : '' ?>">Pengumuman</a></li>
            <li><a href="galeri.php" class="<?= $halaman === 'galeri' ? 'aktif' : '' ?>">Galeri</a></li>
            <li><a href="kalender.php" class="<?= $halaman === 'kalender' ? 'aktif' : '' ?>">Kalender Akademik</a></li>
            <li><a href="karya.php" class="<?= $halaman === 'karya' ? 'aktif' : '' ?>">Karya Siswa</a></li>
            <li><a href="unduhan.php" class="<?= $halaman === 'unduhan' ? 'aktif' : '' ?>">Unduhan</a></li>
            <li><a href="alumni.php" class="<?= $halaman === 'alumni' ? 'aktif' : '' ?>">Alumni</a></li>
            <li><a href="faq.php" class="<?= $halaman === 'faq' ? 'aktif' : '' ?>">FAQ (Tanya Jawab)</a></li>
            <li><a href="pengguna.php" class="<?= $halaman === 'pengguna' ? 'aktif' : '' ?>">Pengguna (Admin)</a></li>
            <?php endif; ?>
            <li><a href="pendaftar.php" class="<?= $halaman === 'pendaftar' ? 'aktif' : '' ?>">Pendaftar SPMB</a></li>
            <li><a href="pesan.php" class="<?= $halaman === 'pesan' ? 'aktif' : '' ?>">Pesan Masuk</a></li>
            <li><a href="../index.php" target="_blank">Lihat Situs &#8599;</a></li>
            <li><a href="logout.php">Keluar</a></li>
        </ul>
    </nav>
</div>
