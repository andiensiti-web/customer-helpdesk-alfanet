@extends('layouts.app')

@section('content')

<div class="space-y-6">

    {{-- HEADER --}}
    <div>
        <h1 class="text-2xl md:text-3xl font-bold text-gray-900">
            Pengaturan
        </h1>

        <p class="text-gray-600 mt-1">
            Kelola informasi akun dan preferensi pelanggan.
        </p>
    </div>


    {{-- INFORMASI AKUN --}}
    <div class="bg-white rounded-xl border border-gray-200 p-6">

        <div class="flex items-center gap-3 mb-6">

            <div class="w-12 h-12 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center">
                <i data-lucide="user" class="w-6 h-6"></i>
            </div>

            <div>

                <h2 class="text-lg font-semibold text-gray-900">
                    Informasi Akun
                </h2>

                <p class="text-gray-500">
                    Informasi akun pelanggan yang sedang digunakan.
                </p>

            </div>

        </div>


        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">


            <div>

                <label class="block text-sm font-medium text-gray-800 mb-2">
                    Nama Lengkap
                </label>

                <input
                    type="text"
                    value="{{ auth()->user()->name ?? '-' }}"
                    readonly
                    class="w-full bg-gray-50 border border-gray-300 rounded-lg px-4 py-3 text-gray-700">

            </div>



            <div>

                <label class="block text-sm font-medium text-gray-800 mb-2">
                    Username
                </label>

                <input
                    type="text"
                    value="{{ auth()->user()->username ?? '-' }}"
                    readonly
                    class="w-full bg-gray-50 border border-gray-300 rounded-lg px-4 py-3 text-gray-700">

            </div>



            <div>

                <label class="block text-sm font-medium text-gray-800 mb-2">
                    Email
                </label>

                <input
                    type="text"
                    value="{{ auth()->user()->email ?? '-' }}"
                    readonly
                    class="w-full bg-gray-50 border border-gray-300 rounded-lg px-4 py-3 text-gray-700">

            </div>



            <div>

                <label class="block text-sm font-medium text-gray-800 mb-2">
                    Role
                </label>

                <input
                    type="text"
                    value="Pelanggan"
                    readonly
                    class="w-full bg-gray-50 border border-gray-300 rounded-lg px-4 py-3 text-gray-700">

            </div>


        </div>

    </div>




    {{-- NOTIFIKASI --}}
    <div class="bg-white rounded-xl border border-gray-200 p-6">


        <div class="flex items-center gap-3 mb-6">


            <div class="w-12 h-12 rounded-xl bg-green-100 text-green-600 flex items-center justify-center">

                <i data-lucide="bell" class="w-6 h-6"></i>

            </div>


            <div>

                <h2 class="text-lg font-semibold text-gray-900">
                    Notifikasi
                </h2>

                <p class="text-gray-500">
                    Notifikasi pelanggan aktif secara otomatis.
                </p>

            </div>


        </div>




        <div class="space-y-4">


            {{-- TAGIHAN --}}
            <div class="flex items-center justify-between border border-gray-200 rounded-xl p-5">


                <div class="flex items-start gap-4">

                    <i data-lucide="file-text" class="w-6 h-6 text-blue-600 mt-1"></i>


                    <div>

                        <h3 class="font-semibold text-gray-900">
                            Tagihan
                        </h3>

                        <p class="text-sm text-gray-500 mt-1">
                            Mendapatkan pemberitahuan ketika tagihan baru dibuat.
                        </p>

                    </div>


                </div>



                <div class="w-12 h-7 bg-blue-600 rounded-full relative">

                    <div class="absolute right-1 top-1 w-5 h-5 bg-white rounded-full shadow">
                    </div>

                </div>


            </div>




            {{-- PEMBAYARAN --}}
            <div class="flex items-center justify-between border border-gray-200 rounded-xl p-5">


                <div class="flex items-start gap-4">

                    <i data-lucide="credit-card" class="w-6 h-6 text-green-600 mt-1"></i>


                    <div>

                        <h3 class="font-semibold text-gray-900">
                            Pembayaran
                        </h3>

                        <p class="text-sm text-gray-500 mt-1">
                            Mendapatkan pemberitahuan ketika pembayaran diverifikasi.
                        </p>

                    </div>


                </div>



                <div class="w-12 h-7 bg-blue-600 rounded-full relative">

                    <div class="absolute right-1 top-1 w-5 h-5 bg-white rounded-full shadow">
                    </div>

                </div>


            </div>





            {{-- PERBAIKAN --}}
            <div class="flex items-center justify-between border border-gray-200 rounded-xl p-5">


                <div class="flex items-start gap-4">

                    <i data-lucide="wrench" class="w-6 h-6 text-orange-500 mt-1"></i>


                    <div>

                        <h3 class="font-semibold text-gray-900">
                            Status Perbaikan
                        </h3>

                        <p class="text-sm text-gray-500 mt-1">
                            Mendapatkan informasi perubahan status gangguan.
                        </p>

                    </div>


                </div>



                <div class="w-12 h-7 bg-blue-600 rounded-full relative">

                    <div class="absolute right-1 top-1 w-5 h-5 bg-white rounded-full shadow">
                    </div>

                </div>


            </div>



        </div>


    </div>


</div>


<script>

    lucide.createIcons();

</script>


@endsection