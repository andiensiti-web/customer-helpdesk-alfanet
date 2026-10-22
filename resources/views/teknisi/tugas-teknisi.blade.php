@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Tugas Saya</h1>
            <p class="text-sm text-gray-500 mt-1">Daftar tugas dan pekerjaan yang diberikan kepada teknisi</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-6">
            <div class="bg-white border border-gray-100 rounded-xl p-5 shadow-sm">
                <div class="flex items-center justify-between gap-3">
                    <div>
                        <p class="text-sm text-gray-500">Total Tugas</p>
                        <h2 class="text-3xl font-bold text-gray-800 mt-2">{{ $totalTugas }}</h2>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-blue-50 flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5h6m-7 4h8m-9 4h10m-9 4h6M5 3h14a2 2 0 012 2v14a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white border border-gray-100 rounded-xl p-5 shadow-sm">
                <div class="flex items-center justify-between gap-3">
                    <div>
                        <p class="text-sm text-gray-500">Belum Dikerjakan</p>
                        <h2 class="text-3xl font-bold text-gray-800 mt-2">{{ $belumDikerjakan }}</h2>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-purple-50 flex items-center justify-center">
                        <svg class="w-6 h-6 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 2m6-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white border border-gray-100 rounded-xl p-5 shadow-sm">
                <div class="flex items-center justify-between gap-3">
                    <div>
                        <p class="text-sm text-gray-500">Sedang Ditangani</p>
                        <h2 class="text-3xl font-bold text-gray-800 mt-2">{{ $sedangDitangani }}</h2>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-orange-50 flex items-center justify-center">
                        <svg class="w-6 h-6 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 2m6-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white border border-gray-100 rounded-xl p-5 shadow-sm">
                <div class="flex items-center justify-between gap-3">
                    <div>
                        <p class="text-sm text-gray-500">Selesai</p>
                        <h2 class="text-3xl font-bold text-gray-800 mt-2">{{ $selesai }}</h2>
                    </div>
                    <div class="w-12 h-12 rounded-xl bg-green-50 flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-5 border-b border-gray-100">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                    <div>
                        <h2 class="text-lg font-semibold text-gray-800">Daftar Tugas</h2>
                        <p class="text-sm text-gray-500 mt-1">Tugas perbaikan yang harus dikerjakan</p>
                    </div>

                    <form method="GET" action="/tugas-teknisi" class="flex flex-col sm:flex-row gap-3">
                        <div class="relative">
                            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35m2.35-5.65a8 8 0 11-16 0 8 8 0 0116 0z"/>
                            </svg>
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari tugas..." class="w-full sm:w-64 pl-10 pr-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-blue-100 focus:border-blue-400 outline-none">
                        </div>

                        <select name="status" onchange="this.form.submit()" class="w-full sm:w-48 px-4 py-2.5 border border-gray-200 rounded-lg text-sm text-gray-600 focus:ring-2 focus:ring-blue-100 focus:border-blue-400 outline-none">
                            <option value="">Semua Status</option>
                            <option value="ditugaskan" {{ request('status') === 'ditugaskan' ? 'selected' : '' }}>Belum Dikerjakan</option>
                            <option value="menunggu" {{ request('status') === 'menunggu' ? 'selected' : '' }}>Menunggu</option>
                            <option value="diproses" {{ request('status') === 'diproses' ? 'selected' : '' }}>Sedang Ditangani</option>
                            <option value="dalam_proses" {{ request('status') === 'dalam_proses' ? 'selected' : '' }}>Dalam Proses</option>
                            <option value="selesai" {{ request('status') === 'selesai' ? 'selected' : '' }}>Selesai</option>
                        </select>

                        <button type="submit" class="hidden">Cari</button>
                    </form>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full min-w-[900px]">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-100">
                            <th class="px-5 py-4 text-left text-sm font-semibold text-gray-600">ID Tugas</th>
                            <th class="px-5 py-4 text-left text-sm font-semibold text-gray-600">Pelanggan</th>
                            <th class="px-5 py-4 text-left text-sm font-semibold text-gray-600">Keluhan</th>
                            <th class="px-5 py-4 text-left text-sm font-semibold text-gray-600">Tanggal</th>
                            <th class="px-5 py-4 text-left text-sm font-semibold text-gray-600">Prioritas</th>
                            <th class="px-5 py-4 text-left text-sm font-semibold text-gray-600">Status</th>
                            <th class="px-5 py-4 text-center text-sm font-semibold text-gray-600">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($tugas as $item)
                            @php
                                $tiket = $item->tiketGangguan;
                                $laporan = $tiket?->laporanGangguan;
                                $status = $item->status;
                                $prioritas = $laporan?->prioritas ?? 'sedang';
                            @endphp

                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-5 py-4">
                                    <span class="font-semibold text-blue-600">
                                        {{ $tiket->id_tiket ?? 'TKT-'.$item->id }}
                                    </span>
                                </td>

                                <td class="px-5 py-4">
                                    <div>
                                        <p class="font-medium text-gray-800">
                                            {{ $tiket?->pelanggan?->user?->name ?? 'Pelanggan' }}
                                        </p>
                                        <p class="text-xs text-gray-500 mt-1">
                                            {{ $tiket?->pelanggan?->id_pelanggan ?? '-' }}
                                        </p>
                                    </div>
                                </td>

                                <td class="px-5 py-4">
                                    <p class="text-sm text-gray-600 max-w-xs truncate">
                                        {{ $laporan?->jenis_gangguan ?? $laporan?->deskripsi ?? 'Gangguan pelanggan' }}
                                    </p>
                                </td>

                                <td class="px-5 py-4">
                                    <span class="text-sm text-gray-600">
                                        {{ $item->tanggal_penugasan ? $item->tanggal_penugasan->format('d M Y') : '-' }}
                                    </span>
                                </td>

                                <td class="px-5 py-4">
                                    @if($prioritas === 'tinggi')
                                        <span class="inline-flex px-3 py-1 rounded-full text-xs font-medium bg-red-50 text-red-500">Tinggi</span>
                                    @elseif($prioritas === 'rendah')
                                        <span class="inline-flex px-3 py-1 rounded-full text-xs font-medium bg-green-50 text-green-600">Rendah</span>
                                    @else
                                        <span class="inline-flex px-3 py-1 rounded-full text-xs font-medium bg-yellow-50 text-yellow-600">Sedang</span>
                                    @endif
                                </td>

                                <td class="px-5 py-4">
                                    @if(in_array($status, ['ditugaskan', 'menunggu', 'belum_dikerjakan']))
                                        <span class="inline-flex px-3 py-1 rounded-full text-xs font-medium bg-purple-50 text-purple-600">
                                            Belum Dikerjakan
                                        </span>
                                    @elseif(in_array($status, ['diproses', 'dalam_proses']))
                                        <span class="inline-flex px-3 py-1 rounded-full text-xs font-medium bg-orange-50 text-orange-600">
                                            Sedang Ditangani
                                        </span>
                                    @elseif($status === 'selesai')
                                        <span class="inline-flex px-3 py-1 rounded-full text-xs font-medium bg-green-50 text-green-600">
                                            Selesai
                                        </span>
                                    @else
                                        <span class="inline-flex px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-600">
                                            {{ ucfirst(str_replace('_', ' ', $status)) }}
                                        </span>
                                    @endif
                                </td>

                                <td class="px-5 py-4 text-center">
                                    <a href="/detail-tugas/{{ $item->id }}" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.46 12C3.73 7.94 7.52 5 12 5s8.27 2.94 9.54 7c-1.27 4.06-5.06 7-9.54 7s-8.27-2.94-9.54-7z"/>
                                        </svg>
                                        Detail
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-5 py-12 text-center">
                                    <div class="w-14 h-14 mx-auto rounded-full bg-gray-100 flex items-center justify-center">
                                        <svg class="w-7 h-7 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5h6m-7 4h8m-9 4h10m-9 4h6M5 3h14a2 2 0 012 2v14a2 2 0 01-2-2V5a2 2 0 012-2z"/>
                                        </svg>
                                    </div>
                                    <p class="text-sm font-medium text-gray-600 mt-3">Belum ada tugas</p>
                                    <p class="text-xs text-gray-400 mt-1">Tugas yang diberikan Admin akan muncul di sini.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($tugas->hasPages())
                <div class="px-5 py-4 border-t border-gray-100">
                    {{ $tugas->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection