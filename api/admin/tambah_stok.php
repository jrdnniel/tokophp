<?php
session_start();
require_once __DIR__ . "/../../config/connection.php";

if (!isset($_SESSION['id_akun']) || ($_SESSION['role'] ?? '') !== 'admin') {
    header("Location: ../../admin/login_admin.php");
    exit;
}

$id_produk = (int) ($_POST['id_produk'] ?? 0);
$stok_tambah = (int) ($_POST['stok_tambah'] ?? 0);

if ($id_produk > 0 && $stok_tambah > 0) {
    mysqli_query($connect, "UPDATE produk SET stok = stok + $stok_tambah WHERE id_produk = $id_produk");
}

$ref = $_SERVER['HTTP_REFERER'] ?? '../../admin/monitoring_stock.php';
header("Location: $ref");
exit;
?>
