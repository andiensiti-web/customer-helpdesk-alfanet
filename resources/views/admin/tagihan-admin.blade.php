@extends('layouts.app')

@section('content')
<div class="space-y-6">

    <div class="flex justify-between items-start">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">
                Tagihan
            </h1>

            <p class="text-gray-500 mt-1">
                Kelola tagihan pelanggan Alfa Net
            </p>
        </div>

        <div class="flex gap-3">

            <button
                type="button"
                onclick="openGenerateModal()"
                class="bg-green-600 hover:bg-green-700 text-white px-4 py-3 rounded-lg flex items-center gap-2">
                <i data-lucide="file-plus" class="w-5 h-5"></i>
                Generate Tagihan
            </button>

            <a href="/tambah-tagihan"
                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-3 rounded-lg flex items-center gap-2">

                <i data-lucide="plus" class="w-5 h-5"></i>

                Tambah Tagihan
            </a>

        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-100 text-green-700 px-4 py-3 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="bg-red-100 text-red-700 px-4 py-3 rounded-lg">
            {{ $errors->first() }}
        </div>
    @endif

    <div class="flex flex-col md:flex-row gap-4">

        <div class="relative">

            <i data-lucide="search"
                class="absolute left-3 top-3 w-5 h-5 text-gray-400"></i>

            <input
                type="text"
                id="searchTagihan"
                placeholder="Cari pelanggan..."
                class="border rounded-lg pl-10 pr-4 py-3 w-64">

        </div>

        <select
            id="filterStatus"
            class="border rounded-lg px-4 py-3">

            <option value="semua">
                Semua Status
            </option>

            <option value="belum_bayar">
                Belum Bayar
            </option>

            <option value="lunas">
                Lunas
            </option>

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
                        ID Tagihan
                    </th>

                    <th class="px-5 py-4 text-left">
                        Pelanggan
                    </th>

                    <th class="px-5 py-4 text-left">
                        Paket
                    </th>

                    <th class="px-5 py-4 text-left">
                        Tanggal Terbit
                    </th>

                    <th class="px-5 py-4 text-left">
                        Jatuh Tempo
                    </th>

                    <th class="px-5 py-4 text-left">
                        Jumlah
                    </th>

                    <th class="px-5 py-4 text-left">
                        Status
                    </th>

                    <th class="px-5 py-4 text-center">
                        Aksi
                    </th>

                </tr>

            </thead>

            <tbody id="tabelTagihan" class="divide-y">

                @forelse($tagihan as $item)

                    <tr
                        data-status="{{ $item->status }}"
                        data-search="{{ strtolower(($item->pelanggan->user->name ?? '') . ' ' . ($item->pelanggan->id_pelanggan ?? '') . ' ' . ($item->kode_tagihan ?? '')) }}">

                        <td class="px-5 py-4">
                            {{ $loop->iteration }}
                        </td>

                        <td class="px-5 py-4 font-semibold">
                            {{ $item->kode_tagihan }}
                        </td>

                        <td class="px-5 py-4">

                            <div>

                                <p class="font-medium">
                                    {{ $item->pelanggan->user->name ?? '-' }}
                                </p>

                                <p class="text-xs text-gray-500">
                                    {{ $item->pelanggan->id_pelanggan ?? '-' }}
                                </p>

                            </div>

                        </td>

                        <td class="px-5 py-4">
                            {{ $item->paket->nama_paket ?? '-' }}
                        </td>

                        <td class="px-5 py-4">
                            {{ $item->tanggal_terbit?->format('d F Y') ?? '-' }}
                        </td>

                        <td class="px-5 py-4">
                            {{ $item->tanggal_jatuh_tempo?->format('d F Y') ?? '-' }}
                        </td>

                        <td class="px-5 py-4 font-medium">
                            Rp{{ number_format($item->jumlah, 0, ',', '.') }}
                        </td>

                        <td class="px-5 py-4">

                            @if($item->status === 'belum_bayar')

                                <span class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full text-xs">
                                    Belum Bayar
                                </span>

                            @elseif($item->status === 'lunas')

                                <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs">
                                    Lunas
                                </span>

                            @else

                                <span class="bg-gray-100 text-gray-700 px-3 py-1 rounded-full text-xs">
                                    {{ ucfirst(str_replace('_', ' ', $item->status)) }}
                                </span>

                            @endif

                        </td>

                        <td class="px-5 py-4 text-center">

                            <a
                                href="/detail-tagihan/{{ $item->id }}"
                                title="Detail Tagihan"
                                class="text-blue-600 hover:text-blue-800">

                                <i
                                    data-lucide="eye"
                                    class="w-5 h-5">
                                </i>

                            </a>

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td
                            colspan="9"
                            class="px-5 py-10 text-center text-gray-500">

                            Belum ada tagihan.

                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

    </div>

    {{-- PAGINATION --}}
    @if($tagihan->hasPages())
        <div class="flex justify-between items-center">

            <p class="text-sm text-gray-500">
                Menampilkan
                <span class="font-medium text-gray-700">
                    {{ $tagihan->firstItem() }}
                </span>
                -
                <span class="font-medium text-gray-700">
                    {{ $tagihan->lastItem() }}
                </span>
                dari
                <span class="font-medium text-gray-700">
                    {{ $tagihan->total() }}
                </span>
                tagihan
            </p>

            <div>
                {{ $tagihan->links() }}
            </div>

        </div>
    @endif

