<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link rel="icon" type="image/png" href="../asset/logo.png">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet">
    <title>Reset Password | Customer</title>
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="bg-[#FFF8E9] min-h-screen flex flex-col">
<header class="w-full bg-[#176B4D] text-white px-5 h-14 flex items-center justify-between">
    <div class="flex items-center gap-2">
        <img src="../asset/logo.png" alt="Logo" class="w-8 h-8 object-contain">
        <span class="font-bold text-sm">TOKO 33</span>
    </div>
    <a href="login_cust.php" class="text-xs font-semibold hover:underline">Kembali ke Login</a>
</header>

<main class="flex-1 px-5 md:px-10 py-8 flex justify-center items-center">
    <div class="bg-white border border-gray-200 rounded-2xl p-8 w-full max-w-lg shadow-sm">
        <div class="flex justify-center mb-4">
            <div class="w-16 h-16 bg-[#DDF3E5] rounded-full flex items-center justify-center">
                <img src="../asset/logo.png" alt="Logo Toko 33" class="w-14 h-14 object-contain">
            </div>
        </div>

        <div class="text-center mb-7">
            <h1 class="text-2xl font-bold text-[#176B4D]">Reset Password</h1>
            <p class="text-xs text-gray-500 mt-2">Masukkan email akun Anda beserta password baru</p>
        </div>

        <form action="../api/user/reset_password.php" method="POST">
            <div class="mb-5">
                <label class="block text-xs font-semibold mb-2">Email</label>
                <input type="email" name="email" required placeholder="Masukkan email terdaftar" class="w-full border border-[#35A875] rounded-xl px-4 py-3.5 text-xs outline-none focus:ring-2 focus:ring-green-100">
            </div>

            <div class="mb-5">
                <label class="block text-xs font-semibold mb-2">Password Baru</label>
                <input type="password" name="password_baru" required placeholder="Masukkan password baru" class="w-full border border-[#35A875] rounded-xl px-4 py-3.5 text-xs outline-none focus:ring-2 focus:ring-green-100">
            </div>

            <div class="mb-5">
                <label class="block text-xs font-semibold mb-2">Konfirmasi Password</label>
                <input type="password" name="konfirmasi_password" required placeholder="Konfirmasi password baru" class="w-full border border-[#35A875] rounded-xl px-4 py-3.5 text-xs outline-none focus:ring-2 focus:ring-green-100">
            </div>

            <button type="submit" class="w-full bg-[#176B4D] hover:bg-[#27815F] text-white rounded-lg py-3 mt-3 text-xs font-bold cursor-pointer">
                Simpan Perubahan
            </button>

            <div class="text-center mt-5">
                <a href="login_cust.php" class="text-xs text-[#176B4D] font-semibold hover:underline">« Kembali</a>
            </div>
        </form>
    </div>
</main>

<footer class="bg-[#176B4D] text-white px-5 py-3 text-center text-xs">
    <p>© 2026 Toko 33. All Rights Reserved.</p>
</footer>
</body>
</html>
