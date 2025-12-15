const CACHE_NAME = 'offline-v1';

const FILES_TO_CACHE = [
    '/',
    '/offline.html',
];

const preLoad = function () {
    return caches.open(CACHE_NAME).then(function (cache) {
        // caching index and important routes
        return cache.addAll(FILES_TO_CACHE);
    });
};

self.addEventListener('install', function (event) {
    self.skipWaiting();
    event.waitUntil(preLoad());
});

self.addEventListener('activate', function (event) {
    event.waitUntil(
        caches.keys().then(function (keyList) {
            return Promise.all(
                keyList.map(function (key) {
                    if (key !== CACHE_NAME) {
                        return caches.delete(key);
                    }
                })
            );
        }).then(function () {
            return self.clients.claim();
        })
    );
});

const addToCache = function (request) {
    // Hanya cache request HTTP biasa
    if (!request.url.startsWith('http')) {
        return;
    }

    return caches.open(CACHE_NAME).then(function (cache) {
        return fetch(request).then(function (response) {
            // Jangan cache jika response invalid
            if (!response || response.status !== 200 || response.type === 'opaque') {
                return response;
            }
            cache.put(request, response.clone());
            return response;
        }).catch(function () {
            // Jika fetch gagal, biarkan saja (akan ditangani oleh fallback lain)
        });
    });
};

// Fallback hanya untuk navigasi halaman (bukan JS/CSS/asset lain)
self.addEventListener('fetch', function (event) {
    const request = event.request;

    // Navigasi halaman (user buka/refresh halaman)
    if (request.mode === 'navigate') {
        event.respondWith(
            fetch(request)
                .then(function (response) {
                    // Jika OK, simpan di cache dan kembalikan
                    return caches.open(CACHE_NAME).then(function (cache) {
                        cache.put(request, response.clone());
                        return response;
                    });
                })
                .catch(function () {
                    // Kalau offline / gagal, ambil dari cache, kalau tidak ada pakai offline.html
                    return caches.match(request).then(function (matching) {
                        if (matching) {
                            return matching;
                        }
                        return caches.match('/offline.html');
                    });
                })
        );
        return;
    }

    // Untuk request lain (JS, CSS, gambar, dll) gunakan network-first tanpa offline.html
    if (request.url.startsWith('http')) {
        event.respondWith(
            fetch(request)
                .then(function (response) {
                    // Simpan ke cache untuk pemanggilan berikutnya
                    return caches.open(CACHE_NAME).then(function (cache) {
                        cache.put(request, response.clone());
                        return response;
                    });
                })
                .catch(function () {
                    // Kalau network gagal, coba ambil dari cache saja
                    return caches.match(request);
                })
        );
    }
});
