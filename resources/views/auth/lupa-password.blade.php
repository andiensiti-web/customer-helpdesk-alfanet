<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Lupa Password - Alfa Net</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <script src="https://unpkg.com/lucide@latest"></script>
</head>

<body class="min-h-screen bg-slate-50">

    <div class="min-h-screen p-3 md:p-4">

        <div class="relative min-h-[calc(100vh-32px)] overflow-hidden border border-blue-400 bg-white">

            <!-- Background -->
            <div class="absolute inset-0 bg-gradient-to-br from-white via-blue-50/50 to-white"></div>

            <!-- Dekorasi -->
            <div class="absolute -left-20 -bottom-24 w-48 h-48 rounded-full border-[48px] border-blue-300/60"></div>

            <div class="absolute -left-10 top-[44%] w-20 h-20 rounded-full border-[12px] border-blue-300/50"></div>


            <!-- HEADER -->
            <div class="relative z-10 flex justify-between items-start px-7 md:px-16 lg:px-24 pt-4">

                <div>

                    <div class="flex items-center gap-1">

                        <span class="text-2xl md:text-3xl font-bold text-slate-900">
                            Alfa
                        </span>

                        <span class="text-2xl md:text-3xl font-bold text-blue-600">
                            Net
                        </span>

                    </div>

                    <p class="text-xs md:text-sm text-slate-500">
                        Customer & Helpdesk System
                    </p>

                </div>


                <div class="hidden md:flex items-center gap-4 text-right">

                    <div>

                        <p class="text-xs text-slate-500">
                            Layanan Lebih Baik,
                        </p>

                        <p class="text-xs text-slate-500">
                            Pelanggan Lebih Dekat
                        </p>

                    </div>

                    <div class="w-12 h-px bg-blue-400"></div>

                </div>

            </div>


            <!-- CONTENT -->
            <div class="relative z-10 flex items-center min-h-[calc(100vh-120px)] px-7 md:px-16 lg:px-24 py-5">

                <div class="w-full grid grid-cols-1 lg:grid-cols-[1fr_470px] gap-10 lg:gap-16 items-center">


                    <!-- KIRI -->
                    <div class="max-w-lg">

                        <h1 class="text-3xl md:text-4xl lg:text-[42px] font-bold leading-tight text-slate-900">

                            Pulihkan
                            <br>

                            <span class="text-blue-600">
                                Akun Anda
                            </span>

                        </h1>


                        <p class="text-slate-500 text-sm md:text-base leading-relaxed mt-4 max-w-md">

                            Jangan khawatir jika Anda lupa password.
                            Masukkan email yang terdaftar untuk mendapatkan
                            instruksi pemulihan akun.

                        </p>


                        <!-- FITUR -->
                        <div class="space-y-4 mt-6">


                            <div class="flex items-center gap-4">

                                <div class="w-10 h-10 rounded-lg bg-blue-100 flex items-center justify-center shrink-0">

                                    <i data-lucide="mail"
                                       class="w-5 h-5 text-blue-600"></i>

                                </div>


                                <div>

                                    <h3 class="text-sm font-semibold text-slate-800">
                                        Pemulihan melalui Email
                                    </h3>

                                    <p class="text-xs text-slate-500">
                                        Instruksi pemulihan dikirim ke email Anda
                                    </p>

                                </div>

                            </div>


                            <div class="flex items-center gap-4">

                                <div class="w-10 h-10 rounded-lg bg-blue-100 flex items-center justify-center shrink-0">

                                    <i data-lucide="shield-check"
                                       class="w-5 h-5 text-blue-600"></i>

                                </div>


                                <div>

                                    <h3 class="text-sm font-semibold text-slate-800">
                                        Proses Aman
                                    </h3>

                                    <p class="text-xs text-slate-500">
                                        Data akun tetap terlindungi
                                    </p>

                                </div>

                            </div>


                            <div class="flex items-center gap-4">

                                <div class="w-10 h-10 rounded-lg bg-blue-100 flex items-center justify-center shrink-0">

                                    <i data-lucide="key-round"
                                       class="w-5 h-5 text-blue-600"></i>

                                </div>


                                <div>

                                    <h3 class="text-sm font-semibold text-slate-800">
                                        Password Baru
                                    </h3>

                                    <p class="text-xs text-slate-500">
                                        Buat password baru setelah verifikasi
                                    </p>

                                </div>

                            </div>


                        </div>

                    </div>


                    <!-- KANAN -->
                    <div class="flex justify-center">

                        <div class="w-full max-w-[450px] bg-white rounded-xl border border-blue-100 shadow-md p-7">


                            <!-- LOGO -->
                            <div class="text-center">

                                <div class="flex justify-center items-center gap-1">

                                    <span class="text-3xl font-bold text-slate-900">
                                        Alfa
                                    </span>

                                    <span class="text-3xl font-bold text-blue-600">
                                        Net
                                    </span>

                                </div>

                                <p class="text-xs text-slate-500 mt-1">
                                    Customer & Helpdesk System
                                </p>

                                <div class="w-8 h-0.5 bg-blue-200 mx-auto mt-4"></div>

                            </div>


                            <!-- TITLE -->
                            <div class="mt-5">

                                <h2 class="text-xl font-bold text-slate-900">
                                    Lupa Password?
                                </h2>

                                <p class="text-xs text-slate-500 mt-1">
                                    Masukkan email yang terdaftar pada akun Anda
                                </p>

                            </div>


                            <!-- ERROR -->
                            @if($errors->any())

                                <div class="bg-red-50 border border-red-200 text-red-600 px-3 py-2 rounded-lg mt-4 text-xs">

                                    {{ $errors->first() }}

                                </div>

                            @endif


                            <!-- SUCCESS -->
                            @if(session('success'))

                                <div class="bg-green-50 border border-green-200 text-green-600 px-3 py-2 rounded-lg mt-4 text-xs">

                                    {{ session('success') }}

                                </div>

                            @endif


                            <!-- FORM -->
                            <form action="#" method="POST" class="mt-5">

                                @csrf


                                <!-- EMAIL -->
                                <div>

                                    <label class="block text-xs font-semibold text-slate-700 mb-2">
                                        Email
                                    </label>


                                    <div class="relative">

                                        <i data-lucide="mail"
                                           class="absolute left-3.5 top-3 w-4 h-4 text-slate-400"></i>


                                        <input
                                            type="email"
                                            name="email"
                                            value="{{ old('email') }}"
                                            required
                                            placeholder="Masukkan email Anda"
                                            class="w-full border border-blue-100 rounded-lg pl-10 pr-3 py-2.5 text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100">

                                    </div>

                                </div>


                                <!-- BUTTON -->
                                <button
                                    type="submit"
                                    class="w-full mt-5 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 rounded-lg transition flex items-center justify-center gap-2 text-sm">

                                    Kirim Instruksi

                                    <i data-lucide="arrow-right"
                                       class="w-4 h-4"></i>

                                </button>


                            </form>


                            <!-- BACK TO LOGIN -->
                            <div class="text-center mt-5">

                                <a href="{{ url('/login') }}"
                                   class="text-xs text-blue-600 hover:text-blue-700 font-medium">

                                    ← Kembali ke halaman login

                                </a>

                            </div>


                            <!-- HELP -->
                            <div class="text-center text-xs text-slate-500 mt-5">

                                Butuh bantuan?

                                <a href="https://wa.me/+6283866154741"
                                   target="_blank"
                                   class="text-blue-600 font-medium">

                                    Hubungi kami

                                </a>

                            </div>


                        </div>

                    </div>


                </div>

            </div>


            <!-- FOOTER -->
            <div class="absolute bottom-3 left-7 md:left-16 lg:left-24 text-[10px] text-slate-400">

                © {{ date('Y') }} Alfa Net. All rights reserved.

            </div>

        </div>

    </div>


    <script>

        lucide.createIcons();

    </script>

</body>

</html>