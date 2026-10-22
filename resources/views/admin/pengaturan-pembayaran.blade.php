@extends('layouts.app')

@section('content')

<div class="p-6 space-y-6">

    {{-- HEADER --}}
    <div>
        <a href="/pengaturan"
           class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-gray-700">
            ← Kembali ke Pengaturan
        </a>

        <h1 class="mt-4 text-2xl font-bold text-gray-800">
            Metode Pembayaran
        </h1>

        <p class="text-sm text-gray-500 mt-1">
            Kelola rekening dan akun pembayaran yang akan ditampilkan kepada pelanggan.
        </p>
    </div>

    {{-- NOTIFIKASI --}}
    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
            {{ session('error') }}
        </div>
    @endif

    @if($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
            <ul class="list-disc list-inside text-sm">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- HEADER TABEL --}}
    <div class="bg-white border border-gray-200 rounded-xl overflow-hidden">

        <div class="p-5 border-b border-gray-200 flex flex-col md:flex-row md:items-center md:justify-between gap-4">

            <div>
                <h2 class="font-semibold text-gray-900">
                    Daftar Metode Pembayaran
                </h2>

                <p class="text-sm text-gray-500 mt-1">
                    Metode dengan status aktif akan muncul di halaman pembayaran pelanggan.
                </p>
            </div>

            <button
                type="button"
                onclick="openTambahMetode()"
                class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-3 rounded-lg flex items-center justify-center gap-2">

                <i data-lucide="plus" class="w-5 h-5"></i>

                Tambah Metode
            </button>

        </div>

        {{-- TABEL --}}
        <div class="overflow-x-auto">

            <table class="w-full text-sm">

                <thead class="bg-gray-50 border-b border-gray-200">

                    <tr>

                        <th class="px-5 py-4 text-left font-semibold text-gray-700">
                            No
                        </th>

                        <th class="px-5 py-4 text-left font-semibold text-gray-700">
                            Metode
                        </th>

                        <th class="px-5 py-4 text-left font-semibold text-gray-700">
                            Jenis
                        </th>

                        <th class="px-5 py-4 text-left font-semibold text-gray-700">
                            Nomor Rekening / Nomor
                        </th>

                        <th class="px-5 py-4 text-left font-semibold text-gray-700">
                            Atas Nama
                        </th>

                        <th class="px-5 py-4 text-left font-semibold text-gray-700">
                            Status
                        </th>

                        <th class="px-5 py-4 text-center font-semibold text-gray-700">
                            Aksi
                        </th>

                    </tr>

                </thead>

                <tbody class="divide-y divide-gray-100">

                    @forelse($metodePembayaran as $item)

                        <tr class="hover:bg-gray-50">

                            <td class="px-5 py-4">
                                {{ $loop->iteration }}
                            </td>

                            <td class="px-5 py-4 font-semibold text-gray-900">
                                {{ $item->nama_metode }}
                            </td>

                            <td class="px-5 py-4">

                                @if($item->jenis === 'bank')

                                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-blue-50 text-blue-700 text-xs font-medium">
                                        <i data-lucide="building-2" class="w-3.5 h-3.5"></i>
                                        Bank
                                    </span>

                                @else

                                    <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-purple-50 text-purple-700 text-xs font-medium">
                                        <i data-lucide="wallet" class="w-3.5 h-3.5"></i>
                                        E-Wallet
                                    </span>

                                @endif

                            </td>

                            <td class="px-5 py-4 font-medium text-gray-800">
                                {{ $item->nomor_rekening }}
                            </td>

                            <td class="px-5 py-4">
                                {{ $item->atas_nama }}
                            </td>

                            <td class="px-5 py-4">

                                @if($item->status === 'aktif')

                                    <span class="inline-flex px-3 py-1 rounded-full bg-green-50 text-green-700 text-xs font-medium">
                                        Aktif
                                    </span>

                                @else

                                    <span class="inline-flex px-3 py-1 rounded-full bg-gray-100 text-gray-600 text-xs font-medium">
                                        Nonaktif
                                    </span>

                                @endif

                            </td>

                            <td class="px-5 py-4">

                                <div class="flex items-center justify-center gap-2">

                                    {{-- EDIT --}}
                                    <button
                                        type="button"
                                        onclick="openEditMetode(
                                            {{ $item->id }},
                                            @js($item->nama_metode),
                                            @js($item->jenis),
                                            @js($item->nomor_rekening),
                                            @js($item->atas_nama),
                                            @js($item->status)
                                        )"
                                        class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg"
                                        title="Edit">

                                        <i data-lucide="pencil" class="w-4 h-4"></i>

                                    </button>

                                    {{-- HAPUS --}}
                                    <form
                                        action="/pengaturan/pembayaran/{{ $item->id }}"
                                        method="POST"
                                        onsubmit="return confirm('Yakin ingin menghapus metode pembayaran ini?')">

                                        @csrf
                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="p-2 text-red-600 hover:bg-red-50 rounded-lg"
                                            title="Hapus">

                                            <i data-lucide="trash-2" class="w-4 h-4"></i>

                                        </button>

                                    </form>

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="7"
                                class="px-5 py-12 text-center text-gray-500">

                                <div class="flex flex-col items-center">

                                    <i data-lucide="credit-card" class="w-10 h-10 text-gray-300 mb-3"></i>

                                    <p class="font-medium text-gray-700">
                                        Belum ada metode pembayaran
                                    </p>

                                    <p class="text-sm mt-1">
                                        Tambahkan rekening atau akun pembayaran terlebih dahulu.
                                    </p>

                                </div>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>


