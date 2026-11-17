<?php
session_start();
require_once __DIR__ . "/../config/connection.php";

if (!isset($_SESSION['id_akun']) || ($_SESSION['role'] ?? '') !== 'admin') {
    header("Location: login_admin.php");
    exit;
}

$id_produk = (int) ($_GET['id'] ?? 0);
$p = mysqli_fetch_assoc(mysqli_query($connect, "SELECT * FROM produk WHERE id_produk = $id_produk"));
if (!$p) {
    header("Location: stock_homecare.php");
    exit;
}
$foto = !empty($p['foto_produk']) ? "../asset/produk/" . basename($p['foto_produk']) : "../asset/wipol.png";
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
    <title>Edit Produk | Admin TOKO 33</title>
</head>
<body class="m-0 p-0 w-full min-h-screen overflow-x-hidden bg-[#F5F5F5]">
    <div class="w-full min-h-screen">
        <header class="bg-[#DDF3E5] sticky top-0 z-30 flex items-center justify-between px-4 h-14 w-full shadow-xs print:hidden">
            <div class="flex items-center gap-3">
                <img src="../asset/logo.png" alt="Logo Toko 33" class="w-10 h-10 object-contain">
                <h1 class="text-[#176B4D] text-sm font-bold">TOKO 33 • ADMIN PANEL</h1>
            </div>
            <a href="profile.php" class="flex items-center gap-2 hover:opacity-85" title="Lihat Profile Admin">
                <?php
                $fotoAdm = $_SESSION['foto_profile'] ?? 'default-profile.png';
                $fotoAdmPath = "../asset/profile/" . basename($fotoAdm);
                if (!file_exists(__DIR__ . "/../asset/profile/" . basename($fotoAdm))) {
                    $fotoAdmPath = "../asset/default-profile.png";
                }
                ?>
                <img src="<?= htmlspecialchars($fotoAdmPath) ?>" alt="Admin" class="w-8 h-8 rounded-full object-cover border border-[#176B4D] bg-white">
                <span class="text-xs font-semibold text-[#176B4D]"><?= htmlspecialchars($_SESSION['nama'] ?? 'Admin') ?></span>
            </a>
        </header>
        <div class="grid grid-cols-[55px_minmax(0,1fr)] sm:grid-cols-[65px_minmax(0,1fr)] md:grid-cols-[200px_minmax(0,1fr)] lg:grid-cols-[230px_minmax(0,1fr)]">
            <aside class="bg-[#176B4D] text-white flex flex-col justify-between py-4 px-1 sm:px-2 md:px-3 sticky top-14 h-[calc(100vh-56px)] overflow-y-auto shrink-0 print:hidden">
                <nav class="flex flex-col gap-2">
                    
                    <a href="dashboard.php" title="Dashboard" class="flex items-center justify-center md:justify-start gap-3 hover:bg-[#27815F] rounded-lg px-2 md:px-3 py-3">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6"><path stroke-linecap="round" stroke-linejoin="round" d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" /></svg>
                        <span class="hidden md:inline text-xs">Dashboard</span>
                    </a>

                    <a href="stock_homecare.php" title="Katalog" class="flex items-center justify-center md:justify-start gap-3 bg-[#35A875] font-semibold rounded-lg px-2 md:px-3 py-3">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 0 1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 5.513 7.5h12.974c.576 0 1.059.435 1.119 1.007ZM8.625 10.5a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm7.5 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" /></svg>
                        <span class="hidden md:inline text-xs">Katalog</span>
                    </a>

                    <a href="daftar_fo.php" title="Daftar Front Office" class="flex items-center justify-center md:justify-start gap-3 hover:bg-[#27815F] rounded-lg px-2 md:px-3 py-3">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6"><path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" /></svg>
                        <span class="hidden md:inline text-xs">Daftar Front Office</span>
                    </a>

                    <a href="daftar_pengguna.php" title="Daftar Pengguna" class="flex items-center justify-center md:justify-start gap-3 hover:bg-[#27815F] rounded-lg px-2 md:px-3 py-3">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" /></svg>
                        <span class="hidden md:inline text-xs">Daftar Pengguna</span>
                    </a>

                    <a href="riwayat_transaksi.php" title="Riwayat Transaksi" class="flex items-center justify-center md:justify-start gap-3 hover:bg-[#27815F] rounded-lg px-2 md:px-3 py-3">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
                        <span class="hidden md:inline text-xs">Riwayat Transaksi</span>
                    </a>

                    <a href="rekap_penjualan.php" title="Rekap Penjualan" class="flex items-center justify-center md:justify-start gap-3 hover:bg-[#27815F] rounded-lg px-2 md:px-3 py-3">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6"><path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z" /></svg>
                        <span class="hidden md:inline text-xs">Rekap Penjualan</span>
                    </a>

                    <a href="monitoring_stock.php" title="Monitoring Stok" class="flex items-center justify-center md:justify-start gap-3 hover:bg-[#27815F] rounded-lg px-2 md:px-3 py-3">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6"><path stroke-linecap="round" stroke-linejoin="round" d="m20.25 7.5-.625 10.632a2.25 2.25 0 0 1-2.247 2.118H6.622a2.25 2.25 0 0 1-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125Z" /></svg>
                        <span class="hidden md:inline text-xs">Monitoring Stok</span>
                    </a>

                    <a href="kritiksaran.php" title="Kritik & Saran" class="flex items-center justify-center md:justify-start gap-3 hover:bg-[#27815F] rounded-lg px-2 md:px-3 py-3">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6"><path stroke-linecap="round" stroke-linejoin="round" d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.123 2.994 2.707 3.227 1.129.166 2.27.293 3.423.379.35.026.67.21.865.501L12 21l2.755-4.133a1.14 1.14 0 0 1 .865-.501 48.172 48.172 0 0 0 3.423-.379c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0 0 12 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018Z" /></svg>
                        <span class="hidden md:inline text-xs">Kritik & Saran</span>
                    </a>

                    <a href="profile.php" title="Profile" class="flex items-center justify-center md:justify-start gap-3 hover:bg-[#27815F] rounded-lg px-2 md:px-3 py-3">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6"><path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0 0 12 15.75a7.488 7.488 0 0 0-5.982 2.975m11.963 0a9 9 0 1 0-11.963 0m11.963 0A8.966 8.966 0 0 1 12 21a8.966 8.966 0 0 1-5.982-2.275M15 9.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" /></svg>
                        <span class="hidden md:inline text-xs">Profile</span>
                    </a>
                </nav>
                <a href="../api/admin/logout_admin.php" title="Logout" class="flex items-center justify-center md:justify-start gap-3 bg-[#FCEBE9] text-[#D9534F] rounded-lg px-2 md:px-3 py-3 mt-5">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 9V5.25A2.25 2.25 0 0 1 10.5 3h6a2.25 2.25 0 0 1 2.25 2.25v13.5A2.25 2.25 0 0 1 16.5 21h-6a2.25 2.25 0 0 1-2.25-2.25V15m-3 0-3-3m0 0 3-3m-3 3H15" />
                    </svg>
                    <span class="hidden md:inline text-xs font-semibold">Logout</span>
                </a>
            </aside>
            <main class="bg-[#FFF8E9] p-4 sm:p-5 md:p-6 min-w-0">
