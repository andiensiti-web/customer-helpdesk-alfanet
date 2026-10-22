@extends('layouts.app')

@section('content')

<div class="max-w-6xl mx-auto space-y-6">

    {{-- HEADER --}}
    <div>
        <a href="{{ url('/laporan-teknisi') }}"
           class="inline-flex items-center gap-2 text-blue-600 hover:text-blue-800 mb-4">
            ← Kembali ke Laporan
        </a>

        <h1 class="text-3xl font-bold text-gray-900">
            Perbaiki Laporan Teknisi
        </h1>

        <p class="text-gray-500 mt-1">
            Lengkapi hasil pekerjaan dan kirim laporan kepada Admin
        </p>
    </div>

    {{-- PESAN DITOLAK --}}
    @if($laporan && $laporan->status === 'ditolak')
        <div class="bg-red-50 border border-red-200 rounded-2xl p-6">

            <div class="flex items-start gap-4">

                <div class="w-10 h-10 rounded-xl bg-red-100 text-red-600 flex items-center justify-center flex-shrink-0">
                    !
                </div>

                <div class="flex-1">

                    <h2 class="text-lg font-bold text-red-700">
                        Laporan Perlu Diperbaiki
                    </h2>

                    <p class="text-red-600 mt-1">
                        Admin menolak laporan ini. Silakan perbaiki bagian yang diminta sebelum mengirim ulang.
                    </p>

                    @if($laporan->catatan)
                        <div class="mt-4 bg-white border border-red-100 rounded-xl p-4">
                            <p class="text-sm font-semibold text-gray-600 mb-2">
                                Catatan Admin
                            </p>

                            <p class="text-gray-700">
                                {{ $laporan->catatan }}
                            </p>
                        </div>
                    @endif

                </div>

            </div>

        </div>
    @endif

    {{-- INFORMASI PEKERJAAN --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">

        <h2 class="text-xl font-bold text-gray-900 mb-6">
            Informasi Pekerjaan
        </h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-10 gap-y-6">

            <div>
                <p class="text-sm text-gray-500">ID Tiket</p>
                <p class="font-semibold text-gray-900 mt-1">
                    {{ $penugasan->tiketGangguan->id_tiket ?? '-' }}
                </p>
            </div>

            <div>
                <p class="text-sm text-gray-500">ID Pelanggan</p>
                <p class="font-semibold text-gray-900 mt-1">
                    {{ $penugasan->tiketGangguan->pelanggan->id_pelanggan ?? '-' }}
                </p>
            </div>

            <div>
                <p class="text-sm text-gray-500">Nama Pelanggan</p>
                <p class="font-semibold text-gray-900 mt-1">
                    {{ $penugasan->tiketGangguan->pelanggan->user->name ?? '-' }}
                </p>
            </div>

            <div>
                <p class="text-sm text-gray-500">Jenis Gangguan</p>
                <p class="font-semibold text-gray-900 mt-1">
                    {{ $penugasan->tiketGangguan->laporanGangguan->jenis_gangguan ?? '-' }}
                </p>
            </div>

            <div class="md:col-span-2">
                <p class="text-sm text-gray-500">Keluhan Pelanggan</p>

                <div class="mt-2 bg-gray-50 rounded-xl p-4 text-gray-700">
                    {{ $penugasan->tiketGangguan->laporanGangguan->deskripsi ?? '-' }}
                </div>
            </div>

            <div>
                <p class="text-sm text-gray-500">Tanggal Perbaikan</p>
                <p class="font-semibold text-gray-900 mt-1">
                    {{ $penugasan->perbaikan?->tanggal_perbaikan?->translatedFormat('d F Y') ?? '-' }}
                </p>
            </div>

            <div>
                <p class="text-sm text-gray-500">Status Pekerjaan</p>

                <span class="inline-flex mt-1 px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-700">
                    {{ ucfirst($penugasan->status) }}
                </span>
            </div>

        </div>

    </div>

    {{-- FORM --}}
    <form
        action="{{ url('/buat-laporan-teknisi/'.$penugasan->id) }}"
        method="POST"
        enctype="multipart/form-data"
        class="space-y-6"
    >

        @csrf

        {{-- ISI LAPORAN --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">

            <h2 class="text-xl font-bold text-gray-900 mb-6">
                Perbaiki Isi Laporan
            </h2>

            <div class="space-y-6">

                {{-- HASIL PERBAIKAN --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Hasil Perbaikan
                    </label>

                    <textarea
                        name="hasil_perbaikan"
                        rows="5"
                        required
                        class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        placeholder="Jelaskan hasil perbaikan yang telah dilakukan..."
                    >{{ old('hasil_perbaikan', $laporan?->hasil_perbaikan) }}</textarea>

                    @error('hasil_perbaikan')
                        <p class="text-sm text-red-600 mt-1">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- TINDAKAN --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Tindakan Perbaikan
                    </label>

                    <textarea
                        name="tindakan"
                        rows="5"
                        required
                        class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        placeholder="Tuliskan tindakan yang dilakukan..."
                    >{{ old('tindakan', $laporan?->tindakan) }}</textarea>

                    @error('tindakan')
                        <p class="text-sm text-red-600 mt-1">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- FOTO BUKTI --}}
                <div>

                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Bukti Foto Perbaikan
                    </label>

                    {{-- FOTO LAMA --}}
                    @if($penugasan->perbaikan?->bukti_foto)

                        <div class="mb-4">

                            <p class="text-sm text-gray-500 mb-2">
                                Foto sebelumnya
                            </p>

                            <img
                                src="{{ asset('storage/'.$penugasan->perbaikan->bukti_foto) }}"
                                alt="Bukti Perbaikan"
                                class="w-64 h-48 object-cover rounded-xl border border-gray-200"
                            >

                        </div>

                    @endif

                    <div class="border-2 border-dashed border-gray-300 rounded-xl p-5">

                        <p class="text-sm text-gray-500 mb-3">
                            Upload foto baru jika ingin mengganti foto sebelumnya.
                        </p>

                        <input
                            type="file"
                            name="bukti_foto"
                            accept=".jpg,.jpeg,.png"
                            class="w-full text-sm text-gray-600"
                        >

                        <p class="text-xs text-gray-400 mt-2">
                            Format JPG, JPEG, atau PNG. Maksimal 5 MB.
                        </p>

                    </div>

                    @error('bukti_foto')
                        <p class="text-sm text-red-600 mt-1">
                            {{ $message }}
                        </p>
                    @enderror

                </div>

                {{-- CATATAN --}}
                <div>

                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Catatan
                    </label>

                    <textarea
                        name="catatan"
                        rows="4"
                        class="w-full rounded-xl border border-gray-300 px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        placeholder="Tambahkan catatan jika diperlukan..."
                    >{{ old('catatan', $laporan?->catatan) }}</textarea>

                    @error('catatan')
                        <p class="text-sm text-red-600 mt-1">
                            {{ $message }}
                        </p>
                    @enderror

                </div>

            </div>

        </div>

        {{-- TOMBOL --}}
        <div class="flex justify-end gap-3">

            <a
                href="{{ url('/laporan-teknisi') }}"
                class="px-5 py-3 rounded-xl border border-gray-300 text-gray-700 hover:bg-gray-50"
            >
                Batal
            </a>

            <button
                type="submit"
                class="px-6 py-3 rounded-xl bg-blue-600 text-white font-semibold hover:bg-blue-700"
            >
                Kirim Ulang Laporan
            </button>

        </div>

    </form>

</div>

@endsection