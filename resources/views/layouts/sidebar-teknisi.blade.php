<aside id="sidebar"
    class="fixed md:static inset-y-0 left-0 z-50 w-72 h-screen bg-blue-900 text-white p-6 flex-shrink-0 transform -translate-x-full md:translate-x-0 transition duration-300 overflow-y-auto overscroll-contain">


    {{-- Tombol Close HP --}}
    <button
        onclick="toggleSidebar()"
        class="md:hidden absolute top-4 right-4">
        <i data-lucide="x" class="w-6 h-6"></i>
    </button>

    @php
        $dataPerusahaan = \App\Models\PengaturanPerusahaan::first();
    @endphp

    <div class="mb-8">
        <h2 class="text-2xl font-bold">
            {{ $dataPerusahaan->nama_perusahaan ?? 'Alfa Net' }}
        </h2>
        <p class="text-sm text-blue-200">Customer & Helpdesk System</p>
    </div>

    <hr class="border-blue-700 mb-5">

    @php
        $jumlahNotifikasi = \App\Models\Notifikasi::where(
            'user_id',
            Auth::id()
        )
        ->where('dibaca', false)
        ->count();
    @endphp

    {{-- Menu --}}
    <nav class="space-y-2">

        {{-- Dashboard --}}
        <a href="/dashboard-teknisi"
            class="flex items-center gap-3 px-4 py-3 rounded-lg transition duration-200
            {{ request()->is('dashboard-teknisi') ? 'bg-blue-700' : 'hover:bg-blue-700' }}">
            <i data-lucide="layout-dashboard" class="w-5 h-5"></i>
            <span>Dashboard</span>
        </a>

        {{-- Tugas Saya --}}
        <a href="/tugas-teknisi"
            class="flex items-center gap-3 px-4 py-3 rounded-lg transition duration-200
            {{ request()->is('tugas-teknisi') ? 'bg-blue-700' : 'hover:bg-blue-700' }}">
            <i data-lucide="clipboard-list" class="w-5 h-5"></i>
            <span>Tugas Saya</span>
        </a>

        {{-- Data Pelanggan --}}
        <a href="/pelanggan-teknisi"
            class="flex items-center gap-3 px-4 py-3 rounded-lg transition duration-200
            {{ request()->is('pelanggan-teknisi') ? 'bg-blue-700' : 'hover:bg-blue-700' }}">
            <i data-lucide="users" class="w-5 h-5"></i>
            <span>Data Pelanggan</span>
        </a>

        {{-- Jadwal Kunjungan --}}
        <a href="/jadwal-kunjungan"
            class="flex items-center gap-3 px-4 py-3 rounded-lg transition duration-200
            {{ request()->is('jadwal-kunjungan') ? 'bg-blue-700' : 'hover:bg-blue-700' }}">
            <i data-lucide="calendar-days" class="w-5 h-5"></i>
            <span>Jadwal Kunjungan</span>
        </a>

        {{-- Laporan --}}
        <a href="/laporan-teknisi"
            class="flex items-center gap-3 px-4 py-3 rounded-lg transition duration-200
            {{ request()->is('laporan-teknisi') ? 'bg-blue-700' : 'hover:bg-blue-700' }}">
            <i data-lucide="file-text" class="w-5 h-5"></i>
            <span>Laporan</span>
        </a>

       {{-- Pengaturan --}}
        <a href="/pengaturan-teknisi"
            class="flex items-center gap-3 px-4 py-3 rounded-lg transition duration-200
            {{ request()->is('pengaturan-teknisi') ? 'bg-blue-700' : 'hover:bg-blue-700' }}">
            <i data-lucide="settings" class="w-5 h-5"></i>
            <span>Pengaturan</span>
        </a>

    </nav>

     {{-- INFORMASI PERUSAHAAN --}}
    @php
        $latitude = $dataPerusahaan->latitude ?? null;
        $longitude = $dataPerusahaan->longitude ?? null;

        $adaLokasi = is_numeric($latitude)
            && is_numeric($longitude);

        $mapsUrl = $adaLokasi
            ? "https://www.google.com/maps?q={$latitude},{$longitude}"
            : (
                !empty($dataPerusahaan->alamat_perusahaan)
                    ? "https://www.google.com/maps/search/?api=1&query="
                        . urlencode($dataPerusahaan->alamat_perusahaan)
                    : "https://www.google.com/maps"
            );
    @endphp

    <hr class="border-blue-700 mt-6 mb-4">

    <div class="rounded-xl bg-blue-800 p-4">
        <h3 class="mb-3 flex items-center gap-2 text-sm font-semibold text-white">
            <i data-lucide="building-2" class="w-5 h-5"></i>
            Informasi Perusahaan
        </h3>

        <div class="space-y-3 text-sm text-blue-100">
            <div class="flex items-start gap-2">
                <i data-lucide="map-pin" class="w-4 h-4 mt-0.5 shrink-0"></i>
                <span>
                    {{ $dataPerusahaan->alamat_perusahaan ?? 'Alamat belum diatur' }}
                </span>
            </div>

            <div class="flex items-start gap-2">
                <i data-lucide="phone" class="w-4 h-4 mt-0.5 shrink-0"></i>
                <span>
                    {{ $dataPerusahaan->telepon_perusahaan ?? 'Telepon belum diatur' }}
                </span>
            </div>
        </div>

        <a href="{{ $mapsUrl }}"
           target="_blank"
           rel="noopener noreferrer"
           class="mt-4 flex items-center gap-2 rounded-lg bg-blue-700 px-3 py-2 text-sm text-white transition hover:bg-blue-600">
            <i data-lucide="map" class="w-4 h-4"></i>
            <span>Lihat Peta Lokasi</span>
            <i data-lucide="external-link" class="w-4 h-4 ml-auto"></i>
        </a>
    </div>


</aside>