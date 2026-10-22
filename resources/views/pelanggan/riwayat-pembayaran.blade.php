@extends('layouts.app')

@section('content')

<div class="max-w-7xl mx-auto py-8 px-4">

    {{-- Header --}}
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">
            Riwayat Pembayaran
        </h1>

        <p class="text-gray-500 mt-1">
            Daftar pembayaran yang pernah Anda lakukan.
        </p>
    </div>


    {{-- Pesan sukses --}}
    @if(session('success'))
        <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
            {{ session('success') }}
        </div>
    @endif


    {{-- Pesan error --}}
    @if(session('error'))
        <div class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
            {{ session('error') }}
        </div>
    @endif


    {{-- Tabel Riwayat --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">

        @if($riwayatPembayaran->count() > 0)

            <div class="overflow-x-auto">

                <table class="w-full text-sm">

                    <thead class="bg-gray-50 border-b border-gray-200">

                        <tr>

                            <th class="px-6 py-4 text-left font-semibold text-gray-700">
                                No
                            </th>

                            <th class="px-6 py-4 text-left font-semibold text-gray-700">
                                Tanggal
                            </th>

                            <th class="px-6 py-4 text-left font-semibold text-gray-700">
                                Tagihan
                            </th>

                            <th class="px-6 py-4 text-left font-semibold text-gray-700">
                                Metode Pembayaran
                            </th>

                            <th class="px-6 py-4 text-left font-semibold text-gray-700">
                                Jumlah
                            </th>

                            <th class="px-6 py-4 text-left font-semibold text-gray-700">
                                Status
                            </th>

                            <th class="px-6 py-4 text-center font-semibold text-gray-700">
                                Aksi
                            </th>

                        </tr>

                    </thead>


                    <tbody class="divide-y divide-gray-100">

                        @foreach($riwayatPembayaran as $index => $pembayaran)

                            <tr class="hover:bg-gray-50 transition">

                                {{-- No --}}
                                <td class="px-6 py-4 text-gray-600">
                                    {{ $index + 1 }}
                                </td>


                                {{-- Tanggal --}}
                                <td class="px-6 py-4">

                                    <div class="font-medium text-gray-900">
                                        {{ \Carbon\Carbon::parse($pembayaran->tanggal_pembayaran)->format('d M Y') }}
                                    </div>

                                </td>


                                {{-- Tagihan --}}
                                <td class="px-6 py-4">

                                    <div class="font-medium text-gray-900">
                                        {{ $pembayaran->tagihan->paket->nama_paket ?? 'Tagihan Internet' }}
                                    </div>

                                    <div class="text-xs text-gray-500 mt-1">
                                        Tagihan #{{ $pembayaran->tagihan_id }}
                                    </div>

                                </td>


                                {{-- Metode --}}
                                <td class="font-medium px-6 py-4 text-gray-700">

                                    {{ $pembayaran->metode ?? '-' }}

                                </td>


                                {{-- Jumlah --}}
                                <td class="px-6 py-4">

                                    <span class="font-semibold text-gray-900">
                                        Rp {{ number_format($pembayaran->jumlah, 0, ',', '.') }}
                                    </span>

                                </td>


                                {{-- Status --}}
                                <td class="px-6 py-4">

                                    @if($pembayaran->status === 'terverifikasi')

                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700">
                                            Terverifikasi
                                        </span>

                                    @elseif($pembayaran->status === 'menunggu_verifikasi')

                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-700">
                                            Menunggu Verifikasi
                                        </span>

                                    @elseif($pembayaran->status === 'ditolak')

                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-700">
                                            Ditolak
                                        </span>

                                    @else

                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-700">
                                            {{ ucfirst(str_replace('_', ' ', $pembayaran->status)) }}
                                        </span>

                                    @endif

                                </td>


                                {{-- Aksi --}}
                                <td class="px-6 py-4">

                                    <div class="flex items-center justify-center gap-2">

                                        {{-- Detail --}}
                                        <a
                                            href="{{ url('/detail-pembayaran/'.$pembayaran->id) }}"
                                            class="inline-flex items-center gap-1.5 px-3 py-2 rounded-lg bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium transition"
                                            title="Lihat Detail">

                                            <i data-lucide="eye" class="w-4 h-4"></i>

                                            Detail

                                        </a>


                                        {{-- Invoice hanya pembayaran terverifikasi --}}
                                        @if($pembayaran->status === 'terverifikasi')

                                            <a
                                                href="{{ url('/invoice-pembayaran/'.$pembayaran->id) }}"
                                                target="_blank"
                                                class="inline-flex items-center gap-1.5 px-3 py-2 rounded-lg bg-blue-600 hover:bg-blue-700 text-white font-medium transition"
                                                title="Lihat Invoice">

                                                <i data-lucide="file-text" class="w-4 h-4"></i>

                                                Invoice

                                            </a>

                                        @endif

                                    </div>

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>

        @else

            {{-- Empty State --}}
            <div class="py-16 text-center">

                <div class="flex justify-center mb-4">

                    <div class="w-16 h-16 rounded-full bg-gray-100 flex items-center justify-center">

                        <i data-lucide="receipt" class="w-8 h-8 text-gray-400"></i>

                    </div>

                </div>

                <h3 class="text-lg font-semibold text-gray-900">
                    Belum Ada Riwayat Pembayaran
                </h3>

                <p class="text-gray-500 mt-1">
                    Pembayaran yang Anda lakukan akan muncul di halaman ini.
                </p>

            </div>

        @endif

    </div>

</div>


<script>
    document.addEventListener('DOMContentLoaded', function () {
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    });
</script>

@endsection