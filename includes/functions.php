<?php
/**
 * Fungsi-fungsi bantu yang dipakai di seluruh halaman
 */

function e($string) {
    return htmlspecialchars($string ?? '', ENT_QUOTES, 'UTF-8');
}

function buat_slug($string) {
    $string = strtolower(trim($string));
    $string = preg_replace('/[^a-z0-9]+/', '-', $string);
    return trim($string, '-');
}

function format_tanggal_indo($tanggal) {
    $bulan = [
        1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
        5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
        9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
    ];
    $ts = strtotime($tanggal);
    return date('j', $ts) . ' ' . $bulan[(int)date('n', $ts)] . ' ' . date('Y', $ts);
}

function buat_nomor_pendaftaran($pdo) {
    $tahun = date('Y');
    $stmt = $pdo->query("SELECT COUNT(*) AS jumlah FROM pendaftar WHERE YEAR(tanggal_daftar) = $tahun");
    $jumlah = (int) $stmt->fetch()['jumlah'] + 1;
    return 'SPMB-' . $tahun . '-' . str_pad($jumlah, 4, '0', STR_PAD_LEFT);
}

function is_admin_logged_in() {
    return isset($_SESSION['admin_id']);
}

function require_admin_login() {
    if (!is_admin_logged_in()) {
        header('Location: login.php');
        exit;
    }
}
