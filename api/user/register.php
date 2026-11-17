<?php
require_once __DIR__ . "/../../config/connection.php";

$nama = mysqli_real_escape_string($connect, trim($_POST["nama"] ?? ""));
$no_telp = mysqli_real_escape_string($connect, trim($_POST["no_telp"] ?? $_POST["telepon"] ?? ""));
$email = mysqli_real_escape_string($connect, trim($_POST["email"] ?? ""));
$alamat = mysqli_real_escape_string($connect, trim($_POST["alamat"] ?? ""));
$tanggal_lahir = mysqli_real_escape_string($connect, trim($_POST["tanggal_lahir"] ?? ""));
$gender_raw = trim($_POST["gender"] ?? "L");
$gender = ($gender_raw === 'P' || strtolower($gender_raw) === 'perempuan') ? 'P' : 'L';
$password = $_POST["password"] ?? "";
$konfirmasi_password = $_POST["konfirmasi_password"] ?? "";

if ($nama === '' || $email === '' || $password === '') {
    echo "<script>alert('Nama, email, dan password wajib diisi!'); window.history.back();</script>";
    exit;
}

if ($password !== $konfirmasi_password) {
    echo "<script>alert('Konfirmasi password tidak sama!'); window.history.back();</script>";
    exit;
}

$cek = mysqli_query($connect, "SELECT id_user FROM users WHERE email='$email'");
if (mysqli_num_rows($cek) > 0) {
    echo "<script>alert('Email sudah digunakan!'); window.history.back();</script>";
    exit;
}

$password_hash = password_hash($password, PASSWORD_DEFAULT);
$tglSql = ($tanggal_lahir !== '') ? "'$tanggal_lahir'" : "NULL";

$query = mysqli_query($connect, "
    INSERT INTO users (nama, no_telp, email, alamat, tanggal_lahir, gender, password, foto_profile)
    VALUES ('$nama', '$no_telp', '$email', '$alamat', $tglSql, '$gender', '$password_hash', 'default-profile.png')
");

if ($query) {
    echo "<script>alert('Registrasi berhasil! Silakan login.'); window.location='../../cust/login_cust.php';</script>";
} else {
    echo "<script>alert('Registrasi gagal!'); window.history.back();</script>";
}
exit;
?>
