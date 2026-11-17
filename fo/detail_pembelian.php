<?php
session_start();
require_once __DIR__ . "/../config/connection.php";

if (!isset($_SESSION['id_akun']) || ($_SESSION['role'] ?? '') !== 'fo') {
    header("Location: login_fo.php");
    exit;
}

$cart = $_SESSION['cart_fo'] ?? [];
$nama_cust_saved = $_SESSION['nama_customer_fo'] ?? '';
$hcItems = [];
$mnItems = [];
$total = 0;

foreach ($cart as $id_p => $it) {
    $total += ((float)$it['harga']) * ((int)$it['qty']);
    if (($it['kategori'] ?? '') === 'minuman') $mnItems[$id_p] = $it;
    else $hcItems[$id_p] = $it;
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
    <title>Detail Pembelian POS | Front Office TOKO 33</title>
</head>
<body class="m-0 p-0 w-full min-h-screen overflow-x-hidden bg-[#F5F5F5]">
    <div class="w-full min-h-screen">
        <header class="bg-[#DDF3E5] sticky top-0 z-30 flex items-center justify-between px-4 h-14 w-full shadow-xs">
            <div class="flex items-center gap-3">
                <img src="../asset/logo.png" alt="Logo Toko 33" class="w-8 h-8 object-contain">
                <h1 class="text-[#176B4D] text-sm font-bold">TOKO 33 • FRONT OFFICE</h1>
            </div>
            <div class="flex items-center gap-3">
                <a href="detail_pembelian.php" class="bg-[#176B4D] text-white px-3 py-1.5 rounded-lg text-xs font-semibold">
                    Keranjang POS (<?= isset($_SESSION['cart_fo']) ? array_sum(array_column($_SESSION['cart_fo'], 'qty')) : 0 ?>)
                </a>
                <a href="profile.php" class="flex items-center gap-2 text-xs font-semibold text-[#176B4D]">
                    <span><?= htmlspecialchars($_SESSION['nama'] ?? 'FO') ?></span>
                </a>
            </div>
        </header>
        <div class="grid grid-cols-[55px_minmax(0,1fr)] sm:grid-cols-[65px_minmax(0,1fr)] md:grid-cols-[200px_minmax(0,1fr)] lg:grid-cols-[230px_minmax(0,1fr)]">
            <aside class="bg-[#176B4D] text-white flex flex-col justify-between py-4 px-1 sm:px-2 md:px-3 sticky top-14 h-[calc(100vh-56px)] overflow-y-auto shrink-0">
                <nav class="flex flex-col gap-2">
                    
                    <a href="dashboard.php" title="Dashboard" class="flex items-center justify-center md:justify-start gap-3 hover:bg-[#27815F] rounded-lg px-2 md:px-3 py-3">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6"><path stroke-linecap="round" stroke-linejoin="round" d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" /></svg>
                        <span class="hidden md:inline text-xs">Dashboard</span>
                    </a>

                    <a href="katalog_homecare.php" title="Katalog" class="flex items-center justify-center md:justify-start gap-3 bg-[#35A875] font-semibold rounded-lg px-2 md:px-3 py-3">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 0 1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 5.513 7.5h12.974c.576 0 1.059.435 1.119 1.007ZM8.625 10.5a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm7.5 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" /></svg>
                        <span class="hidden md:inline text-xs">Katalog</span>
                    </a>

                    <a href="pesanan_masuk.php" title="Pesanan Masuk" class="flex items-center justify-center md:justify-start gap-3 hover:bg-[#27815F] rounded-lg px-2 md:px-3 py-3">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6"><path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0" /></svg>
                        <span class="hidden md:inline text-xs">Pesanan Masuk</span>
                    </a>

                    <a href="status_pesanan.php" title="Status Pesanan" class="flex items-center justify-center md:justify-start gap-3 hover:bg-[#27815F] rounded-lg px-2 md:px-3 py-3">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6"><path stroke-linecap="round" stroke-linejoin="round" d="M11.35 3.836c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m8.9-4.414c.376.023.75.05 1.124.08 1.131.094 1.976 1.057 1.976 2.192V16.5A2.25 2.25 0 0 1 18 18.75h-2.25m-7.5-10.5H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V18.75m-7.5-10.5h6.375c.621 0 1.125.504 1.125 1.125v9.375m-8.25-3 1.5 1.5 3-3.75" /></svg>
                        <span class="hidden md:inline text-xs">Status Pesanan</span>
                    </a>

                    <a href="riwayat_transaksi.php" title="Riwayat Transaksi" class="flex items-center justify-center md:justify-start gap-3 hover:bg-[#27815F] rounded-lg px-2 md:px-3 py-3">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
                        <span class="hidden md:inline text-xs">Riwayat Transaksi</span>
                    </a>

                    <a href="jadwal_fo.php" title="Jadwal FO" class="flex items-center justify-center md:justify-start gap-3 hover:bg-[#27815F] rounded-lg px-2 md:px-3 py-3">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" /></svg>
                        <span class="hidden md:inline text-xs">Jadwal FO</span>
                    </a>

                    <a href="jadwal_saya.php" title="Jadwal Saya" class="flex items-center justify-center md:justify-start gap-3 hover:bg-[#27815F] rounded-lg px-2 md:px-3 py-3">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6"><path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z" /></svg>
                        <span class="hidden md:inline text-xs">Jadwal Saya</span>
                    </a>

                    <a href="profile.php" title="Profile" class="flex items-center justify-center md:justify-start gap-3 hover:bg-[#27815F] rounded-lg px-2 md:px-3 py-3">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6"><path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0 0 12 15.75a7.488 7.488 0 0 0-5.982 2.975m11.963 0a9 9 0 1 0-11.963 0m11.963 0A8.966 8.966 0 0 1 12 21a8.966 8.966 0 0 1-5.982-2.275M15 9.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" /></svg>
                        <span class="hidden md:inline text-xs">Profile</span>
                    </a>
                </nav>
                <a href="../api/fo/logout_fo.php" title="Logout" class="flex items-center justify-center md:justify-start gap-3 bg-[#FCEBE9] text-[#D9534F] rounded-lg px-2 md:px-3 py-3 mt-5">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 9V5.25A2.25 2.25 0 0 1 10.5 3h6a2.25 2.25 0 0 1 2.25 2.25v13.5A2.25 2.25 0 0 1 16.5 21h-6a2.25 2.25 0 0 1-2.25-2.25V15m-3 0-3-3m0 0 3-3m-3 3H15" />
                    </svg>
                    <span class="hidden md:inline text-xs font-semibold">Logout</span>
                </a>
            </aside>
            <main class="bg-[#FFF8E9] p-4 sm:p-5 md:p-6 min-w-0">
<div class="max-w-4xl mx-auto bg-[#DDF3E5] rounded-2xl p-6 shadow-sm">
    <div class="mb-6">
        <h1 class="text-xl font-bold text-[#176B4D]">Detail Pembelian Kasir (POS Front Office)</h1>
        <p class="text-xs text-[#27815F]">Masukkan nama pelanggan dan periksa daftar produk yang dibeli.</p>
    </div>

    <form action="../api/fo/submit_pesanan.php" method="POST">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
            <div>
                <label class="text-xs font-medium text-[#176B4D] block mb-1">Petugas Front Office</label>
                <input type="text" value="<?= htmlspecialchars($_SESSION['nama'] ?? 'FO') ?>" readonly class="w-full h-10 px-3 rounded-lg bg-[#FCEBE9] border border-[#f2c6c2] text-xs font-semibold text-gray-700">
            </div>
            <div>
                <label class="text-xs font-medium text-[#176B4D] block mb-1">Nama Pelanggan *</label>
                <input type="text" name="nama_customer" value="<?= htmlspecialchars($nama_cust_saved) ?>" required placeholder="Masukkan nama pelanggan..." class="w-full h-10 px-3 rounded-lg bg-white border border-gray-300 text-xs">
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div>
                <h2 class="text-sm font-bold text-[#176B4D] mb-3">Home Care</h2>
                <div class="space-y-3">
                    <?php if (empty($hcItems)): ?>
                        <div class="bg-white/70 rounded-xl p-3 text-xs text-gray-400">Belum ada produk Home Care.</div>
                    <?php else: ?>
                        <?php foreach ($hcItems as $id_p => $it): ?>
                        <div class="bg-white rounded-xl p-3 flex items-center justify-between border border-gray-200">
                            <div>
                                <p class="text-xs font-bold text-[#176B4D]"><?= htmlspecialchars($it['nama']) ?></p>
                                <p class="text-xs text-gray-600">Rp <?= number_format((float)$it['harga'], 0, ',', '.') ?> x <?= (int)$it['qty'] ?></p>
                            </div>
                            <div class="flex items-center gap-2">
                                <a href="../api/fo/update_qty.php?id=<?= (int)$id_p ?>&aksi=kurang" class="w-7 h-7 rounded-md bg-[#FCEBE9] text-[#D9534F] font-bold text-xs flex items-center justify-center">-</a>
                                <span class="text-xs font-bold w-5 text-center"><?= (int)$it['qty'] ?></span>
                                <a href="../api/fo/update_qty.php?id=<?= (int)$id_p ?>&aksi=tambah" class="w-7 h-7 rounded-md bg-[#DDF3E5] text-[#176B4D] font-bold text-xs flex items-center justify-center">+</a>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
            <div>
                <h2 class="text-sm font-bold text-[#176B4D] mb-3">Minuman</h2>
                <div class="space-y-3">
                    <?php if (empty($mnItems)): ?>
                        <div class="bg-white/70 rounded-xl p-3 text-xs text-gray-400">Belum ada produk Minuman.</div>
                    <?php else: ?>
                        <?php foreach ($mnItems as $id_p => $it): ?>
                        <div class="bg-white rounded-xl p-3 flex items-center justify-between border border-gray-200">
                            <div>
                                <p class="text-xs font-bold text-[#176B4D]"><?= htmlspecialchars($it['nama']) ?></p>
                                <p class="text-xs text-gray-600">Rp <?= number_format((float)$it['harga'], 0, ',', '.') ?> x <?= (int)$it['qty'] ?></p>
                            </div>
                            <div class="flex items-center gap-2">
                                <a href="../api/fo/update_qty.php?id=<?= (int)$id_p ?>&aksi=kurang" class="w-7 h-7 rounded-md bg-[#FCEBE9] text-[#D9534F] font-bold text-xs flex items-center justify-center">-</a>
                                <span class="text-xs font-bold w-5 text-center"><?= (int)$it['qty'] ?></span>
                                <a href="../api/fo/update_qty.php?id=<?= (int)$id_p ?>&aksi=tambah" class="w-7 h-7 rounded-md bg-[#DDF3E5] text-[#176B4D] font-bold text-xs flex items-center justify-center">+</a>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="mb-6">
            <label class="text-xs font-medium text-[#176B4D] block mb-1">Total Harga</label>
            <div class="w-full h-11 px-4 rounded-xl bg-white border border-gray-300 flex items-center justify-between text-sm font-bold text-[#176B4D]">
                <span>Total Tagihan</span>
                <span>Rp <?= number_format($total, 0, ',', '.') ?></span>
            </div>
        </div>

        <div class="flex flex-col sm:flex-row justify-between items-center gap-3">
            <a href="katalog_homecare.php" class="px-5 py-2.5 rounded-lg bg-[#176B4D] text-white text-xs font-semibold">＋ Tambah Produk</a>
            <?php if (!empty($cart)): ?>
            <div class="flex gap-3">
                <a href="../api/fo/batal.php" class="px-6 py-2.5 rounded-lg bg-[#FCEBE9] text-[#D9534F] text-xs font-semibold">Batal</a>
                <button type="submit" class="px-6 py-2.5 rounded-lg bg-[#35A875] text-white text-xs font-semibold cursor-pointer">Simpan & Lanjut Bayar</button>
            </div>
            <?php endif; ?>
        </div>
    </form>
</div>

            </main>
        </div>
        <!-- FOOTER -->
<footer class="w-full bg-[#176B4D] min-h-10 flex items-center justify-center px-4 py-3 mt-auto print:hidden">
    <p class="text-white text-[9px] sm:text-[10px]">
        © 2026 Toko 33. All Rights Reserved.
    </p>
</footer>
    </div>
</body>
</html>

