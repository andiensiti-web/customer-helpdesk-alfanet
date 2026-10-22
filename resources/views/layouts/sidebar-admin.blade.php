<aside id="sidebar"
    class="fixed md:static inset-y-0 left-0 z-50 w-72 bg-blue-900 text-white p-5 sm:p-6 transform -translate-x-full md:translate-x-0 transition-transform duration-300 flex-shrink-0 overflow-y-auto">

    <button onclick="toggleSidebar()"
        class="md:hidden absolute top-4 right-4 p-1 rounded-lg hover:bg-blue-700">
        <i data-lucide="x" class="w-6 h-6"></i>
    </button>
    
    @php
        $dataPerusahaan = \App\Models\PengaturanPerusahaan::first();
    @endphp

    <div class="mb-6 sm:mb-8 pr-8">
        <h2 class="text-xl sm:text-2xl font-bold">
            {{ $dataPerusahaan->nama_perusahaan ?? 'Alfa Net' }}
        </h2>

        <p class="text-xs sm:text-sm text-blue-200">
            Customer & Helpdesk System
        </p>
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

    <nav class="space-y-1.5 sm:space-y-2">

        <a href="/dashboard"
            class="flex items-center gap-3 p-3 rounded-lg transition duration-200 {{ request()->is('dashboard') ? 'bg-blue-700' : 'hover:bg-blue-700' }}">
            <i data-lucide="layout-dashboard" class="w-5 h-5 flex-shrink-0"></i>
            <span>Dashboard</span>
        </a>

        <a href="/pelanggan-admin"
            class="flex items-center gap-3 p-3 rounded-lg transition duration-200 {{ request()->is('pelanggan-admin') ? 'bg-blue-700' : 'hover:bg-blue-700' }}">
            <i data-lucide="users" class="w-5 h-5 flex-shrink-0"></i>
            <span>Data Pelanggan</span>
        </a>

        <a href="/teknisi-admin"
            class="flex items-center gap-3 p-3 rounded-lg transition duration-200 {{ request()->is('teknisi-admin') ? 'bg-blue-700' : 'hover:bg-blue-700' }}">
            <i data-lucide="wrench" class="w-5 h-5 flex-shrink-0"></i>
            <span>Data Teknisi</span>
        </a>

        <a href="/gangguan-admin"
            class="flex items-center gap-3 p-3 rounded-lg transition duration-200 {{ request()->is('gangguan-admin') ? 'bg-blue-700' : 'hover:bg-blue-700' }}">
            <i data-lucide="circle-alert" class="w-5 h-5 flex-shrink-0"></i>
            <span>Laporan Gangguan</span>
        </a>

        <a href="/penugasan-teknisi"
            class="flex items-center gap-3 p-3 rounded-lg transition duration-200 {{ request()->is('penugasan-teknisi') ? 'bg-blue-700' : 'hover:bg-blue-700' }}">
            <i data-lucide="user-cog" class="w-5 h-5 flex-shrink-0"></i>
            <span>Penugasan Teknisi</span>
        </a>

        <a href="/pembayaran-admin"
            class="flex items-center gap-3 p-3 rounded-lg transition duration-200 {{ request()->is('pembayaran-admin') ? 'bg-blue-700' : 'hover:bg-blue-700' }}">
            <i data-lucide="credit-card" class="w-5 h-5 flex-shrink-0"></i>
            <span>Pembayaran</span>
        </a>

        <a href="/tagihan-admin"
            class="flex items-center gap-3 p-3 rounded-lg transition duration-200 {{ request()->is('tagihan-admin') ? 'bg-blue-700' : 'hover:bg-blue-700' }}">
            <i data-lucide="file-text" class="w-5 h-5 flex-shrink-0"></i>
            <span>Tagihan</span>
        </a>

        <a href="/laporan-admin"
            class="flex items-center gap-3 p-3 rounded-lg transition duration-200 {{ request()->is('laporan-admin') ? 'bg-blue-700' : 'hover:bg-blue-700' }}">
            <i data-lucide="chart-column" class="w-5 h-5 flex-shrink-0"></i>
            <span>Laporan</span>
        </a>

        <a href="/akun-admin"
            class="flex items-center gap-3 p-3 rounded-lg transition duration-200 {{ request()->is('akun-admin') ? 'bg-blue-700' : 'hover:bg-blue-700' }}">
            <i data-lucide="users-round" class="w-5 h-5 flex-shrink-0"></i>
            <span>Manajemen Akun</span>
        </a>

        <a href="/pengaturan"
            class="flex items-center gap-3 p-3 rounded-lg transition duration-200 {{ request()->is('pengaturan') ? 'bg-blue-700' : 'hover:bg-blue-700' }}">
            <i data-lucide="settings" class="w-5 h-5 flex-shrink-0"></i>
            <span>Pengaturan</span>
        </a>

    </nav>

    
<hr class="border-blue-700 mt-6 mb-4">


@php
    $dataPerusahaan = $dataPerusahaan
        ?? \App\Models\PengaturanPerusahaan::first();

    $latitude = $dataPerusahaan->latitude ?? null;
    $longitude = $dataPerusahaan->longitude ?? null;

    $adaLokasi = is_numeric($latitude)
        && is_numeric($longitude);

    $mapsUrl = $adaLokasi
        ? "https://www.google.com/maps?q={$latitude},{$longitude}"
        : "https://www.google.com/maps/search/?api=1&query="
            . urlencode($dataPerusahaan->alamat_perusahaan ?? '');
@endphp

<div class="mx-0 mt-4 rounded-xl bg-blue-800 p-4">
    <a href="{{ $mapsUrl }}"
       target="_blank"
       rel="noopener noreferrer"
       class="group block"
       title="Lihat lokasi perusahaan di Google Maps">

        <h3 class="mb-3 flex items-center gap-2 text-sm font-semibold text-white">
            <i data-lucide="building-2" class="w-5 h-5"></i>
            Informasi Perusahaan
        </h3>

        <div class="space-y-3 text-sm text-blue-100">
            <div class="flex items-start gap-2">
                <i data-lucide="map-pin"
                   class="mt-0.5 h-4 w-4 shrink-0"></i>

                <span class="group-hover:underline">
                    {{ $dataPerusahaan->alamat_perusahaan ?? 'Alamat belum diatur' }}
                </span>
            </div>

            <div class="flex items-start gap-2">
                <i data-lucide="phone"
                   class="mt-0.5 h-4 w-4 shrink-0"></i>

                <span>
                    {{ $dataPerusahaan->telepon_perusahaan ?? 'Telepon belum diatur' }}
                </span>
            </div>
        </div>

        <div class="mt-3 flex items-center gap-2 rounded-lg bg-blue-700 px-3 py-2 text-sm text-white transition group-hover:bg-blue-600">
            <i data-lucide="map" class="h-4 w-4"></i>
            <span>Lihat Peta Lokasi</span>
            <i data-lucide="external-link" class="ml-auto h-4 w-4"></i>
        </div>
    </a>
</div>



</aside>