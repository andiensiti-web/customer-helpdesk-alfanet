@extends('layouts.app')
@section('content')

<div class="w-full space-y-4 sm:space-y-6">

    {{-- HEADER --}}
    <div>
        <h1 class="text-2xl sm:text-3xl font-bold text-gray-800">
            Laporan
        </h1>
        <p class="text-sm sm:text-base text-gray-500 mt-1">
            Rekap data pelanggan dan transaksi Alfa Net
        </p>
    </div>

     {{-- FILTER --}}
    <div class="bg-white rounded-xl shadow-sm p-5">

    <form id="filterLaporan"
          method="GET"
          action="{{ url('/laporan-ditolak') }}">


        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-center">


            {{-- BULAN --}}
            <select
                name="bulan"
                class="border rounded-lg px-4 py-3">


                <option value="">
                    Semua Bulan
                </option>


                <option value="1"
                    {{ request('bulan') == '1' ? 'selected' : '' }}>
                    Januari
                </option>


                <option value="2"
                    {{ request('bulan') == '2' ? 'selected' : '' }}>
                    Februari
                </option>


                <option value="3"
                    {{ request('bulan') == '3' ? 'selected' : '' }}>
                    Maret
                </option>


                <option value="4"
                    {{ request('bulan') == '4' ? 'selected' : '' }}>
                    April
                </option>


                <option value="5"
                    {{ request('bulan') == '5' ? 'selected' : '' }}>
                    Mei
                </option>


                <option value="6"
                    {{ request('bulan') == '6' ? 'selected' : '' }}>
                    Juni
                </option>


                <option value="7"
                    {{ request('bulan') == '7' ? 'selected' : '' }}>
                    Juli
                </option>


                <option value="8"
                    {{ request('bulan') == '8' ? 'selected' : '' }}>
                    Agustus
                </option>


                <option value="9"
                    {{ request('bulan') == '9' ? 'selected' : '' }}>
                    September
                </option>


                <option value="10"
                    {{ request('bulan') == '10' ? 'selected' : '' }}>
                    Oktober
                </option>


                <option value="11"
                    {{ request('bulan') == '11' ? 'selected' : '' }}>
                    November
                </option>


                <option value="12"
                    {{ request('bulan') == '12' ? 'selected' : '' }}>
                    Desember
                </option>


            </select>




            {{-- SEARCH --}}
            <input
                type="text"
                name="search"
                value="{{ request('search') }}"
                placeholder="Cari laporan..."
                class="border rounded-lg px-4 py-3">

            {{-- EXPORT PDF --}}
            <a href="{{ route('laporan-pdf', ['bulan' => request('bulan')]) }}"
                class="bg-blue-600 text-white px-5 py-3 rounded-lg flex items-center gap-2">
                <i data-lucide="download" class="w-5 h-5"></i>
                Export PDF
            </a>

        </div>


    </form>


