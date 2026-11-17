<?php
session_start();
require_once __DIR__ . "/../../config/connection.php";

if (!isset($_SESSION['id_akun']) || ($_SESSION['role'] ?? '') !== 'fo') {
    header("Location: ../../fo/login_fo.php");
    exit;
}

$id_akun = (int) $_SESSION['id_akun'];
$lama = mysqli_fetch_assoc(mysqli_query($connect, "SELECT * FROM akun WHERE id_akun = $id_akun"));

$cek = mysqli_query($connect, "SELECT id_pengajuan FROM pengajuan_perubahan WHERE id_akun = $id_akun AND status = 'pending'");
if (mysqli_num_rows($cek) > 0) {
    header("Location: ../../fo/biodata.php?error=masih_pending");
    exit;
}

$ambil = function ($kunci, $default) use ($connect) {
    $nilai = trim((string) ($_POST[$kunci] ?? ''));
    if ($nilai === '') {
        $nilai = (string) $default;
    }
    return mysqli_real_escape_string($connect, $nilai);
};

$nama_baru = $ambil('nama', $lama['nama']);
$telp_baru = $ambil('no_telp', $lama['no_telp']);
$alamat_baru = $ambil('alamat', $lama['alamat']);
$gender_raw = trim($_POST['gender'] ?? $lama['gender']);
$gender_baru = ($gender_raw === 'P' || strtolower($gender_raw) === 'perempuan') ? 'P' : 'L';
$hari_mulai = strtolower($ambil('hari_kerja_mulai', $lama['hari_kerja_mulai']));
$hari_selesai = strtolower($ambil('hari_kerja_selesai', $lama['hari_kerja_selesai']));
$jam_mulai_baru = $ambil('jam_mulai', $lama['jam_mulai']);
$jam_selesai_baru = $ambil('jam_selesai', $lama['jam_selesai']);

$l = array_map(function ($v) use ($connect) {
    return mysqli_real_escape_string($connect, (string) $v);
}, $lama);

mysqli_query($connect, "
    INSERT INTO pengajuan_perubahan (
        id_akun,
        nama_lama, nama_baru,
        no_telp_lama, no_telp_baru,
        alamat_lama, alamat_baru,
        gender_lama, gender_baru,
        jam_mulai_lama, jam_selesai_lama,
        jam_mulai_baru, jam_selesai_baru,
        hari_kerja_mulai, hari_kerja_selesai,
        status
    ) VALUES (
        $id_akun,
        '{$l['nama']}', '$nama_baru',
        '{$l['no_telp']}', '$telp_baru',
        '{$l['alamat']}', '$alamat_baru',
        '{$l['gender']}', '$gender_baru',
        '{$l['jam_mulai']}', '{$l['jam_selesai']}',
        '$jam_mulai_baru', '$jam_selesai_baru',
        '$hari_mulai', '$hari_selesai',
        'pending'
    )
");

header("Location: ../../fo/biodata.php?pesan=pengajuan_terkirim");
exit;
?>
