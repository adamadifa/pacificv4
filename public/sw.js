// Service worker minimal tanpa mekanisme cache,
// supaya tidak mengganggu load resource (termasuk jQuery dari CDN).

self.addEventListener('install', function (event) {
    // Langsung aktifkan SW baru
    self.skipWaiting();
});

self.addEventListener('activate', function (event) {
    // Klaim semua client, tapi tanpa menyentuh Cache API
    event.waitUntil(self.clients.claim());
});

// Tidak ada event 'fetch' di sini, jadi SW tidak akan
// meng-intercept request apa pun (termasuk CDN).