{{-- ===================================================== --}}
{{-- MODAL TAMBAH --}}
{{-- ===================================================== --}}

<div
    id="modalTambahMetode"
    class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50 p-4">

    <div class="bg-white rounded-xl w-full max-w-lg shadow-xl">

        <div class="p-5 border-b border-gray-200 flex items-center justify-between">

            <div>
                <h2 class="text-lg font-semibold text-gray-900">
                    Tambah Metode Pembayaran
                </h2>

                <p class="text-sm text-gray-500 mt-1">
                    Tambahkan rekening atau akun pembayaran.
                </p>
            </div>

            <button
                type="button"
                onclick="closeTambahMetode()"
                class="text-gray-400 hover:text-gray-600">

                <i data-lucide="x" class="w-5 h-5"></i>

            </button>

        </div>

        <form
            action="/pengaturan/pembayaran"
            method="POST">

            @csrf

            <div class="p-5 space-y-4">

                {{-- NAMA METODE --}}
                <div>

                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Nama Metode
                    </label>

                    <input
                        type="text"
                        name="nama_metode"
                        placeholder="Contoh: BCA, BRI, DANA"
                        required
                        class="w-full rounded-lg border border-gray-300 px-4 py-3 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">

                </div>

                {{-- JENIS --}}
                <div>

                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Jenis
                    </label>

                    <select
                        name="jenis"
                        required
                        class="w-full rounded-lg border border-gray-300 px-4 py-3 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">

                        <option value="bank">
                            Bank
                        </option>

                        <option value="ewallet">
                            E-Wallet
                        </option>

                    </select>

                </div>

                {{-- NOMOR --}}
                <div>

                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Nomor Rekening / Nomor
                    </label>

                    <input
                        type="text"
                        name="nomor_rekening"
                        placeholder="Contoh: 1234567890"
                        required
                        class="w-full rounded-lg border border-gray-300 px-4 py-3 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">

                </div>

                {{-- ATAS NAMA --}}
                <div>

                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Atas Nama
                    </label>

                    <input
                        type="text"
                        name="atas_nama"
                        placeholder="Contoh: PT Alfa Net"
                        required
                        class="w-full rounded-lg border border-gray-300 px-4 py-3 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">

                </div>

                {{-- STATUS --}}
                <div>

                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Status
                    </label>

                    <select
                        name="status"
                        required
                        class="w-full rounded-lg border border-gray-300 px-4 py-3 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">

                        <option value="aktif">
                            Aktif
                        </option>

                        <option value="nonaktif">
                            Nonaktif
                        </option>

                    </select>

                </div>

            </div>

            <div class="p-5 border-t border-gray-200 flex justify-end gap-3">

                <button
                    type="button"
                    onclick="closeTambahMetode()"
                    class="px-4 py-2.5 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-50">

                    Batal

                </button>

                <button
                    type="submit"
                    class="px-5 py-2.5 rounded-lg bg-blue-600 text-white hover:bg-blue-700">

                    Simpan

                </button>

            </div>

        </form>

    </div>

</div>


{{-- ===================================================== --}}
{{-- MODAL EDIT --}}
{{-- ===================================================== --}}

