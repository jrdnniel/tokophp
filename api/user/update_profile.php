<?php
session_start();
require_once __DIR__ . '/../../config/connection.php';

function kembaliKeProfil($status) {
    header('Location: ../../cust/profile.php?status=' . urlencode($status));
    exit;
}

if (empty($_SESSION['id_user'])) {
    header('Location: ../../cust/login_cust.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    kembaliKeProfil('gagal');
}

$id_user = (int) $_SESSION['id_user'];
$nama = trim($_POST['nama'] ?? '');
$no_telp = trim($_POST['no_telp'] ?? $_POST['telepon'] ?? '');
$email = trim($_POST['email'] ?? '');
$alamat = trim($_POST['alamat'] ?? '');
$tanggal_lahir = trim($_POST['tanggal_lahir'] ?? '');
$password_baru = $_POST['password'] ?? '';

if ($nama === '' || $email === '') {
    kembaliKeProfil('gagal');
}

$stmt = mysqli_prepare($connect, 'SELECT id_user FROM users WHERE email = ? AND id_user != ? LIMIT 1');
mysqli_stmt_bind_param($stmt, 'si', $email, $id_user);
mysqli_stmt_execute($stmt);
$res = mysqli_stmt_get_result($stmt);
$emailAda = mysqli_num_rows($res) > 0;
mysqli_stmt_close($stmt);

if ($emailAda) {
    kembaliKeProfil('email');
}

$stmt = mysqli_prepare($connect, 'UPDATE users SET nama = ?, no_telp = ?, email = ?, alamat = ?, tanggal_lahir = ? WHERE id_user = ?');
mysqli_stmt_bind_param($stmt, 'sssssi', $nama, $no_telp, $email, $alamat, $tanggal_lahir, $id_user);
mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);

$_SESSION['nama_user'] = $nama;
$_SESSION['email_user'] = $email;

if ($password_baru !== '') {
    $passwordHash = password_hash($password_baru, PASSWORD_DEFAULT);
    $stmt = mysqli_prepare($connect, 'UPDATE users SET password = ? WHERE id_user = ?');
    mysqli_stmt_bind_param($stmt, 'si', $passwordHash, $id_user);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}

if (isset($_FILES['foto_profile']) && $_FILES['foto_profile']['error'] === UPLOAD_ERR_OK) {
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime = $finfo->file($_FILES['foto_profile']['tmp_name']);
    $izin = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp'];
    if (!isset($izin[$mime])) {
        kembaliKeProfil('format');
    }
    if ($_FILES['foto_profile']['size'] > 2 * 1024 * 1024) {
        kembaliKeProfil('ukuran');
    }
    $folderFoto = __DIR__ . '/../../asset/profile/';
    if (!is_dir($folderFoto)) {
        mkdir($folderFoto, 0777, true);
    }
    $namaFile = 'user_' . $id_user . '_' . bin2hex(random_bytes(6)) . '.' . $izin[$mime];
    if (move_uploaded_file($_FILES['foto_profile']['tmp_name'], $folderFoto . $namaFile)) {
        $stmt = mysqli_prepare($connect, 'UPDATE users SET foto_profile = ? WHERE id_user = ?');
        mysqli_stmt_bind_param($stmt, 'si', $namaFile, $id_user);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        $_SESSION['foto_profile_user'] = $namaFile;
    }
}

kembaliKeProfil('berhasil');
?>
