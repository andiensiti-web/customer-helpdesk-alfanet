@extends('layouts.app')

@section('content')
<div class="p-6">
    <div class="mb-6">
        <a href="/pengaturan"
           class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-gray-700">
            ← Kembali ke Pengaturan
        </a>

        <h1 class="mt-3 text-2xl font-bold text-gray-800">
            Notifikasi
        </h1>

        <p class="text-sm text-gray-500 mt-1">
            Atur jenis notifikasi yang ingin diterima.
        </p>
    </div>

    @if(session('success'))
        <div class="mb-5 rounded-lg bg-green-50 border border-green-200 px-4 py-3 text-sm text-green-700">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 max-w-3xl">

        <form action="/pengaturan/notifikasi" method="POST">
            @csrf

            <div class="space-y-5">

                {{-- Laporan Teknisi --}}
                <div class="flex items-center justify-between gap-4">
                    <div>
                        <h3 class="font-medium text-gray-800">
                            Laporan Teknisi
                        </h3>
                        <p class="text-sm text-gray-500">
                            Notifikasi ketika teknisi mengirimkan laporan.
                        </p>
                    </div>

                    <label class="relative inline-flex items-center cursor-pointer">
                        <input
                            type="checkbox"
                            name="laporan_teknisi"
                            value="1"
                            class="sr-only peer"
                            {{ $setting->laporan_teknisi ? 'checked' : '' }}
                        >

                        <div class="w-11 h-6 bg-gray-200 rounded-full peer
                            peer-checked:bg-blue-600
                            after:content-['']
                            after:absolute after:top-[2px] after:left-[2px]
                            after:bg-white after:border-gray-300
                            after:border after:rounded-full after:h-5 after:w-5
                            after:transition-all
                            peer-checked:after:translate-x-full
                            peer-checked:after:border-white">
                        </div>
                    </label>
                </div>

                {{-- Pembayaran --}}
                <div class="flex items-center justify-between gap-4">
                    <div>
                        <h3 class="font-medium text-gray-800">
                            Pembayaran
                        </h3>
                        <p class="text-sm text-gray-500">
                            Notifikasi terkait pembayaran pelanggan.
                        </p>
                    </div>

                    <label class="relative inline-flex items-center cursor-pointer">
                        <input
                            type="checkbox"
                            name="pembayaran"
                            value="1"
                            class="sr-only peer"
                            {{ $setting->pembayaran ? 'checked' : '' }}
                        >

                        <div class="w-11 h-6 bg-gray-200 rounded-full peer
                            peer-checked:bg-blue-600
                            after:content-['']
                            after:absolute after:top-[2px] after:left-[2px]
                            after:bg-white after:border-gray-300
                            after:border after:rounded-full after:h-5 after:w-5
                            after:transition-all
                            peer-checked:after:translate-x-full
                            peer-checked:after:border-white">
                        </div>
                    </label>
                </div>

                {{-- Gangguan --}}
                <div class="flex items-center justify-between gap-4">
                    <div>
                        <h3 class="font-medium text-gray-800">
                            Gangguan
                        </h3>
                        <p class="text-sm text-gray-500">
                            Notifikasi mengenai laporan gangguan pelanggan.
                        </p>
                    </div>

                    <label class="relative inline-flex items-center cursor-pointer">
                        <input
                            type="checkbox"
                            name="gangguan"
                            value="1"
                            class="sr-only peer"
                            {{ $setting->gangguan ? 'checked' : '' }}
                        >

                        <div class="w-11 h-6 bg-gray-200 rounded-full peer
                            peer-checked:bg-blue-600
                            after:content-['']
                            after:absolute after:top-[2px] after:left-[2px]
                            after:bg-white after:border-gray-300
                            after:border after:rounded-full after:h-5 after:w-5
                            after:transition-all
                            peer-checked:after:translate-x-full
                            peer-checked:after:border-white">
                        </div>
                    </label>
                </div>

                {{-- Penugasan --}}
                <div class="flex items-center justify-between gap-4">
                    <div>
                        <h3 class="font-medium text-gray-800">
                            Penugasan
                        </h3>
                        <p class="text-sm text-gray-500">
                            Notifikasi mengenai penugasan teknisi.
                        </p>
                    </div>

                    <label class="relative inline-flex items-center cursor-pointer">
                        <input
                            type="checkbox"
                            name="penugasan"
                            value="1"
                            class="sr-only peer"
                            {{ $setting->penugasan ? 'checked' : '' }}
                        >

                        <div class="w-11 h-6 bg-gray-200 rounded-full peer
                            peer-checked:bg-blue-600
                            after:content-['']
                            after:absolute after:top-[2px] after:left-[2px]
                            after:bg-white after:border-gray-300
                            after:border after:rounded-full after:h-5 after:w-5
                            after:transition-all
                            peer-checked:after:translate-x-full
                            peer-checked:after:border-white">
                        </div>
                    </label>
                </div>

            </div>

            <div class="mt-6 pt-5 border-t border-gray-200">
                <button
                    type="submit"
                    class="px-5 py-2.5 rounded-lg bg-blue-600 text-white text-sm font-medium hover:bg-blue-700">
                    Simpan Pengaturan
                </button>
            </div>
        </form>

    </div>
</div>
@endsection