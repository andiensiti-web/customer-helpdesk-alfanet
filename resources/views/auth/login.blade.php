<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - {{ $pengaturan->nama_perusahaan ?? 'Alfa Net' }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <script src="https://unpkg.com/lucide@latest"></script>
</head>

<body class="min-h-screen bg-slate-50">

<div class="min-h-screen p-3 md:p-4">
    <div class="relative min-h-[calc(100vh-32px)] overflow-hidden border border-blue-400 bg-white">

        {{-- BACKGROUND --}}
        <div class="absolute inset-0 bg-gradient-to-br from-white via-blue-50/50 to-white"></div>

        {{-- DEKORASI --}}
        <div class="absolute -left-20 -bottom-24 w-48 h-48 rounded-full border-[48px] border-blue-300/60"></div>
        <div class="absolute -left-10 top-[44%] w-20 h-20 rounded-full border-[12px] border-blue-300/50"></div>

        {{-- HEADER --}}
        <div class="relative z-10 flex justify-between items-start px-7 md:px-16 lg:px-24 pt-4">
            <div>
                <div class="flex items-center gap-1">
                    <span class="text-2xl md:text-3xl font-bold text-slate-900">
                        {{ $pengaturan->nama_perusahaan ?? 'Alfa Net' }}
                    </span>
                </div>

                <p class="text-xs md:text-sm text-slate-500">
                    Customer & Helpdesk System
                </p>
            </div>

            <div class="hidden md:flex items-center gap-4 text-right">
                <div>
                    <p class="text-xs text-slate-500">Layanan Lebih Baik,</p>
                    <p class="text-xs text-slate-500">Pelanggan Lebih Dekat</p>
                </div>
                <div class="w-12 h-px bg-blue-400"></div>
            </div>
        </div>

        {{-- CONTENT --}}
        <div class="relative z-10 flex items-center min-h-[calc(100vh-120px)] px-7 md:px-16 lg:px-24 py-5">
            <div class="w-full grid grid-cols-1 lg:grid-cols-[1fr_470px] gap-10 lg:gap-16 items-center">

                {{-- BAGIAN KIRI --}}
                <div class="max-w-lg">
                    <h1 class="text-3xl md:text-4xl lg:text-[42px] font-bold leading-tight text-slate-900">
                        Satu Sistem
                        <br>
                        <span class="text-blue-600">Banyak Kemudahan</span>
                    </h1>

                    <p class="text-slate-500 text-sm md:text-base leading-relaxed mt-4 max-w-md">
                        Kelola pelanggan, laporkan gangguan,
                        pantau penugasan teknisi, dan akses semua
                        layanan dalam satu platform.
                    </p>

                    {{-- FITUR 1 --}}
                    <div class="space-y-4 mt-6">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-lg bg-blue-100 flex items-center justify-center shrink-0">
                                <i data-lucide="users" class="w-5 h-5 text-blue-600"></i>
                            </div>
                            <div>
                                <h3 class="text-sm font-semibold text-slate-800">
                                    Pelayanan Lebih Efisien
                                </h3>
                                <p class="text-xs text-slate-500">
                                    Kelola data pelanggan dengan mudah
                                </p>
                            </div>
                        </div>

                        {{-- FITUR 2 --}}
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-lg bg-blue-100 flex items-center justify-center shrink-0">
                                <i data-lucide="file-text" class="w-5 h-5 text-blue-600"></i>
                            </div>
                            <div>
                                <h3 class="text-sm font-semibold text-slate-800">
                                    Proses Lebih Terstruktur
                                </h3>
                                <p class="text-xs text-slate-500">
                                    Pantau setiap laporan dan tindak lanjut
                                </p>
                            </div>
                        </div>

                        {{-- FITUR 3 --}}
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-lg bg-blue-100 flex items-center justify-center shrink-0">
                                <i data-lucide="shield-check" class="w-5 h-5 text-blue-600"></i>
                            </div>
                            <div>
                                <h3 class="text-sm font-semibold text-slate-800">
                                    Layanan Lebih Terpercaya
                                </h3>
                                <p class="text-xs text-slate-500">
                                    Dukungan terbaik untuk pelanggan
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- BAGIAN KANAN: FORM LOGIN --}}
                <div class="flex justify-center">
                    <div class="w-full max-w-[450px] bg-white rounded-xl border border-blue-100 shadow-md p-7">

                        {{-- LOGO --}}
                        <div class="text-center">
                            <div class="flex justify-center items-center gap-1">
                                <span class="text-3xl font-bold text-slate-900">
                                    {{ $pengaturan->nama_perusahaan ?? 'Alfa Net' }}
                                </span>
                            </div>

                            <p class="text-xs text-slate-500 mt-1">
                                Customer & Helpdesk System
                            </p>

                            <div class="w-8 h-0.5 bg-blue-200 mx-auto mt-4"></div>
                        </div>

                        {{-- TITLE --}}
                        <div class="mt-5">
                            <h2 class="text-xl font-bold text-slate-900">
                                Masuk ke Akun
                            </h2>

                            <p class="text-xs text-slate-500 mt-1">
                                Login untuk Admin dan Teknisi {{ $pengaturan->nama_perusahaan ?? 'Alfa Net' }}
                            </p>
                        </div>

                        {{-- ERROR --}}
                        @if($errors->any())
                            <div class="bg-red-50 border border-red-200 text-red-600 px-3 py-2 rounded-lg mt-4 text-xs">
                                {{ $errors->first() }}
                            </div>
                        @endif

                        {{-- FORM LOGIN --}}
                        <form action="/login" method="POST" class="mt-5">
                            @csrf

                            {{-- USERNAME --}}
                            <div>
                                <label class="block text-xs font-semibold text-slate-700 mb-2">
                                    Username
                                </label>

                                <div class="relative">
                                    <i data-lucide="user" class="absolute left-3.5 top-3 w-4 h-4 text-slate-400"></i>

                                    <input
                                        type="text"
                                        name="username"
                                        value="{{ old('username') }}"
                                        required
                                        autofocus
                                        placeholder="Masukkan username"
                                        class="w-full border border-blue-100 rounded-lg pl-10 pr-3 py-2.5 text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
                                </div>
                            </div>

                            {{-- PASSWORD --}}
                            <div class="mt-4">
                                <label class="block text-xs font-semibold text-slate-700 mb-2">
                                    Password
                                </label>

                                <div class="relative">
                                    <i data-lucide="lock" class="absolute left-3.5 top-3 w-4 h-4 text-slate-400"></i>

                                    <input
                                        type="password"
                                        name="password"
                                        id="password"
                                        required
                                        placeholder="Masukkan password"
                                        class="w-full border border-blue-100 rounded-lg pl-10 pr-10 py-2.5 text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100">

                                    <button
                                        type="button"
                                        onclick="togglePassword()"
                                        class="absolute right-3 top-2.5 text-slate-400 hover:text-blue-600">
                                        <i data-lucide="eye" id="eyeIcon" class="w-4 h-4"></i>
                                    </button>
                                </div>
                            </div>

                            {{-- REMEMBER & LUPA PASSWORD --}}
                            <div class="flex items-center justify-between mt-4">
                                <label class="flex items-center gap-2 text-xs text-slate-600">
                                    <input
                                        type="checkbox"
                                        name="remember"
                                        value="1"
                                        class="w-3.5 h-3.5 rounded border-gray-300 text-blue-600">
                                    Ingat saya
                                </label>

                                <a href="{{ url('/lupa-password') }}"
                                   class="text-xs text-blue-600 hover:text-blue-700">
                                    Lupa password?
                                </a>
                            </div>

                            {{-- BUTTON LOGIN --}}
                            <button
                                type="submit"
                                class="w-full mt-5 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 rounded-lg transition flex items-center justify-center gap-2 text-sm">
                                Masuk
                                <i data-lucide="arrow-right" class="w-4 h-4"></i>
                            </button>
                        </form>

                        {{-- LOGIN PELANGGAN --}}
                        <div class="flex items-center gap-3 my-5">
                            <div class="flex-1 h-px bg-slate-200"></div>
                            <span class="text-xs text-slate-400">atau</span>
                            <div class="flex-1 h-px bg-slate-200"></div>
                        </div>

                        <div class="text-center">
                            <p class="text-xs text-slate-500">
                                Anda pelanggan {{ $pengaturan->nama_perusahaan ?? 'Alfa Net' }}?
                            </p>

                            <a href="/login-pelanggan"
                               class="inline-flex items-center justify-center gap-2 mt-2 text-sm font-medium text-blue-600 hover:text-blue-700">
                                Login sebagai Pelanggan
                                <i data-lucide="arrow-right" class="w-4 h-4"></i>
                            </a>
                        </div>

                        {{-- HELP --}}
                        <div class="flex items-center gap-3 my-5">
                            <div class="flex-1 h-px bg-slate-200"></div>
                            <span class="text-xs text-slate-400">atau</span>
                            <div class="flex-1 h-px bg-slate-200"></div>
                        </div>

                        <div class="text-center text-xs text-slate-500">
                            Butuh bantuan?
                            <a
                                href="https://wa.me/6283866154741"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="text-blue-600 font-medium">
                                Hubungi kami
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- FOOTER --}}
        <div class="absolute bottom-3 left-7 md:left-16 lg:left-24 text-[10px] text-slate-400">
            © {{ date('Y') }} {{ $pengaturan->nama_perusahaan ?? 'Alfa Net' }}. All rights reserved.
        </div>
    </div>
</div>

<script>
    lucide.createIcons();

    function togglePassword() {
        const password = document.getElementById('password');
        const icon = document.getElementById('eyeIcon');

        if (password.type === 'password') {
            password.type = 'text';
            icon.setAttribute('data-lucide', 'eye-off');
        } else {
            password.type = 'password';
            icon.setAttribute('data-lucide', 'eye');
        }

        lucide.createIcons();
    }
</script>

</body>
</html>