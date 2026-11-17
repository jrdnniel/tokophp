<?php
session_start();
require_once __DIR__ . "/../config/connection.php";

if (empty($_SESSION['id_user'])) {
    header("Location: login_cust.php");
    exit;
}

$id_user = (int) $_SESSION['id_user'];
$user = mysqli_fetch_assoc(mysqli_query($connect, "SELECT * FROM users WHERE id_user = $id_user"));
if (!$user) {
    header("Location: login_cust.php");
    exit;
}

$fotoFile = !empty($user['foto_profile']) ? basename($user['foto_profile']) : 'default-profile.png';
$fotoUrl = "../asset/profile/" . $fotoFile;
if (!file_exists(__DIR__ . "/../asset/profile/" . $fotoFile)) {
    $fotoUrl = "../asset/default-profile.png";
}
$statusMsg = $_GET['status'] ?? '';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link rel="icon" type="image/png" href="../asset/logo.png">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet">
    <style>body { font-family: 'Inter', sans-serif; }</style>
    <title>Profil Saya | Customer TOKO 33</title>
</head>
<body class="bg-[#FFF8E9] min-h-screen flex flex-col">

<!-- NAVBAR -->
<header class="w-full bg-[#176B4D] text-white px-5 h-14 grid grid-cols-[1fr_auto_1fr] items-center">
    <div class="flex items-center gap-2 justify-self-start">
        <img src="../asset/logo.png" alt="Logo" class="w-8 h-8 object-contain">
        <span class="font-bold text-sm">TOKO 33</span>
    </div>
    <nav class="flex items-center justify-center gap-6 md:gap-8 text-xs font-medium">
        <a href="homepage.php" class="py-5 hover:text-[#DDF3E5]">Home</a>
        <a href="katalog_homecare.php" class="py-5 hover:text-[#DDF3E5]">Shop</a>
        <a href="riwayat_transaksi.php" class="py-5 hover:text-[#DDF3E5]">Order</a>
        <a href="kritiksaran.php" class="py-5 hover:text-[#DDF3E5]">Kritik & Saran</a>
        <a href="profile.php" class="py-2 border-b-2 border-[#DDF3E5] font-bold">Profile</a>
    </nav>
    <div class="justify-self-end flex items-center gap-2.5">
        <!-- 1. Foto Profile (Kiri) -->
        <a href="profile.php" title="Profile Saya" class="w-8 h-8 rounded-full bg-[#DDF3E5] text-[#176B4D] flex items-center justify-center font-bold text-xs overflow-hidden border border-white shrink-0">
            <?php
            $fotoCust = $_SESSION['foto_profile_user'] ?? 'default-profile.png';
            $pathFotoCust = "../asset/profile/" . basename($fotoCust);
            if (!empty($fotoCust) && $fotoCust !== 'default-profile.png' && file_exists(__DIR__ . "/../asset/profile/" . basename($fotoCust))):
            ?>
                <img src="<?= htmlspecialchars($pathFotoCust) ?>" alt="Profil" class="w-full h-full object-cover">
            <?php else: ?>
                <?= strtoupper(substr($_SESSION['nama_user'] ?? 'U', 0, 1)) ?>
            <?php endif; ?>
        </a>

        <!-- 2. Keranjang Belanja (Tengah) -->
        <a href="detail_pesanan.php" title="Keranjang Belanja" class="relative bg-[#27815F] hover:bg-[#35A875] p-2 rounded-lg text-white flex items-center justify-center">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="w-5 h-5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.7 2.84-7.25H5.106m2.394 7.25L5.106 5.25M7.5 21a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Zm12 0a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Z" />
            </svg>
            <?php $cartCount = isset($_SESSION['cart_user']) ? array_sum(array_column($_SESSION['cart_user'], 'qty')) : 0; ?>
            <?php if ($cartCount > 0): ?>
                <span class="absolute -top-1.5 -right-1.5 bg-[#FFF8E9] text-[#176B4D] font-bold px-1.5 py-0.2 rounded-full text-[10px] shadow-sm leading-tight border border-[#176B4D]/20"><?= $cartCount ?></span>
            <?php endif; ?>
        </a>

        <!-- 3. Logout (Kanan) -->
        <a href="../api/user/logout.php" title="Logout" class="bg-[#FCEBE9] text-[#D9534F] hover:bg-[#fadbd8] p-2 rounded-lg flex items-center justify-center">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="w-5 h-5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 9V5.25A2.25 2.25 0 0 1 10.5 3h6a2.25 2.25 0 0 1 2.25 2.25v13.5A2.25 2.25 0 0 1 16.5 21h-6a2.25 2.25 0 0 1-2.25-2.25V15m-3 0-3-3m0 0 3-3m-3 3H15" />
            </svg>
        </a>
    </div>
</header>

