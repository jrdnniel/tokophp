<?php
session_start();
require_once __DIR__ . '/../../config/connection.php';

if (empty($_SESSION['id_user'])) {
    header('Location: ../../cust/login_cust.php');
    exit;
}

$cart = $_SESSION['cart_user'] ?? [];
if (empty($cart) || !is_array($cart)) {
    header('Location: ../../cust/detail_pesanan.php?status=keranjang_kosong');
    exit;
}

$id_user = (int) $_SESSION['id_user'];
$nama = $_SESSION['nama_user'] ?? 'Customer';

try {
    mysqli_begin_transaction($connect);
    $total = 0;
    $items = [];

    foreach ($cart as $id_produk => $item) {
        $id_produk = (int) $id_produk;
        $qty = (int) ($item['qty'] ?? 0);
        if ($id_produk < 1 || $qty < 1) {
            throw new Exception('Data keranjang tidak valid.');
        }

        $stmt = mysqli_prepare($connect, 'SELECT harga, stok FROM produk WHERE id_produk = ? FOR UPDATE');
        mysqli_stmt_bind_param($stmt, 'i', $id_produk);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $produk = mysqli_fetch_assoc($result);
        mysqli_stmt_close($stmt);

        if (!$produk) {
            throw new Exception("Produk tidak ditemukan.");
        }
        if ((int) $produk['stok'] < $qty) {
            throw new Exception("Stok produk tidak mencukupi.");
        }

        $harga = (float) $produk['harga'];
        $subtotal = $harga * $qty;
        $total += $subtotal;

        $items[] = [
            'id_produk' => $id_produk,
            'qty' => $qty,
            'harga' => $harga,
            'subtotal' => $subtotal
        ];
    }

    $status = 'menunggu_pembayaran';
    $stmt = mysqli_prepare(
        $connect,
        'INSERT INTO transaksi (id_user, nama_customer, tanggal_masuk, expired_at, status, total_pembayaran)
         VALUES (?, ?, NOW(), DATE_ADD(NOW(), INTERVAL 15 MINUTE), ?, ?)'
    );
    mysqli_stmt_bind_param($stmt, 'issd', $id_user, $nama, $status, $total);
    mysqli_stmt_execute($stmt);
    $id_transaksi = mysqli_insert_id($connect);
    mysqli_stmt_close($stmt);

    foreach ($items as $item) {
        $stmt = mysqli_prepare($connect, 'INSERT INTO detail_transaksi (id_transaksi, id_produk, jumlah, harga_satuan, subtotal) VALUES (?, ?, ?, ?, ?)');
        mysqli_stmt_bind_param($stmt, 'iiidd', $id_transaksi, $item['id_produk'], $item['qty'], $item['harga'], $item['subtotal']);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        $stmt = mysqli_prepare($connect, 'UPDATE produk SET stok = stok - ? WHERE id_produk = ? AND stok >= ?');
        mysqli_stmt_bind_param($stmt, 'iii', $item['qty'], $item['id_produk'], $item['qty']);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }

    mysqli_commit($connect);
    unset($_SESSION['cart_user']);
    header('Location: ../../cust/pembayaran.php?id=' . $id_transaksi);
    exit;
} catch (Throwable $e) {
    mysqli_rollback($connect);
    $pesan = htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8');
    echo "<script>alert('Checkout gagal: $pesan'); window.location='../../cust/detail_pesanan.php';</script>";
    exit;
}
?>
