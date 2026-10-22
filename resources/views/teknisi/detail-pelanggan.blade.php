@extends('layouts.app')

@section('content')
<div class="space-y-5">

    <div>

        <a
            href="/pelanggan-teknisi"
            class="inline-flex items-center gap-2 text-blue-600 text-sm mb-3"
        >
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            Kembali ke Data Pelanggan
        </a>

        <h1 class="text-2xl md:text-3xl font-bold text-gray-900">
            Detail Pelanggan
        </h1>

        <p class="text-gray-600 mt-1">
            Informasi lengkap pelanggan dan riwayat gangguan.
        </p>

    </div>

    {{-- INFORMASI PELANGGAN --}}
    <div class="bg-white rounded-xl border border-gray-200 p-5 md:p-6">

        <div class="flex items-center gap-4 pb-5 mb-5 border-b">

            <div class="w-14 h-14 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center">
                <i data-lucide="user" class="w-7 h-7"></i>
            </div>

            <div>

                <h2 class="text-xl font-bold">
                    {{ $pelanggan->user?->name ?? '-' }}
                </h2>

                <p class="text-sm text-gray-500">
                    {{ $pelanggan->id_pelanggan }}
                </p>

            </div>

        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

            <div>
                <p class="text-xs text-gray-500">
                    ID Pelanggan
                </p>

                <p class="font-semibold mt-1">
                    {{ $pelanggan->id_pelanggan }}
                </p>
            </div>

            <div>
                <p class="text-xs text-gray-500">
                    Status
                </p>

                @if($pelanggan->status === 'aktif')

                    <span class="inline-block mt-1 px-3 py-1.5 rounded-full bg-green-100 text-green-700 text-xs">
                        Aktif
                    </span>

                @else

                    <span class="inline-block mt-1 px-3 py-1.5 rounded-full bg-red-100 text-red-600 text-xs">
                        Nonaktif
                    </span>

                @endif
            </div>

            <div>
                <p class="text-xs text-gray-500">
                    Nama
                </p>

                <p class="font-semibold mt-1">
                    {{ $pelanggan->user?->name ?? '-' }}
                </p>
            </div>

            <div>
                <p class="text-xs text-gray-500">
                    Username
                </p>

                <p class="font-semibold mt-1">
                    {{ $pelanggan->user?->username ?? '-' }}
                </p>
            </div>

            <div>
                <p class="text-xs text-gray-500">
                    Email
                </p>

                <p class="font-semibold mt-1">
                    {{ $pelanggan->user?->email ?? '-' }}
                </p>
            </div>

            <div>
                <p class="text-xs text-gray-500">
                    No. Telepon
                </p>

                <p class="font-semibold mt-1">
                    {{ $pelanggan->user?->telepon ?? '-' }}
                </p>
            </div>

            <div>
                <p class="text-xs text-gray-500">
                    Paket Internet
                </p>

                <p class="font-semibold mt-1">
                    {{ $pelanggan->paket?->nama_paket ?? '-' }}
                </p>
            </div>

            <div>
                <p class="text-xs text-gray-500">
                    Tipe Pelanggan
                </p>

                <p class="font-semibold mt-1">
                    {{ ucfirst($pelanggan->tipe_pelanggan ?? '-') }}
                </p>
            </div>

            <div>
                <p class="text-xs text-gray-500">
                    Tanggal Mulai
                </p>

                <p class="font-semibold mt-1">
                    {{ $pelanggan->tanggal_mulai?->format('d F Y') ?? '-' }}
                </p>
            </div>

            {{-- ALAMAT DARI USERS --}}
            <div class="md:col-span-2">

                <p class="text-xs text-gray-500">
                    Alamat Lengkap
                </p>

                <div class="bg-gray-50 rounded-lg p-4 mt-1 text-sm text-gray-700">
                    {{ $pelanggan->user?->alamat ?? '-' }}
                </div>

            </div>

            <div class="md:col-span-2">

                <p class="text-xs text-gray-500">
                    Catatan Pelanggan
                </p>

                <div class="bg-gray-50 rounded-lg p-4 mt-1 text-sm text-gray-700">
                    {{ $pelanggan->catatan ?? '-' }}
                </div>

            </div>

        </div>

    </div>

    {{-- RIWAYAT GANGGUAN --}}
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">

        <div class="p-5 border-b">

            <h2 class="font-bold text-gray-800">
                Riwayat Gangguan
            </h2>

            <p class="text-sm text-gray-500 mt-1">
                Riwayat laporan gangguan pelanggan.
            </p>

        </div>

        <div class="overflow-x-auto">

            <table class="w-full min-w-[900px] text-sm">

                <thead class="bg-gray-50 border-b">

                    <tr>

                        <th class="px-5 py-4 text-left">
                            ID Laporan
                        </th>

                        <th class="px-5 py-4 text-left">
                            Jenis Gangguan
                        </th>

                        <th class="px-5 py-4 text-left">
                            Deskripsi
                        </th>

                        <th class="px-5 py-4 text-left">
                            Prioritas
                        </th>

                        <th class="px-5 py-4 text-left">
                            Status
                        </th>

                        <th class="px-5 py-4 text-left">
                            Tanggal
                        </th>

                    </tr>

                </thead>

                <tbody class="divide-y">

                    @forelse($pelanggan->laporanGangguan as $laporan)

                        <tr class="hover:bg-gray-50">

                            <td class="px-5 py-4 font-semibold">
                                {{ $laporan->id_laporan }}
                            </td>

                            <td class="px-5 py-4">
                                {{ $laporan->jenis_gangguan }}
                            </td>

                            <td class="px-5 py-4 max-w-sm">
                                {{ $laporan->deskripsi }}
                            </td>

                            <td class="px-5 py-4">
                                {{ ucfirst($laporan->prioritas ?? '-') }}
                            </td>

                            <td class="px-5 py-4">

                                <span class="px-3 py-1.5 rounded-full bg-gray-100 text-gray-700 text-xs">
                                    {{ ucfirst(str_replace('_', ' ', $laporan->status ?? '-')) }}
                                </span>

                            </td>

                            <td class="px-5 py-4">
                                {{ $laporan->tanggal_laporan?->format('d F Y') ?? '-' }}
                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="6"
                                class="px-5 py-10 text-center text-gray-500"
                            >
                                Belum ada riwayat gangguan.
                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

<script>
lucide.createIcons();
</script>

@endsection