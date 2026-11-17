<?php
session_start();
require_once __DIR__ . "/../config/connection.php";
require_once __DIR__ . "/../api/fo/cek_expired.php";

if (!isset($_SESSION['id_akun']) || ($_SESSION['role'] ?? '') !== 'fo') {
    header("Location: login_fo.php");
    exit;
}

$id_fo = (int) $_SESSION['id_akun'];
$foData = mysqli_fetch_assoc(mysqli_query($connect, "SELECT * FROM akun WHERE id_akun = $id_fo"));

$cMasuk = (int) mysqli_fetch_row(mysqli_query($connect, "SELECT COUNT(*) FROM transaksi WHERE status = 'menunggu_diproses'"))[0];
$cProses = (int) mysqli_fetch_row(mysqli_query($connect, "SELECT COUNT(*) FROM transaksi WHERE status = 'diproses'"))[0];
$cSelesai = (int) mysqli_fetch_row(mysqli_query($connect, "SELECT COUNT(*) FROM transaksi WHERE status = 'selesai'"))[0];
$cBatal = (int) mysqli_fetch_row(mysqli_query($connect, "SELECT COUNT(*) FROM transaksi WHERE status IN ('ditolak', 'expired')"))[0];

$qTerbaru = mysqli_query($connect, "
    SELECT t.*,
           (SELECT GROUP_CONCAT(CONCAT(p.nama_produk, ' x', dt.jumlah) SEPARATOR ', ')
            FROM detail_transaksi dt JOIN produk p ON dt.id_produk = p.id_produk
            WHERE dt.id_transaksi = t.id_transaksi) AS ringkasan_item
    FROM transaksi t
    ORDER BY t.id_transaksi DESC
    LIMIT 6
");

$qHariIni = mysqli_fetch_assoc(mysqli_query($connect, "
    SELECT
        COALESCE(SUM(CASE WHEN status = 'selesai' THEN total_pembayaran ELSE 0 END), 0) AS total_nominal,
        SUM(CASE WHEN status IN ('menunggu_diproses','diproses','selesai') THEN 1 ELSE 0 END) AS lunas_count,
        SUM(CASE WHEN status = 'menunggu_pembayaran' THEN 1 ELSE 0 END) AS tunggu_bayar_count
    FROM transaksi
    WHERE DATE(tanggal_masuk) = CURDATE()
"));
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
    <title>Dashboard | Front Office TOKO 33</title>
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
                    
                    <a href="dashboard.php" title="Dashboard" class="flex items-center justify-center md:justify-start gap-3 bg-[#35A875] font-semibold rounded-lg px-2 md:px-3 py-3">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6"><path stroke-linecap="round" stroke-linejoin="round" d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" /></svg>
                        <span class="hidden md:inline text-xs">Dashboard</span>
                    </a>

                    <a href="katalog_homecare.php" title="Katalog" class="flex items-center justify-center md:justify-start gap-3 hover:bg-[#27815F] rounded-lg px-2 md:px-3 py-3">
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
<section class="mb-6">
    <h2 class="text-xl sm:text-2xl font-bold text-[#176B4D]">Selamat Datang, <?= htmlspecialchars($foData['nama'] ?? 'Front Office') ?>!</h2>
    <p class="text-xs sm:text-sm text-[#27815F]">Kelola pesanan pelanggan, transaksi kasir, dan jadwal operasional TOKO 33 hari ini.</p>
</section>

<!-- CARD STATISTIK -->
<section class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 mb-6">
    <div class="bg-[#DDF3E5] rounded-2xl p-4 shadow-sm">
        <span class="text-xs text-[#176B4D] font-medium">Pesanan Masuk</span>
        <h3 class="text-2xl font-bold text-[#176B4D] mt-2"><?= $cMasuk ?></h3>
        <p class="text-xs text-[#27815F] mt-1">Menunggu konfirmasi FO</p>
    </div>
    <div class="bg-[#DDF3E5] rounded-2xl p-4 shadow-sm">
        <span class="text-xs text-[#176B4D] font-medium">Sedang Diproses</span>
        <h3 class="text-2xl font-bold text-[#176B4D] mt-2"><?= $cProses ?></h3>
        <p class="text-xs text-[#27815F] mt-1">Sedang disiapkan</p>
    </div>
    <div class="bg-[#DDF3E5] rounded-2xl p-4 shadow-sm">
        <span class="text-xs text-[#176B4D] font-medium">Selesai</span>
        <h3 class="text-2xl font-bold text-[#176B4D] mt-2"><?= $cSelesai ?></h3>
        <p class="text-xs text-[#27815F] mt-1">Transaksi tuntas</p>
    </div>
    <div class="bg-[#DDF3E5] rounded-2xl p-4 shadow-sm">
        <span class="text-xs text-[#176B4D] font-medium">Ditolak / Expired</span>
        <h3 class="text-2xl font-bold text-[#D9534F] mt-2"><?= $cBatal ?></h3>
        <p class="text-xs text-[#27815F] mt-1">Pesanan batal</p>
    </div>
</section>

<section class="grid grid-cols-1 xl:grid-cols-3 gap-6">
    <!-- TABEL PESANAN TERBARU -->
    <div class="xl:col-span-2 bg-[#DDF3E5] rounded-2xl p-4 sm:p-5 shadow-sm overflow-hidden">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h3 class="text-base sm:text-lg font-bold text-[#176B4D]">Pesanan Terbaru</h3>
                <p class="text-xs text-[#27815F]">Daftar pesanan pelanggan terkini</p>
            </div>
            <a href="pesanan_masuk.php" class="text-xs font-semibold bg-[#35A875] text-white px-3 py-2 rounded-lg">Lihat Semua</a>
        </div>
        <div class="overflow-x-auto bg-white rounded-xl p-3">
            <table class="w-full text-left border-collapse min-w-[520px]">
                <thead>
                    <tr class="border-b text-xs text-[#176B4D]">
                        <th class="py-3 px-2 whitespace-nowrap align-middle">ID Pesanan</th>
                        <th class="py-3 px-2 whitespace-nowrap align-middle">Pelanggan</th>
                        <th class="py-3 px-2 whitespace-nowrap align-middle">Produk</th>
                        <th class="py-3 px-2 whitespace-nowrap align-middle">Total</th>
                        <th class="py-3 px-2 whitespace-nowrap align-middle">Status</th>
                    </tr>
                </thead>
                <tbody class="text-xs text-gray-700">
                    <?php while ($qTerbaru && $r = mysqli_fetch_assoc($qTerbaru)): ?>
                    <tr class="border-b">
                        <td class="py-3 px-2 font-semibold whitespace-nowrap align-middle">#TRX-<?= str_pad((string)$r['id_transaksi'], 4, '0', STR_PAD_LEFT) ?></td>
                        <td class="py-3 px-2 whitespace-nowrap align-middle"><?= htmlspecialchars($r['nama_customer']) ?></td>
                        <td class="py-3 px-2 max-w-[200px] truncate whitespace-nowrap align-middle"><?= htmlspecialchars($r['ringkasan_item'] ?? '-') ?></td>
                        <td class="py-3 px-2 font-semibold whitespace-nowrap align-middle">Rp <?= number_format((float)$r['total_pembayaran'], 0, ',', '.') ?></td>
                        <td class="py-3 px-2 whitespace-nowrap align-middle">
                            <span class="inline-flex items-center justify-center bg-[#DDF3E5] text-[#176B4D] px-3 py-1 rounded-full font-semibold whitespace-nowrap"><?= htmlspecialchars($r['status']) ?></span>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- PANEL KANAN -->
    <div class="flex flex-col gap-6">
        <div class="bg-[#DDF3E5] rounded-2xl p-4 sm:p-5 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h3 class="text-base font-bold text-[#176B4D]">Jadwal Kerja Saya</h3>
                    <p class="text-xs text-[#27815F]">Shift aktif minggu ini</p>
                </div>
                <a href="jadwal_saya.php" class="text-xs font-semibold text-[#176B4D] underline">Detail</a>
            </div>
            <div class="bg-white rounded-xl p-3 text-xs space-y-2">
                <div class="flex justify-between">
                    <span class="text-gray-500">Hari Kerja</span>
                    <span class="font-bold text-[#176B4D] uppercase"><?= htmlspecialchars(($foData['hari_kerja_mulai'] ?? 'senin') . ' - ' . ($foData['hari_kerja_selesai'] ?? 'jumat')) ?></span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-500">Jam Operasional</span>
                    <span class="font-bold text-[#176B4D]"><?= substr((string)($foData['jam_mulai'] ?? '08:00'), 0, 5) ?> - <?= substr((string)($foData['jam_selesai'] ?? '16:00'), 0, 5) ?> WIB</span>
                </div>
            </div>
        </div>

        <div class="bg-[#DDF3E5] rounded-2xl p-4 sm:p-5 shadow-sm">
            <h3 class="text-base font-bold text-[#176B4D] mb-1">Ringkasan Transaksi</h3>
            <p class="text-xs text-[#27815F] mb-4">Rekapitulasi transaksi aktif</p>
            <div class="flex flex-col gap-3 text-xs">
                <div class="bg-white rounded-xl p-3 flex justify-between">
                    <span class="text-gray-600">Total pesanan masuk</span>
                    <span class="font-bold text-[#176B4D]"><?= $cMasuk ?> Transaksi</span>
                </div>
                <div class="bg-white rounded-xl p-3 flex justify-between">
                    <span class="text-gray-600">Sedang diproses</span>
                    <span class="font-bold text-[#27815F]"><?= $cProses ?> Transaksi</span>
                </div>
                <div class="bg-white rounded-xl p-3 flex justify-between">
                    <span class="text-gray-600">Transaksi selesai</span>
                    <span class="font-bold text-[#176B4D]"><?= $cSelesai ?> Transaksi</span>
                </div>
            </div>
        </div>
    </div>
</section>

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

