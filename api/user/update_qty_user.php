<?php
session_start();
require_once __DIR__ . "/../../config/connection.php";

$id = (int) ($_GET['id'] ?? 0);
$aksi = $_GET['aksi'] ?? '';

if (isset($_SESSION['cart_user'][$id])) {
    if ($aksi === "tambah") {
        $data = mysqli_fetch_assoc(mysqli_query($connect, "SELECT stok FROM produk WHERE id_produk = $id"));
        if ($_SESSION['cart_user'][$id]['qty'] < (int) ($data['stok'] ?? 0)) {
            $_SESSION['cart_user'][$id]['qty']++;
        }
    } elseif ($aksi === 'kurang') {
        $_SESSION['cart_user'][$id]['qty']--;
        if ($_SESSION['cart_user'][$id]['qty'] <= 0) {
            unset($_SESSION['cart_user'][$id]);
        }
    }
}

header("Location: ../../cust/detail_pesanan.php");
exit;
?>
