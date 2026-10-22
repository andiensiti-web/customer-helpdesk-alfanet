<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login Pelanggan - {{ $pengaturan->nama_perusahaan ?? 'Alfa Net' }}</title>

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
        <div class="relative z-10 flex items-center justify-center min-h-[calc(100vh-120px)] px-7 py-5">
            <div class="w-full max-w-[450px]">

                <div class="bg-white rounded-xl border border-blue-100 shadow-md p-7">

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
                            Login Pelanggan
                        </h2>

                        <p class="text-xs text-slate-500 mt-1">
                            Masuk menggunakan nomor WhatsApp yang terdaftar
                        </p>
                    </div>

                    {{-- ERROR --}}
                    @if($errors->any())
                        <div class="bg-red-50 border border-red-200 text-red-600 px-3 py-2 rounded-lg mt-4 text-xs">
                            {{ $errors->first() }}
                        </div>
                    @endif

                    {{-- FORM LOGIN --}}
                    <form action="/login-pelanggan" method="POST" class="mt-5">
                        @csrf

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-2">
                                Nomor WhatsApp
                            </label>

                            <div class="relative">
                                <i data-lucide="phone"
                                   class="absolute left-3.5 top-3 w-4 h-4 text-slate-400"></i>

                                <input
                                    type="text"
                                    name="telepon"
                                    value="{{ old('telepon') }}"
                                    required
                                    autofocus
                                    placeholder="Contoh: 081234567890"
                                    class="w-full border border-blue-100 rounded-lg pl-10 pr-3 py-2.5 text-sm outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-100">
                            </div>

                            <p class="text-xs text-slate-500 mt-2">
                                Gunakan nomor WhatsApp yang terdaftar pada data pelanggan {{ $pengaturan->nama_perusahaan ?? 'Alfa Net' }}.
                            </p>
                        </div>

                        {{-- BUTTON LOGIN --}}
                        <button
                            type="submit"
                            class="w-full mt-5 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 rounded-lg transition flex items-center justify-center gap-2 text-sm">
                            Masuk
                            <i data-lucide="arrow-right" class="w-4 h-4"></i>
                        </button>
                    </form>

                    {{-- KEMBALI KE LOGIN PETUGAS --}}
                    <div class="text-center text-xs text-slate-500 mt-5">
                        Admin atau Teknisi?

                        <a href="/login"
                           class="text-blue-600 font-medium hover:text-blue-700">
                            Login di sini
                        </a>
                    </div>

                    {{-- PEMISAH --}}
                    <div class="flex items-center gap-3 my-5">
                        <div class="flex-1 h-px bg-slate-200"></div>
                        <span class="text-xs text-slate-400">atau</span>
                        <div class="flex-1 h-px bg-slate-200"></div>
                    </div>

                    {{-- HELP --}}
                    <div class="text-center text-xs text-slate-500">
                        Butuh bantuan?

                        <a
                            href="https://wa.me/6283866154741"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="text-blue-600 font-medium hover:text-blue-700">
                            Hubungi kami
                        </a>
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
</script>

</body>
</html>