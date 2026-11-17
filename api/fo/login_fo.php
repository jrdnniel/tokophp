<?php
session_start();
require_once __DIR__ . "/../../config/connection.php";

$email = mysqli_real_escape_string($connect, trim($_POST['email'] ?? $_GET['email'] ?? ''));
$password = trim($_POST['password'] ?? $_GET['password'] ?? '');

if ($email === '' || $password === '') {
    echo "<script>alert('Email dan password wajib diisi!'); window.history.back();</script>";
    exit;
}

function verify_pw($input_pw, $db_pw) {
    $db_pw = (string) $db_pw;
    $info = password_get_info($db_pw);
    if ($info['algo'] !== null && $info['algo'] !== 0) {
        return password_verify($input_pw, $db_pw);
    }
    return hash_equals($db_pw, $input_pw);
}

// 1. Cek di tabel akun (Admin / Front Office)
$qAkun = mysqli_query($connect, "SELECT * FROM akun WHERE email = '$email' LIMIT 1");
$akun = $qAkun ? mysqli_fetch_assoc($qAkun) : null;

if ($akun) {
    if (!verify_pw($password, $akun['password'])) {
        echo "<script>alert('Password salah!'); window.history.back();</script>";
        exit;
    }
    session_regenerate_id(true);
    $_SESSION['id_akun'] = $akun['id_akun'];
    $_SESSION['nama'] = $akun['nama'];
    $_SESSION['email'] = $akun['email'];
    $_SESSION['role'] = $akun['role'];
    $_SESSION['foto_profile'] = $akun['foto_profile'] ?: 'default-profile.png';

    if ($akun['role'] === 'admin') {
        header("Location: ../../admin/dashboard.php");
        exit;
    } else {
        header("Location: ../../fo/dashboard.php");
        exit;
    }
}

// 2. Cek di tabel users (Customer)
$qUser = mysqli_query($connect, "SELECT * FROM users WHERE email = '$email' LIMIT 1");
$user = $qUser ? mysqli_fetch_assoc($qUser) : null;

if ($user) {
    if (!verify_pw($password, $user['password'])) {
        echo "<script>alert('Password salah!'); window.history.back();</script>";
        exit;
    }
    session_regenerate_id(true);
    $_SESSION['id_user'] = $user['id_user'];
    $_SESSION['nama_user'] = $user['nama'];
    $_SESSION['email_user'] = $user['email'];
    $_SESSION['foto_profile_user'] = $user['foto_profile'] ?: 'default-profile.png';

    header("Location: ../../cust/homepage.php");
    exit;
}

echo "<script>alert('Email tidak ditemukan! Pastikan database db_toko33.sql sudah di-import atau daftar akun baru.'); window.history.back();</script>";
exit;
?>
