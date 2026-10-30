<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.1/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <title>@yield('title', 'MBULU')</title>
    <style>
        *, *::before, *::after { box-sizing: border-box; }
        body {
            font-family: 'Poppins', sans-serif;
            background: #FAFAF7;
            color: #1a1a18;
            margin: 0;
        }

        /* ── NAVBAR ── */
        .navbar {
            position: sticky;
            top: 0;
            z-index: 100;
            background:#f97316;
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(6, 53, 55, 0.08);
            padding: 0 40px;
            height: 64px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .nav-logo {
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
        }
        .nav-logo-mark {
            width: 36px;
            height: 36px;
            background: #063537;
            border-radius: 9px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .nav-logo-text {
            font-family: 'Poppins', sans-serif;
            font-size: 17px;
            font-weight: 800;
            color: #063537;
            letter-spacing: -0.02em;
        }

        .nav-links {
            display: flex;
            align-items: center;
            gap: 4px;
            list-style: none;
            margin: 0;
            padding: 0;
        }
        .nav-links a {
            font-family: 'Poppins', sans-serif;
            font-size: 15px;
            font-weight: 500;
            color: #4a4a45;
            text-decoration: none;
            padding: 6px 12px;
            border-radius: 5px;
            transition: all 0.15s;
            letter-spacing: 0.01em;
        }
        .nav-links a:hover,
        .nav-links a.active {
            background: #f0f4f4;
            color: #063537;
        }
        .nav-links a.active {
            font-weight: 600;
        }

        .nav-actions {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .btn-ghost {
            font-family: 'Poppins', sans-serif;
            font-size: 12.5px;
            font-weight: 600;
            color: #4a4a45;
            background: transparent;
            border: 1px solid #e0ddd8;
            border-radius: 8px;
            padding: 7px 16px;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.15s;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        .btn-ghost:hover {
            border-color: #063537;
            color: #063537;
            background: #f0f4f4;
        }

        .btn-primary {
            font-family: 'Poppins', sans-serif;
            font-size: 12.5px;
            font-weight: 600;
            color: white;
            background: #063537;
            border: 1px solid #063537;
            border-radius: 8px;
            padding: 7px 16px;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.15s;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        .btn-primary:hover {
            background: #f97316;
            border-color: #f97316;
        }

        .btn-danger {
            font-family: 'Poppins', sans-serif;
            font-size: 12.5px;
            font-weight: 600;
            color: #4a4a45;
            background: transparent;
            border: 1px solid #e0ddd8;
            border-radius: 8px;
            padding: 7px 16px;
            cursor: pointer;
            transition: all 0.15s;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        .btn-danger:hover {
            color: #dc2626;
            border-color: #fca5a5;
            background: #fef2f2;
        }

        /* Active nav detection helper */
        .nav-links a[data-route] { position: relative; }

        /* ── FOOTER ── */
        .site-footer {
            background: #063537;
            color: white;
            padding: 56px 40px 32px;
            margin-top: auto;
        }
        .footer-grid {
            display: grid;
            grid-template-columns: 1.6fr 1fr 1fr;
            gap: 48px;
            max-width: 1100px;
            margin: 0 auto 40px;
        }
        .footer-brand p {
            font-size: 13.5px;
            color: #ffffff;
            line-height: 1.7;
            margin: 12px 0 0;
            max-width: 280px;
            font-family: 'Poppins', sans-serif;
        }
        .footer-col-title {
            font-family: 'Poppins', sans-serif;
            font-size: 10.5px;
            font-weight: 700;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: rgba(255,255,255,0.3);
            margin-bottom: 16px;
        }
        .footer-links {
            list-style: none;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        .footer-links a {
            font-family: 'Poppins', sans-serif;
            font-size: 13px;
            color: rgba(255,255,255,0.55);
            text-decoration: none;
            transition: color 0.15s;
        }
        .footer-links a:hover { color: #f97316; }

        .footer-bottom {
            max-width: 1100px;
            margin: 0 auto;
            padding-top: 24px;
            border-top: 1px solid rgba(255,255,255,0.08);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .footer-bottom p {
            font-family: 'Poppins', sans-serif;
            font-size: 12px;
            color: rgba(255,255,255,0.25);
        }
        .footer-dots {
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .footer-dots span {
            width: 5px;
            height: 5px;
            border-radius: 50%;
            background: rgba(255,255,255,0.15);
        }
        .footer-dots span:nth-child(2) { background: #f97316; opacity: 0.7; }

        /* Mobile menu toggle */
        .hamburger {
            display: none;
            flex-direction: column;
            gap: 4px;
            cursor: pointer;
            padding: 6px;
            border-radius: 7px;
            background: transparent;
            border: none;
        }
        .hamburger span {
            width: 20px;
            height: 2px;
            background: #4a4a45;
            border-radius: 2px;
            transition: all 0.2s;
        }

        /* ── COMPOSANTS PARTAGÉS (carrousels, apparitions) ── */
        .no-scrollbar { scrollbar-width: none; }
        .no-scrollbar::-webkit-scrollbar { display: none; }

        .carousel-btn {
            width: 34px; height: 34px;
            align-items: center; justify-content: center;
            border-radius: 50%;
            border: 1px solid #e0ddd8;
            background: white;
            color: #063537;
            cursor: pointer;
            transition: all 0.15s;
        }
        .carousel-btn:hover:not(:disabled) { background: #063537; border-color: #063537; color: white; }
        .carousel-btn:disabled { opacity: 0.35; cursor: default; }

        /* ── LIVRE 3D : dos à gauche, tranche des pages et 4e de couverture en bas ──
           <div class="livre-3d">
               <div class="livre-3d__couverture …">…image… <span class="livre-3d__dos"></span></div>
               <span class="livre-3d__pages"></span>
           </div>
           Couleur de la 4e de couverture : --dos (teal par défaut). */
        .livre-3d {
            --dos: #063537;
            position: relative;
            filter: drop-shadow(0 6px 10px rgba(6, 53, 55, 0.18));
            transition: transform 0.3s ease, filter 0.3s ease;
        }
        .livre-3d.sur-fond-sombre { --dos: #0f5457; filter: drop-shadow(0 18px 26px rgba(0, 0, 0, 0.45)); }
        .group:hover .livre-3d.anime,
        .livre-3d.anime:hover {
            transform: translateY(-5px);
            filter: drop-shadow(0 16px 22px rgba(6, 53, 55, 0.28));
        }
        .livre-3d__couverture {
            position: relative;
            z-index: 1;
            overflow: hidden;
            border-radius: 2px 6px 6px 2px;
        }
        .livre-3d__dos {
            position: absolute;
            inset: 0 auto 0 0;
            width: 9%;
            z-index: 3;
            pointer-events: none;
            background: linear-gradient(90deg, rgba(0,0,0,0.42) 0%, rgba(0,0,0,0.18) 55%, rgba(255,255,255,0.28) 80%, rgba(0,0,0,0.22) 100%);
        }
        .livre-3d__dos::before,
        .livre-3d__dos::after {
            content: '';
            position: absolute;
            left: 0;
            width: 140%;
            height: 6%;
            border-top: 2px solid rgba(255,255,255,0.9);
            border-bottom: 2px solid rgba(255,255,255,0.9);
            border-radius: 0 100% 100% 0 / 0 50% 50% 0;
        }
        .livre-3d__dos::before { top: 6%; }
        .livre-3d__dos::after { bottom: 6%; }
        .livre-3d__pages {
            position: relative;
            display: block;
            margin: -4% 0 0 2%;
            padding-top: 9%;
            background: var(--dos);
            border-radius: 0 0 6px 7px;
        }
        .livre-3d__pages::before {
            content: '';
            position: absolute;
            left: 4%;
            right: 2.5%;
            top: 0;
            height: 62%;
            border-radius: 0 0 2px 2px;
            background: repeating-linear-gradient(180deg, #f4f6f7 0 1.5px, #d3dbe0 1.5px 3px);
        }

        .reveal { opacity: 0; transform: translateY(16px); transition: opacity 0.6s ease, transform 0.6s ease; }
        .reveal.is-visible { opacity: 1; transform: none; }

        @media (prefers-reduced-motion: reduce) {
            .reveal { opacity: 1; transform: none; transition: none; }
            .book-cover *, .scroll-smooth, .livre-3d { transition: none !important; scroll-behavior: auto !important; }
            .group:hover .livre-3d.anime, .livre-3d.anime:hover { transform: none; }
        }

        @media (max-width: 768px) {
            .navbar { padding: 0 20px; }
            .nav-links, .nav-actions { display: none; }
            .hamburger { display: flex; }
            .site-footer { padding: 40px 20px 24px; }
            .footer-grid { grid-template-columns: 1fr; gap: 32px; }
        }
    </style>
</head>

<body class="flex flex-col min-h-screen">

    <!-- ── NAVBAR ── -->
    <nav class="navbar">

        <!-- Logo -->
        <a href="{{ route('home') }}" class="nav-logo ">
            <div class="flex items-center space-x-2">
            <img src="{{ asset('Digital.png') }}" alt="Logo" class="w-12 h-12 rounded-full">
        </div>
        </a>

        <!-- Links -->
        <ul class="nav-links">
            <li>
                <a href="{{ route('home') }}"
                   class="{{ request()->routeIs('home') ? 'active' : '' }}">
                    Accueil
                </a>
            </li>
            <li>
                <a href="{{ route('livre.index') }}"
                   class="{{ request()->routeIs('livre.*') ? 'active' : '' }}">
                    Bibliothèque
                </a>
            </li>
            <li>
                <a href="{{ route('articles.index') }}"
                   class="{{ request()->routeIs('articles.*') ? 'active' : '' }}">
                    Blog
                </a>
            </li>
            <li>
                <a href="{{ route('contact') }}"
                   class="{{ request()->routeIs('contact') ? 'active' : '' }}">
                    Contact
                </a>
            </li>
            <li>
                <a href="{{ route('about') }}"
                   class="{{ request()->routeIs('about') ? 'active' : '' }}">
                    À propos
                </a>
            </li>
        </ul>

        <!-- Actions -->
        <div class="nav-actions">
            @auth
                @if(auth()->user()->is_admin)
                    <a href="{{ route('admin.dashboard') }}" class="btn-ghost">Administration</a>
                @endif
                <a href="{{ route('dashboard') }}" class="btn-ghost">
                    {{ auth()->user()->name }}
                </a>
                <form method="POST" action="{{ route('logout') }}" style="margin:0;">
                    @csrf
                    <button type="submit" class="btn-danger">
                        <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                            <path d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75"/>
                        </svg>
                        Déconnexion
                    </button>
                </form>
            @else
                <a href="{{ route('register') }}" class="btn-ghost">S'inscrire</a>
                <a href="{{ route('login') }}" class="btn-primary">
                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9"/>
                    </svg>
                    Se connecter
                </a>
            @endauth
        </div>

        <!-- Hamburger (mobile) -->
        <button class="hamburger" onclick="toggleMenu(this)" aria-label="Menu">
            <span></span>
            <span></span>
            <span></span>
        </button>

    </nav>

    <!-- ── CONTENT ── -->
    <main class="flex-grow">
        @yield('content')
    </main>

    <!-- ── FOOTER ── -->
    <footer class="site-footer">
        <div class="footer-grid">

            <!-- Brand -->
            <div class="footer-brand">
                <div style="display:flex; align-items:center; gap:10px;">
                    <div style="width:34px; height:34px; background:rgba(255,255,255,0.08);
                                border-radius:9px; display:flex; align-items:center; justify-content:center;">
                        <svg width="18" height="18" fill="none" stroke="white" stroke-width="1.8" viewBox="0 0 24 24">
                            <path d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25"/>
                        </svg>
                    </div>
                    <span style="font-family:'Poppins',sans-serif; font-size:17px; font-weight:800; color:white;">BIBLIO</span>
                </div>
                <p>La bibliothèque numérique du patrimoine gabonais. Littérature, culture et savoir d'Afrique centrale, accessible à tous.</p>

                <div style="display:flex; align-items:center; gap:6px; margin-top:20px;">
                    <span style="width:6px; height:6px; border-radius:50%; background:#f97316;"></span>
                    <span style="font-family:'Poppins',sans-serif; font-size:11px; color:rgba(255,255,255,0.3); letter-spacing:0.08em; text-transform:uppercase;">
                        Fondée à Libreville · Gabon
                    </span>
                </div>
            </div>

            <!-- Navigation -->
            <div>
                <p class="footer-col-title">Navigation</p>
                <ul class="footer-links">
                    <li><a href="{{ route('home') }}">Accueil</a></li>
                    <li><a href="{{ route('livre.index') }}">Bibliothèque</a></li>
                    <li><a href="{{ route('articles.index') }}">Blog</a></li>
                    <li><a href="{{ route('contact') }}">Contact</a></li>
                    <li><a href="{{ route('about') }}">À propos</a></li>
                </ul>
            </div>

            <!-- Compte -->
            <div>
                <p class="footer-col-title">Mon compte</p>
                <ul class="footer-links">
                    @auth
                        <li><a href="{{ route('dashboard') }}">Mon espace</a></li>
                        <li><a href="{{ route('espace.bibliotheque') }}">Ma bibliothèque</a></li>
                        @if(auth()->user()->is_admin)
                            <li><a href="{{ route('admin.dashboard') }}">Administration</a></li>
                        @endif
                        <li><a href="{{ route('logout') }}"
                               onclick="event.preventDefault(); document.getElementById('footer-logout').submit();">
                            Déconnexion
                        </a></li>
                        <form id="footer-logout" action="{{ route('logout') }}" method="POST" style="display:none;">@csrf</form>
                    @else
                        <li><a href="{{ route('register') }}">Créer un compte</a></li>
                        <li><a href="{{ route('login') }}">Se connecter</a></li>
                    @endauth
                </ul>
            </div>

        </div>

        <div class="footer-bottom">
            <p>&copy; {{ date('Y') }} BIBLIO. Tous droits réservés.</p>
            <div class="footer-dots">
                <span></span><span></span><span></span><span></span><span></span>
            </div>
        </div>
    </footer>

    <script>
    // Carrousels (flèches précédent / suivant) et apparition douce des sections.
    // Rappelable sur un fragment de page remplacé dynamiquement : window.initUi(element)
    window.initUi = function (root) {
        root = root || document;

        root.querySelectorAll('[data-carousel]').forEach(function (carousel) {
            const track = carousel.querySelector('[data-track]');
            const prev = carousel.querySelector('[data-prev]');
            const next = carousel.querySelector('[data-next]');
            if (!track || !prev || !next) return;

            function update() {
                prev.disabled = track.scrollLeft <= 4;
                next.disabled = track.scrollLeft + track.clientWidth >= track.scrollWidth - 4;
            }

            prev.addEventListener('click', () => track.scrollBy({ left: -track.clientWidth * 0.85 }));
            next.addEventListener('click', () => track.scrollBy({ left: track.clientWidth * 0.85 }));
            track.addEventListener('scroll', update, { passive: true });
            window.addEventListener('resize', update);
            update();
        });

        const reveals = root.querySelectorAll('.reveal:not(.is-visible)');
        if (!('IntersectionObserver' in window)) {
            reveals.forEach(el => el.classList.add('is-visible'));
            return;
        }
        const observer = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-visible');
                    observer.unobserve(entry.target);
                }
            });
        }, { rootMargin: '0px 0px -60px 0px' });
        reveals.forEach(el => observer.observe(el));
    };
    document.addEventListener('DOMContentLoaded', () => window.initUi());

    function toggleMenu(btn) {
        const links = document.querySelector('.nav-links');
        const actions = document.querySelector('.nav-actions');
        const open = links.style.display === 'flex';
        if (open) {
            links.style.display = '';
            actions.style.display = '';
        } else {
            links.style.cssText = 'display:flex;flex-direction:column;position:absolute;top:64px;left:0;right:0;background:rgba(250,250,247,0.98);backdrop-filter:blur(12px);padding:16px 20px;border-bottom:1px solid rgba(6,53,55,0.08);gap:4px;';
            actions.style.cssText = 'display:flex;flex-direction:column;position:absolute;top:64px;left:0;right:0;background:rgba(250,250,247,0.98);padding:0 20px 16px;border-bottom:1px solid rgba(6,53,55,0.08);gap:8px;margin-top:{{ request()->routeIs("home") ? "220px" : "220px" }}';
        }
    }
    </script>

</body>
</html>