@extends('layouts.app')

@section('content')

<div class="space-y-6">

    {{-- HEADER --}}
    <div>
        <h1 class="text-3xl font-bold text-gray-900">
            Pembayaran
        </h1>

        <p class="text-gray-500 mt-1">
            Lakukan pembayaran tagihan internet Anda.
        </p>
    </div>


    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- DETAIL TAGIHAN --}}
        <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm p-6">

            <h2 class="text-xl font-bold">
                Tagihan yang Akan Dibayar
            </h2>

            <p class="text-gray-500 mt-1">
                Periksa informasi tagihan sebelum melakukan pembayaran.
            </p>


            {{-- ID TAGIHAN --}}
            <div class="bg-blue-50 rounded-2xl p-5 mt-5 flex justify-between items-center">

                <div>
                    <p class="text-sm text-gray-500">
                        ID Tagihan
                    </p>

                    <h3 class="text-2xl font-bold">
                        {{ $tagihan->kode_tagihan }}
                    </h3>
                </div>

                <span class="bg-yellow-100 text-yellow-700 px-4 py-2 rounded-full text-sm">
                    Belum Bayar
                </span>

            </div>


            {{-- DETAIL PELANGGAN --}}
            <div class="mt-6 space-y-4">

                <div class="flex justify-between">
                    <span class="text-gray-500">
                        ID Pelanggan
                    </span>

                    <b>
                        {{ $pelanggan->id_pelanggan }}
                    </b>
                </div>


                <div class="flex justify-between">
                    <span class="text-gray-500">
                        Nama Pelanggan
                    </span>

                    <b>
                        {{ $pelanggan->user->name ?? $pelanggan->nama ?? '-' }}
                    </b>
                </div>


                <div class="flex justify-between">
                    <span class="text-gray-500">
                        Paket Internet
                    </span>

                    <b>
                        {{ $tagihan->paket->nama_paket ?? '-' }}
                    </b>
                </div>


                <div class="flex justify-between">
                    <span class="text-gray-500">
                        Periode
                    </span>

                    <b>
                        {{ $tagihan->tanggal_terbit->format('F Y') }}
                    </b>
                </div>


                <div class="flex justify-between">
                    <span class="text-gray-500">
                        Jatuh Tempo
                    </span>

                    <b>
                        {{ $tagihan->tanggal_jatuh_tempo->format('d F Y') }}
                    </b>
                </div>

            </div>


            {{-- TOTAL --}}
            <div class="mt-8 flex justify-between items-center">

                <h3 class="text-xl font-bold">
                    Total Pembayaran
                </h3>

                <h3 class="text-3xl font-bold text-blue-600">
                    Rp {{ number_format($tagihan->jumlah, 0, ',', '.') }}
                </h3>

            </div>

        </div>


        {{-- FORM PEMBAYARAN --}}
        <div class="bg-white rounded-2xl shadow-sm p-6">

            <h2 class="text-xl font-bold">
                Pembayaran
            </h2>

            <p class="text-gray-500 mt-1">
                Pilih metode pembayaran.
            </p>


            {{-- ERROR VALIDASI --}}
            @if($errors->any())

                <div class="mt-5 bg-red-50 border border-red-200 rounded-xl p-4">

                    <p class="font-semibold text-red-700 mb-2">
                        Pembayaran gagal dikirim:
                    </p>

                    <ul class="text-sm text-red-600 space-y-1">

                        @foreach($errors->all() as $error)

                            <li>
                                • {{ $error }}
                            </li>

                        @endforeach

                    </ul>

                </div>

            @endif


            {{-- PESAN SUKSES --}}
            @if(session('success'))

                <div class="mt-5 bg-green-50 border border-green-200 rounded-xl p-4">

                    <p class="text-sm text-green-700">
                        {{ session('success') }}
                    </p>

                </div>

            @endif


            {{-- PEMBAYARAN DITOLAK --}}
            @if(
                isset($pembayaranTerakhir) &&
                $pembayaranTerakhir->status === 'ditolak'
            )

                <div class="bg-red-50 rounded-xl p-5 mt-5">

                    <h3 class="font-bold text-red-700">
                        Pembayaran Perlu Diperbaiki
                    </h3>

                    <p class="text-red-600 mt-2">
                        {{ $pembayaranTerakhir->catatan_verifikasi ?? '-' }}
                    </p>

                    <p class="text-sm text-gray-500 mt-2">
                        Silakan upload ulang bukti pembayaran.
                    </p>

                </div>

            @endif


            {{-- FORM --}}
            <form
                action="{{ url('/pembayaran-pelanggan') }}"
                method="POST"
                enctype="multipart/form-data"
                class="mt-5"
            >

                @csrf


                {{-- TAGIHAN --}}
                <input
                    type="hidden"
                    name="tagihan_id"
                    value="{{ $tagihan->id }}"
                >


                {{-- METODE TERPILIH --}}
                <input
                    type="hidden"
                    name="metode"
                    id="metode"
                    value="{{ $metodePembayaran->first()->nama_metode ?? '' }}"
                >


                {{-- METODE PEMBAYARAN --}}
                <div>

                    <label class="block font-semibold mb-3">
                        Metode Pembayaran
                    </label>


                    @if($metodePembayaran->isEmpty())

                        <div class="border border-yellow-200 bg-yellow-50 rounded-xl p-4">

                            <p class="font-semibold text-yellow-800">
                                Metode pembayaran belum tersedia
                            </p>

                            <p class="text-sm text-yellow-700 mt-1">
                                Silakan hubungi admin untuk informasi pembayaran.
                            </p>

                        </div>

                    @else

                        <div class="space-y-3">

                            @foreach($metodePembayaran as $index => $metode)

                                <button
                                    type="button"
                                    class="metode-card w-full rounded-2xl p-4 text-left transition border
                                    {{ $index === 0
                                        ? 'border-blue-600 bg-blue-50'
                                        : 'border-gray-200 hover:border-blue-400 hover:bg-gray-50'
                                    }}"
                                    data-metode="{{ $metode->nama_metode }}"
                                    data-nama="{{ $metode->nama_metode }}"
                                    data-nomor="{{ $metode->nomor_rekening }}"
                                    data-atas-nama="{{ $metode->atas_nama }}"
                                >

                                    <div class="flex items-center gap-4">

                                        {{-- ICON --}}
                                        <div
                                            class="
                                                p-3 rounded-xl
                                                {{ $metode->jenis === 'bank'
                                                    ? 'bg-blue-100 text-blue-600'
                                                    : 'bg-purple-100 text-purple-600'
                                                }}
                                            "
                                        >

                                            @if($metode->jenis === 'bank')

                                                <svg
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    width="28"
                                                    height="28"
                                                    viewBox="0 0 24 24"
                                                    fill="none"
                                                    stroke="currentColor"
                                                    stroke-width="2"
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                >
                                                    <rect
                                                        x="3"
                                                        y="4"
                                                        width="18"
                                                        height="16"
                                                        rx="2"
                                                    ></rect>

                                                    <path d="M7 8h10"></path>

                                                    <path d="M7 12h5"></path>
                                                </svg>

                                            @else

                                                <svg
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    width="28"
                                                    height="28"
                                                    viewBox="0 0 24 24"
                                                    fill="none"
                                                    stroke="currentColor"
                                                    stroke-width="2"
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                >
                                                    <rect
                                                        x="3"
                                                        y="6"
                                                        width="18"
                                                        height="12"
                                                        rx="2"
                                                    ></rect>

                                                    <path d="M16 12h2"></path>
                                                </svg>

                                            @endif

                                        </div>


                                        {{-- INFORMASI --}}
                                        <div class="flex-1">

                                            <h3 class="font-bold text-gray-900">
                                                {{ $metode->nama_metode }}
                                            </h3>

                                            <p class="text-sm text-gray-700 mt-1">
                                                {{ $metode->nomor_rekening }}
                                            </p>

                                            <p class="text-xs text-gray-500 mt-1">
                                                a.n. {{ $metode->atas_nama }}
                                            </p>

                                        </div>


                                        {{-- CHECK --}}
                                        <div
                                            class="check-metode {{ $index === 0 ? '' : 'hidden' }}"
                                        >

                                            <div class="w-6 h-6 rounded-full bg-blue-600 flex items-center justify-center">

                                                <svg
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    width="15"
                                                    height="15"
                                                    viewBox="0 0 24 24"
                                                    fill="none"
                                                    stroke="white"
                                                    stroke-width="3"
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                >
                                                    <polyline points="20 6 9 17 4 12"></polyline>
                                                </svg>

                                            </div>

                                        </div>

                                    </div>

                                </button>

                            @endforeach

                        </div>

                    @endif

                </div>


                {{-- INFORMASI PEMBAYARAN --}}
                @if($metodePembayaran->isNotEmpty())

                    <div
                        id="informasiMetode"
                        class="bg-blue-50 rounded-2xl p-5 mt-5"
                    >

                        <h3 class="font-bold mb-3">
                            Informasi Pembayaran
                        </h3>


                        <div class="space-y-2">

                            <p>
                                Metode:
                                <b id="infoNamaMetode">
                                    {{ $metodePembayaran->first()->nama_metode }}
                                </b>
                            </p>


                            <p>
                                Nomor:
                                <b id="infoNomor">
                                    {{ $metodePembayaran->first()->nomor_rekening }}
                                </b>
                            </p>


                            <p>
                                Atas Nama:
                                <b id="infoAtasNama">
                                    {{ $metodePembayaran->first()->atas_nama }}
                                </b>
                            </p>

                        </div>

                    </div>

                @endif


                {{-- UPLOAD BUKTI --}}
                <div class="mt-5">

                    <label class="block font-semibold mb-2">
                        Upload Bukti Pembayaran
                    </label>

                    <input
                        type="file"
                        name="bukti_pembayaran"
                        accept=".jpg,.jpeg,.png,image/jpeg,image/png"
                        class="w-full rounded-xl bg-gray-50 p-3 border border-gray-200"
                        required
                    >

                    <p class="text-xs text-gray-500 mt-2">
                        Format: JPG, JPEG, PNG. Maksimal 5 MB.
                    </p>

                </div>


                {{-- TANGGAL --}}
                <input
                    type="hidden"
                    name="tanggal_pembayaran"
                    value="{{ date('Y-m-d') }}"
                >


                {{-- TOMBOL --}}
                <button
                    type="submit"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white py-3 rounded-xl mt-5 transition disabled:bg-gray-400 disabled:cursor-not-allowed"
                    {{ $metodePembayaran->isEmpty() ? 'disabled' : '' }}
                >
                    Kirim Pembayaran
                </button>

            </form>

        </div>

    </div>

