@extends('layouts.app')

@section('content')

<div class="space-y-6">

    {{-- HEADER --}}
    <div>
        <h1 class="text-3xl font-bold text-gray-800">
            Laporan
        </h1>

        <p class="text-gray-500 mt-1">
            Rekap data pelanggan dan transaksi Alfa Net
        </p>
    </div>


    {{-- FILTER --}}
    <div class="bg-white rounded-xl shadow-sm p-5">

    <form id="filterLaporan" method="GET" action="{{ url('/laporan-admin') }}">

    <div class="grid grid-cols-1 md:grid-cols-5 gap-4">

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

        {{-- JENIS --}}
        <select
            name="jenis"
            class="border rounded-lg px-4 py-3">
            <option value="">
                Semua Jenis Laporan
            </option>
            <option value="perbaikan"
                {{ request('jenis') == 'perbaikan' ? 'selected' : '' }}>
                Perbaikan
            </option>
            <option value="pembayaran"
                {{ request('jenis') == 'pembayaran' ? 'selected' : '' }}>
                Pembayaran
            </option>
        </select>

        {{-- STATUS --}}
        <select
            name="status"
            class="border rounded-lg px-4 py-3">
            <option value="">
                Semua Status
            </option>
            <option value="menunggu_approval"
                {{ request('status') == 'menunggu_approval' ? 'selected' : '' }}>
                Menunggu
            </option>
            <option value="disetujui"
                {{ request('status') == 'disetujui' ? 'selected' : '' }}>
                Disetujui
            </option>
            <option value="terverifikasi"
                {{ request('status') == 'terverifikasi' ? 'selected' : '' }}>
                Terverifikasi
            </option>
            <option value="ditolak"
                {{ request('status') == 'ditolak' ? 'selected' : '' }}>
                Ditolak
            </option>
        </select>

        {{-- SEARCH --}}
        <input
            type="text"
            name="search"
            value="{{ request('search') }}"
            placeholder="Cari transaksi..."
            class="border rounded-lg px-4 py-3"
        >

        <a href="{{ route('laporan-pdf', ['bulan' => request('bulan')]) }}"
            class="bg-blue-600 text-white px-5 py-3 rounded-lg flex items-center gap-2">
            <i data-lucide="download" class="w-5 h-5"></i>
            Export PDF
            </a>
    </div>
    </form>
       
    </div>

        {{-- CARD STATISTIK --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">

        {{-- TOTAL PELANGGAN --}}
        <div class="bg-blue-50 rounded-xl p-5 flex items-center gap-4">

            <div class="bg-blue-100 p-3 rounded-lg">

                <i data-lucide="users"
                    class="w-6 h-6 text-blue-600">
                </i>

            </div>

            <div>

                <p class="text-gray-500 text-sm">
                    Total Pelanggan
                </p>

                <h2 class="text-3xl font-bold">
                    {{ $totalPelanggan }}
                </h2>

                <p class="text-xs text-gray-500">
                    Pelanggan aktif
                </p>

            </div>

        </div>



        {{-- TOTAL GANGGUAN --}}
        <div class="bg-yellow-50 rounded-xl p-5 flex items-center gap-4">

            <div class="bg-yellow-100 p-3 rounded-lg">

                <i data-lucide="alert-triangle"
                    class="w-6 h-6 text-yellow-600">
                </i>

            </div>

            <div>

                <p class="text-gray-500 text-sm">
                    Total Gangguan
                </p>

                <h2 class="text-3xl font-bold">
                    {{ $totalGangguan }}
                </h2>

                <p class="text-xs text-gray-500">
                    Laporan gangguan
                </p>

            </div>

        </div>



        {{-- TOTAL PEMBAYARAN --}}
        <div class="bg-green-50 rounded-xl p-5 flex items-center gap-4">

            <div class="bg-green-100 p-3 rounded-lg">

                <i data-lucide="credit-card"
                    class="w-6 h-6 text-green-600">
                </i>

            </div>

            <div>

                <p class="text-gray-500 text-sm">
                    Total Pembayaran
                </p>

                <h2 class="text-3xl font-bold">
                    {{ $totalPembayaran }}
                </h2>

                <p class="text-xs text-gray-500">
                    Pembayaran berhasil
                </p>

            </div>

        </div>



        {{-- PENDAPATAN --}}
        <div class="bg-purple-50 rounded-xl p-5 flex items-center gap-4">

            <div class="bg-purple-100 p-3 rounded-lg">

                <i data-lucide="trending-up"
                    class="w-6 h-6 text-purple-600">
                </i>

            </div>

            <div>

                <p class="text-gray-500 text-sm">
                    Pendapatan
                </p>

                <h2 class="text-2xl font-bold">
                    Rp{{ number_format($pendapatan,0,',','.') }}
                </h2>

                <p class="text-xs text-gray-500">
                    Total pendapatan
                </p>

            </div>

        </div>

    </div>



    {{-- TAB LAPORAN --}}
    <div class="bg-white rounded-xl shadow-sm p-5">


        <div class="flex gap-8 border-b overflow-x-auto">


            <a href="{{ url('/laporan-admin') }}"
               class="pb-3 border-b-2 border-blue-600 text-blue-600 font-medium">

                Semua Laporan

            </a>


            <a href="{{ url('/laporan-menunggu') }}"
               class="pb-3 text-gray-600">

                Menunggu Approval

            </a>


            <a href="{{ url('/laporan-disetujui') }}"
               class="pb-3 text-gray-600">

                Disetujui

            </a>


            <a href="{{ url('/laporan-ditolak') }}"
               class="pb-3 text-gray-600">

                Ditolak

            </a>

            <a href="{{ url('/laporan-keuangan') }}"
               class="pb-3 text-gray-600">

                Laporan Keuangan

            </a>
        </div>

        <h2 class="text-xl font-semibold mt-5">
            Daftar Laporan
        </h2>

            <div class="overflow-x-auto mt-5">

            <table class="w-full min-w-[1000px] text-sm">


                <thead>

                    <tr class="border-b text-gray-600">

                        <th class="py-4 px-3 text-left">
                            No
                        </th>

                        <th class="py-4 px-3 text-left">
                            Jenis Laporan
                        </th>

                        <th class="py-4 px-3 text-left">
                            Pelanggan
                        </th>

                        <th class="py-4 px-3 text-left">
                            Teknisi
                        </th>

                        <th class="py-4 px-3 text-left">
                            Periode
                        </th>

                        <th class="py-4 px-3 text-left">
                            Tanggal
                        </th>

                        <th class="py-4 px-3 text-left">
                            Status
                        </th>

                        <th class="py-4 px-3 text-left">
                            Aksi
                        </th>

                    </tr>

                </thead>



                <tbody>


                @forelse($laporans as $data)


                    <tr class="border-b hover:bg-gray-50">


                        <td class="py-4 px-3">
                            {{ $loop->iteration }}
                        </td>



                        {{-- JENIS --}}
                        <td class="py-4 px-3">


                            @if($data->jenis_data == 'pembayaran')


                                <p class="font-medium">
                                    Pembayaran
                                </p>


                                <p class="text-xs text-blue-600">
                                    {{ $data->kode_pembayaran ?? '-' }}
                                </p>


                            @else


                                <p class="font-medium">
                                    Perbaikan
                                </p>


                                <p class="text-xs text-blue-600">
                                    {{ $data->id_laporan ?? '-' }}
                                </p>


                            @endif


                        </td>


                        {{-- PELANGGAN --}}
                        <td class="py-4 px-3">

                            {{ $data->pelanggan?->user?->name ?? '-' }}

                        </td>


                        {{-- TEKNISI --}}
                        <td class="py-4 px-3">
                            @if($data->jenis_data == 'laporan')
                                {{ $data->teknisi?->nama ?? '-' }}
                            @else
                                -
                            @endif
                        </td>


                        {{-- PERIODE --}}
                        <td class="py-4 px-3">
                            {{ $data->created_at?->format('F Y') }}
                        </td>


                        {{-- TANGGAL --}}
                        <td class="py-4 px-3">
                            {{ $data->created_at?->format('d F Y') }}
                            <br>
                            <span class="text-xs text-gray-500">
                                {{ $data->created_at?->format('H:i') }}
                            </span>
                        </td>

                        {{-- STATUS --}}
                        <td class="py-4 px-3">
                            @if($data->status == 'disetujui')
                                <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full">
                                    Disetujui
                                </span>
                            @elseif($data->status == 'terverifikasi')
                                <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full">
                                    Terverifikasi
                                </span>
                            @elseif($data->status == 'ditolak')
                                <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full">
                                    Ditolak
                                </span>
                            @else
                                <span class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full">
                                    Menunggu
                                </span>
                            @endif
                        </td>

                        {{-- AKSI --}}
                        <td class="py-4 px-3">
                            <div class="flex gap-4">
                                @if($data->jenis_data == 'pembayaran')
                                    {{-- DETAIL PEMBAYARAN --}}
                                    <a href="{{ url('/detail-pembayaran-admin/'.$data->id.'?from=laporan') }}"
                                       class="text-blue-600"
                                       title="Detail Pembayaran">
                                        <i data-lucide="eye"
                                           class="w-5 h-5">
                                        </i>
                                    </a>

                                    {{-- PRINT PEMBAYARAN --}}
                                    <a href="{{ url('/print-pembayaran/'.$data->id) }}"
                                       target="_blank"
                                       class="text-gray-600"
                                       title="Print Pembayaran">
                                        <i data-lucide="printer"
                                           class="w-5 h-5">
                                        </i>
                                    </a>
                                @else

                                    {{-- DETAIL LAPORAN --}}
                                    <a href="{{ url('/laporan-detail/'.$data->id) }}"
                                       class="text-blue-600"
                                       title="Detail Laporan">
                                        <i data-lucide="eye"
                                           class="w-5 h-5">
                                        </i>
                                    </a>

                                    {{-- PRINT LAPORAN --}}
                                    <a href="{{ url('/laporan-pdf-satuan/'.$data->id) }}"
                                       target="_blank"
                                       class="text-gray-600"
                                       title="Print Laporan">
                                        <i data-lucide="printer"
                                           class="w-5 h-5">
                                        </i>
                                    </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8"
                            class="text-center py-10 text-gray-500">
                            Belum ada laporan.
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

            {{-- PAGINATION --}}
            @if(false)
            <div class="mt-5">
                {{ $laporans->links() }}
            </div>
            @endif
              
   </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function(){
    const form = document.querySelector(
        '#filterLaporan'
    );

    if(!form){
        return;
    }
    const filters = form.querySelectorAll(
        'select'
    );
    filters.forEach(function(filter){
        filter.addEventListener(
            'change',
            function(){
                form.submit();
            }
        );
    });

});
</script>

@endsection