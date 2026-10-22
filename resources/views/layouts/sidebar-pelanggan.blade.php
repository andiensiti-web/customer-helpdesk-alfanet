<aside id="sidebar"
    class="fixed md:static top-0 left-0 z-50 w-72 bg-blue-900 text-white min-h-screen p-6 flex-shrink-0 transform -translate-x-full md:translate-x-0 transition duration-300">

    <button onclick="toggleSidebar()" class="md:hidden absolute top-4 right-4">
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

    @php
        $jumlahNotifikasi = \App\Models\Notifikasi::where(
            'user_id',
            Auth::id()
        )
        ->where('dibaca', false)
        ->count();
    @endphp

    <nav class="space-y-2">

        <a href="/dashboard-pelanggan"
            class="flex items-center gap-3 px-4 py-3 rounded-lg transition
            {{ request()->is('dashboard-pelanggan') ? 'bg-blue-700' : 'hover:bg-blue-700' }}">
            <i data-lucide="layout-dashboard" class="w-6 h-6"></i>
            <span>Dashboard</span>
        </a>

        <a href="/laporan-gangguan"
            class="flex items-center gap-3 px-4 py-3 rounded-lg transition
            {{ request()->is('laporan-gangguan') ? 'bg-blue-700' : 'hover:bg-blue-700' }}">
            <i data-lucide="triangle-alert" class="w-6 h-6"></i>
            <span>Laporan Gangguan</span>
        </a>

        <a href="/tagihan-pelanggan"
            class="flex items-center gap-3 px-4 py-3 rounded-lg transition
            {{ request()->is('tagihan-pelanggan') ? 'bg-blue-700' : 'hover:bg-blue-700' }}">
            <i data-lucide="receipt" class="w-6 h-6"></i>
            <span>Tagihan Saya</span>
        </a>

        <a href="/riwayat-pembayaran"
            class="flex items-center gap-3 px-4 py-3 rounded-lg transition
            {{ request()->is('riwayat-pembayaran') ? 'bg-blue-700' : 'hover:bg-blue-700' }}">
            <i data-lucide="history" class="w-6 h-6"></i>
            <span>Riwayat Pembayaran</span>
        </a>

        <a href="/pengaturan-pelanggan"
            class="flex items-center gap-3 px-4 py-3 rounded-lg transition
            {{ request()->is('pengaturan-pelanggan') ? 'bg-blue-700' : 'hover:bg-blue-700' }}">
            <i data-lucide="settings" class="w-6 h-6"></i>
            <span>Pengaturan</span>
        </a>

    </nav>

    
@php
    $dataPerusahaan = $dataPerusahaan
        ?? \App\Models\PengaturanPerusahaan::first();

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