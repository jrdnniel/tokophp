<?php
session_start();
require_once __DIR__ . "/../../config/connection.php";

if (!isset($_SESSION['id_akun']) || ($_SESSION['role'] ?? '') !== 'admin') {
    header("Location: ../../admin/login_admin.php");
    exit;
}

$id_produk = (int) ($_POST['id_produk'] ?? 0);
$nama_produk = mysqli_real_escape_string($connect, trim($_POST['nama_produk'] ?? ''));
$kode_produk = mysqli_real_escape_string($connect, trim($_POST['kode_produk'] ?? ''));
$kategori_input = strtolower(trim($_POST['kategori'] ?? ''));
$deskripsi = mysqli_real_escape_string($connect, trim($_POST['deskripsi'] ?? ''));
$stok = max(0, (int) ($_POST['stok'] ?? 0));
$stok_minimum = max(0, (int) ($_POST['stok_minimum'] ?? 10));
$tanggal_exp = trim($_POST['tanggal_exp'] ?? '');
$lacak_exp = isset($_POST['lacak_exp']) ? (int) $_POST['lacak_exp'] : 0;

$harga_raw = preg_replace('/[,.]\d{2}$/', '', trim($_POST['harga'] ?? ''));
$harga = (int) preg_replace('/\D/', '', $harga_raw);

$lama = mysqli_fetch_assoc(mysqli_query($connect, "SELECT * FROM produk WHERE id_produk = $id_produk"));
if (!$lama) {
    echo "<script>alert('Produk tidak ditemukan!'); window.location='../../admin/stock_homecare.php';</script>";
    exit;
}

$kategori = ($kategori_input === 'minuman' || $kategori_input === 'homecare') ? $kategori_input : $lama['kategori'];
if ($kode_produk === '') {
    $kode_produk = mysqli_real_escape_string($connect, (string) $lama['kode_produk']);
}

$foto_produk = $lama['foto_produk'];

if (isset($_FILES['foto_produk']) && $_FILES['foto_produk']['error'] === UPLOAD_ERR_OK) {
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime = $finfo->file($_FILES['foto_produk']['tmp_name']);
    if (in_array($mime, ['image/jpeg', 'image/png', 'image/webp'])) {
        $folder = __DIR__ . "/../../asset/produk/";
        if (!is_dir($folder)) {
            mkdir($folder, 0777, true);
        }
        $cleanName = preg_replace('/[^a-zA-Z0-9._-]/', '_', basename($_FILES['foto_produk']['name']));
        $foto_produk = time() . "_" . $cleanName;
        move_uploaded_file($_FILES['foto_produk']['tmp_name'], $folder . $foto_produk);
    }
}

$foto_produk_esc = mysqli_real_escape_string($connect, (string) $foto_produk);
$expSql = ($tanggal_exp !== '') ? "'" . mysqli_real_escape_string($connect, $tanggal_exp) . "'" : "NULL";

$query = mysqli_query(
    $connect,
    "UPDATE produk SET
        kode_produk = '$kode_produk',
        nama_produk = '$nama_produk',
        kategori = '$kategori',
        harga = '$harga',
        stok = $stok,
        stok_minimum = $stok_minimum,
        tanggal_exp = $expSql,
        lacak_exp = $lacak_exp,
        deskripsi = '$deskripsi',
        foto_produk = '$foto_produk_esc'
     WHERE id_produk = $id_produk"
);

$kembali = ($kategori === 'minuman') ? 'stock_minuman.php' : 'stock_homecare.php';

if ($query) {
    echo "<script>alert('Produk berhasil diperbarui!'); window.location='../../admin/$kembali';</script>";
} else {
    die(mysqli_error($connect));
}
exit;
?>
