self.addEventListener('push', function (event) {
    if (!event.data) return;

    const data = event.data.json();

    const options = {
        body: data.body || '',
        icon: '/images/logo.png',
        badge: '/images/logo.png',
        data: {
            url: data.url || '/',
            type: data.type || 'umum'
        },
        requireInteraction: false
    };

    event.waitUntil(
        self.registration.showNotification(
            data.title || 'Alfa Net',
            options
        )
    );
});

self.addEventListener('notificationclick', function (event) {
    event.notification.close();

    const url = event.notification.data?.url || '/';

    event.waitUntil(
        clients.matchAll({
            type: 'window',
            includeUncontrolled: true
        }).then(function (clientList) {
            for (const client of clientList) {
                if ('focus' in client) {
                    client.navigate(url);
                    return client.focus();
                }
            }

            if (clients.openWindow) {
                return clients.openWindow(url);
            }
        })
    );
});