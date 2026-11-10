<?php
session_start();
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';
require_admin_login();

$halaman = 'guru';
$id = isset($_GET['id']) ? (int) $_GET['id'] : null;
$data = [
    'nama'         => '',
    'kategori'     => 'Guru',
    'jabatan'      => '',
    'bidang_studi' => '',
    'pendidikan'   => '',
    'urutan'       => 0,
    'foto'         => ''
];
$error = null;

if ($id) {
    $stmt = $pdo->prepare("SELECT * FROM guru WHERE id = ?");
    $stmt->execute([$id]);
    $existing = $stmt->fetch();
    if ($existing) { $data = $existing; }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data['nama']         = trim($_POST['nama'] ?? '');
    $data['kategori']     = $_POST['kategori'] ?? 'Guru';
    $data['jabatan']      = trim($_POST['jabatan'] ?? '');
    $data['bidang_studi'] = trim($_POST['bidang_studi'] ?? '');
    $data['pendidikan']   = trim($_POST['pendidikan'] ?? '');
    $data['urutan']       = (int) ($_POST['urutan'] ?? 0);

    if ($data['nama'] === '') {
        $error = 'Nama personil wajib diisi.';
    } else {
        $namaFoto = $data['foto'] ?? null;

        // Upload foto personil
        if (!empty($_FILES['foto']['name'])) {
            $ekstensiDiizinkan = ['jpg', 'jpeg', 'png', 'webp'];
            $ekstensi = strtolower(pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION));
            if (!in_array($ekstensi, $ekstensiDiizinkan, true)) {
                $error = 'Format gambar harus JPG, PNG, atau WEBP.';
            } else {
                $prefix = strtolower($data['kategori'] === 'Tata Usaha' ? 'tu' : ($data['kategori'] === 'Karyawan' ? 'karyawan' : 'guru'));
                $namaFoto = $prefix . '-' . time() . '-' . mt_rand(100, 999) . '.' . $ekstensi;
                $tujuanDir = __DIR__ . '/../assets/img/guru';
                if (!is_dir($tujuanDir)) {
                    mkdir($tujuanDir, 0755, true);
                }
                move_uploaded_file($_FILES['foto']['tmp_name'], $tujuanDir . '/' . $namaFoto);
            }
        }

        if (!$error) {
            if ($id) {
                $stmt = $pdo->prepare("UPDATE guru SET nama=?, kategori=?, jabatan=?, bidang_studi=?, pendidikan=?, urutan=?, foto=? WHERE id=?");
                $stmt->execute([$data['nama'], $data['kategori'], $data['jabatan'], $data['bidang_studi'], $data['pendidikan'], $data['urutan'], $namaFoto, $id]);
            } else {
                $stmt = $pdo->prepare("INSERT INTO guru (nama, kategori, jabatan, bidang_studi, pendidikan, urutan, foto) VALUES (?, ?, ?, ?, ?, ?, ?)");
                $stmt->execute([$data['nama'], $data['kategori'], $data['jabatan'], $data['bidang_studi'], $data['pendidikan'], $data['urutan'], $namaFoto]);
            }
            header('Location: guru.php?tersimpan=1');
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= $id ? 'Ubah' : 'Tambah' ?> Guru &amp; Staff - SMAI MQ</title>
<link rel="icon" type="image/png" href="../assets/img/logo.png">
<link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
<div class="admin-shell">
    <?php require __DIR__ . '/includes_sidebar.php'; ?>
    <div class="admin-konten">
        <div class="admin-topbar">
            <h1><?= $id ? 'Ubah Data Personil' : 'Tambah Guru / Staff Baru' ?></h1>
            <a href="guru.php" class="btn btn-luar">&larr; Kembali</a>
        </div>

        <div class="kartu-admin">
            <?php if ($error): ?><div class="pesan-status gagal"><?= e($error) ?></div><?php endif; ?>
            <form method="post" enctype="multipart/form-data" class="form-admin">
                <div class="grup-form">
                    <label for="nama">Nama Lengkap &amp; Gelar</label>
                    <input type="text" id="nama" name="nama" required value="<?= e($data['nama']) ?>" placeholder="Contoh: Ustadz Salman Al-Farisi, M.Pd.">
                </div>

                <div style="display:grid; grid-template-columns:1fr 1fr; gap:18px;">
                    <div class="grup-form">
                        <label for="kategori">Kategori Personil</label>
                        <select id="kategori" name="kategori" required>
                            <option value="Guru" <?= $data['kategori'] === 'Guru' ? 'selected' : '' ?>>Dewan Guru (Tenaga Pendidik)</option>
                            <option value="Tata Usaha" <?= $data['kategori'] === 'Tata Usaha' ? 'selected' : '' ?>>Staff Tata Usaha (Administrasi)</option>
                            <option value="Karyawan" <?= $data['kategori'] === 'Karyawan' ? 'selected' : '' ?>>Karyawan &amp; Tenaga Pendukung / Musyrif</option>
                        </select>
                    </div>

                    <div class="grup-form">
                        <label for="jabatan">Jabatan</label>
                        <input type="text" id="jabatan" name="jabatan" value="<?= e($data['jabatan']) ?>" placeholder="Contoh: Wakasek Kurikulum / Guru Fisika / Bendahara">
                    </div>
                </div>

                <div style="display:grid; grid-template-columns:1fr 1fr; gap:18px;">
                    <div class="grup-form">
                        <label for="bidang_studi">Bidang Studi / Tugas Khusus</label>
                        <input type="text" id="bidang_studi" name="bidang_studi" value="<?= e($data['bidang_studi']) ?>" placeholder="Contoh: Fisika &amp; Kimia / Pembina Asrama Putra">
                    </div>

                    <div class="grup-form">
                        <label for="pendidikan">Pendidikan Terakhir</label>
                        <input type="text" id="pendidikan" name="pendidikan" value="<?= e($data['pendidikan']) ?>" placeholder="Contoh: S1 Pendidikan Biologi / S2 Manajemen">
                    </div>
                </div>

                <div class="grup-form">
                    <label for="foto">Foto Profil</label>
                    <?php if (!empty($data['foto']) && file_exists(__DIR__ . '/../assets/img/guru/' . $data['foto'])): ?>
                        <div style="margin-bottom:12px; display:flex; align-items:center; gap:14px;">
                            <img src="../assets/img/guru/<?= e($data['foto']) ?>" alt="Foto Profil" style="width:70px; height:70px; border-radius:50%; object-fit:cover; border:2px solid #ddd;">
                            <small style="color:var(--abu);">Foto saat ini: <?= e($data['foto']) ?> (Pilih file baru jika ingin mengganti)</small>
                        </div>
                    <?php endif; ?>
                    <input type="file" id="foto" name="foto" accept="image/*">
                    <small style="color:#777; font-size:0.8rem; display:block; margin-top:4px;">Disarankan foto formal/pas foto dengan rasio vertikal (JPG, PNG, atau WEBP).</small>
                </div>

                <div class="grup-form">
                    <label for="urutan">Nomor Urut Tampilan</label>
                    <input type="number" id="urutan" name="urutan" value="<?= (int)$data['urutan'] ?>">
                </div>

                <button type="submit" class="btn btn-emas">Simpan Data Personil</button>
            </form>
        </div>
    </div>
</div>
</body>
</html>
