@extends('layouts.app')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">

    <div>
        <a href="/gangguan-admin" class="text-blue-600">← Kembali</a>
        <h1 class="text-3xl font-bold text-gray-800 mt-3">Verifikasi Laporan</h1>
        <p class="text-gray-500 mt-1">Periksa laporan sebelum dibuat menjadi tiket gangguan.</p>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-6">

        <div class="space-y-5">

            <div>
                <p class="text-sm text-gray-500">ID Laporan</p>
                <p class="font-semibold">{{ $laporan->id_laporan }}</p>
            </div>

            <div>
                <p class="text-sm text-gray-500">Pelanggan</p>
                <p class="font-semibold">
                    {{ $laporan->pelanggan->user->name ?? '-' }}
                </p>
            </div>

            <div>
                <p class="text-sm text-gray-500">Jenis Gangguan</p>
                <p class="font-semibold">{{ $laporan->jenis_gangguan }}</p>
            </div>

            <div>
                <p class="text-sm text-gray-500">Prioritas</p>
                <p class="font-semibold">{{ ucfirst($laporan->prioritas) }}</p>
            </div>

            <div>
                <p class="text-sm text-gray-500">Deskripsi</p>
                <div class="bg-gray-50 rounded-lg p-4 mt-2">
                    {{ $laporan->deskripsi }}
                </div>
            </div>

        </div>

        <div class="flex justify-end gap-3 mt-6">

            <form action="/gangguan-tolak/{{ $laporan->id }}" method="POST" class="flex items-center gap-2">
                @csrf
                @method('PUT')

                <input
                    type="text"
                    name="catatan"
                    placeholder="Alasan penolakan"
                    required
                    class="border border-gray-300 rounded-lg px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-red-200"
                >

                <button
                    type="submit"
                    class="border border-red-500 text-red-600 px-5 py-3 rounded-lg hover:bg-red-50"
                >
                    Tolak
                </button>
            </form>

            <form action="/gangguan-setujui/{{ $laporan->id }}" method="POST">
                @csrf
                @method('PUT')

                <button class="bg-green-600 hover:bg-green-700 text-white px-5 py-3 rounded-lg">
                    Setujui & Buat Tiket
                </button>
            </form>

        </div>

    </div>

</div>
@endsection