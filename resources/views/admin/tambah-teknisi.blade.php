@extends('layouts.app')

@section('content')

<div class="space-y-6">

    {{-- HEADER --}}
    <div>
        <a href="/teknisi-admin" class="text-blue-600 flex items-center gap-2 mb-3">
            <i data-lucide="arrow-left" class="w-5 h-5"></i>
            Kembali
        </a>

        <h1 class="text-3xl font-bold text-gray-800">
            Tambah Teknisi
        </h1>

        <p class="text-gray-500 mt-1">
            Tambahkan data teknisi baru Alfa Net
        </p>
    </div>

    {{-- ERROR --}}
    @if($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-600 p-4 rounded-lg">
            <ul class="list-disc ml-5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- FORM --}}
    <form
        action="/tambah-teknisi"
        method="POST"
        enctype="multipart/form-data"
    >
        @csrf

        <div class="bg-white rounded-xl shadow-sm p-6">

            <h2 class="text-xl font-semibold mb-5">
                Informasi Teknisi
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                {{-- NAMA --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Nama Teknisi
                    </label>

                    <input
                        type="text"
                        name="nama"
                        value="{{ old('nama') }}"
                        required
                        class="w-full border rounded-lg px-4 py-3"
                        placeholder="Masukkan nama teknisi"
                    >

                    @error('nama')
                        <p class="text-red-500 text-xs mt-1">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- ID TEKNISI --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        ID Teknisi
                    </label>

                    <input
                        type="text"
                        value="Otomatis oleh sistem"
                        disabled
                        class="w-full border rounded-lg px-4 py-3 bg-gray-100 text-gray-500"
                    >

                    <p class="text-xs text-gray-500 mt-1">
                        ID akan dibuat otomatis saat data disimpan.
                    </p>
                </div>

                {{-- EMAIL --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Email
                    </label>

                    <input
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        required
                        class="w-full border rounded-lg px-4 py-3"
                        placeholder="contoh@email.com"
                    >

                    @error('email')
                        <p class="text-red-500 text-xs mt-1">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- TELEPON --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        No. Telepon
                    </label>

                    <input
                        type="text"
                        name="telepon"
                        value="{{ old('telepon') }}"
                        required
                        class="w-full border rounded-lg px-4 py-3"
                        placeholder="08xxxxxxxxxx"
                    >

                    @error('telepon')
                        <p class="text-red-500 text-xs mt-1">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- KEAHLIAN --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Keahlian
                    </label>

                    <input
                        type="text"
                        name="keahlian"
                        value="{{ old('keahlian') }}"
                        class="w-full border rounded-lg px-4 py-3"
                        placeholder="Contoh: Fiber Optic"
                    >

                    @error('keahlian')
                        <p class="text-red-500 text-xs mt-1">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- STATUS --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Status
                    </label>

                    <select
                        name="status"
                        required
                        class="w-full border rounded-lg px-4 py-3"
                    >

                        <option value="">
                            Pilih Status
                        </option>

                        <option
                            value="aktif"
                            {{ old('status') === 'aktif' ? 'selected' : '' }}
                        >
                            Aktif
                        </option>

                        <option
                            value="isolir"
                            {{ old('status') === 'isolir' ? 'selected' : '' }}
                        >
                            Isolir
                        </option>

                        <option
                            value="nonaktif"
                            {{ old('status') === 'nonaktif' ? 'selected' : '' }}
                        >
                            Nonaktif
                        </option>

                    </select>

                    @error('status')
                        <p class="text-red-500 text-xs mt-1">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- ALAMAT --}}
                <div class="md:col-span-2">

                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Alamat
                    </label>

                    <textarea
                        name="alamat"
                        rows="4"
                        required
                        class="w-full border rounded-lg px-4 py-3"
                        placeholder="Masukkan alamat teknisi"
                    >{{ old('alamat') }}</textarea>

                    @error('alamat')
                        <p class="text-red-500 text-xs mt-1">
                            {{ $message }}
                        </p>
                    @enderror

                </div>

            </div>

        </div>

        {{-- INFORMASI AKUN --}}
        <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 mt-5">

            <div class="flex gap-3">

                <i
                    data-lucide="info"
                    class="w-5 h-5 text-blue-600 mt-0.5"
                ></i>

                <div class="text-sm text-blue-700">

                    <p class="font-semibold">
                        Akun Login Otomatis
                    </p>

                    <p class="mt-1">
                        Setelah teknisi disimpan, sistem otomatis membuat akun login.
                        Username dibuat dari email dan password awal menggunakan ID akun.
                        Informasi login akan ditampilkan setelah data berhasil disimpan.
                    </p>

                </div>

            </div>

        </div>

        {{-- TOMBOL --}}
        <div class="flex justify-end gap-3 mt-6">

            <a
                href="/teknisi-admin"
                class="px-5 py-3 border rounded-lg text-gray-600 hover:bg-gray-50"
            >
                Batal
            </a>

            <button
                type="submit"
                class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-3 rounded-lg"
            >
                Simpan Teknisi
            </button>

        </div>

    </form>

</div>


{{-- PREVIEW FOTO --}}
<script>

    const inputFoto = document.getElementById('foto');
    const previewFoto = document.getElementById('previewFoto');
    const previewPlaceholder = document.getElementById('previewPlaceholder');

    inputFoto.addEventListener('change', function(event) {

        const file = event.target.files[0];

        if (!file) {
            previewFoto.src = '';
            previewFoto.classList.add('hidden');
            previewPlaceholder.classList.remove('hidden');
            return;
        }

        const allowedTypes = [
            'image/jpeg',
            'image/png'
        ];

        if (!allowedTypes.includes(file.type)) {
            alert('Foto harus berformat JPG, JPEG, atau PNG.');

            inputFoto.value = '';

            previewFoto.src = '';
            previewFoto.classList.add('hidden');
            previewPlaceholder.classList.remove('hidden');

            return;
        }

        if (file.size > 5 * 1024 * 1024) {
            alert('Ukuran foto maksimal 5 MB.');

            inputFoto.value = '';

            previewFoto.src = '';
            previewFoto.classList.add('hidden');
            previewPlaceholder.classList.remove('hidden');

            return;
        }

        const reader = new FileReader();

        reader.onload = function(e) {

            previewFoto.src = e.target.result;

            previewFoto.classList.remove('hidden');
            previewPlaceholder.classList.add('hidden');

        };

        reader.readAsDataURL(file);

    });


    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }

</script>

@endsection