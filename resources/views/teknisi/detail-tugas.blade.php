@extends('layouts.app')

@section('content')
@php
    $tiket = $tugas->tiketGangguan;
    $pelanggan = $tiket?->pelanggan;
    $userPelanggan = $pelanggan?->user;
    $paket = $pelanggan?->paket;
    $laporan = $tiket?->laporanGangguan;
    $perbaikan = $tugas->perbaikan;

    $status = $tugas->status ?? 'ditugaskan';

    $statusLabel = [
        'ditugaskan' => 'Ditugaskan',
        'menunggu' => 'Menunggu',
        'belum_dikerjakan' => 'Belum Dikerjakan',
        'diproses' => 'Sedang Diproses',
        'dalam_proses' => 'Sedang Diproses',
        'selesai' => 'Selesai',
    ];

    $statusClass = [
        'ditugaskan' => 'bg-yellow-100 text-yellow-700',
        'menunggu' => 'bg-yellow-100 text-yellow-700',
        'belum_dikerjakan' => 'bg-yellow-100 text-yellow-700',
        'diproses' => 'bg-blue-100 text-blue-700',
        'dalam_proses' => 'bg-blue-100 text-blue-700',
        'selesai' => 'bg-green-100 text-green-700',
    ];

    $prioritas = $laporan?->prioritas ?? $tiket?->prioritas ?? 'sedang';

    $prioritasClass = [
        'rendah' => 'bg-green-100 text-green-700',
        'sedang' => 'bg-yellow-100 text-yellow-700',
        'tinggi' => 'bg-orange-100 text-orange-700',
        'darurat' => 'bg-red-100 text-red-700',
    ];

    $tanggalPenugasan = $tugas->tanggal_penugasan
        ? \Carbon\Carbon::parse($tugas->tanggal_penugasan)->translatedFormat('d F Y')
        : '-';

    $tanggalLaporan = $laporan?->tanggal_laporan
        ? \Carbon\Carbon::parse($laporan->tanggal_laporan)->translatedFormat('d F Y')
        : '-';
@endphp

