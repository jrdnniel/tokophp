<?php
session_start();
require_once __DIR__ . "/../../config/connection.php";

if (!isset($_SESSION['id_akun']) || ($_SESSION['role'] ?? '') !== 'admin') {
    header("Location: ../../admin/login_admin.php");
    exit;
}

$id_pengajuan = (int) ($_POST['id_pengajuan'] ?? $_GET['id_pengajuan'] ?? 0);
$aksi = $_POST['aksi'] ?? $_GET['aksi'] ?? '';
$setuju = $_POST['setuju'] ?? [];

$data = mysqli_fetch_assoc(mysqli_query($connect,
    "SELECT * FROM pengajuan_perubahan WHERE id_pengajuan = $id_pengajuan AND status = 'pending'"
));

if (!$data) {
    header("Location: ../../admin/daftar_fo.php");
    exit;
}

if ($aksi === 'tolak') {
    mysqli_query($connect, "UPDATE pengajuan_perubahan SET status = 'ditolak' WHERE id_pengajuan = $id_pengajuan");
    echo "<script>alert('Pengajuan perubahan data ditolak.'); window.location='../../admin/daftar_fo.php';</script>";
    exit;
}

$update = [];
$esc = function ($v) use ($connect) {
    return mysqli_real_escape_string($connect, (string) $v);
};

// Jika tidak ada checkbox spesifik yang dikirim tetapi aksi=setuju, setujui semua perubahan
if (empty($setuju)) {
    $setuju = ['nama', 'no_telp', 'alamat', 'gender', 'hari', 'jam'];
}

if (in_array('nama', $setuju) && !empty($data['nama_baru'])) {
    $update[] = "nama = '" . $esc($data['nama_baru']) . "'";
}
if (in_array('no_telp', $setuju) && !empty($data['no_telp_baru'])) {
    $update[] = "no_telp = '" . $esc($data['no_telp_baru']) . "'";
}
if (in_array('alamat', $setuju) && !empty($data['alamat_baru'])) {
    $update[] = "alamat = '" . $esc($data['alamat_baru']) . "'";
}
if (in_array('gender', $setuju) && !empty($data['gender_baru'])) {
    $update[] = "gender = '" . $esc($data['gender_baru']) . "'";
}
if (in_array('hari', $setuju) && !empty($data['hari_kerja_mulai'])) {
    $update[] = "hari_kerja_mulai = '" . $esc($data['hari_kerja_mulai']) . "'";
    $update[] = "hari_kerja_selesai = '" . $esc($data['hari_kerja_selesai']) . "'";
}
if (in_array('jam', $setuju) && !empty($data['jam_mulai_baru'])) {
    $update[] = "jam_mulai = '" . $esc($data['jam_mulai_baru']) . "'";
    $update[] = "jam_selesai = '" . $esc($data['jam_selesai_baru']) . "'";
}

if (count($update) > 0) {
    mysqli_query($connect, "UPDATE akun SET " . implode(", ", $update) . " WHERE id_akun = '{$data['id_akun']}'");
}

mysqli_query($connect, "UPDATE pengajuan_perubahan SET status = 'disetujui' WHERE id_pengajuan = $id_pengajuan");

echo "<script>alert('Pengajuan perubahan Front Office berhasil disetujui!'); window.location='../../admin/daftar_fo.php';</script>";
exit;
?>
