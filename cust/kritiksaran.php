<?php
session_start();
require_once __DIR__ . "/../config/connection.php";

if (empty($_SESSION['id_user'])) {
    header("Location: login_cust.php");
    exit;
}

$id_user = (int) $_SESSION['id_user'];
$qMyKritik = mysqli_query($connect, "SELECT * FROM kritik_saran WHERE id_user = $id_user ORDER BY id_kritik DESC");
$qMyTrx = mysqli_query($connect, "SELECT id_transaksi, tanggal_masuk, total_pembayaran FROM transaksi WHERE id_user = $id_user ORDER BY id_transaksi DESC LIMIT 10");
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
    <title>Kritik & Saran | Customer TOKO 33</title>
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
        <a href="kritiksaran.php" class="py-2 border-b-2 border-[#DDF3E5] font-bold">Kritik & Saran</a>
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

<main class="max-w-5xl mx-auto px-4 py-6 flex-1 w-full">
    <section class="bg-[#DDF3E5] rounded-2xl p-6 mb-6 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <span class="inline-block bg-[#176B4D] text-white text-[10px] font-semibold px-3 py-1 rounded-full mb-2">SUARA PELANGGAN TOKO 33</span>
            <h1 class="text-xl md:text-2xl font-bold text-[#176B4D]">Kritik & Saran Anda Membuat Kami Lebih Baik</h1>
            <p class="text-xs text-[#27815F] mt-1">Ceritakan pengalaman belanja Anda agar TOKO 33 terus meningkatkan kualitas produk dan layanan.</p>
        </div>
    </section>

    <div class="grid grid-cols-1 lg:grid-cols-[1.2fr_1fr] gap-6">
        <!-- FORM KRITIK & SARAN -->
        <div class="bg-[#DDF3E5] rounded-2xl p-6">
            <h2 class="text-base font-bold text-[#176B4D] mb-1">Kirim Masukan</h2>
            <p class="text-xs text-[#27815F] mb-5">Isi formulir di bawah ini dengan pengalaman Anda.</p>

            <form action="../api/user/submit_kritiksaran.php" method="POST" class="space-y-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-[#176B4D] mb-1">Nama</label>
                        <input type="text" value="<?= htmlspecialchars($_SESSION['nama_user'] ?? '') ?>" readonly class="w-full h-10 px-3 rounded-lg bg-white border border-gray-300 text-xs text-gray-700">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-[#176B4D] mb-1">Email</label>
                        <input type="email" value="<?= htmlspecialchars($_SESSION['email_user'] ?? '') ?>" readonly class="w-full h-10 px-3 rounded-lg bg-white border border-gray-300 text-xs text-gray-700">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-[#176B4D] mb-1">Kategori Topik</label>
                        <select name="kategori" required class="w-full h-10 px-3 rounded-lg bg-white border border-gray-300 text-xs text-gray-700">
                            <option value="Pelayanan Toko / Kasir">Pelayanan Toko / Kasir</option>
                            <option value="Kualitas & Expired Produk">Kualitas & Expired Produk</option>
                            <option value="Kelengkapan Stok Produk">Kelengkapan Stok Produk</option>
                            <option value="Harga & Pembayaran QRIS">Harga & Pembayaran QRIS</option>
                            <option value="Kebersihan & Kenyamanan">Kebersihan & Kenyamanan</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-[#176B4D] mb-1">ID Transaksi (Opsional)</label>
                        <select name="id_transaksi" class="w-full h-10 px-3 rounded-lg bg-white border border-gray-300 text-xs text-gray-700">
                            <option value="">-- Pilih Transaksi (Opsional) --</option>
                            <?php while ($qMyTrx && $tr = mysqli_fetch_assoc($qMyTrx)): ?>
                                <option value="TRX-<?= str_pad((string)$tr['id_transaksi'], 4, '0', STR_PAD_LEFT) ?>">
                                    #TRX-<?= str_pad((string)$tr['id_transaksi'], 4, '0', STR_PAD_LEFT) ?> (Rp <?= number_format((float)$tr['total_pembayaran'], 0, ',', '.') ?>)
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-[#176B4D] mb-1">Rating Kepuasan</label>
                        <select name="rating" class="w-full h-10 px-3 rounded-lg bg-white border border-gray-300 text-xs text-gray-700 font-semibold">
                            <option value="5">★★★★★ (5 - Sangat Puas)</option>
                            <option value="4">★★★★☆ (4 - Puas)</option>
                            <option value="3">★★★☆☆ (3 - Cukup)</option>
                            <option value="2">★★☆☆☆ (2 - Kurang)</option>
                            <option value="1">★☆☆☆☆ (1 - Kecewa)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-[#176B4D] mb-1">Jenis Masukan</label>
                        <select name="jenis" class="w-full h-10 px-3 rounded-lg bg-white border border-gray-300 text-xs text-gray-700 font-semibold">
                            <option value="saran">💡 Saran</option>
                            <option value="pujian">👏 Pujian</option>
                            <option value="keluhan">⚠️ Keluhan</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-medium text-[#176B4D] mb-1">Pesan Kritik / Saran</label>
                    <textarea name="pesan" rows="4" required placeholder="Tuliskan kritik, saran, atau apresiasi Anda untuk TOKO 33..." class="w-full p-3 rounded-lg bg-white border border-gray-300 text-xs text-gray-700"></textarea>
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="px-6 py-2.5 rounded-lg bg-[#176B4D] hover:bg-[#27815F] text-white text-xs font-semibold cursor-pointer">
                        Kirim Masukan
                    </button>
                </div>
            </form>
        </div>

        <!-- RIWAYAT MASUKAN SAYA -->
        <div class="bg-[#DDF3E5] rounded-2xl p-6">
            <h2 class="text-base font-bold text-[#176B4D] mb-4">Masukan Saya</h2>
            <div class="space-y-4">
                <?php if ($qMyKritik && mysqli_num_rows($qMyKritik) > 0): ?>
                    <?php while ($k = mysqli_fetch_assoc($qMyKritik)):
                        $jBadge = $k['jenis'] === 'keluhan' ? 'bg-[#FCEBE9] text-[#D9534F]' : ($k['jenis'] === 'pujian' ? 'bg-[#DDF3E5] text-[#176B4D]' : 'bg-[#FFF3CD] text-[#B78103]');
                    ?>
                    <div class="bg-white rounded-xl p-4 border border-gray-200">
                        <div class="flex justify-between items-center mb-2">
                            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-semibold uppercase <?= $jBadge ?>">
                                <?= htmlspecialchars($k['jenis']) ?> • <?= str_repeat('★', (int)$k['rating']) ?>
                            </span>
                            <span class="text-[11px] text-gray-400"><?= date('d M Y', strtotime($k['created_at'])) ?></span>
                        </div>
                        <p class="text-xs font-bold text-[#176B4D] mb-1">
                            <?= htmlspecialchars($k['kategori']) ?> <?= !empty($k['id_transaksi']) ? '(' . htmlspecialchars($k['id_transaksi']) . ')' : '' ?>
                        </p>
                        <p class="text-xs text-gray-600 mb-3"><?= nl2br(htmlspecialchars($k['pesan'])) ?></p>

                        <?php if (!empty($k['balasan_admin'])): ?>
                            <div class="bg-[#DDF3E5] rounded-lg p-3 border-l-4 border-[#176B4D]">
                                <p class="text-[11px] font-bold text-[#176B4D]">Balasan Admin TOKO 33</p>
                                <p class="text-xs text-gray-700 mt-1"><?= nl2br(htmlspecialchars($k['balasan_admin'])) ?></p>
                            </div>
                        <?php else: ?>
                            <span class="inline-block text-[10px] text-gray-400 italic">Menunggu tanggapan Admin</span>
                        <?php endif; ?>
                    </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <div class="bg-white rounded-xl p-6 text-center text-xs text-gray-500">
                        Belum ada kritik atau saran yang Anda kirimkan.
                    </div>
                <?php endif; ?>
            </div>
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