</div>
</div>
{{-- MODAL GENERATE TAGIHAN --}}

<div
    id="generateModal"
    class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">

    <div class="bg-white rounded-xl shadow-xl w-full max-w-md mx-4">

        <div class="flex justify-between items-center px-6 py-5 border-b">

            <div>

                <h2 class="text-xl font-bold text-gray-800">
                    Generate Tagihan
                </h2>

                <p class="text-sm text-gray-500 mt-1">
                    Buat tagihan pelanggan secara otomatis
                </p>

            </div>

            <button
                type="button"
                onclick="closeGenerateModal()"
                class="text-gray-400 hover:text-gray-600">

                <i
                    data-lucide="x"
                    class="w-5 h-5">
                </i>

            </button>

        </div>


        <form
            action="/generate-tagihan"
            method="POST">

            @csrf

            <div class="p-6 space-y-5">

                <div>

                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Bulan
                    </label>

                    <select
                        name="bulan"
                        required
                        class="w-full border rounded-lg px-4 py-3">

                        <option value="">
                            Pilih Bulan
                        </option>

                        @for($bulan = 1; $bulan <= 12; $bulan++)

                            <option value="{{ $bulan }}"
                                {{ $bulan == now()->month ? 'selected' : '' }}>

                                {{ \Carbon\Carbon::create()->month($bulan)->translatedFormat('F') }}

                            </option>

                        @endfor

                    </select>

                </div>


                <div>

                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Tahun
                    </label>

                    <select
                        name="tahun"
                        required
                        class="w-full border rounded-lg px-4 py-3">

                        @for($tahun = now()->year - 1; $tahun <= now()->year + 10; $tahun++)
                            <option value="{{ $tahun }}"
                                {{ $tahun == now()->year ? 'selected' : '' }}>
                                {{ $tahun }}
                            </option>
                        @endfor
                    </select>

                </div>


                <div class="bg-blue-50 text-blue-700 rounded-lg p-4 text-sm">

                    <div class="flex gap-3">

                        <i
                            data-lucide="info"
                            class="w-5 h-5 flex-shrink-0">
                        </i>

                        <p>
                            Sistem akan membuat tagihan untuk pelanggan
                            <strong>aktif</strong> berdasarkan paket dan
                            tanggal jatuh tempo masing-masing.
                        </p>

                    </div>

                </div>

            </div>


            <div class="flex justify-end gap-3 px-6 py-4 border-t bg-gray-50 rounded-b-xl">

                <button
                    type="button"
                    onclick="closeGenerateModal()"
                    class="px-4 py-2 border rounded-lg text-gray-700 hover:bg-gray-100">

                    Batal

                </button>

                <button
                    type="submit"
                    class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg">

                    Generate Tagihan

                </button>

            </div>

        </form>

    </div>

</div>



<script>

const searchTagihan =
    document.getElementById('searchTagihan');

const filterStatus =
    document.getElementById('filterStatus');


function filterTagihan() {

    const keyword =
        searchTagihan.value.toLowerCase().trim();

    const status =
        filterStatus.value;


    document
        .querySelectorAll('#tabelTagihan tr[data-status]')
        .forEach(row => {

            const cocokSearch =
                row.dataset.search.includes(keyword);

            const cocokStatus =
                status === 'semua' ||
                row.dataset.status === status;


            row.style.display =
                cocokSearch && cocokStatus
                    ? ''
                    : 'none';

        });

}


searchTagihan.addEventListener(
    'input',
    filterTagihan
);


filterStatus.addEventListener(
    'change',
    filterTagihan
);


function openGenerateModal() {

    const modal =
        document.getElementById('generateModal');

    modal.classList.remove('hidden');

    modal.classList.add('flex');

}


function closeGenerateModal() {

    const modal =
        document.getElementById('generateModal');

    modal.classList.add('hidden');

    modal.classList.remove('flex');

}


document
    .getElementById('generateModal')
    .addEventListener('click', function(event) {

        if (event.target === this) {
            closeGenerateModal();
        }

    });

</script>

@endsection