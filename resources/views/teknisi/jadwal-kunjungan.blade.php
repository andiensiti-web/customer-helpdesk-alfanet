@extends('layouts.app')

@section('content')

@php
    $totalJadwal = $totalJadwal ?? 0;
    $jadwalSelesai = $jadwalSelesai ?? 0;
    $jadwalMenunggu = $jadwalMenunggu ?? 0;
    $jadwalDalamProses = $jadwalDalamProses ?? 0;
    $jadwals = $jadwals ?? collect();

    $hariIni = $jadwals->filter(function ($jadwal) {
        return $jadwal->tanggal_kunjungan &&
            $jadwal->tanggal_kunjungan->isToday();
    });

    $mendatang = $jadwals->filter(function ($jadwal) {
        return $jadwal->tanggal_kunjungan &&
            $jadwal->tanggal_kunjungan->isFuture();
    })->groupBy(function ($jadwal) {
        return $jadwal->tanggal_kunjungan->format('Y-m-d');
    });
@endphp

<div class="space-y-7">

    {{-- HEADER --}}
    <div>
        <h1 class="text-2xl md:text-3xl font-bold text-gray-900">
            Jadwal Kunjungan
        </h1>

        <p class="text-gray-600 mt-1">
            Lihat jadwal kunjungan pelanggan yang ditugaskan kepada Anda.
        </p>
    </div>

    {{-- STATISTIK --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">

        {{-- TOTAL --}}
        <div class="bg-blue-50 rounded-xl p-6">
            <div class="flex justify-center">
                <div class="w-12 h-12 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center">
                    <i data-lucide="calendar-days" class="w-6 h-6"></i>
                </div>
            </div>

            <div class="text-center mt-4">
                <p class="text-3xl font-bold text-gray-800">
                    {{ $totalJadwal }}
                </p>

                <p class="text-gray-600 mt-1">
                    Total Kunjungan
                </p>
            </div>
        </div>

        {{-- SELESAI --}}
        <div class="bg-green-50 rounded-xl p-6">
            <div class="flex justify-center">
                <div class="w-12 h-12 rounded-xl bg-green-100 text-green-600 flex items-center justify-center">
                    <i data-lucide="circle-check" class="w-6 h-6"></i>
                </div>
            </div>

            <div class="text-center mt-4">
                <p class="text-3xl font-bold text-gray-800">
                    {{ $jadwalSelesai }}
                </p>

                <p class="text-gray-600 mt-1">
                    Tugas Selesai
                </p>
            </div>
        </div>

        {{-- MENUNGGU --}}
        <div class="bg-orange-50 rounded-xl p-6">
            <div class="flex justify-center">
                <div class="w-12 h-12 rounded-xl bg-orange-100 text-orange-600 flex items-center justify-center">
                    <i data-lucide="clock-3" class="w-6 h-6"></i>
                </div>
            </div>

            <div class="text-center mt-4">
                <p class="text-3xl font-bold text-gray-800">
                    {{ $jadwalMenunggu }}
                </p>

                <p class="text-gray-600 mt-1">
                    Menunggu
                </p>
            </div>
        </div>

        {{-- DALAM PROSES --}}
        <div class="bg-purple-50 rounded-xl p-6">
            <div class="flex justify-center">
                <div class="w-12 h-12 rounded-xl bg-purple-100 text-purple-600 flex items-center justify-center">
                    <i data-lucide="circle-alert" class="w-6 h-6"></i>
                </div>
            </div>

            <div class="text-center mt-4">
                <p class="text-3xl font-bold text-gray-800">
                    {{ $jadwalDalamProses }}
                </p>

                <p class="text-gray-600 mt-1">
                    Dalam Proses
                </p>
            </div>
        </div>

    </div>

    {{-- KUNJUNGAN --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

        {{-- HARI INI --}}
        <div>

            <div class="flex items-center gap-3 mb-4">
                <i data-lucide="calendar-days" class="w-6 h-6 text-blue-600"></i>

                <h2 class="text-lg font-bold text-gray-800">
                    Kunjungan Hari Ini
                </h2>
            </div>

            <div>

                @forelse($hariIni as $jadwal)

                    @php
                        $pelanggan = $jadwal->pelanggan;
                        $user = $pelanggan?->user;
                        $tiket = $jadwal->penugasan?->tiketGangguan;
                        $laporanGangguan = $tiket?->laporanGangguan;

                        $status = strtolower($jadwal->status ?? '');

                        if ($status === 'selesai') {
                            $statusText = 'Selesai';
                            $statusClass = 'bg-green-100 text-green-700';
                        } elseif (in_array($status, [
                            'berlangsung',
                            'diproses',
                            'dalam_proses',
                            'dalam_perjalanan'
                        ])) {
                            $statusText = 'Dalam Perjalanan';
                            $statusClass = 'bg-blue-100 text-blue-700';
                        } else {
                            $statusText = 'Belum Dikerjakan';
                            $statusClass = 'bg-gray-100 text-gray-700';
                        }
                    @endphp

                    <div class="flex items-center gap-3 py-4 border-b border-gray-200">

                        {{-- JAM --}}
                        <div class="shrink-0">
                            <span class="inline-flex items-center justify-center px-4 py-2 rounded-full bg-blue-100 text-blue-700 font-medium text-sm">
                                {{ $jadwal->jam_kunjungan ?? '-' }}
                            </span>
                        </div>

                        {{-- PELANGGAN --}}
                        <div class="min-w-0 flex-1">

                            <p class="font-semibold text-gray-800 truncate">
                                {{ $user?->name ?? '-' }}
                            </p>

                            <p class="text-sm text-gray-500 truncate">
                                {{ $user?->alamat ?? '-' }}
                            </p>

                        </div>

                        {{-- GANGGUAN --}}
                        <div class="hidden md:block w-32 text-sm font-medium text-gray-700">
                            {{ $laporanGangguan?->jenis_gangguan ?? '-' }}
                        </div>

                        {{-- STATUS --}}
                        <div class="hidden sm:block">
                            <span class="inline-flex px-3 py-1.5 rounded-full text-xs font-medium {{ $statusClass }}">
                                {{ $statusText }}
                            </span>
                        </div>

                        {{-- DETAIL --}}
                        <button
                            type="button"
                            onclick="openDetail(this)"
                            data-tiket="{{ $tiket?->id_tiket ?? '-' }}"
                            data-pelanggan="{{ $pelanggan?->id_pelanggan ?? '-' }}"
                            data-waktu="{{ $jadwal->jam_kunjungan ?? '-' }}"
                            data-nama="{{ $user?->name ?? '-' }}"
                            data-telepon="{{ $user?->telepon ?? '-' }}"
                            data-alamat="{{ $user?->alamat ?? '-' }}"
                            data-paket="{{ $pelanggan?->paket?->nama_paket ?? '-' }}"
                            data-gangguan="{{ $laporanGangguan?->jenis_gangguan ?? '-' }}"
                            data-catatan="{{ $jadwal->catatan ?? '-' }}"
                            data-status="{{ $statusText }}"
                            class="shrink-0 text-gray-700 hover:text-blue-600"
                        >
                            <i data-lucide="arrow-right" class="w-6 h-6"></i>
                        </button>

                    </div>

                @empty

                    <div class="py-10 text-center text-gray-500">
                        Tidak ada kunjungan hari ini.
                    </div>

                @endforelse

            </div>

        </div>

        {{-- MENDATANG --}}
        <div>

            <div class="flex items-center gap-3 mb-4">
                <i data-lucide="calendar-days" class="w-6 h-6 text-blue-600"></i>

                <h2 class="text-lg font-bold text-gray-800">
                    Kunjungan Mendatang
                </h2>
            </div>

            <div class="space-y-6">

                @forelse($mendatang as $tanggal => $items)

                    @php
                        $tanggalCarbon = \Carbon\Carbon::parse($tanggal);
                    @endphp

                    <div>

                        <div class="bg-blue-50 text-blue-600 rounded-lg px-4 py-3 font-medium mb-2">
                            {{ $tanggalCarbon->locale('id')->translatedFormat('l, d F') }}
                        </div>

                        @foreach($items as $jadwal)

                            @php
                                $pelanggan = $jadwal->pelanggan;
                                $user = $pelanggan?->user;
                                $tiket = $jadwal->penugasan?->tiketGangguan;
                                $laporanGangguan = $tiket?->laporanGangguan;
                            @endphp

                            <div class="flex items-center gap-3 py-4 border-b border-gray-200">

                                {{-- JAM --}}
                                <div class="shrink-0">
                                    <span class="inline-flex items-center justify-center px-4 py-2 rounded-full bg-blue-100 text-blue-700 font-medium text-sm">
                                        {{ $jadwal->jam_kunjungan ?? '-' }}
                                    </span>
                                </div>

                                {{-- PELANGGAN --}}
                                <div class="min-w-0 flex-1">

                                    <p class="font-semibold text-gray-800 truncate">
                                        {{ $user?->name ?? '-' }}
                                    </p>

                                    <p class="text-sm text-gray-500 truncate">
                                        {{ $user?->alamat ?? '-' }}
                                    </p>

                                </div>

                                {{-- GANGGUAN --}}
                                <div class="hidden md:block w-32 text-sm font-medium text-gray-700">
                                    {{ $laporanGangguan?->jenis_gangguan ?? '-' }}
                                </div>

                                {{-- DETAIL --}}
                                <button
                                    type="button"
                                    onclick="openDetail(this)"
                                    data-tiket="{{ $tiket?->id_tiket ?? '-' }}"
                                    data-pelanggan="{{ $pelanggan?->id_pelanggan ?? '-' }}"
                                    data-waktu="{{ $jadwal->jam_kunjungan ?? '-' }}"
                                    data-nama="{{ $user?->name ?? '-' }}"
                                    data-telepon="{{ $user?->telepon ?? '-' }}"
                                    data-alamat="{{ $user?->alamat ?? '-' }}"
                                    data-paket="{{ $pelanggan?->paket?->nama_paket ?? '-' }}"
                                    data-gangguan="{{ $laporanGangguan?->jenis_gangguan ?? '-' }}"
                                    data-catatan="{{ $jadwal->catatan ?? '-' }}"
                                    data-status="{{ ucfirst($jadwal->status ?? 'Dijadwalkan') }}"
                                    class="shrink-0 text-gray-700 hover:text-blue-600"
                                >
                                    <i data-lucide="arrow-right" class="w-6 h-6"></i>
                                </button>

                            </div>

                        @endforeach

                    </div>

                @empty

                    <div class="py-10 text-center text-gray-500">
                        Tidak ada kunjungan mendatang.
                    </div>

                @endforelse

            </div>

        </div>

    </div>

</div>

{{-- MODAL DETAIL --}}
<div
    id="detailModal"
    class="fixed inset-0 z-50 hidden items-center justify-center bg-black/40 px-4"
>

    <div class="bg-white w-full max-w-2xl rounded-2xl shadow-xl overflow-hidden max-h-[90vh] overflow-y-auto">

        {{-- HEADER --}}
        <div class="px-7 py-6 border-b border-gray-200 flex items-start justify-between">

            <div>
                <h2 class="text-xl font-bold text-gray-800">
                    Detail Kunjungan
                </h2>

                <p class="text-gray-500 mt-1">
                    Informasi jadwal kunjungan pelanggan
                </p>
            </div>

            <button
                type="button"
                onclick="closeDetail()"
                class="text-gray-500 hover:text-gray-800"
            >
                <i data-lucide="x" class="w-6 h-6"></i>
            </button>

        </div>

        {{-- DETAIL --}}
        <div class="px-7 py-6">

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">

                <div>
                    <p class="text-sm text-gray-500">
                        ID Tiket
                    </p>

                    <p id="detailTiket" class="font-semibold text-gray-800 mt-1">
                        -
                    </p>
                </div>

                <div>
                    <p class="text-sm text-gray-500">
                        Waktu
                    </p>

                    <p id="detailWaktu" class="font-semibold text-gray-800 mt-1">
                        -
                    </p>
                </div>

                <div>
                    <p class="text-sm text-gray-500">
                        ID Pelanggan
                    </p>

                    <p id="detailPelanggan" class="font-semibold text-gray-800 mt-1">
                        -
                    </p>
                </div>

                <div>
                    <p class="text-sm text-gray-500">
                        Nama Pelanggan
                    </p>

                    <p id="detailNama" class="font-semibold text-gray-800 mt-1">
                        -
                    </p>
                </div>

                <div>
                    <p class="text-sm text-gray-500">
                        Nomor Telepon
                    </p>

                    <p id="detailTelepon" class="font-semibold text-gray-800 mt-1">
                        -
                    </p>
                </div>

                <div>
                    <p class="text-sm text-gray-500">
                        Paket Internet
                    </p>

                    <p id="detailPaket" class="font-semibold text-gray-800 mt-1">
                        -
                    </p>
                </div>

            </div>

            <div class="border-t border-gray-200 my-6"></div>

            <div class="space-y-5">

                <div>
                    <p class="text-sm text-gray-500">
                        Alamat
                    </p>

                    <p id="detailAlamat" class="font-semibold text-gray-800 mt-1">
                        -
                    </p>
                </div>

                <div>
                    <p class="text-sm text-gray-500">
                        Jenis Gangguan
                    </p>

                    <p id="detailGangguan" class="font-semibold text-gray-800 mt-1">
                        -
                    </p>
                </div>

                <div>
                    <p class="text-sm text-gray-500">
                        Catatan Kunjungan
                    </p>

                    <div id="detailCatatan" class="bg-gray-50 rounded-lg p-3 text-sm text-gray-700 mt-1">
                        -
                    </div>
                </div>

                <div>
                    <p class="text-sm text-gray-500">
                        Status
                    </p>

                    <span
                        id="detailStatus"
                        class="inline-block mt-2 px-3 py-1.5 rounded-full text-xs font-medium"
                    >
                        -
                    </span>
                </div>

            </div>

        </div>

        {{-- FOOTER --}}
        <div class="border-t border-gray-200 px-7 py-5 flex justify-end">

            <button
                type="button"
                onclick="closeDetail()"
                class="px-5 py-2.5 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50"
            >
                Tutup
            </button>

        </div>

    </div>

</div>

<script>
function openDetail(button) {

    document.getElementById('detailTiket').innerText =
        button.dataset.tiket || '-';

    document.getElementById('detailWaktu').innerText =
        (button.dataset.waktu || '-') + ' WIB';

    document.getElementById('detailPelanggan').innerText =
        button.dataset.pelanggan || '-';

    document.getElementById('detailNama').innerText =
        button.dataset.nama || '-';

    document.getElementById('detailTelepon').innerText =
        button.dataset.telepon || '-';

    document.getElementById('detailPaket').innerText =
        button.dataset.paket || '-';

    document.getElementById('detailAlamat').innerText =
        button.dataset.alamat || '-';

    document.getElementById('detailGangguan').innerText =
        button.dataset.gangguan || '-';

    document.getElementById('detailCatatan').innerText =
        button.dataset.catatan || '-';

    const status = button.dataset.status || '-';

    const statusElement =
        document.getElementById('detailStatus');

    statusElement.innerText = status;

    statusElement.className =
        'inline-block mt-2 px-3 py-1.5 rounded-full text-xs font-medium';

    if (status === 'Selesai') {

        statusElement.classList.add(
            'bg-green-100',
            'text-green-700'
        );

    } else if (
        status === 'Dalam Perjalanan' ||
        status === 'Berlangsung'
    ) {

        statusElement.classList.add(
            'bg-blue-100',
            'text-blue-700'
        );

    } else {

        statusElement.classList.add(
            'bg-gray-100',
            'text-gray-700'
        );

    }

    const modal =
        document.getElementById('detailModal');

    modal.classList.remove('hidden');
    modal.classList.add('flex');

    lucide.createIcons();
}

function closeDetail() {

    const modal =
        document.getElementById('detailModal');

    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

document
    .getElementById('detailModal')
    .addEventListener('click', function (event) {

        if (event.target === this) {
            closeDetail();
        }

    });

lucide.createIcons();
</script>

@endsection