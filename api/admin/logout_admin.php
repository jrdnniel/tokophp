<?php
session_start();
$_SESSION = [];
session_destroy();
echo "<script>alert('Logout berhasil!'); window.location.href = '../../admin/login_admin.php';</script>";
exit;
?>
