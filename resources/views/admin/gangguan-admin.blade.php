@extends('layouts.app')

@section('content')

<div class="space-y-6">

    <div>
        <h1 class="text-3xl font-bold text-gray-800">
            Laporan Gangguan
        </h1>

        <p class="text-gray-500 mt-1">
            Kelola laporan gangguan yang dikirim pelanggan Alfa Net
        </p>
    </div>

    @if(session('success'))
        <div class="bg-green-100 text-green-700 px-4 py-3 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    <div class="flex justify-between gap-4">

        <div class="relative">
            <i data-lucide="search"
                class="absolute left-3 top-3 w-5 h-5 text-gray-400"></i>

            <input
                type="text"
                id="searchLaporan"
                placeholder="Cari laporan..."
                class="w-64 pl-10 py-3 border rounded-lg">
        </div>

        <select
            id="filterStatus"
            class="border rounded-lg px-4 py-3">

            <option value="semua">Semua Status</option>
            <option value="baru">Baru</option>
            <option value="diverifikasi">Diverifikasi</option>
            <option value="ditolak">Ditolak</option>
            <option value="selesai">Selesai</option>

        </select>

    </div>

    <div class="bg-white rounded-xl shadow-sm overflow-x-auto">

        <table class="w-full text-sm">

            <thead class="bg-gray-50 border-b">

                <tr>

                    <th class="px-5 py-4 text-left">
                        No
                    </th>

                    <th class="px-5 py-4 text-left">
                        ID Laporan
                    </th>

                    <th class="px-5 py-4 text-left">
                        Pelanggan
                    </th>

                    <th class="px-5 py-4 text-left">
                        Gangguan
                    </th>

                    <th class="px-5 py-4 text-left">
                        Status
                    </th>

                    <th class="px-5 py-4 text-left">
                        Tanggal
                    </th>

                    <th class="px-5 py-4 text-center">
                        Aksi
                    </th>

                </tr>

            </thead>

            <tbody id="tabelLaporan" class="divide-y">

                @forelse($laporans as $laporan)

                    <tr
                        data-status="{{ $laporan->status }}"
                        data-search="{{ strtolower(
                            $laporan->id_laporan . ' ' .
                            ($laporan->pelanggan->user->name ?? '') . ' ' .
                            $laporan->jenis_gangguan
                        ) }}">

                        <td class="px-5 py-4">
                            {{ $loop->iteration }}
                        </td>

                        <td class="px-5 py-4 font-semibold">
                            {{ $laporan->id_laporan }}
                        </td>

                        <td class="px-5 py-4">
                            {{ $laporan->pelanggan->user->name ?? '-' }}
                        </td>

                        <td class="px-5 py-4">
                            {{ $laporan->jenis_gangguan }}
                        </td>

                        <td class="px-5 py-4">

                            @if($laporan->status === 'baru')

                                <span class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full text-xs">
                                    Baru
                                </span>

                            @elseif($laporan->status === 'diverifikasi')

                                <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-xs">
                                    Diverifikasi
                                </span>

                            @elseif($laporan->status === 'ditolak')

                                <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-xs">
                                    Ditolak
                                </span>

                            @elseif($laporan->status === 'selesai')

                                <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs">
                                    Selesai
                                </span>

                            @else

                                <span class="bg-gray-100 text-gray-700 px-3 py-1 rounded-full text-xs">
                                    {{ ucfirst($laporan->status) }}
                                </span>

                            @endif

                        </td>

                        <td class="px-5 py-4">
                            {{ $laporan->tanggal_laporan->format('d F Y') }}
                        </td>

                        <td class="px-5 py-4 text-center">

                        @if($laporan->status === 'baru')

                            <a href="/gangguan-verifikasi/{{ $laporan->id }}"
                                title="Verifikasi"
                                class="text-blue-600 hover:text-blue-800">

                                <i data-lucide="clipboard-check"
                                    class="w-5 h-5"></i>

                            </a>

                        @elseif($laporan->status === 'diverifikasi')

                            <a href="/tambah-tiket/{{ $laporan->id }}"
                                title="Buat Tiket"
                                class="text-green-600 hover:text-green-800">

                                <i data-lucide="ticket"
                                    class="w-5 h-5"></i>

                            </a>

                        @elseif($laporan->status === 'ditolak')

                            <a href="/gangguan-detail/{{ $laporan->id }}"
                                title="Lihat Detail"
                                class="text-gray-600 hover:text-gray-800">

                                <i data-lucide="eye"
                                    class="w-5 h-5"></i>

                            </a>

                        @elseif($laporan->status === 'selesai')

                            <a href="/gangguan-detail/{{ $laporan->id }}"
                                title="Lihat Detail"
                                class="text-gray-600 hover:text-gray-800">

                                <i data-lucide="eye"
                                    class="w-5 h-5"></i>

                            </a>

                        @endif

                    </td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="7"
                            class="px-5 py-10 text-center text-gray-500">

                            Belum ada laporan gangguan.

                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

<script>

const searchLaporan = document.getElementById('searchLaporan');
const filterStatus = document.getElementById('filterStatus');

function filterLaporan() {

    const keyword = searchLaporan.value.toLowerCase().trim();
    const status = filterStatus.value;

    document.querySelectorAll('#tabelLaporan tr[data-status]').forEach(row => {

        const dataSearch = row.dataset.search;
        const dataStatus = row.dataset.status;

        const cocokSearch = dataSearch.includes(keyword);
        const cocokStatus =
            status === 'semua' || dataStatus === status;

        row.style.display =
            cocokSearch && cocokStatus ? '' : 'none';

    });
}

searchLaporan.addEventListener('input', filterLaporan);
filterStatus.addEventListener('change', filterLaporan);

</script>

@endsection