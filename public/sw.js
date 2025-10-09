// Clean City PWA Service Worker
// Version 1.0.0 - Comprehensive caching and offline support

const CACHE_NAME = 'clean-city-v1';
const OFFLINE_PAGE = '/offline.html';

// Static assets to cache immediately
const STATIC_CACHE_URLS = [
  '/',
  '/offline.html',
  '/css/app.css',
  '/js/app.js',
  '/images/logo.png',
  '/images/icons/icon-192x192.png',
  '/images/icons/icon-512x512.png',
  'https://cdn.tailwindcss.com',
  'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css',
  'https://fonts.googleapis.com/css2?family=Quicksand:wght@400;600&display=swap'
];

// Routes to cache dynamically (Runtime caching)
const DYNAMIC_CACHE_ROUTES = [
  '/resident/dashboard',
  '/resident/collections',
  '/resident/payments',
  '/resident/profile',
  '/login',
  '/register'
];

// Routes that require network (don't cache)
const NETWORK_ONLY_ROUTES = [
  '/api/',
  '/auth/',
  '/admin/',
  '/collector/',
  '/logout'
];

// Install Event - Cache static assets
self.addEventListener('install', (event) => {
  console.log('[ServiceWorker] Installing...');
  
  event.waitUntil(
    caches.open(CACHE_NAME)
      .then((cache) => {
        console.log('[ServiceWorker] Caching static assets');
        return cache.addAll(STATIC_CACHE_URLS);
      })
      .then(() => {
        console.log('[ServiceWorker] Installation complete');
        return self.skipWaiting(); // Activate immediately
      })
      .catch((error) => {
        console.error('[ServiceWorker] Installation failed:', error);
      })
  );
});

// Activate Event - Clean up old caches
self.addEventListener('activate', (event) => {
  console.log('[ServiceWorker] Activating...');
  
  event.waitUntil(
    caches.keys().then((cacheNames) => {
      return Promise.all(
        cacheNames.map((cacheName) => {
          if (cacheName !== CACHE_NAME) {
            console.log('[ServiceWorker] Deleting old cache:', cacheName);
            return caches.delete(cacheName);
          }
        })
      );
    }).then(() => {
      console.log('[ServiceWorker] Activation complete');
      return self.clients.claim(); // Take control immediately
    })
  );
});

// Fetch Event - Handle requests with caching strategy
self.addEventListener('fetch', (event) => {
  const { request } = event;
  const url = new URL(request.url);
  
  // Skip non-GET requests
  if (request.method !== 'GET') {
    return;
  }
  
  // Skip chrome-extension requests
  if (url.protocol === 'chrome-extension:') {
    return;
  }
  
  // Handle different types of requests
  if (isNetworkOnlyRoute(url.pathname)) {
    // Network only routes (API calls, auth, etc.)
    event.respondWith(
      fetch(request)
        .catch(() => {
          // If network fails, return offline page for navigation requests
          if (request.destination === 'document') {
            return caches.match(OFFLINE_PAGE);
          }
          return new Response('Network Error', { status: 503 });
        })
    );
  } else if (isStaticAsset(request.url)) {
    // Static assets - Cache first strategy
    event.respondWith(
      caches.match(request)
        .then((cachedResponse) => {
          if (cachedResponse) {
            return cachedResponse;
          }
          return fetch(request)
            .then((response) => {
              // Cache the response for future use
              if (response.status === 200) {
                const responseClone = response.clone();
                caches.open(CACHE_NAME)
                  .then((cache) => {
                    cache.put(request, responseClone);
                  });
              }
              return response;
            });
        })
        .catch(() => {
          // Return offline page for navigation requests
          if (request.destination === 'document') {
            return caches.match(OFFLINE_PAGE);
          }
          return new Response('Offline', { status: 503 });
        })
    );
  } else {
    // Dynamic content - Network first, fallback to cache
    event.respondWith(
      fetch(request)
        .then((response) => {
          // Cache successful responses for key routes
          if (response.status === 200 && isDynamicCacheRoute(url.pathname)) {
            const responseClone = response.clone();
            caches.open(CACHE_NAME)
              .then((cache) => {
                cache.put(request, responseClone);
              });
          }
          return response;
        })
        .catch(() => {
          // Try to serve from cache
          return caches.match(request)
            .then((cachedResponse) => {
              if (cachedResponse) {
                return cachedResponse;
              }
              // Return offline page for navigation requests
              if (request.destination === 'document') {
                return caches.match(OFFLINE_PAGE);
              }
              return new Response('Offline', { status: 503 });
            });
        })
    );
  }
});

