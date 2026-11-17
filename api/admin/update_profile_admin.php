<?php
session_start();
require_once __DIR__ . "/../../config/connection.php";

if (!isset($_SESSION['id_akun']) || ($_SESSION['role'] ?? '') !== 'admin') {
    header("Location: ../../admin/login_admin.php");
    exit;
}

$id_akun = (int) $_SESSION['id_akun'];
$dataOld = mysqli_fetch_assoc(mysqli_query($connect, "SELECT * FROM akun WHERE id_akun = $id_akun AND role = 'admin'"));
if (!$dataOld) {
    header("Location: ../../admin/login_admin.php");
    exit;
}

// Jika hanya upload foto cepat dari halaman profile.php atau header
$foto = $dataOld['foto_profile'] ?: 'default-profile.png';
if (isset($_FILES['foto_profile']) && $_FILES['foto_profile']['error'] === UPLOAD_ERR_OK) {
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime = $finfo->file($_FILES['foto_profile']['tmp_name']);
    $izin = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp'];
    if (!isset($izin[$mime])) {
        header("Location: ../../admin/profile.php?error=format");
        exit;
    }
    if ($_FILES['foto_profile']['size'] > 2 * 1024 * 1024) {
        header("Location: ../../admin/profile.php?error=ukuran");
        exit;
    }
    $folder = __DIR__ . "/../../asset/profile/";
    if (!is_dir($folder)) {
        mkdir($folder, 0777, true);
    }
    $namaFile = 'admin_' . $id_akun . '_' . bin2hex(random_bytes(6)) . '.' . $izin[$mime];
    if (move_uploaded_file($_FILES['foto_profile']['tmp_name'], $folder . $namaFile)) {
        $foto = $namaFile;
        $_SESSION['foto_profile'] = $foto;
    }
}

// Cek apakah form mengirim pembaruan data biodata lengkap
if (isset($_POST['nama']) && trim($_POST['nama']) !== '') {
    $esc = function ($k, $def = '') use ($connect) {
        $val = trim((string) ($_POST[$k] ?? $def));
        return mysqli_real_escape_string($connect, $val);
    };

    $nama = $esc('nama', $dataOld['nama']);
    $email = $esc('email', $dataOld['email']);
    $no_telp = $esc('no_telp', $dataOld['no_telp']);
    $tanggal_lahir = $esc('tanggal_lahir', $dataOld['tanggal_lahir']);
    $gender_raw = trim($_POST['gender'] ?? $dataOld['gender']);
    $gender = ($gender_raw === 'P' || strtolower($gender_raw) === 'perempuan') ? 'P' : 'L';
    $hari_mulai = strtolower($esc('hari_kerja_mulai', $dataOld['hari_kerja_mulai'] ?: 'senin'));
    $hari_selesai = strtolower($esc('hari_kerja_selesai', $dataOld['hari_kerja_selesai'] ?: 'sabtu'));
    $jam_mulai = $esc('jam_mulai', $dataOld['jam_mulai'] ?: '08:00');
    $jam_selesai = $esc('jam_selesai', $dataOld['jam_selesai'] ?: '17:00');
    $alamat = $esc('alamat', $dataOld['alamat']);
    $password_baru = $_POST['password'] ?? '';

    // Cek duplikasi email
    $cekEmail = mysqli_query($connect, "SELECT id_akun FROM akun WHERE email = '$email' AND id_akun != $id_akun");
    if ($cekEmail && mysqli_num_rows($cekEmail) > 0) {
        echo "<script>alert('Email sudah digunakan oleh akun lain!'); window.history.back();</script>";
        exit;
    }

    $setPassword = "";
    if ($password_baru !== '') {
        $pwHash = password_hash($password_baru, PASSWORD_DEFAULT);
        $setPassword = ", password = '" . mysqli_real_escape_string($connect, $pwHash) . "'";
    }

    $tglSql = ($tanggal_lahir !== '') ? "'$tanggal_lahir'" : "NULL";
    $fotoEsc = mysqli_real_escape_string($connect, $foto);

    mysqli_query($connect, "
        UPDATE akun SET
            nama = '$nama',
            email = '$email',
            no_telp = '$no_telp',
            tanggal_lahir = $tglSql,
            gender = '$gender',
            hari_kerja_mulai = '$hari_mulai',
            hari_kerja_selesai = '$hari_selesai',
            jam_mulai = '$jam_mulai',
            jam_selesai = '$jam_selesai',
            alamat = '$alamat',
            foto_profile = '$fotoEsc'
            $setPassword
        WHERE id_akun = $id_akun AND role = 'admin'
    ");

    $_SESSION['nama'] = stripslashes($nama);
    $_SESSION['email'] = stripslashes($email);
    $_SESSION['foto_profile'] = $foto;

    header("Location: ../../admin/profile.php?pesan=profile_berhasil");
    exit;
} else {
    $fotoEsc = mysqli_real_escape_string($connect, $foto);
    mysqli_query($connect, "UPDATE akun SET foto_profile = '$fotoEsc' WHERE id_akun = $id_akun AND role = 'admin'");
    header("Location: ../../admin/profile.php?pesan=foto_berhasil");
    exit;
}
?>
