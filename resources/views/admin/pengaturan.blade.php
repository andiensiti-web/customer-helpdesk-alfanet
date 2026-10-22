@extends('layouts.app')

@section('content')

<div class="space-y-6">

    {{-- HEADER --}}
    <div>
        <h1 class="text-2xl md:text-3xl font-bold text-gray-900">
            Pengaturan
        </h1>

        <p class="text-gray-600 mt-1">
            Kelola pengaturan sistem Alfa Net.
        </p>
    </div>


    {{-- MENU PENGATURAN --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">


        {{-- INFORMASI AKUN --}}
        <a
            href="/pengaturan/akun"
            class="bg-white rounded-xl border border-gray-200 p-6 hover:shadow-md hover:border-blue-200 transition group">

            <div class="flex items-center justify-between">

                <div class="flex items-center gap-4">

                    <div class="w-12 h-12 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center">

                        <i
                            data-lucide="user"
                            class="w-6 h-6">
                        </i>

                    </div>

                    <div>

                        <h2 class="text-lg font-semibold text-gray-900">
                            Informasi Akun Perusahaan
                        </h2>

                        <p class="text-sm text-gray-500 mt-1">
                            Kelola informasi akun perusahaan.
                        </p>

                    </div>

                </div>


                <i
                    data-lucide="chevron-right"
                    class="w-5 h-5 text-gray-400 group-hover:text-blue-600 transition">
                </i>

            </div>

        </a>


        {{-- NOTIFIKASI --}}
        <a
            href="/pengaturan/notifikasi"
            class="bg-white rounded-xl border border-gray-200 p-6 hover:shadow-md hover:border-purple-200 transition group">

            <div class="flex items-center justify-between">

                <div class="flex items-center gap-4">

                    <div class="w-12 h-12 rounded-xl bg-purple-100 text-purple-600 flex items-center justify-center">

                        <i
                            data-lucide="bell"
                            class="w-6 h-6">
                        </i>

                    </div>

                    <div>

                        <h2 class="text-lg font-semibold text-gray-900">
                            Notifikasi
                        </h2>

                        <p class="text-sm text-gray-500 mt-1">
                            Atur preferensi notifikasi admin.
                        </p>

                    </div>

                </div>


                <i
                    data-lucide="chevron-right"
                    class="w-5 h-5 text-gray-400 group-hover:text-purple-600 transition">
                </i>

            </div>

        </a>


        {{-- PAKET INTERNET --}}
        <a
            href="/pengaturan/paket"
            class="bg-white rounded-xl border border-gray-200 p-6 hover:shadow-md hover:border-green-200 transition group">

            <div class="flex items-center justify-between">

                <div class="flex items-center gap-4">

                    <div class="w-12 h-12 rounded-xl bg-green-100 text-green-600 flex items-center justify-center">

                        <i
                            data-lucide="wifi"
                            class="w-6 h-6">
                        </i>

                    </div>

                    <div>

                        <h2 class="text-lg font-semibold text-gray-900">
                            Paket Internet
                        </h2>

                        <p class="text-sm text-gray-500 mt-1">
                            Kelola paket dan harga internet.
                        </p>

                    </div>

                </div>


                <i
                    data-lucide="chevron-right"
                    class="w-5 h-5 text-gray-400 group-hover:text-green-600 transition">
                </i>

            </div>

        </a>


        {{-- METODE PEMBAYARAN --}}
        <a
            href="/pengaturan/pembayaran"
            class="bg-white rounded-xl border border-gray-200 p-6 hover:shadow-md hover:border-orange-200 transition group">

            <div class="flex items-center justify-between">

                <div class="flex items-center gap-4">

                    <div class="w-12 h-12 rounded-xl bg-orange-100 text-orange-600 flex items-center justify-center">

                        <i
                            data-lucide="credit-card"
                            class="w-6 h-6">
                        </i>

                    </div>

                    <div>

                        <h2 class="text-lg font-semibold text-gray-900">
                            Metode Pembayaran
                        </h2>

                        <p class="text-sm text-gray-500 mt-1">
                            Kelola rekening pembayaran pelanggan.
                        </p>

                    </div>

                </div>


                <i
                    data-lucide="chevron-right"
                    class="w-5 h-5 text-gray-400 group-hover:text-orange-600 transition">
                </i>

            </div>

        </a>

    </div>

</div>


<script>
    lucide.createIcons();
</script>

@endsection