<div
    id="modalEditMetode"
    class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50 p-4">

    <div class="bg-white rounded-xl w-full max-w-lg shadow-xl">

        <div class="p-5 border-b border-gray-200 flex items-center justify-between">

            <div>
                <h2 class="text-lg font-semibold text-gray-900">
                    Edit Metode Pembayaran
                </h2>

                <p class="text-sm text-gray-500 mt-1">
                    Perbarui informasi pembayaran.
                </p>
            </div>

            <button
                type="button"
                onclick="closeEditMetode()"
                class="text-gray-400 hover:text-gray-600">

                <i data-lucide="x" class="w-5 h-5"></i>

            </button>

        </div>

        <form
            id="formEditMetode"
            method="POST">

            @csrf
            @method('PUT')

            <div class="p-5 space-y-4">

                {{-- NAMA --}}
                <div>

                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Nama Metode
                    </label>

                    <input
                        type="text"
                        id="edit_nama_metode"
                        name="nama_metode"
                        required
                        class="w-full rounded-lg border border-gray-300 px-4 py-3 text-sm focus:ring-2 focus:ring-blue-500">

                </div>

                {{-- JENIS --}}
                <div>

                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Jenis
                    </label>

                    <select
                        id="edit_jenis"
                        name="jenis"
                        required
                        class="w-full rounded-lg border border-gray-300 px-4 py-3 text-sm focus:ring-2 focus:ring-blue-500">

                        <option value="bank">
                            Bank
                        </option>

                        <option value="ewallet">
                            E-Wallet
                        </option>

                    </select>

                </div>

                {{-- NOMOR --}}
                <div>

                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Nomor Rekening / Nomor
                    </label>

                    <input
                        type="text"
                        id="edit_nomor_rekening"
                        name="nomor_rekening"
                        required
                        class="w-full rounded-lg border border-gray-300 px-4 py-3 text-sm focus:ring-2 focus:ring-blue-500">

                </div>

                {{-- ATAS NAMA --}}
                <div>

                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Atas Nama
                    </label>

                    <input
                        type="text"
                        id="edit_atas_nama"
                        name="atas_nama"
                        required
                        class="w-full rounded-lg border border-gray-300 px-4 py-3 text-sm focus:ring-2 focus:ring-blue-500">

                </div>

                {{-- STATUS --}}
                <div>

                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Status
                    </label>

                    <select
                        id="edit_status"
                        name="status"
                        required
                        class="w-full rounded-lg border border-gray-300 px-4 py-3 text-sm focus:ring-2 focus:ring-blue-500">

                        <option value="aktif">
                            Aktif
                        </option>

                        <option value="nonaktif">
                            Nonaktif
                        </option>

                    </select>

                </div>

            </div>

            <div class="p-5 border-t border-gray-200 flex justify-end gap-3">

                <button
                    type="button"
                    onclick="closeEditMetode()"
                    class="px-4 py-2.5 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-50">

                    Batal

                </button>

                <button
                    type="submit"
                    class="px-5 py-2.5 rounded-lg bg-blue-600 text-white hover:bg-blue-700">

                    Simpan Perubahan

                </button>

            </div>

        </form>

    </div>

</div>


<script>

    function openTambahMetode() {

        const modal = document.getElementById('modalTambahMetode');

        modal.classList.remove('hidden');

        modal.classList.add('flex');

    }

    function closeTambahMetode() {

        const modal = document.getElementById('modalTambahMetode');

        modal.classList.add('hidden');

        modal.classList.remove('flex');

    }

    function openEditMetode(
        id,
        nama,
        jenis,
        nomor,
        atasNama,
        status
    ) {

        document.getElementById('edit_nama_metode').value = nama;
        document.getElementById('edit_jenis').value = jenis;
        document.getElementById('edit_nomor_rekening').value = nomor;
        document.getElementById('edit_atas_nama').value = atasNama;
        document.getElementById('edit_status').value = status;

        document.getElementById('formEditMetode').action =
            '/pengaturan/pembayaran/' + id;

        const modal = document.getElementById('modalEditMetode');

        modal.classList.remove('hidden');

        modal.classList.add('flex');

    }

    function closeEditMetode() {

        const modal = document.getElementById('modalEditMetode');

        modal.classList.add('hidden');

        modal.classList.remove('flex');

    }

</script>

@endsection