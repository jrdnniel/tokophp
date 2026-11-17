<?php
session_start();
require_once __DIR__ . "/../../config/connection.php";

if (!isset($_SESSION['id_akun']) || ($_SESSION['role'] ?? '') !== 'admin') {
    header("Location: ../../admin/login_admin.php");
    exit;
}

$id_produk = (int) ($_GET['id'] ?? $_POST['id_produk'] ?? 0);
$data = mysqli_fetch_assoc(mysqli_query($connect, "SELECT foto_produk, kategori FROM produk WHERE id_produk = $id_produk"));

if (!$data) {
    echo "<script>alert('Produk tidak ditemukan!'); window.location='../../admin/stock_homecare.php';</script>";
    exit;
}

$kembali = ($data['kategori'] === 'minuman') ? 'stock_minuman.php' : 'stock_homecare.php';

$dipakai = mysqli_fetch_row(mysqli_query($connect, "SELECT COUNT(*) FROM detail_transaksi WHERE id_produk = $id_produk"))[0];
if ($dipakai > 0) {
    echo "<script>alert('Produk sudah tercatat di riwayat transaksi sehingga tidak bisa dihapus permanen. Silakan ubah stoknya menjadi 0.'); window.location='../../admin/$kembali';</script>";
    exit;
}

$hapus = mysqli_query($connect, "DELETE FROM produk WHERE id_produk = $id_produk");
if ($hapus) {
    echo "<script>alert('Produk berhasil dihapus!'); window.location='../../admin/$kembali';</script>";
} else {
    die(mysqli_error($connect));
}
exit;
?>
