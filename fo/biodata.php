<?php
session_start();
require_once __DIR__ . "/../config/connection.php";

if (!isset($_SESSION['id_akun']) || ($_SESSION['role'] ?? '') !== 'fo') {
    header("Location: login_fo.php");
    exit;
}

$id_fo = (int) $_SESSION['id_akun'];
$fo = mysqli_fetch_assoc(mysqli_query($connect, "SELECT * FROM akun WHERE id_akun = $id_fo"));
$foto = !empty($fo['foto_profile']) ? "../asset/profile/" . basename($fo['foto_profile']) : "../asset/default-profile.png";
$pending = mysqli_fetch_assoc(mysqli_query($connect, "SELECT * FROM pengajuan_perubahan WHERE id_akun = $id_fo AND status = 'pending' ORDER BY id_pengajuan DESC LIMIT 1"));
$pesan = $_GET['pesan'] ?? '';
$err = $_GET['error'] ?? '';
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
    <title>Profile Saya | Front Office TOKO 33</title>
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

                    <a href="profile.php" title="Profile" class="flex items-center justify-center md:justify-start gap-3 bg-[#35A875] font-semibold rounded-lg px-2 md:px-3 py-3">
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
<div class="max-w-4xl mx-auto">
    <?php if ($pesan === 'foto_berhasil'): ?>
        <div class="bg-[#DDF3E5] border border-[#35A875] text-[#176B4D] px-4 py-3 rounded-xl text-xs font-semibold mb-4">Foto profil berhasil diperbarui ke folder <code>asset/profile</code>!</div>
    <?php elseif ($pesan === 'pengajuan_terkirim'): ?>
        <div class="bg-[#DDF3E5] border border-[#35A875] text-[#176B4D] px-4 py-3 rounded-xl text-xs font-semibold mb-4">Pengajuan perubahan biodata berhasil dikirim dan menunggu persetujuan Admin.</div>
    <?php elseif ($err === 'masih_pending'): ?>
        <div class="bg-[#FFF3CD] text-[#B78103] px-4 py-3 rounded-xl text-xs font-semibold mb-4">Anda masih memiliki pengajuan perubahan yang berstatus Pending.</div>
    <?php endif; ?>

    <section class="bg-[#DDF3E5] rounded-2xl p-6 shadow-sm">
        <div class="flex flex-col sm:flex-row items-center justify-between gap-4 mb-6">
            <div class="flex items-center gap-4">
                <img src="<?= htmlspecialchars($foto) ?>" class="w-20 h-20 rounded-full object-cover border-2 border-[#176B4D] bg-white" alt="Foto Profil">
                <div>
                    <h2 class="text-xl font-bold text-[#176B4D]"><?= htmlspecialchars($fo['nama']) ?></h2>
                    <p class="text-xs text-[#27815F]">ID: FO-<?= str_pad((string)$fo['id_akun'], 3, '0', STR_PAD_LEFT) ?></p>
                    <form action="../api/fo/edit_fo.php" method="POST" enctype="multipart/form-data" class="mt-2 flex items-center gap-2">
                        <input type="file" name="foto_profile" required accept=".jpg,.jpeg,.png,.webp" class="text-[11px] bg-white rounded px-2 py-1 border border-gray-300">
                        <button type="submit" class="px-3 py-1 rounded bg-[#176B4D] text-white text-[11px] font-semibold cursor-pointer">Upload Foto</button>
                    </form>
                </div>
            </div>
            <?php if ($pending): ?>
                <span class="px-4 py-2 rounded-lg bg-[#FFF3CD] text-[#B78103] text-xs font-semibold">Pengajuan Perubahan: Pending</span>
            <?php endif; ?>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs">
            <div>
                <label class="block font-medium text-[#176B4D] mb-1">Nama Lengkap</label>
                <div class="h-10 px-3 rounded-lg bg-white border border-gray-300 flex items-center"><?= htmlspecialchars($fo['nama']) ?></div>
            </div>
            <div>
                <label class="block font-medium text-[#176B4D] mb-1">Email</label>
                <div class="h-10 px-3 rounded-lg bg-white border border-gray-300 flex items-center"><?= htmlspecialchars($fo['email']) ?></div>
            </div>
            <div>
                <label class="block font-medium text-[#176B4D] mb-1">Nomor Telepon</label>
                <div class="h-10 px-3 rounded-lg bg-white border border-gray-300 flex items-center"><?= htmlspecialchars($fo['no_telp'] ?? '-') ?></div>
            </div>
            <div>
                <label class="block font-medium text-[#176B4D] mb-1">Gender</label>
                <div class="h-10 px-3 rounded-lg bg-white border border-gray-300 flex items-center"><?= $fo['gender'] === 'P' ? 'Perempuan' : 'Laki-laki' ?></div>
            </div>
            <div>
                <label class="block font-medium text-[#176B4D] mb-1">Hari Kerja</label>
                <div class="h-10 px-3 rounded-lg bg-white border border-gray-300 flex items-center capitalize"><?= htmlspecialchars(($fo['hari_kerja_mulai'] ?? 'senin') . ' - ' . ($fo['hari_kerja_selesai'] ?? 'jumat')) ?></div>
            </div>
            <div>
                <label class="block font-medium text-[#176B4D] mb-1">Jam Kerja</label>
                <div class="h-10 px-3 rounded-lg bg-white border border-gray-300 flex items-center"><?= substr((string)$fo['jam_mulai'], 0, 5) ?> - <?= substr((string)$fo['jam_selesai'], 0, 5) ?> WIB</div>
            </div>
            <div class="md:col-span-2">
                <label class="block font-medium text-[#176B4D] mb-1">Alamat Lengkap</label>
                <div class="p-3 rounded-lg bg-white border border-gray-300"><?= htmlspecialchars($fo['alamat'] ?? '-') ?></div>
            </div>
        </div>

        <div class="flex justify-end mt-6">
            <a href="edit_biodata.php" class="px-6 py-2.5 rounded-lg bg-[#176B4D] hover:bg-[#27815F] text-white text-xs font-semibold">Ajukan Perubahan Data</a>
        </div>
    </section>
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

