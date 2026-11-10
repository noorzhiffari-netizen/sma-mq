# Website SMAI MQ

Website resmi **SMA Islam MQ (SMAI MQ)** — sekolah berbasis Pondok Pesantren
di Jl. Tonjong, Desa Sindangraja, Kec. Jamanis, Kabupaten Tasikmalaya,
Jawa Barat 46175. Seluruh santri wajib tinggal di asrama. Program peminatan:
**IPA** dan **IPS**. Berdiri sejak tahun **2022**.

Dibangun dengan **PHP native + MySQL (PDO)**, tanpa framework, agar mudah
dijalankan di hosting/cPanel biasa maupun di komputer lokal (XAMPP/Laragon).

## Struktur Folder

```
smai-mq/
├── index.php              Beranda
├── profil.php              Profil sekolah (sejarah, visi-misi, pembina)
├── jurusan.php             Program IPA & IPS
├── asrama.php              Info wajib asrama
├── fasilitas.php           Fasilitas sekolah
├── berita.php               Daftar berita
├── berita-detail.php       Detail satu berita
├── galeri.php               Galeri foto kegiatan
├── kontak.php               Form kontak (tersimpan ke database)
├── ppdb.php                  Form pendaftaran santri baru (tersimpan ke database)
├── config/database.php      Konfigurasi koneksi database
├── includes/                Header, footer, fungsi bantu
├── assets/css, assets/js, assets/img/galeri
├── admin/                    Panel admin (kelola berita, galeri, pendaftar, pesan)
└── database/smai_mq.sql     Skema + data awal database
```

## Cara Instalasi (Lokal - XAMPP/Laragon)

1. Salin folder `smai-mq` ke `htdocs` (XAMPP) atau `www` (Laragon).
2. Buka **phpMyAdmin**, buat database baru (atau biarkan, karena file SQL
   sudah membuatnya otomatis), lalu impor file `database/smai_mq.sql`.
3. Buka `config/database.php`, sesuaikan bila perlu:
   ```php
   define('DB_HOST', 'localhost');
   define('DB_NAME', 'smai_mq');
   define('DB_USER', 'root');
   define('DB_PASS', '');
   ```
4. Akses website di browser: `http://localhost/smai-mq/`
5. Akses panel admin di: `http://localhost/smai-mq/admin/login.php`
   - Username: `admin`
   - Password: `admin123`
   - **Segera ganti password** setelah login pertama (update langsung di
     tabel `admin`, kolom `password`, menggunakan hasil dari
     `password_hash('password_baru', PASSWORD_BCRYPT)`).

## Cara Instalasi (Hosting/cPanel)

1. Buat database MySQL beserta user melalui menu **MySQL Databases** di cPanel.
2. Impor `database/smai_mq.sql` melalui **phpMyAdmin**.
3. Unggah seluruh isi folder `smai-mq` ke `public_html` (atau subfolder).
4. Sesuaikan `config/database.php` dengan nama database, user, dan password
   yang diberikan oleh hosting.
5. Pastikan folder `assets/img/galeri/` dapat ditulis (writable) oleh PHP
   agar fitur unggah foto di panel admin berfungsi.

## Fitur

- Halaman publik: Beranda, Profil, Jurusan (IPA/IPS), Asrama, Fasilitas,
  Berita, Galeri, Kontak, dan Formulir PPDB — semua konten diambil dari
  database MySQL.
- Formulir **PPDB** otomatis membuat nomor pendaftaran dan menyimpannya
  ke tabel `pendaftar`.
- Formulir **Kontak** menyimpan pesan pengunjung ke tabel `pesan`.
- Panel **admin** (folder `admin/`) untuk:
  - Menulis, mengubah, menghapus berita.
  - Mengunggah dan menghapus foto galeri.
  - Melihat dan mengubah status pendaftar PPDB (Menunggu Verifikasi /
    Diterima / Ditolak).
  - Membaca dan menghapus pesan dari formulir kontak.

## Mengubah Data Sekolah

Data alamat, visi-misi, sejarah, dan sambutan diambil dari tabel
`profil_sekolah`. Untuk mengubahnya, edit langsung melalui phpMyAdmin pada
tabel tersebut (satu baris data).

## Catatan Keamanan

- Ganti password admin default sesegera mungkin.
- Selalu gunakan HTTPS saat sudah online.
- Jangan mengunggah folder `config/database.php` dengan kredensial database
  produksi ke repository publik.
