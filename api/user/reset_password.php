<?php
require_once __DIR__ . "/../../config/connection.php";

$email = mysqli_real_escape_string($connect, trim($_POST["email"] ?? ""));
$password_baru = $_POST["password_baru"] ?? "";
$konfirmasi_password = $_POST["konfirmasi_password"] ?? "";

if ($password_baru === '' || $password_baru !== $konfirmasi_password) {
    echo "<script>alert('Konfirmasi password tidak sama!'); window.history.back();</script>";
    exit;
}

$cek = mysqli_query($connect, "SELECT id_user FROM users WHERE email='$email'");
if (mysqli_num_rows($cek) === 0) {
    echo "<script>alert('Email tidak ditemukan!'); window.history.back();</script>";
    exit;
}

$password_hash = password_hash($password_baru, PASSWORD_DEFAULT);
$update = mysqli_query($connect, "UPDATE users SET password='$password_hash' WHERE email='$email'");

if ($update) {
    echo "<script>alert('Password berhasil diubah! Silakan login.'); window.location='../../cust/login_cust.php';</script>";
} else {
    echo "<script>alert('Password gagal diubah!'); window.history.back();</script>";
}
exit;
?>