<div class="max-w-4xl mx-auto">
    <div class="mb-5">
        <h2 class="text-xl sm:text-2xl font-bold text-[#176B4D]">Edit Produk • <?= htmlspecialchars($p['nama_produk']) ?></h2>
        <p class="text-xs sm:text-sm text-[#27815F]">Perbarui detail, harga, stok, atau ganti foto produk di <code>asset/produk/</code></p>
    </div>

    <section class="bg-[#DDF3E5] rounded-2xl p-5 sm:p-6 shadow-sm">
        <form action="../api/admin/edit_produk.php" method="POST" enctype="multipart/form-data" class="space-y-4 text-xs">
            <input type="hidden" name="id_produk" value="<?= (int)$p['id_produk'] ?>">

            <div class="flex items-center gap-4 bg-white p-3 rounded-xl border border-gray-200 mb-2">
                <img src="<?= htmlspecialchars($foto) ?>" class="w-16 h-16 object-contain rounded-lg bg-[#F5F5F5] p-1" alt="">
                <div class="flex-1">
                    <label class="block font-semibold text-[#176B4D] mb-1">Ganti Foto Produk (Opsional)</label>
                    <input type="file" name="foto_produk" accept=".jpg,.jpeg,.png,.webp" class="w-full text-xs">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block font-semibold text-[#176B4D] mb-1">Nama Produk *</label>
                    <input type="text" name="nama_produk" value="<?= htmlspecialchars($p['nama_produk']) ?>" required class="w-full h-10 px-3 rounded-lg bg-white border border-gray-300">
                </div>
                <div>
                    <label class="block font-semibold text-[#176B4D] mb-1">Kode Produk</label>
                    <input type="text" name="kode_produk" value="<?= htmlspecialchars($p['kode_produk'] ?? '') ?>" class="w-full h-10 px-3 rounded-lg bg-white border border-gray-300">
                </div>
                <div>
                    <label class="block font-semibold text-[#176B4D] mb-1">Kategori</label>
                    <select name="kategori" class="w-full h-10 px-3 rounded-lg bg-white border border-gray-300">
                        <option value="homecare" <?= $p['kategori'] === 'homecare' ? 'selected' : '' ?>>Home Care</option>
                        <option value="minuman" <?= $p['kategori'] === 'minuman' ? 'selected' : '' ?>>Minuman</option>
                    </select>
                </div>
                <div>
                    <label class="block font-semibold text-[#176B4D] mb-1">Harga (Rp)</label>
                    <input type="number" name="harga" value="<?= (int)$p['harga'] ?>" required class="w-full h-10 px-3 rounded-lg bg-white border border-gray-300">
                </div>
                <div>
                    <label class="block font-semibold text-[#176B4D] mb-1">Stok Saat Ini</label>
                    <input type="number" name="stok" value="<?= (int)$p['stok'] ?>" required class="w-full h-10 px-3 rounded-lg bg-white border border-gray-300">
                </div>
                <div>
                    <label class="block font-semibold text-[#176B4D] mb-1">Stok Minimum</label>
                    <input type="number" name="stok_minimum" value="<?= (int)($p['stok_minimum'] ?? 10) ?>" required class="w-full h-10 px-3 rounded-lg bg-white border border-gray-300">
                </div>
                <div>
                    <label class="block font-semibold text-[#176B4D] mb-1">Tanggal Expired</label>
                    <input type="date" name="tanggal_exp" value="<?= htmlspecialchars($p['tanggal_exp'] ?? '') ?>" class="w-full h-10 px-3 rounded-lg bg-white border border-gray-300">
                </div>
                <div class="flex items-center pt-6">
                    <label class="flex items-center gap-2 font-semibold text-[#176B4D]">
                        <input type="checkbox" name="lacak_exp" value="1" <?= !empty($p['lacak_exp']) ? 'checked' : '' ?>>
                        Lacak Tanggal Kedaluwarsa
                    </label>
                </div>
                <div class="md:col-span-2">
                    <label class="block font-semibold text-[#176B4D] mb-1">Deskripsi Produk</label>
                    <textarea name="deskripsi" rows="3" class="w-full p-3 rounded-lg bg-white border border-gray-300"><?= htmlspecialchars($p['deskripsi'] ?? '') ?></textarea>
                </div>
            </div>

            <div class="flex justify-between items-center pt-3">
                <a href="../api/admin/hapus_produk.php?id=<?= (int)$p['id_produk'] ?>" onclick="return confirm('Hapus produk ini?')" class="px-5 py-2.5 rounded-lg bg-[#FCEBE9] text-[#D9534F] font-semibold">Hapus Produk</a>
                <div class="flex gap-3">
                    <a href="stock_<?= $p['kategori'] === 'minuman' ? 'minuman' : 'homecare' ?>.php" class="px-5 py-2.5 rounded-lg bg-white border border-gray-300 text-gray-700 font-semibold">Batal</a>
                    <button type="submit" class="px-6 py-2.5 rounded-lg bg-[#176B4D] hover:bg-[#27815F] text-white font-semibold cursor-pointer">Simpan Perubahan</button>
                </div>
            </div>
        </form>
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

