@extends('layouts.app')

@section('content')
<div class="space-y-6">

    {{-- HEADER --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">

        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-gray-900">
                Notifikasi
            </h1>

            <p class="text-gray-600 mt-1">
                Informasi dan aktivitas terbaru akun Anda.
            </p>
        </div>

        @if($jumlahBelumDibaca > 0)
            <form
                action="{{ url('/notifikasi/baca-semua') }}"
                method="POST"
            >
                @csrf

                <button
                    type="submit"
                    class="inline-flex items-center gap-2 px-4 py-2.5 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700 transition"
                >
                    <i data-lucide="check-check" class="w-4 h-4"></i>
                    Tandai Semua Dibaca
                </button>
            </form>
        @endif

    </div>

    {{-- PESAN --}}
    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 rounded-lg px-4 py-3">
            {{ session('success') }}
        </div>
    @endif

    {{-- DAFTAR --}}
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">

        @forelse($notifikasi as $item)

            <a
                href="{{ url('/notifikasi/'.$item->id) }}"
                class="block px-5 py-4 border-b border-gray-200 hover:bg-gray-50 transition {{ !$item->dibaca ? 'bg-blue-50' : '' }}"
            >

                <div class="flex gap-4">

                    {{-- ICON --}}
                    <div class="w-11 h-11 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center shrink-0">
                        <i data-lucide="bell" class="w-5 h-5"></i>
                    </div>

                    {{-- ISI --}}
                    <div class="flex-1 min-w-0">

                        <div class="flex items-start justify-between gap-4">

                            <div class="min-w-0">

                                <p class="font-semibold text-gray-800">
                                    {{ $item->judul }}
                                </p>

                                <p class="text-sm text-gray-600 mt-1">
                                    {{ $item->pesan }}
                                </p>

                            </div>

                            @if(!$item->dibaca)
                                <span class="w-2.5 h-2.5 bg-blue-600 rounded-full mt-2 shrink-0"></span>
                            @endif

                        </div>

                        <div class="flex items-center gap-2 mt-2 text-xs text-gray-400">
                            <i data-lucide="clock-3" class="w-3.5 h-3.5"></i>

                            <span>
                                {{ $item->created_at?->diffForHumans() }}
                            </span>
                        </div>

                    </div>

                </div>

            </a>

        @empty

            {{-- KOSONG --}}
            <div class="py-16 px-6 text-center">

                <div class="w-16 h-16 mx-auto rounded-full bg-gray-100 text-gray-400 flex items-center justify-center">
                    <i data-lucide="bell-off" class="w-8 h-8"></i>
                </div>

                <h3 class="mt-4 font-semibold text-gray-800">
                    Belum Ada Notifikasi
                </h3>

                <p class="text-sm text-gray-500 mt-1">
                    Notifikasi baru akan muncul di halaman ini.
                </p>

            </div>

        @endforelse

    </div>

    {{-- PAGINATION --}}
    @if($notifikasi->hasPages())
        <div>
            {{ $notifikasi->links() }}
        </div>
    @endif

</div>

<script>
if (window.lucide) {
    lucide.createIcons();
}
</script>
@endsection