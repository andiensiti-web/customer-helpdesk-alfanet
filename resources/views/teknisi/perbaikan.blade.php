@extends('layouts.app')

@section('content')

@php
    $tiket = $tugas->tiketGangguan;
    $pelanggan = $tiket?->pelanggan;
    $userPelanggan = $pelanggan?->user;
    $paket = $pelanggan?->paket;
    $laporan = $tiket?->laporanGangguan;

    $status = $perbaikan?->status ?? 'menunggu';

    $statusLabel = [
        'menunggu' => 'Menunggu',
        'perjalanan' => 'Dalam Perjalanan',
        'ditangani' => 'Sedang Ditangani',
        'selesai' => 'Selesai',
    ];

    $statusClass = [
        'menunggu' => 'bg-yellow-100 text-yellow-700',
        'perjalanan' => 'bg-blue-100 text-blue-700',
        'ditangani' => 'bg-orange-100 text-orange-700',
        'selesai' => 'bg-green-100 text-green-700',
    ];

    $statusIndex = [
        'menunggu' => 0,
        'perjalanan' => 1,
        'ditangani' => 2,
        'selesai' => 3,
    ];

    $currentIndex = $statusIndex[$status] ?? 0;

    $idTiket = $tiket?->id_tiket ?? '-';

    $namaPelanggan = $userPelanggan?->name ?? '-';

    /*
    |--------------------------------------------------------------------------
    | DATA PELANGGAN
    |--------------------------------------------------------------------------
    | Data telepon dan alamat diambil dari tabel users.
    */
    $telepon = $userPelanggan?->telepon ?? '-';
    $alamat = $userPelanggan?->alamat ?? '-';

    $namaPaket = $paket?->nama_paket ?? '-';

    $jenisGangguan = $laporan?->jenis_gangguan ?? '-';

    $deskripsi = $laporan?->deskripsi
        ?? 'Belum ada deskripsi gangguan.';

    /*
    |--------------------------------------------------------------------------
    | TANGGAL LAPOR
    |--------------------------------------------------------------------------
    */
    $tanggalLapor = $laporan?->tanggal_laporan
        ? \Carbon\Carbon::parse($laporan->tanggal_laporan)->translatedFormat('d F Y')
        : '-';

    /*
    |--------------------------------------------------------------------------
    | JADWAL KUNJUNGAN
    |--------------------------------------------------------------------------
    | Prioritas:
    | 1. Jadwal Kunjungan
    | 2. Tanggal Penugasan
    */
    $tanggalKunjungan = '-';

    if ($jadwalKunjungan?->tanggal_kunjungan) {
        $tanggalKunjungan = \Carbon\Carbon::parse(
            $jadwalKunjungan->tanggal_kunjungan
        )->translatedFormat('d F Y');
    } elseif ($tugas?->tanggal_penugasan) {
        $tanggalKunjungan = \Carbon\Carbon::parse(
            $tugas->tanggal_penugasan
        )->translatedFormat('d F Y');
    }

    $jamKunjungan = '-';

    if ($jadwalKunjungan?->jam_kunjungan) {
        $jamKunjungan = \Carbon\Carbon::parse(
            $jadwalKunjungan->jam_kunjungan
        )->format('H:i') . ' WIB';
    }

    /*
    |--------------------------------------------------------------------------
    | STEPS STATUS
    |--------------------------------------------------------------------------
    */
    $steps = [
        [
            'key' => 'menunggu',
            'label' => 'Menunggu'
        ],
        [
            'key' => 'perjalanan',
            'label' => 'Dalam Perjalanan'
        ],
        [
            'key' => 'ditangani',
            'label' => 'Sedang Ditangani'
        ],
        [
            'key' => 'selesai',
            'label' => 'Selesai'
        ],
    ];
@endphp


