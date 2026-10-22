@extends('layouts.app')

@section('content')

<div class="space-y-6">

    <div>
        <h1 class="text-3xl font-bold text-gray-900">
            Tagihan Saya
        </h1>

        <p class="text-gray-500 mt-1">
            Lihat tagihan internet dan lakukan pembayaran.
        </p>
    </div>


    <div class="bg-white rounded-2xl shadow-sm p-6">

        <h2 class="text-xl font-bold text-gray-900 mb-6">
            Daftar Tagihan
        </h2>


        <div class="space-y-5">

            @forelse($tagihan as $item)

            <div class="bg-gray-50 rounded-2xl p-6">


                <div class="flex justify-between items-start">

                    <div>

                        <p class="text-sm text-gray-500">
                            Kode Tagihan
                        </p>

                        <h3 class="text-2xl font-bold text-gray-900">
                            {{ $item->kode_tagihan }}
                        </h3>

                    </div>
                    
                    @if($item->status == 'lunas')
                    <span class="bg-green-100 text-green-700 px-4 py-2 rounded-full text-sm">
                        Lunas
                    </span>
                    @elseif($item->status == 'menunggu_verifikasi')
                    <span class="bg-yellow-100 text-yellow-700 px-4 py-2 rounded-full text-sm">
                        Menunggu Verifikasi
                    </span>
                    @elseif($item->status == 'perlu_perbaikan')
                    <span class="bg-red-100 text-red-700 px-4 py-2 rounded-full text-sm">
                        Perlu Perbaikan
                    </span>
                    @else
                    <span class="bg-gray-100 text-gray-700 px-4 py-2 rounded-full text-sm">
                        Belum Bayar
                    </span>
                    @endif


                </div>




                <div class="mt-6 space-y-4">


                    <div class="flex justify-between">

                        <span class="text-gray-500">
                            Paket Internet
                        </span>


                        <span class="font-semibold text-gray-900">

                            {{ $item->paket->nama_paket ?? '-' }}

                        </span>

                    </div>



                    <div class="flex justify-between">

                        <span class="text-gray-500">
                            Jumlah Tagihan
                        </span>


                        <span class="font-bold text-blue-600">

                            Rp {{ number_format($item->jumlah,0,',','.') }}

                        </span>

                    </div>



                    <div class="flex justify-between">

                        <span class="text-gray-500">
                            Tanggal Terbit
                        </span>


                        <span class="font-semibold">

                            {{ \Carbon\Carbon::parse($item->tanggal_terbit)->format('d F Y') }}

                        </span>

                    </div>



                    <div class="flex justify-between">

                        <span class="text-gray-500">
                            Jatuh Tempo
                        </span>


                        <span class="font-semibold">

                            {{ \Carbon\Carbon::parse($item->tanggal_jatuh_tempo)->format('d F Y') }}

                        </span>

                    </div>


                </div>
                <div class="mt-6">
                    @if($item->status == 'belum_bayar')
                    <a href="{{ url('/pembayaran-pelanggan/'.$item->id) }}"
                    class="bg-blue-600 text-white px-6 py-3 rounded-xl">
                        Bayar Sekarang
                    </a>
                    @elseif($item->status == 'menunggu_verifikasi')
                    <a href="{{ url('/detail-pembayaran/'.$pembayaran[$item->id]->id) }}"
                    class="bg-yellow-100 text-yellow-700 px-6 py-3 rounded-xl">
                        Lihat Detail
                    </a>
                    @elseif($item->status == 'perlu_perbaikan')
                    <a href="{{ url('/pembayaran-pelanggan/'.$item->id) }}"
                    class="bg-red-100 text-red-700 px-6 py-3 rounded-xl">
                        Perbaiki Pembayaran
                    </a>
                    @else
                    <button disabled
                    class="bg-green-100 text-green-700 px-6 py-3 rounded-xl">
                        Sudah Lunas
                    </button>
                    @endif
                </div>
            </div>
            @empty
            <div class="text-center py-10">
                <p class="text-gray-500">
                    Belum ada tagihan.
                </p>
            </div>
            @endforelse
        </div>
    </div>
</div>

@endsection