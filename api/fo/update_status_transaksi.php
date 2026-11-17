<?php
session_start();
require_once __DIR__ . "/../../config/connection.php";

if (!isset($_SESSION['id_akun']) || ($_SESSION['role'] ?? '') !== 'fo') {
    header("Location: ../../fo/login_fo.php");
    exit;
}

$id = (int) ($_POST['id_transaksi'] ?? $_GET['id'] ?? 0);
$status = $_POST['status'] ?? $_GET['status'] ?? '';
$id_fo = (int) $_SESSION['id_akun'];

if ($id < 1 || !in_array($status, ['diproses', 'selesai', 'ditolak'], true)) {
    die("Data tidak valid");
}

$data = mysqli_fetch_assoc(mysqli_query($connect, "SELECT status FROM transaksi WHERE id_transaksi = $id"));
if (!$data) {
    die("Transaksi tidak ditemukan");
}

$status_lama = $data['status'];

if ($status === 'diproses') {
    if ($status_lama === 'menunggu_diproses') {
        mysqli_query($connect, "
            UPDATE transaksi
            SET status = 'diproses', tanggal_diproses = NOW(), id_fo = IFNULL(id_fo, $id_fo)
            WHERE id_transaksi = $id
        ");
    }
    header("Location: ../../fo/status_pesanan.php");
    exit;
}

if ($status === 'selesai') {
    if ($status_lama === 'diproses') {
        mysqli_query($connect, "
            UPDATE transaksi
            SET status = 'selesai', tanggal_selesai = NOW()
            WHERE id_transaksi = $id
        ");
    }
    header("Location: ../../fo/riwayat_transaksi.php");
    exit;
}

if ($status === 'ditolak') {
    if ($status_lama === 'menunggu_diproses') {
        mysqli_query($connect, "
            UPDATE transaksi
            SET status = 'ditolak', tanggal_ditolak = NOW(), id_fo = IFNULL(id_fo, $id_fo)
            WHERE id_transaksi = $id
        ");
        mysqli_query($connect, "
            UPDATE produk p
            JOIN detail_transaksi d ON d.id_produk = p.id_produk
            SET p.stok = p.stok + d.jumlah
            WHERE d.id_transaksi = $id
        ");
    }
    header("Location: ../../fo/riwayat_transaksi.php");
    exit;
}
?>
