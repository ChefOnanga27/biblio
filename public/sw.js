/*
 * Service worker de Biblio (PWA).
 *
 * - Pages HTML : toujours le réseau (elles dépendent de l'utilisateur connecté, jamais mises en cache),
 *   page /offline.html en secours sans connexion.
 * - Fichiers statiques du site, bibliothèques CDN (Tailwind, Alpine) et polices : cache, mis à jour en arrière-plan.
 * - Couvertures et images sur Neon Object Storage : cache d'abord (noms de fichiers uniques, jamais réécrits),
 *   limité aux MAX_IMAGES plus récentes.
 * - Requêtes autres que GET, requêtes partielles (vidéo, PDF) : non interceptées.
 *
 * Changer VERSION à chaque modification de ce fichier ou des fichiers précachés.
 */
const VERSION = 'v1';
const CACHE_STATIQUE = `biblio-statique-${VERSION}`;
const CACHE_IMAGES = `biblio-images-${VERSION}`;
const MAX_IMAGES = 120;

const PRECACHE = ['/offline.html', '/manifest.webmanifest', '/pwa-icons/icon-192.png', '/pwa-icons/icon-512.png', '/Digital.png'];
const CDN = ['cdn.jsdelivr.net', 'cdnjs.cloudflare.com', 'fonts.googleapis.com', 'fonts.gstatic.com'];

self.addEventListener('install', (event) => {
    event.waitUntil(caches.open(CACHE_STATIQUE).then((cache) => cache.addAll(PRECACHE)).then(() => self.skipWaiting()));
});

self.addEventListener('activate', (event) => {
    event.waitUntil(
        caches.keys()
            .then((cles) => Promise.all(cles.filter((c) => c.startsWith('biblio-') && ![CACHE_STATIQUE, CACHE_IMAGES].includes(c)).map((c) => caches.delete(c))))
            .then(() => self.clients.claim())
    );
});

self.addEventListener('fetch', (event) => {
    const requete = event.request;
    if (requete.method !== 'GET' || requete.headers.has('range')) return;

    const url = new URL(requete.url);

    if (requete.mode === 'navigate') {
        event.respondWith(fetch(requete).catch(() => caches.match('/offline.html')));
        return;
    }

    const memeOrigine = url.origin === self.location.origin;
    if ((memeOrigine && /\.(png|jpe?g|webp|svg|ico|css|js|woff2?|webmanifest)$/.test(url.pathname)) || CDN.includes(url.hostname)) {
        event.respondWith(cacheEtMiseAJour(requete, CACHE_STATIQUE));
        return;
    }

    if (url.hostname.endsWith('.neon.tech') && url.pathname.includes('/medias/') && requete.destination === 'image') {
        event.respondWith(cacheDabord(requete));
    }
});

// Réponse du cache tout de suite si elle existe, mise à jour depuis le réseau en arrière-plan
async function cacheEtMiseAJour(requete, nomCache) {
    const cache = await caches.open(nomCache);
    const enCache = await cache.match(requete);
    const reseau = fetch(requete)
        .then((reponse) => {
            if (reponse.ok || reponse.type === 'opaque') cache.put(requete, reponse.clone());
            return reponse;
        })
        .catch(() => enCache || Response.error());
    return enCache || reseau;
}

async function cacheDabord(requete) {
    const cache = await caches.open(CACHE_IMAGES);
    const enCache = await cache.match(requete);
    if (enCache) return enCache;
    const reponse = await fetch(requete);
    if (reponse.ok || reponse.type === 'opaque') {
        await cache.put(requete, reponse.clone());
        const cles = await cache.keys();
        for (const cle of cles.slice(0, Math.max(0, cles.length - MAX_IMAGES))) await cache.delete(cle);
    }
    return reponse;
}
