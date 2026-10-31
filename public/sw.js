/*
 * Service worker de Biblio (PWA), avec mode hors connexion.
 *
 * - Pages HTML : réseau d'abord ; chaque page consultée est gardée en cache et resservie sans connexion
 *   (MAX_PAGES plus récentes). Jamais pour l'administration, l'authentification, le profil ni les formulaires.
 *   Sinon, page /offline.html, qui liste ce qui reste consultable.
 * - Livres (PDF sur Neon Object Storage) : cache d'abord, le lecteur les télécharge en entier pour qu'ils
 *   restent lisibles hors connexion (MAX_LIVRES plus récents).
 * - Fichiers statiques du site, bibliothèques CDN (Tailwind, Alpine, pdf.js) et polices : cache, mis à jour en arrière-plan.
 * - Couvertures et images sur Neon : cache d'abord (noms de fichiers uniques, jamais réécrits), MAX_IMAGES plus récentes.
 * - Connexion, inscription, déconnexion : pages et livres en cache effacés (ils dépendent de l'utilisateur).
 * - Autres requêtes que GET, requêtes partielles : non interceptées.
 *
 * Changer VERSION à chaque modification de ce fichier ou des fichiers précachés.
 */
const VERSION = 'v2';
const CACHE_STATIQUE = `biblio-statique-${VERSION}`;
const CACHE_IMAGES = 'biblio-images-v1';
const CACHE_PAGES = 'biblio-pages-v1';
const CACHE_LIVRES = 'biblio-livres-v1';
const MAX_IMAGES = 120;
const MAX_PAGES = 60;
const MAX_LIVRES = 15;

const PRECACHE = ['/offline.html', '/manifest.webmanifest', '/pwa-icons/icon-192.png', '/pwa-icons/icon-512.png', '/Digital.png'];
const CDN = ['cdn.jsdelivr.net', 'cdnjs.cloudflare.com', 'fonts.googleapis.com', 'fonts.gstatic.com'];

// Pages jamais gardées : personnelles et sensibles, ou formulaires dont le jeton CSRF serait périmé
const PAGES_EXCLUES = /^\/(admin|login|register|logout|forgot-password|reset-password|verify-email|confirm-password|email|profile|contribuer)(\/|$)/;
const CHANGEMENT_UTILISATEUR = ['/login', '/register', '/logout'];

self.addEventListener('install', (event) => {
    event.waitUntil(caches.open(CACHE_STATIQUE).then((cache) => cache.addAll(PRECACHE)).then(() => self.skipWaiting()));
});

self.addEventListener('activate', (event) => {
    const gardes = [CACHE_STATIQUE, CACHE_IMAGES, CACHE_PAGES, CACHE_LIVRES];
    event.waitUntil(
        caches.keys()
            .then((cles) => Promise.all(cles.filter((c) => c.startsWith('biblio-') && !gardes.includes(c)).map((c) => caches.delete(c))))
            .then(() => self.clients.claim())
    );
});

self.addEventListener('fetch', (event) => {
    const requete = event.request;
    const url = new URL(requete.url);
    const memeOrigine = url.origin === self.location.origin;

    if (requete.method === 'POST' && memeOrigine && CHANGEMENT_UTILISATEUR.includes(url.pathname)) {
        event.waitUntil(Promise.all([caches.delete(CACHE_PAGES), caches.delete(CACHE_LIVRES)]));
        return;
    }
    if (requete.method !== 'GET' || requete.headers.has('range')) return;

    if (requete.mode === 'navigate') {
        event.respondWith(page(requete, memeOrigine && !PAGES_EXCLUES.test(url.pathname)));
        return;
    }

    if ((memeOrigine && /\.(png|jpe?g|webp|svg|ico|css|js|woff2?|webmanifest)$/.test(url.pathname)) || CDN.includes(url.hostname)) {
        event.respondWith(cacheEtMiseAJour(requete, CACHE_STATIQUE));
        return;
    }

    if (url.hostname.endsWith('.neon.tech') && url.pathname.includes('/medias/')) {
        if (/\.pdf$/i.test(url.pathname)) event.respondWith(cacheDabord(requete, CACHE_LIVRES, MAX_LIVRES));
        else if (requete.destination === 'image') event.respondWith(cacheDabord(requete, CACHE_IMAGES, MAX_IMAGES));
    }
});

// Réseau d'abord ; la page est gardée (avec son titre, pour la liste de offline.html) et resservie sans connexion
async function page(requete, aGarder) {
    try {
        const reponse = await fetch(requete);
        const html = (reponse.headers.get('content-type') || '').includes('text/html');
        if (aGarder && reponse.status === 200 && !reponse.redirected && html) {
            const texte = await reponse.clone().text();
            const titre = (texte.match(/<title>([^<]*)<\/title>/i) || [])[1] || '';
            const entetes = new Headers(reponse.headers);
            entetes.set('X-Biblio-Titre', encodeURIComponent(titre.trim()));
            entetes.set('X-Biblio-Date', String(Date.now()));
            const cache = await caches.open(CACHE_PAGES);
            await cache.delete(cleDePage(requete.url)); // remise en fin de liste : la plus récente
            await cache.put(cleDePage(requete.url), new Response(texte, { status: 200, headers: entetes }));
            await limiter(cache, MAX_PAGES);
        }
        return reponse;
    } catch (erreur) {
        return (await caches.match(cleDePage(requete.url), { cacheName: CACHE_PAGES }))
            || (await caches.match('/offline.html'))
            || Response.error();
    }
}

// « /?source=pwa » (lancement depuis l'écran d'accueil) et « / » sont la même page
function cleDePage(adresse) {
    const url = new URL(adresse);
    url.searchParams.delete('source');
    url.hash = '';
    return url.href;
}

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

async function cacheDabord(requete, nomCache, max) {
    const cache = await caches.open(nomCache);
    const enCache = await cache.match(requete.url);
    if (enCache) return enCache;
    const reponse = await fetch(requete);
    if (reponse.status === 200 || reponse.type === 'opaque') {
        try {
            await cache.put(requete.url, reponse.clone());
            await limiter(cache, max);
        } catch (erreur) {
            // Espace de stockage plein, par exemple : le fichier est servi sans être gardé
            console.warn('Biblio : fichier non gardé hors connexion', requete.url, erreur);
        }
    }
    return reponse;
}

// Les entrées sont dans l'ordre d'ajout : on retire les plus anciennes
async function limiter(cache, max) {
    const cles = await cache.keys();
    for (const cle of cles.slice(0, Math.max(0, cles.length - max))) await cache.delete(cle);
}
