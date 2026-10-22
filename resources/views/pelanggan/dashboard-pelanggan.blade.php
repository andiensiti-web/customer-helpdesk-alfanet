@extends('layouts.app')

@section('content')

<div class="space-y-6">

    {{-- HEADER --}}
    <div>
        <h1 class="text-3xl font-bold text-gray-900">
            Dashboard
        </h1>

        <p class="text-gray-700 mt-2">
            Halo, {{ auth()->user()->name ?? '-' }}!
        </p>

        <p class="text-gray-500 mt-1">
            Berikut ringkasan layanan Anda.
        </p>
    </div>


    {{-- CARD INFORMASI --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

        {{-- PAKET --}}
        <div class="bg-blue-50 rounded-xl p-6">

            <div class="flex items-center gap-4">

                <div class="w-14 h-14 bg-blue-100 rounded-xl flex items-center justify-center">
                    <i data-lucide="wifi" class="w-7 h-7 text-blue-600"></i>
                </div>

                <div>

                    <p class="text-gray-500">
                        Paket Internet
                    </p>

                    <h2 class="text-2xl font-bold text-gray-900">
                        {{ $pelanggan->paket->nama_paket ?? '-' }}
                    </h2>

                    <p class="text-sm text-gray-400 mt-1">
                        Aktif sejak
                        {{ $pelanggan->tanggal_mulai?->format('d F Y') ?? '-' }}
                    </p>

                </div>

            </div>

        </div>


        {{-- STATUS --}}
        <div class="bg-green-50 rounded-xl p-6">

            <div class="flex items-center gap-4">

                <div class="w-14 h-14 bg-green-100 rounded-xl flex items-center justify-center">
                    <i data-lucide="circle-check" class="w-7 h-7 text-green-600"></i>
                </div>

                <div>

                    <p class="text-gray-500">
                        Status Jaringan
                    </p>

                    <h2 class="text-2xl font-bold text-gray-900">
                        Normal
                    </h2>

                    <p class="text-sm text-gray-400 mt-1">
                        Koneksi stabil
                    </p>

                </div>

            </div>

        </div>


        {{-- TAGIHAN --}}
        <div class="bg-orange-50 rounded-xl p-6">

            <div class="flex items-center gap-4">

                <div class="w-14 h-14 bg-orange-100 rounded-xl flex items-center justify-center">
                    <i data-lucide="receipt" class="w-7 h-7 text-orange-600"></i>
                </div>

                <div>

                    <p class="text-gray-500">
                        Tagihan Bulan Ini
                    </p>

                    <h2 class="text-2xl font-bold text-gray-900">
                        Rp {{ number_format($tagihanTerakhir->jumlah ?? 0) }}
                    </h2>

                    <p class="text-sm text-gray-400 mt-1">
                        Jatuh tempo
                        {{ $tagihanTerakhir?->tanggal_jatuh_tempo?->format('d F Y') ?? '-' }}
                    </p>

                </div>

            </div>

        </div>

    </div>


    {{-- BAWAH --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">


        {{-- LAPORAN --}}
        <div class="bg-white border border-gray-200 rounded-xl">

            <div class="p-6 border-b">

                <div class="flex justify-between items-center">

                    <h2 class="text-lg font-semibold text-gray-900">
                        Laporan Gangguan Terbaru
                    </h2>

                    <a href="/laporan-gangguan"
                       class="text-blue-600 text-sm">
                        Lihat semua
                    </a>

                </div>

            </div>


            <div class="p-6">

                @if($laporanTerbaru)

                <div class="flex justify-between">

                    <div class="flex gap-4">

                        <div class="w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center">
                            <i data-lucide="file-text"
                               class="w-6 h-6 text-red-600">
                            </i>
                        </div>


                        <div>

                            <h3 class="font-semibold">
                                {{ $laporanTerbaru->id_laporan }}
                            </h3>

                            <p class="text-gray-600">
                                {{ $laporanTerbaru->jenis_gangguan }}
                            </p>

                            <p class="text-sm text-gray-400 mt-1">
                                {{ $laporanTerbaru->tanggal_laporan?->format('d F Y') }}
                            </p>

                        </div>

                    </div>


                    <div>

                        <span class="bg-yellow-100 text-yellow-700 px-4 py-2 rounded-lg text-sm">

                            {{ ucfirst($laporanTerbaru->status) }}

                        </span>

                    </div>

                </div>


                @else

                <p class="text-gray-500">
                    Belum ada laporan gangguan.
                </p>

                @endif

            </div>

        </div>




        {{-- AKSES CEPAT --}}
        <div class="bg-white border border-gray-200 rounded-xl">

            <div class="p-6 border-b">

                <h2 class="text-lg font-semibold">
                    Akses Cepat
                </h2>

            </div>


            <div class="p-6 space-y-4">

                <a href="/laporan-gangguan"
                   class="flex items-center justify-between bg-red-100 text-red-600 p-5 rounded-xl">

                    <span class="font-semibold">
                        Buat Laporan Gangguan
                    </span>

                    <i data-lucide="arrow-right"></i>

                </a>


                <a href="/tagihan-pelanggan"
                   class="flex items-center justify-between bg-blue-100 text-blue-600 p-5 rounded-xl">

                    <span class="font-semibold">
                        Bayar Tagihan
                    </span>

                    <i data-lucide="arrow-right"></i>

                </a>

            </div>

        </div>


    </div>


</div>


<script>
    lucide.createIcons();
</script>

@endsection