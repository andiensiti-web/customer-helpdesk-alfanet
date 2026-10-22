@extends('layouts.app')

@section('content')

<div class="space-y-6">

    <div>
        <a href="/teknisi-admin" class="text-blue-600 flex items-center gap-2 mb-3">
            <i data-lucide="arrow-left" class="w-5 h-5"></i>
            Kembali
        </a>

        <h1 class="text-3xl font-bold text-gray-800">Edit Teknisi</h1>
        <p class="text-gray-500 mt-1">Perbarui data teknisi Alfa Net</p>
    </div>

    @if ($errors->any())
        <div class="bg-red-50 text-red-600 p-4 rounded-lg">
            <ul class="list-disc ml-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="/edit-teknisi/{{ $teknisi->id }}" method="POST">

        @csrf
        @method('PUT')

        <div class="bg-white rounded-xl shadow-sm p-6">

            <h2 class="text-xl font-semibold mb-5">
                Informasi Teknisi
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Nama Teknisi
                    </label>

                    <input type="text"
                        name="nama"
                        value="{{ old('nama', $teknisi->nama) }}"
                        required
                        class="w-full border rounded-lg px-4 py-3">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        ID Teknisi
                    </label>

                    <input type="text"
                        value="{{ $teknisi->id_teknisi }}"
                        disabled
                        class="w-full border rounded-lg px-4 py-3 bg-gray-100 text-gray-500">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Email
                    </label>

                    <input type="email"
                        name="email"
                        value="{{ old('email', $teknisi->email) }}"
                        required
                        class="w-full border rounded-lg px-4 py-3">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        No. Telepon
                    </label>

                    <input type="text"
                        name="telepon"
                        value="{{ old('telepon', $teknisi->telepon) }}"
                        required
                        class="w-full border rounded-lg px-4 py-3">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Keahlian
                    </label>

                    <input type="text"
                        name="keahlian"
                        value="{{ old('keahlian', $teknisi->keahlian) }}"
                        class="w-full border rounded-lg px-4 py-3">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Status
                    </label>
                        <select
                            name="status"
                            required
                            class="w-full border rounded-lg px-4 py-3"
                        >

                        <option
                            value="aktif"
                            {{ old('status', $teknisi->status) === 'aktif' ? 'selected' : '' }}
                        >
                            Aktif
                        </option>

                        <option
                            value="isolir"
                            {{ old('status', $teknisi->status) === 'isolir' ? 'selected' : '' }}
                        >
                            Isolir
                        </option>

                        <option
                            value="nonaktif"
                            {{ old('status', $teknisi->status) === 'nonaktif' ? 'selected' : '' }}
                        >
                            Nonaktif
                        </option>

                    </select>
                </div>

                <div class="md:col-span-2">

                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Alamat
                    </label>

                    <textarea
                        name="alamat"
                        rows="4"
                        required
                        class="w-full border rounded-lg px-4 py-3">{{ old('alamat', $teknisi->alamat) }}</textarea>

                </div>

            </div>

        </div>

        <div class="flex justify-end gap-3 mt-6">

            <a href="/teknisi-admin"
                class="px-5 py-3 border rounded-lg text-gray-600">
                Batal
            </a>

            <button type="submit"
                class="bg-blue-600 text-white px-5 py-3 rounded-lg">
                Simpan Perubahan
            </button>

        </div>

    </form>

</div>

<script>
    lucide.createIcons();
</script>

@endsection