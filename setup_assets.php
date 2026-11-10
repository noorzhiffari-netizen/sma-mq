<?php
/**
 * Setup Script - Menyiapkan direktori gambar, logo, foto dummy (termasuk guru, TU, karyawan), dan migrasi database.
 */
require_once __DIR__ . '/config/database.php';

$dirs = [
    __DIR__ . '/assets/img',
    __DIR__ . '/assets/img/profil',
    __DIR__ . '/assets/img/jurusan',
    __DIR__ . '/assets/img/asrama',
    __DIR__ . '/assets/img/fasilitas',
    __DIR__ . '/assets/img/berita',
    __DIR__ . '/assets/img/galeri',
    __DIR__ . '/assets/img/guru',
];

foreach ($dirs as $dir) {
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }
}

// 1. Salin Logo yang diunggah user
$uploadedLogo = 'C:/Users/Babeh Rusdhi/.gemini/antigravity-ide/brain/02ce3f87-0d38-4b6a-a384-bda1ae88089a/.user_uploaded/media_1788948794301.jpg';
if (file_exists($uploadedLogo)) {
    copy($uploadedLogo, __DIR__ . '/assets/img/logo.png');
    copy($uploadedLogo, __DIR__ . '/assets/img/logo.jpg');
}

// 2. Salin foto-foto hasil render AI
$heroSrc = 'C:/Users/Babeh Rusdhi/.gemini/antigravity-ide/brain/02ce3f87-0d38-4b6a-a384-bda1ae88089a/hero_pesantren_1788948973077.jpg';
if (file_exists($heroSrc)) {
    copy($heroSrc, __DIR__ . '/assets/img/hero.jpg');
    copy($heroSrc, __DIR__ . '/assets/img/profil/gedung.jpg');
    copy($heroSrc, __DIR__ . '/assets/img/fasilitas/asrama.jpg');
}

$kepsekSrc = 'C:/Users/Babeh Rusdhi/.gemini/antigravity-ide/brain/02ce3f87-0d38-4b6a-a384-bda1ae88089a/kepala_sekolah_1788948999932.jpg';
if (file_exists($kepsekSrc)) {
    copy($kepsekSrc, __DIR__ . '/assets/img/profil/kepsek.jpg');
    copy($kepsekSrc, __DIR__ . '/assets/img/guru/kepsek.jpg');
}

$labSrc = 'C:/Users/Babeh Rusdhi/.gemini/antigravity-ide/brain/02ce3f87-0d38-4b6a-a384-bda1ae88089a/lab_ipa_1788949016759.jpg';
if (file_exists($labSrc)) {
    copy($labSrc, __DIR__ . '/assets/img/jurusan/ipa.jpg');
    copy($labSrc, __DIR__ . '/assets/img/fasilitas/lab-ipa.jpg');
    copy($labSrc, __DIR__ . '/assets/img/galeri/kelas.jpg');
}

