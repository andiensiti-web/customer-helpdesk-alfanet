@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">

    <div>
        <a href="/tagihan-admin"
            class="text-blue-600 hover:text-blue-800">
            ← Kembali
        </a>

        <h1 class="text-3xl font-bold text-gray-800 mt-3">
            Tambah Tagihan
        </h1>

        <p class="text-gray-500 mt-1">
            Buat tagihan baru untuk pelanggan Alfa Net
        </p>
    </div>

    @if($errors->any())
        <div class="bg-red-100 text-red-700 px-4 py-3 rounded-lg">
            {{ $errors->first() }}
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm p-6">

        <form action="/tambah-tagihan" method="POST">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                {{-- PELANGGAN --}}
                <div>
                    <label class="text-sm text-gray-600">
                        Pelanggan
                    </label>

                    <select
                        name="pelanggan_id"
                        id="pelanggan_id"
                        required
                        class="w-full mt-2 border rounded-lg px-4 py-3"
                    >

                        <option value="">
                            Pilih Pelanggan
                        </option>

                        @foreach($pelanggans as $pelanggan)

                            <option
                                value="{{ $pelanggan->id }}"

                                data-id="{{ $pelanggan->id_pelanggan }}"

                                data-paket="{{ $pelanggan->paket->nama_paket ?? '' }}"

                                data-kecepatan="{{ $pelanggan->paket->kecepatan ?? '' }}"

                                data-harga="{{ $pelanggan->paket->harga_bulanan ?? 0 }}"

                                {{ (string) $pelangganTerpilih === (string) $pelanggan->id ? 'selected' : '' }}
                            >

                                {{ $pelanggan->user->name ?? '-' }}

                            </option>

                        @endforeach

                    </select>
                </div>


                {{-- ID PELANGGAN --}}
                <div>
                    <label class="text-sm text-gray-600">
                        ID Pelanggan
                    </label>

                    <input
                        type="text"
                        id="id_pelanggan"
                        readonly
                        class="w-full mt-2 border rounded-lg px-4 py-3 bg-gray-100"
                        placeholder="Otomatis"
                    >
                </div>


                {{-- PAKET --}}
                <div>
                    <label class="text-sm text-gray-600">
                        Paket
                    </label>

                    <input
                        type="text"
                        id="nama_paket"
                        readonly
                        class="w-full mt-2 border rounded-lg px-4 py-3 bg-gray-100"
                        placeholder="Otomatis"
                    >
                </div>


                {{-- KECEPATAN --}}
                <div>
                    <label class="text-sm text-gray-600">
                        Kecepatan
                    </label>

                    <input
                        type="text"
                        id="kecepatan"
                        readonly
                        class="w-full mt-2 border rounded-lg px-4 py-3 bg-gray-100"
                        placeholder="Otomatis"
                    >
                </div>


                {{-- JUMLAH --}}
                <div>
                    <label class="text-sm text-gray-600">
                        Jumlah Tagihan
                    </label>

                    <input
                        type="number"
                        name="jumlah"
                        id="jumlah"
                        required
                        min="0"
                        readonly
                        class="w-full mt-2 border rounded-lg px-4 py-3 bg-gray-100"
                        placeholder="Otomatis"
                    >
                </div>


                {{-- TANGGAL TERBIT --}}
                <div>
                    <label class="text-sm text-gray-600">
                        Tanggal Terbit
                    </label>

                    <input
                        type="date"
                        name="tanggal_terbit"
                        value="{{ date('Y-m-d') }}"
                        required
                        class="w-full mt-2 border rounded-lg px-4 py-3"
                    >
                </div>


                {{-- TANGGAL JATUH TEMPO --}}
                <div>
                    <label class="text-sm text-gray-600">
                        Tanggal Jatuh Tempo
                    </label>

                    <input
                        type="date"
                        name="tanggal_jatuh_tempo"
                        required
                        class="w-full mt-2 border rounded-lg px-4 py-3"
                    >
                </div>


                {{-- STATUS --}}
                <div>
                <label class="text-sm text-gray-600">
                    Status
                </label>

                <input
                    type="text"
                    value="Belum Bayar"
                    readonly
                    class="w-full mt-2 border rounded-lg px-4 py-3 bg-gray-100 text-gray-600"
                >

                <input
                    type="hidden"
                    name="status"
                    value="belum_bayar"
                >
            </div>

            </div>


            {{-- CATATAN --}}
            <div class="mt-5">

                <label class="text-sm text-gray-600">
                    Catatan
                </label>

                <textarea
                    name="catatan"
                    rows="4"
                    class="w-full mt-2 border rounded-lg px-4 py-3"
                    placeholder="Tambahkan catatan tagihan..."
                ></textarea>

            </div>


            {{-- TOMBOL --}}
            <div class="flex justify-end gap-3 mt-6">

                <a
                    href="/tagihan-admin"
                    class="px-5 py-3 border rounded-lg hover:bg-gray-50"
                >
                    Batal
                </a>

                <button
                    type="submit"
                    class="px-5 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg"
                >
                    Simpan Tagihan
                </button>

            </div>

        </form>

    </div>
</div>


<script>

const pelanggan =
    document.getElementById('pelanggan_id');


function isiDataPelanggan() {

    const option =
        pelanggan.options[pelanggan.selectedIndex];


    if (!option || !option.value) {

        document.getElementById('id_pelanggan').value = '';
        document.getElementById('nama_paket').value = '';
        document.getElementById('kecepatan').value = '';
        document.getElementById('jumlah').value = '';

        return;
    }


    document.getElementById('id_pelanggan').value =
        option.dataset.id || '';


    document.getElementById('nama_paket').value =
        option.dataset.paket || '';


    document.getElementById('kecepatan').value =
        option.dataset.kecepatan
            ? option.dataset.kecepatan + ' Mbps'
            : '';


    document.getElementById('jumlah').value =
        option.dataset.harga || '';
}


pelanggan.addEventListener(
    'change',
    isiDataPelanggan
);

document.addEventListener(
    'DOMContentLoaded',
    function () {
        isiDataPelanggan();
    }
);

</script>

@endsection