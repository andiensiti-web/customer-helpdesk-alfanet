<header class="bg-white shadow-sm px-4 md:px-6 py-4 flex items-center justify-between relative">

    {{-- MENU MOBILE --}}
    <button
        type="button"
        onclick="toggleSidebar()"
        class="md:hidden text-gray-700"
    >
        <i data-lucide="menu" class="w-6 h-6"></i>
    </button>


    {{-- JUDUL --}}
    <div>

        <h2 class="text-lg md:text-xl font-bold text-gray-800">
            Customer Helpdesk System
        </h2>

        <p class="hidden md:block text-sm text-gray-500">

            @if(auth()->user()->role === 'teknisi')

                Pengelolaan Laporan & Pekerjaan Teknisi

            @elseif(auth()->user()->role === 'pelanggan')

                Layanan Pelanggan & Laporan Gangguan

            @else

                Pengelolaan Pelanggan & Layanan Gangguan

            @endif

        </p>

    </div>


    {{-- KANAN --}}
    <div class="flex items-center gap-2 md:gap-3">


        {{-- NOTIFIKASI --}}
<div class="relative">

    <button
        type="button"
        onclick="toggleNotifMenu()"
        class="relative w-10 h-10 rounded-lg flex items-center justify-center text-gray-600 hover:bg-gray-100 transition"
    >

        <i
            data-lucide="bell"
            class="w-5 h-5"
        ></i>


       

    </button>



    {{-- LIST NOTIFIKASI --}}
    <div
        id="notifMenu"
        class="hidden absolute right-0 mt-3 w-80 bg-white rounded-xl shadow-lg border border-gray-200 z-50"
    >

        <div class="px-4 py-3 border-b font-semibold">
            Notifikasi
        </div>



        @forelse($notifikasi ?? [] as $notif)

            <a
                href="{{ url('/notifikasi/'.$notif->id) }}"
                class="block px-4 py-3 hover:bg-gray-50 border-b"
            >

                <p class="font-semibold text-sm text-gray-800">
                    {{ $notif->judul }}
                </p>


                <p class="text-xs text-gray-500">
                    {{ $notif->pesan }}
                </p>


            </a>


        @empty

            <div class="px-4 py-4 text-sm text-gray-500">
                Belum ada notifikasi.
            </div>

        @endforelse


    </div>

