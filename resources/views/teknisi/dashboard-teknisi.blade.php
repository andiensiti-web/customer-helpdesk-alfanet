@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Dashboard Teknisi</h1>
            <p class="text-sm text-gray-500 mt-1">Selamat datang, {{ $teknisi->nama ?? auth()->user()->name }}</p>
        </div>

        {{-- Statistik --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-6">
            <div class="w-full min-w-0 bg-blue-50 border border-blue-100 rounded-xl p-5 shadow-sm">
                <div class="flex items-center justify-between gap-3">
                    <div class="min-w-0">
                        <p class="text-sm font-medium text-blue-600">Total Tugas</p>
                        <h2 class="text-3xl font-bold text-gray-800 mt-2">{{ $totalTugas }}</h2>
                        <p class="text-xs text-gray-500 mt-2">Semua tugas</p>
                    </div>
                    <div class="w-11 h-11 shrink-0 rounded-xl bg-blue-100 flex items-center justify-center">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5h6m-7 4h8m-9 4h10m-9 4h6M5 3h14a2 2 0 012 2v14a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="w-full min-w-0 bg-orange-50 border border-orange-100 rounded-xl p-5 shadow-sm">
                <div class="flex items-center justify-between gap-3">
                    <div class="min-w-0">
                        <p class="text-sm font-medium text-orange-600">Tugas Hari Ini</p>
                        <h2 class="text-3xl font-bold text-gray-800 mt-2">{{ $tugasHariIni }}</h2>
                        <p class="text-xs text-gray-500 mt-2">Penugasan hari ini</p>
                    </div>
                    <div class="w-11 h-11 shrink-0 rounded-xl bg-orange-100 flex items-center justify-center">
                        <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10m-11 9h12a2 2 0 002-2V7a2 2 0 00-2-2H6a2 2 0 00-2 2v11a2 2 0 002 2z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="w-full min-w-0 bg-yellow-50 border border-yellow-100 rounded-xl p-5 shadow-sm">
                <div class="flex items-center justify-between gap-3">
                    <div class="min-w-0">
                        <p class="text-sm font-medium text-yellow-600">Sedang Ditangani</p>
                        <h2 class="text-3xl font-bold text-gray-800 mt-2">{{ $sedangDitangani }}</h2>
                        <p class="text-xs text-gray-500 mt-2">Tugas dalam proses</p>
                    </div>
                    <div class="w-11 h-11 shrink-0 rounded-xl bg-yellow-100 flex items-center justify-center">
                        <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 2m6-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="w-full min-w-0 bg-green-50 border border-green-100 rounded-xl p-5 shadow-sm">
                <div class="flex items-center justify-between gap-3">
                    <div class="min-w-0">
                        <p class="text-sm font-medium text-green-600">Tugas Selesai</p>
                        <h2 class="text-3xl font-bold text-gray-800 mt-2">{{ $tugasSelesai }}</h2>
                        <p class="text-xs text-gray-500 mt-2">Selesai minggu ini</p>
                    </div>
                    <div class="w-11 h-11 shrink-0 rounded-xl bg-green-100 flex items-center justify-center">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        {{-- Jadwal dan Status --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            {{-- Jadwal Kunjungan --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-5 border-b border-gray-100">
                    <div class="flex items-center justify-between gap-3">
                        <div>
                            <h2 class="text-lg font-semibold text-gray-800">Jadwal Kunjungan Hari Ini</h2>
                            <p class="text-sm text-gray-500 mt-1">Kunjungan pelanggan yang harus dilakukan</p>
                        </div>
                        <div class="w-10 h-10 shrink-0 rounded-lg bg-blue-50 flex items-center justify-center">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 4h10m-1 14H8a4 4 0 01-4-4V8a2 2 0 012-2h12a2 2 0 012 2v9a4 4 0 01-4 4z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="p-5">
                    @if($jadwalHariIni->count())
                        <div class="space-y-3">
                            @foreach($jadwalHariIni as $jadwal)
                                @php
                                    $pelanggan = $jadwal->pelanggan ?? $jadwal->tiketGangguan?->pelanggan;
                                    $namaPelanggan = $pelanggan?->user?->name ?? 'Pelanggan';
                                    $idPelanggan = $pelanggan?->id_pelanggan ?? '-';
                                    $catatan = $jadwal->catatan ?? $jadwal->tiketGangguan?->laporanGangguan?->deskripsi;
                                    $jam = $jadwal->jam_kunjungan ?? null;
                                    $status = $jadwal->status ?? 'terjadwal';
                                @endphp

                                <div class="border border-gray-100 rounded-lg p-4 hover:bg-gray-50 transition">
                                    <div class="flex items-start justify-between gap-4">
                                        <div class="flex items-start gap-3 min-w-0">
                                            <div class="w-10 h-10 shrink-0 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center font-semibold">
                                                {{ strtoupper(substr($namaPelanggan, 0, 1)) }}
                                            </div>

                                            <div class="min-w-0">
                                                <h3 class="font-semibold text-gray-800 truncate">
                                                    {{ $namaPelanggan }}
                                                </h3>
                                                <p class="text-sm text-gray-500 mt-1">
                                                    ID: {{ $idPelanggan }}
                                                </p>

                                                @if($catatan)
                                                    <p class="text-sm text-gray-500 mt-2 line-clamp-2">
                                                        {{ $catatan }}
                                                    </p>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="text-right shrink-0">
                                            <p class="font-semibold text-gray-800">
                                                {{ $jam ? \Carbon\Carbon::parse($jam)->format('H:i') : 'Hari ini' }}
                                            </p>

                                            @if(in_array($status, ['ditugaskan', 'menunggu', 'belum_dikerjakan', 'terjadwal']))
                                                <span class="inline-block mt-2 px-2.5 py-1 text-xs rounded-full bg-blue-50 text-blue-600">
                                                    {{ ucfirst(str_replace('_', ' ', $status)) }}
                                                </span>
                                            @elseif(in_array($status, ['diproses', 'dalam_proses']))
                                                <span class="inline-block mt-2 px-2.5 py-1 text-xs rounded-full bg-orange-50 text-orange-600">
                                                    Sedang Ditangani
                                                </span>
                                            @elseif($status === 'selesai')
                                                <span class="inline-block mt-2 px-2.5 py-1 text-xs rounded-full bg-green-50 text-green-600">
                                                    Selesai
                                                </span>
                                            @else
                                                <span class="inline-block mt-2 px-2.5 py-1 text-xs rounded-full bg-gray-100 text-gray-600">
                                                    {{ ucfirst(str_replace('_', ' ', $status)) }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-10">
                            <div class="w-14 h-14 mx-auto rounded-full bg-gray-100 flex items-center justify-center">
                                <svg class="w-7 h-7 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 4h10m-1 14H8a4 4 0 01-4-4V8a2 2 0 012-2h12a2 2 0 012 2v9a4 4 0 01-4 4z"/>
                                </svg>
                            </div>
                            <p class="text-sm font-medium text-gray-600 mt-3">Tidak ada jadwal kunjungan hari ini.</p>
                            <p class="text-xs text-gray-400 mt-1">Jadwal kunjungan akan muncul ketika Admin membuat jadwal.</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Status Perbaikan --}}
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-5 border-b border-gray-100">
                    <div class="flex items-center justify-between gap-3">
                        <div>
                            <h2 class="text-lg font-semibold text-gray-800">Status Perbaikan</h2>
                            <p class="text-sm text-gray-500 mt-1">Ringkasan pekerjaan teknisi</p>
                        </div>
                        <div class="w-10 h-10 shrink-0 rounded-lg bg-green-50 flex items-center justify-center">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="p-5">
                    @php
                        $belumMulai = $statusPerbaikan['belum_mulai'] ?? 0;
                        $dikerjakan = $statusPerbaikan['dikerjakan'] ?? 0;
                        $selesai = $statusPerbaikan['selesai'] ?? 0;
                        $totalPerbaikan = $belumMulai + $dikerjakan + $selesai;
                    @endphp

                    @if($totalPerbaikan > 0)
                        <div class="flex flex-col sm:flex-row items-center gap-8 py-5">
                            <div class="relative w-40 h-40 shrink-0">
                                @php
                                    $persenBelumMulai = ($belumMulai / $totalPerbaikan) * 360;
                                    $persenDikerjakan = (($belumMulai + $dikerjakan) / $totalPerbaikan) * 360;
                                @endphp

                                <div class="w-full h-full rounded-full"
                                     style="background: conic-gradient(#bfdbfe 0deg {{ $persenBelumMulai }}deg, #fde68a {{ $persenBelumMulai }}deg {{ $persenDikerjakan }}deg, #bbf7d0 {{ $persenDikerjakan }}deg 360deg);">
                                </div>

                                <div class="absolute inset-5 bg-white rounded-full flex flex-col items-center justify-center">
                                    <span class="text-2xl font-bold text-gray-800">{{ $totalPerbaikan }}</span>
                                    <span class="text-xs text-gray-500">Perbaikan</span>
                                </div>
                            </div>

                            <div class="w-full space-y-4">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        <span class="w-3 h-3 rounded-full bg-blue-200"></span>
                                        <span class="text-sm text-gray-600">Belum Mulai</span>
                                    </div>
                                    <span class="font-semibold text-gray-800">{{ $belumMulai }}</span>
                                </div>

                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        <span class="w-3 h-3 rounded-full bg-yellow-200"></span>
                                        <span class="text-sm text-gray-600">Dikerjakan</span>
                                    </div>
                                    <span class="font-semibold text-gray-800">{{ $dikerjakan }}</span>
                                </div>

                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        <span class="w-3 h-3 rounded-full bg-green-200"></span>
                                        <span class="text-sm text-gray-600">Selesai</span>
                                    </div>
                                    <span class="font-semibold text-gray-800">{{ $selesai }}</span>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-10">
                            <div class="w-16 h-16 mx-auto rounded-full bg-green-50 flex items-center justify-center">
                                <svg class="w-8 h-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <h3 class="font-semibold text-gray-800 mt-4">Belum Ada Data</h3>
                            <p class="text-sm text-gray-500 mt-1">Belum ada pekerjaan perbaikan yang tercatat.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection