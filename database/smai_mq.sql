-- =====================================================================
-- Database: smai_mq
-- Website Resmi SMA Islam (SMAI) MQ - Tasikmalaya
-- Berbasis Pondok Pesantren (santri wajib tinggal di asrama)
-- =====================================================================

CREATE DATABASE IF NOT EXISTS smai_mq CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE smai_mq;

-- ---------------------------------------------------------------------
-- Tabel: admin (pengelola website)
-- ---------------------------------------------------------------------
CREATE TABLE admin (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    nama_lengkap VARCHAR(100) NOT NULL,
    role ENUM('superadmin', 'ppdb') DEFAULT 'superadmin',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Password default: admin123 (sudah di-hash dengan password_hash PHP - bcrypt)
INSERT INTO admin (username, password, nama_lengkap, role) VALUES
('admin', '$2y$10$tZ2yD/J2X8p.2W8fXjJ2qO5RzP0eXqJ2qO5RzP0eXqJ2qO5RzP0eX', 'Administrator SMAI MQ', 'superadmin');
-- Catatan: hash di atas adalah placeholder umum. Segera ganti password setelah instalasi
-- melalui admin/ganti_password.php, atau update manual dengan password_hash('passwordbaru', PASSWORD_BCRYPT).

-- ---------------------------------------------------------------------
-- Tabel: profil_sekolah (data umum yang bisa diubah tanpa ubah kode)
-- ---------------------------------------------------------------------
CREATE TABLE profil_sekolah (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_sekolah VARCHAR(150) NOT NULL,
    npsn VARCHAR(20) DEFAULT NULL,
    tahun_berdiri YEAR NOT NULL,
    alamat VARCHAR(255) NOT NULL,
    desa_kelurahan VARCHAR(100),
    kecamatan VARCHAR(100),
    kabupaten VARCHAR(100),
    provinsi VARCHAR(100),
    kode_pos VARCHAR(10),
    telepon VARCHAR(30),
    email VARCHAR(100),
    sambutan_kepsek TEXT,
    visi TEXT,
    misi TEXT,
    sejarah TEXT,
    logo VARCHAR(255) DEFAULT NULL,
    facebook VARCHAR(255) DEFAULT NULL,
    instagram VARCHAR(255) DEFAULT NULL,
    youtube VARCHAR(255) DEFAULT NULL,
    whatsapp VARCHAR(20) DEFAULT NULL,
    video_profil VARCHAR(255) DEFAULT NULL
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- Tabel: faq
-- ---------------------------------------------------------------------
CREATE TABLE faq (
    id INT AUTO_INCREMENT PRIMARY KEY,
    pertanyaan VARCHAR(255) NOT NULL,
    jawaban TEXT NOT NULL,
    urutan INT DEFAULT 0
) ENGINE=InnoDB;

INSERT INTO profil_sekolah
(nama_sekolah, tahun_berdiri, alamat, desa_kelurahan, kecamatan, kabupaten, provinsi, kode_pos, telepon, email, visi, misi, sejarah, logo, facebook, instagram, youtube)
VALUES
('SMA Islam MQ (SMAI MQ)', 2022,
 'Jl. Tonjong', 'Sindangraja', 'Kec. Jamanis', 'Kabupaten Tasikmalaya', 'Jawa Barat', '46175',
 '(0265) 000000', 'info@smaimq.sch.id',
 'Menjadi lembaga pendidikan menengah atas berbasis pesantren yang melahirkan generasi berakhlak mulia, unggul dalam ilmu pengetahuan, dan mandiri dalam kehidupan.',
 'Menyelenggarakan pendidikan formal jenjang SMA yang terintegrasi dengan nilai-nilai kepesantrenan;\nMembina santri agar memiliki akhlak karimah, kemandirian, dan kedisiplinan tinggi melalui kehidupan asrama;\nMengembangkan potensi akademik santri pada jalur IPA dan IPS secara optimal;\nMenumbuhkan jiwa kepemimpinan dan kepedulian sosial berlandaskan nilai-nilai Islam.',
 'SMAI MQ didirikan pada tahun 2022 di Jl. Tonjong, Desa Sindangraja, Kecamatan Jamanis, Kabupaten Tasikmalaya sebagai bagian dari Pondok Pesantren MQ. Sejak awal berdiri, seluruh santri diwajibkan tinggal di asrama agar pembinaan akhlak, kedisiplinan, dan penguasaan ilmu agama dapat berjalan seiring dengan pendidikan formal.',
  'logo.png', 'https://facebook.com', 'https://instagram.com', 'https://youtube.com');

-- ---------------------------------------------------------------------
-- Tabel: jurusan (IPA & IPS)
-- ---------------------------------------------------------------------
CREATE TABLE jurusan (
    id INT AUTO_INCREMENT PRIMARY KEY,
    kode VARCHAR(10) NOT NULL UNIQUE,
    nama VARCHAR(100) NOT NULL,
    deskripsi TEXT,
    mata_pelajaran_unggulan TEXT,
    prospek TEXT
) ENGINE=InnoDB;

INSERT INTO jurusan (kode, nama, deskripsi, mata_pelajaran_unggulan, prospek) VALUES
('IPA', 'Ilmu Pengetahuan Alam',
 'Program peminatan bagi santri yang tertarik pada sains dan penerapannya, dipadukan dengan penguatan ilmu agama di asrama.',
 'Matematika Peminatan, Fisika, Kimia, Biologi',
 'Kedokteran, Farmasi, Teknik, MIPA, Kesehatan, dan bidang sains terapan lainnya'),
('IPS', 'Ilmu Pengetahuan Sosial',
 'Program peminatan bagi santri yang tertarik pada dinamika sosial, ekonomi, dan kemasyarakatan, dipadukan dengan kajian keislaman.',
 'Ekonomi, Sosiologi, Geografi, Sejarah',
 'Hukum, Ekonomi/Bisnis, Ilmu Komunikasi, Hubungan Internasional, Dakwah, dan bidang sosial-humaniora lainnya');

-- ---------------------------------------------------------------------
-- Tabel: asrama (info asrama - wajib bagi seluruh santri)
-- ---------------------------------------------------------------------
CREATE TABLE asrama (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_asrama VARCHAR(100) NOT NULL,
    jenis ENUM('Putra','Putri') NOT NULL,
    kapasitas INT DEFAULT 0,
    fasilitas TEXT,
    keterangan TEXT
) ENGINE=InnoDB;

INSERT INTO asrama (nama_asrama, jenis, kapasitas, fasilitas, keterangan) VALUES
('Asrama Putra Al-Fatih', 'Putra', 120, 'Kamar tidur, lemari pribadi, kamar mandi, musala asrama, ruang belajar bersama', 'Dihuni oleh seluruh santri putra sesuai kebijakan wajib mukim'),
('Asrama Putri Khadijah', 'Putri', 120, 'Kamar tidur, lemari pribadi, kamar mandi, musala asrama, ruang belajar bersama', 'Dihuni oleh seluruh santri putri sesuai kebijakan wajib mukim');

-- ---------------------------------------------------------------------
-- Tabel: fasilitas sekolah (umum)
-- ---------------------------------------------------------------------
CREATE TABLE fasilitas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_fasilitas VARCHAR(100) NOT NULL,
    deskripsi TEXT,
    gambar VARCHAR(255) DEFAULT NULL,
    urutan INT DEFAULT 0
) ENGINE=InnoDB;

INSERT INTO fasilitas (nama_fasilitas, deskripsi, urutan) VALUES
('Asrama Santri', 'Tempat tinggal wajib bagi seluruh santri putra dan putri, terpisah dan diawasi pembina asrama.', 1),
('Masjid / Musala', 'Pusat kegiatan ibadah harian dan kajian keislaman santri.', 2),
('Ruang Kelas', 'Ruang belajar representatif untuk kegiatan belajar-mengajar program IPA dan IPS.', 3),
('Laboratorium IPA', 'Laboratorium untuk praktikum Fisika, Kimia, dan Biologi.', 4),
('Perpustakaan', 'Koleksi buku pelajaran umum dan kitab-kitab keislaman.', 5),
('Lapangan Olahraga', 'Sarana kegiatan olahraga dan pengembangan bakat non-akademik santri.', 6);

-- ---------------------------------------------------------------------
-- Tabel: guru (tenaga pendidik)
-- ---------------------------------------------------------------------
CREATE TABLE guru (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    jabatan VARCHAR(100),
    bidang_studi VARCHAR(100),
    foto VARCHAR(255) DEFAULT NULL,
    urutan INT DEFAULT 0
) ENGINE=InnoDB;

INSERT INTO guru (nama, jabatan, bidang_studi, urutan) VALUES
('Kepala Sekolah', 'Kepala SMAI MQ', 'Manajemen Pendidikan', 1),
('Wakil Kepala Sekolah', 'Wakasek Kurikulum', 'Kurikulum', 2),
('Pembina Asrama Putra', 'Musyrif Asrama Putra', 'Kepesantrenan', 3),
('Pembina Asrama Putri', 'Musyrifah Asrama Putri', 'Kepesantrenan', 4);

-- ---------------------------------------------------------------------
-- Tabel: berita (untuk halaman berita/pengumuman, dikelola via admin)
-- ---------------------------------------------------------------------
CREATE TABLE berita (
    id INT AUTO_INCREMENT PRIMARY KEY,
    judul VARCHAR(200) NOT NULL,
    slug VARCHAR(220) NOT NULL UNIQUE,
    ringkasan VARCHAR(300),
    konten TEXT NOT NULL,
    gambar VARCHAR(255) DEFAULT NULL,
    penulis VARCHAR(100) DEFAULT 'Admin SMAI MQ',
    status ENUM('draft','terbit') DEFAULT 'terbit',
    dibuat_pada TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    diperbarui_pada TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

INSERT INTO berita (judul, slug, ringkasan, konten, penulis, status) VALUES
('Pembukaan Pendaftaran Santri Baru Tahun Ajaran Baru', 'pembukaan-pendaftaran-santri-baru',
 'SMAI MQ membuka pendaftaran santri baru untuk program IPA dan IPS dengan sistem asrama penuh.',
 'SMAI MQ resmi membuka pendaftaran santri baru. Seluruh calon santri yang diterima wajib tinggal di asrama selama menempuh pendidikan, mengikuti program IPA maupun IPS. Informasi lebih lanjut dapat dilihat pada halaman SPMB.',
 'Panitia SPMB', 'terbit'),
('Kegiatan Pembinaan Asrama Semester Ini', 'kegiatan-pembinaan-asrama',
 'Program pembinaan akhlak dan kedisiplinan santri di asrama putra dan putri berjalan lancar.',
 'Kegiatan pembinaan di Asrama Putra Al-Fatih dan Asrama Putri Khadijah meliputi kajian rutin, tahfidz, serta pendampingan belajar malam bagi seluruh santri.',
 'Bagian Kepesantrenan', 'terbit');

-- ---------------------------------------------------------------------
-- Tabel: galeri (dokumentasi kegiatan)
-- ---------------------------------------------------------------------
CREATE TABLE galeri (
    id INT AUTO_INCREMENT PRIMARY KEY,
    judul VARCHAR(150) NOT NULL,
    kategori VARCHAR(50) DEFAULT 'Kegiatan',
    gambar VARCHAR(255) NOT NULL,
    keterangan VARCHAR(255),
    dibuat_pada TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

INSERT INTO galeri (judul, kategori, gambar, keterangan) VALUES
('Suasana Belajar di Kelas', 'Akademik', 'kelas.jpg', 'Kegiatan belajar-mengajar program IPA dan IPS'),
('Kegiatan Asrama Putra', 'Asrama', 'asrama-putra.jpg', 'Aktivitas santri di Asrama Putra Al-Fatih'),
('Kegiatan Asrama Putri', 'Asrama', 'asrama-putri.jpg', 'Aktivitas santri di Asrama Putri Khadijah'),
('Kajian Rutin', 'Kepesantrenan', 'kajian.jpg', 'Kajian keislaman rutin bersama pembina asrama');

-- ---------------------------------------------------------------------
-- Tabel: pendaftar (formulir SPMB / pendaftaran santri baru)
-- ---------------------------------------------------------------------
CREATE TABLE pendaftar (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nomor_pendaftaran VARCHAR(30) NOT NULL UNIQUE,
    nama_lengkap VARCHAR(100) NOT NULL,
    jenis_kelamin ENUM('Laki-laki','Perempuan') NOT NULL,
    tempat_lahir VARCHAR(100),
    tanggal_lahir DATE,
    asal_sekolah VARCHAR(150),
    nama_orang_tua VARCHAR(100),
    no_hp VARCHAR(20) NOT NULL,
    alamat TEXT,
    jurusan_pilihan ENUM('IPA','IPS') NOT NULL,
    status ENUM('Menunggu Verifikasi','Diterima','Ditolak') DEFAULT 'Menunggu Verifikasi',
    tanggal_daftar TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- Tabel: pesan (formulir kontak dari pengunjung website)
-- ---------------------------------------------------------------------
CREATE TABLE pesan (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    subjek VARCHAR(150),
    isi_pesan TEXT NOT NULL,
    sudah_dibaca TINYINT(1) DEFAULT 0,
    dikirim_pada TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- Tabel: prestasi
-- ---------------------------------------------------------------------
CREATE TABLE prestasi (
    id INT AUTO_INCREMENT PRIMARY KEY,
    judul VARCHAR(200) NOT NULL,
    deskripsi TEXT,
    gambar VARCHAR(255) DEFAULT NULL,
    tanggal_meraih DATE,
    dibuat_pada TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- Tabel: ekstrakurikuler
-- ---------------------------------------------------------------------
CREATE TABLE ekstrakurikuler (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    deskripsi TEXT,
    gambar VARCHAR(255) DEFAULT NULL,
    jadwal VARCHAR(100),
    dibuat_pada TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- Tabel: pengumuman
-- ---------------------------------------------------------------------
CREATE TABLE pengumuman (
    id INT AUTO_INCREMENT PRIMARY KEY,
    judul VARCHAR(200) NOT NULL,
    isi TEXT NOT NULL,
    dibuat_pada TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- Tabel: unduhan
-- ---------------------------------------------------------------------
CREATE TABLE unduhan (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_file VARCHAR(200) NOT NULL,
    keterangan VARCHAR(255),
    path_file VARCHAR(255) NOT NULL,
    dibuat_pada TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- Tabel: alumni
-- ---------------------------------------------------------------------
CREATE TABLE alumni (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama_lengkap VARCHAR(150) NOT NULL,
    tahun_lulus YEAR NOT NULL,
    pekerjaan_kuliah VARCHAR(200),
    testimoni TEXT,
    foto VARCHAR(255) DEFAULT NULL,
    status ENUM('draft', 'terbit') DEFAULT 'draft',
    dibuat_pada TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;
