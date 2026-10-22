@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">

    <div>
        <a href="/tagihan-admin" class="text-blue-600 hover:text-blue-800">
            ← Kembali
        </a>

        <h1 class="text-3xl font-bold text-gray-800 mt-3">
            Detail Tagihan
        </h1>

        <p class="text-gray-500 mt-1">
            Informasi lengkap tagihan pelanggan
        </p>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-6">
        <div class="flex items-center gap-2 mb-6">
            <i data-lucide="receipt" class="w-6 h-6 text-blue-600"></i>
            <h2 class="text-xl font-semibold">Informasi Tagihan</h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

            <div>
                <p class="text-sm text-gray-500">ID Tagihan</p>
                <p class="font-semibold mt-1">
                    {{ $tagihan->kode_tagihan }}
                </p>
            </div>

            <div>
                <p class="text-sm text-gray-500">Status</p>

                @if($tagihan->status === 'belum_bayar')
                    <span class="inline-block mt-1 bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full text-xs">
                        Belum Bayar
                    </span>
                @elseif($tagihan->status === 'lunas')
                    <span class="inline-block mt-1 bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs">
                        Lunas
                    </span>
                @else
                    <span class="inline-block mt-1 bg-gray-100 text-gray-700 px-3 py-1 rounded-full text-xs">
                        {{ ucfirst($tagihan->status) }}
                    </span>
                @endif
            </div>

            <div>
                <p class="text-sm text-gray-500">Pelanggan</p>
                <p class="font-semibold mt-1">
                    {{ $tagihan->pelanggan->user->name ?? '-' }}
                </p>
            </div>

            <div>
                <p class="text-sm text-gray-500">ID Pelanggan</p>
                <p class="font-semibold mt-1">
                    {{ $tagihan->pelanggan->id_pelanggan ?? '-' }}
                </p>
            </div>

            <div>
                <p class="text-sm text-gray-500">Paket</p>
                <p class="font-semibold mt-1">
                    {{ $tagihan->paket->nama_paket ?? '-' }}
                </p>
            </div>

            <div>
                <p class="text-sm text-gray-500">Kecepatan</p>
                <p class="font-semibold mt-1">
                    {{ $tagihan->paket->kecepatan ?? '-' }} Mbps
                </p>
            </div>

            <div>
                <p class="text-sm text-gray-500">Jumlah Tagihan</p>
                <p class="font-semibold text-blue-600 mt-1">
                    Rp{{ number_format($tagihan->jumlah, 0, ',', '.') }}
                </p>
            </div>

            <div>
                <p class="text-sm text-gray-500">Tanggal Terbit</p>
                <p class="font-semibold mt-1">
                    {{ $tagihan->tanggal_terbit->format('d F Y') }}
                </p>
            </div>

            <div>
                <p class="text-sm text-gray-500">Tanggal Jatuh Tempo</p>
                <p class="font-semibold mt-1">
                    {{ $tagihan->tanggal_jatuh_tempo->format('d F Y') }}
                </p>
            </div>

        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-6">
        <div class="flex items-center gap-2 mb-5">
            <i data-lucide="credit-card" class="w-6 h-6 text-blue-600"></i>
            <h2 class="text-xl font-semibold">Riwayat Pembayaran</h2>
        </div>

        @forelse($tagihan->pembayaran as $pembayaran)
            <div class="border rounded-lg p-4 mb-3">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="font-semibold">
                            {{ $pembayaran->kode_pembayaran }}
                        </p>

                        <p class="text-sm text-gray-500 mt-1">
                            {{ $pembayaran->tanggal_pembayaran->format('d F Y') }}
                        </p>
                    </div>

                    @if($pembayaran->status === 'terverifikasi')
                        <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs">
                            Terverifikasi
                        </span>
                    @elseif($pembayaran->status === 'menunggu_verifikasi')
                        <span class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full text-xs">
                            Menunggu Verifikasi
                        </span>
                    @else
                        <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-xs">
                            Ditolak
                        </span>
                    @endif
                </div>

                <p class="font-semibold mt-3">
                    Rp{{ number_format($pembayaran->jumlah, 0, ',', '.') }}
                </p>

                <p class="text-sm text-gray-500 mt-1">
                    Metode: {{ ucfirst($pembayaran->metode) }}
                </p>
            </div>
        @empty
            <div class="text-center py-6 text-gray-500">
                Belum ada pembayaran untuk tagihan ini.
            </div>
        @endforelse
    </div>

    @if($tagihan->catatan)
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h2 class="text-xl font-semibold mb-3">Catatan</h2>
            <p class="text-gray-600">
                {{ $tagihan->catatan }}
            </p>
        </div>
    @endif

</div>
@endsection