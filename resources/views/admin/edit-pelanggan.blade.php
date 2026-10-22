@extends('layouts.app')

@section('content')

<div class="space-y-6">

    {{-- HEADER --}}
    <div>
        <a
            href="/pelanggan-admin"
            class="text-blue-600 hover:text-blue-800 flex items-center gap-2 mb-3"
        >
            <i data-lucide="arrow-left" class="w-5 h-5"></i>
            Kembali
        </a>

        <h1 class="text-3xl font-bold text-gray-800">
            Edit Pelanggan
        </h1>

        <p class="text-gray-500 mt-1">
            Perbarui data pelanggan Alfa Net
        </p>
    </div>


    {{-- ERROR VALIDASI --}}
    @if ($errors->any())

        <div class="bg-red-50 border border-red-200 text-red-600 p-4 rounded-lg">

            <p class="font-medium mb-2">
                Data belum dapat disimpan:
            </p>

            <ul class="list-disc ml-5 space-y-1">

                @foreach ($errors->all() as $error)

                    <li>
                        {{ $error }}
                    </li>

                @endforeach

            </ul>

        </div>

    @endif


    {{-- FORM --}}
    <form
        action="/edit-pelanggan/{{ $pelanggan->id }}"
        method="POST"
    >

        @csrf
        @method('PUT')


        {{-- INFORMASI PELANGGAN --}}
        <div class="bg-white rounded-xl shadow-sm p-6">

            <h2 class="text-xl font-semibold text-gray-800 mb-6">
                Informasi Pelanggan
            </h2>


            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">


                {{-- NAMA --}}
                <div>

                    <label class="text-sm font-medium text-gray-700">
                        Nama Pelanggan *
                    </label>

                    <input
                        type="text"
                        name="nama"
                        value="{{ old('nama', $pelanggan->user->name) }}"
                        placeholder="Masukkan nama pelanggan"
                        class="w-full mt-2 border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        required
                    >

                </div>


                {{-- ID PELANGGAN --}}
                <div>

                    <label class="text-sm font-medium text-gray-700">
                        ID Pelanggan
                    </label>

                    <input
                        type="text"
                        value="{{ $pelanggan->id_pelanggan }}"
                        disabled
                        class="w-full mt-2 bg-gray-100 border border-gray-300 rounded-lg p-3 text-gray-600"
                    >

                    <p class="text-xs text-gray-400 mt-1">
                        ID pelanggan tidak dapat diubah.
                    </p>

                </div>


                {{-- NOMOR TELEPON --}}
                <div>

                    <label class="text-sm font-medium text-gray-700">
                        Nomor Telepon *
                    </label>

                    <input
                        type="text"
                        name="telepon"
                        value="{{ old('telepon', $pelanggan->user->telepon) }}"
                        placeholder="Masukkan nomor telepon"
                        class="w-full mt-2 border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        required
                    >

                </div>


                {{-- JENIS PELANGGAN --}}
                <div>

                    <label class="text-sm font-medium text-gray-700">
                        Jenis Pelanggan
                    </label>

                    <select
                        name="tipe_pelanggan"
                        class="w-full mt-2 border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    >

                        <option
                            value="rumah"
                            {{ old('tipe_pelanggan', $pelanggan->tipe_pelanggan) === 'rumah' ? 'selected' : '' }}
                        >
                            Rumah
                        </option>

                        <option
                            value="bisnis"
                            {{ old('tipe_pelanggan', $pelanggan->tipe_pelanggan) === 'bisnis' ? 'selected' : '' }}
                        >
                            Bisnis
                        </option>

                    </select>

                </div>


                {{-- EMAIL --}}
                <div>

                    <label class="text-sm font-medium text-gray-700">
                        Email *
                    </label>

                    <input
                        type="email"
                        name="email"
                        value="{{ old('email', $pelanggan->user->email) }}"
                        placeholder="Email pelanggan"
                        class="w-full mt-2 border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        required
                    >

                </div>


                {{-- PAKET INTERNET --}}
                <div>

                    <label class="text-sm font-medium text-gray-700">
                        Paket Internet *
                    </label>

                    <select
                        name="paket_id"
                        required
                        class="w-full mt-2 border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    >

                        @foreach($pakets as $paket)

                            <option
                                value="{{ $paket->id }}"
                                {{ old('paket_id', $pelanggan->paket_id) == $paket->id ? 'selected' : '' }}
                            >

                                {{ $paket->nama_paket }}
                                -
                                {{ $paket->kecepatan }} Mbps
                                -
                                Rp{{ number_format($paket->harga_bulanan, 0, ',', '.') }}

                            </option>

                        @endforeach

                    </select>

                </div>


                {{-- ALAMAT --}}
                <div class="md:col-span-2">

                    <label class="text-sm font-medium text-gray-700">
                        Alamat Lengkap *
                    </label>

                    <textarea
                        name="alamat"
                        rows="3"
                        placeholder="Masukkan alamat pelanggan"
                        class="w-full mt-2 border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        required
                    >{{ old('alamat', $pelanggan->user->alamat) }}</textarea>

                </div>


                {{-- STATUS --}}
                <div>

                    <label class="text-sm font-medium text-gray-700">
                        Status Pelanggan *
                    </label>

                    <select
                        name="status"
                        required
                        class="w-full mt-2 border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    >

                        <option
                            value="aktif"
                            {{ old('status', $pelanggan->status) === 'aktif' ? 'selected' : '' }}
                        >
                            Aktif
                        </option>

                        <option
                            value="isolir"
                            {{ old('status', $pelanggan->status) === 'isolir' ? 'selected' : '' }}
                        >
                            Isolir
                        </option>

                        <option
                            value="nonaktif"
                            {{ old('status', $pelanggan->status) === 'nonaktif' ? 'selected' : '' }}
                        >
                            Nonaktif
                        </option>

                    </select>

                    <p class="text-xs text-gray-500 mt-1">
                        Isolir digunakan ketika layanan pelanggan dihentikan sementara.
                    </p>

                </div>


                {{-- TANGGAL MULAI --}}
                <div>

                    <label class="text-sm font-medium text-gray-700">
                        Tanggal Mulai Berlangganan *
                    </label>

                    <input
                        type="date"
                        name="tanggal_mulai"
                        value="{{ old('tanggal_mulai', $pelanggan->tanggal_mulai ? $pelanggan->tanggal_mulai->format('Y-m-d') : '') }}"
                        required
                        class="w-full border rounded-lg px-4 py-3"
                    >

                </div>


                {{-- TANGGAL JATUH TEMPO --}}
                <div>

                    <label class="text-sm font-medium text-gray-700">
                        Tanggal Jatuh Tempo *
                    </label>

                    <select
                        name="tanggal_jatuh_tempo"
                        required
                        class="w-full mt-2 border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    >

                        <option value="">
                            Pilih Tanggal
                        </option>

                        @for($i = 1; $i <= 28; $i++)

                            <option
                                value="{{ $i }}"
                                {{ old(
                                    'tanggal_jatuh_tempo',
                                    $pelanggan->tanggal_jatuh_tempo
                                ) == $i ? 'selected' : '' }}
                            >
                                Tanggal {{ $i }}
                            </option>

                        @endfor

                    </select>

                    <p class="text-xs text-gray-500 mt-1">
                        Tanggal pembayaran pelanggan setiap bulan.
                    </p>

                </div>


                {{-- CATATAN --}}
                <div class="md:col-span-2">

                    <label class="text-sm font-medium text-gray-700">
                        Catatan
                    </label>

                    <textarea
                        name="catatan"
                        rows="3"
                        placeholder="Tambahkan catatan pelanggan jika diperlukan"
                        class="w-full mt-2 border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    >{{ old('catatan', $pelanggan->catatan) }}</textarea>

                </div>

            </div>

        </div>


        {{-- TOMBOL --}}
        <div class="flex justify-end gap-3 mt-6">

            <a
                href="/pelanggan-admin"
                class="px-6 py-3 border border-gray-300 rounded-lg hover:bg-gray-50 transition"
            >
                Batal
            </a>

            <button
                type="submit"
                class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition flex items-center gap-2"
            >

                <i data-lucide="save" class="w-5 h-5"></i>

                Simpan Perubahan

            </button>

        </div>

    </form>

</div>


<script>
    document.addEventListener('DOMContentLoaded', function () {
        lucide.createIcons();
    });
</script>

@endsection