</div>


<script>

document.addEventListener('DOMContentLoaded', function () {

    const cards =
        document.querySelectorAll('.metode-card');

    const inputMetode =
        document.getElementById('metode');

    const infoNamaMetode =
        document.getElementById('infoNamaMetode');

    const infoNomor =
        document.getElementById('infoNomor');

    const infoAtasNama =
        document.getElementById('infoAtasNama');


    cards.forEach(function (card) {

        card.addEventListener('click', function () {

            const namaMetode =
                card.dataset.metode;


            if (inputMetode) {

                inputMetode.value =
                    namaMetode;

            }


            cards.forEach(function (item) {

                item.classList.remove(
                    'border-blue-600',
                    'bg-blue-50'
                );

                item.classList.add(
                    'border-gray-200'
                );

            });


            card.classList.remove(
                'border-gray-200'
            );

            card.classList.add(
                'border-blue-600',
                'bg-blue-50'
            );


            document
                .querySelectorAll('.check-metode')
                .forEach(function (check) {

                    check.classList.add('hidden');

                });


            const check =
                card.querySelector('.check-metode');

            if (check) {

                check.classList.remove('hidden');

            }


            if (infoNamaMetode) {

                infoNamaMetode.textContent =
                    card.dataset.nama;

            }


            if (infoNomor) {

                infoNomor.textContent =
                    card.dataset.nomor;

            }


            if (infoAtasNama) {

                infoAtasNama.textContent =
                    card.dataset.atasNama;

            }

        });

    });

});

</script>

@endsection