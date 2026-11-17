<?php
session_start();
require_once __DIR__ . "/../config/connection.php";
require_once __DIR__ . "/../api/user/cek_expired_user.php";

if (empty($_SESSION['id_user'])) {
    header("Location: login_cust.php");
    exit;
}

$id_user = (int) $_SESSION['id_user'];

// Ambil produk yang terakhir dibeli user, jika belum ada tampilkan 3 produk unggulan
$qLast = mysqli_query($connect, "
    SELECT DISTINCT p.*
    FROM detail_transaksi dt
    JOIN transaksi t ON dt.id_transaksi = t.id_transaksi
    JOIN produk p ON dt.id_produk = p.id_produk
    WHERE t.id_user = $id_user
    ORDER BY t.tanggal_masuk DESC
    LIMIT 3
");

$produkList = [];
if ($qLast && mysqli_num_rows($qLast) > 0) {
    while ($row = mysqli_fetch_assoc($qLast)) {
        $produkList[] = $row;
    }
}
if (count($produkList) < 3) {
    $existingIds = array_map(fn($x) => (int)$x['id_produk'], $produkList);
    $notIn = count($existingIds) > 0 ? "WHERE id_produk NOT IN (" . implode(",", $existingIds) . ")" : "";
    $need = 3 - count($produkList);
    $qFill = mysqli_query($connect, "SELECT * FROM produk $notIn ORDER BY id_produk ASC LIMIT $need");
    while ($qFill && $row = mysqli_fetch_assoc($qFill)) {
        $produkList[] = $row;
    }
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
    <title>Homepage | Customer TOKO 33</title>
</head>
<body class="bg-[#FFF8E9] min-h-screen flex flex-col">

<!-- NAVBAR -->
<header class="w-full bg-[#176B4D] text-white px-5 h-14 grid grid-cols-[1fr_auto_1fr] items-center">
    <div class="flex items-center gap-2 justify-self-start">
        <img src="../asset/logo.png" alt="Logo" class="w-8 h-8 object-contain">
        <span class="font-bold text-sm">TOKO 33</span>
    </div>
    <nav class="flex items-center justify-center gap-6 md:gap-8 text-xs font-medium">
        <a href="homepage.php" class="py-2 border-b-2 border-[#DDF3E5] font-bold">Home</a>
        <a href="katalog_homecare.php" class="py-5 hover:text-[#DDF3E5]">Shop</a>
        <a href="riwayat_transaksi.php" class="py-5 hover:text-[#DDF3E5]">Order</a>
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

<main class="flex-1 px-5 md:px-10 py-5">
    <!-- HERO BANNER -->
    <section class="bg-[#DDF3E5] rounded-2xl px-6 md:px-10 py-6 mb-6 overflow-hidden">
        <div class="grid grid-cols-1 md:grid-cols-2 items-center gap-5">
            <div>
                <p class="text-xs font-semibold text-[#176B4D] mb-1">Selamat datang, <?= htmlspecialchars($_SESSION['nama_user'] ?? 'Pelanggan') ?>!</p>
                <h1 class="text-2xl md:text-3xl font-bold text-[#176B4D] leading-tight">
                    Belanja Mudah,<br>Kebutuhan Lengkap!
                </h1>
                <p class="text-xs text-gray-600 mt-3">
                    Satu tempat untuk memenuhi kebutuhan harian Anda di TOKO 33.
                </p>
                <a href="katalog_homecare.php" class="inline-block bg-[#176B4D] hover:bg-[#27815F] text-white px-8 py-3 rounded-lg text-xs font-semibold mt-4">
                    Belanja Sekarang
                </a>
            </div>
            <div class="flex justify-center items-center">
                <img src="../asset/banner-produk.png" alt="Produk Toko 33" class="w-full max-w-md h-44 object-contain">
            </div>
        </div>
    </section>

    <!-- KATEGORI PILIHAN -->
    <section class="mb-6">
        <h2 class="text-sm font-bold text-[#176B4D] mb-3">Kategori Pilihan</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <a href="katalog_homecare.php" class="bg-[#DDF3E5] rounded-xl p-4 flex items-center gap-4 hover:bg-green-100 transition">
                <div class="bg-white rounded-lg p-2 flex items-center justify-center gap-2 w-1/2 h-20">
                    <img src="../asset/produk/wipol.png" alt="Wipol" class="h-14 object-contain">
                    <img src="../asset/produk/sunlight.png" alt="Sunlight" class="h-14 object-contain">
                    <img src="../asset/produk/soklin.png" alt="So Klin" class="h-14 object-contain">
                </div>
                <div>
                    <h3 class="text-sm font-bold text-[#176B4D]">Home Care</h3>
                    <p class="text-xs text-gray-600 mt-1">Pembersih rumah & perlengkapan cuci harian</p>
                </div>
            </a>
            <a href="katalog_minuman.php" class="bg-[#DDF3E5] rounded-xl p-4 flex items-center gap-4 hover:bg-green-100 transition">
                <div class="bg-white rounded-lg p-2 flex items-center justify-center gap-2 w-1/2 h-20">
                    <img src="../asset/produk/floridina.png" alt="Floridina" class="h-14 object-contain">
                    <img src="../asset/produk/tehpucuk.png" alt="Teh Pucuk" class="h-14 object-contain">
                    <img src="../asset/produk/golda.png" alt="Golda" class="h-14 object-contain">
                </div>
                <div>
                    <h3 class="text-sm font-bold text-[#176B4D]">Minuman</h3>
                    <p class="text-xs text-gray-600 mt-1">Minuman segar siap menemani hari Anda</p>
                </div>
            </a>
        </div>
    </section>

    <!-- TERAKHIR KAMU BELI / REKOMENDASI -->
    <section>
        <h2 class="text-sm font-bold text-[#176B4D] mb-3">Terakhir Kamu Beli & Produk Pilihan</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
            <?php foreach ($produkList as $p):
                $foto = !empty($p['foto_produk']) ? "../asset/produk/" . basename($p['foto_produk']) : "../asset/wipol.png";
                $stok = (int) $p['stok'];
                $minStok = (int) ($p['stok_minimum'] ?? 10);
                if ($stok <= 0) {
                    $badgeText = "Habis";
                    $badgeClass = "bg-red-100 text-red-700";
                } elseif ($stok <= $minStok) {
                    $badgeText = "Menipis";
                    $badgeClass = "bg-yellow-100 text-yellow-700";
                } else {
                    $badgeText = "Tersedia";
                    $badgeClass = "bg-[#DDF3E5] text-[#176B4D]";
                }
            ?>
            <div class="bg-[#DDF3E5] rounded-xl p-4 flex flex-col justify-between">
                <div class="bg-white rounded-xl p-4 flex flex-col">
                    <div class="h-44 flex items-center justify-center">
                        <img src="<?= htmlspecialchars($foto) ?>" alt="<?= htmlspecialchars($p['nama_produk']) ?>" class="h-full object-contain">
                    </div>
                    <h3 class="text-base font-bold text-gray-900 text-center mt-2"><?= htmlspecialchars($p['nama_produk']) ?></h3>
                    <p class="text-xs text-gray-500 text-center mt-1 line-clamp-1"><?= htmlspecialchars($p['deskripsi'] ?? '') ?></p>
                    <?php if (!empty($p['tanggal_exp'])): ?>
                    <div class="flex justify-center mt-2">
                        <?php $expWarn = (strtotime($p['tanggal_exp']) <= strtotime('+7 days', strtotime(date('Y-m-d')))); ?>
                        <span class="<?= $expWarn ? 'bg-[#FCEBE9] text-[#D9534F]' : 'bg-[#DDF3E5] text-[#176B4D]' ?> text-[10px] font-semibold px-2.5 py-0.5 rounded-full whitespace-nowrap">
                            Exp: <?= date('d M Y', strtotime($p['tanggal_exp'])) ?>
                        </span>
                    </div>
                    <?php endif; ?>
                </div>
                <div class="flex items-center justify-between mt-3">
                    <div>
                        <p class="text-sm font-bold text-gray-900">Rp <?= number_format((float)$p['harga'], 0, ',', '.') ?></p>
                        <p class="text-xs text-gray-600">Stok: <?= $stok ?></p>
                    </div>
                    <span class="<?= $badgeClass ?> text-[11px] font-semibold px-3 py-1 rounded-full"><?= $badgeText ?></span>
                </div>
                <div class="flex items-center justify-end mt-3">
                    <?php if ($stok > 0): ?>
                    <a href="../api/user/tambah_ke_detail_user.php?id=<?= (int)$p['id_produk'] ?>" class="bg-[#176B4D] hover:bg-[#27815F] text-white text-xs font-semibold px-6 py-2 rounded-lg">
                        Beli
                    </a>
                    <?php else: ?>
                    <span class="bg-gray-300 text-gray-600 text-xs font-semibold px-6 py-2 rounded-lg cursor-not-allowed">Stok Habis</span>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </section>
</main>

<!-- FOOTER -->
<footer class="w-full bg-[#176B4D] min-h-10 flex items-center justify-center px-4 py-3 mt-auto print:hidden">
    <p class="text-white text-[9px] sm:text-[10px]">
        © 2026 Toko 33. All Rights Reserved.
    </p>
</footer>
</body>
</html>
