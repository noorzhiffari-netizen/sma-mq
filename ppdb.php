<?php
header("Location: https://spmb.ponpes-mhmq.my.id/");
exit;
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';
$halaman_aktif = 'ppdb';
$judul_halaman = 'SPMB';

$status = null;
$nomor_pendaftaran = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_lengkap    = trim($_POST['nama_lengkap'] ?? '');
    $jenis_kelamin   = $_POST['jenis_kelamin'] ?? '';
    $tempat_lahir    = trim($_POST['tempat_lahir'] ?? '');
    $tanggal_lahir   = $_POST['tanggal_lahir'] ?? null;
    $asal_sekolah    = trim($_POST['asal_sekolah'] ?? '');
    $nama_orang_tua  = trim($_POST['nama_orang_tua'] ?? '');
    $no_hp           = trim($_POST['no_hp'] ?? '');
    $alamat          = trim($_POST['alamat'] ?? '');
    $jurusan_pilihan = $_POST['jurusan_pilihan'] ?? '';

    $wajib = [$nama_lengkap, $jenis_kelamin, $no_hp, $jurusan_pilihan];
    if (in_array('', $wajib, true)) {
        $status = ['tipe' => 'gagal', 'teks' => 'Mohon lengkapi seluruh kolom bertanda wajib.'];
    } else {
        $nomor_pendaftaran = buat_nomor_pendaftaran($pdo);
        $stmt = $pdo->prepare(
            "INSERT INTO pendaftar
            (nomor_pendaftaran, nama_lengkap, jenis_kelamin, tempat_lahir, tanggal_lahir, asal_sekolah, nama_orang_tua, no_hp, alamat, jurusan_pilihan)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
        );
        $stmt->execute([
            $nomor_pendaftaran, $nama_lengkap, $jenis_kelamin,
            $tempat_lahir ?: null, $tanggal_lahir ?: null,
            $asal_sekolah ?: null, $nama_orang_tua ?: null,
            $no_hp, $alamat ?: null, $jurusan_pilihan
        ]);
        $status = ['tipe' => 'sukses', 'teks' => "Pendaftaran berhasil! Nomor pendaftaran Anda: $nomor_pendaftaran. Mohon simpan nomor ini untuk keperluan verifikasi selanjutnya."];
    }
}

require __DIR__ . '/includes/header.php';
?>

<div class="header-halaman">
    <div class="wadah">
        <span class="jejak"><a href="index.php">Beranda</a> / SPMB</span>
        <h1>Pendaftaran Santri Baru</h1>
        <p style="color:#C9D3C6; max-width:60ch;">Lengkapi formulir berikut untuk mendaftar sebagai calon santri SMAI MQ. Setiap santri yang diterima wajib tinggal di asrama.</p>
    </div>
</div>

<section>
    <div class="wadah dua-kolom">
        <div class="kotak-form">
            <?php if ($status): ?>
                <div class="pesan-status <?= $status['tipe'] ?>"><?= e($status['teks']) ?></div>
            <?php endif; ?>
            <form method="post" action="ppdb.php">
                <div class="baris-form">
                    <div class="grup-form">
                        <label for="nama_lengkap">Nama Lengkap Calon Santri *</label>
                        <input type="text" id="nama_lengkap" name="nama_lengkap" required value="<?= e($_POST['nama_lengkap'] ?? '') ?>">
                    </div>
                    <div class="grup-form">
                        <label for="jenis_kelamin">Jenis Kelamin *</label>
                        <select id="jenis_kelamin" name="jenis_kelamin" required>
                            <option value="">Pilih</option>
                            <option value="Laki-laki" <?= (($_POST['jenis_kelamin'] ?? '') === 'Laki-laki') ? 'selected' : '' ?>>Laki-laki</option>
                            <option value="Perempuan" <?= (($_POST['jenis_kelamin'] ?? '') === 'Perempuan') ? 'selected' : '' ?>>Perempuan</option>
                        </select>
                    </div>
                </div>
                <div class="baris-form">
                    <div class="grup-form">
                        <label for="tempat_lahir">Tempat Lahir</label>
                        <input type="text" id="tempat_lahir" name="tempat_lahir" value="<?= e($_POST['tempat_lahir'] ?? '') ?>">
                    </div>
                    <div class="grup-form">
                        <label for="tanggal_lahir">Tanggal Lahir</label>
                        <input type="date" id="tanggal_lahir" name="tanggal_lahir" value="<?= e($_POST['tanggal_lahir'] ?? '') ?>">
                    </div>
                </div>
                <div class="baris-form">
                    <div class="grup-form">
                        <label for="asal_sekolah">Asal Sekolah (SMP/MTs)</label>
                        <input type="text" id="asal_sekolah" name="asal_sekolah" value="<?= e($_POST['asal_sekolah'] ?? '') ?>">
                    </div>
                    <div class="grup-form">
                        <label for="nama_orang_tua">Nama Orang Tua/Wali</label>
                        <input type="text" id="nama_orang_tua" name="nama_orang_tua" value="<?= e($_POST['nama_orang_tua'] ?? '') ?>">
                    </div>
                </div>
                <div class="baris-form">
                    <div class="grup-form">
                        <label for="no_hp">Nomor HP/WhatsApp Aktif *</label>
                        <input type="text" id="no_hp" name="no_hp" required value="<?= e($_POST['no_hp'] ?? '') ?>">
                    </div>
                    <div class="grup-form">
                        <label for="jurusan_pilihan">Program Peminatan *</label>
                        <select id="jurusan_pilihan" name="jurusan_pilihan" required>
                            <option value="">Pilih</option>
                            <option value="IPA" <?= (($_POST['jurusan_pilihan'] ?? '') === 'IPA') ? 'selected' : '' ?>>IPA</option>
                            <option value="IPS" <?= (($_POST['jurusan_pilihan'] ?? '') === 'IPS') ? 'selected' : '' ?>>IPS</option>
                        </select>
                    </div>
                </div>
                <div class="grup-form">
                    <label for="alamat">Alamat Lengkap</label>
                    <textarea id="alamat" name="alamat"><?= e($_POST['alamat'] ?? '') ?></textarea>
                </div>
                <button type="submit" class="btn btn-emas">Kirim Pendaftaran</button>
            </form>
        </div>
        <div>
            <div class="kartu">
                <span class="panel-label">Perlu Diperhatikan</span>
                <ul class="daftar-fasilitas" style="margin-top:14px;">
                    <li>Seluruh santri yang diterima wajib tinggal di asrama selama pendidikan.</li>
                    <li>Formulir ini adalah pendaftaran awal; verifikasi berkas dilakukan setelah pendaftaran online.</li>
                    <li>Simpan nomor pendaftaran Anda untuk proses selanjutnya.</li>
                    <li>Hubungi panitia SPMB (Sistem Penerimaan Murid Baru) melalui halaman Kontak bila memerlukan bantuan.</li>
                </ul>
            </div>
        </div>
    </div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>
