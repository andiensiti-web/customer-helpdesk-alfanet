@extends('layouts.app')

@section('content')
<div class="w-full space-y-4 sm:space-y-6">
    {{-- HEADER --}}
    <div>
        <a href="/akun-admin" class="text-blue-600 flex items-center gap-2 mb-3 text-sm">
            <i data-lucide="arrow-left" class="w-5 h-5"></i>
            Kembali
        </a>
        <h1 class="text-2xl sm:text-3xl font-bold text-gray-800">Edit Akun</h1>
        <p class="text-sm sm:text-base text-gray-500 mt-1">Perbarui informasi akun Admin atau Teknisi Alfa Net</p>
    </div>

    {{-- ERROR --}}
    @if($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 rounded-lg p-4">
            <p class="font-medium mb-2">Terdapat kesalahan:</p>
            <ul class="list-disc pl-5 text-sm space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="/edit-akun/{{ $akun->id }}" method="POST">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-6">
            {{-- INFORMASI AKUN --}}
            <div class="lg:col-span-2 bg-white rounded-xl shadow-sm p-4 sm:p-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-5">Informasi Akun</h2>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    {{-- ID AKUN --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">ID Akun</label>
                        <input type="text" value="USR-{{ str_pad($akun->id, 4, '0', STR_PAD_LEFT) }}" readonly class="w-full bg-gray-100 border rounded-lg px-4 py-3 text-sm text-gray-600">
                    </div>

                    {{-- NAMA --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nama Pengguna <span class="text-red-500">*</span></label>
                        <input type="text" name="name" value="{{ old('name', $akun->name) }}" class="w-full border rounded-lg px-4 py-3 text-sm" required>
                        @error('name')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- USERNAME --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Username <span class="text-red-500">*</span></label>
                        <input type="text" name="username" value="{{ old('username', $akun->username) }}" class="w-full border rounded-lg px-4 py-3 text-sm" required>
                        @error('username')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- PASSWORD --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                        <input type="password" name="password" placeholder="Kosongkan jika tidak diubah" class="w-full border rounded-lg px-4 py-3 text-sm">
                        <p class="text-xs text-gray-500 mt-1">Kosongkan jika password tidak ingin diubah.</p>
                        @error('password')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- ROLE --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Role Pengguna <span class="text-red-500">*</span></label>
                        <select name="role" class="w-full border rounded-lg px-4 py-3 text-sm" required>
                            <option value="admin" {{ old('role', $akun->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="teknisi" {{ old('role', $akun->role) == 'teknisi' ? 'selected' : '' }}>Teknisi</option>
                        </select>
                        @error('role')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- STATUS --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Status Akun <span class="text-red-500">*</span></label>
                        <select name="status" class="w-full border rounded-lg px-4 py-3 text-sm" required>
                            <option value="aktif" {{ old('status', $akun->status) == 'aktif' ? 'selected' : '' }}>Aktif</option>
                            <option value="nonaktif" {{ old('status', $akun->status) == 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
                        </select>
                        @error('status')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- DATA TAMBAHAN --}}
            <div class="bg-white rounded-xl shadow-sm p-4 sm:p-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-5">Data Tambahan</h2>

                <div class="space-y-5">
                    {{-- TELEPON --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nomor Telepon</label>
                        <input type="text" name="telepon" value="{{ old('telepon', $akun->telepon) }}" class="w-full border rounded-lg px-4 py-3 text-sm" placeholder="08xxxxxxxxxx">
                        @error('telepon')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- EMAIL --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Email <span class="text-red-500">*</span></label>
                        <input type="email" name="email" value="{{ old('email', $akun->email) }}" class="w-full border rounded-lg px-4 py-3 text-sm" required>
                        @error('email')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- ALAMAT --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Alamat</label>
                        <textarea name="alamat" rows="5" class="w-full border rounded-lg px-4 py-3 text-sm">{{ old('alamat', $akun->alamat) }}</textarea>
                        @error('alamat')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        {{-- BUTTON --}}
        <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-3 mt-6">
            <a href="/akun-admin" class="w-full sm:w-auto px-6 py-3 border rounded-lg text-gray-700 flex items-center justify-center gap-2 text-sm">
                Batal
            </a>
            <button type="submit" class="w-full sm:w-auto px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg flex items-center justify-center gap-2 text-sm">
                <i data-lucide="save" class="w-5 h-5"></i>
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>

<script>
    lucide.createIcons();
</script>
@endsection