<?php
session_start();
require_once __DIR__ . "/../config/connection.php";
require_once __DIR__ . "/../api/user/cek_expired_user.php";

if (empty($_SESSION['id_user'])) {
    header("Location: login_cust.php");
    exit;
}

$id_user = (int) $_SESSION['id_user'];
$id_transaksi = (int) ($_GET['id'] ?? 0);

if ($id_transaksi > 0) {
    $trx = mysqli_fetch_assoc(mysqli_query($connect, "SELECT * FROM transaksi WHERE id_transaksi = $id_transaksi AND id_user = $id_user"));
} else {
    $trx = mysqli_fetch_assoc(mysqli_query($connect, "SELECT * FROM transaksi WHERE id_user = $id_user AND status = 'menunggu_pembayaran' ORDER BY id_transaksi DESC LIMIT 1"));
}

if (!$trx || $trx['status'] !== 'menunggu_pembayaran') {
    header("Location: riwayat_transaksi.php");
    exit;
}
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
    <title>Pembayaran | Customer TOKO 33</title>
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
        <a href="riwayat_transaksi.php" class="py-2 border-b-2 border-[#DDF3E5] font-bold">Order</a>
        <a href="kritiksaran.php" class="py-5 hover:text-[#DDF3E5]">Kritik & Saran</a>
        <a href="profile.php" class="py-5 hover:text-[#DDF3E5]">Profile</a>
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

<main class="max-w-md mx-auto px-4 py-8 flex-1 w-full">
    <div class="bg-[#DDF3E5] rounded-2xl p-6 shadow-sm">
        <div class="mb-5">
            <h1 class="text-xl font-bold text-[#176B4D]">Pembayaran Pesanan #TRX-<?= str_pad((string)$trx['id_transaksi'], 4, '0', STR_PAD_LEFT) ?></h1>
            <p class="text-xs text-[#27815F] mt-1">Scan QRIS di bawah ini dan unggah bukti pembayaran Anda.</p>
        </div>

        <div class="bg-white rounded-xl p-4 border border-gray-200 flex justify-center mb-4">
            <img src="../asset/qris.jpeg" alt="QRIS Toko 33" class="w-64 object-contain rounded-lg">
        </div>

        <?php if (!empty($trx['expired_at'])): ?>
        <div class="bg-[#FFF3CD] text-[#B78103] text-xs font-medium px-3 py-2 rounded-lg text-center mb-4">
            Batas waktu pembayaran: <?= date('d M Y H:i', strtotime($trx['expired_at'])) ?> WIB
        </div>
        <?php endif; ?>

        <div class="bg-white rounded-xl p-4 border border-gray-200 mb-4 flex justify-between items-center">
            <span class="text-xs text-gray-600">Total Pembayaran</span>
            <span class="text-base font-bold text-[#176B4D]">Rp <?= number_format((float)$trx['total_pembayaran'], 0, ',', '.') ?></span>
        </div>

        <form action="../api/user/submit_transaksi_user.php?id=<?= (int)$trx['id_transaksi'] ?>" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id_transaksi" value="<?= (int)$trx['id_transaksi'] ?>">
            <div class="mb-5">
                <label class="block text-xs font-semibold text-[#176B4D] mb-2">Upload Bukti Pembayaran (JPG/PNG/WEBP/PDF)</label>
                <input type="file" name="bukti_transfer" required accept=".jpg,.jpeg,.png,.webp,.pdf" class="w-full text-xs bg-white rounded-lg border border-gray-300 p-2 file:mr-3 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:bg-[#DDF3E5] file:text-[#176B4D] file:font-semibold">
            </div>

            <div class="flex justify-between gap-3">
                <a href="riwayat_transaksi.php" class="w-full text-center py-2.5 rounded-lg bg-white text-[#176B4D] text-xs font-semibold border border-gray-300">Lihat Riwayat</a>
                <button type="submit" class="w-full text-center py-2.5 rounded-lg bg-[#176B4D] hover:bg-[#27815F] text-white text-xs font-semibold cursor-pointer">Upload Bukti</button>
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
