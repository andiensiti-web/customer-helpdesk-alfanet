@extends('layouts.app')

@section('content')

<div class="bg-gray-100 py-8 px-4 print:bg-white print:p-0">

    {{-- TOMBOL AKSI --}}
    <div class="max-w-4xl mx-auto flex justify-end gap-3 mb-5 print:hidden">

        <button
            type="button"
            onclick="window.print()"
            class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-5 py-3 rounded-xl font-semibold shadow-sm transition">
            <i data-lucide="printer" class="w-5 h-5"></i>
            Print
        </button>

        <button
            type="button"
            onclick="window.print()"
            class="inline-flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white px-5 py-3 rounded-xl font-semibold shadow-sm transition">
            <i data-lucide="file-down" class="w-5 h-5"></i>
            Save PDF
        </button>

    </div>


    {{-- INVOICE --}}
    <div
        id="invoice"
        class="max-w-4xl mx-auto bg-white shadow-lg rounded-2xl overflow-hidden print:shadow-none print:rounded-none">

        {{-- HEADER --}}
        <div class="px-10 py-8 border-b border-gray-200">

            <div class="flex items-start justify-between">

                {{-- BRAND --}}
                <div>
                    <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">
                        AlfaNet
                    </h1>

                    <p class="text-sm text-gray-500 mt-1">
                        Internet Service Provider
                    </p>
                </div>


                {{-- INVOICE --}}
                <div class="text-right">

                    <h2 class="text-3xl font-extrabold text-gray-900 tracking-wide">
                        INVOICE
                    </h2>

                    <p class="text-sm text-gray-500 mt-1">
                        PAY-{{ str_pad($pembayaran->id, 4, '0', STR_PAD_LEFT) }}
                    </p>

                </div>

            </div>

        </div>


        {{-- INFORMASI UTAMA --}}
        <div class="px-10 py-8">

            <div class="grid grid-cols-2 gap-10">

                {{-- PELANGGAN --}}
                <div>

                    <p class="text-xs font-bold uppercase tracking-wider text-gray-400 mb-3">
                        Ditagihkan Kepada
                    </p>

                    <h3 class="text-lg font-bold text-gray-900">
                        {{ $pelanggan->nama ?? $pembayaran->pelanggan->user->name ?? '-' }}
                    </h3>

                    <div class="mt-3 space-y-1.5 text-sm text-gray-600">

                        <p>
                            <span class="text-gray-400">ID Pelanggan:</span>
                            <span class="font-semibold text-gray-800">
                                {{ $pelanggan->id_pelanggan ?? '-' }}
                            </span>
                        </p>

                        <p>
                            <span class="text-gray-400">Telepon:</span>
                            <span class="font-semibold text-gray-800">
                                {{ $pembayaran->pelanggan->user->telepon ?? '-' }}
                            </span>
                        </p>

                        <p>
                            <span class="text-gray-400">Email:</span>
                            <span class="font-semibold text-gray-800">
                                {{ $pembayaran->pelanggan->user->email ?? '-' }}
                            </span>
                        </p>

                    </div>

                </div>


                {{-- PEMBAYARAN --}}
                <div class="text-right">

                    <p class="text-xs font-bold uppercase tracking-wider text-gray-400 mb-3">
                        Informasi Pembayaran
                    </p>

                    <div class="space-y-2 text-sm">

                        <div>
                            <span class="text-gray-400">
                                Tanggal Pembayaran
                            </span>

                            <p class="font-semibold text-gray-900">
                                {{ \Carbon\Carbon::parse($pembayaran->tanggal_pembayaran)->format('d F Y') }}
                            </p>
                        </div>

                        <div>
                            <span class="text-gray-400">
                                Metode Pembayaran
                            </span>

                            <p class="font-semibold text-gray-900">
                                {{ $pembayaran->metode ?? '-' }}
                            </p>
                        </div>

                        <div>
                            <span class="text-gray-400">
                                Status
                            </span>

                            <p class="font-semibold text-green-600">
                                Terverifikasi
                            </p>
                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- DETAIL PEMBAYARAN --}}
        <div class="px-10">

            <div class="border border-gray-200 rounded-xl overflow-hidden">

                {{-- HEADER TABLE --}}
                <div class="grid grid-cols-12 bg-gray-50 px-5 py-4 border-b border-gray-200">

                    <div class="col-span-7">
                        <span class="text-xs font-bold uppercase tracking-wider text-gray-500">
                            Deskripsi
                        </span>
                    </div>

                    <div class="col-span-5 text-right">
                        <span class="text-xs font-bold uppercase tracking-wider text-gray-500">
                            Jumlah
                        </span>
                    </div>

                </div>


                {{-- ITEM --}}
                <div class="grid grid-cols-12 px-5 py-6">

                    <div class="col-span-7">

                        <p class="font-bold text-gray-900 text-base">
                            {{ $pembayaran->tagihan->paket->nama_paket ?? 'Tagihan Internet' }}
                        </p>

                        <p class="text-sm text-gray-500 mt-1">
                            Pembayaran tagihan internet
                        </p>

                        @if($pembayaran->tagihan->tanggal_jatuh_tempo ?? false)

                            <p class="text-xs text-gray-400 mt-2">
                                Jatuh tempo:
                                {{ \Carbon\Carbon::parse($pembayaran->tagihan->tanggal_jatuh_tempo)->format('d F Y') }}
                            </p>

                        @endif

                    </div>


                    <div class="col-span-5 text-right">

                        <p class="text-lg font-bold text-gray-900">
                            Rp {{ number_format($pembayaran->jumlah, 0, ',', '.') }}
                        </p>

                    </div>

                </div>

            </div>

        </div>


        {{-- TOTAL --}}
        <div class="px-10 py-8">

            <div class="flex justify-end">

                <div class="w-full md:w-80">

                    <div class="flex justify-between items-center py-3">

                        <span class="text-sm text-gray-500">
                            Subtotal
                        </span>

                        <span class="font-semibold text-gray-900">
                            Rp {{ number_format($pembayaran->jumlah, 0, ',', '.') }}
                        </span>

                    </div>

                    <div class="border-t border-gray-200 pt-4 flex justify-between items-center">

                        <span class="text-base font-bold text-gray-900">
                            Total Pembayaran
                        </span>

                        <span class="text-xl font-extrabold text-gray-900">
                            Rp {{ number_format($pembayaran->jumlah, 0, ',', '.') }}
                        </span>

                    </div>

                </div>

            </div>

        </div>


        {{-- STATUS --}}
        <div class="mx-10 mb-8">

            <div class="bg-green-50 border border-green-200 rounded-xl px-5 py-4 flex items-center gap-3">

                <div class="w-9 h-9 rounded-full bg-green-100 flex items-center justify-center flex-shrink-0">

                    <i data-lucide="circle-check"
                       class="w-5 h-5 text-green-600">
                    </i>

                </div>

                <div>

                    <p class="font-semibold text-green-800">
                        Pembayaran Terverifikasi
                    </p>

                    <p class="text-sm text-green-700 mt-0.5">
                        Pembayaran ini telah diverifikasi oleh AlfaNet.
                    </p>

                </div>

            </div>

        </div>


        {{-- FOOTER --}}
        <div class="border-t border-gray-200 px-10 py-7">

            <div class="flex justify-between items-end gap-8">

                <div>

                    <p class="font-semibold text-gray-900">
                        Terima kasih telah melakukan pembayaran.
                    </p>

                    <p class="text-sm text-gray-500 mt-1">
                        Simpan invoice ini sebagai bukti pembayaran.
                    </p>

                </div>

                <div class="text-right text-sm text-gray-500">

                    <p class="font-semibold text-gray-700">
                        AlfaNet
                    </p>

                    <p>
                        Internet Service Provider
                    </p>

                </div>

            </div>

        </div>

    </div>

</div>


{{-- PRINT STYLE --}}
<style>

    @media print {

        html,
        body {
            background: white !important;
            margin: 0 !important;
            padding: 0 !important;
        }

        nav,
        aside,
        header,
        footer,
        .print\:hidden {
            display: none !important;
        }

        #invoice {
            width: 100% !important;
            max-width: none !important;
            margin: 0 !important;
            padding: 0 !important;
            box-shadow: none !important;
            border: none !important;
            border-radius: 0 !important;
        }

        @page {
            size: A4;
            margin: 8mm;
        }

    }

</style>


<script>
    document.addEventListener('DOMContentLoaded', function () {

        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }

    });
</script>

@endsection