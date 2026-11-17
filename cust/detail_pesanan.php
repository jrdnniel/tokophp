<?php
session_start();
require_once __DIR__ . "/../config/connection.php";

if (empty($_SESSION['id_user'])) {
    header("Location: login_cust.php");
    exit;
}

$cart = $_SESSION['cart_user'] ?? [];
$homecareItems = [];
$minumanItems = [];
$totalHarga = 0;

foreach ($cart as $id_produk => $item) {
    $sub = ((float)$item['harga']) * ((int)$item['qty']);
    $totalHarga += $sub;
    if (($item['kategori'] ?? '') === 'minuman') {
        $minumanItems[$id_produk] = $item;
    } else {
        $homecareItems[$id_produk] = $item;
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
    <title>Detail Pesanan | Customer TOKO 33</title>
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

<main class="max-w-4xl mx-auto px-4 py-8 flex-1 w-full">
    <div class="bg-[#DDF3E5] rounded-2xl p-6 shadow-sm">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-xl font-bold text-[#176B4D]">Detail Pembelian</h1>
                <p class="text-xs text-[#27815F] mt-1">Periksa kembali produk belanjaan Anda sebelum melanjutkan ke pembayaran.</p>
            </div>
            <a href="katalog_homecare.php" class="text-xs font-semibold text-[#176B4D] hover:underline">← Kembali ke Katalog</a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
            <div>
                <label class="text-xs font-medium text-[#176B4D] block mb-1">ID Pelanggan</label>
                <div class="w-full h-10 px-3 rounded-lg bg-[#FCEBE9] border border-[#f2c6c2] flex items-center text-xs text-gray-700 font-semibold">
                    USR-<?= str_pad((string)$_SESSION['id_user'], 3, '0', STR_PAD_LEFT) ?>
                </div>
            </div>
            <div>
                <label class="text-xs font-medium text-[#176B4D] block mb-1">Nama Pengguna</label>
                <div class="w-full h-10 px-3 rounded-lg bg-white border border-gray-300 flex items-center text-xs text-gray-800 font-semibold">
                    <?= htmlspecialchars($_SESSION['nama_user'] ?? '') ?>
                </div>
            </div>
        </div>

        <?php if (empty($cart)): ?>
            <div class="bg-white rounded-xl p-8 text-center mb-6">
                <p class="text-sm text-gray-600 mb-3">Keranjang belanja Anda masih kosong.</p>
                <a href="katalog_homecare.php" class="inline-block bg-[#176B4D] text-white px-5 py-2.5 rounded-lg text-xs font-semibold">Mulai Belanja</a>
            </div>
        <?php else: ?>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <!-- HOME CARE -->
                <div>
                    <h2 class="text-sm font-bold text-[#176B4D] mb-3">Home Care</h2>
                    <div class="space-y-3">
                        <?php if (empty($homecareItems)): ?>
                            <div class="bg-white/70 rounded-xl p-4 text-xs text-gray-500">Tidak ada item Home Care.</div>
                        <?php else: ?>
                            <?php foreach ($homecareItems as $id_p => $it):
                                $foto = !empty($it['foto_produk']) ? "../asset/produk/" . basename($it['foto_produk']) : "../asset/wipol.png";
                            ?>
                            <div class="bg-white rounded-xl p-3 flex items-center justify-between border border-gray-200">
                                <div class="flex items-center gap-3">
                                    <div class="w-12 h-12 rounded-lg bg-[#F5F5F5] flex items-center justify-center p-1">
                                        <img src="<?= htmlspecialchars($foto) ?>" class="h-full object-contain" alt="">
                                    </div>
                                    <div>
                                        <p class="text-xs font-bold text-[#176B4D]"><?= htmlspecialchars($it['nama']) ?></p>
                                        <p class="text-xs text-gray-600">Rp <?= number_format((float)$it['harga'], 0, ',', '.') ?> x <?= (int)$it['qty'] ?></p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-2">
                                    <a href="../api/user/update_qty_user.php?id=<?= (int)$id_p ?>&aksi=kurang" class="w-7 h-7 rounded-md bg-[#FCEBE9] text-[#D9534F] font-bold text-xs flex items-center justify-center">-</a>
                                    <span class="text-xs font-bold w-5 text-center"><?= (int)$it['qty'] ?></span>
                                    <a href="../api/user/update_qty_user.php?id=<?= (int)$id_p ?>&aksi=tambah" class="w-7 h-7 rounded-md bg-[#DDF3E5] text-[#176B4D] font-bold text-xs flex items-center justify-center">+</a>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- MINUMAN -->
                <div>
                    <h2 class="text-sm font-bold text-[#176B4D] mb-3">Minuman</h2>
                    <div class="space-y-3">
                        <?php if (empty($minumanItems)): ?>
                            <div class="bg-white/70 rounded-xl p-4 text-xs text-gray-500">Tidak ada item Minuman.</div>
                        <?php else: ?>
                            <?php foreach ($minumanItems as $id_p => $it):
                                $foto = !empty($it['foto_produk']) ? "../asset/produk/" . basename($it['foto_produk']) : "../asset/tehpucuk.png";
                            ?>
                            <div class="bg-white rounded-xl p-3 flex items-center justify-between border border-gray-200">
                                <div class="flex items-center gap-3">
                                    <div class="w-12 h-12 rounded-lg bg-[#F5F5F5] flex items-center justify-center p-1">
                                        <img src="<?= htmlspecialchars($foto) ?>" class="h-full object-contain" alt="">
                                    </div>
                                    <div>
                                        <p class="text-xs font-bold text-[#176B4D]"><?= htmlspecialchars($it['nama']) ?></p>
                                        <p class="text-xs text-gray-600">Rp <?= number_format((float)$it['harga'], 0, ',', '.') ?> x <?= (int)$it['qty'] ?></p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-2">
                                    <a href="../api/user/update_qty_user.php?id=<?= (int)$id_p ?>&aksi=kurang" class="w-7 h-7 rounded-md bg-[#FCEBE9] text-[#D9534F] font-bold text-xs flex items-center justify-center">-</a>
                                    <span class="text-xs font-bold w-5 text-center"><?= (int)$it['qty'] ?></span>
                                    <a href="../api/user/update_qty_user.php?id=<?= (int)$id_p ?>&aksi=tambah" class="w-7 h-7 rounded-md bg-[#DDF3E5] text-[#176B4D] font-bold text-xs flex items-center justify-center">+</a>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <!-- TOTAL HARGA -->
        <div class="mb-6">
            <label class="text-xs font-medium text-[#176B4D] block mb-1">Total Harga</label>
            <div class="w-full h-11 px-4 rounded-xl bg-white border border-gray-300 flex items-center justify-between text-sm font-bold text-[#176B4D]">
                <span>Total Pembayaran</span>
                <span>Rp <?= number_format($totalHarga, 0, ',', '.') ?></span>
            </div>
        </div>

        <!-- ACTION BUTTONS -->
        <div class="flex flex-col sm:flex-row justify-between items-center gap-3">
            <a href="katalog_homecare.php" class="w-full sm:w-auto px-5 py-2.5 rounded-lg bg-[#176B4D] hover:bg-[#27815F] text-white text-xs font-semibold text-center">
                ＋ Tambah Produk
            </a>
            <?php if (!empty($cart)): ?>
            <div class="flex gap-3 w-full sm:w-auto">
                <a href="../api/user/batal_submit.php" class="w-full sm:w-auto px-6 py-2.5 rounded-lg bg-[#FCEBE9] text-[#D9534F] text-xs font-semibold text-center">
                    Batal
                </a>
                <form action="../api/user/submit_pesanan_user.php" method="POST" class="w-full sm:w-auto">
                    <button type="submit" class="w-full sm:w-auto px-6 py-2.5 rounded-lg bg-[#35A875] hover:bg-[#27815F] text-white text-xs font-semibold cursor-pointer">
                        Simpan & Bayar
                    </button>
                </form>
            </div>
            <?php endif; ?>
        </div>
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
