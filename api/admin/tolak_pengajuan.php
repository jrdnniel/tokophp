<?php
session_start();
require_once __DIR__ . "/../../config/connection.php";

if (!isset($_SESSION['id_akun']) || ($_SESSION['role'] ?? '') !== 'admin') {
    header("Location: ../../admin/login_admin.php");
    exit;
}

$id_pengajuan = (int) ($_GET['id_pengajuan'] ?? $_POST['id_pengajuan'] ?? 0);
if ($id_pengajuan > 0) {
    mysqli_query($connect, "UPDATE pengajuan_perubahan SET status = 'ditolak' WHERE id_pengajuan = $id_pengajuan");
}

header("Location: ../../admin/daftar_fo.php");
exit;
?>
