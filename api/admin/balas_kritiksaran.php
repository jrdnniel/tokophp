<?php
session_start();
require_once __DIR__ . "/../../config/connection.php";

if (!isset($_SESSION['id_akun']) || ($_SESSION['role'] ?? '') !== 'admin') {
    header("Location: ../../admin/login_admin.php");
    exit;
}

$id_kritik = (int) ($_POST['id_kritik'] ?? 0);
$balasan = mysqli_real_escape_string($connect, trim($_POST['balasan_admin'] ?? ''));

if ($id_kritik > 0 && $balasan !== '') {
    mysqli_query($connect, "UPDATE kritik_saran SET balasan_admin = '$balasan', status = 'dibalas', tanggal_balas = NOW() WHERE id_kritik = $id_kritik");
    echo "<script>alert('Balasan berhasil dikirim!'); window.location='../../admin/kritiksaran.php';</script>";
    exit;
}

header("Location: ../../admin/kritiksaran.php");
exit;
?>