<main class="max-w-3xl mx-auto px-4 py-6 flex-1 w-full">
    <?php if ($statusMsg === 'berhasil'): ?>
        <div class="bg-[#DDF3E5] border border-[#35A875] text-[#176B4D] px-4 py-3 rounded-xl text-xs font-semibold mb-4">
            Profil dan foto berhasil diperbarui!
        </div>
    <?php elseif ($statusMsg !== ''): ?>
        <div class="bg-[#FCEBE9] border border-[#D9534F] text-[#D9534F] px-4 py-3 rounded-xl text-xs font-semibold mb-4">
            Gagal memperbarui profil (<?= htmlspecialchars($statusMsg) ?>). Pastikan format & ukuran file sesuai.
        </div>
    <?php endif; ?>

    <div class="bg-[#DDF3E5] rounded-2xl p-6 shadow-sm">
        <div class="mb-6">
            <h1 class="text-lg font-bold text-[#176B4D]">Profil Pengguna</h1>
            <p class="text-xs text-[#27815F]">Kelola informasi akun dan foto profil Anda</p>
        </div>

        <form action="../api/user/update_profile.php" method="POST" enctype="multipart/form-data" class="space-y-4">
            <div class="flex flex-col items-center mb-6">
                <img src="<?= htmlspecialchars($fotoUrl) ?>" alt="Foto Profil" class="w-24 h-24 rounded-full object-cover border-2 border-[#176B4D] bg-white mb-3">
                <label class="px-4 py-2 rounded-lg bg-[#176B4D] hover:bg-[#27815F] text-white text-xs font-semibold cursor-pointer">
                    Pilih Foto Profil Baru
                    <input type="file" name="foto_profile" accept=".jpg,.jpeg,.png,.webp" class="hidden">
                </label>
                <p class="text-[11px] text-gray-500 mt-1">Format: JPG, PNG, atau WEBP (Maks. 2MB)</p>
            </div>

            <div>
                <label class="block text-xs font-medium text-[#176B4D] mb-1">ID Pengguna</label>
                <input type="text" value="USR-<?= str_pad((string)$user['id_user'], 3, '0', STR_PAD_LEFT) ?>" readonly class="w-full h-10 px-3 rounded-lg bg-[#FCEBE9] border border-[#f2c6c2] text-xs font-semibold text-gray-700">
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-medium text-[#176B4D] mb-1">Nama Lengkap</label>
                    <input type="text" name="nama" value="<?= htmlspecialchars($user['nama'] ?? '') ?>" required class="w-full h-10 px-3 rounded-lg bg-white border border-gray-300 text-xs">
                </div>
                <div>
                    <label class="block text-xs font-medium text-[#176B4D] mb-1">Nomor Telepon</label>
                    <input type="text" name="no_telp" value="<?= htmlspecialchars($user['no_telp'] ?? '') ?>" required class="w-full h-10 px-3 rounded-lg bg-white border border-gray-300 text-xs">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-medium text-[#176B4D] mb-1">Email</label>
                    <input type="email" name="email" value="<?= htmlspecialchars($user['email'] ?? '') ?>" required class="w-full h-10 px-3 rounded-lg bg-white border border-gray-300 text-xs">
                </div>
                <div>
                    <label class="block text-xs font-medium text-[#176B4D] mb-1">Tanggal Lahir</label>
                    <input type="date" name="tanggal_lahir" value="<?= htmlspecialchars($user['tanggal_lahir'] ?? '') ?>" class="w-full h-10 px-3 rounded-lg bg-white border border-gray-300 text-xs">
                </div>
            </div>

            <div>
                <label class="block text-xs font-medium text-[#176B4D] mb-1">Alamat Lengkap</label>
                <textarea name="alamat" rows="3" class="w-full p-3 rounded-lg bg-white border border-gray-300 text-xs"><?= htmlspecialchars($user['alamat'] ?? '') ?></textarea>
            </div>

            <div>
                <label class="block text-xs font-medium text-[#176B4D] mb-1">Password Baru (Kosongkan jika tidak ingin mengubah)</label>
                <input type="password" name="password" placeholder="Minimal 8 karakter" class="w-full h-10 px-3 rounded-lg bg-white border border-gray-300 text-xs">
            </div>

            <div class="flex justify-between items-center pt-4">
                <a href="resetpassword.php" class="text-xs font-semibold text-[#176B4D] hover:underline">Reset Password via Email</a>
                <button type="submit" class="px-6 py-2.5 rounded-lg bg-[#176B4D] hover:bg-[#27815F] text-white text-xs font-semibold cursor-pointer">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</main>

<!-- FOOTER -->
<footer class="w-full bg-[#176B4D] min-h-10 flex items-center justify-center px-4 py-3 mt-auto print:hidden">
    <p class="text-white text-[9px] sm:text-[10px]">
        © 2026 Toko 33. All Rights Reserved.
    </p>
</footer>
</body>
</html>
