<?php
session_start();
require_once __DIR__ . "/../config/connection.php";
require_once __DIR__ . "/../api/user/cek_expired_user.php";

if (empty($_SESSION['id_user'])) {
    header("Location: login_cust.php");
    exit;
}

$id_user = (int) $_SESSION['id_user'];
$q = trim($_GET['q'] ?? '');
$where = "WHERE t.id_user = $id_user";
if ($q !== '') {
    $qEsc = mysqli_real_escape_string($connect, $q);
    $where .= " AND (t.id_transaksi LIKE '%$qEsc%' OR t.status LIKE '%$qEsc%' OR t.metode_pembayaran LIKE '%$qEsc%')";
}

$resTrx = mysqli_query($connect, "SELECT t.* FROM transaksi t $where ORDER BY t.id_transaksi DESC");
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
    <title>Riwayat Pemesanan | Customer TOKO 33</title>
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

<main class="max-w-5xl mx-auto px-4 py-6 flex-1 w-full">
    <div class="mb-5">
        <h1 class="text-xl font-bold text-[#176B4D]">Riwayat Pemesanan</h1>
        <p class="text-xs text-[#27815F]">Pantau status pesanan dan riwayat belanja Anda di TOKO 33</p>
    </div>

    <form method="GET" action="riwayat_transaksi.php" class="flex flex-col sm:flex-row gap-2 mb-5">
        <input type="text" name="q" value="<?= htmlspecialchars($q) ?>" placeholder="Cari nomor transaksi atau status..." class="flex-1 h-10 px-3 rounded-lg border border-gray-300 bg-white text-xs focus:outline-none focus:border-[#176B4D]">
        <button type="submit" class="h-10 px-5 rounded-lg bg-[#176B4D] text-white text-xs font-semibold cursor-pointer">Cari</button>
    </form>

    <?php if ($resTrx && mysqli_num_rows($resTrx) > 0): ?>
        <?php while ($t = mysqli_fetch_assoc($resTrx)):
            $id_t = (int) $t['id_transaksi'];
            $qDet = mysqli_query($connect, "
                SELECT dt.*, p.nama_produk, p.kategori
                FROM detail_transaksi dt
                JOIN produk p ON dt.id_produk = p.id_produk
                WHERE dt.id_transaksi = $id_t
            ");
            $hcList = [];
            $mnList = [];
            while ($d = mysqli_fetch_assoc($qDet)) {
                if ($d['kategori'] === 'minuman') $mnList[] = $d;
                else $hcList[] = $d;
            }
            $st = $t['status'];
            $statusMap = [
                'menunggu_pembayaran' => ['Menunggu Pembayaran', 'bg-[#FFF3CD] text-[#B78103]'],
                'menunggu_diproses'   => ['Menunggu Konfirmasi FO', 'bg-blue-100 text-blue-700'],
                'diproses'            => ['Sedang Diproses', 'bg-[#FFF3CD] text-[#B78103]'],
                'selesai'             => ['Pesanan Selesai', 'bg-[#DDF3E5] text-[#176B4D]'],
                'ditolak'             => ['Pesanan Ditolak', 'bg-[#FCEBE9] text-[#D9534F]'],
                'expired'             => ['Kedaluwarsa (Expired)', 'bg-gray-200 text-gray-600'],
            ];
            [$stLabel, $stClass] = $statusMap[$st] ?? [$st, 'bg-gray-100 text-gray-700'];
        ?>
        <div class="bg-[#DDF3E5] rounded-2xl p-5 mb-5 shadow-sm">
            <div class="flex flex-wrap justify-between items-center gap-2 mb-3">
                <div class="flex items-center gap-2">
                    <span class="px-3 py-1 rounded-full bg-white text-[#176B4D] text-xs font-semibold">
                        <?= htmlspecialchars($t['metode_pembayaran'] ?: 'Belum Dibayar') ?>
                    </span>
                    <?php if (!empty($t['bukti_transfer'])): ?>
                        <a href="../asset/bukti_transfer/<?= htmlspecialchars(basename($t['bukti_transfer'])) ?>" target="_blank" class="text-xs text-[#176B4D] underline font-medium">Lihat Bukti</a>
                    <?php endif; ?>
                </div>
                <span class="text-xs font-bold text-[#176B4D]">#TRX-<?= str_pad((string)$id_t, 4, '0', STR_PAD_LEFT) ?></span>
            </div>

            <div class="mb-4">
                <h2 class="text-base font-bold text-[#176B4D]"><?= htmlspecialchars($t['nama_customer']) ?></h2>
                <p class="text-xs text-[#27815F]">ID Pelanggan: USR-<?= str_pad((string)$t['id_user'], 3, '0', STR_PAD_LEFT) ?></p>
            </div>

            <div class="bg-white rounded-xl p-3 mb-4 grid grid-cols-1 sm:grid-cols-3 gap-2 text-xs">
                <div>
                    <p class="text-gray-400">Waktu Pemesanan</p>
                    <p class="font-semibold text-gray-700"><?= $t['tanggal_masuk'] ? date('d M Y H:i', strtotime($t['tanggal_masuk'])) : '-' ?></p>
                </div>
                <div>
                    <p class="text-gray-400">Waktu Diproses</p>
                    <p class="font-semibold text-gray-700"><?= $t['tanggal_diproses'] ? date('d M Y H:i', strtotime($t['tanggal_diproses'])) : '-' ?></p>
                </div>
                <div>
                    <p class="text-gray-400">Waktu Selesai / Dibatalkan</p>
                    <p class="font-semibold text-gray-700">
                        <?= $t['tanggal_selesai'] ? date('d M Y H:i', strtotime($t['tanggal_selesai'])) : ($t['tanggal_ditolak'] ? date('d M Y H:i', strtotime($t['tanggal_ditolak'])) : '-') ?>
                    </p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <h3 class="text-xs font-bold text-[#176B4D] mb-2">Home Care</h3>
                    <?php if (empty($hcList)): ?>
                        <div class="bg-white/70 rounded-xl p-3 text-xs text-gray-400">-</div>
                    <?php else: ?>
                        <?php foreach ($hcList as $item): ?>
                        <div class="bg-white rounded-xl p-3 flex justify-between items-center mb-2">
                            <div>
                                <p class="text-xs font-semibold text-gray-800"><?= htmlspecialchars($item['nama_produk']) ?></p>
                                <p class="text-[11px] text-gray-500">Qty: <?= (int)$item['jumlah'] ?> x Rp <?= number_format((float)$item['harga_satuan'], 0, ',', '.') ?></p>
                            </div>
                            <p class="text-xs font-bold text-[#176B4D]">Rp <?= number_format((float)$item['subtotal'], 0, ',', '.') ?></p>
                        </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
                <div>
                    <h3 class="text-xs font-bold text-[#176B4D] mb-2">Minuman</h3>
                    <?php if (empty($mnList)): ?>
                        <div class="bg-white/70 rounded-xl p-3 text-xs text-gray-400">-</div>
                    <?php else: ?>
                        <?php foreach ($mnList as $item): ?>
                        <div class="bg-white rounded-xl p-3 flex justify-between items-center mb-2">
                            <div>
                                <p class="text-xs font-semibold text-gray-800"><?= htmlspecialchars($item['nama_produk']) ?></p>
                                <p class="text-[11px] text-gray-500">Qty: <?= (int)$item['jumlah'] ?> x Rp <?= number_format((float)$item['harga_satuan'], 0, ',', '.') ?></p>
                            </div>
                            <p class="text-xs font-bold text-[#176B4D]">Rp <?= number_format((float)$item['subtotal'], 0, ',', '.') ?></p>
                        </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>

            <div class="bg-white rounded-xl p-4 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
                <div>
                    <p class="text-xs text-gray-500">Total Pembayaran</p>
                    <p class="text-base font-bold text-[#176B4D]">Rp <?= number_format((float)$t['total_pembayaran'], 0, ',', '.') ?></p>
                </div>
                <div class="flex items-center gap-2">
                    <span class="px-4 py-2 rounded-lg text-xs font-semibold <?= $stClass ?>"><?= $stLabel ?></span>
                    <?php if ($st === 'menunggu_pembayaran'): ?>
                        <a href="pembayaran.php?id=<?= $id_t ?>" class="px-4 py-2 rounded-lg bg-[#176B4D] hover:bg-[#27815F] text-white text-xs font-semibold">Bayar Sekarang</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php endwhile; ?>
    <?php else: ?>
        <div class="bg-white rounded-2xl p-8 text-center text-gray-500 text-sm">
            Belum ada riwayat transaksi.
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
