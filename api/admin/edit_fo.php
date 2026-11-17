<?php
session_start();
require_once __DIR__ . "/../../config/connection.php";

if (!isset($_SESSION['id_akun']) || ($_SESSION['role'] ?? '') !== 'admin') {
    header("Location: ../../admin/login_admin.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $esc = function ($kunci) use ($connect) {
        return mysqli_real_escape_string($connect, trim((string) ($_POST[$kunci] ?? '')));
    };

    $id_akun = (int) ($_POST['id_akun'] ?? 0);
    $nama = $esc('nama');
    $tanggal_lahir = $esc('tanggal_lahir');
    $gender_raw = trim($_POST['gender'] ?? 'L');
    $gender = ($gender_raw === 'P' || strtolower($gender_raw) === 'perempuan') ? 'P' : 'L';
    $alamat = $esc('alamat');
    $no_telp = mysqli_real_escape_string($connect, trim($_POST['no_telp'] ?? $_POST['telepon'] ?? ''));
    $hari_awal = mysqli_real_escape_string($connect, strtolower(trim($_POST['hari_awal'] ?? $_POST['hari_kerja_mulai'] ?? 'senin')));
    $hari_akhir = mysqli_real_escape_string($connect, strtolower(trim($_POST['hari_akhir'] ?? $_POST['hari_kerja_selesai'] ?? 'jumat')));
    $jam_mulai = $esc('jam_mulai');
    $jam_selesai = $esc('jam_selesai');
    $email = $esc('email');
    $password = $_POST['password'] ?? '';

    $dataOld = mysqli_fetch_assoc(mysqli_query($connect, "SELECT * FROM akun WHERE id_akun = $id_akun AND role = 'fo'"));
    if (!$dataOld) {
        die("Data Front Office tidak ditemukan");
    }

    $cek = mysqli_query($connect, "SELECT id_akun FROM akun WHERE email = '$email' AND id_akun != $id_akun");
    if (mysqli_num_rows($cek) > 0) {
        echo "<script>alert('Email sudah digunakan akun lain!'); window.history.back();</script>";
        exit;
    }

    $foto = $dataOld['foto_profile'] ?: 'default-profile.png';
    $fileKey = !empty($_FILES['foto']['name']) ? 'foto' : (!empty($_FILES['foto_profile']['name']) ? 'foto_profile' : null);

    if ($fileKey && $_FILES[$fileKey]['error'] === UPLOAD_ERR_OK) {
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mime = $finfo->file($_FILES[$fileKey]['tmp_name']);
        $izin = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp'];
        if (isset($izin[$mime])) {
            $folder = __DIR__ . "/../../asset/profile/";
            if (!is_dir($folder)) {
                mkdir($folder, 0777, true);
            }
            $namafoto = 'fo_' . $id_akun . '_' . bin2hex(random_bytes(6)) . '.' . $izin[$mime];
            move_uploaded_file($_FILES[$fileKey]['tmp_name'], $folder . $namafoto);
            $foto = $namafoto;
        }
    }

    $foto = mysqli_real_escape_string($connect, (string) $foto);
    $setPassword = '';
    if ($password !== '') {
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        $setPassword = ", password = '$passwordHash'";
    }
    $tglSql = ($tanggal_lahir !== '') ? "'$tanggal_lahir'" : "NULL";

    $sql = "UPDATE akun SET
                nama = '$nama',
                tanggal_lahir = $tglSql,
                gender = '$gender',
                alamat = '$alamat',
                no_telp = '$no_telp',
                hari_kerja_mulai = '$hari_awal',
                hari_kerja_selesai = '$hari_akhir',
                jam_mulai = '$jam_mulai',
                jam_selesai = '$jam_selesai',
                email = '$email',
                foto_profile = '$foto'
                $setPassword
            WHERE id_akun = $id_akun AND role = 'fo'";

    if (mysqli_query($connect, $sql)) {
        echo "<script>alert('Data Front Office berhasil diperbarui!'); window.location='../../admin/daftar_fo.php';</script>";
        exit;
    }
    die(mysqli_error($connect));
}

header("Location: ../../admin/daftar_fo.php");
exit;
?>
