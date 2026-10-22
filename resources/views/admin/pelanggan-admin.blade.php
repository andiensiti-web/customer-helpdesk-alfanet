
@extends('layouts.app')

@section('content')
<div class="space-y-6">

    {{-- HEADER --}}
    <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Data Pelanggan</h1>
            <p class="text-gray-500 mt-1">Kelola data pelanggan dan akun login Alfa Net</p>
        </div>

        <a href="/tambah-pelanggan"
           class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-3 rounded-lg flex items-center justify-center gap-2">
            <i data-lucide="plus" class="w-5 h-5"></i>
            Tambah Pelanggan
        </a>
    </div>

    {{-- PESAN --}}
    @if(session('success'))
        <div class="bg-green-100 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="bg-red-100 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
            {{ $errors->first() }}
        </div>
    @endif

    {{-- DETAIL IMPORT YANG GAGAL --}}
    @if(session('import_errors') && count(session('import_errors')) > 0)
        <div class="bg-yellow-50 border border-yellow-200 text-yellow-800 px-4 py-4 rounded-lg">
            <h3 class="font-semibold mb-2">Keterangan Data yang Gagal Diimpor</h3>
            <ul class="list-disc pl-5 space-y-1 text-sm">
                @foreach(session('import_errors') as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- PENCARIAN DAN FILTER --}}
    <div class="bg-white rounded-xl shadow-sm p-5">
        <form id="filterPelanggan" method="GET" action="{{ url('/pelanggan-admin') }}">
            <div class="flex flex-col lg:flex-row lg:items-center gap-3">
                <div class="relative flex-1">
                    <i data-lucide="search"
                       class="absolute left-3 top-3 w-5 h-5 text-gray-400"></i>

                    <input type="text"
                           name="search"
                           id="searchPelanggan"
                           value="{{ request('search') }}"
                           placeholder="Cari nama, username, atau ID pelanggan..."
                           class="w-full border rounded-lg pl-10 pr-4 py-3 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                </div>

                <select name="status" id="filterStatus"
                        class="border rounded-lg px-4 py-3 lg:w-48">
                    <option value="">Semua Status</option>
                    <option value="aktif" {{ request('status') === 'aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="nonaktif" {{ request('status') === 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                    <option value="isolir" {{ request('status') === 'isolir' ? 'selected' : '' }}>Isolir</option>
                </select>

                <select name="tipe" id="filterTipe"
                        class="border rounded-lg px-4 py-3 lg:w-44">
                    <option value="">Semua Tipe</option>
                    <option value="rumah" {{ request('tipe') === 'rumah' ? 'selected' : '' }}>Rumah</option>
                    <option value="bisnis" {{ request('tipe') === 'bisnis' ? 'selected' : '' }}>Bisnis</option>
                </select>
            </div>
        </form>

        <div class="flex flex-wrap gap-3 mt-4">
            <button type="button"
                    onclick="openImportModal()"
                    class="border border-gray-300 hover:bg-gray-50 text-gray-700 px-4 py-2.5 rounded-lg flex items-center gap-2">
                <i data-lucide="upload" class="w-5 h-5"></i>
                Import Data
            </button>

            <a href="/pelanggan-admin/export"
               class="border border-green-600 text-green-700 hover:bg-green-50 px-4 py-2.5 rounded-lg flex items-center gap-2">
                <i data-lucide="download" class="w-5 h-5"></i>
                Export Data
            </a>

            <button type="submit"
                    form="formHapusMassal"
                    id="btnHapusTerpilih"
                    disabled
                    class="border border-red-300 bg-red-50 text-red-700 hover:bg-red-100 disabled:opacity-50 disabled:cursor-not-allowed px-4 py-2.5 rounded-lg flex items-center gap-2">
                <i data-lucide="trash-2" class="w-5 h-5"></i>
                Hapus Terpilih (<span id="jumlahTerpilih">0</span>)
            </button>
        </div>
    </div>

    
    <form id="formHapusMassal"
        action="{{ url('/pelanggan-admin/hapus-massal') }}"
        method="POST">
        @csrf
        @method('DELETE')
    </form>

    {{-- TABEL PELANGGAN --}}
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="p-6 border-b flex flex-col sm:flex-row sm:justify-between sm:items-center gap-2">
            <div>
                <h2 class="text-xl font-bold text-gray-800">Daftar Pelanggan</h2>
                <p class="text-gray-500 mt-1">Seluruh pelanggan yang terdaftar di Alfa Net</p>
            </div>

            <span class="text-gray-500 whitespace-nowrap">
                {{ $pelanggans->total() }} pelanggan
            </span>
        </div>

        <div class="px-5 py-4">
            <div class="overflow-x-auto rounded-lg border border-gray-200">
                <table class="w-full text-sm min-w-[1250px]">
                    <thead class="border-b bg-gray-50">
                        <tr>
                            <th class="text-center py-4 px-4">
                                <input type="checkbox"
                                       id="pilihSemuaPelanggan"
                                       class="w-4 h-4 cursor-pointer"
                                       title="Pilih semua pelanggan di halaman ini">
                            </th>
                            <th class="text-left py-4 px-4">No</th>
                            <th class="text-left py-4 px-4">ID Pelanggan</th>
                            <th class="text-left py-4 px-4">Nama</th>
                            <th class="text-left py-4 px-4">Username</th>
                            <th class="text-left py-4 px-4">Alamat</th>
                            <th class="text-left py-4 px-4">Paket</th>
                            <th class="text-left py-4 px-4">Mulai Berlangganan</th>
                            <th class="text-left py-4 px-4">Tipe</th>
                            <th class="text-left py-4 px-4">Status</th>
                            <th class="text-center py-4 px-4">Aksi</th>
                        </tr>
                    </thead>

                    <tbody id="tabelPelanggan" class="divide-y">
                        @forelse($pelanggans as $pelanggan)
                            <tr class="hover:bg-gray-50"
                                data-search="{{ strtolower(($pelanggan->user->name ?? '') . ' ' . ($pelanggan->user->username ?? '') . ' ' . ($pelanggan->id_pelanggan ?? '')) }}"
                                data-status="{{ strtolower($pelanggan->status ?? '') }}"
                                data-tipe="{{ strtolower($pelanggan->tipe_pelanggan ?? '') }}">

                                {{-- CHECKBOX PELANGGAN --}}
                                <td class="py-5 px-4 text-center">
                                    <input type="checkbox"
                                           name="pelanggan_ids[]"
                                           value="{{ $pelanggan->id }}"
                                           form="formHapusMassal"
                                           class="checkbox-pelanggan w-4 h-4 cursor-pointer"
                                           aria-label="Pilih {{ $pelanggan->id_pelanggan }}">
                                </td>

                                {{-- NOMOR --}}
                                <td class="py-5 px-4 whitespace-nowrap">
                                    {{ $pelanggans->firstItem() + $loop->index }}
                                </td>

                                {{-- ID PELANGGAN --}}
                                <td class="py-5 px-4 font-semibold whitespace-nowrap">
                                    {{ $pelanggan->id_pelanggan }}
                                </td>

                                {{-- NAMA --}}
                                <td class="py-5 px-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center font-semibold">
                                            {{ strtoupper(substr($pelanggan->user->name ?? 'P', 0, 1)) }}
                                        </div>

                                        <div>
                                            <p class="font-medium text-gray-800">
                                                {{ $pelanggan->user->name ?? '-' }}
                                            </p>
                                            <p class="text-xs text-gray-500">
                                                {{ $pelanggan->user->email ?? '-' }}
                                            </p>
                                        </div>
                                    </div>
                                </td>

                                {{-- USERNAME --}}
                                <td class="py-5 px-4 whitespace-nowrap">
                                    {{ $pelanggan->user->username ?? '-' }}
                                </td>

                                {{-- ALAMAT --}}
                                <td class="py-5 px-4 max-w-[220px] whitespace-normal break-words">
                                    {{ $pelanggan->user->alamat ?? '-' }}
                                </td>

                                {{-- PAKET --}}
                                <td class="py-5 px-4 whitespace-nowrap">
                                    {{ $pelanggan->paket->nama_paket ?? '-' }}
                                </td>

                                {{-- TANGGAL MULAI --}}
                                <td class="py-5 px-4 whitespace-nowrap">
                                    {{ $pelanggan->tanggal_mulai
                                        ? \Carbon\Carbon::parse($pelanggan->tanggal_mulai)->format('d M Y')
                                        : '-' }}
                                </td>

                                {{-- TIPE --}}
                                <td class="py-5 px-4">
                                    @if($pelanggan->tipe_pelanggan === 'rumah')
                                        <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-xs whitespace-nowrap">Rumah</span>
                                    @elseif($pelanggan->tipe_pelanggan === 'bisnis')
                                        <span class="bg-purple-100 text-purple-700 px-3 py-1 rounded-full text-xs whitespace-nowrap">Bisnis</span>
                                    @else
                                        <span class="bg-gray-100 text-gray-700 px-3 py-1 rounded-full text-xs">-</span>
                                    @endif
                                </td>

                                {{-- STATUS --}}
                                <td class="py-5 px-4">
                                    @if($pelanggan->status === 'aktif')
                                        <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs whitespace-nowrap">Aktif</span>
                                    @elseif($pelanggan->status === 'nonaktif')
                                        <span class="bg-gray-100 text-gray-700 px-3 py-1 rounded-full text-xs whitespace-nowrap">Nonaktif</span>
                                    @elseif($pelanggan->status === 'isolir')
                                        <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-xs whitespace-nowrap">Isolir</span>
                                    @else
                                        <span class="bg-gray-100 text-gray-700 px-3 py-1 rounded-full text-xs">
                                            {{ ucfirst($pelanggan->status ?? '-') }}
                                        </span>
                                    @endif
                                </td>

                                {{-- AKSI --}}
                                <td class="py-5 px-4">
                                    <div class="flex justify-center items-center gap-3">
                                        <button type="button"
                                                onclick="openDetailModal({{ $pelanggan->id }})"
                                                class="text-blue-600 hover:text-blue-800"
                                                title="Detail">
                                            <i data-lucide="eye" class="w-5 h-5"></i>
                                        </button>

                                        <a href="/edit-pelanggan/{{ $pelanggan->id }}"
                                           class="text-yellow-600 hover:text-yellow-800"
                                           title="Edit">
                                            <i data-lucide="pencil" class="w-5 h-5"></i>
                                        </a>

                                        <form action="/hapus-pelanggan/{{ $pelanggan->id }}"
                                              method="POST"
                                              onsubmit="return confirm('Yakin ingin menghapus pelanggan ini?')">
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit"
                                                    class="text-red-600 hover:text-red-800"
                                                    title="Hapus">
                                                <i data-lucide="trash-2" class="w-5 h-5"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="11" class="py-12 text-center text-gray-500">
                                    Belum ada pelanggan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div id="pesanKosongFilter" class="hidden py-8 text-center text-gray-500">
                Tidak ada pelanggan yang sesuai dengan pencarian atau filter.
            </div>

            {{-- PAGINATION --}}
            @if($pelanggans->hasPages())
                <div class="flex flex-col sm:flex-row justify-between items-center gap-4 px-2 pt-5">
                    <div class="text-sm text-gray-500">
                        Menampilkan
                        <span class="font-medium text-gray-700">{{ $pelanggans->firstItem() }}</span>
                        -
                        <span class="font-medium text-gray-700">{{ $pelanggans->lastItem() }}</span>
                        dari
                        <span class="font-medium text-gray-700">{{ $pelanggans->total() }}</span>
                        pelanggan
                    </div>
                    <div>{{ $pelanggans->links() }}</div>
                </div>
            @endif
        </div>
    </div>
</div>

{{-- MODAL DETAIL --}}
<div id="detailModal"
     class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50 p-4">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-2xl max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between px-6 py-5 border-b">
            <div>
                <h2 class="text-xl font-bold text-gray-800">Detail Pelanggan</h2>
                <p class="text-sm text-gray-500 mt-1">Informasi lengkap pelanggan Alfa Net</p>
            </div>

            <button type="button" onclick="closeDetailModal()" class="text-gray-400 hover:text-gray-700">
                <i data-lucide="x" class="w-6 h-6"></i>
            </button>
        </div>

        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                @foreach([
                    'detailId' => 'ID Pelanggan',
                    'detailNama' => 'Nama',
                    'detailUsername' => 'Username',
                    'detailEmail' => 'Email',
                    'detailTelepon' => 'Telepon',
                    'detailPaket' => 'Paket',
                    'detailKecepatan' => 'Kecepatan',
                    'detailHarga' => 'Harga Bulanan',
                    'detailTipe' => 'Tipe Pelanggan',
                    'detailTanggalMulai' => 'Mulai Berlangganan',
                    'detailTanggalJatuhTempo' => 'Jatuh Tempo',
                    'detailStatus' => 'Status',
                    'detailAlamat' => 'Alamat',
                    'detailCatatan' => 'Catatan'
                ] as $id => $label)
                    <div class="{{ in_array($id, ['detailAlamat', 'detailCatatan']) ? 'md:col-span-2' : '' }}">
                        <p class="text-xs text-gray-500">{{ $label }}</p>
                        <p id="{{ $id }}" class="text-gray-800 font-medium mt-1 break-words">-</p>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="px-6 py-4 border-t flex justify-end">
            <button type="button" onclick="closeDetailModal()" class="px-5 py-2.5 border rounded-lg hover:bg-gray-50">
                Tutup
            </button>
        </div>
    </div>
</div>

{{-- MODAL IMPORT --}}
<div id="importModal"
     class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50 p-4">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg">
        <div class="flex items-center justify-between px-6 py-5 border-b">
            <div>
                <h2 class="text-xl font-bold text-gray-800">Import Data Pelanggan</h2>
                <p class="text-sm text-gray-500 mt-1">Gunakan file CSV sesuai format Alfa Net</p>
            </div>

            <button type="button" onclick="closeImportModal()" class="text-gray-400 hover:text-gray-700">
                <i data-lucide="x" class="w-6 h-6"></i>
            </button>
        </div>

        <form action="/pelanggan-admin/import"
              method="POST"
              enctype="multipart/form-data"
              class="p-6">
            @csrf

            <div class="bg-blue-50 text-blue-700 rounded-lg p-4 text-sm">
                <p class="font-semibold mb-2">Format CSV:</p>
                <p class="leading-6 mb-4">
                    ID Pelanggan; Nama; Username; Email; Telepon; Alamat;
                    Paket; Tipe Pelanggan; Tanggal Mulai;
                    Tanggal Jatuh Tempo; Status; Catatan
                </p>

                <a href="{{ route('pelanggan.template') }}"
                   class="inline-flex items-center gap-2 bg-white border border-blue-300 hover:bg-blue-100 text-blue-700 px-4 py-2 rounded-lg font-medium">
                    <i data-lucide="download" class="w-4 h-4"></i>
                    Download Template CSV
                </a>
            </div>

            <div class="mt-5">
                <label for="fileImport" class="text-sm font-medium text-gray-700">File CSV</label>

                <input id="fileImport"
                       type="file"
                       name="file"
                       accept=".csv,.txt"
                       required
                       class="w-full mt-2 border rounded-lg px-4 py-3">

                @error('file')
                    <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
                @enderror

                <p class="text-xs text-gray-500 mt-2">
                    File CSV maksimal 2 MB dan menggunakan pemisah titik koma (;).
                </p>
            </div>

            <div class="mt-4 bg-amber-50 border border-amber-200 text-amber-800 rounded-lg p-3 text-sm">
                <p class="font-semibold mb-1">Perhatian:</p>
                <ul class="list-disc pl-5 space-y-1">
                    <li>Kolom wajib yang kosong akan membuat baris dilewati.</li>
                    <li>Status wajib diisi: aktif, isolir, atau nonaktif.</li>
                    <li>Nama paket harus sesuai dengan paket yang terdaftar.</li>
                    <li>Tanggal mulai menggunakan format DD/MM/YYYY atau YYYY-MM-DD.</li>
                </ul>
            </div>

            <div class="flex justify-end gap-3 mt-6">
                <button type="button" onclick="closeImportModal()" class="px-5 py-2.5 border rounded-lg hover:bg-gray-50">
                    Batal
                </button>

                <button type="submit" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg">
                    Import Data
                </button>
            </div>
        </form>
    </div>
</div>

{{-- JAVASCRIPT --}}
<script>
    /* Data detail pelanggan */
    window.pelangganData = {};

    @foreach($pelanggans as $pelanggan)
        window.pelangganData[@json($pelanggan->id)] = {
            id: @json($pelanggan->id_pelanggan ?? '-'),
            nama: @json($pelanggan->user->name ?? '-'),
            username: @json($pelanggan->user->username ?? '-'),
            email: @json($pelanggan->user->email ?? '-'),
            telepon: @json($pelanggan->user->telepon ?? '-'),
            alamat: @json($pelanggan->user->alamat ?? '-'),
            paket: @json($pelanggan->paket->nama_paket ?? '-'),
            kecepatan: @json($pelanggan->paket->kecepatan ?? '-'),
            harga: @json($pelanggan->paket->harga_bulanan ?? 0),
            tipe: @json($pelanggan->tipe_pelanggan ?? '-'),
            tanggalMulai: @json($pelanggan->tanggal_mulai ? \Carbon\Carbon::parse($pelanggan->tanggal_mulai)->format('d F Y') : '-'),
            tanggalJatuhTempo: @json($pelanggan->tanggal_jatuh_tempo ? 'Tanggal ' . $pelanggan->tanggal_jatuh_tempo : '-'),
            status: @json($pelanggan->status ?? '-'),
            catatan: @json($pelanggan->catatan ?? '-')
        };
    @endforeach

    /* Pencarian dan filter */
    const formFilter = document.getElementById('filterPelanggan');
    const searchPelanggan = document.getElementById('searchPelanggan');
    const filterStatus = document.getElementById('filterStatus');
    const filterTipe = document.getElementById('filterTipe');

    if (filterStatus && formFilter) {
        filterStatus.addEventListener('change', function () {
            formFilter.submit();
        });
    }

    if (filterTipe && formFilter) {
        filterTipe.addEventListener('change', function () {
            formFilter.submit();
        });
    }

    let timerPencarian;

    if (searchPelanggan && formFilter) {
        searchPelanggan.addEventListener('input', function () {
            clearTimeout(timerPencarian);

            timerPencarian = setTimeout(function () {
                formFilter.submit();
            }, 500);
        });
    }

    /* Modal detail pelanggan */
    function openDetailModal(id) {
        const data = window.pelangganData[id];
        if (!data) return;

        const isi = {
            detailId: data.id,
            detailNama: data.nama,
            detailUsername: data.username,
            detailEmail: data.email,
            detailTelepon: data.telepon,
            detailAlamat: data.alamat,
            detailPaket: data.paket,
            detailKecepatan: data.kecepatan ? data.kecepatan + ' Mbps' : '-',
            detailHarga: 'Rp' + Number(data.harga || 0).toLocaleString('id-ID'),
            detailTipe: data.tipe === 'rumah' ? 'Rumah' :
                (data.tipe === 'bisnis' ? 'Bisnis' : '-'),
            detailTanggalMulai: data.tanggalMulai,
            detailTanggalJatuhTempo: data.tanggalJatuhTempo,
            detailStatus: data.status,
            detailCatatan: data.catatan
        };

        Object.entries(isi).forEach(function ([idElemen, nilai]) {
            const elemen = document.getElementById(idElemen);

            if (elemen) {
                elemen.textContent = nilai ?? '-';
            }
        });

        const modal = document.getElementById('detailModal');

        if (modal) {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }
    }

    function closeDetailModal() {
        const modal = document.getElementById('detailModal');

        if (modal) {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    }

    /* Modal import */
    function openImportModal() {
        const modal = document.getElementById('importModal');

        if (modal) {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }
    }

    function closeImportModal() {
        const modal = document.getElementById('importModal');

        if (modal) {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    }

    /* Checkbox pelanggan pada halaman aktif */
    const pilihSemua = document.getElementById('pilihSemuaPelanggan');
    const btnHapusTerpilih = document.getElementById('btnHapusTerpilih');
    const jumlahTerpilih = document.getElementById('jumlahTerpilih');
    const formHapusMassal = document.getElementById('formHapusMassal');

    function getCheckboxPelanggan() {
        return document.querySelectorAll(
            '#tabelPelanggan input.checkbox-pelanggan'
        );
    }

    function perbaruiPilihanPelanggan() {
        const checkbox = getCheckboxPelanggan();
        const totalTerpilih = document.querySelectorAll(
            '#tabelPelanggan input.checkbox-pelanggan:checked'
        ).length;

        if (jumlahTerpilih) {
            jumlahTerpilih.textContent = totalTerpilih;
        }

        if (btnHapusTerpilih) {
            btnHapusTerpilih.disabled = totalTerpilih === 0;
        }

        if (pilihSemua) {
            pilihSemua.checked =
                checkbox.length > 0 && totalTerpilih === checkbox.length;

            pilihSemua.indeterminate =
                totalTerpilih > 0 && totalTerpilih < checkbox.length;
        }
    }

    /* Pilih atau batalkan semua pelanggan di halaman ini */
    if (pilihSemua) {
        pilihSemua.addEventListener('change', function () {
            getCheckboxPelanggan().forEach(function (checkbox) {
                checkbox.checked = pilihSemua.checked;
            });

            perbaruiPilihanPelanggan();
        });
    }

    /* Perbarui jumlah saat checkbox baris berubah */
    document.addEventListener('change', function (event) {
        if (event.target.matches('#tabelPelanggan .checkbox-pelanggan')) {
            perbaruiPilihanPelanggan();
        }
    });

    /* Konfirmasi hapus massal */
    function konfirmasiHapusMassal() {
        const totalTerpilih = document.querySelectorAll(
            '#tabelPelanggan input.checkbox-pelanggan:checked'
        ).length;

        if (totalTerpilih === 0) {
            alert('Pilih minimal satu pelanggan terlebih dahulu.');
            return false;
        }

        return confirm(
            'Yakin ingin menghapus ' + totalTerpilih +
            ' pelanggan yang dipilih? Data yang terhapus mungkin tidak dapat dikembalikan.'
        );
    }

    /* Validasi sebelum mengirim form hapus massal */
    if (formHapusMassal) {
        formHapusMassal.addEventListener('submit', function (event) {
            const totalTerpilih = document.querySelectorAll(
                '#tabelPelanggan input.checkbox-pelanggan:checked'
            ).length;

            if (totalTerpilih === 0) {
                event.preventDefault();
                alert('Pilih minimal satu pelanggan terlebih dahulu.');
                return;
            }

            if (!confirm(
                'Yakin ingin menghapus ' + totalTerpilih +
                ' pelanggan yang dipilih? Data yang terhapus mungkin tidak dapat dikembalikan.'
            )) {
                event.preventDefault();
            }
        });
    }

    /* Tutup modal jika klik area luar */
    ['detailModal', 'importModal'].forEach(function (id) {
        const modal = document.getElementById(id);

        if (modal) {
            modal.addEventListener('click', function (event) {
                if (event.target === modal) {
                    if (id === 'detailModal') {
                        closeDetailModal();
                    } else {
                        closeImportModal();
                    }
                }
            });
        }
    });

    /* Tutup modal dengan tombol Escape */
    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape') {
            closeDetailModal();
            closeImportModal();
        }
    });

    /* Buka kembali modal import jika validasi file gagal */
    @if($errors->has('file'))
        openImportModal();
    @endif

    /* Atur jumlah pilihan saat halaman dimuat */
    perbaruiPilihanPelanggan();
</script>
@endsection
