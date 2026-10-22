@extends('layouts.app')

@section('content')
<div class="p-6">

    {{-- HEADER --}}
    <div class="mb-6">
        <a href="/pengaturan"
           class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-gray-700">
            ← Kembali ke Pengaturan
        </a>

        <div class="flex items-center justify-between mt-3">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">
                    Paket Internet
                </h1>

                <p class="text-sm text-gray-500 mt-1">
                    Kelola paket internet, harga, dan status paket.
                </p>
            </div>

            <button
                type="button"
                onclick="openTambahPaket()"
                class="px-4 py-2.5 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700">
                + Tambah Paket
            </button>
        </div>
    </div>

    {{-- NOTIFIKASI --}}
    @if(session('success'))
        <div class="mb-5 rounded-lg bg-green-50 border border-green-200 px-4 py-3 text-sm text-green-700">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-5 rounded-lg bg-red-50 border border-red-200 px-4 py-3 text-sm text-red-700">
            {{ session('error') }}
        </div>
    @endif

    @if($errors->any())
        <div class="mb-5 rounded-lg bg-red-50 border border-red-200 px-4 py-3 text-sm text-red-700">
            <ul class="list-disc ml-5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- TABEL PAKET --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">

        <div class="overflow-x-auto">

            <table class="w-full text-sm">

                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>

                        <th class="px-6 py-4 text-left font-semibold text-gray-600">
                            No
                        </th>

                        <th class="px-6 py-4 text-left font-semibold text-gray-600">
                            Kode Paket
                        </th>

                        <th class="px-6 py-4 text-left font-semibold text-gray-600">
                            Nama Paket
                        </th>

                        <th class="px-6 py-4 text-left font-semibold text-gray-600">
                            Kecepatan
                        </th>

                        <th class="px-6 py-4 text-left font-semibold text-gray-600">
                            Harga Bulanan
                        </th>

                        <th class="px-6 py-4 text-left font-semibold text-gray-600">
                            Deskripsi
                        </th>

                        <th class="px-6 py-4 text-left font-semibold text-gray-600">
                            Status
                        </th>

                        <th class="px-6 py-4 text-center font-semibold text-gray-600">
                            Aksi
                        </th>

                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-100">

                    @forelse($pakets as $paket)

                        <tr class="hover:bg-gray-50">

                            {{-- NO --}}
                            <td class="px-6 py-4 text-gray-600">
                                {{ $loop->iteration }}
                            </td>

                            {{-- KODE --}}
                            <td class="px-6 py-4 font-medium text-gray-800">
                                {{ $paket->kode_paket }}
                            </td>

                            {{-- NAMA --}}
                            <td class="px-6 py-4 font-medium text-gray-800">
                                {{ $paket->nama_paket }}
                            </td>

                            {{-- KECEPATAN --}}
                            <td class="px-6 py-4 text-gray-600">
                                {{ $paket->kecepatan }} Mbps
                            </td>

                            {{-- HARGA --}}
                            <td class="px-6 py-4 text-gray-600">
                                Rp{{ number_format($paket->harga_bulanan, 0, ',', '.') }}
                            </td>

                            {{-- DESKRIPSI --}}
                            <td class="px-6 py-4 text-gray-600 max-w-xs">
                                {{ $paket->deskripsi ?: '-' }}
                            </td>

                            {{-- STATUS --}}
                            <td class="px-6 py-4">

                                @if($paket->status_paket === 'aktif')

                                    <span class="inline-flex px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700">
                                        Aktif
                                    </span>

                                @else

                                    <span class="inline-flex px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-600">
                                        Nonaktif
                                    </span>

                                @endif

                            </td>

                            {{-- AKSI --}}
                            <td class="px-6 py-4">

                                <div class="flex items-center justify-center gap-3">

                                    {{-- EDIT --}}
                                    <button
                                        type="button"
                                        onclick='openEditPaket(@json($paket))'
                                        class="text-blue-600 hover:text-blue-800"
                                        title="Edit">

                                        <i
                                            data-lucide="pencil"
                                            class="w-5 h-5">
                                        </i>

                                    </button>

                                    {{-- HAPUS --}}
                                    <form
                                        action="/pengaturan/paket/{{ $paket->id }}"
                                        method="POST"
                                        onsubmit="return confirm('Yakin ingin menghapus paket ini?')">

                                        @csrf
                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="text-red-600 hover:text-red-800"
                                            title="Hapus">

                                            <i
                                                data-lucide="trash-2"
                                                class="w-5 h-5">
                                            </i>

                                        </button>

                                    </form>

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td
                                colspan="8"
                                class="px-6 py-12 text-center text-gray-500">

                                Belum ada paket internet.

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>