<div class="space-y-6">

    {{-- HEADER --}}
    <div>
        <h1 class="text-2xl font-bold text-gray-800">
            Perbaikan
        </h1>

        <p class="text-gray-500 mt-1">
            Kelola proses dan hasil perbaikan gangguan pelanggan.
        </p>

        <a
            href="/detail-tugas/{{ $tugas->id }}"
            class="inline-flex items-center gap-2 mt-3 text-blue-600 hover:text-blue-800 text-sm font-medium"
        >
            <i data-lucide="arrow-left" class="w-4 h-4"></i>
            Kembali ke Detail Tugas
        </a>
    </div>


    {{-- PESAN BERHASIL --}}
    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 rounded-lg px-4 py-3 text-sm">
            {{ session('success') }}
        </div>
    @endif


    {{-- ERROR --}}
    @if($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 rounded-lg px-4 py-3 text-sm">
            <ul class="list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif


    {{-- INFORMASI TIKET --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5">

        <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-4">

            <div>
                <p class="text-sm text-gray-500">
                    Tiket yang sedang dikerjakan
                </p>

                <p class="text-lg font-semibold text-gray-800 mt-1">
                    {{ $idTiket }} - {{ $namaPelanggan }}
                </p>
            </div>

            <div class="w-full md:w-80">

                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Status Saat Ini
                </label>

                <span
                    class="inline-flex px-3 py-2 rounded-lg text-sm font-medium
                    {{ $statusClass[$status] ?? 'bg-gray-100 text-gray-700' }}"
                >
                    {{ $statusLabel[$status] ?? 'Menunggu' }}
                </span>

            </div>

        </div>

    </div>


    {{-- FORM UTAMA --}}
    <form
        action="/perbaikan-teknisi/{{ $tugas->id }}"
        method="POST"
        enctype="multipart/form-data"
    >

        @csrf


        <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">

            {{-- ========================================================= --}}
            {{-- KOLOM KIRI --}}
            {{-- ========================================================= --}}

            <div class="space-y-6">


                {{-- INFORMASI GANGGUAN --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">

                    <h2 class="text-lg font-bold text-gray-800 mb-5">
                        Informasi Gangguan
                    </h2>


                    <div>

                        {{-- ID TIKET --}}
                        <div class="grid grid-cols-2 py-3 border-b border-gray-200">

                            <span class="text-sm text-gray-600">
                                ID Tiket
                            </span>

                            <span class="text-sm font-medium text-gray-800">
                                {{ $idTiket }}
                            </span>

                        </div>


                        {{-- NAMA PELANGGAN --}}
                        <div class="grid grid-cols-2 py-3 border-b border-gray-200">

                            <span class="text-sm text-gray-600">
                                Nama Pelanggan
                            </span>

                            <span class="text-sm font-medium text-gray-800">
                                {{ $namaPelanggan }}
                            </span>

                        </div>


                        {{-- NO TELEPON --}}
                        <div class="grid grid-cols-2 py-3 border-b border-gray-200">

                            <span class="text-sm text-gray-600">
                                No. Telepon
                            </span>

                            <span class="text-sm font-medium text-gray-800">
                                {{ $telepon }}
                            </span>

                        </div>


                        {{-- ALAMAT --}}
                        <div class="grid grid-cols-2 py-3 border-b border-gray-200">

                            <span class="text-sm text-gray-600">
                                Alamat
                            </span>

                            <span class="text-sm font-medium text-gray-800">
                                {{ $alamat }}
                            </span>

                        </div>


                        {{-- PAKET --}}
                        <div class="grid grid-cols-2 py-3 border-b border-gray-200">

                            <span class="text-sm text-gray-600">
                                Paket Internet
                            </span>

                            <span class="text-sm font-medium text-gray-800">
                                {{ $namaPaket }}
                            </span>

                        </div>


                        {{-- JENIS GANGGUAN --}}
                        <div class="grid grid-cols-2 py-3 border-b border-gray-200">

                            <span class="text-sm text-gray-600">
                                Jenis Gangguan
                            </span>

                            <span class="text-sm font-medium text-gray-800">
                                {{ $jenisGangguan }}
                            </span>

                        </div>


                        {{-- TANGGAL LAPOR --}}
                        <div class="grid grid-cols-2 py-3 border-b border-gray-200">

                            <span class="text-sm text-gray-600">
                                Tanggal Lapor
                            </span>

                            <span class="text-sm font-medium text-gray-800">
                                {{ $tanggalLapor }}
                            </span>

                        </div>


                        {{-- JADWAL KUNJUNGAN --}}
                        <div class="grid grid-cols-2 py-3 border-b border-gray-200">

                            <span class="text-sm text-gray-600">
                                Jadwal Kunjungan
                            </span>

                            <span class="text-sm font-medium text-gray-800">

                                {{ $tanggalKunjungan }}

                                @if($jamKunjungan !== '-')
                                    - {{ $jamKunjungan }}
                                @endif

                            </span>

                        </div>


                        {{-- STATUS --}}
                        <div class="grid grid-cols-2 py-3">

                            <span class="text-sm text-gray-600">
                                Status
                            </span>

                            <span>

                                <span
                                    class="inline-flex px-3 py-1 rounded-full text-xs font-medium
                                    {{ $statusClass[$status] ?? 'bg-gray-100 text-gray-700' }}"
                                >
                                    {{ $statusLabel[$status] ?? 'Menunggu' }}
                                </span>

                            </span>

                        </div>

                    </div>

                </div>


                {{-- KELUHAN PELANGGAN --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">

                    <h2 class="text-lg font-bold text-gray-800 mb-4">
                        Keluhan Pelanggan
                    </h2>

                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">

                        <p class="text-sm text-gray-700 leading-relaxed">
                            {{ $deskripsi }}
                        </p>

                    </div>

                </div>


                {{-- HASIL PENGECEKAN --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">

                    <h2 class="text-lg font-bold text-gray-800 mb-4">
                        Hasil Pengecekan
                    </h2>

                    <textarea
                        name="keluhan"
                        rows="4"
                        placeholder="Tuliskan hasil pemeriksaan..."
                        class="w-full border border-gray-300 rounded-lg px-4 py-3 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none resize-none"
                    >{{ old('keluhan', $perbaikan?->keluhan ?? '') }}</textarea>

                </div>


                {{-- TINDAKAN PERBAIKAN --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">

                    <h2 class="text-lg font-bold text-gray-800 mb-4">
                        Tindakan Perbaikan
                    </h2>

                    <textarea
                        name="tindakan"
                        rows="4"
                        placeholder="Tuliskan tindakan perbaikan yang dilakukan..."
                        class="w-full border border-gray-300 rounded-lg px-4 py-3 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none resize-none"
                    >{{ old('tindakan', $perbaikan?->tindakan ?? '') }}</textarea>

                </div>

            </div>


            {{-- ========================================================= --}}
            {{-- KOLOM KANAN --}}
            {{-- ========================================================= --}}

            <div class="space-y-6">


                {{-- STATUS PERBAIKAN --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">

                    <h2 class="text-lg font-bold text-gray-800 mb-6">
                        Status Perbaikan
                    </h2>


                    <div class="relative px-2">

                        <div class="absolute left-8 right-8 top-4 h-0.5 bg-gray-300"></div>

                        <div
                            class="absolute left-8 top-4 h-0.5 bg-blue-600"
                            style="width: {{ $currentIndex * 33.33 }}%"
                        ></div>


                        <div class="relative flex justify-between">

                            @foreach($steps as $index => $step)

                                <div class="flex flex-col items-center text-center w-1/4">

                                    <div
                                        class="w-8 h-8 rounded-full
                                        {{ $index <= $currentIndex
                                            ? 'bg-blue-600 border-4 border-white shadow'
                                            : 'bg-white border-2 border-gray-400'
                                        }}
                                        flex items-center justify-center z-10"
                                    >

                                        @if($index <= $currentIndex)

                                            <i
                                                data-lucide="check"
                                                class="w-4 h-4 text-white"
                                            ></i>

                                        @endif

                                    </div>


                                    <span
                                        class="text-xs
                                        {{ $index <= $currentIndex
                                            ? 'font-medium text-gray-700'
                                            : 'text-gray-600'
                                        }}
                                        mt-2"
                                    >
                                        {{ $step['label'] }}
                                    </span>

                                </div>

                            @endforeach

                        </div>

                    </div>


                    {{-- UBAH STATUS --}}
                    <div class="mt-8">

                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Ubah Status
                        </label>

                        <div class="flex flex-col sm:flex-row gap-3">

                            <select
                                name="status"
                                class="flex-1 border border-gray-300 rounded-lg px-4 py-3 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none"
                            >

                                <option
                                    value="menunggu"
                                    {{ $status === 'menunggu' ? 'selected' : '' }}
                                >
                                    Menunggu
                                </option>

                                <option
                                    value="perjalanan"
                                    {{ $status === 'perjalanan' ? 'selected' : '' }}
                                >
                                    Dalam Perjalanan
                                </option>

                                <option
                                    value="ditangani"
                                    {{ $status === 'ditangani' ? 'selected' : '' }}
                                >
                                    Sedang Ditangani
                                </option>

                                <option
                                    value="selesai"
                                    {{ $status === 'selesai' ? 'selected' : '' }}
                                >
                                    Selesai
                                </option>

                            </select>


                            <button
                                type="submit"
                                class="inline-flex items-center justify-center gap-2 px-5 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition text-sm font-medium"
                            >

                                <i
                                    data-lucide="refresh-cw"
                                    class="w-4 h-4"
                                ></i>

                                Update Status

                            </button>

                        </div>

                    </div>

                </div>


                {{-- CATATAN TEKNISI --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">

                    <h2 class="text-lg font-bold text-gray-800 mb-4">
                        Catatan Teknisi
                    </h2>

                    <textarea
                        name="catatan"
                        rows="4"
                        placeholder="Tuliskan catatan teknisi..."
                        class="w-full border border-gray-300 rounded-lg px-4 py-3 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none resize-none"
                    >{{ old('catatan', $perbaikan?->catatan ?? '') }}</textarea>

                </div>


                {{-- BUKTI PERBAIKAN --}}
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">

                    <h2 class="text-lg font-bold text-gray-800 mb-4">
                        Bukti Perbaikan
                    </h2>


                    @if($perbaikan?->bukti_foto)

                        <div class="mb-4">

                            <img
                                src="{{ asset('storage/'.$perbaikan->bukti_foto) }}"
                                alt="Bukti Perbaikan"
                                class="w-full max-h-72 object-cover rounded-lg border border-gray-200"
                            >

                        </div>

                    @endif


                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-6">

                        <div class="text-center mb-4">

                            <div class="w-12 h-12 rounded-lg bg-gray-100 text-gray-500 flex items-center justify-center mx-auto mb-3">

                                <i
                                    data-lucide="image-plus"
                                    class="w-6 h-6"
                                ></i>

                            </div>


                            <p class="text-sm text-gray-600">

                                {{
                                    $perbaikan?->bukti_foto
                                    ? 'Ganti foto bukti perbaikan'
                                    : 'Upload foto sebagai bukti perbaikan'
                                }}

                            </p>

                        </div>


                        <input
                            type="file"
                            name="bukti_foto"
                            accept="image/jpeg,image/jpg,image/png"
                            class="w-full border border-gray-300 rounded-lg px-4 py-3 text-sm bg-white cursor-pointer"
                        >


                        <p class="text-xs text-gray-400 mt-2 text-center">
                            Format JPG, JPEG, PNG
                        </p>

                    </div>

                </div>

            </div>

        </div>


        {{-- TOMBOL BAWAH --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 mt-6">

            <div class="flex flex-col sm:flex-row justify-end gap-3">

                <button
                    type="submit"
                    class="inline-flex items-center justify-center gap-2 px-5 py-3 border border-blue-600 text-blue-600 rounded-lg hover:bg-blue-50 transition font-medium"
                >

                    <i
                        data-lucide="save"
                        class="w-5 h-5"
                    ></i>

                    Simpan Perubahan

                </button>


                <button
                    type="submit"
                    name="status"
                    value="selesai"
                    onclick="return confirm('Apakah Anda yakin pekerjaan perbaikan ini sudah selesai?')"
                    class="inline-flex items-center justify-center gap-2 px-5 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium shadow-sm"
                >

                    <i
                        data-lucide="check-circle"
                        class="w-5 h-5"
                    ></i>

                    Tandai Selesai

                </button>

            </div>

        </div>

    </form>

</div>


<script>
    lucide.createIcons();
</script>

@endsection