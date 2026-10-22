@extends('layouts.app')

@section('content')

<div class="space-y-6">

    <div class="flex justify-between items-start">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">
                Pembayaran
            </h1>
            <p class="text-gray-500 mt-1">
                Kelola pembayaran pelanggan Alfa Net
            </p>
        </div>

    </div>

    @if(session('success'))
        <div class="bg-green-100 text-green-700 px-4 py-3 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    <div class="flex flex-col md:flex-row gap-4">

        <div class="relative">
            <i data-lucide="search"
                class="absolute left-3 top-3 w-5 h-5 text-gray-400"></i>

            <input type="text"
                id="searchPembayaran"
                placeholder="Cari pelanggan..."
                class="border rounded-lg pl-10 pr-4 py-3 w-64">
        </div>

        <select id="filterStatus"
            class="border rounded-lg px-4 py-3">

            <option value="semua">
                Semua Status
            </option>

            <option value="menunggu_verifikasi">
                Menunggu Verifikasi
            </option>

            <option value="terverifikasi">
                Terverifikasi
            </option>

            <option value="ditolak">
                Ditolak
            </option>

        </select>

    </div>

    <div class="bg-white rounded-xl shadow-sm overflow-x-auto">

        <table class="w-full text-sm">

            <thead class="bg-gray-50 border-b">

                <tr>
                    <th class="px-5 py-4 text-left">No</th>
                    <th class="px-5 py-4 text-left">ID Pembayaran</th>
                    <th class="px-5 py-4 text-left">Pelanggan</th>
                    <th class="px-5 py-4 text-left">ID Tagihan</th>
                    <th class="px-5 py-4 text-left">Tanggal</th>
                    <th class="px-5 py-4 text-left">Nominal</th>
                    <th class="px-5 py-4 text-left">Status</th>
                    <th class="px-5 py-4 text-left">Bukti</th>
                    <th class="px-5 py-4 text-center">Aksi</th>
                </tr>

            </thead>

            <tbody id="tabelPembayaran" class="divide-y">

                @forelse($pembayaran as $item)

                    <tr
                        data-status="{{ $item->status }}"
                        data-search="{{ strtolower($item->pelanggan->user->name ?? '') }}">

                        <td class="px-5 py-4">
                            {{ $loop->iteration }}
                        </td>

                        <td class="px-5 py-4 font-semibold">
                            {{ $item->kode_pembayaran }}
                        </td>

                        <td class="px-5 py-4">
                            {{ $item->pelanggan->user->name ?? '-' }}
                        </td>

                        <td class="px-5 py-4">
                            {{ $item->tagihan->kode_tagihan ?? '-' }}
                        </td>

                        <td class="px-5 py-4">
                            {{ $item->tanggal_pembayaran->format('d F Y') }}
                        </td>

                        <td class="px-5 py-4">
                            Rp{{ number_format($item->jumlah, 0, ',', '.') }}
                        </td>

                        <td class="px-5 py-4">

                            @if($item->status === 'menunggu_verifikasi')

                                <span class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full text-xs">
                                    Menunggu Verifikasi
                                </span>

                            @elseif($item->status === 'terverifikasi')

                                <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs">
                                    Terverifikasi
                                </span>

                            @elseif($item->status === 'ditolak')

                                <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-xs">
                                    Ditolak
                                </span>

                            @else

                                <span class="bg-gray-100 text-gray-700 px-3 py-1 rounded-full text-xs">
                                    {{ ucfirst($item->status) }}
                                </span>

                            @endif

                        </td>

                        <td class="px-5 py-4">

                            @if($item->bukti_pembayaran)

                                <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs">
                                    Ada
                                </span>

                            @else

                                <span class="bg-gray-100 text-gray-500 px-3 py-1 rounded-full text-xs">
                                    Tidak Ada
                                </span>

                            @endif

                        </td>

                        <td class="px-5 py-4 text-center">

                            <a href="{{ url('/detail-pembayaran-admin/'.$item->id.'?from=pembayaran') }}"
                                title="Detail Pembayaran"
                                class="text-blue-600 hover:text-blue-800">

                                <i data-lucide="eye"
                                    class="w-5 h-5"></i>

                            </a>

                        </td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="9"
                            class="px-5 py-10 text-center text-gray-500">
                            Belum ada pembayaran.
                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

<script>

const searchPembayaran = document.getElementById('searchPembayaran');
const filterStatus = document.getElementById('filterStatus');

function filterPembayaran() {

    const keyword = searchPembayaran.value.toLowerCase().trim();
    const status = filterStatus.value;

    document.querySelectorAll('#tabelPembayaran tr[data-status]').forEach(row => {

        const cocokSearch =
            row.dataset.search.includes(keyword);

        const cocokStatus =
            status === 'semua' ||
            row.dataset.status === status;

        row.style.display =
            cocokSearch && cocokStatus ? '' : 'none';

    });
}

searchPembayaran.addEventListener('input', filterPembayaran);
filterStatus.addEventListener('change', filterPembayaran);

</script>

@endsection