<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link rel="icon" type="image/png" href="../asset/logo.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet">
    <title>Register | Customer</title>
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body>
<div class="w-full min-h-screen bg-[#FFF8E9] flex flex-col">
    <!-- NAVBAR -->
    <header class="bg-[#176B4D] h-11 px-4 sm:px-6 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <img src="../asset/logo.png" alt="Logo Toko 33" class="w-8 h-8 object-contain">
            <h1 class="text-white text-sm font-bold">TOKO 33</h1>
        </div>
        <div class="flex items-center gap-2">
            <a href="login_cust.php" class="bg-[#35A875] hover:bg-[#27815F] text-white text-[9px] font-semibold px-4 py-2 rounded-md">Login</a>
            <a href="register.php" class="bg-white text-[#176B4D] text-[9px] font-semibold px-4 py-2 rounded-md">Register</a>
        </div>
    </header>

    <!-- MAIN REGISTER -->
    <main class="flex-1 flex flex-col items-center justify-center px-4 py-8">
        <div class="bg-[#DDF3E5] w-full max-w-[850px] rounded-xl p-6 sm:p-8 shadow-sm">
            <div class="flex justify-center mb-3">
                <img src="../asset/logo.png" alt="Logo Toko 33" class="w-20 h-20 object-contain">
            </div>

            <div class="flex justify-center mb-5">
                <span class="bg-[#176B4D] text-white text-[10px] font-semibold px-10 py-2 rounded-full">
                    Registrasi Customer
                </span>
            </div>

            <!-- FORM REGISTER -->
            <form action="../api/user/register.php" method="POST" class="space-y-4">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-4">
                    <!-- NAMA PENGGUNA -->
                    <div>
                        <label for="nama" class="block text-[10px] font-semibold text-gray-800 mb-1">Nama Pengguna</label>
                        <input
                            type="text"
                            id="nama"
                            name="nama"
                            required
                            placeholder="Masukkan Nama"
                            class="w-full bg-white border border-gray-200 rounded-md px-3 py-2 text-xs outline-none focus:border-[#35A875]"
                        >
                    </div>

                    <!-- TANGGAL LAHIR -->
                    <div>
                        <label for="tanggal_lahir" class="block text-[10px] font-semibold text-gray-800 mb-1">Tanggal Lahir</label>
                        <input
                            type="date"
                            id="tanggal_lahir"
                            name="tanggal_lahir"
                            required
                            class="w-full bg-white border border-gray-200 rounded-md px-3 py-2 text-xs text-gray-700 outline-none focus:border-[#35A875]"
                        >
                    </div>

                    <!-- ALAMAT -->
                    <div>
                        <label for="alamat" class="block text-[10px] font-semibold text-gray-800 mb-1">Alamat</label>
                        <input
                            type="text"
                            id="alamat"
                            name="alamat"
                            required
                            placeholder="Masukkan Alamat"
                            class="w-full bg-white border border-gray-200 rounded-md px-3 py-2 text-xs outline-none focus:border-[#35A875]"
                        >
                    </div>

                    <!-- NOMOR TELEPON -->
                    <div>
                        <label for="telepon" class="block text-[10px] font-semibold text-gray-800 mb-1">Nomor Telepon</label>
                        <div class="relative">
                            <input
                                type="tel"
                                id="telepon"
                                name="telepon"
                                required
                                placeholder="Masukkan Nomor Telepon"
                                class="w-full bg-white border border-gray-200 rounded-md px-3 py-2 pr-9 text-xs outline-none focus:border-[#35A875]"
                            >
                        </div>
                    </div>

                    <!-- EMAIL -->
                    <div>
                        <label for="email" class="block text-[10px] font-semibold text-gray-800 mb-1">Email</label>
                        <div class="relative">
                            <input
                                type="email"
                                id="email"
                                name="email"
                                required
                                placeholder="Masukkan Email"
                                class="w-full bg-white border border-gray-200 rounded-md px-3 py-2 pr-9 text-xs outline-none focus:border-[#35A875]"
                            >
                        </div>
                    </div>

                    <!-- GENDER -->
                    <div>
                        <label for="gender" class="block text-[10px] font-semibold text-gray-800 mb-1">Gender</label>
                        <select
                            id="gender"
                            name="gender"
                            required
                            class="w-full bg-white border border-gray-200 rounded-md px-3 py-2 text-xs text-gray-700 outline-none focus:border-[#35A875]"
                        >
                            <option value="L">Laki-laki</option>
                            <option value="P">Perempuan</option>
                        </select>
                    </div>

                    <!-- PASSWORD -->
                    <div>
                        <label for="password" class="block text-[10px] font-semibold text-gray-800 mb-1">Password</label>
                        <div class="relative">
                            <input
                                type="password"
                                id="password"
                                name="password"
                                required
                                placeholder="Masukkan Password"
                                class="w-full bg-white border border-gray-200 rounded-md px-3 py-2 pr-9 text-xs outline-none focus:border-[#35A875]"
                            >
                        </div>
                    </div>

                    <!-- KONFIRMASI PASSWORD -->
                    <div>
                        <label for="konfirmasi_password" class="block text-[10px] font-semibold text-gray-800 mb-1">Konfirmasi Password</label>
                        <div class="relative">
                            <input
                                type="password"
                                id="konfirmasi_password"
                                name="konfirmasi_password"
                                required
                                placeholder="Masukkan Konfirmasi Password"
                                class="w-full bg-white border border-gray-200 rounded-md px-3 py-2 pr-9 text-xs outline-none focus:border-[#35A875]"
                            >
                        </div>
                    </div>
                </div>

                <!-- BUTTON SUBMIT -->
                <div class="flex justify-end pt-2">
                    <button
                        type="submit"
                        class="bg-[#176B4D] hover:bg-[#27815F] text-white text-xs font-bold px-10 py-2.5 rounded-md cursor-pointer">
                        Submit
                    </button>
                </div>
            </form>
        </div>

        <a href="../index.php" class="w-full max-w-[850px] mt-3 bg-[#176B4D] hover:bg-[#27815F] text-white text-center text-xs font-bold py-3 rounded-md">
            Kembali ke Beranda
        </a>
    </main>

    <footer class="bg-[#176B4D] min-h-10 flex items-center justify-center px-4 py-3">
        <p class="text-white text-[9px]">© 2026 Toko 33. All Rights Reserved.</p>
    </footer>
</div>
</body>
</html>
