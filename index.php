<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>

    <link rel="icon" type="image/x-icon" href="../asset/logo.png">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>

    <title>Index</title>
</head>

<body class="bg-[#FFF8E9] min-h-screen flex flex-col">

<!-- NAVBAR -->
<header class="w-full bg-[#176B4D] text-white px-5 h-14 grid grid-cols-[1fr_auto_1fr] items-center">

    <!-- LOGO KIRI -->
    <div class="flex items-center gap-2 justify-self-start">
        <img src="asset/logo.png"
             alt="Logo"
             class="w-13 h-13 object-contain">

        <span class="font-bold text-sm">TOKO 33</span>
    </div>

     <!-- NAVBAR BUTTON -->
        <div class="flex items-center gap-2">

            <a href="cust/login_cust.php"
               class="bg-[#35A875] hover:bg-[#27815F] text-white text-[9px] font-semibold px-4 py-2 rounded-md">
                Login
            </a>

            <a href="cust/register.php"
               class="bg-[#35A875] hover:bg-[#27815F] text-white text-[9px] font-semibold px-4 py-2 rounded-md">
                Register
            </a>

        </div>
    </nav>

    <div class="justify-self-end"></div>

</header>

<!-- CONTENT -->
<main class="flex-1 px-5 md:px-10 py-5">

    <!-- HERO BANNER -->
    <section class="bg-[#DDF3E5] rounded-2xl px-6 md:px-10 py-6 mb-6 overflow-hidden">

        <div class="grid grid-cols-1 md:grid-cols-2 items-center gap-5">

            <!-- TEXT BANNER -->
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-[#176B4D] leading-tight">
                    Belanja Mudah,<br>
                    Kebutuhan Lengkap!
                </h1>

                <p class="text-xs text-gray-600 mt-3">
                    Satu tempat untuk memenuhi kebutuhan harian Anda.
                </p>

                <a href="cust/login_cust.php"
                   class="inline-block bg-[#176B4D] hover:bg-[#27815F] text-white px-8 py-3 rounded-lg text-xs font-semibold mt-4">
                    Belanja Sekarang
                </a>
            </div>

            <!-- GAMBAR BANNER -->
            <div class="flex justify-center items-center">
                <img src="asset/banner-produk.png"
                     alt="Produk Toko 33"
                     class="w-full max-w-md h-44 object-contain">
            </div>

        </div>

    </section>


    <!-- KATEGORI PILIHAN -->
    <section class="mb-6">

        <h2 class="text-sm font-bold text-[#176B4D] mb-3">
            Kategori Pilihan
        </h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

            <!-- HOME CARE -->
            <a href="cust/login_cust.php"
               class="bg-[#DDF3E5] rounded-xl p-4 flex items-center gap-4 hover:bg-green-100 transition">

                <div class="bg-white rounded-lg p-2 flex items-center gap-2 w-1/2 h-20">

                    <img src="asset/wipol.png"
                         alt="Wipol"
                         class="w-1/3 h-full object-contain">

                    <img src="asset/molto.png"
                         alt="Molto"
                         class="w-1/3 h-full object-contain">

                    <img src="asset/soklin.png"
                         alt="So Klin"
                         class="w-1/3 h-full object-contain">

                </div>

                <div class="flex-1">

                    <h3 class="text-sm font-bold text-[#176B4D]">
                        Home Care
                    </h3>

                    <p class="text-[10px] text-gray-500 mt-1">
                        Berbagai kebutuhan kebersihan rumah tangga.
                    </p>

                </div>

                <span class="bg-[#35A875] text-white rounded-full w-7 h-7 flex items-center justify-center text-sm">
                    ›
                </span>

            </a>


            <!-- MINUMAN -->
            <a href="cust/login_cust.php"
               class="bg-[#DDF3E5] rounded-xl p-4 flex items-center gap-4 hover:bg-green-100 transition">

                <div class="bg-white rounded-lg p-2 flex items-center gap-2 w-1/2 h-20">

                    <img src="asset/tehpucuk.png"
                         alt="Teh Pucuk"
                         class="w-1/3 h-full object-contain">

                    <img src="asset/floridina.png"
                         alt="Floridina"
                         class="w-1/3 h-full object-contain">

                    <img src="asset/pocarisweat.png"
                         alt="Pocari Sweat"
                         class="w-1/3 h-full object-contain">

                </div>

                <div class="flex-1">

                    <h3 class="text-sm font-bold text-[#176B4D]">
                        Minuman
                    </h3>

                    <p class="text-[10px] text-gray-500 mt-1">
                        Aneka minuman segar untuk kebutuhan Anda.
                    </p>

                </div>

                <span class="bg-[#35A875] text-white rounded-full w-7 h-7 flex items-center justify-center text-sm">
                    ›
                </span>

            </a>

        </div>

    </section>


    <!-- PRODUK TERLARIS -->
    <section class="mb-8">

        <div class="flex justify-between items-center mb-4">

            <h2 class="text-sm font-bold text-[#176B4D]">
                Produk Kami
            </h2>

        </div>


        <!-- GRID PRODUK -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">


            <!-- CARD 1: WIPOL -->
            <div class="bg-white border border-gray-200 rounded-2xl p-4">

                <div class="h-44 flex justify-center items-center">

                    <img src="asset/wipol.png"
                         alt="Wipol"
                         class="w-full h-full object-contain">

                </div>

                <div class="mt-3">

                    <h3 class="text-sm font-bold">
                        Wipol Karbol
                    </h3>

                    <p class="text-[10px] text-gray-500">
                        • Home Care
                    </p>

                    <div class="flex justify-between items-center mt-2">

                        <div>
                            <p class="text-sm font-bold text-[#176B4D]">
                                Rp15.000
                            </p>

                            <p class="text-[10px] text-gray-500">
                                Stok tersisa: 120
                            </p>
                        </div>

                        <div class="flex flex-col items-end gap-1">

                            <span class="bg-amber-100 text-amber-700 text-[9px] px-3 py-1 rounded-full">
                                Exp 10 Des 2026
                            </span>

                            <span class="bg-green-100 text-green-700 text-[9px] px-3 py-1 rounded-full">
                                Tersedia
                            </span>

                        </div>

                    </div>

                </div>


                <div class="flex gap-2 mt-3">

                    <a href="cust/login_cust.php" type="button"
                            class="flex items-center justify-center w-full bg-[#176B4D] hover:bg-[#27815F] text-white rounded-lg py-3 mt-5 text-xs font-bold text-center">
                        Beli
                    </a>

                </div>

            </div>


            <!-- CARD 2: TEH PUCUK -->
            <div class="bg-white border border-gray-200 rounded-2xl p-4">

                <div class="h-44 flex justify-center items-center">

                    <img src="asset/tehpucuk.png"
                         alt="Teh Pucuk Harum"
                         class="w-full h-full object-contain">

                </div>

                <div class="mt-3">

                    <h3 class="text-sm font-bold">
                        Teh Pucuk Harum
                    </h3>

                    <p class="text-[10px] text-gray-500">
                        • Minuman
                    </p>

                    <div class="flex justify-between items-center mt-2">

                        <div>
                            <p class="text-sm font-bold text-[#176B4D]">
                                Rp4.000
                            </p>

                            <p class="text-[10px] text-gray-500">
                                Stok tersisa: 200
                            </p>
                        </div>

                        <div class="flex flex-col items-end gap-1">

                            <span class="bg-green-100 text-green-700 text-[9px] px-3 py-1 rounded-full">
                                Exp 20 Jan 2027
                            </span>

                            <span class="bg-green-100 text-green-700 text-[9px] px-3 py-1 rounded-full">
                                Tersedia
                            </span>

                        </div>

                    </div>

                </div>


                 <div class="flex gap-2 mt-3">

                    <a href="/cust/login_cust.php" type="button"
                            class="flex items-center justify-center w-full bg-[#176B4D] hover:bg-[#27815F] text-white rounded-lg py-3 mt-5 text-xs font-bold text-center">
                        Beli
                    </a>

                </div>

            </div>


            <!-- CARD 3: SO KLIN -->
            <div class="bg-white border border-gray-200 rounded-2xl p-4">

                <div class="h-44 flex justify-center items-center">

                    <img src="asset/soklin.png"
                         alt="So Klin"
                         class="w-full h-full object-contain">

                </div>

                <div class="mt-3">

                    <h3 class="text-sm font-bold">
                        So Klin
                    </h3>

                    <p class="text-[10px] text-gray-500">
                        • Home Care
                    </p>

                    <div class="flex justify-between items-center mt-2">

                        <div>
                            <p class="text-sm font-bold text-[#176B4D]">
                                Rp10.000
                            </p>

                            <p class="text-[10px] text-gray-500">
                                Stok tersisa: 85
                            </p>
                        </div>

                        <div class="flex flex-col items-end gap-1">

                            <span class="bg-green-100 text-green-700 text-[9px] px-3 py-1 rounded-full">
                                Exp 15 Feb 2027
                            </span>

                            <span class="bg-green-100 text-green-700 text-[9px] px-3 py-1 rounded-full">
                                Tersedia
                            </span>

                        </div>

                    </div>

                </div>


                 <div class="flex gap-2 mt-3">

                    <a href="cust/login_cust.php" type="button"
                            class="flex items-center justify-center w-full bg-[#176B4D] hover:bg-[#27815F] text-white rounded-lg py-3 mt-5 text-xs font-bold text-center">
                        Beli
                    </a>

                </div>

            </div>

        </div>

    </section>


    <!-- KEUNGGULAN TOKO -->
    <section class="bg-[#DDF3E5] rounded-2xl p-6 md:p-8 mb-5">

        <h2 class="text-sm font-bold text-[#176B4D] mb-6">
            Mengapa Belanja di TOKO 33?
        </h2>


        <!-- KEUNGGULAN -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-8">

            <!-- KEUNGGULAN 1 -->
            <div class="flex items-center gap-3">

                <div class="bg-white text-[#35A875] rounded-full w-9 h-9 flex items-center justify-center font-bold">
                    ✓
                </div>

                <div>

                    <h3 class="text-xs font-bold text-[#176B4D]">
                        Stok Selalu Terjaga
                    </h3>

                    <p class="text-[10px] text-gray-600">
                        Produk tersedia untuk kebutuhan Anda.
                    </p>

                </div>

            </div>


            <!-- KEUNGGULAN 2 -->
            <div class="flex items-center gap-3">

                <div class="bg-white text-[#35A875] rounded-full w-9 h-9 flex items-center justify-center font-bold">
                    ✓
                </div>

                <div>

                    <h3 class="text-xs font-bold text-[#176B4D]">
                        Kualitas Terjamin
                    </h3>

                    <p class="text-[10px] text-gray-600">
                        Produk berkualitas dan terpercaya.
                    </p>

                </div>

            </div>


            <!-- KEUNGGULAN 3 -->
            <div class="flex items-center gap-3">

                <div class="bg-white text-[#35A875] rounded-full w-9 h-9 flex items-center justify-center font-bold">
                    ✓
                </div>

                <div>

                    <h3 class="text-xs font-bold text-[#176B4D]">
                        Pelayanan Ramah
                    </h3>

                    <p class="text-[10px] text-gray-600">
                        Melayani pelanggan dengan sepenuh hati.
                    </p>

                </div>

            </div>

        </div>


        <!-- KONTAK DAN MAP -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-center">


            <!-- INFORMASI KONTAK -->
            <div>

                <h2 class="text-lg font-bold text-[#176B4D] mb-4">
                    Ada yang bisa kami bantu? Kontak Kami!
                </h2>


                <!-- EMAIL -->
                <div class="flex items-center gap-3 mb-4">

                    <span class="text-2xl text-[#176B4D]">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75" />
                        </svg>
                    </span>

                    <div>

                        <p class="text-[10px] text-gray-500">
                            Email
                        </p>

                        <p class="text-xs font-semibold text-gray-700">
                            tokotigatiga@gmail.com
                        </p>

                    </div>

                </div>


                <!-- TELEPON -->
                <div class="flex items-center gap-3 mb-4">

                    <span class="text-2xl text-[#176B4D]">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 0 0 2.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 0 1-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 0 0-1.091-.852H4.5A2.25 2.25 0 0 0 2.25 4.5v2.25Z" />
                        </svg>
                    </span>

                    <div>

                        <p class="text-[10px] text-gray-500">
                            No. Telp
                        </p>

                        <p class="text-xs font-semibold text-gray-700">
                            081234567890
                        </p>

                    </div>

                </div>


                <!-- ALAMAT -->
                <div class="flex items-center gap-3">

                    <span class="text-2xl text-[#176B4D]">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
                        </svg>
                    </span>

                    <div>

                        <p class="text-[10px] text-gray-500">
                            Alamat
                        </p>

                        <p class="text-xs font-semibold text-gray-700">
                            Jl. Medayu Selatan XV No.33
                        </p>

                    </div>

                </div>

            </div>


            <!-- MAP -->
            <div class="w-full h-64 rounded-xl overflow-hidden border border-gray-200">

                <iframe
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3957.2045921432377!2d112.79990679999999!3d-7.3309047000000005!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2dd7faa4c14a2313%3A0xadd5b2b2ad898fbf!2sToko%2033!5e0!3m2!1sen!2sid!4v1794775313919!5m2!1sen!2sid"
                    width="100%"
                    height="100%"
                    style="border:0;"
                    loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade"
                    title="Lokasi Toko 33">
                </iframe>

            </div>

        </div>

    </section>

</main>

<!-- FOOTER -->
    <footer class="bg-[#176B4D] min-h-10 flex items-center justify-center px-4 py-3">

        <p class="text-white text-[9px]">
            © 2026 Toko 33. All Rights Reserved.
        </p>

    </footer>
</body>

</html>