<?php
session_start();
require_once __DIR__ . "/../../config/connection.php";

if (empty($_SESSION['id_user'])) {
    header("Location: ../../cust/login_cust.php");
    exit;
}

$id_produk = (int) ($_GET['id'] ?? $_POST['id_produk'] ?? 0);
$qty_add = max(1, (int) ($_GET['qty'] ?? $_POST['qty'] ?? 1));

$data = mysqli_fetch_assoc(mysqli_query($connect, "SELECT * FROM produk WHERE id_produk = $id_produk"));
if (!$data || (int) $data['stok'] <= 0) {
    echo "<script>alert('Stok produk habis!'); window.history.back();</script>";
    exit;
}

if (!isset($_SESSION['cart_user'])) {
    $_SESSION['cart_user'] = [];
}

if (isset($_SESSION['cart_user'][$id_produk])) {
    $newQty = min((int) $data['stok'], $_SESSION['cart_user'][$id_produk]['qty'] + $qty_add);
    $_SESSION['cart_user'][$id_produk]['qty'] = $newQty;
} else {
    $_SESSION['cart_user'][$id_produk] = [
        'id_produk' => $id_produk,
        'nama' => $data['nama_produk'],
        'harga' => (float) $data['harga'],
        'qty' => min((int) $data['stok'], $qty_add),
        'kategori' => $data['kategori'],
        'foto_produk' => $data['foto_produk']
    ];
}

header("Location: ../../cust/detail_pesanan.php");
exit;
?>