// 3. Download foto edukasi & personil (dengan timeout & fallback aman)
$extraImages = [
    __DIR__ . '/assets/img/jurusan/ips.jpg' => 'https://images.unsplash.com/photo-1523240795612-9a054b0db644?w=900&auto=format&fit=crop&q=80',
    __DIR__ . '/assets/img/asrama/asrama-putra.jpg' => 'https://images.unsplash.com/photo-1555854877-bab0e564b8d5?w=900&auto=format&fit=crop&q=80',
    __DIR__ . '/assets/img/asrama/asrama-putri.jpg' => 'https://images.unsplash.com/photo-1595526114035-0d45ed16cfbf?w=900&auto=format&fit=crop&q=80',
    __DIR__ . '/assets/img/fasilitas/masjid.jpg' => 'https://images.unsplash.com/photo-1564769625905-50e93615e769?w=900&auto=format&fit=crop&q=80',
    __DIR__ . '/assets/img/fasilitas/kelas.jpg' => 'https://images.unsplash.com/photo-1580582932707-520aed937b7b?w=900&auto=format&fit=crop&q=80',
    __DIR__ . '/assets/img/fasilitas/perpustakaan.jpg' => 'https://images.unsplash.com/photo-1521587760476-6c12a4b040da?w=900&auto=format&fit=crop&q=80',
    __DIR__ . '/assets/img/fasilitas/lapangan.jpg' => 'https://images.unsplash.com/photo-1534438327276-14e5300c3a48?w=900&auto=format&fit=crop&q=80',
    __DIR__ . '/assets/img/berita/pendaftaran.jpg' => 'https://images.unsplash.com/photo-1523050854058-8df90110c9f1?w=900&auto=format&fit=crop&q=80',
    __DIR__ . '/assets/img/berita/asrama.jpg' => 'https://images.unsplash.com/photo-1609599006353-e629aaabfeae?w=900&auto=format&fit=crop&q=80',
    __DIR__ . '/assets/img/galeri/asrama-putra.jpg' => 'https://images.unsplash.com/photo-1555854877-bab0e564b8d5?w=900&auto=format&fit=crop&q=80',
    __DIR__ . '/assets/img/galeri/asrama-putri.jpg' => 'https://images.unsplash.com/photo-1595526114035-0d45ed16cfbf?w=900&auto=format&fit=crop&q=80',
    __DIR__ . '/assets/img/galeri/kajian.jpg' => 'https://images.unsplash.com/photo-1609599006353-e629aaabfeae?w=900&auto=format&fit=crop&q=80',

    // Foto Guru
    __DIR__ . '/assets/img/guru/guru-1.jpg' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=600&auto=format&fit=crop&q=80',
    __DIR__ . '/assets/img/guru/guru-2.jpg' => 'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?w=600&auto=format&fit=crop&q=80',
    __DIR__ . '/assets/img/guru/guru-3.jpg' => 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=600&auto=format&fit=crop&q=80',
    __DIR__ . '/assets/img/guru/guru-4.jpg' => 'https://images.unsplash.com/photo-1580894732470-dfd35b91a783?w=600&auto=format&fit=crop&q=80',
    __DIR__ . '/assets/img/guru/guru-5.jpg' => 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?w=600&auto=format&fit=crop&q=80',
    __DIR__ . '/assets/img/guru/guru-6.jpg' => 'https://images.unsplash.com/photo-1567532939604-b6b5b0db2604?w=600&auto=format&fit=crop&q=80',
    __DIR__ . '/assets/img/guru/guru-7.jpg' => 'https://images.unsplash.com/photo-1519085360753-af0119f7cbe7?w=600&auto=format&fit=crop&q=80',

    // Foto Staff Tata Usaha
    __DIR__ . '/assets/img/guru/tu-1.jpg' => 'https://images.unsplash.com/photo-1522075469751-3a6694fb2f61?w=600&auto=format&fit=crop&q=80',
    __DIR__ . '/assets/img/guru/tu-2.jpg' => 'https://images.unsplash.com/photo-1573497019940-1c28c88b4f3e?w=600&auto=format&fit=crop&q=80',
    __DIR__ . '/assets/img/guru/tu-3.jpg' => 'https://images.unsplash.com/photo-1506794778202-cad84cf45f1d?w=600&auto=format&fit=crop&q=80',
    __DIR__ . '/assets/img/guru/tu-4.jpg' => 'https://images.unsplash.com/photo-1544005313-94ddf0286df2?w=600&auto=format&fit=crop&q=80',

    // Foto Karyawan & Tenaga Pendukung
    __DIR__ . '/assets/img/guru/karyawan-1.jpg' => 'https://images.unsplash.com/photo-1501196354995-cbb51c65aaea?w=600&auto=format&fit=crop&q=80',
    __DIR__ . '/assets/img/guru/karyawan-2.jpg' => 'https://images.unsplash.com/photo-1580489944761-15a19d654956?w=600&auto=format&fit=crop&q=80',
    __DIR__ . '/assets/img/guru/karyawan-3.jpg' => 'https://images.unsplash.com/photo-1517841905240-472988babdf9?w=600&auto=format&fit=crop&q=80',
    __DIR__ . '/assets/img/guru/karyawan-4.jpg' => 'https://images.unsplash.com/photo-1539571696357-5a69c17a67c6?w=600&auto=format&fit=crop&q=80',
    __DIR__ . '/assets/img/guru/karyawan-5.jpg' => 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=600&auto=format&fit=crop&q=80',
];

$ctx = stream_context_create([
    'http' => ['timeout' => 3, 'user_agent' => 'Mozilla/5.0'],
    'ssl'  => ['verify_peer' => false, 'verify_peer_name' => false]
]);

$fallbackImg = file_exists(__DIR__ . '/assets/img/profil/kepsek.jpg') ? __DIR__ . '/assets/img/profil/kepsek.jpg' : (__DIR__ . '/assets/img/hero.jpg');

foreach ($extraImages as $dest => $url) {
    if (!file_exists($dest)) {
        $data = @file_get_contents($url, false, $ctx);
        if ($data !== false && strlen($data) > 1000) {
            file_put_contents($dest, $data);
        } elseif (file_exists($fallbackImg)) {
            copy($fallbackImg, $dest);
        }
    }
}

