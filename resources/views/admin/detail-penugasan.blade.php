@extends('layouts.app')

@section('content')

<div class="max-w-5xl mx-auto space-y-6">

    <div>
        <a href="/penugasan-teknisi" class="text-blue-600 hover:text-blue-800">
            ← Kembali
        </a>

        <h1 class="text-3xl font-bold text-gray-800 mt-3">
            Penugasan Teknisi
        </h1>

        <p class="text-gray-500 mt-1">
            Tentukan teknisi untuk menangani tiket gangguan pelanggan
        </p>
    </div>

    @if($errors->any())
        <div class="bg-red-100 text-red-700 px-4 py-3 rounded-lg">
            {{ $errors->first() }}
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm p-6">

        <div class="flex items-center gap-2 mb-5">
            <i data-lucide="ticket" class="w-6 h-6 text-blue-600"></i>
            <h2 class="text-xl font-semibold">Informasi Tiket</h2>
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
            <i data-lucide="user-cog" class="w-6 h-6 text-blue-600"></i>
            <h2 class="text-xl font-semibold">Form Penugasan</h2>
        </div>

        <form action="/detail-penugasan/{{ $tiket->id }}" method="POST">

            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                <div>
                    <label class="text-sm text-gray-600">
                        Pilih Teknisi
                    </label>

                    <select name="teknisi_id"
                        required
                        class="w-full mt-2 border rounded-lg px-4 py-3">

                        <option value="">
                            Pilih Teknisi
                        </option>

                        @foreach($teknisis as $teknisi)
                            <option value="{{ $teknisi->id }}">
                                {{ $teknisi->nama }}
                            </option>
                        @endforeach

                    </select>
                </div>

                <div>
                    <label class="text-sm text-gray-600">
                        Tanggal Penugasan
                    </label>

                    <input type="date"
                        name="tanggal_penugasan"
                        required
                        class="w-full mt-2 border rounded-lg px-4 py-3">
                </div>

                <div>
                    <label class="text-sm text-gray-600">
                        Status
                    </label>

                    <select name="status"
                        class="w-full mt-2 border rounded-lg px-4 py-3">

                        <option value="ditugaskan">
                            Ditugaskan
                        </option>

                        <option value="dalam_perjalanan">
                            Dalam Perjalanan
                        </option>

                        <option value="selesai">
                            Selesai
                        </option>

                    </select>
                </div>

            </div>

            <div class="mt-5">

                <label class="text-sm text-gray-600">
                    Catatan
                </label>

                <textarea name="catatan"
                    rows="4"
                    placeholder="Tambahkan catatan penugasan..."
                    class="w-full mt-2 border rounded-lg px-4 py-3"></textarea>

            </div>

            <div class="flex justify-end gap-3 mt-6">

                <a href="/penugasan-teknisi"
                    class="px-5 py-3 border rounded-lg hover:bg-gray-50">
                    Batal
                </a>

                <button type="submit"
                    class="px-5 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg">
                    Tugaskan Teknisi
                </button>

            </div>

        </form>

    </div>

</div>

@endsection