@extends('layouts.app')

@section('content')

<div class="max-w-4xl mx-auto space-y-6">

    <div>
        <a href="/pembayaran-admin" class="text-blue-600 hover:text-blue-800">
            ← Kembali
        </a>

        <h1 class="text-3xl font-bold text-gray-800 mt-3">
            Catat Pembayaran
        </h1>

        <p class="text-gray-500 mt-1">
            Catat pembayaran yang dilakukan oleh pelanggan
        </p>
    </div>

    @if($errors->any())
        <div class="bg-red-100 text-red-700 px-4 py-3 rounded-lg">
            {{ $errors->first() }}
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm p-6">

        <div class="flex items-center gap-2 mb-6">
            <i data-lucide="credit-card" class="w-6 h-6 text-blue-600"></i>

            <h2 class="text-xl font-semibold">
                Informasi Pembayaran
            </h2>
        </div>

        <form action="/catat-pembayaran" method="POST">

            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                <div>
                    <label class="text-sm text-gray-600">
                        Pelanggan
                    </label>

                    <select name="pelanggan_id"
                        id="pelanggan"
                        required
                        class="w-full mt-2 border rounded-lg px-4 py-3">

                        <option value="">
                            Pilih Pelanggan
                        </option>

                        @foreach($pelanggans as $pelanggan)

                            <option
                                value="{{ $pelanggan->id }}"
                                {{ old('pelanggan_id') == $pelanggan->id ? 'selected' : '' }}>

                                {{ $pelanggan->user->name ?? '-' }}
                                - {{ $pelanggan->id_pelanggan }}

                            </option>

                        @endforeach

                    </select>
                </div>

                <div>
                    <label class="text-sm text-gray-600">
                        Tagihan
                    </label>

                    <select name="tagihan_id"
                        id="tagihan"
                        required
                        class="w-full mt-2 border rounded-lg px-4 py-3">

                        <option value="">
                            Pilih Tagihan
                        </option>

                        @foreach($tagihans as $tagihan)

                            <option
                                value="{{ $tagihan->id }}"
                                data-pelanggan="{{ $tagihan->pelanggan_id }}"
                                data-jumlah="{{ $tagihan->jumlah }}"
                                {{ old('tagihan_id') == $tagihan->id ? 'selected' : '' }}>

                                {{ $tagihan->kode_tagihan }}
                                - Rp{{ number_format($tagihan->jumlah, 0, ',', '.') }}

                            </option>

                        @endforeach

                    </select>
                </div>

                <div>
                    <label class="text-sm text-gray-600">
                        Nominal Pembayaran
                    </label>

                    <input type="number"
                        name="jumlah"
                        id="jumlah"
                        value="{{ old('jumlah') }}"
                        min="1"
                        required
                        class="w-full mt-2 border rounded-lg px-4 py-3"
                        placeholder="Masukkan nominal">
                </div>

                <div>
                    <label class="text-sm text-gray-600">
                        Metode Pembayaran
                    </label>

                    <select name="metode"
                        required
                        class="w-full mt-2 border rounded-lg px-4 py-3">

                        <option value="">
                            Pilih Metode
                        </option>

                        <option value="tunai">
                            Tunai
                        </option>

                        <option value="transfer">
                            Transfer Bank
                        </option>

                        <option value="e_wallet">
                            E-Wallet
                        </option>

                    </select>
                </div>

                <div>
                    <label class="text-sm text-gray-600">
                        Tanggal Pembayaran
                    </label>

                    <input type="date"
                        name="tanggal_pembayaran"
                        value="{{ old('tanggal_pembayaran', date('Y-m-d')) }}"
                        required
                        class="w-full mt-2 border rounded-lg px-4 py-3">
                </div>

            </div>

            <div class="mt-5">

                <label class="text-sm text-gray-600">
                    Catatan
                </label>

                <textarea name="catatan_pelanggan"
                    rows="4"
                    placeholder="Tambahkan catatan pembayaran..."
                    class="w-full mt-2 border rounded-lg px-4 py-3">{{ old('catatan_pelanggan') }}</textarea>

            </div>

            <div class="flex justify-end gap-3 mt-6">

                <a href="/pembayaran-admin"
                    class="px-5 py-3 border rounded-lg hover:bg-gray-50">
                    Batal
                </a>

                <button type="submit"
                    class="px-5 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg">
                    Simpan Pembayaran
                </button>

            </div>

        </form>

    </div>

</div>

<script>

const pelanggan = document.getElementById('pelanggan');
const tagihan = document.getElementById('tagihan');
const jumlah = document.getElementById('jumlah');

pelanggan.addEventListener('change', function () {

    const pelangganId = this.value;

    tagihan.value = '';
    jumlah.value = '';

    Array.from(tagihan.options).forEach(option => {

        if (!option.value) {
            option.hidden = false;
            return;
        }

        option.hidden =
            option.dataset.pelanggan !== pelangganId;

    });

});

tagihan.addEventListener('change', function () {

    const option = this.options[this.selectedIndex];

    if (option.dataset.jumlah) {
        jumlah.value = option.dataset.jumlah;
    }

});

</script>

@endsection