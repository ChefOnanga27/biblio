{{-- Application installable (PWA) : manifeste, icônes et service worker (public/sw.js). --}}
<link rel="manifest" href="{{ asset('manifest.webmanifest') }}">
<meta name="theme-color" content="#070b14">
<link rel="apple-touch-icon" href="{{ asset('pwa-icons/apple-touch-icon.png') }}">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
<meta name="apple-mobile-web-app-title" content="{{ $siteNom ?? 'Biblio' }}">
<script>
    if ('serviceWorker' in navigator) {
        window.addEventListener('load', () => navigator.serviceWorker.register('/sw.js', { scope: '/' }).catch(() => {}));
    }

    // Bandeau affiché sans connexion : la page vient alors du cache de l'appareil
    document.addEventListener('DOMContentLoaded', function () {
        const bandeau = document.createElement('div');
        bandeau.setAttribute('role', 'status');
        bandeau.textContent = 'Hors connexion : vous consultez la version enregistrée sur cet appareil.';
        bandeau.style.cssText = 'position:fixed;left:50%;bottom:16px;transform:translateX(-50%);z-index:9999;width:max-content;max-width:calc(100% - 32px);'
            + 'padding:10px 16px;border-radius:999px;background:#1a2134;color:#e8eaf0;border:1px solid rgba(240,113,85,.5);'
            + 'font:500 13px/1.4 ui-sans-serif,system-ui,sans-serif;box-shadow:0 10px 30px rgba(0,0,0,.4);text-align:center;';
        const actualiser = () => { bandeau.style.display = navigator.onLine ? 'none' : 'block'; };
        actualiser();
        document.body.appendChild(bandeau);
        window.addEventListener('online', actualiser);
        window.addEventListener('offline', actualiser);
    });
</script>
