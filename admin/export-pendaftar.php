<?php
session_start();
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';
require_admin_login();

// Ambil data pendaftar
$stmt = $pdo->query("SELECT * FROM pendaftar ORDER BY tanggal_daftar DESC");
$pendaftar = $stmt->fetchAll();

// Set header untuk export CSV
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=Data_Pendaftar_SPMB_' . date('Y-m-d') . '.csv');

// Buka output stream
$output = fopen('php://output', 'w');

// Header kolom CSV
fputcsv($output, [
    'No', 
    'Nomor Pendaftaran', 
    'Nama Lengkap', 
    'Jenis Kelamin', 
    'Tempat Lahir', 
    'Tanggal Lahir', 
    'Asal Sekolah', 
    'Nama Orang Tua', 
    'No HP', 
    'Alamat', 
    'Jurusan Pilihan', 
    'Status', 
    'Tanggal Daftar'
]);

// Isi data
$no = 1;
foreach ($pendaftar as $p) {
    fputcsv($output, [
        $no++,
        $p['nomor_pendaftaran'],
        $p['nama_lengkap'],
        $p['jenis_kelamin'],
        $p['tempat_lahir'],
        $p['tanggal_lahir'],
        $p['asal_sekolah'],
        $p['nama_orang_tua'],
        $p['no_hp'],
        $p['alamat'],
        $p['jurusan_pilihan'],
        $p['status'],
        $p['tanggal_daftar']
    ]);
}

fclose($output);
exit;
