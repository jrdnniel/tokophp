<?php
session_start();
require_once __DIR__ . "/../../config/connection.php";

if (empty($_SESSION['id_user'])) {
    header("Location: ../../cust/login_cust.php");
    exit;
}

$id_user = (int) $_SESSION['id_user'];
$kategori = mysqli_real_escape_string($connect, trim($_POST['kategori'] ?? 'Pelayanan Toko'));
$id_transaksi = mysqli_real_escape_string($connect, trim($_POST['id_transaksi'] ?? ''));
$rating = max(1, min(5, (int) ($_POST['rating'] ?? 5)));
$jenis_raw = strtolower(trim($_POST['jenis'] ?? 'saran'));
$jenis = in_array($jenis_raw, ['saran', 'keluhan', 'pujian']) ? $jenis_raw : 'saran';
$pesan = mysqli_real_escape_string($connect, trim($_POST['pesan'] ?? ''));

if ($pesan === '') {
    echo "<script>alert('Pesan kritik atau saran wajib diisi!'); window.history.back();</script>";
    exit;
}

mysqli_query($connect, "
    INSERT INTO kritik_saran (id_user, id_transaksi, kategori, rating, jenis, pesan, status)
    VALUES ($id_user, '$id_transaksi', '$kategori', $rating, '$jenis', '$pesan', 'belum_dibalas')
");

echo "<script>alert('Terima kasih! Kritik dan saran Anda berhasil dikirim.'); window.location='../../cust/kritiksaran.php';</script>";
exit;
?>