// 4. Migrasi Kolom Database
try {
    // profil_sekolah
    $colsP = $pdo->query("SHOW COLUMNS FROM profil_sekolah")->fetchAll(PDO::FETCH_COLUMN);
    if (!in_array('foto_kepsek', $colsP)) {
        $pdo->exec("ALTER TABLE profil_sekolah ADD COLUMN foto_kepsek VARCHAR(255) DEFAULT 'kepsek.jpg'");
    }
    if (!in_array('foto_sekolah', $colsP)) {
        $pdo->exec("ALTER TABLE profil_sekolah ADD COLUMN foto_sekolah VARCHAR(255) DEFAULT 'gedung.jpg'");
    }

    // jurusan
    $colsJ = $pdo->query("SHOW COLUMNS FROM jurusan")->fetchAll(PDO::FETCH_COLUMN);
    if (!in_array('gambar', $colsJ)) {
        $pdo->exec("ALTER TABLE jurusan ADD COLUMN gambar VARCHAR(255) DEFAULT NULL");
    }
    $pdo->exec("UPDATE jurusan SET gambar = 'ipa.jpg' WHERE kode = 'IPA' AND (gambar IS NULL OR gambar = '')");
    $pdo->exec("UPDATE jurusan SET gambar = 'ips.jpg' WHERE kode = 'IPS' AND (gambar IS NULL OR gambar = '')");

    // asrama
    $colsA = $pdo->query("SHOW COLUMNS FROM asrama")->fetchAll(PDO::FETCH_COLUMN);
    if (!in_array('gambar', $colsA)) {
        $pdo->exec("ALTER TABLE asrama ADD COLUMN gambar VARCHAR(255) DEFAULT NULL");
    }
    $pdo->exec("UPDATE asrama SET gambar = 'asrama-putra.jpg' WHERE jenis = 'Putra' AND (gambar IS NULL OR gambar = '')");
    $pdo->exec("UPDATE asrama SET gambar = 'asrama-putri.jpg' WHERE jenis = 'Putri' AND (gambar IS NULL OR gambar = '')");

    // fasilitas
    $pdo->exec("UPDATE fasilitas SET gambar = 'asrama.jpg' WHERE nama_fasilitas LIKE '%Asrama%' AND (gambar IS NULL OR gambar = '')");
    $pdo->exec("UPDATE fasilitas SET gambar = 'masjid.jpg' WHERE nama_fasilitas LIKE '%Masjid%' AND (gambar IS NULL OR gambar = '')");
    $pdo->exec("UPDATE fasilitas SET gambar = 'kelas.jpg' WHERE nama_fasilitas LIKE '%Kelas%' AND (gambar IS NULL OR gambar = '')");
    $pdo->exec("UPDATE fasilitas SET gambar = 'lab-ipa.jpg' WHERE nama_fasilitas LIKE '%IPA%' AND (gambar IS NULL OR gambar = '')");
    $pdo->exec("UPDATE fasilitas SET gambar = 'perpustakaan.jpg' WHERE nama_fasilitas LIKE '%Perpustakaan%' AND (gambar IS NULL OR gambar = '')");
    $pdo->exec("UPDATE fasilitas SET gambar = 'lapangan.jpg' WHERE nama_fasilitas LIKE '%Lapangan%' AND (gambar IS NULL OR gambar = '')");

    // berita
    $pdo->exec("UPDATE berita SET gambar = 'pendaftaran.jpg' WHERE slug LIKE '%pendaftaran%' AND (gambar IS NULL OR gambar = '')");
    $pdo->exec("UPDATE berita SET gambar = 'asrama.jpg' WHERE slug LIKE '%asrama%' AND (gambar IS NULL OR gambar = '')");

    // 5. Migrasi Tabel Guru & Personil
    $colsG = $pdo->query("SHOW COLUMNS FROM guru")->fetchAll(PDO::FETCH_COLUMN);
    if (!in_array('kategori', $colsG)) {
        $pdo->exec("ALTER TABLE guru ADD COLUMN kategori ENUM('Guru','Tata Usaha','Karyawan') DEFAULT 'Guru'");
    }
    if (!in_array('pendidikan', $colsG)) {
        $pdo->exec("ALTER TABLE guru ADD COLUMN pendidikan VARCHAR(100) DEFAULT NULL");
    }
    if (!in_array('status_kepegawaian', $colsG)) {
        $pdo->exec("ALTER TABLE guru ADD COLUMN status_kepegawaian VARCHAR(50) DEFAULT 'Tetap'");
    }

    // Hapus data lama placeholder awal jika masih bawaan smai_mq.sql (4 baris awal nama generik)
    $cekAwal = $pdo->query("SELECT COUNT(*) c FROM guru WHERE nama = 'Kepala Sekolah'")->fetch()['c'];
    if ($cekAwal > 0) {
        $pdo->exec("DELETE FROM guru");
    }

    // Masukkan data dummy lengkap jika tabel kosong
    $totalPersonil = $pdo->query("SELECT COUNT(*) c FROM guru")->fetch()['c'];
    if ($totalPersonil == 0) {
        $personilList = [
            // Tenaga Pendidik / Guru
            ['K.H. Ahmad Fauzi, S.Pd.I.', 'Guru', 'Kepala Sekolah & Pembina', 'Kepesantrenan & Kepemimpinan', 'kepsek.jpg', 'S1 Pend. Agama Islam', 1],
            ['Ustadz Salman Al-Farisi, M.Pd.', 'Guru', 'Wakasek Kurikulum', 'PAI & Bahasa Arab', 'guru-1.jpg', 'S2 Manajemen Pendidikan', 2],
            ['Siti Nurhaliza, M.Si.', 'Guru', 'Guru Sains & Koordinator Lab', 'Fisika & Kimia Peminatan', 'guru-2.jpg', 'S2 Sains Terapan', 3],
            ['Rahmat Hidayat, S.Pd.', 'Guru', 'Guru Sains & Pembina Riset', 'Biologi & Matematika Peminatan', 'guru-3.jpg', 'S1 Pendidikan Biologi', 4],
            ['Dra. Hj. Fatimah Azzahra', 'Guru', 'Koordinator Peminatan IPS', 'Sosiologi & Ekonomi', 'guru-4.jpg', 'S1 Pendidikan Ilmu Sosial', 5],
            ['Muhammad Ridwan, S.Pd.', 'Guru', 'Guru Humaniora', 'Sejarah & Geografi', 'guru-5.jpg', 'S1 Pendidikan Sejarah', 6],
            ['Annisa Rahmawati, S.Pd.', 'Guru', 'Guru Bahasa & Literasi', 'Bahasa Inggris & B. Indonesia', 'guru-6.jpg', 'S1 Pendidikan Bahasa', 7],
            ['Farhan Abdullah, S.Kom.', 'Guru', 'Guru Teknologi & IT', 'Informatika & Multimedia', 'guru-7.jpg', 'S1 Ilmu Komputer', 8],

            // Staff Tata Usaha (Administrasi)
            ['Ahmad Zaelani, S.E.', 'Tata Usaha', 'Kepala Tata Usaha', 'Administrasi Umum & Perizinan', 'tu-1.jpg', 'S1 Manajemen Ekonomi', 9],
            ['Dewi Sartika, S.Ak.', 'Tata Usaha', 'Bendahara Sekolah', 'Keuangan & Administrasi SPP', 'tu-2.jpg', 'S1 Akuntansi', 10],
            ['Rizky Ramadhan, A.Md.', 'Tata Usaha', 'Staff Operator & Dapodik', 'Pengelolaan Data Santri & Persuratan', 'tu-3.jpg', 'D3 Manajemen Informatika', 11],
            ['Nurul Hikmah, S.I.Pust.', 'Tata Usaha', 'Kepala Perpustakaan', 'Pengelolaan Pustaka & Arsip Kitab', 'tu-4.jpg', 'S1 Ilmu Perpustakaan', 12],

            // Karyawan & Tenaga Pendukung
            ['Ust. Deden Wahyudi', 'Karyawan', 'Musyrif Utama Asrama Putra', 'Pembinaan Kedisiplinan Asrama Putra', 'karyawan-1.jpg', 'Pondok Pesantren Aliyah', 13],
            ['Ustdzh. Siti Maryam, S.Pd.', 'Karyawan', 'Musyrifah Utama Asrama Putri', 'Pembinaan Kedisiplinan Asrama Putri', 'karyawan-2.jpg', 'S1 PAI Pesantren', 14],
            ['Hendra Gunawan', 'Karyawan', 'Koordinator Sarpras & Teknisi', 'Pemeliharaan Gedung & Jaringan', 'karyawan-3.jpg', 'SMK Teknik Ketenagalistrikan', 15],
            ['Slamet Riyadi', 'Karyawan', 'Komandan Regu Keamanan', 'Keamanan Kampus & Pos Jaga Gerbang', 'karyawan-4.jpg', 'Sertifikasi Garda Pratama', 16],
            ['Agus Supriatna', 'Karyawan', 'Petugas Kebersihan & Asrama', 'Sanitasi Lingkungan Pesantren', 'karyawan-5.jpg', 'SMA / Sederajat', 17],
        ];

        $ins = $pdo->prepare("INSERT INTO guru (nama, kategori, jabatan, bidang_studi, foto, pendidikan, urutan) VALUES (?, ?, ?, ?, ?, ?, ?)");
        foreach ($personilList as $p) {
            $ins->execute($p);
        }
    }

} catch (Exception $e) {
    // Abaikan jika sudah termigrasi
}
