@extends('layouts.app')

@section('content')
<div class="w-full space-y-4 sm:space-y-6">
    {{-- HEADER --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-800">Manajemen Akun</h1>
            <p class="text-sm sm:text-base text-gray-500 mt-1">Kelola akun Admin dan Teknisi Alfa Net</p>
        </div>
        <a href="/tambah-akun" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2.5 rounded-lg flex items-center justify-center gap-2 text-sm">
            <i data-lucide="plus" class="w-5 h-5"></i>
            Tambah Akun
        </a>
    </div>

    {{-- PESAN BERHASIL --}}
    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg text-sm">
            {{ session('success') }}
        </div>
    @endif

    {{-- AKUN BARU --}}
    @if(session('akun_baru'))
        <div class="bg-blue-50 border border-blue-200 rounded-xl p-5">
            <div class="flex items-start gap-3">
                <div class="bg-blue-100 text-blue-600 p-2 rounded-lg">
                    <i data-lucide="key-round" class="w-5 h-5"></i>
                </div>
                <div class="flex-1">
                    <h3 class="font-semibold text-blue-800">Akun Berhasil Dibuat</h3>
                    <p class="text-sm text-blue-700 mt-1">Berikan informasi login berikut kepada {{ session('akun_baru.nama') }}.</p>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 mt-4">
                        <div class="bg-white border rounded-lg p-3">
                            <p class="text-xs text-gray-500">ID Akun</p>
                            <p class="font-semibold text-gray-800 mt-1">{{ session('akun_baru.id') }}</p>
                        </div>

                        <div class="bg-white border rounded-lg p-3">
                            <p class="text-xs text-gray-500">Username</p>
                            <p class="font-semibold text-gray-800 mt-1">{{ session('akun_baru.username') }}</p>
                        </div>

                        <div class="bg-white border rounded-lg p-3">
                            <p class="text-xs text-gray-500">Password</p>
                            <p class="font-semibold text-gray-800 mt-1">{{ session('akun_baru.password') }}</p>
                        </div>
                    </div>

                    <p class="text-xs text-gray-500 mt-3">
                        Role:
                        <span class="font-medium">{{ ucfirst(session('akun_baru.role')) }}</span>
                    </p>
                </div>
            </div>
        </div>
    @endif

    {{-- PASSWORD HASIL RESET --}}
    @if(session('password_reset'))
        <div class="bg-orange-50 border border-orange-200 rounded-xl p-5">
            <div class="flex items-start gap-3">
                <div class="bg-orange-100 text-orange-600 p-2 rounded-lg">
                    <i data-lucide="key-round" class="w-5 h-5"></i>
                </div>
                <div class="flex-1">
                    <h3 class="font-semibold text-orange-800">Password Berhasil Direset</h3>
                    <p class="text-sm text-orange-700 mt-1">Berikan informasi login berikut kepada {{ session('password_reset.nama') }}.</p>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mt-4">
                        <div class="bg-white border rounded-lg p-3">
                            <p class="text-xs text-gray-500">Username</p>
                            <p class="font-semibold text-gray-800 mt-1">{{ session('password_reset.username') }}</p>
                        </div>

                        <div class="bg-white border rounded-lg p-3">
                            <p class="text-xs text-gray-500">Password Baru</p>
                            <p class="font-semibold text-gray-800 mt-1">{{ session('password_reset.password') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- FILTER --}}
    <div class="bg-white rounded-xl shadow-sm p-4 sm:p-5">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
            <div class="relative">
                <i data-lucide="search" class="absolute left-3 top-2.5 w-5 h-5 text-gray-400"></i>
                <input type="text" id="searchAkun" value="{{ request('search') }}" placeholder="Cari nama, username, atau email..." class="w-full border rounded-lg pl-10 pr-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <select id="filterRole" class="w-full border rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">Semua Role</option>
                <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                <option value="teknisi" {{ request('role') == 'teknisi' ? 'selected' : '' }}>Teknisi</option>
            </select>

            <select id="filterStatus" class="w-full border rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">Semua Status</option>
                <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                <option value="nonaktif" {{ request('status') == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
            </select>
        </div>
    </div>

    {{-- STATISTIK --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-blue-50 rounded-xl p-4 sm:p-5 flex items-center gap-4">
            <div class="bg-blue-100 p-3 rounded-lg">
                <i data-lucide="users" class="w-5 h-5 text-blue-600"></i>
            </div>
            <div>
                <p class="text-gray-500 text-sm">Total Akun</p>
                <h2 class="text-2xl font-bold">{{ $totalAkun }}</h2>
                <p class="text-xs text-gray-500">Admin dan teknisi</p>
            </div>
        </div>

        <div class="bg-green-50 rounded-xl p-4 sm:p-5 flex items-center gap-4">
            <div class="bg-green-100 p-3 rounded-lg">
                <i data-lucide="user-check" class="w-5 h-5 text-green-600"></i>
            </div>
            <div>
                <p class="text-gray-500 text-sm">Akun Aktif</p>
                <h2 class="text-2xl font-bold">{{ $akunAktif }}</h2>
                <p class="text-xs text-gray-500">Akun yang aktif</p>
            </div>
        </div>

        <div class="bg-blue-50 rounded-xl p-4 sm:p-5 flex items-center gap-4">
            <div class="bg-blue-100 p-3 rounded-lg">
                <i data-lucide="shield-check" class="w-5 h-5 text-blue-600"></i>
            </div>
            <div>
                <p class="text-gray-500 text-sm">Admin</p>
                <h2 class="text-2xl font-bold">{{ $totalAdmin }}</h2>
                <p class="text-xs text-gray-500">Akun admin</p>
            </div>
        </div>

        <div class="bg-purple-50 rounded-xl p-4 sm:p-5 flex items-center gap-4">
            <div class="bg-purple-100 p-3 rounded-lg">
                <i data-lucide="wrench" class="w-5 h-5 text-purple-600"></i>
            </div>
            <div>
                <p class="text-gray-500 text-sm">Teknisi</p>
                <h2 class="text-2xl font-bold">{{ $totalTeknisi }}</h2>
                <p class="text-xs text-gray-500">Akun teknisi</p>
            </div>
        </div>
    </div>

    {{-- TABEL AKUN --}}
    <div class="bg-white rounded-xl shadow-sm p-4 sm:p-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-5">
            <div>
                <h2 class="text-lg sm:text-xl font-semibold text-gray-800">Daftar Akun</h2>
                <p class="text-sm text-gray-500 mt-1">Daftar akun Admin dan Teknisi Alfa Net</p>
            </div>
            <span class="text-sm text-gray-500">{{ $akuns->total() }} akun</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full min-w-[1000px] text-sm">
                <thead>
                    <tr class="border-b text-gray-600">
                        <th class="text-left py-4 px-2">No</th>
                        <th class="text-left py-4 px-2">ID Akun</th>
                        <th class="text-left py-4 px-2">Nama</th>
                        <th class="text-left py-4 px-2">Username</th>
                        <th class="text-left py-4 px-2">Email</th>
                        <th class="text-left py-4 px-2">Role</th>
                        <th class="text-left py-4 px-2">Status</th>
                        <th class="text-left py-4 px-2">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($akuns as $akun)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="py-4 px-2">{{ $akuns->firstItem() + $loop->index }}</td>

                            <td class="py-4 px-2 font-medium">
                                USR-{{ str_pad($akun->id, 4, '0', STR_PAD_LEFT) }}
                            </td>

                            <td class="py-4 px-2">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center font-semibold">
                                        {{ strtoupper(substr($akun->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-800">{{ $akun->name }}</p>
                                        <p class="text-xs text-gray-500">{{ $akun->telepon ?? '-' }}</p>
                                    </div>
                                </div>
                            </td>

                            <td class="py-4 px-2">{{ $akun->username }}</td>
                            <td class="py-4 px-2">{{ $akun->email }}</td>

                            <td class="py-4 px-2">
                                @if($akun->role == 'admin')
                                    <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-xs">Admin</span>
                                @elseif($akun->role == 'teknisi')
                                    <span class="bg-purple-100 text-purple-700 px-3 py-1 rounded-full text-xs">Teknisi</span>
                                @endif
                            </td>

                            <td class="py-4 px-2">
                                @if($akun->status == 'aktif')
                                    <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs">Aktif</span>
                                @else
                                    <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-xs">Nonaktif</span>
                                @endif
                            </td>

                            <td class="py-4 px-2">
                                <div class="flex items-center gap-3">
                                    <a href="/edit-akun/{{ $akun->id }}" class="text-blue-600 hover:text-blue-800" title="Edit">
                                        <i data-lucide="pencil" class="w-5 h-5"></i>
                                    </a>

                                    <form action="/reset-password-akun/{{ $akun->id }}" method="POST" onsubmit="return confirm('Reset password akun {{ $akun->name }} menjadi USR-{{ str_pad($akun->id, 4, '0', STR_PAD_LEFT) }}?')">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="text-orange-500 hover:text-orange-700" title="Reset Password">
                                            <i data-lucide="key-round" class="w-5 h-5"></i>
                                        </button>
                                    </form>

                                    <form action="/hapus-akun/{{ $akun->id }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800" title="Hapus">
                                            <i data-lucide="trash-2" class="w-5 h-5"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-10 text-gray-500">
                                Belum ada akun Admin atau Teknisi.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($akuns->hasPages())
            <div class="mt-5">
                {{ $akuns->links() }}
            </div>
        @endif
    </div>
</div>

<script>
    const searchInput = document.getElementById('searchAkun');
    const filterRole = document.getElementById('filterRole');
    const filterStatus = document.getElementById('filterStatus');
    let searchTimer;

    function jalankanFilter() {
        const search = searchInput.value.trim();
        const role = filterRole.value;
        const status = filterStatus.value;
        const params = new URLSearchParams();

        if (search !== '') {
            params.set('search', search);
        }

        if (role !== '') {
            params.set('role', role);
        }

        if (status !== '') {
            params.set('status', status);
        }

        const query = params.toString();

        window.location.href = '/akun-admin' + (query ? '?' + query : '');
    }

    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimer);
        searchTimer = setTimeout(function() {
            jalankanFilter();
        }, 500);
    });

    filterRole.addEventListener('change', function() {
        jalankanFilter();
    });

    filterStatus.addEventListener('change', function() {
        jalankanFilter();
    });

    lucide.createIcons();
</script>
@endsection