</div>

    {{-- CARD STATISTIK --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-blue-50 rounded-xl p-4 sm:p-5 flex items-center gap-4">
            <div class="bg-blue-100 p-3 rounded-lg">
                <i data-lucide="users"
                    class="w-5 h-5 text-blue-600">
                </i>
            </div>
            <div>
                <p class="text-gray-500 text-sm">
                    Total Pelanggan
                </p>
                <h2 class="text-2xl font-bold">
                    {{ $totalPelanggan }}
                </h2>
                <p class="text-xs text-gray-500">
                    Pelanggan Aktif
                </p>
            </div>
        </div>

        <div class="bg-yellow-50 rounded-xl p-4 sm:p-5 flex items-center gap-4">
            <div class="bg-yellow-100 p-3 rounded-lg">
                <i data-lucide="alert-triangle"
                    class="w-5 h-5 text-yellow-600">
                </i>
            </div>

            <div>
                <p class="text-gray-500 text-sm">
                    Total Gangguan
                </p>
                <h2 class="text-2xl font-bold">
                    {{ $totalGangguan }}
                </h2>
                <p class="text-xs text-gray-500">
                    Total laporan gangguan
                </p>
            </div>
        </div>

        <div class="bg-green-50 rounded-xl p-4 sm:p-5 flex items-center gap-4">
            <div class="bg-green-100 p-3 rounded-lg">
                <i data-lucide="credit-card"
                    class="w-5 h-5 text-green-600">
                </i>
            </div>
            <div>
                <p class="text-gray-500 text-sm">
                    Total Pembayaran
                </p>
                <h2 class="text-2xl font-bold">
                    {{ $totalPembayaran }}
                </h2>
                <p class="text-xs text-gray-500">
                    Pembayaran berhasil
                </p>
            </div>
        </div>

        <div class="bg-purple-50 rounded-xl p-4 sm:p-5 flex items-center gap-4">
            <div class="bg-purple-100 p-3 rounded-lg">
                <i data-lucide="trending-up"
                    class="w-5 h-5 text-purple-600">
                </i>
            </div>
            <div>
                <p class="text-gray-500 text-sm">
                    Pendapatan
                </p>
                <h2 class="text-xl sm:text-2xl font-bold">
                    Rp{{ number_format($pendapatan,0,',','.') }}
                </h2>
                <p class="text-xs text-gray-500">
                    Total pendapatan bulan ini
                </p>
            </div>
        </div>
    </div>

    {{-- TAB --}}
    <div class="bg-white rounded-xl shadow-sm p-4 sm:p-6">
        <div class="overflow-x-auto">
            <div class="flex gap-6 sm:gap-8 border-b min-w-max">
                <a href="/laporan-admin"
                    class="pb-3 text-sm text-gray-600">
                    Semua Laporan
                </a>
                <a href="/laporan-menunggu"
                    class="pb-3 text-sm text-gray-600 flex items-center gap-2">
                    Menunggu Approval
                    @if($jumlahMenunggu > 0)
                    <span class="bg-red-500 text-white text-xs px-2 py-0.5 rounded-full">
                        {{ $jumlahMenunggu }}
                    </span>
                    @endif
                </a>
                <a href="/laporan-disetujui"
                    class="pb-3 text-sm text-gray-600">
                    Disetujui
                </a>
                <a href="/laporan-ditolak"
                    class="pb-3 border-b-2 border-blue-600 text-blue-600 text-sm font-medium">
                    Ditolak
                </a>
                <a href="/laporan-keuangan"
                    class="pb-3 text-sm text-gray-600">
                    Laporan Keuangan
                </a>
            </div>
        </div>

        {{-- TABLE --}}
        <div class="mt-5">
            <h2 class="text-lg sm:text-xl font-semibold mb-5">
                Daftar Laporan Ditolak
            </h2>

            <div class="overflow-x-auto">
                <table class="w-full min-w-[950px] text-sm">
                    <thead>
                        <tr class="border-b text-gray-600">
                            <th class="text-left py-4 px-2">
                                No
                            </th>
                            <th class="text-left py-4 px-2">
                                Jenis Laporan
                            </th>
                            <th class="text-left py-4 px-2">
                                Pelanggan
                            </th>
                            <th class="text-left py-4 px-2">
                                Teknisi
                            </th>
                            <th class="text-left py-4 px-2">
                                Tanggal
                            </th>
                            <th class="text-left py-4 px-2">
                                Status
                            </th>
                            <th class="text-left py-4 px-2">
                                Catatan
                            </th>
                            <th class="text-left py-4 px-2">
                                Aksi
                            </th>
                        </tr>
                    </thead>

                    <tbody>
                    @forelse($laporans as $laporan)
                        <tr class="border-b">
                            <td class="py-4 px-2">
                                {{ $loop->iteration }}
                            </td>
                            <td class="py-4 px-2">
                                Perbaikan
                                <p class="text-xs text-gray-500">
                                    {{ $laporan->id_laporan ?? 'LPR-'.$laporan->id }}
                                </p>
                            </td>
                            <td class="py-4 px-2">
                                {{ $laporan->pelanggan?->user?->name ?? '-' }}
                                <p class="text-xs text-gray-500">
                                    {{ $laporan->pelanggan?->id_pelanggan ?? '-' }}
                                </p>
                            </td>
                            <td class="py-4 px-2">
                                {{ $laporan->teknisi?->nama ?? '-' }}
                            </td>
                            <td class="py-4 px-2">
                                {{ $laporan->created_at->format('d F Y') }}
                            </td>
                            <td class="py-4 px-2">
                                <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-xs">
                                    Ditolak
                                </span>
                            </td>
                            <td class="py-4 px-2">
                                {{ $laporan->catatan ?? '-' }}
                            </td>
                            <td class="py-4 px-2">
                                <a href="/laporan-detail/{{ $laporan->id }}">
                                    <i data-lucide="eye"
                                       class="w-5 h-5 text-blue-600">
                                    </i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8"
                                class="text-center py-10 text-gray-500">
                                Belum ada laporan yang ditolak.
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            @if($laporans->hasPages())
            <div class="mt-5">
                {{ $laporans->links() }}
            </div>
            @endif
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    if(window.lucide){
        lucide.createIcons();
    }
    const bulan =
        document.querySelector('select[name="bulan"]');
    if(bulan){
        bulan.addEventListener('change', function(){
            this.form.submit();
        });
    }
    const search =
        document.querySelector('input[name="search"]');
    if(search){
        search.addEventListener('keypress', function(e){
            if(e.key === 'Enter'){
                e.preventDefault();
                this.form.submit();
            }
        });
    }
});
</script>

@endsection