<div class="space-y-6">

    {{-- HEADER --}}
    <div>
        <h1 class="text-2xl font-bold text-gray-800">
            Detail Penugasan
        </h1>

        <p class="text-gray-500 mt-1">
            Informasi lengkap tugas perbaikan gangguan pelanggan.
        </p>

        <a href="/tugas-teknisi"
            class="inline-flex items-center gap-2 mt-3 text-blue-600 hover:text-blue-800 text-sm font-medium">
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            Kembali ke Tugas Saya
        </a>
    </div>

    {{-- PESAN --}}
    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 rounded-lg px-4 py-3 text-sm">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-50 border border-red-200 text-red-700 rounded-lg px-4 py-3 text-sm">
            {{ session('error') }}
        </div>
    @endif

    {{-- STATUS TUGAS --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-5">

            <div>
                <p class="text-sm text-gray-500">ID Tiket</p>

                <h2 class="text-2xl font-bold text-gray-800 mt-1">
                    {{ $tiket?->id_tiket ?? '-' }}
                </h2>
            </div>

            <div class="flex flex-wrap gap-3">

                <span class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-medium {{ $prioritasClass[$prioritas] ?? 'bg-gray-100 text-gray-700' }}">
                    Prioritas {{ ucfirst($prioritas) }}
                </span>

                <span class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-medium {{ $statusClass[$status] ?? 'bg-gray-100 text-gray-700' }}">
                    {{ $statusLabel[$status] ?? ucfirst($status) }}
                </span>

            </div>
        </div>
    </div>

    {{-- INFORMASI PENUGASAN --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">

        <div class="flex items-center gap-3 mb-6">

            <div class="w-10 h-10 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center">
                <i data-lucide="clipboard-list" class="w-5 h-5"></i>
            </div>

            <div>
                <h2 class="text-lg font-bold text-gray-800">
                    Informasi Penugasan
                </h2>

                <p class="text-sm text-gray-500">
                    Informasi penugasan teknisi untuk tiket ini.
                </p>
            </div>

        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

            <div>
                <p class="text-sm text-gray-500">ID Penugasan</p>

                <p class="text-sm font-semibold text-gray-800 mt-1">
                    #{{ $tugas->id }}
                </p>
            </div>

            <div>
                <p class="text-sm text-gray-500">Tanggal Penugasan</p>

                <p class="text-sm font-semibold text-gray-800 mt-1">
                    {{ $tanggalPenugasan }}
                </p>
            </div>

            <div>
                <p class="text-sm text-gray-500">Status Penugasan</p>

                <p class="text-sm font-semibold text-gray-800 mt-1">
                    {{ $statusLabel[$status] ?? ucfirst($status) }}
                </p>
            </div>

            <div>
                <p class="text-sm text-gray-500">Catatan Penugasan</p>

                <p class="text-sm font-semibold text-gray-800 mt-1">
                    {{ $tugas->catatan ?? '-' }}
                </p>
            </div>

        </div>
    </div>

    {{-- INFORMASI TEKNISI --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">

        <div class="flex items-center gap-3 mb-6">

            <div class="w-10 h-10 rounded-lg bg-purple-100 text-purple-600 flex items-center justify-center">
                <i data-lucide="user-cog" class="w-5 h-5"></i>
            </div>

            <div>
                <h2 class="text-lg font-bold text-gray-800">
                    Informasi Teknisi
                </h2>

                <p class="text-sm text-gray-500">
                    Teknisi yang menangani gangguan.
                </p>
            </div>

        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

            <div>
                <p class="text-sm text-gray-500">Nama Teknisi</p>

                <p class="text-sm font-semibold text-gray-800 mt-1">
                    {{ $tugas->teknisi?->nama ?? '-' }}
                </p>
            </div>

            <div>
                <p class="text-sm text-gray-500">ID Teknisi</p>

                <p class="text-sm font-semibold text-gray-800 mt-1">
                    {{ $tugas->teknisi?->id_teknisi ?? '-' }}
                </p>
            </div>

            <div>
                <p class="text-sm text-gray-500">No. Telepon</p>

                <p class="text-sm font-semibold text-gray-800 mt-1">
                    {{ $tugas->teknisi?->telepon ?? '-' }}
                </p>
            </div>

            <div>
                <p class="text-sm text-gray-500">Keahlian</p>

                <p class="text-sm font-semibold text-gray-800 mt-1">
                    {{ $tugas->teknisi?->keahlian ?? '-' }}
                </p>
            </div>

        </div>
    </div>

    {{-- INFORMASI PELANGGAN --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">

        <div class="flex items-center gap-3 mb-6">

            <div class="w-10 h-10 rounded-lg bg-green-100 text-green-600 flex items-center justify-center">
                <i data-lucide="user" class="w-5 h-5"></i>
            </div>

            <div>
                <h2 class="text-lg font-bold text-gray-800">
                    Informasi Pelanggan
                </h2>

                <p class="text-sm text-gray-500">
                    Data pelanggan yang mengalami gangguan.
                </p>
            </div>

        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

            {{-- NAMA --}}
            <div>
                <p class="text-sm text-gray-500">
                    Nama Pelanggan
                </p>

                <p class="text-sm font-semibold text-gray-800 mt-1">
                    {{ $userPelanggan?->name ?? '-' }}
                </p>
            </div>

            {{-- ID --}}
            <div>
                <p class="text-sm text-gray-500">
                    ID Pelanggan
                </p>

                <p class="text-sm font-semibold text-gray-800 mt-1">
                    {{ $pelanggan?->id_pelanggan ?? '-' }}
                </p>
            </div>

            {{-- NO TELEPON --}}
            <div>
                <p class="text-sm text-gray-500">
                    No. Telepon
                </p>

                <p class="text-sm font-semibold text-gray-800 mt-1">
                    {{ $userPelanggan?->telepon ?? '-' }}
                </p>
            </div>

            {{-- PAKET --}}
            <div>
                <p class="text-sm text-gray-500">
                    Paket Internet
                </p>

                <p class="text-sm font-semibold text-gray-800 mt-1">
                    {{ $paket?->nama_paket ?? '-' }}
                </p>
            </div>

            {{-- ALAMAT --}}
            <div class="md:col-span-2">
                <p class="text-sm text-gray-500">
                    Alamat
                </p>

                <p class="text-sm font-semibold text-gray-800 mt-1">
                    {{ $userPelanggan?->alamat ?? '-' }}
                </p>
            </div>

        </div>
    </div>

    {{-- DETAIL GANGGUAN --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">

        <div class="flex items-center gap-3 mb-6">

            <div class="w-10 h-10 rounded-lg bg-red-100 text-red-600 flex items-center justify-center">
                <i data-lucide="triangle-alert" class="w-5 h-5"></i>
            </div>

            <div>
                <h2 class="text-lg font-bold text-gray-800">
                    Detail Gangguan
                </h2>

                <p class="text-sm text-gray-500">
                    Informasi gangguan yang dilaporkan oleh pelanggan.
                </p>
            </div>

        </div>

        <div class="space-y-5">

            <div>
                <p class="text-sm text-gray-500">
                    Jenis Gangguan
                </p>

                <p class="text-base font-semibold text-gray-800 mt-1">
                    {{ $laporan?->jenis_gangguan ?? '-' }}
                </p>
            </div>

            <div>
                <p class="text-sm text-gray-500">
                    Tanggal Laporan
                </p>

                <p class="text-sm font-semibold text-gray-800 mt-1">
                    {{ $tanggalLaporan }}
                </p>
            </div>

            <div>
                <p class="text-sm text-gray-500 mb-2">
                    Keluhan Pelanggan
                </p>

                <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">

                    <p class="text-sm text-gray-700 leading-relaxed">
                        {{ $laporan?->deskripsi ?? 'Belum ada deskripsi gangguan.' }}
                    </p>

                </div>
            </div>

        </div>
    </div>

    {{-- HASIL PERBAIKAN --}}
    @if($perbaikan)

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">

            <div class="flex items-center gap-3 mb-6">

                <div class="w-10 h-10 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center">
                    <i data-lucide="wrench" class="w-5 h-5"></i>
                </div>

                <div>
                    <h2 class="text-lg font-bold text-gray-800">
                        Hasil Perbaikan
                    </h2>

                    <p class="text-sm text-gray-500">
                        Informasi hasil pekerjaan teknisi.
                    </p>
                </div>

            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                <div>
                    <p class="text-sm text-gray-500">
                        Status Perbaikan
                    </p>

                    <p class="text-sm font-semibold text-gray-800 mt-1">
                        {{ ucfirst($perbaikan->status) }}
                    </p>
                </div>

                <div>
                    <p class="text-sm text-gray-500">
                        Tanggal Perbaikan
                    </p>

                    <p class="text-sm font-semibold text-gray-800 mt-1">
                        {{ $perbaikan->tanggal_perbaikan?->translatedFormat('d F Y') ?? '-' }}
                    </p>
                </div>

                <div>
                    <p class="text-sm text-gray-500">
                        Hasil Pengecekan
                    </p>

                    <p class="text-sm text-gray-700 mt-1">
                        {{ $perbaikan->keluhan ?? '-' }}
                    </p>
                </div>

                <div>
                    <p class="text-sm text-gray-500">
                        Tindakan Perbaikan
                    </p>

                    <p class="text-sm text-gray-700 mt-1">
                        {{ $perbaikan->tindakan ?? '-' }}
                    </p>
                </div>

                <div class="md:col-span-2">
                    <p class="text-sm text-gray-500">
                        Catatan Teknisi
                    </p>

                    <p class="text-sm text-gray-700 mt-1">
                        {{ $perbaikan->catatan ?? '-' }}
                    </p>
                </div>

                @if($perbaikan->bukti_foto)

                    <div class="md:col-span-2">

                        <p class="text-sm text-gray-500 mb-2">
                            Bukti Perbaikan
                        </p>

                        <img
                            src="{{ asset('storage/'.$perbaikan->bukti_foto) }}"
                            alt="Bukti Perbaikan"
                            class="max-w-full md:max-w-xl max-h-80 object-cover rounded-lg border border-gray-200"
                        >

                    </div>

                @endif

            </div>
        </div>

    @endif

    {{-- TOMBOL AKSI --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">

        <div class="flex flex-col sm:flex-row justify-end gap-3">

            @if(in_array($status, ['ditugaskan', 'menunggu', 'belum_dikerjakan']))

                <form action="/detail-tugas/{{ $tugas->id }}/mulai" method="POST">
                    @csrf

                    <button type="submit"
                        class="inline-flex items-center justify-center gap-2 px-5 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium">

                        <i data-lucide="play" class="w-5 h-5"></i>

                        Mulai Perbaikan

                    </button>
                </form>

            @elseif(in_array($status, ['diproses', 'dalam_proses']))

                <a href="/perbaikan-teknisi/{{ $tugas->id }}"
                    class="inline-flex items-center justify-center gap-2 px-5 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium">

                    <i data-lucide="wrench" class="w-5 h-5"></i>

                    Lanjutkan Perbaikan

                </a>

            @elseif($status === 'selesai')

                <a href="/laporan-teknisi"
                    class="inline-flex items-center justify-center gap-2 px-5 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition font-medium">

                    <i data-lucide="file-text" class="w-5 h-5"></i>

                    Buat Laporan Teknisi

                </a>

            @endif

            <a href="/tugas-teknisi"
                class="inline-flex items-center justify-center gap-2 px-5 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition font-medium">

                <i data-lucide="arrow-left" class="w-5 h-5"></i>

                Kembali

            </a>

        </div>

    </div>

</div>

<script>
    lucide.createIcons();
</script>

@endsection