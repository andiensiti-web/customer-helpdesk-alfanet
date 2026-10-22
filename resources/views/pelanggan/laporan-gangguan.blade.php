@extends('layouts.app')

@section('content')

<div class="max-w-7xl mx-auto">

    {{-- HEADER --}}
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900">
            Laporan Gangguan
        </h1>

        <p class="text-sm text-gray-600 mt-2">
            Laporkan gangguan jaringan dan lihat proses penanganan laporan Anda.
        </p>
    </div>

    {{-- PESAN BERHASIL --}}
    @if(session('success'))
        <div class="bg-green-100 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6">
            {{ session('success') }}
        </div>
    @endif

    {{-- PESAN ERROR --}}
    @if($errors->any())
        <div class="bg-red-100 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6">
            <ul class="list-disc ml-5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- INFORMASI PELANGGAN --}}
    <div class="bg-white border border-gray-200 rounded-xl p-5 mb-6">

        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">

            <div>
                <p class="text-xs text-gray-500">
                    Pelanggan
                </p>

                <p class="font-bold text-gray-900 mt-1">
                    {{ $pelanggan->nama }}
                </p>

                <p class="text-sm text-gray-500">
                    {{ $pelanggan->id_pelanggan }}
                </p>
            </div>

            <div>
                <p class="text-xs text-gray-500">
                    Paket Internet
                </p>

                <p class="font-semibold text-gray-900 mt-1">
                    {{ $pelanggan->paket?->nama_paket ?? '-' }}
                </p>
            </div>
            <div>
                <p class="text-xs text-gray-500">
                    Status Pelanggan
                </p>

                @if($pelanggan->status === 'aktif')
                    <span class="inline-flex mt-1 px-3 py-1.5 rounded-full bg-green-100 text-green-700 text-xs font-semibold">
                        Aktif
                    </span>
                @elseif($pelanggan->status === 'isolir')
                    <span class="inline-flex mt-1 px-3 py-1.5 rounded-full bg-yellow-100 text-yellow-700 text-xs font-semibold">
                        Isolir
                    </span>
                @elseif($pelanggan->status === 'nonaktif')
                    <span class="inline-flex mt-1 px-3 py-1.5 rounded-full bg-red-100 text-red-700 text-xs font-semibold">
                        Nonaktif
                    </span>
                @else
                    <span class="inline-flex mt-1 px-3 py-1.5 rounded-full bg-gray-100 text-gray-700 text-xs font-semibold">
                        {{ ucfirst($pelanggan->status ?? '-') }}
                    </span>
                @endif
            </div>

        </div>

    </div>

    {{-- TOMBOL BUAT LAPORAN --}}
    <div class="flex justify-end mb-5">

        <button
            type="button"
            onclick="bukaModalLaporan()"
            class="flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-5 py-3 rounded-lg font-semibold transition">

            <i data-lucide="plus" class="w-5 h-5"></i>

            Buat Laporan Gangguan

        </button>

    </div>

    {{-- RIWAYAT LAPORAN --}}
    <div class="bg-white border border-gray-200 rounded-xl overflow-hidden">

        <div class="p-5 border-b border-gray-200">

            <h2 class="font-semibold text-gray-900">
                Riwayat Laporan Gangguan
            </h2>

            <p class="text-sm text-gray-500 mt-1">
                Daftar laporan gangguan yang pernah Anda buat.
            </p>

        </div>

        <div class="overflow-x-auto">

            <table class="w-full text-sm">

                <thead class="bg-gray-50 border-b border-gray-200">

                    <tr class="text-left text-gray-600">

                        <th class="px-5 py-4 font-semibold">
                            ID Laporan
                        </th>

                        <th class="px-5 py-4 font-semibold">
                            Jenis Gangguan
                        </th>

                        <th class="px-5 py-4 font-semibold">
                            Tanggal
                        </th>

                        <th class="px-5 py-4 font-semibold">
                            Status
                        </th>

                        <th class="px-5 py-4 font-semibold text-center">
                            Aksi
                        </th>

                    </tr>

                </thead>

                <tbody class="divide-y divide-gray-200">

                    @forelse($laporans as $laporan)

                        <tr class="hover:bg-gray-50">

                            <td class="px-5 py-4 font-semibold text-gray-900">
                                {{ $laporan->id_laporan }}
                            </td>

                            <td class="px-5 py-4">
                                {{ $laporan->jenis_gangguan }}
                            </td>

                            <td class="px-5 py-4 text-gray-600">
                                {{ $laporan->tanggal_laporan?->format('d F Y') }}
                            </td>

                            <td class="px-5 py-4">

                                @php
                                    $perbaikan = $laporan->tiketGangguan?->penugasan?->perbaikan;

                                    $statusTampilan = $perbaikan?->status === 'selesai'
                                        ? 'selesai'
                                        : $laporan->status;
                                @endphp

                                @if($statusTampilan === 'baru')

                                    <span class="inline-flex px-3 py-1 rounded-full bg-yellow-100 text-yellow-700 text-xs font-semibold">
                                        Baru
                                    </span>

                                @elseif($statusTampilan === 'diproses')

                                    <span class="inline-flex px-3 py-1 rounded-full bg-blue-100 text-blue-700 text-xs font-semibold">
                                        Sedang Diproses
                                    </span>

                                @elseif($statusTampilan === 'diverifikasi')

                                    <span class="inline-flex px-3 py-1 rounded-full bg-blue-100 text-blue-700 text-xs font-semibold">
                                        Diverifikasi
                                    </span>

                                @elseif($statusTampilan === 'selesai')

                                    <span class="inline-flex px-3 py-1 rounded-full bg-green-100 text-green-700 text-xs font-semibold">
                                        Selesai
                                    </span>

                                @elseif($statusTampilan === 'ditolak')

                                    <span class="inline-flex px-3 py-1 rounded-full bg-red-100 text-red-700 text-xs font-semibold">
                                        Ditolak
                                    </span>

                                @else

                                    <span class="inline-flex px-3 py-1 rounded-full bg-gray-100 text-gray-700 text-xs font-semibold">
                                        {{ ucfirst($statusTampilan) }}
                                    </span>

                                @endif

                            </td>

                            <td class="px-5 py-4 text-center">

                                <button
                                    type="button"
                                    onclick="lihatDetail(
                                        @js($laporan->id_laporan),
                                        @js($laporan->jenis_gangguan),
                                        @js($laporan->tanggal_laporan?->format('d F Y')),
                                        @js($laporan->prioritas),
                                        @js($laporan->deskripsi),
                                        @js($statusTampilan)
                                    )"
                                    class="px-3 py-2 rounded-lg border border-blue-500 text-blue-600 hover:bg-blue-50 text-xs font-semibold">

                                    Detail

                                </button>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="5"
                                class="text-center py-10 text-gray-500">

                                Belum ada laporan gangguan.

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>


{{-- =========================
     MODAL BUAT LAPORAN
========================= --}}

<div
    id="modalLaporan"
    class="hidden fixed inset-0 z-50 bg-black/40 flex items-center justify-center p-4">

    <div class="bg-white rounded-xl w-full max-w-lg shadow-xl">

        {{-- HEADER MODAL --}}

        <div class="p-5 border-b border-gray-200 flex items-center justify-between">

            <div>

                <h2 class="text-xl font-bold text-gray-900">
                    Buat Laporan Gangguan
                </h2>

                <p class="text-sm text-gray-500 mt-1">
                    Isi informasi gangguan yang Anda alami.
                </p>

            </div>

            <button
                type="button"
                onclick="tutupModalLaporan()"
                class="text-gray-500 hover:text-gray-800">

                <i data-lucide="x" class="w-5 h-5"></i>

            </button>

        </div>


        {{-- FORM LAPORAN --}}

        <form
            action="{{ url('/laporan-gangguan') }}"
            method="POST">

            @csrf

            <div class="p-5 space-y-4">

                {{-- JENIS GANGGUAN --}}

                <div>

                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Jenis Gangguan
                    </label>

                    <select
                        name="jenis_gangguan"
                        required
                        class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:outline-none">

                        <option value="">
                            Pilih jenis gangguan
                        </option>

                        <option value="Internet Mati" @selected(old('jenis_gangguan') === 'Internet Mati')>
                            Internet Mati
                        </option>

                        <option value="Koneksi Lambat" @selected(old('jenis_gangguan') === 'Koneksi Lambat')>
                            Koneksi Lambat
                        </option>

                        <option value="Internet Tidak Stabil" @selected(old('jenis_gangguan') === 'Internet Tidak Stabil')>
                            Internet Tidak Stabil
                        </option>

                        <option value="Gangguan WiFi" @selected(old('jenis_gangguan') === 'Gangguan WiFi')>
                            Gangguan WiFi
                        </option>

                        <option value="Lainnya" @selected(old('jenis_gangguan') === 'Lainnya')>
                            Lainnya
                        </option>

                    </select>

                </div>


                {{-- DESKRIPSI --}}

                <div>

                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Deskripsi Gangguan
                    </label>

                    <textarea
                        name="deskripsi"
                        rows="4"
                        required
                        placeholder="Jelaskan gangguan yang Anda alami..."
                        class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:outline-none">{{ old('deskripsi') }}</textarea>

                </div>


                {{-- INFORMASI ML --}}

                <div class="bg-blue-50 border border-blue-100 rounded-lg px-4 py-3">

                    <p class="text-sm text-blue-700">
                        Prioritas laporan akan ditentukan otomatis oleh sistem berdasarkan jenis dan deskripsi gangguan.
                    </p>

                </div>

            </div>


            {{-- BUTTON --}}

            <div class="p-5 border-t border-gray-200 flex justify-end gap-3">

                <button
                    type="button"
                    onclick="tutupModalLaporan()"
                    class="px-5 py-2.5 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-50">

                    Batal

                </button>

                <button
                    type="submit"
                    class="px-5 py-2.5 rounded-lg bg-blue-600 hover:bg-blue-700 text-white font-semibold">

                    Kirim Laporan

                </button>

            </div>

        </form>

    </div>

</div>


{{-- =========================
     MODAL DETAIL LAPORAN
========================= --}}

<div
    id="detailLaporan"
    class="hidden fixed inset-0 z-50 bg-black/40 flex items-center justify-center p-4">

    <div class="bg-white rounded-xl w-full max-w-lg shadow-xl">

        {{-- HEADER --}}

        <div class="p-5 border-b border-gray-200 flex items-center justify-between">

            <div>

                <h2 class="text-xl font-bold text-gray-900">
                    Detail Laporan
                </h2>

                <p
                    id="detailId"
                    class="text-sm text-gray-500 mt-1">
                </p>

            </div>

            <button
                type="button"
                onclick="tutupDetail()"
                class="text-gray-500 hover:text-gray-800">

                <i data-lucide="x" class="w-5 h-5"></i>

            </button>

        </div>


        {{-- ISI DETAIL --}}

        <div class="p-5 space-y-4">

            <div class="grid grid-cols-2 gap-4">

                <div>

                    <p class="text-xs text-gray-500">
                        Jenis Gangguan
                    </p>

                    <p
                        id="detailJenis"
                        class="font-semibold text-gray-900 mt-1">
                    </p>

                </div>

                <div>

                    <p class="text-xs text-gray-500">
                        Tanggal Laporan
                    </p>

                    <p
                        id="detailTanggal"
                        class="font-semibold text-gray-900 mt-1">
                    </p>

                </div>

                <div>

                    <p class="text-xs text-gray-500">
                        Prioritas ML
                    </p>

                    <p
                        id="detailPrioritas"
                        class="font-semibold text-gray-900 mt-1">
                    </p>

                </div>

                <div>

                    <p class="text-xs text-gray-500">
                        Status
                    </p>

                    <p
                        id="detailStatus"
                        class="font-semibold text-gray-900 mt-1">
                    </p>

                </div>

            </div>


            <div>

                <p class="text-xs text-gray-500">
                    Deskripsi Gangguan
                </p>

                <div
                    id="detailDeskripsi"
                    class="mt-2 bg-gray-50 rounded-lg p-4 text-sm text-gray-700">
                </div>

            </div>

        </div>


        {{-- FOOTER --}}

        <div class="p-5 border-t border-gray-200 flex justify-end">

            <button
                type="button"
                onclick="tutupDetail()"
                class="px-5 py-2.5 rounded-lg bg-blue-600 hover:bg-blue-700 text-white font-semibold">

                Tutup

            </button>

        </div>

    </div>

</div>


{{-- =========================
     JAVASCRIPT
========================= --}}

<script>

function bukaModalLaporan() {
    document.getElementById('modalLaporan').classList.remove('hidden');
}

function tutupModalLaporan() {
    document.getElementById('modalLaporan').classList.add('hidden');
}

function lihatDetail(id, jenis, tanggal, prioritas, deskripsi, status) {

    document.getElementById('detailId').textContent = id;
    document.getElementById('detailJenis').textContent = jenis;
    document.getElementById('detailTanggal').textContent = tanggal || '-';

    document.getElementById('detailPrioritas').textContent =
        prioritas ? prioritas.toUpperCase() : '-';

    document.getElementById('detailDeskripsi').textContent = deskripsi || '-';
    document.getElementById('detailStatus').textContent = status || '-';

    document.getElementById('detailLaporan').classList.remove('hidden');
}

function tutupDetail() {
    document.getElementById('detailLaporan').classList.add('hidden');
}

lucide.createIcons();

</script>

@endsection