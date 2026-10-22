
@extends('layouts.app')

@section('content')

<div class="space-y-8">

    {{-- HEADER DASHBOARD --}}
    <div>
        <h1 class="text-3xl font-bold text-gray-800">
            Dashboard
        </h1>
        <p class="text-gray-500 mt-2">
            Selamat datang di Sistem Pengelolaan Pelanggan dan Layanan Alfa Net
        </p>
    </div>

    {{-- CARD STATISTIK --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">

        {{-- TOTAL PELANGGAN --}}
        <a href="{{ url('/pelanggan-admin') }}"
           class="block bg-blue-50 rounded-xl p-6 shadow-sm min-h-[190px] flex flex-col justify-center cursor-pointer transition duration-200 hover:-translate-y-1 hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-blue-400"
           aria-label="Lihat semua pelanggan">
            <div class="flex justify-center">
                <div class="bg-blue-100 p-3 rounded-lg">
                    <i data-lucide="users" class="w-6 h-6 text-blue-600"></i>
                </div>
            </div>
            <p class="text-center mt-4 text-gray-600">Total Pelanggan</p>
            <h2 class="text-center text-3xl font-bold mt-1">
                {{ $totalPelanggan ?? 0 }}
            </h2>
            <p class="text-center text-xs text-gray-500 mt-1">
                Semua pelanggan terdaftar
            </p>
        </a>

        {{-- PELANGGAN AKTIF --}}
        <a href="{{ url('/pelanggan-admin?status=aktif') }}"
           class="block bg-green-50 rounded-xl p-6 shadow-sm min-h-[190px] flex flex-col justify-center cursor-pointer transition duration-200 hover:-translate-y-1 hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-green-400"
           aria-label="Lihat pelanggan aktif">
            <div class="flex justify-center">
                <div class="bg-green-100 p-3 rounded-lg">
                    <i data-lucide="user-check" class="w-6 h-6 text-green-600"></i>
                </div>
            </div>
            <p class="text-center mt-4 text-gray-600">Pelanggan Aktif</p>
            <h2 class="text-center text-3xl font-bold mt-1">
                {{ $pelangganAktif ?? 0 }}
            </h2>
            <p class="text-center text-xs text-gray-500 mt-1">
                Pelanggan aktif
            </p>
        </a>

        {{-- PELANGGAN TIDAK AKTIF --}}
        <a href="{{ url('/pelanggan-admin?status=nonaktif') }}"
           class="block bg-gray-50 rounded-xl p-6 shadow-sm min-h-[190px] flex flex-col justify-center cursor-pointer transition duration-200 hover:-translate-y-1 hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-gray-400"
           aria-label="Lihat pelanggan tidak aktif">
            <div class="flex justify-center">
                <div class="bg-gray-200 p-3 rounded-lg">
                    <i data-lucide="user-x" class="w-6 h-6 text-gray-600"></i>
                </div>
            </div>
            <p class="text-center mt-4 text-gray-600">Pelanggan Tidak Aktif</p>
            <h2 class="text-center text-3xl font-bold mt-1">
                {{ $pelangganTidakAktif ?? 0 }}
            </h2>
            <p class="text-center text-xs text-gray-500 mt-1">
                Pelanggan nonaktif
            </p>
        </a>

        {{-- PELANGGAN ISOLIR --}}
        <a href="{{ url('/pelanggan-admin?status=isolir') }}"
           class="block bg-red-50 rounded-xl p-6 shadow-sm min-h-[190px] flex flex-col justify-center cursor-pointer transition duration-200 hover:-translate-y-1 hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-red-400"
           aria-label="Lihat pelanggan isolir">
            <div class="flex justify-center">
                <div class="bg-red-100 p-3 rounded-lg">
                    <i data-lucide="wifi-off" class="w-6 h-6 text-red-600"></i>
                </div>
            </div>
            <p class="text-center mt-4 text-gray-600">Pelanggan Isolir</p>
            <h2 class="text-center text-3xl font-bold mt-1">
                {{ $pelangganIsolir ?? 0 }}
            </h2>
            <p class="text-center text-xs text-gray-500 mt-1">
                Layanan sedang diisolir
            </p>
        </a>

        {{-- GANGGUAN BARU --}}
        <a href="{{ url('/gangguan-admin') }}"
           class="block bg-orange-50 rounded-xl p-6 shadow-sm min-h-[190px] flex flex-col justify-center cursor-pointer transition duration-200 hover:-translate-y-1 hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-orange-400"
           aria-label="Lihat gangguan baru">
            <div class="flex justify-center">
                <div class="bg-orange-100 p-3 rounded-lg">
                    <i data-lucide="triangle-alert" class="w-6 h-6 text-orange-600"></i>
                </div>
            </div>
            <p class="text-center mt-4 text-gray-600">Gangguan Baru</p>
            <h2 class="text-center text-3xl font-bold mt-1">
                {{ $gangguanBaru ?? 0 }}
            </h2>
            <p class="text-center text-xs text-gray-500 mt-1">
                Laporan baru masuk
            </p>
        </a>

        {{-- DALAM PROSES --}}
        <a href="{{ url('/penugasan-teknisi') }}"
           class="block bg-purple-50 rounded-xl p-6 shadow-sm min-h-[190px] flex flex-col justify-center cursor-pointer transition duration-200 hover:-translate-y-1 hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-purple-400"
           aria-label="Lihat tiket dalam proses">
            <div class="flex justify-center">
                <div class="bg-purple-100 p-3 rounded-lg">
                    <i data-lucide="settings" class="w-6 h-6 text-purple-600"></i>
                </div>
            </div>
            <p class="text-center mt-4 text-gray-600">Dalam Proses</p>
            <h2 class="text-center text-3xl font-bold mt-1">
                {{ $dalamProses ?? 0 }}
            </h2>
            <p class="text-center text-xs text-gray-500 mt-1">
                Sedang ditangani teknisi
            </p>
        </a>

        {{-- PEMBAYARAN BELUM BAYAR --}}
        <a href="{{ url('/tagihan-admin?status=belum_bayar') }}"
           class="block bg-yellow-50 rounded-xl p-6 shadow-sm min-h-[190px] flex flex-col justify-center cursor-pointer transition duration-200 hover:-translate-y-1 hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-yellow-400"
           aria-label="Lihat tagihan belum dibayar">
            <div class="flex justify-center">
                <div class="bg-yellow-100 p-3 rounded-lg">
                    <i data-lucide="wallet" class="w-6 h-6 text-yellow-600"></i>
                </div>
            </div>
            <p class="text-center mt-4 text-gray-600">Pembayaran Belum Bayar</p>
            <h2 class="text-center text-3xl font-bold mt-1">
                {{ $belumBayar ?? 0 }}
            </h2>
            <p class="text-center text-xs text-gray-500 mt-1">
                Tagihan belum dibayar
            </p>
        </a>

        {{-- PEMASUKAN BULAN INI --}}
        <a href="{{ url('/laporan-keuangan?periode=bulan_ini') }}"
        class="block bg-emerald-50 rounded-xl p-6 shadow-sm min-h-[190px] flex flex-col justify-center cursor-pointer transition duration-200 hover:-translate-y-1 hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-emerald-400"
        aria-label="Lihat laporan keuangan bulan ini">
            <div class="flex justify-center">
                <div class="bg-emerald-100 p-3 rounded-lg">
                    <i data-lucide="badge-dollar-sign" class="w-6 h-6 text-emerald-600"></i>
                </div>
            </div>

            <p class="text-center mt-4 text-gray-600">Pemasukan Bulan Ini</p>

            <h2 class="text-center text-2xl font-bold mt-1">
                Rp{{ number_format($pemasukanBulanIni ?? 0, 0, ',', '.') }}
            </h2>

            <p class="text-center text-xs text-gray-500 mt-1">
                Pembayaran terverifikasi
            </p>
        </a>

        {{-- PEMASUKAN BULAN KEMARIN --}}
        <a href="{{ url('/laporan-keuangan?periode=bulan_kemarin') }}"
        class="block bg-indigo-50 rounded-xl p-6 shadow-sm min-h-[190px] flex flex-col justify-center cursor-pointer transition duration-200 hover:-translate-y-1 hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-indigo-400"
        aria-label="Lihat laporan keuangan bulan kemarin">

            <div class="flex justify-center">
                <div class="bg-indigo-100 p-3 rounded-lg">
                    <i data-lucide="calendar-check" class="w-6 h-6 text-indigo-600"></i>
                </div>
            </div>

            <p class="text-center mt-4 text-gray-600">
                Pemasukan Bulan Kemarin
            </p>

            <h2 class="text-center text-2xl font-bold mt-1">
                Rp{{ number_format($pemasukanBulanKemarin ?? 0, 0, ',', '.') }}
            </h2>

            <p class="text-center text-xs text-gray-500 mt-1">
                Pembayaran terverifikasi
            </p>
        </a>

    </div>

    {{-- STATISTIK DASHBOARD --}}
    <div>
        <h2 class="text-2xl font-bold text-gray-800">
            Statistik Dashboard
        </h2>
        <p class="text-gray-500">
            Ringkasan aktivitas dan pembayaran pelanggan
        </p>
    </div>

    {{-- GRAFIK --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        {{-- GRAFIK PEMBAYARAN --}}
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="text-xl font-bold">Tren Pembayaran</h3>
            <p class="text-gray-500 mb-5">Per Bulan</p>
            <canvas id="paymentChart"></canvas>
        </div>

        {{-- GRAFIK STATUS PELANGGAN --}}
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h3 class="text-xl font-bold">Status Pelanggan</h3>
            <p class="text-gray-500 mb-5">
                Jumlah pelanggan berdasarkan status
            </p>
            <canvas id="customerChart"></canvas>
        </div>

    </div>

</div>

{{-- JAVASCRIPT GRAFIK --}}
<script>
document.addEventListener('DOMContentLoaded', function () {

    // Grafik pembayaran
    const paymentCanvas = document.getElementById('paymentChart');

    if (paymentCanvas && typeof Chart !== 'undefined') {
        new Chart(paymentCanvas, {
            type: 'line',
            data: {
                labels: [
                    'Januari', 'Februari', 'Maret', 'April',
                    'Mei', 'Juni', 'Juli', 'Agustus',
                    'September', 'Oktober', 'November', 'Desember'
                ],
                datasets: [{
                    label: 'Pembayaran',
                    data: {!! json_encode($dataPembayaran ?? []) !!},
                    borderWidth: 2
                }]
            }
        });
    }

    // Grafik status pelanggan
    const customerCanvas = document.getElementById('customerChart');

    if (customerCanvas && typeof Chart !== 'undefined') {
        new Chart(customerCanvas, {
            type: 'bar',
            data: {
                labels: ['Aktif', 'Menunggak', 'Nonaktif', 'Isolir'],
                datasets: [{
                    label: 'Jumlah',
                    data: {!! json_encode($statusPelanggan ?? [0, 0, 0, 0]) !!},
                    borderWidth: 1
                }]
            }
        });
    }

});
</script>

@endsection