{{-- ========================================================= --}}
{{-- MODAL TAMBAH PAKET --}}
{{-- ========================================================= --}}

<div
    id="modalTambahPaket"
    class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50 p-4">

    <div class="bg-white rounded-xl w-full max-w-lg p-6">

        <div class="flex items-center justify-between mb-5">

            <h2 class="text-lg font-semibold text-gray-800">
                Tambah Paket Internet
            </h2>

            <button
                type="button"
                onclick="closeTambahPaket()"
                class="text-gray-400 hover:text-gray-600 text-xl">

                ×

            </button>

        </div>

        <form
            action="/pengaturan/paket"
            method="POST">

            @csrf

            <div class="space-y-4">

                {{-- KODE PAKET --}}
                <div>

                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Kode Paket
                    </label>

                    <input
                        type="text"
                        name="kode_paket"
                        value="{{ old('kode_paket') }}"
                        placeholder="Contoh: PKT-001"
                        required
                        class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">

                </div>

                {{-- NAMA PAKET --}}
                <div>

                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Nama Paket
                    </label>

                    <input
                        type="text"
                        name="nama_paket"
                        value="{{ old('nama_paket') }}"
                        placeholder="Contoh: Alfa Home 20"
                        required
                        class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">

                </div>

                {{-- KECEPATAN --}}
                <div>

                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Kecepatan
                    </label>

                    <div class="flex gap-2">

                        <input
                            type="number"
                            name="kecepatan"
                            value="{{ old('kecepatan') }}"
                            min="1"
                            required
                            class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">

                        <div class="flex items-center px-4 bg-gray-100 rounded-lg text-sm text-gray-600">
                            Mbps
                        </div>

                    </div>

                </div>

                {{-- HARGA --}}
                <div>

                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Harga Bulanan
                    </label>

                    <div class="relative">

                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 text-sm">
                            Rp
                        </span>

                        <input
                            type="number"
                            name="harga_bulanan"
                            value="{{ old('harga_bulanan') }}"
                            min="0"
                            required
                            class="w-full border border-gray-300 rounded-lg pl-11 pr-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">

                    </div>

                </div>

                {{-- DESKRIPSI --}}
                <div>

                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Deskripsi
                    </label>

                    <textarea
                        name="deskripsi"
                        rows="3"
                        placeholder="Deskripsi paket internet"
                        class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">{{ old('deskripsi') }}</textarea>

                </div>

                {{-- STATUS --}}
                <div>

                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Status
                    </label>

                    <select
                        name="status_paket"
                        required
                        class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">

                        <option value="aktif">
                            Aktif
                        </option>

                        <option value="nonaktif">
                            Nonaktif
                        </option>

                    </select>

                </div>

            </div>

            <div class="flex justify-end gap-3 mt-6">

                <button
                    type="button"
                    onclick="closeTambahPaket()"
                    class="px-4 py-2.5 border border-gray-300 rounded-lg text-sm text-gray-700 hover:bg-gray-50">

                    Batal

                </button>

                <button
                    type="submit"
                    class="px-4 py-2.5 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700">

                    Simpan

                </button>

            </div>

        </form>

    </div>

</div>


