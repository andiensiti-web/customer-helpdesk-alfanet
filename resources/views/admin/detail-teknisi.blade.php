@extends('layouts.app')

@section('content')

<div class="max-w-5xl mx-auto space-y-6">

    <div>
        <a href="/penugasan-teknisi" class="text-blue-600 hover:text-blue-800">
            ← Kembali
        </a>

        <h1 class="text-3xl font-bold text-gray-800 mt-3">
            Detail Penugasan Teknisi
        </h1>

        <p class="text-gray-500 mt-1">
            Informasi teknisi yang ditugaskan untuk menangani tiket gangguan
        </p>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-6">

        <div class="flex items-center gap-2 mb-5">
            <i data-lucide="ticket" class="w-6 h-6 text-blue-600"></i>
            <h2 class="text-xl font-semibold">
                Informasi Tiket
            </h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

            <div>
                <p class="text-sm text-gray-500">ID Tiket</p>
                <p class="font-semibold">{{ $tiket->id_tiket }}</p>
            </div>

            <div>
                <p class="text-sm text-gray-500">ID Laporan</p>
                <p class="font-semibold">
                    {{ $tiket->laporanGangguan->id_laporan ?? '-' }}
                </p>
            </div>

            <div>
                <p class="text-sm text-gray-500">Pelanggan</p>
                <p class="font-semibold">
                    {{ $tiket->pelanggan->user->name ?? '-' }}
                </p>
            </div>

            <div>
                <p class="text-sm text-gray-500">Gangguan</p>
                <p class="font-semibold">
                    {{ $tiket->jenis_gangguan }}
                </p>
            </div>

            <div>
                <p class="text-sm text-gray-500">Prioritas</p>
                <p class="font-semibold">
                    {{ ucfirst($tiket->prioritas) }}
                </p>
            </div>

            <div>
                <p class="text-sm text-gray-500">Status Tiket</p>
                @if($tiket->status === 'selesai')
                    <span class="inline-block bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs">
                        Selesai
                    </span>
                @elseif($tiket->status === 'ditugaskan')
                    <span class="inline-block bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-xs">
                        Ditugaskan
                    </span>
                @elseif($tiket->status === 'diproses' || $tiket->status === 'dalam_proses')
                    <span class="inline-block bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full text-xs">
                        Sedang Diproses
                    </span>
                @elseif($tiket->status === 'baru')
                    <span class="inline-block bg-gray-100 text-gray-700 px-3 py-1 rounded-full text-xs">
                        Baru
                    </span>
                @elseif($tiket->status === 'ditutup')
                    <span class="inline-block bg-gray-100 text-gray-700 px-3 py-1 rounded-full text-xs">
                        Ditutup
                    </span>
                @else
                    <span class="inline-block bg-gray-100 text-gray-700 px-3 py-1 rounded-full text-xs">
                        {{ ucfirst(str_replace('_', ' ', $tiket->status ?? '-')) }}
                    </span>
                @endif
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-6">

        <div class="flex items-center gap-2 mb-5">
            <i data-lucide="user-check" class="w-6 h-6 text-blue-600"></i>

            <h2 class="text-xl font-semibold">
                Teknisi yang Ditugaskan
            </h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

            <div>
                <p class="text-sm text-gray-500">Nama Teknisi</p>
                <p class="font-semibold">
                    {{ $tiket->penugasan->teknisi->nama ?? '-' }}
                </p>
            </div>

            <div>
                <p class="text-sm text-gray-500">ID Teknisi</p>
                <p class="font-semibold">
                    {{ $tiket->penugasan->teknisi->id_teknisi ?? '-' }}
                </p>
            </div>

            <div>
                <p class="text-sm text-gray-500">Keahlian</p>
                <p class="font-semibold">
                    {{ $tiket->penugasan->teknisi->keahlian ?? '-' }}
                </p>
            </div>

            <div>
                <p class="text-sm text-gray-500">Telepon</p>
                <p class="font-semibold">
                    {{ $tiket->penugasan->teknisi->telepon ?? '-' }}
                </p>
            </div>

            <div>
                <p class="text-sm text-gray-500">Tanggal Penugasan</p>
                <p class="font-semibold">
                    {{ $tiket->penugasan?->tanggal_penugasan?->format('d F Y') ?? '-' }}
                </p>
            </div>

            <div>
                <p class="text-sm text-gray-500">Status Penugasan</p>

                <span class="inline-block bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-xs">
                    {{ ucfirst(str_replace('_', ' ', $tiket->penugasan->status ?? '-')) }}
                </span>
            </div>

        </div>

        <div class="mt-5">
            <p class="text-sm text-gray-500">
                Catatan
            </p>

            <div class="mt-2 bg-gray-50 rounded-lg p-4">
                {{ $tiket->penugasan->catatan ?? '-' }}
            </div>
        </div>

    </div>
</div>
    </div>

</div>

@endsection