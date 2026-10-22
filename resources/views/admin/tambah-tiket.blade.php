@extends('layouts.app')

@section('content')

<div class="max-w-5xl mx-auto space-y-6">

    <div>
        <a href="/gangguan-verifikasi/{{ $laporan->id }}" class="text-blue-600 hover:text-blue-800">
            ← Kembali
        </a>

        <h1 class="text-3xl font-bold text-gray-800 mt-3">
            Buat Tiket Gangguan
        </h1>

        <p class="text-gray-500 mt-1">
            Membuat tiket untuk proses penanganan teknisi
        </p>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-6">

        <div class="flex items-center gap-2 mb-6">
            <i data-lucide="ticket" class="w-6 h-6 text-blue-600"></i>

            <h2 class="text-xl font-semibold">
                Informasi Tiket
            </h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

            <div>
                <label class="text-sm text-gray-500">ID Tiket</label>
                <input type="text"
                    value="Otomatis saat disimpan"
                    disabled
                    class="w-full mt-2 border rounded-lg p-3 bg-gray-100">
            </div>

            <div>
                <label class="text-sm text-gray-500">ID Laporan</label>
                <input type="text"
                    value="{{ $laporan->id_laporan }}"
                    disabled
                    class="w-full mt-2 border rounded-lg p-3 bg-gray-100">
            </div>

            <div>
                <label class="text-sm text-gray-500">Pelanggan</label>
                <input type="text"
                    value="{{ $laporan->pelanggan->user->name ?? '-' }}"
                    disabled
                    class="w-full mt-2 border rounded-lg p-3 bg-gray-100">
            </div>

            <div>
                <label class="text-sm text-gray-500">Gangguan</label>
                <input type="text"
                    value="{{ $laporan->jenis_gangguan }}"
                    disabled
                    class="w-full mt-2 border rounded-lg p-3 bg-gray-100">
            </div>

            <div>
                <label class="text-sm text-gray-500">Prioritas</label>
                <input type="text"
                    value="{{ ucfirst($laporan->prioritas) }}"
                    disabled
                    class="w-full mt-2 border rounded-lg p-3 bg-gray-100">
            </div>

            <div>
                <label class="text-sm text-gray-500">Status Tiket</label>
                <input type="text"
                    value="Menunggu Teknisi"
                    disabled
                    class="w-full mt-2 border rounded-lg p-3 bg-gray-100">
            </div>

        </div>

        <div class="mt-5">
            <label class="text-sm text-gray-500">
                Deskripsi Gangguan
            </label>

            <div class="mt-2 bg-gray-50 border rounded-lg p-4">
                {{ $laporan->deskripsi }}
            </div>
        </div>

    </div>

    <div class="flex justify-end gap-3">

        <a href="/gangguan-verifikasi/{{ $laporan->id }}"
            class="px-5 py-3 border rounded-lg hover:bg-gray-50">
            Batal
        </a>

        <form action="/tambah-tiket/{{ $laporan->id }}" method="POST">
            @csrf

            <button type="submit"
                class="px-5 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg">
                Simpan Tiket
            </button>
        </form>

    </div>

</div>

@endsection