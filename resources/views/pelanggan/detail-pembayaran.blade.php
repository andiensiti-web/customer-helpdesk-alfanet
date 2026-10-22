@extends('layouts.app')

@section('content')

<div class="space-y-6">

    <div class="flex justify-between items-center">

        <div>
            <h1 class="text-3xl font-bold text-gray-900">
                Detail Pembayaran
            </h1>

            <p class="text-gray-500 mt-1">
                Informasi lengkap transaksi pembayaran Anda.
            </p>
        </div>

        <a href="{{ url('/riwayat-pembayaran') }}"
           class="flex items-center gap-2 bg-gray-200 hover:bg-gray-300 px-5 py-3 rounded-xl">

            <i data-lucide="arrow-left" class="w-5 h-5"></i>

            Kembali

        </a>

    </div>


    <div class="bg-white rounded-2xl shadow-sm p-6">

        <div class="flex justify-between items-center">

            <div>
                <p class="text-sm text-gray-500">
                    Kode Pembayaran
                </p>

                <h2 class="text-2xl font-bold">
                    {{ $pembayaran->kode_pembayaran }}
                </h2>
            </div>


            @if($pembayaran->status == 'terverifikasi')

            <span class="bg-green-100 text-green-700 px-4 py-2 rounded-full font-semibold">
                Lunas
            </span>

            @elseif($pembayaran->status == 'ditolak')

            <span class="bg-red-100 text-red-700 px-4 py-2 rounded-full font-semibold">
                Ditolak
            </span>

            @else

            <span class="bg-yellow-100 text-yellow-700 px-4 py-2 rounded-full font-semibold">
                Menunggu
            </span>

            @endif

        </div>

    </div>


    <div class="bg-white rounded-2xl shadow-sm p-6">

        <h2 class="text-xl font-bold mb-5">
            Informasi Pembayaran
        </h2>


        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

            <div class="bg-gray-50 rounded-xl p-4">
                <p class="text-gray-500 text-sm">
                    ID Tagihan
                </p>

                <h3 class="font-bold mt-1">
                    {{ $pembayaran->tagihan->kode_tagihan }}
                </h3>
            </div>


            <div class="bg-blue-50 rounded-xl p-4">

                <p class="text-gray-500 text-sm">
                    Total Pembayaran
                </p>

                <h3 class="font-bold text-blue-600 text-xl mt-1">
                    Rp {{ number_format($pembayaran->jumlah,0,',','.') }}
                </h3>

            </div>


            <div class="bg-gray-50 rounded-xl p-4">

                <p class="text-gray-500 text-sm">
                    Metode Pembayaran
                </p>

                <h3 class="font-bold mt-1">
                    {{ ucfirst($pembayaran->metode) }}
                </h3>

            </div>


            <div class="bg-gray-50 rounded-xl p-4">

                <p class="text-gray-500 text-sm">
                    Tanggal Pembayaran
                </p>

                <h3 class="font-bold mt-1">
                    {{ \Carbon\Carbon::parse($pembayaran->tanggal_pembayaran)->format('d F Y') }}
                </h3>

            </div>


        </div>

    </div>


    <div class="bg-white rounded-2xl shadow-sm p-6">

        <div class="flex items-center gap-3 mb-5">

            <div class="bg-blue-100 p-3 rounded-xl">

                <i data-lucide="image"
                   class="w-6 h-6 text-blue-600">
                </i>

            </div>


            <div>
                <h2 class="text-xl font-bold">
                    Bukti Pembayaran
                </h2>

                <p class="text-gray-500 text-sm">
                    Bukti transfer yang Anda upload.
                </p>
            </div>

        </div>


        @if($pembayaran->bukti_pembayaran)

        <div class="bg-gray-50 rounded-xl p-4">

            <img src="{{ asset('storage/'.$pembayaran->bukti_pembayaran) }}"
                 class="rounded-xl max-w-lg">

        </div>

        @else

        <div class="bg-gray-50 rounded-xl p-5 text-center text-gray-500">

            Bukti pembayaran belum tersedia.

        </div>

        @endif

    </div>


</div>


<script>
    lucide.createIcons();
</script>


@endsection