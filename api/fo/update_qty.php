<?php
session_start();
require_once __DIR__ . "/../../config/connection.php";

$id = (int) ($_GET['id'] ?? 0);
$aksi = $_GET['aksi'] ?? '';

if (isset($_SESSION['cart_fo'][$id])) {
    if ($aksi === "tambah") {
        $data = mysqli_fetch_assoc(mysqli_query($connect, "SELECT stok FROM produk WHERE id_produk = $id"));
        $stok = (int) ($data['stok'] ?? 0);
        if ($_SESSION['cart_fo'][$id]['qty'] < $stok) {
            $_SESSION['cart_fo'][$id]['qty']++;
        }
    } elseif ($aksi === "kurang") {
        $_SESSION['cart_fo'][$id]['qty']--;
        if ($_SESSION['cart_fo'][$id]['qty'] <= 0) {
            unset($_SESSION['cart_fo'][$id]);
        }
    }
}

header("Location: ../../fo/detail_pembelian.php");
exit;
?>
