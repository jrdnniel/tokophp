<?php
session_start();
require_once __DIR__ . "/../../config/connection.php";

if (!isset($_SESSION['id_akun']) || ($_SESSION['role'] ?? '') !== 'admin') {
    header("Location: ../../admin/login_admin.php");
    exit;
}

$nama = mysqli_real_escape_string($connect, trim($_POST['nama'] ?? ''));
$email = mysqli_real_escape_string($connect, trim($_POST['email'] ?? ''));
$password = $_POST['password'] ?? '';
$konfirmasi_password = $_POST['konfirmasi_password'] ?? $password;

$tanggal_lahir = mysqli_real_escape_string($connect, trim($_POST['tanggal_lahir'] ?? ''));
$no_telp = mysqli_real_escape_string($connect, trim($_POST['telepon'] ?? $_POST['no_telp'] ?? ''));
$gender_raw = trim($_POST['gender'] ?? 'L');
$gender = ($gender_raw === 'P' || strtolower($gender_raw) === 'perempuan') ? 'P' : 'L';
$alamat = mysqli_real_escape_string($connect, trim($_POST['alamat'] ?? ''));

$hari_kerja_mulai = mysqli_real_escape_string($connect, strtolower(trim($_POST['hari_kerja_mulai'] ?? $_POST['hari_awal'] ?? 'senin')));
$hari_kerja_selesai = mysqli_real_escape_string($connect, strtolower(trim($_POST['hari_kerja_selesai'] ?? $_POST['hari_akhir'] ?? 'jumat')));
$jam_mulai = mysqli_real_escape_string($connect, trim($_POST['jam_mulai'] ?? '08:00'));
$jam_selesai = mysqli_real_escape_string($connect, trim($_POST['jam_selesai'] ?? '16:00'));

if ($nama === '' || $email === '' || $password === '') {
    echo "<script>alert('Nama, email, dan password wajib diisi!'); window.history.back();</script>";
    exit;
}

if ($password !== $konfirmasi_password) {
    echo "<script>alert('Konfirmasi password tidak sama!'); window.history.back();</script>";
    exit;
}

$cek = mysqli_query($connect, "SELECT id_akun FROM akun WHERE email='$email'");
if (mysqli_num_rows($cek) > 0) {
    echo "<script>alert('Email sudah digunakan oleh akun lain!'); window.history.back();</script>";
    exit;
}

$foto = 'default-profile.png';
$fileKey = isset($_FILES['foto_profile']) ? 'foto_profile' : (isset($_FILES['foto']) ? 'foto' : null);
if ($fileKey && $_FILES[$fileKey]['error'] === UPLOAD_ERR_OK) {
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime = $finfo->file($_FILES[$fileKey]['tmp_name']);
    $izin = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp'];
    if (isset($izin[$mime])) {
        $folder = __DIR__ . "/../../asset/profile/";
        if (!is_dir($folder)) {
            mkdir($folder, 0777, true);
        }
        $foto = 'fo_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $izin[$mime];
        move_uploaded_file($_FILES[$fileKey]['tmp_name'], $folder . $foto);
    }
}

$passwordHash = password_hash($password, PASSWORD_DEFAULT);
$tglSql = ($tanggal_lahir !== '') ? "'$tanggal_lahir'" : "NULL";

$query = mysqli_query(
    $connect,
    "INSERT INTO akun (nama, email, password, role, tanggal_lahir, no_telp, gender, alamat, foto_profile, hari_kerja_mulai, hari_kerja_selesai, jam_mulai, jam_selesai)
     VALUES ('$nama', '$email', '$passwordHash', 'fo', $tglSql, '$no_telp', '$gender', '$alamat', '$foto', '$hari_kerja_mulai', '$hari_kerja_selesai', '$jam_mulai', '$jam_selesai')"
);

if ($query) {
    echo "<script>alert('Akun Front Office berhasil ditambahkan!'); window.location='../../admin/daftar_fo.php';</script>";
} else {
    die(mysqli_error($connect));
}
exit;
?>
