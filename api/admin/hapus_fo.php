<?php
session_start();
require_once __DIR__ . '/../../config/connection.php';

if (!isset($_SESSION['id_akun']) || ($_SESSION['role'] ?? '') !== 'admin') {
    header('Location: ../../admin/login_admin.php');
    exit;
}

$id_fo = (int) ($_GET['id'] ?? $_POST['id_akun'] ?? 0);
if ($id_fo < 1) {
    header('Location: ../../admin/daftar_fo.php?status=gagal');
    exit;
}

$stmt = mysqli_prepare($connect, "DELETE FROM akun WHERE id_akun = ? AND role = 'fo'");
mysqli_stmt_bind_param($stmt, 'i', $id_fo);
mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);

echo "<script>alert('Akun Front Office berhasil dihapus!'); window.location='../../admin/daftar_fo.php';</script>";
exit;
?>
