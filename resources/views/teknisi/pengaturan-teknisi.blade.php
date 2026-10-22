@extends('layouts.app')

@section('content')

<div class="space-y-6">

    {{-- HEADER --}}
    <div>
        <h1 class="text-2xl md:text-3xl font-bold text-gray-900">
            Pengaturan
        </h1>

        <p class="text-gray-600 mt-1">
            Kelola informasi akun dan preferensi teknisi.
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
                    Informasi akun teknisi yang sedang digunakan.
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
                    value="{{ $teknisi->nama ?? $user->name ?? '-' }}"
                    readonly
                    class="w-full bg-gray-50 border border-gray-300 rounded-lg px-4 py-3 text-gray-700"
                >
            </div>


            <div>
                <label class="block text-sm font-medium text-gray-800 mb-2">
                    Username
                </label>

                <input
                    type="text"
                    value="{{ $user->username ?? '-' }}"
                    readonly
                    class="w-full bg-gray-50 border border-gray-300 rounded-lg px-4 py-3 text-gray-700"
                >
            </div>


            <div>
                <label class="block text-sm font-medium text-gray-800 mb-2">
                    Email
                </label>

                <input
                    type="text"
                    value="{{ $user->email ?? '-' }}"
                    readonly
                    class="w-full bg-gray-50 border border-gray-300 rounded-lg px-4 py-3 text-gray-700"
                >
            </div>


            <div>
                <label class="block text-sm font-medium text-gray-800 mb-2">
                    Nomor Telepon
                </label>

                <input
                    type="text"
                    value="{{ $teknisi->telepon ?? $user->telepon ?? '-' }}"
                    readonly
                    class="w-full bg-gray-50 border border-gray-300 rounded-lg px-4 py-3 text-gray-700"
                >
            </div>


            <div>
                <label class="block text-sm font-medium text-gray-800 mb-2">
                    ID Teknisi
                </label>

                <input
                    type="text"
                    value="{{ $teknisi->id_teknisi ?? '-' }}"
                    readonly
                    class="w-full bg-gray-50 border border-gray-300 rounded-lg px-4 py-3 text-gray-700"
                >
            </div>


            <div>
                <label class="block text-sm font-medium text-gray-800 mb-2">
                    Jabatan
                </label>

                <input
                    type="text"
                    value="Teknisi Lapangan"
                    readonly
                    class="w-full bg-gray-50 border border-gray-300 rounded-lg px-4 py-3 text-gray-700"
                >
            </div>

        </div>

    </div>





    {{-- PREFERENSI --}}

    <div class="bg-white rounded-xl border border-gray-200 p-6">

        <div class="flex items-center gap-3 mb-6">

            <div class="w-12 h-12 rounded-xl bg-purple-100 text-purple-600 flex items-center justify-center">
                <i data-lucide="sliders-horizontal" class="w-6 h-6"></i>
            </div>

            <div>
                <h2 class="text-lg font-semibold text-gray-900">
                    Preferensi
                </h2>

                <p class="text-gray-500">
                    Atur preferensi notifikasi teknisi.
                </p>
            </div>

        </div>


        <form method="POST" action="/pengaturan-teknisi">

            @csrf


            <div class="space-y-4">


                {{-- TUGAS BARU --}}

                <div class="flex items-center justify-between border border-gray-200 rounded-xl p-5">

                    <div class="flex items-start gap-4">

                        <i data-lucide="bell" class="w-6 h-6 text-blue-600 mt-1"></i>

                        <div>
                            <h3 class="font-semibold text-gray-900">
                                Notifikasi Tugas
                            </h3>

                            <p class="text-sm text-gray-500 mt-1">
                                Terima pemberitahuan ketika mendapatkan tugas baru.
                            </p>
                        </div>

                    </div>


                    <label class="relative inline-flex items-center cursor-pointer">

                        <input
                            type="checkbox"
                            name="tugas_baru"
                            class="sr-only peer"
                            onchange="this.form.submit()"
                            {{ $setting->tugas_baru ? 'checked' : '' }}
                        >

                        <div class="w-12 h-7 bg-gray-300 rounded-full peer-checked:bg-blue-600 transition">
                        </div>

                        <div class="absolute left-1 top-1 w-5 h-5 bg-white rounded-full transition-transform peer-checked:translate-x-5">
                        </div>

                    </label>

                </div>




                {{-- JADWAL KUNJUNGAN --}}

                <div class="flex items-center justify-between border border-gray-200 rounded-xl p-5">

                    <div class="flex items-start gap-4">

                        <i data-lucide="clock-3" class="w-6 h-6 text-orange-500 mt-1"></i>

                        <div>
                            <h3 class="font-semibold text-gray-900">
                                Pengingat Jadwal Kunjungan
                            </h3>

                            <p class="text-sm text-gray-500 mt-1">
                                Tampilkan pengingat sebelum jadwal kunjungan pelanggan.
                            </p>
                        </div>

                    </div>


                    <label class="relative inline-flex items-center cursor-pointer">

                        <input
                            type="checkbox"
                            name="jadwal_kunjungan"
                            class="sr-only peer"
                            onchange="this.form.submit()"
                            {{ $setting->jadwal_kunjungan ? 'checked' : '' }}
                        >

                        <div class="w-12 h-7 bg-gray-300 rounded-full peer-checked:bg-blue-600 transition">
                        </div>

                        <div class="absolute left-1 top-1 w-5 h-5 bg-white rounded-full transition-transform peer-checked:translate-x-5">
                        </div>

                    </label>

                </div>




                {{-- LAPORAN DISETUJUI --}}

                <div class="flex items-center justify-between border border-gray-200 rounded-xl p-5">

                    <div class="flex items-start gap-4">

                        <i data-lucide="file-check" class="w-6 h-6 text-green-600 mt-1"></i>

                        <div>
                            <h3 class="font-semibold text-gray-900">
                                Laporan Disetujui
                            </h3>

                            <p class="text-sm text-gray-500 mt-1">
                                Terima pemberitahuan ketika laporan disetujui admin.
                            </p>
                        </div>

                    </div>


                    <label class="relative inline-flex items-center cursor-pointer">

                        <input
                            type="checkbox"
                            name="laporan_disetujui"
                            class="sr-only peer"
                            onchange="this.form.submit()"
                            {{ $setting->laporan_disetujui ? 'checked' : '' }}
                        >

                        <div class="w-12 h-7 bg-gray-300 rounded-full peer-checked:bg-blue-600 transition">
                        </div>

                        <div class="absolute left-1 top-1 w-5 h-5 bg-white rounded-full transition-transform peer-checked:translate-x-5">
                        </div>

                    </label>

                </div>


            </div>

        </form>

    </div>

</div>


<script>
    lucide.createIcons();
</script>

@endsection