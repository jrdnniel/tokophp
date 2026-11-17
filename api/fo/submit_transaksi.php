<?php
session_start();
require_once __DIR__ . "/../../config/connection.php";

if (!isset($_SESSION['id_akun']) || ($_SESSION['role'] ?? '') !== 'fo') {
    header("Location: ../../fo/login_fo.php");
    exit;
}

$id = (int) ($_POST['id_transaksi'] ?? $_GET['id'] ?? 0);
$metode = strtolower(trim($_POST['metode'] ?? ''));

if ($id < 1 || ($metode !== 'cash' && $metode !== 'transfer')) {
    die("Parameter tidak lengkap");
}

$t = mysqli_fetch_assoc(mysqli_query($connect, "SELECT * FROM transaksi WHERE id_transaksi = $id"));
if (!$t) {
    die("Transaksi tidak ditemukan");
}

if ($t['status'] !== 'menunggu_pembayaran') {
    header("Location: ../../fo/pesanan_masuk.php");
    exit;
}

if ($metode === "cash") {
    mysqli_query($connect, "
        UPDATE transaksi
        SET metode_pembayaran = 'Cash',
            status = 'menunggu_diproses',
            expired_at = NULL
        WHERE id_transaksi = $id
    ");
} else {
    if (!isset($_FILES['bukti_transfer']) || $_FILES['bukti_transfer']['error'] !== UPLOAD_ERR_OK) {
        header("Location: ../../fo/pembayaran.php?id=$id&error=file");
        exit;
    }

    $file = $_FILES['bukti_transfer'];
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime = $finfo->file($file['tmp_name']);
    $izin = [
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
        'image/webp' => 'webp',
        'application/pdf' => 'pdf'
    ];

    if (!isset($izin[$mime]) || $file['size'] > 3 * 1024 * 1024) {
        header("Location: ../../fo/pembayaran.php?id=$id&error=format");
        exit;
    }

    $folder = __DIR__ . "/../../asset/bukti_transfer/";
    if (!is_dir($folder)) {
        mkdir($folder, 0777, true);
    }

    $namaFile = "bukti_" . $id . "_" . time() . "." . $izin[$mime];
    move_uploaded_file($file['tmp_name'], $folder . $namaFile);

    mysqli_query($connect, "
        UPDATE transaksi
        SET metode_pembayaran = 'Transfer',
            bukti_transfer = '$namaFile',
            status = 'menunggu_diproses',
            expired_at = NULL
        WHERE id_transaksi = $id
    ");
}

header("Location: ../../fo/pesanan_masuk.php");
exit;
?>
