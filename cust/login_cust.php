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
    <title>Login | Customer Access</title>
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
            <a href="#" class="bg-white text-[#176B4D] text-[9px] font-semibold px-4 py-2 rounded-md">Login</a>
            <a href="register.php" class="bg-[#35A875] hover:bg-[#27815F] text-white text-[9px] font-semibold px-4 py-2 rounded-md">Register</a>
        </div>
    </header>

    <!-- MAIN LOGIN -->
    <main class="flex-1 flex items-center justify-center px-4 py-8">
        <div class="bg-[#DDF3E5] w-full max-w-[400px] rounded-xl p-6 sm:p-8 shadow-sm">
            <div class="flex justify-center mb-3">
                <img src="../asset/logo.png" alt="Logo Toko 33" class="w-20 h-20 object-contain">
            </div>

            <div class="flex justify-center mb-5">
                <span class="bg-[#176B4D] text-white text-[10px] font-semibold px-8 py-2 rounded-full">
                    Customer Access
                </span>
            </div>

            <!-- FORM LOGIN -->
            <form action="../api/user/login_cust.php" method="POST" class="space-y-4">
                <div>
                    <label for="email" class="block text-[10px] font-semibold text-gray-800 mb-1">Email</label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        required
                        placeholder="Masukkan Email"
                        class="w-full bg-white border border-gray-200 rounded-md px-3 py-2 text-xs outline-none focus:border-[#35A875]"
                    >
                </div>

                <div>
                    <label for="password" class="block text-[10px] font-semibold text-gray-800 mb-1">Password</label>
                    <div class="relative">
                        <input
                            type="password"
                            id="password"
                            name="password"
                            required
                            placeholder="••••"
                            class="w-full bg-white border border-gray-200 rounded-md px-3 py-2 pr-10 text-xs outline-none focus:border-[#35A875]"
                        >
                        <button
                            type="button"
                            id="togglePassword"
                            class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 flex items-center justify-center cursor-pointer"
                            aria-label="Tampilkan password"
                        >
                            <span id="eyeOpen" class="hidden">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                </svg>
                            </span>
                            <span id="eyeClosed">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88" />
                                </svg>
                            </span>
                        </button>
                    </div>
                </div>

                <div class="flex items-center justify-between gap-2 pt-1">
                    <div class="flex flex-col gap-2">
                        <a href="../admin/login_admin.php" class="text-[10px] text-[#176B4D] hover:underline font-medium">Login as Admin</a>
                        <a href="../fo/login_fo.php" class="text-[10px] text-[#176B4D] hover:underline font-medium">Login as Front Office</a>
                    </div>

                    <button
                        type="submit"
                        class="bg-[#176B4D] hover:bg-[#27815F] text-white text-xs font-bold px-8 py-2.5 rounded-md cursor-pointer">
                        Login
                    </button>
                </div>

                <div class="flex justify-between items-center pt-2">
                    <a href="../cust/resetpassword.php" class="text-[10px] text-[#176B4D] hover:underline">Lupa Password?</a>
                    <a href="../index.php" class="text-[10px] text-[#176B4D] hover:underline">Back to Home</a>
                </div>
            </form>
        </div>
    </main>

    <footer class="bg-[#176B4D] min-h-10 flex items-center justify-center px-4 py-3">
        <p class="text-white text-[9px]">© 2026 Toko 33. All Rights Reserved.</p>
    </footer>
</div>

<script>
    const passwordInput = document.getElementById("password");
    const togglePassword = document.getElementById("togglePassword");
    const eyeOpen = document.getElementById("eyeOpen");
    const eyeClosed = document.getElementById("eyeClosed");

    togglePassword.addEventListener("click", function () {
        if (passwordInput.type === "password") {
            passwordInput.type = "text";
            eyeOpen.classList.remove("hidden");
            eyeClosed.classList.add("hidden");
        } else {
            passwordInput.type = "password";
            eyeOpen.classList.add("hidden");
            eyeClosed.classList.remove("hidden");
        }
    });
</script>
</body>
</html>
