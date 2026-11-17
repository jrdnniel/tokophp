<?php
session_start();
require_once __DIR__ . "/../../config/connection.php";

if (!isset($_SESSION['id_akun']) || ($_SESSION['role'] ?? '') !== 'admin') {
    header("Location: ../../admin/login_admin.php");
    exit;
}

$id = (int) ($_GET["id"] ?? $_POST["id_user"] ?? 0);
if ($id < 1) {
    header("Location: ../../admin/daftar_pengguna.php");
    exit;
}

mysqli_begin_transaction($connect);
try {
    mysqli_query($connect, "DELETE dt FROM detail_transaksi dt JOIN transaksi t ON dt.id_transaksi=t.id_transaksi WHERE t.id_user='$id'");
    mysqli_query($connect, "DELETE FROM transaksi WHERE id_user='$id'");
    mysqli_query($connect, "DELETE FROM kritik_saran WHERE id_user='$id'");
    mysqli_query($connect, "DELETE FROM users WHERE id_user='$id'");
    mysqli_commit($connect);
    echo "<script>alert('Pengguna berhasil dihapus!'); window.location='../../admin/daftar_pengguna.php';</script>";
} catch (Throwable $e) {
    mysqli_rollback($connect);
    echo "<script>alert('Gagal menghapus pengguna!'); history.back();</script>";
}
exit;
?>
