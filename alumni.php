<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';

require_once __DIR__ . '/includes/functions.php';

session_start();

$status_pesan = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_alumni'])) {
    $nama = trim($_POST['nama_lengkap']);
    $tahun = (int) $_POST['tahun_lulus'];
    $pekerjaan = trim($_POST['pekerjaan_kuliah']);
    $testimoni = trim($_POST['testimoni']);
    $captcha = (int)($_POST['captcha'] ?? 0);

    if (!isset($_SESSION['captcha_hasil']) || $captcha !== $_SESSION['captcha_hasil']) {
        $status_pesan = ['tipe' => 'gagal', 'teks' => 'CAPTCHA salah.'];
    } else {
        $foto = null;
        if (!empty($_FILES['foto']['name'])) {
            $ext = strtolower(pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION));
            if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp'])) {
                $namaFile = 'alumni-temp-' . time() . '.' . $ext;
                $destDir = __DIR__ . '/assets/img/alumni';
                if (!is_dir($destDir)) { mkdir($destDir, 0755, true); }
                move_uploaded_file($_FILES['foto']['tmp_name'], $destDir . '/' . $namaFile);
                $foto = $namaFile;
            }
        }

        $stmt = $pdo->prepare("INSERT INTO alumni (nama_lengkap, tahun_lulus, pekerjaan_kuliah, testimoni, status, foto) VALUES (?, ?, ?, ?, 'draft', ?)");
        $stmt->execute([$nama, $tahun, $pekerjaan, $testimoni, $foto]);
        $status_pesan = ['tipe' => 'sukses', 'teks' => 'Terima kasih, data Anda berhasil dikirim dan menunggu moderasi admin.'];
    }
}

// Generate Math Captcha
$angka1 = rand(1, 9);
$angka2 = rand(1, 9);
$_SESSION['captcha_hasil'] = $angka1 + $angka2;
$captcha_soal = "$angka1 + $angka2";

$judul_halaman = 'Portal Alumni - SMAI MQ';
require_once __DIR__ . '/includes/header.php';

$alumni = $pdo->query("SELECT * FROM alumni WHERE status = 'terbit' ORDER BY tahun_lulus DESC")->fetchAll();
?>

<div class="kontainer">
    <div style="text-align:center; padding: 40px 0;">
        <h1 style="color:var(--biru-tua); font-size:2.5rem; margin-bottom:10px;">Portal & Testimoni Alumni</h1>
        <p style="color:#555; max-width:600px; margin:0 auto;">Kisah sukses, jejak langkah, dan testimoni alumni SMA Islam MQ di berbagai bidang.</p>
    </div>

    <?php if (!$alumni): ?>
        <div style="text-align:center; padding: 50px; background:#f9f9f9; border-radius:10px; margin-bottom:40px;">
            <p>Belum ada testimoni alumni yang dipublikasikan.</p>
        </div>
    <?php else: ?>
        <div style="display:grid; grid-template-columns:repeat(auto-fill, minmax(300px, 1fr)); gap:30px; margin-bottom:40px;">
            <?php foreach ($alumni as $a): ?>
                <div style="background:#fff; border:1px solid #eee; border-radius:10px; padding:25px; text-align:center; box-shadow:0 4px 10px rgba(0,0,0,0.03);">
                    <div style="width:100px; height:100px; border-radius:50%; margin:0 auto 15px auto; overflow:hidden; border:3px solid var(--emas); padding:3px;">
                        <?php if ($a['foto']): ?>
                            <img src="assets/img/alumni/<?= e($a['foto']) ?>" alt="<?= e($a['nama_lengkap']) ?>" style="width:100%; height:100%; object-fit:cover; border-radius:50%;">
                        <?php else: ?>
                            <div style="width:100%; height:100%; background:#f0f0f0; border-radius:50%; display:flex; align-items:center; justify-content:center; color:#999; font-size:12px;">No Photo</div>
                        <?php endif; ?>
                    </div>
                    <h3 style="color:var(--biru-tua); margin-bottom:5px; font-size:1.2rem;"><?= e($a['nama_lengkap']) ?></h3>
                    <p style="color:var(--emas); font-weight:bold; font-size:0.9rem; margin-bottom:5px;">Lulusan <?= e($a['tahun_lulus']) ?></p>
                    <p style="color:#777; font-size:0.9rem; margin-bottom:15px;"><?= e($a['pekerjaan_kuliah']) ?></p>
                    
                    <?php if ($a['testimoni']): ?>
                        <div style="position:relative; padding:15px; background:#fdfdfd; border-radius:8px; border:1px solid #f0f0f0; font-style:italic; color:#555; font-size:0.95rem; line-height:1.5;">
                            <span style="font-size:2rem; color:var(--emas-terang); position:absolute; top:-10px; left:10px; opacity:0.5;">&ldquo;</span>
                            <?= nl2br(e($a['testimoni'])) ?>
                            <span style="font-size:2rem; color:var(--emas-terang); position:absolute; bottom:-25px; right:10px; opacity:0.5;">&rdquo;</span>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <hr style="border:0; border-top:1px solid #ddd; margin:40px 0;">

    <div style="max-width:600px; margin:0 auto; background:#fff; padding:30px; border-radius:10px; border:1px solid #eee;">
        <h2 style="color:var(--biru-tua); text-align:center; margin-bottom:20px;">Kirim Data / Testimoni Alumni</h2>
        <?php if ($status_pesan): ?>
            <div style="padding:10px; border-radius:5px; margin-bottom:20px; color:#fff; background:<?= $status_pesan['tipe'] === 'sukses' ? '#4CAF50' : '#F44336' ?>">
                <?= e($status_pesan['teks']) ?>
            </div>
        <?php endif; ?>
        <form method="post" action="alumni.php" enctype="multipart/form-data">
            <input type="hidden" name="submit_alumni" value="1">
            <div style="margin-bottom:15px;">
                <label style="display:block; margin-bottom:5px;">Nama Lengkap</label>
                <input type="text" name="nama_lengkap" style="width:100%; padding:10px; border:1px solid #ccc; border-radius:5px;" required>
            </div>
            <div style="margin-bottom:15px;">
                <label style="display:block; margin-bottom:5px;">Tahun Lulus</label>
                <input type="number" name="tahun_lulus" style="width:100%; padding:10px; border:1px solid #ccc; border-radius:5px;" required>
            </div>
            <div style="margin-bottom:15px;">
                <label style="display:block; margin-bottom:5px;">Pekerjaan / Kuliah Saat Ini</label>
                <input type="text" name="pekerjaan_kuliah" style="width:100%; padding:10px; border:1px solid #ccc; border-radius:5px;">
            </div>
            <div style="margin-bottom:15px;">
                <label style="display:block; margin-bottom:5px;">Pesan Testimoni</label>
                <textarea name="testimoni" style="width:100%; padding:10px; border:1px solid #ccc; border-radius:5px; height:100px;"></textarea>
            </div>
            <div style="margin-bottom:15px;">
                <label style="display:block; margin-bottom:5px;">Foto Wajah</label>
                <input type="file" name="foto" accept="image/*" style="width:100%;">
            </div>
            <div style="margin-bottom:20px;">
                <label style="display:block; margin-bottom:5px;">Berapa <?= $captcha_soal ?>?</label>
                <input type="number" name="captcha" style="width:100px; padding:10px; border:1px solid #ccc; border-radius:5px;" required>
            </div>
            <button type="submit" class="btn btn-emas" style="width:100%;">Kirim Testimoni</button>
        </form>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