// Background Sync - Handle form submissions when offline
self.addEventListener('sync', (event) => {
  console.log('[ServiceWorker] Background sync triggered:', event.tag);
  
  if (event.tag === 'background-sync-form') {
    event.waitUntil(
      syncFormData()
    );
  }
});

// Push Notification Handler
self.addEventListener('push', (event) => {
  console.log('[ServiceWorker] Push notification received');
  
  if (!event.data) {
    return;
  }
  
  const data = event.data.json();
  const options = {
    body: data.body || 'Clean City notification',
    icon: '/images/icons/icon-192x192.png',
    badge: '/images/icons/badge-72x72.png',
    image: data.image,
    tag: data.tag || 'clean-city-notification',
    data: data.data,
    actions: [
      {
        action: 'view',
        title: 'View',
        icon: '/images/icons/view-action.png'
      },
      {
        action: 'dismiss',
        title: 'Dismiss',
        icon: '/images/icons/dismiss-action.png'
      }
    ],
    requireInteraction: data.requireInteraction || false,
    silent: false
  };
  
  event.waitUntil(
    self.registration.showNotification(data.title || 'Clean City', options)
  );
});

// Notification Click Handler
self.addEventListener('notificationclick', (event) => {
  console.log('[ServiceWorker] Notification clicked:', event.notification.tag);
  
  event.notification.close();
  
  if (event.action === 'view') {
    // Open the app to a specific page
    const url = event.notification.data?.url || '/resident/dashboard';
    event.waitUntil(
      clients.openWindow(url)
    );
  } else if (event.action === 'dismiss') {
    // Just close the notification (already handled above)
    return;
  } else {
    // Default action - open the app
    event.waitUntil(
      clients.openWindow('/resident/dashboard')
    );
  }
});

// Message Handler - Communication with main thread
self.addEventListener('message', (event) => {
  console.log('[ServiceWorker] Message received:', event.data);
  
  if (event.data && event.data.type === 'SKIP_WAITING') {
    self.skipWaiting();
  }
  
  if (event.data && event.data.type === 'GET_VERSION') {
    event.ports[0].postMessage({ version: CACHE_NAME });
  }
  
  if (event.data && event.data.type === 'CACHE_URLS') {
    event.waitUntil(
      caches.open(CACHE_NAME)
        .then((cache) => {
          return cache.addAll(event.data.urls);
        })
        .then(() => {
          event.ports[0].postMessage({ success: true });
        })
        .catch((error) => {
          event.ports[0].postMessage({ success: false, error: error.message });
        })
    );
  }
});

// Helper Functions
function isNetworkOnlyRoute(pathname) {
  return NETWORK_ONLY_ROUTES.some(route => pathname.startsWith(route));
}

function isDynamicCacheRoute(pathname) {
  return DYNAMIC_CACHE_ROUTES.includes(pathname) || 
         DYNAMIC_CACHE_ROUTES.some(route => pathname.startsWith(route));
}

function isStaticAsset(url) {
  return url.includes('/css/') || 
         url.includes('/js/') || 
         url.includes('/images/') || 
         url.includes('/fonts/') ||
         url.includes('cdn.tailwindcss.com') ||
         url.includes('cdnjs.cloudflare.com') ||
         url.includes('fonts.googleapis.com');
}

// Background sync for form data
async function syncFormData() {
  try {
    // Get pending form submissions from IndexedDB
    const pendingForms = await getPendingFormSubmissions();
    
    for (const formData of pendingForms) {
      try {
        const response = await fetch(formData.url, {
          method: formData.method,
          headers: formData.headers,
          body: formData.body
        });
        
        if (response.ok) {
          // Remove from pending submissions
          await removePendingFormSubmission(formData.id);
          console.log('[ServiceWorker] Form synced successfully:', formData.id);
        }
      } catch (error) {
        console.error('[ServiceWorker] Form sync failed:', error);
        // Will retry on next sync event
      }
    }
  } catch (error) {
    console.error('[ServiceWorker] Background sync error:', error);
  }
}

// IndexedDB helpers for offline form storage
async function getPendingFormSubmissions() {
  // Implementation would use IndexedDB to store offline form submissions
  // Returning empty array for now - can be expanded based on needs
  return [];
}

async function removePendingFormSubmission(id) {
  // Implementation would remove the form submission from IndexedDB
  console.log('[ServiceWorker] Removing pending form:', id);
}

// Error Handler
self.addEventListener('error', (event) => {
  console.error('[ServiceWorker] Error:', event.error);
});

console.log('[ServiceWorker] Service Worker loaded successfully');