<?php
session_start();
require_once __DIR__ . "/../../config/connection.php";

$id_produk = (int) ($_GET['id'] ?? $_POST['id_produk'] ?? 0);
$qty_add = max(1, (int) ($_GET['qty'] ?? $_POST['qty'] ?? 1));

$data = mysqli_fetch_assoc(mysqli_query($connect, "SELECT * FROM produk WHERE id_produk = $id_produk"));
if (!$data || (int) $data['stok'] <= 0) {
    echo "<script>alert('Stok produk sedang habis!'); window.history.back();</script>";
    exit;
}

if (!isset($_SESSION['cart_fo'])) {
    $_SESSION['cart_fo'] = [];
}

if (isset($_SESSION['cart_fo'][$id_produk])) {
    $newQty = min((int) $data['stok'], $_SESSION['cart_fo'][$id_produk]['qty'] + $qty_add);
    $_SESSION['cart_fo'][$id_produk]['qty'] = $newQty;
} else {
    $_SESSION['cart_fo'][$id_produk] = [
        'id_produk' => $id_produk,
        'nama' => $data['nama_produk'],
        'harga' => (float) $data['harga'],
        'qty' => min((int) $data['stok'], $qty_add),
        'kategori' => $data['kategori'],
        'foto_produk' => $data['foto_produk'],
    ];
}

header("Location: ../../fo/detail_pembelian.php");
exit;
?>
