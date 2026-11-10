<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/config/database.php';

try {
    echo "<h3>Memulai Perbaikan Database...</h3>";

    // 1. Tambah Kolom Baru (Jika belum ada)
    echo "Memeriksa struktur tabel...<br>";
    
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

    // asrama
    $colsA = $pdo->query("SHOW COLUMNS FROM asrama")->fetchAll(PDO::FETCH_COLUMN);
    if (!in_array('gambar', $colsA)) {
        $pdo->exec("ALTER TABLE asrama ADD COLUMN gambar VARCHAR(255) DEFAULT NULL");
    }

    // guru
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

    echo "Struktur tabel berhasil diperbarui!<br>";

    // 2. Isi Data Dummy Guru (Jika kosong atau masih data bawaan)
    echo "Memeriksa data personil...<br>";
    $cekAwal = $pdo->query("SELECT COUNT(*) c FROM guru WHERE nama = 'Kepala Sekolah'")->fetch()['c'];
    if ($cekAwal > 0) {
        $pdo->exec("DELETE FROM guru");
    }

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
        echo "Data personil berhasil ditambahkan!<br>";
    }

    echo "<h3>Semua proses selesai! Database Anda sekarang 100% kompatibel.</h3>";
    echo "<p><a href='index.php'>Kembali ke Halaman Utama</a></p>";

} catch (Exception $e) {
    echo "<h3 style='color:red'>Terjadi Kesalahan:</h3>";
    echo $e->getMessage();
}
