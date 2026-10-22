@extends('layouts.app')

@section('content')

<div class="space-y-5">

    {{-- HEADER --}}
    <div>
        <h1 class="text-2xl md:text-3xl font-bold text-gray-900">
            Data Pelanggan
        </h1>

        <p class="text-sm md:text-base text-gray-600 mt-1">
            Informasi pelanggan sebagai referensi teknisi.
        </p>
    </div>


    {{-- STATISTIK --}}
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4">

        {{-- TOTAL --}}
        <div class="bg-white rounded-xl border border-gray-200 p-5">

            <p class="text-sm text-gray-500">
                Total Pelanggan
            </p>

            <p class="text-3xl font-bold text-gray-800 mt-2">
                {{ $totalPelanggan }}
            </p>

        </div>


        {{-- AKTIF --}}
        <div class="bg-white rounded-xl border border-gray-200 p-5">

            <p class="text-sm text-gray-500">
                Pelanggan Aktif
            </p>

            <p class="text-3xl font-bold text-green-600 mt-2">
                {{ $pelangganAktif }}
            </p>

        </div>

            {{-- ISOLIR --}}
        <div class="bg-white rounded-xl border border-gray-200 p-5">

            <p class="text-sm text-gray-500">
                Pelanggan Isolir
            </p>

            <p class="text-3xl font-bold text-yellow-500 mt-2">
                {{ $pelangganIsolir }}
            </p>

        </div>

        {{-- NONAKTIF --}}
        <div class="bg-white rounded-xl border border-gray-200 p-5">

            <p class="text-sm text-gray-500">
                Pelanggan Nonaktif
            </p>

            <p class="text-3xl font-bold text-red-600 mt-2">
                {{ $pelangganNonaktif }}
            </p>

        </div>

    </div>


    {{-- FILTER --}}
    <div class="bg-white rounded-xl border border-gray-200 p-5">

        <form
            id="filterPelanggan"
            method="GET"
            action="/pelanggan-teknisi"
            class="flex flex-col md:flex-row gap-4 md:items-center md:justify-between"
        >

            {{-- SEARCH --}}
            <div class="relative w-full md:max-w-xl">

                <i
                    data-lucide="search"
                    class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400"
                ></i>

                <input
                    id="searchPelanggan"
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Cari nama, ID, atau nomor telepon..."
                    autocomplete="off"
                    class="w-full border border-gray-300 rounded-lg pl-11 pr-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                >

            </div>


            {{-- STATUS --}}
            <select
                id="statusPelanggan"
                name="status"
                class="border border-gray-300 rounded-lg px-4 py-3 text-sm bg-white focus:outline-none focus:ring-2 focus:ring-blue-500"
            >

                <option value="">
                    Semua Status
                </option>

                <option
                    value="aktif"
                    {{ request('status') === 'aktif' ? 'selected' : '' }}
                >
                    Aktif
                </option>

                <option
                    value="nonaktif"
                    {{ request('status') === 'nonaktif' ? 'selected' : '' }}
                >
                    Nonaktif
                </option>

            </select>

        </form>

    </div>


    {{-- TABEL --}}
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">

        {{-- JUDUL TABEL --}}
        <div class="px-5 py-4 border-b border-gray-200">

            <h2 class="font-bold text-gray-800">
                Daftar Pelanggan
            </h2>

            <p class="text-sm text-gray-500 mt-1">
                Seluruh pelanggan yang terdaftar di Alfa Net.
            </p>

        </div>


        {{-- TABLE --}}
        <div class="overflow-x-auto">

            <table class="w-full min-w-[1200px] text-sm">

                <thead class="bg-gray-50 border-b">

                    <tr>

                        <th class="px-5 py-4 text-left">
                            No
                        </th>

                        <th class="px-5 py-4 text-left">
                            ID Pelanggan
                        </th>

                        <th class="px-5 py-4 text-left">
                            Nama
                        </th>

                        <th class="px-5 py-4 text-left">
                            No. Telepon
                        </th>

                        <th class="px-5 py-4 text-left">
                            Alamat
                        </th>

                        <th class="px-5 py-4 text-left">
                            Paket
                        </th>

                        <th class="px-5 py-4 text-left">
                            Status
                        </th>

                        <th class="px-5 py-4 text-center">
                            Aksi
                        </th>

                    </tr>

                </thead>


                <tbody class="divide-y">

                    @forelse($pelanggans as $pelanggan)

                        <tr class="hover:bg-gray-50">

                            {{-- NO --}}
                            <td class="px-5 py-4">
                                {{ $pelanggans->firstItem() + $loop->index }}
                            </td>


                            {{-- ID PELANGGAN --}}
                            <td class="px-5 py-4 font-semibold">
                                {{ $pelanggan->id_pelanggan }}
                            </td>


                            {{-- NAMA --}}
                            <td class="px-5 py-4">
                                {{ $pelanggan->user?->name ?? '-' }}
                            </td>


                            {{-- TELEPON --}}
                            <td class="px-5 py-4">
                                {{ $pelanggan->user?->telepon ?? '-' }}
                            </td>


                            {{-- ALAMAT --}}
                            <td class="px-5 py-4 max-w-xs">
                                {{ $pelanggan->user?->alamat ?? '-' }}
                            </td>


                            {{-- PAKET --}}
                            <td class="px-5 py-4">
                                {{ $pelanggan->paket?->nama_paket ?? '-' }}
                            </td>


                            {{-- STATUS --}}
                            <td class="px-5 py-4">

                                @if($pelanggan->status === 'aktif')

                                    <span class="px-3 py-1.5 rounded-full bg-green-100 text-green-700 text-xs font-medium">
                                        Aktif
                                    </span>

                                @else

                                    <span class="px-3 py-1.5 rounded-full bg-red-100 text-red-600 text-xs font-medium">
                                        Nonaktif
                                    </span>

                                @endif

                            </td>


                            {{-- AKSI --}}
                            <td class="px-5 py-4 text-center">

                                <a
                                    href="/detail-pelanggan/{{ $pelanggan->id }}"
                                    class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-xs font-medium"
                                >

                                    <i
                                        data-lucide="eye"
                                        class="w-4 h-4"
                                    ></i>

                                    Detail

                                </a>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="8"
                                class="px-5 py-10 text-center text-gray-500"
                            >
                                Data pelanggan tidak ditemukan.
                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>


        {{-- PAGINATION --}}
        @if($pelanggans->hasPages())

            <div class="px-5 py-4 border-t">

                {{ $pelanggans->links() }}

            </div>

        @endif

    </div>

</div>


{{-- JAVASCRIPT --}}
<script>

document.addEventListener('DOMContentLoaded', function () {

    // Aktifkan icon Lucide
    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }


    const form = document.getElementById('filterPelanggan');
    const search = document.getElementById('searchPelanggan');
    const status = document.getElementById('statusPelanggan');

    let timer;
    search.addEventListener('input', function () {

        clearTimeout(timer);

        timer = setTimeout(function () {

            form.submit();

        }, 500);

    });

    status.addEventListener('change', function () {

        form.submit();

    });

});

</script>

@endsection