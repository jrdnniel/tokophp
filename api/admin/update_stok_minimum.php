<?php
session_start();
require_once __DIR__ . "/../../config/connection.php";

if (!isset($_SESSION['id_akun']) || ($_SESSION['role'] ?? '') !== 'admin') {
    header("Location: ../../admin/login_admin.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['stok_minimum']) && is_array($_POST['stok_minimum'])) {
        foreach ($_POST['stok_minimum'] as $id_produk => $min_val) {
            $id = (int) $id_produk;
            $min = max(0, (int) $min_val);
            if ($id > 0) {
                mysqli_query($connect, "UPDATE produk SET stok_minimum = $min WHERE id_produk = $id");
            }
        }
    } elseif (isset($_POST['id_produk'])) {
        $id = (int) $_POST['id_produk'];
        $min = max(0, (int) ($_POST['stok_minimum_val'] ?? $_POST['stok_minimum'] ?? 10));
        mysqli_query($connect, "UPDATE produk SET stok_minimum = $min WHERE id_produk = $id");
    }
    echo "<script>alert('Batas stok minimum berhasil diperbarui!'); window.location='../../admin/monitoring_stock.php';</script>";
    exit;
}

header("Location: ../../admin/monitoring_stock.php");
exit;
?>
