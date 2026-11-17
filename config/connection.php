<?php
date_default_timezone_set('Asia/Jakarta');

$host = "localhost";
$username = "root";
$password = "";
$database = "db_toko33";

mysqli_report(MYSQLI_REPORT_OFF);

// Coba koneksi port default XAMPP (3306), fallback otomatis ke 3307 bila diperlukan
$connect = @mysqli_connect($host, $username, $password, $database, 3306);
if (!$connect) {
    $connect = @mysqli_connect($host, $username, $password, $database, 3307);
}
if (!$connect) {
    $connect = @mysqli_connect("127.0.0.1", $username, $password, $database, 3306);
}
if (!$connect) {
    die("Koneksi database gagal: " . mysqli_connect_error() . ". Pastikan MySQL di XAMPP aktif dan database 'db_toko33' sudah di-import di phpMyAdmin.");
}

mysqli_set_charset($connect, "utf8mb4");
?>
