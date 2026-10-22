<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Customer Helpdesk System</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-100 overflow-x-hidden">

<div class="flex min-h-screen w-full">

    {{-- SIDEBAR --}}

@if(auth()->check())

    @if(auth()->user()->role == 'admin')

        @include('layouts.sidebar-admin')


    @elseif(auth()->user()->role == 'teknisi')

        @include('layouts.sidebar-teknisi')


    @elseif(auth()->user()->role == 'pelanggan')

        @include('layouts.sidebar-pelanggan')


    @else

        @include('layouts.sidebar-admin')

    @endif


@else

    @include('layouts.sidebar-admin')

@endif


    {{-- OVERLAY MOBILE --}}
    <div
        id="overlay"
        onclick="toggleSidebar()"
        class="hidden fixed inset-0 bg-black/40 z-40 md:hidden">
    </div>


    {{-- AREA UTAMA --}}
    <div class="flex-1 min-w-0 w-full flex flex-col min-h-screen">

        @include('layouts.navbar')


        <main class="flex-1 w-full min-w-0 p-3 sm:p-4 md:p-6">
            @yield('content')
        </main>


        {{-- FOOTER --}}
        <footer class="w-full bg-white border-t border-gray-200 px-4 py-4 text-center">
            <p class="text-xs sm:text-sm text-gray-400">
                © {{ date('Y') }} Alfa Net. All Rights Reserved.
            </p>
        </footer>

    </div>

</div>


{{-- LIBRARY --}}
<script src="https://unpkg.com/lucide@latest"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


<script>
function toggleSidebar() {

    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('overlay');

    if (!sidebar || !overlay) {
        return;
    }

    sidebar.classList.toggle('-translate-x-full');
    overlay.classList.toggle('hidden');
}


document.addEventListener('DOMContentLoaded', function () {

    if (window.lucide) {
        lucide.createIcons();
    }

});
</script>


{{-- PUSH NOTIFICATION --}}
@if(auth()->check())

<script>

let pushRegistration = null;


/*
|--------------------------------------------------------------------------
| SERVICE WORKER
|--------------------------------------------------------------------------
*/

async function registerPushServiceWorker() {

    if (!('serviceWorker' in navigator)) {
        return null;
    }

    if (!('PushManager' in window)) {
        return null;
    }

    try {

        pushRegistration =
            await navigator.serviceWorker.register('/sw.js');

        console.log('Service Worker berhasil terdaftar.');

        return pushRegistration;

    } catch (error) {

        console.error(
            'Service Worker gagal:',
            error
        );

        return null;
    }
}


/*
|--------------------------------------------------------------------------
| VAPID KEY
|--------------------------------------------------------------------------
*/

function urlBase64ToUint8Array(base64String) {

    const padding =
        '='.repeat(
            (4 - base64String.length % 4) % 4
        );

    const base64 =
        (base64String + padding)
        .replace(/-/g, '+')
        .replace(/_/g, '/');

    const rawData =
        window.atob(base64);

    const outputArray =
        new Uint8Array(rawData.length);

    for (
        let i = 0;
        i < rawData.length;
        i++
    ) {

        outputArray[i] =
            rawData.charCodeAt(i);

    }

    return outputArray;
}


/*
|--------------------------------------------------------------------------
| SIMPAN SUBSCRIPTION
|--------------------------------------------------------------------------
*/

async function savePushSubscription(subscription) {

    try {

        const response =
            await fetch('{{ url('/push/subscribe') }}', {

                method: 'POST',

                headers: {

                    'Content-Type':
                        'application/json',

                    'X-CSRF-TOKEN':
                        document
                        .querySelector(
                            'meta[name="csrf-token"]'
                        )
                        .getAttribute('content'),

                    'Accept':
                        'application/json'
                },

                body:
                    JSON.stringify(
                        subscription.toJSON()
                    )
            });


        if (!response.ok) {

            throw new Error(
                'Gagal menyimpan subscription.'
            );

        }

        console.log(
            'Push subscription berhasil disimpan.'
        );

    } catch (error) {

        console.error(
            'Gagal menyimpan push subscription:',
            error
        );

    }
}


/*
|--------------------------------------------------------------------------
| AKTIFKAN PUSH
|--------------------------------------------------------------------------
*/

async function enablePushNotification() {

    if (!('Notification' in window)) {

        alert(
            'Browser ini tidak mendukung notifikasi.'
        );

        return;
    }


    if (!('PushManager' in window)) {

        alert(
            'Browser ini tidak mendukung Push Notification.'
        );

        return;
    }


    const vapidPublicKey =
        @json(config('webpush.public_key'));


    if (!vapidPublicKey) {

        alert(
            'VAPID Public Key belum diatur.'
        );

        return;
    }


    try {

        const registration =
            pushRegistration ||
            await registerPushServiceWorker();


        if (!registration) {
            return;
        }


        let permission =
            Notification.permission;


        if (permission === 'default') {

            permission =
                await Notification.requestPermission();

        }


        if (permission !== 'granted') {

            alert(
                'Izin notifikasi belum diberikan.'
            );

            return;
        }


        let subscription =
            await registration.pushManager.getSubscription();


        if (!subscription) {

            subscription =
                await registration.pushManager.subscribe({

                    userVisibleOnly: true,

                    applicationServerKey:
                        urlBase64ToUint8Array(
                            vapidPublicKey
                        )
                });

        }


        await savePushSubscription(
            subscription
        );


        alert(
            'Notifikasi berhasil diaktifkan.'
        );


    } catch (error) {

        console.error(
            'Push notification error:',
            error
        );

        alert(
            'Notifikasi gagal diaktifkan. Cek Console.'
        );

    }

}


/*
|--------------------------------------------------------------------------
| REGISTER SERVICE WORKER
|--------------------------------------------------------------------------
*/

document.addEventListener(
    'DOMContentLoaded',
    async function () {

        const registration =
            await registerPushServiceWorker();


        if (!registration) {
            return;
        }


        /*
        Jika izin sebelumnya sudah diberikan,
        langsung simpan subscription.
        */

        if (
            'Notification' in window &&
            Notification.permission === 'granted'
        ) {

            try {

                const subscription =
                    await registration
                    .pushManager
                    .getSubscription();


                if (subscription) {

                    await savePushSubscription(
                        subscription
                    );

                }

            } catch (error) {

                console.error(
                    'Subscription error:',
                    error
                );

            }

        }

    }
);

</script>

@endif

</body>
</html>