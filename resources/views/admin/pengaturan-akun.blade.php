
@extends('layouts.app')

@section('content')
<div class="p-4 sm:p-6">

    {{-- HEADER --}}
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">
            Pengaturan Perusahaan
        </h1>
        <p class="mt-1 text-sm text-gray-500">
            Kelola informasi dan lokasi perusahaan Alfa Net.
        </p>
    </div>

    {{-- NOTIFIKASI --}}
    @if(session('success'))
        <div class="mb-5 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-5 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
            {{ session('error') }}
        </div>
    @endif

    @if($errors->any())
        <div class="mb-5 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
            <p class="mb-1 font-semibold">Data belum bisa disimpan:</p>
            <ul class="list-inside list-disc">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- FORM --}}
    <form
        action="{{ route('pengaturan-perusahaan.update') }}"
        method="POST"
        class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm sm:p-6"
    >
        @csrf

        <div class="grid grid-cols-1 gap-5 md:grid-cols-2">

            {{-- NAMA PERUSAHAAN --}}
            <div>
                <label for="nama_perusahaan"
                       class="mb-2 block text-sm font-medium text-gray-700">
                    Nama Perusahaan
                </label>
                <input
                    type="text"
                    id="nama_perusahaan"
                    name="nama_perusahaan"
                    value="{{ old('nama_perusahaan', $perusahaan->nama_perusahaan ?? '') }}"
                    maxlength="255"
                    required
                    class="w-full rounded-lg border border-gray-300 px-4 py-3 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100"
                    placeholder="Contoh: Alfa Net"
                >
            </div>

            {{-- NOMOR TELEPON --}}
            <div>
                <label for="telepon_perusahaan"
                       class="mb-2 block text-sm font-medium text-gray-700">
                    No WhatsApp / Telp Perusahaan
                </label>
                <input
                    type="text"
                    id="telepon_perusahaan"
                    name="telepon_perusahaan"
                    value="{{ old('telepon_perusahaan', $perusahaan->telepon_perusahaan ?? '') }}"
                    maxlength="30"
                    required
                    class="w-full rounded-lg border border-gray-300 px-4 py-3 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100"
                    placeholder="Contoh: 08xxxxxxxxxx"
                >
            </div>

            {{-- ALAMAT --}}
            <div class="md:col-span-2">
                <label for="alamat_perusahaan"
                       class="mb-2 block text-sm font-medium text-gray-700">
                    Alamat Perusahaan
                </label>
                <textarea
                    id="alamat_perusahaan"
                    name="alamat_perusahaan"
                    rows="3"
                    required
                    class="w-full rounded-lg border border-gray-300 px-4 py-3 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100"
                    placeholder="Masukkan alamat lengkap perusahaan"
                >{{ old('alamat_perusahaan', $perusahaan->alamat_perusahaan ?? '') }}</textarea>
            </div>

            {{-- LATITUDE --}}
            <div>
                <label for="latitude"
                       class="mb-2 block text-sm font-medium text-gray-700">
                    Latitude
                </label>
                <input
                    type="number"
                    step="any"
                    id="latitude"
                    name="latitude"
                    value="{{ old('latitude', $perusahaan->latitude ?? '-6.8912') }}"
                    min="-90"
                    max="90"
                    required
                    class="w-full rounded-lg border border-gray-300 px-4 py-3 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100"
                >
            </div>

            {{-- LONGITUDE --}}
            <div>
                <label for="longitude"
                       class="mb-2 block text-sm font-medium text-gray-700">
                    Longitude
                </label>
                <input
                    type="number"
                    step="any"
                    id="longitude"
                    name="longitude"
                    value="{{ old('longitude', $perusahaan->longitude ?? '107.9038') }}"
                    min="-180"
                    max="180"
                    required
                    class="w-full rounded-lg border border-gray-300 px-4 py-3 text-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100"
                >
            </div>

        </div>

        {{-- PETA --}}
        <div class="mt-6">
            <label class="mb-2 block text-sm font-medium text-gray-700">
                Titik Koordinat Perusahaan
            </label>

            <div
                id="company-map"
                class="h-80 w-full rounded-lg border border-gray-300"
            ></div>

            <p class="mt-2 text-xs text-gray-500">
                Klik peta atau geser penanda untuk menentukan lokasi perusahaan.
                Latitude dan longitude akan diperbarui otomatis.
            </p>
        </div>

        {{-- TOMBOL SIMPAN --}}
        <div class="mt-6 flex justify-end">
            <button
                type="submit"
                class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-5 py-3 text-sm font-semibold text-white transition hover:bg-blue-700"
            >
                <i data-lucide="save" class="h-4 w-4"></i>
                Simpan
            </button>
        </div>

    </form>
</div>

{{-- LEAFLET MAP --}}
<link
    rel="stylesheet"
    href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const mapElement = document.getElementById('company-map');
    const latitudeInput = document.getElementById('latitude');
    const longitudeInput = document.getElementById('longitude');

    if (!mapElement || !latitudeInput || !longitudeInput) {
        return;
    }

    if (typeof L === 'undefined') {
        mapElement.innerHTML =
            '<p class="p-4 text-sm text-red-600">Peta gagal dimuat. Periksa koneksi internet.</p>';
        return;
    }

    const parseCoordinate = (value, fallback) => {
        const number = Number(value);
        return Number.isFinite(number) ? number : fallback;
    };

    const initialLat = parseCoordinate(latitudeInput.value, -6.8912);
    const initialLng = parseCoordinate(longitudeInput.value, 107.9038);

    const map = L.map(mapElement).setView(
        [initialLat, initialLng],
        15
    );

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors',
        maxZoom: 19
    }).addTo(map);

    const marker = L.marker([initialLat, initialLng], {
        draggable: true
    }).addTo(map);

    function updateCoordinates(lat, lng) {
        latitudeInput.value = lat.toFixed(7);
        longitudeInput.value = lng.toFixed(7);
        marker.setLatLng([lat, lng]);
    }

    map.on('click', function (event) {
        updateCoordinates(event.latlng.lat, event.latlng.lng);
    });

    marker.on('dragend', function () {
        const position = marker.getLatLng();
        updateCoordinates(position.lat, position.lng);
    });

    function updateMapFromInputs() {
        const latText = latitudeInput.value.trim();
        const lngText = longitudeInput.value.trim();

        if (latText === '' || lngText === '') {
            return;
        }

        const lat = Number(latText);
        const lng = Number(lngText);

        if (
            !Number.isFinite(lat) ||
            !Number.isFinite(lng) ||
            lat < -90 || lat > 90 ||
            lng < -180 || lng > 180
        ) {
            return;
        }

        marker.setLatLng([lat, lng]);
        map.setView([lat, lng], 15);
    }

    latitudeInput.addEventListener('change', updateMapFromInputs);
    longitudeInput.addEventListener('change', updateMapFromInputs);

    setTimeout(function () {
        map.invalidateSize();
    }, 200);
});
</script>
@endsection
