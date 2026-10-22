@extends('layouts.app')

@section('content')

<div class="space-y-6">
    <div>
        <h1 class="text-3xl font-bold text-gray-800">Penugasan Teknisi</h1>
        <p class="text-gray-500 mt-1">Kelola penugasan teknisi untuk menangani tiket gangguan pelanggan</p>
    </div>

    @if(session('success'))
        <div class="bg-green-100 text-green-700 px-4 py-3 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm overflow-x-auto">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 border-b">
            <tr>
                <th class="px-5 py-4 text-left">No</th>
                <th class="px-5 py-4 text-left">ID Tiket</th>
                <th class="px-5 py-4 text-left">Pelanggan</th>
                <th class="px-5 py-4 text-left">Gangguan</th>
                <th class="px-5 py-4 text-left">Teknisi</th>
                <th class="px-5 py-4 text-left">Jadwal</th>
                <th class="px-5 py-4 text-left">Status</th>
                <th class="px-5 py-4 text-center">Aksi</th>
            </tr>
        </thead>

        <tbody class="divide-y">
            @forelse($tiket as $item)
                <tr>
                    <td class="px-5 py-4">{{ $loop->iteration }}</td>

                    <td class="px-5 py-4 font-semibold">
                        {{ $item->id_tiket }}
                    </td>

                    <td class="px-5 py-4">
                        {{ $item->pelanggan->user->name ?? '-' }}
                    </td>

                    <td class="px-5 py-4">
                        {{ $item->jenis_gangguan }}
                    </td>

                    <td class="px-5 py-4">
                        {{ $item->penugasan->teknisi->nama ?? '-' }}
                    </td>

                    <td class="px-5 py-4">
                        {{ $item->penugasan?->tanggal_penugasan?->format('d F Y') ?? '-' }}
                    </td>

                    <td class="px-5 py-4">
                        @if($item->penugasan?->status === 'selesai')
                            <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs">
                                Selesai
                            </span>

                        @elseif($item->penugasan?->status === 'diproses')
                            <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-xs">
                                Sedang Diproses
                            </span>

                        @elseif($item->penugasan?->status === 'ditugaskan')
                            <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-xs">
                                Sudah Ditugaskan
                            </span>

                        @else
                            <span class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full text-xs">
                                Menunggu Teknisi
                            </span>
                        @endif
                    </td>

                    <td class="px-5 py-4 text-center">
                        @if($item->penugasan)
                            <a href="/detail-teknisi/{{ $item->id }}"
                                title="Lihat Detail">
                                <i data-lucide="eye"
                                    class="w-5 h-5 text-blue-600"></i>
                            </a>
                        @else
                            <a href="/detail-penugasan/{{ $item->id }}"
                                title="Tugaskan Teknisi">
                                <i data-lucide="user-plus"
                                    class="w-5 h-5 text-blue-600"></i>
                            </a>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="px-5 py-10 text-center text-gray-500">
                        Belum ada tiket gangguan.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
</div>

@endsection