@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">

    <div>
        <a href="/gangguan-admin" class="text-blue-600">← Kembali</a>
        <h1 class="text-3xl font-bold text-gray-800 mt-3">Detail Laporan Gangguan</h1>
        <p class="text-gray-500 mt-1">Detail laporan yang dikirim pelanggan.</p>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-6">

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

            <div>
                <p class="text-sm text-gray-500">ID Laporan</p>
                <p class="font-semibold mt-1">{{ $laporan->id_laporan }}</p>
            </div>

            <div>
                <p class="text-sm text-gray-500">Pelanggan</p>
                <p class="font-semibold mt-1">
                    {{ $laporan->pelanggan->user->name ?? '-' }}
                </p>
            </div>

            <div>
                <p class="text-sm text-gray-500">Jenis Gangguan</p>
                <p class="font-semibold mt-1">{{ $laporan->jenis_gangguan }}</p>
            </div>

            <div>
                <p class="text-sm text-gray-500">Tanggal</p>
                <p class="font-semibold mt-1">
                    {{ $laporan->tanggal_laporan->format('d F Y') }}
                </p>
            </div>

            <div>
                <p class="text-sm text-gray-500">Prioritas</p>
                <p class="font-semibold mt-1">{{ ucfirst($laporan->prioritas) }}</p>
            </div>

            <div>
                <p class="text-sm text-gray-500">Status</p>
                <span class="inline-block mt-1 bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full text-xs">
                    {{ ucfirst($laporan->status) }}
                </span>
            </div>

        </div>

        <div class="mt-6">
            <p class="text-sm text-gray-500">Deskripsi Gangguan</p>
            <div class="bg-gray-50 rounded-lg p-4 mt-2">
                {{ $laporan->deskripsi }}
            </div>
        </div>

        <div class="flex justify-end gap-3 mt-6">

            <a href="/gangguan-verifikasi/{{ $laporan->id }}"
               class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-3 rounded-lg">
                Verifikasi Laporan
            </a>

        </div>

    </div>

</div>
@endsection