</div>

        {{-- PROFILE --}}
        <div class="relative">

            <button
                type="button"
                onclick="toggleProfileMenu()"
                class="flex items-center gap-3 hover:bg-gray-100 rounded-lg px-2 py-1.5 transition"
            >

                {{-- NAMA --}}
                <div class="text-right hidden sm:block">

                    <p class="font-semibold text-gray-800">
                        {{ auth()->user()->name }}
                    </p>

                    <p class="text-sm text-gray-500">

                        @if(auth()->user()->role === 'admin')

                            Administrator

                        @elseif(auth()->user()->role === 'teknisi')

                            Teknisi Lapangan

                        @else

                            Pelanggan

                        @endif

                    </p>

                </div>


                {{-- AVATAR --}}
                <div class="w-10 h-10 rounded-full bg-blue-700 text-white flex items-center justify-center font-semibold">

                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}

                </div>


                <i
                    data-lucide="chevron-down"
                    class="w-4 h-4 text-gray-500"
                ></i>

            </button>


            {{-- DROPDOWN --}}
            <div
                id="profileMenu"
                class="hidden absolute right-0 mt-2 w-56 bg-white border border-gray-200 rounded-xl shadow-lg z-50 overflow-hidden"
            >

                {{-- INFO USER --}}
                <div class="px-4 py-4 border-b border-gray-200">

                    <div class="flex items-center gap-3">

                        <div class="w-10 h-10 rounded-full bg-blue-700 text-white flex items-center justify-center font-semibold">

                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}

                        </div>

                        <div class="min-w-0">

                            <p class="font-semibold text-gray-800 truncate">
                                {{ auth()->user()->name }}
                            </p>

                            <p class="text-xs text-gray-500 truncate">
                                {{ auth()->user()->email }}
                            </p>

                        </div>

                    </div>


                    {{-- ROLE --}}
                    <div class="mt-3">

                        @if(auth()->user()->role === 'admin')

                            <span class="inline-flex items-center gap-1 bg-blue-100 text-blue-700 px-2.5 py-1 rounded-full text-xs">

                                <i
                                    data-lucide="shield-check"
                                    class="w-3.5 h-3.5"
                                ></i>

                                Administrator

                            </span>

                        @elseif(auth()->user()->role === 'teknisi')

                            <span class="inline-flex items-center gap-1 bg-green-100 text-green-700 px-2.5 py-1 rounded-full text-xs">

                                <i
                                    data-lucide="wrench"
                                    class="w-3.5 h-3.5"
                                ></i>

                                Teknisi

                            </span>

                        @else

                            <span class="inline-flex items-center gap-1 bg-purple-100 text-purple-700 px-2.5 py-1 rounded-full text-xs">

                                <i
                                    data-lucide="user"
                                    class="w-3.5 h-3.5"
                                ></i>

                                Pelanggan

                            </span>

                        @endif

                    </div>

                </div>


                {{-- PENGATURAN ADMIN --}}
                @if(auth()->user()->role === 'admin')

                    <a
                        href="{{ url('/pengaturan') }}"
                        class="flex items-center gap-3 px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 transition"
                    >

                        <i
                            data-lucide="settings"
                            class="w-5 h-5 text-gray-500"
                        ></i>

                        <span>
                            Pengaturan
                        </span>

                    </a>


                {{-- PENGATURAN TEKNISI --}}
                @elseif(auth()->user()->role === 'teknisi')

                    <a
                        href="{{ url('/pengaturan-teknisi') }}"
                        class="flex items-center gap-3 px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 transition"
                    >

                        <i
                            data-lucide="settings"
                            class="w-5 h-5 text-gray-500"
                        ></i>

                        <span>
                            Pengaturan
                        </span>

                    </a>


                {{-- PENGATURAN PELANGGAN --}}
                @else

                    <a
                        href="{{ url('/pengaturan-pelanggan') }}"
                        class="flex items-center gap-3 px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 transition"
                    >

                        <i
                            data-lucide="settings"
                            class="w-5 h-5 text-gray-500"
                        ></i>

                        <span>
                            Pengaturan
                        </span>

                    </a>

                @endif


                {{-- LOGOUT --}}
                <div class="border-t border-gray-200">

                    <form
                        action="{{ url('/logout') }}"
                        method="POST"
                    >

                        @csrf

                        <button
                            type="submit"
                            onclick="return confirm('Apakah Anda yakin ingin logout?')"
                            class="w-full flex items-center gap-3 px-4 py-3 text-sm text-red-600 hover:bg-red-50 transition"
                        >

                            <i
                                data-lucide="log-out"
                                class="w-5 h-5"
                            ></i>

                            <span>
                                Logout
                            </span>

                        </button>

                    </form>

                </div>

            </div>

        </div>

    </div>

</header>


<script>

function toggleProfileMenu() {

    const menu =
        document.getElementById('profileMenu');

    if (!menu) {
        return;
    }

    menu.classList.toggle('hidden');
}

function toggleNotifMenu() {

    const menu = document.getElementById('notifMenu');

    if (!menu) {
        return;
    }

    menu.classList.toggle('hidden');
}


document.addEventListener(
    'click',
    function (event) {

        const menu =
            document.getElementById('profileMenu');

        if (!menu) {
            return;
        }


        const profileButton =
            event.target.closest(
                'button[onclick="toggleProfileMenu()"]'
            );


        if (
            !menu.contains(event.target) &&
            !profileButton
        ) {

            menu.classList.add('hidden');

        }

    }
);


if (window.lucide) {

    lucide.createIcons();

}

</script>