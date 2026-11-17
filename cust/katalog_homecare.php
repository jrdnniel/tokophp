<?php
session_start();
require_once __DIR__ . "/../config/connection.php";
require_once __DIR__ . "/../api/user/cek_expired_user.php";

if (empty($_SESSION['id_user'])) {
    header("Location: login_cust.php");
    exit;
}

$q = trim($_GET['q'] ?? '');
$where = "WHERE kategori = 'homecare'";
if ($q !== '') {
    $qEsc = mysqli_real_escape_string($connect, $q);
    $where .= " AND (nama_produk LIKE '%$qEsc%' OR deskripsi LIKE '%$qEsc%' OR kode_produk LIKE '%$qEsc%')";
}

$perPage = 6;
$page = max(1, (int) ($_GET['page'] ?? 1));
$totalRows = (int) mysqli_fetch_row(mysqli_query($connect, "SELECT COUNT(*) FROM produk $where"))[0];
$totalPages = max(1, (int) ceil($totalRows / $perPage));
if ($page > $totalPages) $page = $totalPages;
$offset = ($page - 1) * $perPage;

$resProduk = mysqli_query($connect, "SELECT * FROM produk $where ORDER BY id_produk ASC LIMIT $perPage OFFSET $offset");
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
    <title>Katalog Home Care | Customer TOKO 33</title>
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
        <a href="katalog_homecare.php" class="py-2 border-b-2 border-[#DDF3E5] font-bold">Shop</a>
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

<main class="flex-1 px-4 md:px-8 py-4 max-w-6xl mx-auto w-full">
    <!-- SEARCH BAR -->
    <form method="GET" action="katalog_homecare.php" class="flex flex-col sm:flex-row gap-2 mb-4">
        <input type="text" name="q" value="<?= htmlspecialchars($q) ?>" placeholder="Cari produk home care..." class="flex-1 bg-white border border-gray-300 rounded-md px-3 py-2 text-xs focus:outline-none focus:border-[#176B4D]">
        <button type="submit" class="bg-[#176B4D] hover:bg-[#27815F] text-white px-5 py-2 rounded-md text-xs font-semibold cursor-pointer">Cari</button>
        <?php if ($q !== ''): ?>
            <a href="katalog_homecare.php" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-md text-xs font-semibold text-center">Reset</a>
        <?php endif; ?>
    </form>

    <!-- KATEGORI BUTTON -->
    <div class="grid grid-cols-2 gap-3 mb-5">
        <a href="katalog_homecare.php" class="bg-[#176B4D] text-white text-center py-2.5 rounded-md text-xs font-semibold">Home Care</a>
        <a href="katalog_minuman.php" class="bg-[#DDF3E5] text-[#176B4D] hover:bg-[#cbead7] text-center py-2.5 rounded-md text-xs font-semibold">Minuman</a>
    </div>

    <!-- GRID PRODUK -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
        <?php if ($resProduk && mysqli_num_rows($resProduk) > 0): ?>
            <?php while ($p = mysqli_fetch_assoc($resProduk)):
                $foto = !empty($p['foto_produk']) ? "../asset/produk/" . basename($p['foto_produk']) : "../asset/wipol.png";
                $stok = (int) $p['stok'];
                $minStok = (int) ($p['stok_minimum'] ?? 10);
                if ($stok <= 0) {
                    $badgeText = "Habis";
                    $badgeClass = "bg-[#FCEBE9] text-[#D9534F]";
                } elseif ($stok <= $minStok) {
                    $badgeText = "Menipis";
                    $badgeClass = "bg-[#FFF3CD] text-[#B78103]";
                } else {
                    $badgeText = "Tersedia";
                    $badgeClass = "bg-[#DDF3E5] text-[#176B4D]";
                }
                $qtyInCart = $_SESSION['cart_user'][$p['id_produk']]['qty'] ?? 0;
            ?>
            <div class="bg-[#DDF3E5] rounded-xl p-4 flex flex-col justify-between">
                <div class="bg-white rounded-xl p-4 flex flex-col">
                    <div class="h-44 flex items-center justify-center">
                        <img src="<?= htmlspecialchars($foto) ?>" alt="<?= htmlspecialchars($p['nama_produk']) ?>" class="h-full object-contain">
                    </div>
                    <h3 class="text-base font-bold text-gray-900 text-center mt-2"><?= htmlspecialchars($p['nama_produk']) ?></h3>
                    <p class="text-xs text-gray-500 text-center mt-1 line-clamp-2"><?= htmlspecialchars($p['deskripsi'] ?? '') ?></p>
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
                <div class="flex items-center justify-between mt-3">
                    <span class="text-[11px] text-[#176B4D] font-medium">
                        <?= $qtyInCart > 0 ? "Di keranjang: $qtyInCart" : "" ?>
                    </span>
                    <?php if ($stok > 0): ?>
                    <a href="../api/user/tambah_ke_detail_user.php?id=<?= (int)$p['id_produk'] ?>" class="bg-[#176B4D] hover:bg-[#27815F] text-white text-xs font-semibold px-5 py-2 rounded-lg">
                        + Beli
                    </a>
                    <?php else: ?>
                    <span class="bg-gray-300 text-gray-600 text-xs font-semibold px-5 py-2 rounded-lg cursor-not-allowed">Habis</span>
                    <?php endif; ?>
                </div>
            </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="col-span-full bg-white rounded-xl p-8 text-center text-gray-500 text-sm">
                Produk tidak ditemukan.
            </div>
        <?php endif; ?>
    </div>

    <!-- PAGINATION -->
    <?php if ($totalPages > 1): ?>
    <div class="flex justify-between items-center mt-6 text-xs text-gray-600">
        <span>Menampilkan halaman <?= $page ?> dari <?= $totalPages ?> (Total <?= $totalRows ?> produk)</span>
        <div class="flex gap-1">
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <a href="katalog_homecare.php?page=<?= $i ?>&q=<?= urlencode($q) ?>" class="px-3 py-1.5 rounded <?= $i === $page ? 'bg-[#176B4D] text-white font-bold' : 'bg-white border text-gray-700' ?>"><?= $i ?></a>
            <?php endfor; ?>
        </div>
    </div>
    <?php endif; ?>
</main>

<!-- FOOTER -->
<footer class="w-full bg-[#176B4D] min-h-10 flex items-center justify-center px-4 py-3 mt-auto print:hidden">
    <p class="text-white text-[9px] sm:text-[10px]">
        © 2026 Toko 33. All Rights Reserved.
    </p>
</footer>
</body>
</html>
