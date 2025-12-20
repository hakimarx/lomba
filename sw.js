// Musabaqah Service Worker v1.0
const CACHE_NAME = 'musabaqah-v1.0';
const OFFLINE_URL = '/lomba/offline.html';

// Assets to cache immediately
const PRECACHE_ASSETS = [
    '/lomba/',
    '/lomba/musabaqah/',
    '/lomba/global/css/modern.css',
    '/lomba/global/css/menu.css',
    '/lomba/global/css/ku.css',
    '/lomba/global/js/ku.js',
    '/lomba/global/js/menu.js'
];

// Install event - cache core assets
self.addEventListener('install', (event) => {
    console.log('[SW] Installing Service Worker...');
    event.waitUntil(
        caches.open(CACHE_NAME)
            .then((cache) => {
                console.log('[SW] Caching app shell...');
                return cache.addAll(PRECACHE_ASSETS);
            })
            .then(() => {
                console.log('[SW] Skip waiting on install');
                return self.skipWaiting();
            })
            .catch((err) => {
                console.log('[SW] Cache failed:', err);
            })
    );
});

// Activate event - clean old caches
self.addEventListener('activate', (event) => {
    console.log('[SW] Activating Service Worker...');
    event.waitUntil(
        caches.keys().then((cacheNames) => {
            return Promise.all(
                cacheNames
                    .filter((cacheName) => cacheName !== CACHE_NAME)
                    .map((cacheName) => {
                        console.log('[SW] Deleting old cache:', cacheName);
                        return caches.delete(cacheName);
                    })
            );
        }).then(() => {
            console.log('[SW] Claiming clients');
            return self.clients.claim();
        })
    );
});

// Fetch event - network first, fallback to cache
self.addEventListener('fetch', (event) => {
    // Skip non-GET requests
    if (event.request.method !== 'GET') {
        return;
    }

    // Skip external requests
    if (!event.request.url.startsWith(self.location.origin)) {
        return;
    }

    // Skip PHP API requests (always fetch fresh)
    if (event.request.url.includes('.php') &&
        (event.request.url.includes('?') || event.request.headers.get('accept')?.includes('application/json'))) {
        return;
    }

    event.respondWith(
        // Try network first
        fetch(event.request)
            .then((response) => {
                // If valid response, cache it
                if (response && response.status === 200) {
                    const responseClone = response.clone();
                    caches.open(CACHE_NAME).then((cache) => {
                        cache.put(event.request, responseClone);
                    });
                }
                return response;
            })
            .catch(() => {
                // Network failed, try cache
                return caches.match(event.request)
                    .then((cachedResponse) => {
                        if (cachedResponse) {
                            return cachedResponse;
                        }
                        // If no cache and it's a navigation, show offline page
                        if (event.request.mode === 'navigate') {
                            return caches.match(OFFLINE_URL);
                        }
                        return new Response('Offline', {
                            status: 503,
                            statusText: 'Service Unavailable'
                        });
                    });
            })
    );
});

// Background sync for offline form submissions
self.addEventListener('sync', (event) => {
    if (event.tag === 'sync-forms') {
        console.log('[SW] Syncing forms...');
        // Future: implement form sync logic
    }
});

// Push notifications (future feature)
self.addEventListener('push', (event) => {
    if (event.data) {
        const data = event.data.json();
        const options = {
            body: data.body || 'Ada notifikasi baru',
            icon: '/lomba/assets/icons/icon-192x192.png',
            badge: '/lomba/assets/icons/icon-72x72.png',
            vibrate: [100, 50, 100],
            data: {
                dateOfArrival: Date.now(),
                primaryKey: 1
            }
        };

        event.waitUntil(
            self.registration.showNotification(data.title || 'Musabaqah', options)
        );
    }
});

console.log('[SW] Service Worker loaded');
