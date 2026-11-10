<?php
session_start();
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';

$halaman_aktif = 'kontak';
$judul_halaman = 'Kontak';

$status = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama    = trim($_POST['nama'] ?? '');
    $email   = trim($_POST['email'] ?? '');
    $subjek  = trim($_POST['subjek'] ?? '');
    $isi     = trim($_POST['isi_pesan'] ?? '');

    $isi     = trim($_POST['isi_pesan'] ?? '');
    $captcha_jawab = (int)($_POST['captcha'] ?? 0);

    if ($nama === '' || $email === '' || $isi === '') {
        $status = ['tipe' => 'gagal', 'teks' => 'Mohon lengkapi nama, email, dan isi pesan.'];
    } elseif (!isset($_SESSION['captcha_hasil']) || $captcha_jawab !== $_SESSION['captcha_hasil']) {
        $status = ['tipe' => 'gagal', 'teks' => 'Jawaban verifikasi keamanan (CAPTCHA) salah.'];
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $status = ['tipe' => 'gagal', 'teks' => 'Format email tidak valid.'];
    } else {
        $stmt = $pdo->prepare("INSERT INTO pesan (nama, email, subjek, isi_pesan) VALUES (?, ?, ?, ?)");
        $stmt->execute([$nama, $email, $subjek, $isi]);
        $status = ['tipe' => 'sukses', 'teks' => 'Pesan Anda berhasil dikirim. Tim kami akan segera menghubungi Anda.'];
    }
}

// Generate Math Captcha
$angka1 = rand(1, 9);
$angka2 = rand(1, 9);
$_SESSION['captcha_hasil'] = $angka1 + $angka2;
$captcha_soal = "$angka1 + $angka2";

$profil = $pdo->query("SELECT * FROM profil_sekolah LIMIT 1")->fetch();

require __DIR__ . '/includes/header.php';
?>

<div class="header-halaman">
    <div class="wadah">
        <span class="jejak"><a href="index.php">Beranda</a> / Kontak</span>
        <h1>Hubungi Kami</h1>
        <p style="color:#C9D3C6; max-width:60ch;">Ada pertanyaan seputar pendaftaran, asrama, atau kegiatan sekolah? Silakan hubungi kami.</p>
    </div>
</div>

<section>
    <div class="wadah dua-kolom">
        <div>
            <div class="kotak-form">
                <?php if ($status): ?>
                    <div class="pesan-status <?= $status['tipe'] ?>"><?= e($status['teks']) ?></div>
                <?php endif; ?>
                <form method="post" action="kontak.php">
                    <div class="baris-form">
                        <div class="grup-form">
                            <label for="nama">Nama Lengkap</label>
                            <input type="text" id="nama" name="nama" required value="<?= e($_POST['nama'] ?? '') ?>">
                        </div>
                        <div class="grup-form">
                            <label for="email">Alamat Email</label>
                            <input type="email" id="email" name="email" required value="<?= e($_POST['email'] ?? '') ?>">
                        </div>
                    </div>
                    <div class="grup-form">
                        <label for="subjek">Subjek</label>
                        <input type="text" id="subjek" name="subjek" value="<?= e($_POST['subjek'] ?? '') ?>">
                    </div>
                    <div class="grup-form">
                        <label for="isi_pesan">Pesan</label>
                        <textarea id="isi_pesan" name="isi_pesan" required><?= e($_POST['isi_pesan'] ?? '') ?></textarea>
                    </div>
                    <div class="grup-form">
                        <label for="captcha">Verifikasi Keamanan: Berapa <?= $captcha_soal ?> ?</label>
                        <input type="number" id="captcha" name="captcha" required class="form-input" style="width:100px;">
                    </div>
                    <button type="submit" class="btn btn-emas">Kirim Pesan</button>
                </form>
            </div>
        </div>
        <div>
            <div class="kartu" style="margin-bottom:20px;">
                <span class="panel-label">Alamat</span>
                <p style="margin-top:10px;"><?= e($profil['alamat']) ?>, Desa <?= e($profil['desa_kelurahan']) ?>, <?= e($profil['kecamatan']) ?>, <?= e($profil['kabupaten']) ?>, <?= e($profil['provinsi']) ?> <?= e($profil['kode_pos']) ?></p>
                <span class="panel-label">Telepon</span>
                <p style="margin-top:10px;"><?= e($profil['telepon']) ?></p>
                <span class="panel-label">Email</span>
                <p style="margin-top:10px;"><?= e($profil['email']) ?></p>
            </div>
            <div class="kotak-peta">
                <iframe loading="lazy" src="https://www.google.com/maps?q=Jl.+Tonjong,+Sindangraja,+Kec.+Jamanis,+Kabupaten+Tasikmalaya,+Jawa+Barat+46175&output=embed"></iframe>
            </div>
        </div>
    </div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
