@extends('layouts.app')

@section('content')
<div class="space-y-5">

    {{-- HEADER --}}
    <div>
        <h1 class="text-2xl md:text-3xl font-bold text-gray-900">
            Laporan
        </h1>
        <p class="text-sm md:text-base text-gray-600 mt-1">
            Kelola dan kirim laporan hasil pekerjaan kepada admin
        </p>
    </div>

    {{-- PESAN --}}
    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg text-sm">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-sm">
            {{ session('error') }}
        </div>
    @endif

    {{-- STATISTIK --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">

        <div class="bg-blue-50 rounded-xl p-5 text-center">
            <div class="w-10 h-10 mx-auto rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center">
                <i data-lucide="briefcase-business" class="w-5 h-5"></i>
            </div>
            <p class="text-2xl font-bold text-gray-800 mt-2">
                {{ $totalSelesai }}
            </p>
            <p class="text-sm text-gray-600">Pekerjaan Selesai</p>
            <p class="text-[10px] text-gray-400 mt-1">
                Telah diperbaiki di lapangan
            </p>
        </div>

        <div class="bg-orange-50 rounded-xl p-5 text-center">
            <div class="w-10 h-10 mx-auto rounded-lg bg-orange-100 text-orange-500 flex items-center justify-center">
                <i data-lucide="clock-3" class="w-5 h-5"></i>
            </div>
            <p class="text-2xl font-bold text-gray-800 mt-2">
                {{ $belumDilaporkan }}
            </p>
            <p class="text-sm text-gray-600">Belum Dilaporkan</p>
            <p class="text-[10px] text-gray-400 mt-1">
                Menunggu laporan dari Anda
            </p>
        </div>

        <div class="bg-purple-50 rounded-xl p-5 text-center">
            <div class="w-10 h-10 mx-auto rounded-lg bg-purple-100 text-purple-600 flex items-center justify-center">
                <i data-lucide="send" class="w-5 h-5"></i>
            </div>
            <p class="text-2xl font-bold text-gray-800 mt-2">
                {{ $laporanTerkirim }}
            </p>
            <p class="text-sm text-gray-600">Laporan Terkirim</p>
            <p class="text-[10px] text-gray-400 mt-1">
                Sudah dikirim ke admin
            </p>
        </div>

        <div class="bg-green-50 rounded-xl p-5 text-center">
            <div class="w-10 h-10 mx-auto rounded-lg bg-green-100 text-green-600 flex items-center justify-center">
                <i data-lucide="circle-check" class="w-5 h-5"></i>
            </div>
            <p class="text-2xl font-bold text-gray-800 mt-2">
                {{ $laporanDisetujui }}
            </p>
            <p class="text-sm text-gray-600">Laporan Disetujui</p>
            <p class="text-[10px] text-gray-400 mt-1">
                Diterima oleh admin
            </p>
        </div>

    </div>

    {{-- TAB --}}
    <div class="border-b border-gray-300 flex gap-6">

        <button
            type="button"
            onclick="showTab('belum')"
            id="tabBelum"
            class="px-1 pb-3 text-sm font-semibold text-blue-600 border-b-2 border-blue-600"
        >
            Pekerjaan Belum Dilaporkan ({{ $belumDilaporkan }})
        </button>

        <button
            type="button"
            onclick="showTab('terkirim')"
            id="tabTerkirim"
            class="px-1 pb-3 text-sm font-semibold text-gray-600"
        >
            Laporan Terkirim ({{ $laporanTerkirim }})
        </button>

    </div>

    {{-- TAB BELUM DILAPORKAN --}}
    <div id="tabContentBelum">

        <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">

            <div class="px-5 py-4 border-b border-gray-200">
                <h2 class="font-bold text-gray-800">
                    Pekerjaan Belum Dilaporkan
                </h2>
            </div>

            <div class="overflow-x-auto">

                <table class="w-full text-sm">

                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-5 py-3 text-left">No</th>
                            <th class="px-5 py-3 text-left">ID Tiket</th>
                            <th class="px-5 py-3 text-left">Nama Pelanggan</th>
                            <th class="px-5 py-3 text-left">Jenis Gangguan</th>
                            <th class="px-5 py-3 text-left">Tanggal Perbaikan</th>
                            <th class="px-5 py-3 text-left">Status</th>
                            <th class="px-5 py-3 text-center">Aksi</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-100">

                        @forelse($tugasBelumDilaporkan as $index => $tugas)

                            @php
                                $tiket = $tugas->tiketGangguan;
                                $pelanggan = $tiket?->pelanggan;
                                $laporanGangguan = $tiket?->laporanGangguan;
                                $perbaikan = $tugas->perbaikan;
                            @endphp

                            <tr class="hover:bg-gray-50">

                                <td class="px-5 py-4">
                                    {{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}
                                </td>

                                <td class="px-5 py-4 font-medium">
                                    {{ $tiket?->id_tiket ?? '-' }}
                                </td>

                                <td class="px-5 py-4">
                                    {{ $pelanggan?->user?->name ?? '-' }}
                                </td>

                                <td class="px-5 py-4">
                                    {{ $laporanGangguan?->jenis_gangguan ?? '-' }}
                                </td>

                                <td class="px-5 py-4">
                                    {{ $perbaikan?->tanggal_perbaikan?->format('d F Y') ?? '-' }}
                                </td>

                                <td class="px-5 py-4">
                                    <span class="px-3 py-1.5 rounded-full bg-orange-100 text-orange-600 text-xs font-medium">
                                        Menunggu Laporan
                                    </span>
                                </td>

                                <td class="px-5 py-4 text-center">

                                    <a
                                        href="/buat-laporan-teknisi/{{ $tugas->id }}"
                                        class="inline-flex items-center gap-1.5 px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-xs font-medium transition"
                                    >
                                        <i data-lucide="file-plus" class="w-4 h-4"></i>
                                        Buat Laporan
                                    </a>

                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="7" class="px-5 py-10 text-center text-gray-500">
                                    Tidak ada pekerjaan yang belum dilaporkan.
                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>

    {{-- TAB LAPORAN TERKIRIM --}}
    <div id="tabContentTerkirim" class="hidden">

        <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">

            <div class="px-5 py-4 border-b border-gray-200">
                <h2 class="font-bold text-gray-800">
                    Laporan Terkirim
                </h2>
            </div>

            <div class="overflow-x-auto">

                <table class="w-full text-sm">

                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-5 py-3 text-left">No</th>
                            <th class="px-5 py-3 text-left">ID Laporan</th>
                            <th class="px-5 py-3 text-left">ID Tiket</th>
                            <th class="px-5 py-3 text-left">Nama Pelanggan</th>
                            <th class="px-5 py-3 text-left">Jenis Gangguan</th>
                            <th class="px-5 py-3 text-left">Tanggal Dikirim</th>
                            <th class="px-5 py-3 text-left">Status</th>
                            <th class="px-5 py-3 text-center">Aksi</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-100">

                        @forelse($laporan as $index => $item)

                            @php
                                $tiket = $item->penugasan?->tiketGangguan;
                                $pelanggan = $tiket?->pelanggan;
                                $laporanGangguan = $tiket?->laporanGangguan;
                                $perbaikan = $item->penugasan?->perbaikan;

                                if ($item->status === 'menunggu_approval') {
                                    $statusLabel = 'Menunggu Approval';
                                    $statusClass = 'bg-blue-100 text-blue-700';
                                } elseif ($item->status === 'disetujui') {
                                    $statusLabel = 'Disetujui';
                                    $statusClass = 'bg-green-100 text-green-700';
                                } elseif ($item->status === 'ditolak') {
                                    $statusLabel = 'Perlu Diperbaiki';
                                    $statusClass = 'bg-red-100 text-red-600';
                                } else {
                                    $statusLabel = ucfirst(
                                        str_replace('_', ' ', $item->status ?? '-')
                                    );
                                    $statusClass = 'bg-gray-100 text-gray-600';
                                }
                            @endphp

                            <tr class="hover:bg-gray-50">

                                <td class="px-5 py-4">
                                    {{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}
                                </td>

                                <td class="px-5 py-4 font-medium">
                                    {{ $item->id_laporan }}
                                </td>

                                <td class="px-5 py-4">
                                    {{ $tiket?->id_tiket ?? '-' }}
                                </td>

                                <td class="px-5 py-4">
                                    {{ $pelanggan?->user?->name ?? '-' }}
                                </td>

                                <td class="px-5 py-4">
                                    {{ $laporanGangguan?->jenis_gangguan ?? '-' }}
                                </td>

                                <td class="px-5 py-4">
                                    {{ $item->tanggal_laporan?->format('d F Y') ?? '-' }}
                                </td>

                                <td class="px-5 py-4">
                                    <span class="px-3 py-1.5 rounded-full {{ $statusClass }} text-xs font-medium">
                                        {{ $statusLabel }}
                                    </span>
                                </td>

                                <td class="px-5 py-4 text-center">

                                    <div class="flex items-center justify-center gap-2">

                                        {{-- TOMBOL LIHAT --}}
                                        <button
                                            type="button"
                                            class="btn-detail-laporan px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-xs font-medium"
                                            data-id="{{ $item->id_laporan }}"
                                            data-penugasan="{{ $item->penugasan_teknisi_id }}"
                                            data-tiket="{{ $tiket?->id_tiket ?? '-' }}"
                                            data-pelanggan="{{ $pelanggan?->user?->name ?? '-' }}"
                                            data-gangguan="{{ $laporanGangguan?->jenis_gangguan ?? '-' }}"
                                            data-status="{{ $statusLabel }}"
                                            data-hasil="{{ e($item->hasil_perbaikan ?? '-') }}"
                                            data-tindakan="{{ e($item->tindakan ?? '-') }}"
                                            data-catatan="{{ e($item->catatan ?? '-') }}"
                                            data-foto="{{ $perbaikan?->bukti_foto ?? '' }}"
                                        >
                                            Lihat
                                        </button>

                                        {{-- TOMBOL PERBAIKI HANYA JIKA DITOLAK --}}
                                        @if($item->status === 'ditolak')

                                            <a
                                                href="/buat-laporan-teknisi/{{ $item->penugasan_teknisi_id }}"
                                                class="inline-flex items-center gap-1.5 px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg text-xs font-medium"
                                            >
                                                <i data-lucide="pencil" class="w-4 h-4"></i>
                                                Perbaiki
                                            </a>

                                        @endif

                                    </div>

                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="8" class="px-5 py-10 text-center text-gray-500">
                                    Belum ada laporan yang dikirim.
                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>

{{-- MODAL DETAIL --}}
<div
    id="modalLaporan"
    class="hidden fixed inset-0 z-[100] bg-black/40 p-4"
>

    <div class="min-h-full flex items-center justify-center">

        <div class="bg-white w-full max-w-lg rounded-2xl shadow-xl max-h-[90vh] overflow-y-auto">

            {{-- HEADER --}}
            <div class="flex items-center justify-between p-5 border-b">

                <div>
                    <h2 class="font-bold text-lg">
                        Detail Laporan
                    </h2>

                    <p class="text-sm text-gray-500">
                        Informasi laporan teknisi
                    </p>
                </div>

                <button
                    type="button"
                    onclick="closeLaporan()"
                    class="text-gray-500 hover:text-gray-800"
                >
                    <i data-lucide="x" class="w-6 h-6"></i>
                </button>

            </div>

            {{-- ISI --}}
            <div class="p-5 space-y-4">

                <div class="grid grid-cols-2 gap-4">

                    <div>
                        <p class="text-xs text-gray-500">
                            ID Laporan
                        </p>
                        <p id="detailIdLaporan" class="font-semibold">
                            -
                        </p>
                    </div>

                    <div>
                        <p class="text-xs text-gray-500">
                            ID Tiket
                        </p>
                        <p id="detailIdTiket" class="font-semibold">
                            -
                        </p>
                    </div>

                    <div>
                        <p class="text-xs text-gray-500">
                            Pelanggan
                        </p>
                        <p id="detailPelanggan" class="font-semibold">
                            -
                        </p>
                    </div>

                    <div>
                        <p class="text-xs text-gray-500">
                            Gangguan
                        </p>
                        <p id="detailGangguan" class="font-semibold">
                            -
                        </p>
                    </div>

                </div>

                {{-- STATUS --}}
                <div>

                    <p class="text-xs text-gray-500">
                        Status
                    </p>

                    <span
                        id="detailStatus"
                        class="inline-flex mt-1 px-3 py-1.5 rounded-full text-xs font-medium"
                    >
                    </span>

                </div>

                {{-- HASIL --}}
                <div>

                    <p class="text-xs text-gray-500 mb-1">
                        Hasil Perbaikan
                    </p>

                    <div
                        id="detailHasil"
                        class="bg-gray-50 rounded-lg p-3 text-sm text-gray-700 whitespace-pre-line"
                    >
                        -
                    </div>

                </div>

                {{-- TINDAKAN --}}
                <div>

                    <p class="text-xs text-gray-500 mb-1">
                        Tindakan
                    </p>

                    <div
                        id="detailTindakan"
                        class="bg-gray-50 rounded-lg p-3 text-sm text-gray-700 whitespace-pre-line"
                    >
                        -
                    </div>

                </div>

                {{-- CATATAN --}}
                <div>

                    <p
                        id="labelCatatan"
                        class="text-xs text-gray-500 mb-1"
                    >
                        Catatan
                    </p>

                    <div
                        id="detailCatatan"
                        class="bg-gray-50 rounded-lg p-3 text-sm text-gray-700 whitespace-pre-line"
                    >
                        -
                    </div>

                </div>

                {{-- BUKTI FOTO --}}
                <div id="detailFotoContainer" class="hidden">

                    <p class="text-xs text-gray-500 mb-2">
                        Bukti Perbaikan
                    </p>

                    <img
                        id="detailFoto"
                        src=""
                        alt="Bukti Perbaikan"
                        class="w-full max-h-72 object-contain rounded-lg border border-gray-200"
                    >

                </div>

                {{-- INFO PERBAIKAN --}}
                <div
                    id="infoPerbaikan"
                    class="hidden bg-red-50 border border-red-200 rounded-lg p-4"
                >

                    <div class="flex items-start gap-3">

                        <div class="text-red-600 shrink-0">
                            <i data-lucide="circle-alert" class="w-5 h-5"></i>
                        </div>

                        <div>

                            <p class="font-semibold text-red-700 text-sm">
                                Laporan Perlu Diperbaiki
                            </p>

                            <p class="text-xs text-red-600 mt-1">
                                Perbaiki laporan sesuai catatan Admin sebelum dikirim ulang.
                            </p>

                        </div>

                    </div>

                </div>

            </div>

            {{-- FOOTER --}}
            <div class="flex justify-end gap-2 p-5 border-t">

                {{-- DEFAULT HIDDEN --}}
                <a
                    id="btnPerbaiki"
                    href="#"
                    class="hidden items-center gap-2 px-5 py-2.5 bg-red-600 hover:bg-red-700 text-white rounded-lg text-sm font-medium"
                >
                    <i data-lucide="pencil" class="w-4 h-4"></i>
                    Perbaiki Laporan
                </a>

                <button
                    type="button"
                    onclick="closeLaporan()"
                    class="px-5 py-2.5 border rounded-lg text-sm hover:bg-gray-50"
                >
                    Tutup
                </button>

            </div>

        </div>

    </div>

</div>

<script>
function showTab(tab) {

    const belum = document.getElementById('tabContentBelum');
    const terkirim = document.getElementById('tabContentTerkirim');

    const tabBelum = document.getElementById('tabBelum');
    const tabTerkirim = document.getElementById('tabTerkirim');

    if (tab === 'belum') {

        belum.classList.remove('hidden');
        terkirim.classList.add('hidden');

        tabBelum.classList.add(
            'text-blue-600',
            'border-b-2',
            'border-blue-600'
        );

        tabBelum.classList.remove('text-gray-600');

        tabTerkirim.classList.remove(
            'text-blue-600',
            'border-b-2',
            'border-blue-600'
        );

        tabTerkirim.classList.add('text-gray-600');

    } else {

        belum.classList.add('hidden');
        terkirim.classList.remove('hidden');

        tabTerkirim.classList.add(
            'text-blue-600',
            'border-b-2',
            'border-blue-600'
        );

        tabTerkirim.classList.remove('text-gray-600');

        tabBelum.classList.remove(
            'text-blue-600',
            'border-b-2',
            'border-blue-600'
        );

        tabBelum.classList.add('text-gray-600');

    }
}

document.querySelectorAll('.btn-detail-laporan').forEach(function(button) {

    button.addEventListener('click', function() {

        const status = this.dataset.status;

        document.getElementById('detailIdLaporan').innerText =
            this.dataset.id || '-';

        document.getElementById('detailIdTiket').innerText =
            this.dataset.tiket || '-';

        document.getElementById('detailPelanggan').innerText =
            this.dataset.pelanggan || '-';

        document.getElementById('detailGangguan').innerText =
            this.dataset.gangguan || '-';

        document.getElementById('detailHasil').innerText =
            this.dataset.hasil || '-';

        document.getElementById('detailTindakan').innerText =
            this.dataset.tindakan || '-';

        document.getElementById('detailCatatan').innerText =
            this.dataset.catatan || '-';

        /* STATUS */
        const badge = document.getElementById('detailStatus');

        badge.innerText = status || '-';

        badge.className =
            'inline-flex mt-1 px-3 py-1.5 rounded-full text-xs font-medium';

        if (status === 'Disetujui') {

            badge.classList.add(
                'bg-green-100',
                'text-green-700'
            );

        } else if (status === 'Perlu Diperbaiki') {

            badge.classList.add(
                'bg-red-100',
                'text-red-600'
            );

        } else {

            badge.classList.add(
                'bg-blue-100',
                'text-blue-700'
            );

        }

        /* TOMBOL PERBAIKI */
        const btnPerbaiki =
            document.getElementById('btnPerbaiki');

        const infoPerbaikan =
            document.getElementById('infoPerbaikan');

        const labelCatatan =
            document.getElementById('labelCatatan');

        if (status === 'Perlu Diperbaiki') {

            btnPerbaiki.href =
                '/buat-laporan-teknisi/' +
                this.dataset.penugasan;

            btnPerbaiki.classList.remove('hidden');
            btnPerbaiki.classList.add('inline-flex');

            infoPerbaikan.classList.remove('hidden');

            labelCatatan.innerText =
                'Catatan / Alasan Penolakan';

        } else {

            btnPerbaiki.href = '#';

            btnPerbaiki.classList.add('hidden');
            btnPerbaiki.classList.remove('inline-flex');

            infoPerbaikan.classList.add('hidden');

            labelCatatan.innerText = 'Catatan';

        }

        /* FOTO */
        const fotoContainer =
            document.getElementById('detailFotoContainer');

        const foto =
            document.getElementById('detailFoto');

        if (this.dataset.foto) {

            foto.src =
                '/storage/' + this.dataset.foto;

            fotoContainer.classList.remove('hidden');

        } else {

            foto.src = '';

            fotoContainer.classList.add('hidden');

        }

        /* BUKA MODAL */
        document
            .getElementById('modalLaporan')
            .classList.remove('hidden');

        lucide.createIcons();

    });

});

function closeLaporan() {

    document
        .getElementById('modalLaporan')
        .classList.add('hidden');

}

document
    .getElementById('modalLaporan')
    .addEventListener('click', function(e) {

        if (e.target === this) {
            closeLaporan();
        }

    });

lucide.createIcons();
</script>

@endsection