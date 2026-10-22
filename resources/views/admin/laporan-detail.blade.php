@extends('layouts.app')

@section('content')
<div class="w-full space-y-4 md:space-y-6">
    <div>
        <a href="/laporan-admin" class="text-blue-600 flex items-center gap-2 mb-3 text-sm md:text-base">
            <i data-lucide="arrow-left" class="w-5 h-5"></i>
            Kembali
        </a>
        <h1 class="text-2xl md:text-3xl font-bold text-gray-800">Detail Laporan</h1>
        <p class="text-sm md:text-base text-gray-500 mt-1">Pemeriksaan laporan teknisi sebelum approval admin</p>
    </div>

    <!-- Informasi Laporan -->
    <div class="bg-white rounded-xl shadow-sm p-4 md:p-6">
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3 mb-5 md:mb-6">
            <h2 class="text-lg md:text-xl font-semibold">Informasi Laporan</h2>

            @if($laporan->status === 'menunggu_approval')
                <span class="bg-yellow-100 text-yellow-700 px-3 py-2 rounded-full text-xs md:text-sm w-fit">
                    Menunggu Approval
                </span>
            @elseif($laporan->status === 'disetujui')
                <span class="bg-green-100 text-green-700 px-3 py-2 rounded-full text-xs md:text-sm w-fit">
                    Disetujui
                </span>
            @elseif($laporan->status === 'ditolak')
                <span class="bg-red-100 text-red-700 px-3 py-2 rounded-full text-xs md:text-sm w-fit">
                    Ditolak
                </span>
            @else
                <span class="bg-gray-100 text-gray-700 px-3 py-2 rounded-full text-xs md:text-sm w-fit">
                    {{ $laporan->status }}
                </span>
            @endif
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 md:gap-6">
            <div>
                <p class="text-gray-500 text-sm">ID Laporan</p>
                <p class="font-semibold mt-1">{{ $laporan->id_laporan }}</p>
            </div>

            <div>
                <p class="text-gray-500 text-sm">Pelanggan</p>
                <p class="font-semibold mt-1">
                    {{ $laporan->pelanggan?->user?->name ?? '-' }}
                </p>
                <p class="text-sm text-gray-500">
                    {{ $laporan->pelanggan?->id_pelanggan ?? '-' }}
                </p>
            </div>

            <div>
                <p class="text-gray-500 text-sm">Teknisi</p>
                <p class="font-semibold mt-1">
                    {{ $laporan->teknisi?->nama ?? '-' }}
                </p>
                <p class="text-sm text-gray-500">
                    {{ $laporan->teknisi?->id_teknisi ?? '-' }}
                </p>
            </div>

            <div>
                <p class="text-gray-500 text-sm">Tanggal Laporan</p>
                <p class="font-semibold mt-1">
                    {{ $laporan->tanggal_laporan?->format('d F Y') ?? '-' }}
                </p>
            </div>

            <div>
                <p class="text-sm text-gray-500">Waktu</p>
                <p class="text-lg font-semibold text-gray-800">
                    {{ $laporan->tanggal_laporan?->timezone('Asia/Jakarta')->format('H:i') ?? '-' }} WIB
                </p>
            </div>
        </div>
    </div>

    <!-- Hasil Perbaikan -->
    <div class="bg-white rounded-xl shadow-sm p-4 md:p-6">
        <h2 class="font-semibold text-lg mb-3 md:mb-4">Hasil Perbaikan</h2>
        <p class="text-sm md:text-base text-gray-600 leading-relaxed">
            {{ $laporan->hasil_perbaikan ?? '-' }}
        </p>
    </div>

    <!-- Tindakan -->
    <div class="bg-white rounded-xl shadow-sm p-4 md:p-6">
        <h2 class="font-semibold text-lg mb-3 md:mb-4">Tindakan Teknisi</h2>
        <p class="text-sm md:text-base text-gray-600 leading-relaxed">
            {{ $laporan->tindakan ?? '-' }}
        </p>
    </div>

    <!-- Bukti Perbaikan -->
    <div class="bg-white rounded-xl shadow-sm p-4 md:p-6">
        <h2 class="font-semibold text-lg mb-3 md:mb-4">Bukti Perbaikan</h2>

        @if($laporan->penugasan?->perbaikan?->bukti_foto)
            <div class="mt-2">
                <img
                    src="{{ asset('storage/'.$laporan->penugasan->perbaikan->bukti_foto) }}"
                    alt="Bukti Perbaikan"
                    class="w-full max-w-md rounded-lg border object-cover"
                >
            </div>
        @else
            <p class="text-sm text-gray-500">
                Tidak ada foto bukti perbaikan.
            </p>
        @endif
    </div>

    <!-- Catatan -->
    <div class="bg-white rounded-xl shadow-sm p-4 md:p-6">
        <h2 class="font-semibold text-lg mb-3">Catatan</h2>
        <p class="text-sm md:text-base text-gray-600 leading-relaxed">
            {{ $laporan->catatan ?? '-' }}
        </p>
    </div>

    <!-- Approval -->
    @if($laporan->status === 'menunggu_approval')
        <div class="bg-white rounded-xl shadow-sm p-4 md:p-6">
            <h2 class="font-semibold text-lg mb-3">Approval Laporan</h2>

            <p class="text-sm text-gray-500 mb-5">
                Periksa hasil laporan sebelum menentukan keputusan.
            </p>

            <div class="flex flex-col sm:flex-row gap-3">
                <form method="POST" action="/laporan-setujui/{{ $laporan->id }}" class="w-full sm:w-auto">
                    @csrf
                    @method('PUT')

                    <button
                        type="submit"
                        class="w-full sm:w-auto px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg flex items-center justify-center gap-2"
                    >
                        <i data-lucide="check" class="w-5 h-5"></i>
                        Setujui
                    </button>
                </form>

                <button
                    type="button"
                    onclick="document.getElementById('formTolak').classList.toggle('hidden')"
                    class="w-full sm:w-auto px-6 py-3 border border-red-500 text-red-600 hover:bg-red-50 rounded-lg flex items-center justify-center gap-2"
                >
                    <i data-lucide="x" class="w-5 h-5"></i>
                    Tolak
                </button>
            </div>

            <form
                id="formTolak"
                method="POST"
                action="/laporan-tolak/{{ $laporan->id }}"
                class="hidden mt-5"
            >
                @csrf
                @method('PUT')

                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Alasan Penolakan
                </label>

                <textarea
                    name="catatan"
                    rows="4"
                    required
                    class="w-full border rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-red-200 focus:border-red-400"
                    placeholder="Tuliskan bagian laporan yang perlu diperbaiki..."
                ></textarea>

                @error('catatan')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror

                <button
                    type="submit"
                    class="mt-3 px-5 py-2.5 bg-red-600 hover:bg-red-700 text-white rounded-lg"
                >
                    Kirim Penolakan
                </button>
            </form>
        </div>

    @elseif($laporan->status === 'ditolak' && $laporan->catatan)
        <div class="bg-red-50 border border-red-100 rounded-xl p-4 md:p-6">
            <h2 class="font-semibold text-lg text-red-700 mb-2">
                Catatan Penolakan
            </h2>

            <p class="text-sm md:text-base text-red-600 leading-relaxed">
                {{ $laporan->catatan }}
            </p>
        </div>
    @endif
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    lucide.createIcons();
});
</script>
@endsection