{{-- ========================================================= --}}
{{-- MODAL EDIT PAKET --}}
{{-- ========================================================= --}}

<div
    id="modalEditPaket"
    class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50 p-4">

    <div class="bg-white rounded-xl w-full max-w-lg p-6">

        <div class="flex items-center justify-between mb-5">

            <h2 class="text-lg font-semibold text-gray-800">
                Edit Paket Internet
            </h2>

            <button
                type="button"
                onclick="closeEditPaket()"
                class="text-gray-400 hover:text-gray-600 text-xl">

                ×

            </button>

        </div>

        <form
            id="formEditPaket"
            method="POST">

            @csrf
            @method('PUT')

            <div class="space-y-4">

                {{-- KODE PAKET --}}
                <div>

                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Kode Paket
                    </label>

                    <input
                        type="text"
                        id="editKodePaket"
                        name="kode_paket"
                        required
                        class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">

                </div>

                {{-- NAMA PAKET --}}
                <div>

                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Nama Paket
                    </label>

                    <input
                        type="text"
                        id="editNamaPaket"
                        name="nama_paket"
                        required
                        class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">

                </div>

                {{-- KECEPATAN --}}
                <div>

                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Kecepatan
                    </label>

                    <div class="flex gap-2">

                        <input
                            type="number"
                            id="editKecepatan"
                            name="kecepatan"
                            min="1"
                            required
                            class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">

                        <div class="flex items-center px-4 bg-gray-100 rounded-lg text-sm text-gray-600">
                            Mbps
                        </div>

                    </div>

                </div>

                {{-- HARGA --}}
                <div>

                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Harga Bulanan
                    </label>

                    <div class="relative">

                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 text-sm">
                            Rp
                        </span>

                        <input
                            type="number"
                            id="editHarga"
                            name="harga_bulanan"
                            min="0"
                            required
                            class="w-full border border-gray-300 rounded-lg pl-11 pr-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">

                    </div>

                </div>

                {{-- DESKRIPSI --}}
                <div>

                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Deskripsi
                    </label>

                    <textarea
                        id="editDeskripsi"
                        name="deskripsi"
                        rows="3"
                        class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"></textarea>

                </div>

                {{-- STATUS --}}
                <div>

                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Status
                    </label>

                    <select
                        id="editStatus"
                        name="status_paket"
                        required
                        class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">

                        <option value="aktif">
                            Aktif
                        </option>

                        <option value="nonaktif">
                            Nonaktif
                        </option>

                    </select>

                </div>

            </div>

            <div class="flex justify-end gap-3 mt-6">

                <button
                    type="button"
                    onclick="closeEditPaket()"
                    class="px-4 py-2.5 border border-gray-300 rounded-lg text-sm text-gray-700 hover:bg-gray-50">

                    Batal

                </button>

                <button
                    type="submit"
                    class="px-4 py-2.5 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700">

                    Simpan Perubahan

                </button>

            </div>

        </form>

    </div>

</div>


<script>

function openTambahPaket()
{
    const modal = document.getElementById('modalTambahPaket');

    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeTambahPaket()
{
    const modal = document.getElementById('modalTambahPaket');

    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

function openEditPaket(paket)
{
    document.getElementById('formEditPaket').action =
        '/pengaturan/paket/' + paket.id;

    document.getElementById('editKodePaket').value =
        paket.kode_paket ?? '';

    document.getElementById('editNamaPaket').value =
        paket.nama_paket ?? '';

    document.getElementById('editKecepatan').value =
        paket.kecepatan ?? '';

    document.getElementById('editHarga').value =
        paket.harga_bulanan ?? '';

    document.getElementById('editDeskripsi').value =
        paket.deskripsi ?? '';

    document.getElementById('editStatus').value =
        paket.status_paket ?? 'aktif';

    const modal = document.getElementById('modalEditPaket');

    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeEditPaket()
{
    const modal = document.getElementById('modalEditPaket');

    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

lucide.createIcons();

</script>

@endsection