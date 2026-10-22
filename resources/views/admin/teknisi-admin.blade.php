@extends('layouts.app')

@section('content')

<div class="space-y-6">

    {{-- HEADER --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Data Teknisi</h1>
            <p class="text-gray-500 mt-1">Kelola data teknisi dan akun login Alfa Net</p>
        </div>

        <a href="/tambah-teknisi"
           class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-3 rounded-lg flex items-center justify-center gap-2">
            <i data-lucide="user-plus" class="w-5 h-5"></i>
            Tambah Teknisi
        </a>
    </div>

    {{-- PESAN BERHASIL --}}
    @if(session('success'))
        <div class="bg-green-100 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    {{-- INFORMASI AKUN BARU --}}
    @if(session('akun_teknisi'))
        <div class="bg-blue-50 border border-blue-200 rounded-xl p-5">
            <div class="flex items-start gap-3">
                <div class="bg-blue-100 text-blue-600 p-2 rounded-lg">
                    <i data-lucide="key-round" class="w-5 h-5"></i>
                </div>

                <div class="flex-1">
                    <h3 class="font-semibold text-blue-800">
                        Teknisi Berhasil Ditambahkan
                    </h3>

                    <p class="text-sm text-blue-700 mt-1">
                        Akun login berhasil dibuat. Berikan informasi berikut kepada
                        {{ session('akun_teknisi.nama') }}.
                    </p>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 mt-4">

                        {{-- ID TEKNISI --}}
                        <div class="bg-white border rounded-lg p-3">
                            <p class="text-xs text-gray-500">ID Teknisi</p>
                            <p class="font-semibold text-gray-800 mt-1">
                                {{ session('akun_teknisi.id_teknisi') }}
                            </p>
                        </div>

                        {{-- ID AKUN --}}
                        <div class="bg-white border rounded-lg p-3">
                            <p class="text-xs text-gray-500">ID Akun</p>
                            <p class="font-semibold text-gray-800 mt-1">
                                {{ session('akun_teknisi.id') }}
                            </p>
                        </div>

                        {{-- USERNAME --}}
                        <div class="bg-white border rounded-lg p-3">
                            <p class="text-xs text-gray-500">Username</p>

                            <div class="flex items-center justify-between gap-2 mt-1">
                                <p id="usernameTeknisi" class="font-semibold text-gray-800">
                                    {{ session('akun_teknisi.username') }}
                                </p>

                                <button
                                    type="button"
                                    onclick="copyValue('usernameTeknisi', this)"
                                    class="text-blue-600 hover:text-blue-800"
                                    title="Salin username">
                                    <i data-lucide="copy" class="w-4 h-4"></i>
                                </button>
                            </div>
                        </div>

                        {{-- PASSWORD --}}
                        <div class="bg-white border rounded-lg p-3">
                            <p class="text-xs text-gray-500">Password</p>

                            <div class="flex items-center justify-between gap-2 mt-1">
                                <p id="passwordTeknisi" class="font-semibold text-gray-800">
                                    {{ session('akun_teknisi.password') }}
                                </p>

                                <button
                                    type="button"
                                    onclick="copyValue('passwordTeknisi', this)"
                                    class="text-blue-600 hover:text-blue-800"
                                    title="Salin password">
                                    <i data-lucide="copy" class="w-4 h-4"></i>
                                </button>
                            </div>
                        </div>

                    </div>

                    <button
                        type="button"
                        onclick="copyLoginTeknisi()"
                        class="mt-4 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm flex items-center gap-2">
                        <i data-lucide="copy" class="w-4 h-4"></i>
                        Salin Username & Password
                    </button>
                </div>
            </div>
        </div>
    @endif

    {{-- CARD DAFTAR TEKNISI --}}
    <div class="bg-white rounded-2xl shadow-sm p-7">

        {{-- HEADER CARD --}}
        <div class="flex flex-col md:flex-row justify-between items-start gap-4 mb-6">
            <div>
                <h2 class="text-2xl font-semibold text-gray-800">
                    Daftar Teknisi
                </h2>

                <p class="text-gray-500 mt-1">
                    Seluruh teknisi yang terdaftar di Alfa Net
                </p>
            </div>

            <div class="text-gray-500 pt-2 whitespace-nowrap">
                {{ $teknisis->total() }} teknisi
            </div>
        </div>

        {{-- SEARCH + FILTER --}}
        <div class="mb-6">
            <div class="flex flex-col lg:flex-row gap-3">

                {{-- SEARCH --}}
                <div class="relative flex-1">
                    <i data-lucide="search"
                       class="absolute left-3 top-3.5 w-5 h-5 text-gray-400"></i>

                    <input
                        type="text"
                        id="searchTeknisi"
                        value="{{ request('search') }}"
                        placeholder="Cari nama, ID, username, email..."
                        class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                {{-- STATUS --}}
                <select
                    id="filterStatus"
                    class="w-full lg:w-52 border border-gray-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500">

                    <option value="semua">
                        Semua Status
                    </option>

                    <option value="aktif"
                        {{ request('status') === 'aktif' ? 'selected' : '' }}>
                        Aktif
                    </option>

                    <option value="isolir"
                        {{ request('status') === 'isolir' ? 'selected' : '' }}>
                        Isolir
                    </option>

                    <option value="nonaktif"
                        {{ request('status') === 'nonaktif' ? 'selected' : '' }}>
                        Nonaktif
                    </option>
                </select>

            </div>
        </div>

        {{-- TABEL --}}
        <div class="overflow-x-auto rounded-lg border border-gray-200">

            <table class="w-full text-sm min-w-[1200px]">

                <thead>
                    <tr class="border-b border-gray-300 bg-gray-50 text-gray-600">

                        <th class="text-left py-4 px-4 font-semibold whitespace-nowrap">
                            No
                        </th>

                        <th class="text-left py-4 px-4 font-semibold whitespace-nowrap">
                            ID Teknisi
                        </th>

                        <th class="text-left py-4 px-5 font-semibold whitespace-nowrap">
                            Nama Teknisi
                        </th>

                        <th class="text-left py-4 px-5 font-semibold whitespace-nowrap">
                            Username
                        </th>

                        <th class="text-left py-4 px-5 font-semibold whitespace-nowrap">
                            No. Telepon
                        </th>

                        <th class="text-left py-4 px-5 font-semibold whitespace-nowrap">
                            Keahlian
                        </th>

                        <th class="text-left py-4 px-5 font-semibold">
                            Alamat
                        </th>

                        <th class="text-left py-4 px-5 font-semibold whitespace-nowrap">
                            Status
                        </th>

                        <th class="text-left py-4 px-5 font-semibold whitespace-nowrap">
                            Aksi
                        </th>

                    </tr>
                </thead>

                <tbody>

                    @forelse($teknisis as $index => $teknisi)

                        <tr class="border-b border-gray-200 hover:bg-gray-50 transition">

                            {{-- NO --}}
                            <td class="py-5 px-4 whitespace-nowrap">
                                {{ $teknisis->firstItem() + $index }}
                            </td>

                            {{-- ID TEKNISI --}}
                            <td class="py-5 px-4 whitespace-nowrap">
                                <span class="font-semibold text-gray-800">
                                    {{ $teknisi->id_teknisi }}
                                </span>
                            </td>

                            {{-- NAMA --}}
                            <td class="py-5 px-5">
                                <div class="flex items-center gap-4">

                                    <div class="w-11 h-11 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center font-semibold flex-shrink-0">
                                        {{ strtoupper(substr($teknisi->nama, 0, 1)) }}
                                    </div>

                                    <div class="min-w-[180px]">
                                        <div class="font-semibold text-gray-800">
                                            {{ $teknisi->nama }}
                                        </div>

                                        <div class="text-sm text-gray-500 mt-1">
                                            {{ $teknisi->email ?? '-' }}
                                        </div>
                                    </div>

                                </div>
                            </td>

                            {{-- USERNAME --}}
                            <td class="py-5 px-5 whitespace-nowrap">
                                {{ $teknisi->user->username ?? '-' }}
                            </td>

                            {{-- TELEPON --}}
                            <td class="py-5 px-5 whitespace-nowrap">
                                {{ $teknisi->telepon ?? '-' }}
                            </td>

                            {{-- KEAHLIAN --}}
                            <td class="py-5 px-5 whitespace-nowrap">
                                {{ $teknisi->keahlian ?? '-' }}
                            </td>

                            {{-- ALAMAT --}}
                            <td class="py-5 px-5 min-w-[280px]">
                                <div class="whitespace-normal break-words">
                                    {{ $teknisi->alamat ?? '-' }}
                                </div>
                            </td>

                            {{-- STATUS --}}
                            <td class="py-5 px-5">

                                @if($teknisi->status === 'aktif')

                                    <span class="inline-block bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs whitespace-nowrap">
                                        Aktif
                                    </span>

                                @elseif($teknisi->status === 'isolir')

                                    <span class="inline-block bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full text-xs whitespace-nowrap">
                                        Isolir
                                    </span>

                                @elseif($teknisi->status === 'nonaktif')

                                    <span class="inline-block bg-gray-100 text-gray-700 px-3 py-1 rounded-full text-xs whitespace-nowrap">
                                        Nonaktif
                                    </span>

                                @else

                                    <span class="inline-block bg-gray-100 text-gray-700 px-3 py-1 rounded-full text-xs whitespace-nowrap">
                                        -
                                    </span>

                                @endif

                            </td>

                            {{-- AKSI --}}
                            <td class="py-5 px-5">

                                <div class="flex items-center gap-4">

                                    {{-- DETAIL --}}
                                    <button
                                        type="button"
                                        class="btn-detail-teknisi text-blue-600 hover:text-blue-800"
                                        title="Detail Teknisi"

                                        data-id="{{ $teknisi->id_teknisi }}"
                                        data-nama="{{ $teknisi->nama }}"
                                        data-username="{{ $teknisi->user->username ?? '-' }}"
                                        data-email="{{ $teknisi->email ?? '-' }}"
                                        data-telepon="{{ $teknisi->telepon ?? '-' }}"
                                        data-keahlian="{{ $teknisi->keahlian ?? '-' }}"
                                        data-alamat="{{ $teknisi->alamat ?? '-' }}"
                                        data-status="{{ $teknisi->status ?? '-' }}"
                                    >
                                        <i data-lucide="eye" class="w-5 h-5"></i>
                                    </button>

                                    {{-- EDIT --}}
                                    <a
                                        href="/edit-teknisi/{{ $teknisi->id }}"
                                        class="text-green-600 hover:text-green-800"
                                        title="Edit Teknisi">
                                        <i data-lucide="square-pen" class="w-5 h-5"></i>
                                    </a>

                                    {{-- HAPUS --}}
                                    <form
                                        action="/hapus-teknisi/{{ $teknisi->id }}"
                                        method="POST"
                                        onsubmit="return confirm('Yakin ingin menghapus teknisi ini?')">

                                        @csrf
                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="text-red-500 hover:text-red-700"
                                            title="Hapus Teknisi">
                                            <i data-lucide="trash-2" class="w-5 h-5"></i>
                                        </button>

                                    </form>

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="9" class="text-center py-12 text-gray-500">

                                <div class="flex flex-col items-center justify-center">

                                    <i data-lucide="users"
                                       class="w-10 h-10 text-gray-300 mb-3"></i>

                                    <p>
                                        Tidak ada data teknisi.
                                    </p>

                                    @if(request('search') || request('status'))

                                        <p class="text-sm mt-1">
                                            Coba ubah pencarian atau filter.
                                        </p>

                                    @endif

                                </div>

                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

        {{-- PAGINATION --}}
        @if($teknisis->hasPages())

            <div class="flex flex-col md:flex-row justify-between items-center gap-4 mt-6">

                <p class="text-sm text-gray-500">

                    Menampilkan

                    <span class="font-medium text-gray-700">
                        {{ $teknisis->firstItem() }}
                    </span>

                    -

                    <span class="font-medium text-gray-700">
                        {{ $teknisis->lastItem() }}
                    </span>

                    dari

                    <span class="font-medium text-gray-700">
                        {{ $teknisis->total() }}
                    </span>

                    teknisi

                </p>

                <div>
                    {{ $teknisis->links() }}
                </div>

            </div>

        @endif

    </div>

</div>


{{-- ===================================================== --}}
{{-- MODAL DETAIL TEKNISI --}}
{{-- ===================================================== --}}

<div
    id="detailTeknisiModal"
    class="fixed inset-0 z-50 hidden items-center justify-center bg-black/40 px-4">

    <div
        class="bg-white w-full max-w-2xl rounded-2xl shadow-xl overflow-hidden">

        {{-- HEADER MODAL --}}
        <div class="flex items-center justify-between px-6 py-5 border-b">

            <div>
                <h2 class="text-xl font-semibold text-gray-800">
                    Detail Teknisi
                </h2>

                <p class="text-sm text-gray-500 mt-1">
                    Informasi lengkap teknisi Alfa Net
                </p>
            </div>

            <button
                type="button"
                onclick="closeDetailTeknisi()"
                class="text-gray-400 hover:text-gray-600">

                <i data-lucide="x" class="w-5 h-5"></i>

            </button>

        </div>

        {{-- ISI MODAL --}}
        <div class="p-6">

            {{-- PROFIL --}}
            <div class="flex items-center gap-4 mb-6">

                <div
                    id="detailTeknisiInitial"
                    class="w-14 h-14 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center text-xl font-bold">
                    -
                </div>

                <div>
                    <h3
                        id="detailTeknisiNama"
                        class="text-lg font-semibold text-gray-800">
                        -
                    </h3>

                    <p
                        id="detailTeknisiId"
                        class="text-sm text-gray-500">
                        -
                    </p>
                </div>

            </div>

            {{-- INFORMASI --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                {{-- USERNAME --}}
                <div class="bg-gray-50 rounded-lg p-4">
                    <p class="text-xs text-gray-500">
                        Username
                    </p>

                    <p
                        id="detailTeknisiUsername"
                        class="font-medium text-gray-800 mt-1">
                        -
                    </p>
                </div>

                {{-- EMAIL --}}
                <div class="bg-gray-50 rounded-lg p-4">
                    <p class="text-xs text-gray-500">
                        Email
                    </p>

                    <p
                        id="detailTeknisiEmail"
                        class="font-medium text-gray-800 mt-1 break-words">
                        -
                    </p>
                </div>

                {{-- TELEPON --}}
                <div class="bg-gray-50 rounded-lg p-4">
                    <p class="text-xs text-gray-500">
                        Nomor Telepon
                    </p>

                    <p
                        id="detailTeknisiTelepon"
                        class="font-medium text-gray-800 mt-1">
                        -
                    </p>
                </div>

                {{-- KEAHLIAN --}}
                <div class="bg-gray-50 rounded-lg p-4">
                    <p class="text-xs text-gray-500">
                        Keahlian
                    </p>

                    <p
                        id="detailTeknisiKeahlian"
                        class="font-medium text-gray-800 mt-1">
                        -
                    </p>
                </div>

                {{-- ALAMAT --}}
                <div class="bg-gray-50 rounded-lg p-4 sm:col-span-2">
                    <p class="text-xs text-gray-500">
                        Alamat
                    </p>

                    <p
                        id="detailTeknisiAlamat"
                        class="font-medium text-gray-800 mt-1">
                        -
                    </p>
                </div>

                {{-- STATUS --}}
                <div class="bg-gray-50 rounded-lg p-4 sm:col-span-2">

                    <p class="text-xs text-gray-500">
                        Status
                    </p>

                    <div
                        id="detailTeknisiStatus"
                        class="mt-1">
                        -
                    </div>

                </div>

            </div>

        </div>

        {{-- FOOTER --}}
        <div class="flex justify-end px-6 py-4 border-t bg-gray-50">

            <button
                type="button"
                onclick="closeDetailTeknisi()"
                class="px-5 py-2.5 bg-gray-800 hover:bg-gray-900 text-white rounded-lg text-sm">
                Tutup
            </button>

        </div>

    </div>

</div>


<script>

    // =====================================================
    // COPY
    // =====================================================

    function copyToClipboard(text) {

        const textarea = document.createElement('textarea');

        textarea.value = text;
        textarea.style.position = 'fixed';
        textarea.style.left = '-9999px';
        textarea.style.top = '0';

        document.body.appendChild(textarea);

        textarea.focus();
        textarea.select();

        try {

            document.execCommand('copy');

            document.body.removeChild(textarea);

            return true;

        } catch (error) {

            document.body.removeChild(textarea);

            return false;
        }
    }


    function copyValue(elementId, button) {

        const element = document.getElementById(elementId);

        if (!element) {
            return;
        }

        const value = element.innerText.trim();

        if (copyToClipboard(value)) {

            const original = button.innerHTML;

            button.innerHTML = '✓';

            setTimeout(() => {

                button.innerHTML = original;

                if (typeof lucide !== 'undefined') {
                    lucide.createIcons();
                }

            }, 1500);

        } else {

            alert('Gagal menyalin. Silakan salin secara manual.');

        }
    }


    function copyLoginTeknisi() {

        const usernameElement =
            document.getElementById('usernameTeknisi');

        const passwordElement =
            document.getElementById('passwordTeknisi');

        if (!usernameElement || !passwordElement) {
            return;
        }

        const username =
            usernameElement.innerText.trim();

        const password =
            passwordElement.innerText.trim();

        const text =
            `Username: ${username}\nPassword: ${password}`;

        if (copyToClipboard(text)) {

            alert('Username dan password berhasil disalin.');

        } else {

            alert('Gagal menyalin. Silakan salin secara manual.');

        }
    }


    // =====================================================
    // DETAIL TEKNISI
    // =====================================================

    document.querySelectorAll('.btn-detail-teknisi').forEach(function(button) {

        button.addEventListener('click', function() {

            const nama =
                this.dataset.nama || '-';

            document.getElementById('detailTeknisiInitial').textContent =
                nama.charAt(0).toUpperCase();

            document.getElementById('detailTeknisiNama').textContent =
                nama;

            document.getElementById('detailTeknisiId').textContent =
                this.dataset.id || '-';

            document.getElementById('detailTeknisiUsername').textContent =
                this.dataset.username || '-';

            document.getElementById('detailTeknisiEmail').textContent =
                this.dataset.email || '-';

            document.getElementById('detailTeknisiTelepon').textContent =
                this.dataset.telepon || '-';

            document.getElementById('detailTeknisiKeahlian').textContent =
                this.dataset.keahlian || '-';

            document.getElementById('detailTeknisiAlamat').textContent =
                this.dataset.alamat || '-';


            // STATUS
            let statusHtml = '';

            if (this.dataset.status === 'aktif') {

                statusHtml = `
                    <span class="inline-block bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs">
                        Aktif
                    </span>
                `;

            } else if (this.dataset.status === 'isolir') {

                statusHtml = `
                    <span class="inline-block bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full text-xs">
                        Isolir
                    </span>
                `;

            } else if (this.dataset.status === 'nonaktif') {

                statusHtml = `
                    <span class="inline-block bg-gray-100 text-gray-700 px-3 py-1 rounded-full text-xs">
                        Nonaktif
                    </span>
                `;

            } else {

                statusHtml = `
                    <span class="inline-block bg-gray-100 text-gray-700 px-3 py-1 rounded-full text-xs">
                        -
                    </span>
                `;
            }

            document.getElementById('detailTeknisiStatus').innerHTML =
                statusHtml;


            // TAMPILKAN MODAL
            const modal =
                document.getElementById('detailTeknisiModal');

            modal.classList.remove('hidden');
            modal.classList.add('flex');

            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }

        });

    });

    function closeDetailTeknisi() {
        const modal =
            document.getElementById('detailTeknisiModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }
    // Tutup jika klik area luar modal
    document.getElementById('detailTeknisiModal')
        .addEventListener('click', function(event) {
            if (event.target === this) {
                closeDetailTeknisi();
            }
        });

    const searchInput = document.getElementById('searchTeknisi');
    const filterStatus = document.getElementById('filterStatus');
    let searchTimer;

    function jalankanFilter() {
        const search = searchInput.value.trim();
        const status = filterStatus.value;
        const params = new URLSearchParams();
        if (search !== '') {params.set('search', search);}
        if (status !== 'semua') {params.set('status', status);}
        const query = params.toString(); window.location.href = '/teknisi-admin' + (query ? '?' + query : '');
    }

    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimer);
        searchTimer = setTimeout(function() {
            jalankanFilter();
        }, 500);
    });

    filterStatus.addEventListener('change', function() {

        jalankanFilter();

    });

    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }

</script>

@endsection