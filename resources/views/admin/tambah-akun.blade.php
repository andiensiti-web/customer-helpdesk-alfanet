@extends('layouts.app')

@section('content')
<div class="w-full max-w-4xl mx-auto space-y-5">
    {{-- HEADER --}}
    <div>
        <h1 class="text-2xl sm:text-3xl font-bold text-gray-800">Tambah Akun</h1>
        <p class="text-sm sm:text-base text-gray-500 mt-1">Buat akun baru untuk Admin atau Teknisi</p>
    </div>

    {{-- FORM --}}
    <div class="bg-white rounded-xl shadow-sm p-5 sm:p-7">
        <form action="/tambah-akun" method="POST" class="space-y-5">
            @csrf

            {{-- ID AKUN --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">ID Akun</label>
                <input type="text" value="{{ $idAkun }}" readonly class="w-full border rounded-lg px-4 py-2.5 bg-gray-100 text-gray-600">
                <p class="text-xs text-gray-500 mt-1">ID ini juga akan menjadi password awal akun.</p>
            </div>

            {{-- NAMA --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap</label>
                <input type="text" name="name" value="{{ old('name') }}" required class="w-full border rounded-lg px-4 py-2.5" placeholder="Masukkan nama lengkap">
                @error('name')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- USERNAME --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Username</label>
                <input type="text" name="username" value="{{ old('username') }}" required class="w-full border rounded-lg px-4 py-2.5" placeholder="Masukkan username">
                @error('username')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- PASSWORD --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Password Awal</label>
                <div class="flex gap-2">
                    <input type="text" value="{{ $idAkun }}" readonly class="flex-1 border rounded-lg px-4 py-2.5 bg-gray-100 text-gray-600" id="passwordAwal">
                    <button type="button" onclick="copyText('passwordAwal', this)" class="bg-gray-100 hover:bg-gray-200 border rounded-lg px-4 py-2.5 text-sm">
                        <i data-lucide="copy" class="w-4 h-4 inline"></i>
                        Salin
                    </button>
                </div>
                <p class="text-xs text-gray-500 mt-1">Password awal otomatis menggunakan ID Akun.</p>
            </div>

            {{-- ROLE --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Role</label>
                <select name="role" required class="w-full border rounded-lg px-4 py-2.5">
                    <option value="">Pilih Role</option>
                    <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                    <option value="teknisi" {{ old('role') == 'teknisi' ? 'selected' : '' }}>Teknisi</option>
                </select>
                @error('role')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- STATUS --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select name="status" required class="w-full border rounded-lg px-4 py-2.5">
                    <option value="aktif" {{ old('status', 'aktif') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                    <option value="nonaktif" {{ old('status') == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                </select>
                @error('status')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- TELEPON --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">No. Telepon</label>
                <input type="text" name="telepon" value="{{ old('telepon') }}" class="w-full border rounded-lg px-4 py-2.5" placeholder="08xxxxxxxxxx">
                @error('telepon')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- EMAIL --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required class="w-full border rounded-lg px-4 py-2.5" placeholder="contoh@email.com">
                @error('email')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- ALAMAT --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Alamat</label>
                <textarea name="alamat" rows="3" class="w-full border rounded-lg px-4 py-2.5" placeholder="Masukkan alamat">{{ old('alamat') }}</textarea>
                @error('alamat')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- TOMBOL --}}
            <div class="flex flex-col sm:flex-row justify-end gap-3 pt-3">
                <a href="/akun-admin" class="border rounded-lg px-5 py-2.5 text-sm text-center hover:bg-gray-50">
                    Batal
                </a>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white rounded-lg px-5 py-2.5 text-sm flex items-center justify-center gap-2">
                    <i data-lucide="save" class="w-5 h-5"></i>
                    Simpan Akun
                </button>
            </div>
        </form>
    </div>

    {{-- INFORMASI --}}
    <div class="bg-blue-50 border border-blue-200 rounded-xl p-4">
        <div class="flex gap-3">
            <i data-lucide="info" class="w-5 h-5 text-blue-600 mt-0.5"></i>
            <div class="text-sm text-blue-700">
                <p class="font-semibold">Informasi Login</p>
                <p class="mt-1">Setelah akun berhasil dibuat, username dan password awal akan ditampilkan di halaman Manajemen Akun. Informasi tersebut dapat diberikan kepada Admin atau Teknisi yang bersangkutan.</p>
            </div>
        </div>
    </div>
</div>

<script>
function copyText(inputId, button) {
    const input = document.getElementById(inputId);
    navigator.clipboard.writeText(input.value);
    const original = button.innerHTML;
    button.innerHTML = 'Tersalin';

    setTimeout(() => {
        button.innerHTML = original;
        lucide.createIcons();
    }, 1500);
}

lucide.createIcons();
</script>
@endsection