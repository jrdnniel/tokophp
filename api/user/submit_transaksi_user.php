<?php
session_start();
require_once __DIR__ . "/../../config/connection.php";

if (empty($_SESSION['id_user'])) {
    header("Location: ../../cust/login_cust.php");
    exit;
}

$id = (int) ($_GET["id"] ?? $_POST["id"] ?? $_POST["id_transaksi"] ?? 0);
$id_user = (int) $_SESSION['id_user'];

if ($id < 1) {
    die("Parameter tidak lengkap");
}

$t = mysqli_fetch_assoc(mysqli_query($connect, "SELECT * FROM transaksi WHERE id_transaksi = $id AND id_user = $id_user"));
if (!$t) {
    die("Transaksi tidak ditemukan");
}

if ($t['status'] !== 'menunggu_pembayaran') {
    header("Location: ../../cust/riwayat_transaksi.php");
    exit;
}

if (!isset($_FILES['bukti_transfer']) || $_FILES['bukti_transfer']['error'] !== UPLOAD_ERR_OK) {
    header("Location: ../../cust/pembayaran.php?id=$id&status=gagal");
    exit;
}

$file = $_FILES['bukti_transfer'];
if ($file['size'] > 3 * 1024 * 1024) {
    header("Location: ../../cust/pembayaran.php?id=$id&status=ukuran");
    exit;
}

$finfo = new finfo(FILEINFO_MIME_TYPE);
$mime = $finfo->file($file['tmp_name']);
$izin = [
    'image/jpeg' => 'jpg',
    'image/png' => 'png',
    'image/webp' => 'webp',
    'application/pdf' => 'pdf'
];

if (!isset($izin[$mime])) {
    header("Location: ../../cust/pembayaran.php?id=$id&status=format");
    exit;
}

$folder = __DIR__ . "/../../asset/bukti_transfer/";
if (!is_dir($folder)) {
    mkdir($folder, 0777, true);
}

$namaFile = "bukti_" . $id . "_" . time() . "." . $izin[$mime];
move_uploaded_file($file['tmp_name'], $folder . $namaFile);

mysqli_query($connect, "
    UPDATE transaksi
    SET metode_pembayaran = 'Transfer',
        bukti_transfer = '$namaFile',
        status = 'menunggu_diproses',
        expired_at = NULL
    WHERE id_transaksi = $id
");

echo "<script>alert('Bukti pembayaran berhasil diupload! Pesanan Anda sedang diverifikasi.'); window.location='../../cust/riwayat_transaksi.php';</script>";
exit;
?>
