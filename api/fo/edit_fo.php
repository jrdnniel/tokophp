<?php
session_start();
require_once __DIR__ . '/../../config/connection.php';

if (!isset($_SESSION['id_akun']) || ($_SESSION['role'] ?? '') !== 'fo') {
    header('Location: ../../fo/login_fo.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../../fo/biodata.php');
    exit;
}

if (!isset($_FILES['foto_profile']) || $_FILES['foto_profile']['error'] !== UPLOAD_ERR_OK) {
    header('Location: ../../fo/biodata.php?error=file');
    exit;
}

$file = $_FILES['foto_profile'];
if ($file['size'] > 2 * 1024 * 1024) {
    header('Location: ../../fo/biodata.php?error=ukuran');
    exit;
}

$finfo = new finfo(FILEINFO_MIME_TYPE);
$mime = $finfo->file($file['tmp_name']);
$allowedTypes = [
    'image/jpeg' => 'jpg',
    'image/png'  => 'png',
    'image/webp' => 'webp'
];

if (!isset($allowedTypes[$mime])) {
    header('Location: ../../fo/biodata.php?error=format');
    exit;
}

$id_akun = (int) $_SESSION['id_akun'];
$folder = __DIR__ . '/../../asset/profile/';
if (!is_dir($folder)) {
    mkdir($folder, 0777, true);
}

$namaFile = 'fo_' . $id_akun . '_' . bin2hex(random_bytes(6)) . '.' . $allowedTypes[$mime];
$tujuan = $folder . $namaFile;

if (!move_uploaded_file($file['tmp_name'], $tujuan)) {
    header('Location: ../../fo/biodata.php?error=upload');
    exit;
}

$stmt = mysqli_prepare($connect, "UPDATE akun SET foto_profile = ? WHERE id_akun = ? AND role = 'fo'");
mysqli_stmt_bind_param($stmt, 'si', $namaFile, $id_akun);
mysqli_stmt_execute($stmt);
mysqli_stmt_close($stmt);

$_SESSION['foto_profile'] = $namaFile;
header('Location: ../../fo/biodata.php?pesan=foto_berhasil');
exit;
?>
