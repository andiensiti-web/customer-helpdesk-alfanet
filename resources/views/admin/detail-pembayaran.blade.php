@extends('layouts.app')

@section('content')

<div class="space-y-6">

    <div class="flex justify-between items-center">

        <div>
            <h1 class="text-3xl font-bold">
                Detail Pembayaran
            </h1>

            <p class="text-gray-500">
                Verifikasi pembayaran pelanggan.
            </p>
        </div>

            @if(request('from') == 'keuangan')

            <a href="{{ url('/laporan-keuangan') }}"
            class="bg-gray-200 px-5 py-3 rounded-xl">
                Kembali
            </a>


            @elseif(request('from') == 'pembayaran')

            <a href="{{ url('/pembayaran-admin') }}"
            class="bg-gray-200 px-5 py-3 rounded-xl">
                Kembali
            </a>


            @else

            <a href="{{ url('/laporan-admin') }}"
            class="bg-gray-200 px-5 py-3 rounded-xl">
                Kembali
            </a>

            @endif

    </div>


    <div class="bg-white rounded-2xl shadow-sm p-6">


        <div class="flex justify-between">

            <div>

                <p class="text-gray-500">
                    Kode Pembayaran
                </p>

                <h2 class="text-2xl font-bold">
                    {{ $pembayaran->kode_pembayaran }}
                </h2>

            </div>


            @if($pembayaran->status == 'menunggu_verifikasi')

            <span class="bg-yellow-100 text-yellow-700 px-4 py-2 rounded-full">
                Menunggu Verifikasi
            </span>


            @elseif($pembayaran->status == 'terverifikasi')

            <span class="bg-green-100 text-green-700 px-4 py-2 rounded-full">
                Terverifikasi
            </span>


            @elseif($pembayaran->status == 'ditolak')

            <span class="bg-red-100 text-red-700 px-4 py-2 rounded-full">
                Ditolak
            </span>

            @endif


        </div>



        <div class="grid md:grid-cols-2 gap-5 mt-6">


            <div class="bg-gray-50 p-4 rounded-xl">

                <p class="text-gray-500">
                    Pelanggan
                </p>

                <b>
                    {{ $pembayaran->pelanggan->user->name }}
                </b>

            </div>


            <div class="bg-gray-50 p-4 rounded-xl">

                <p class="text-gray-500">
                    Jumlah
                </p>

                <b class="text-blue-600">

                    Rp {{ number_format($pembayaran->jumlah,0,',','.') }}

                </b>

            </div>


            <div class="bg-gray-50 p-4 rounded-xl">

                <p class="text-gray-500">
                    Metode
                </p>

                <b>
                    {{ ucfirst($pembayaran->metode) }}
                </b>

            </div>


            <div class="bg-gray-50 p-4 rounded-xl">

                <p class="text-gray-500">
                    Tanggal
                </p>

                <b>
                    {{ \Carbon\Carbon::parse($pembayaran->tanggal_pembayaran)->format('d F Y') }}
                </b>
          </div>


        </div>


    </div>



    <div class="bg-white rounded-2xl shadow-sm p-6">

        <h2 class="text-xl font-bold mb-4">
            Bukti Pembayaran
        </h2>


        @if($pembayaran->bukti_pembayaran)

        <img src="{{ asset('storage/'.$pembayaran->bukti_pembayaran) }}"
             class="max-w-lg rounded-xl">


        @else

        <p class="text-gray-500">
            Tidak ada bukti pembayaran.
        </p>

        @endif


    </div>



    @if($pembayaran->status == 'menunggu_verifikasi')


    <div class="flex gap-4">


        <form action="{{ url('/verifikasi-pembayaran/'.$pembayaran->id) }}"
              method="POST">

            @csrf

            <button class="bg-green-600 text-white px-6 py-3 rounded-xl">

                Verifikasi

            </button>

        </form>


        <form action="{{ url('/tolak-pembayaran/'.$pembayaran->id) }}"
            method="POST">

            @csrf

            <textarea name="catatan"
                    class="border rounded-lg p-3"
                    placeholder="Alasan penolakan"></textarea>


            <button type="submit"
                    class="bg-red-600 text-white px-6 py-3 rounded-xl">

                Tolak

            </button>

        </form>


    </div>


    @endif


</div>


@endsection