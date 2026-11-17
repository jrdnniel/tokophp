<?php
session_start();
require_once __DIR__ . "/../../config/connection.php";

if (!isset($_SESSION['id_akun']) || ($_SESSION['role'] ?? '') !== 'admin') {
    header("Location: ../../admin/login_admin.php");
    exit;
}

$kategori_input = strtolower(trim($_POST['kategori'] ?? 'homecare'));
$kategori = ($kategori_input === 'minuman') ? 'minuman' : 'homecare';
$nama_produk = mysqli_real_escape_string($connect, trim($_POST['nama_produk'] ?? ''));
$kode_produk = mysqli_real_escape_string($connect, trim($_POST['kode_produk'] ?? ''));
$deskripsi = mysqli_real_escape_string($connect, trim($_POST['deskripsi'] ?? ''));
$stok = max(0, (int) ($_POST['stok'] ?? 0));
$stok_minimum = max(0, (int) ($_POST['stok_minimum'] ?? 10));
$tanggal_exp = trim($_POST['tanggal_exp'] ?? '');
$lacak_exp = isset($_POST['lacak_exp']) ? (int) $_POST['lacak_exp'] : 1;

$harga_raw = preg_replace('/[,.]\d{2}$/', '', trim($_POST['harga'] ?? ''));
$harga = (int) preg_replace('/\D/', '', $harga_raw);

if ($nama_produk === '' || $harga <= 0) {
    echo "<script>alert('Nama produk dan harga wajib diisi dengan benar!'); window.history.back();</script>";
    exit;
}

if ($kode_produk === '') {
    $prefix = ($kategori === 'minuman') ? 'MNM-' : 'HC-';
    $qMax = mysqli_query($connect, "SELECT COUNT(*) AS total FROM produk WHERE kategori='$kategori'");
    $rMax = mysqli_fetch_assoc($qMax);
    $nextNum = ((int)($rMax['total'] ?? 0)) + 1;
    $kode_produk = $prefix . str_pad((string)$nextNum, 3, '0', STR_PAD_LEFT);
}

$namaBaru = '';
if (isset($_FILES['foto_produk']) && $_FILES['foto_produk']['error'] === UPLOAD_ERR_OK) {
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime = $finfo->file($_FILES['foto_produk']['tmp_name']);
    $izin = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp'];

    if (!isset($izin[$mime])) {
        echo "<script>alert('Foto produk harus berformat JPG, PNG, atau WEBP!'); window.history.back();</script>";
        exit;
    }

    $folder = __DIR__ . "/../../asset/produk/";
    if (!is_dir($folder)) {
        mkdir($folder, 0777, true);
    }

    $cleanName = preg_replace('/[^a-zA-Z0-9._-]/', '_', basename($_FILES['foto_produk']['name']));
    $namaBaru = time() . "_" . $cleanName;
    move_uploaded_file($_FILES['foto_produk']['tmp_name'], $folder . $namaBaru);
}

$namaBaruEsc = mysqli_real_escape_string($connect, $namaBaru);
$expSql = ($tanggal_exp !== '') ? "'" . mysqli_real_escape_string($connect, $tanggal_exp) . "'" : "NULL";

$query = mysqli_query(
    $connect,
    "INSERT INTO produk (kode_produk, nama_produk, kategori, harga, stok, stok_minimum, tanggal_exp, lacak_exp, deskripsi, foto_produk)
     VALUES ('$kode_produk', '$nama_produk', '$kategori', '$harga', $stok, $stok_minimum, $expSql, $lacak_exp, '$deskripsi', '$namaBaruEsc')"
);

$kembali = ($kategori === 'minuman') ? 'stock_minuman.php' : 'stock_homecare.php';

if ($query) {
    echo "<script>alert('Produk berhasil ditambahkan!'); window.location='../../admin/$kembali';</script>";
} else {
    echo "<script>alert('Gagal menambahkan produk: " . addslashes(mysqli_error($connect)) . "'); window.history.back();</script>";
}
exit;
?>
