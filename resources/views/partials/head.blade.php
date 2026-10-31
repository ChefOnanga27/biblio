    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', $siteNom)</title>
    <link rel="icon" href="{{ asset('Digital.png') }}">
    @include('partials.pwa')
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.1/dist/cdn.min.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:ital,wght@0,400;0,500;0,600;0,700;1,400&family=Jost:wght@400;500;600&display=swap" rel="stylesheet">

    <style type="text/tailwindcss">
        @theme {
            --font-sans: 'IBM Plex Sans', ui-sans-serif, system-ui, sans-serif;
            --font-logo: 'Jost', 'IBM Plex Sans', sans-serif;

            --color-nuit: #070b14;        /* fond */
            --color-surface: #121829;     /* cartes */
            --color-surface-2: #1a2134;   /* pastilles, barre du haut */
            --color-champ: #232a3d;       /* champs de formulaire */
            --color-ligne: rgb(255 255 255 / 0.07);
            --color-encre: #e8eaf0;       /* texte principal */
            --color-brume: #a3abbd;       /* texte secondaire */
            --color-voile: #6b7386;       /* texte discret */
            --color-corail: #f07155;      /* accent */
            --color-corail-fonce: #dc5a3e;
            --color-menthe: #3fbf6a;
            --color-sable: #ead2b0;
        }
    </style>

    <style>
        /* Ordre des couches de Tailwind : les composants ci-dessous sont dans « components », donc les utilitaires
           (hidden, sm:inline-flex, !h-12…) l'emportent sur eux, comme prévu par Tailwind. */
        @layer theme, base, components, utilities;

        body {
            margin: 0;
            min-height: 100dvh;
            color: #e8eaf0;
            background:
                radial-gradient(ellipse 70% 50% at 15% -10%, rgba(40, 60, 105, 0.55), transparent 60%),
                linear-gradient(180deg, #0b1222 0%, #070b14 45%, #05070d 100%) fixed;
            -webkit-font-smoothing: antialiased;
        }
        /* Grain discret, comme sur les maquettes */
        body::before {
            content: '';
            position: fixed;
            inset: 0;
            pointer-events: none;
            z-index: 0;
            opacity: 0.07;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='160' height='160'%3E%3Cfilter id='g'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='2' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23g)'/%3E%3C/svg%3E");
        }
        [x-cloak] { display: none !important; }
        ::selection { background: rgba(240, 113, 85, 0.35); }

        @layer components {
        /* ── Boutons ── */
        .btn { display: inline-flex; align-items: center; justify-content: center; gap: 8px; border-radius: 10px;
               padding: 11px 20px; font-size: 14px; font-weight: 600; line-height: 1; cursor: pointer;
               transition: background .15s, color .15s, border-color .15s, transform .15s; border: 1px solid transparent; text-decoration: none; }
        .btn:active { transform: scale(0.98); }
        .btn:disabled { opacity: .5; cursor: default; }
        .btn-blanc { background: #f7f5f2; color: #10131c; }
        .btn-blanc:hover { background: #ffffff; }
        .btn-corail { background: #f07155; color: #fff; }
        .btn-corail:hover { background: #dc5a3e; }
        .btn-contour { border-color: #f07155; color: #f07155; background: transparent; }
        .btn-contour:hover { background: rgba(240, 113, 85, 0.1); }
        .btn-discret { border-color: rgba(255,255,255,.12); color: #e8eaf0; background: transparent; }
        .btn-discret:hover { background: rgba(255,255,255,.06); }
        .btn-sm { padding: 8px 14px; font-size: 12.5px; border-radius: 8px; }

        /* ── Champs ── */
        .champ { width: 100%; background: #232a3d; border: 1px solid transparent; border-radius: 8px; color: #e8eaf0;
                 padding: 13px 16px; font-size: 14px; outline: none; transition: border-color .15s, background .15s; }
        .champ::placeholder { color: #6b7386; }
        .champ:focus { border-color: rgba(240, 113, 85, 0.7); background: #262e43; }
        .champ-label { display: block; font-size: 13.5px; color: #c9cedb; margin-bottom: 8px; }

        /* ── Barre latérale ── */
        .nav-item { display: flex; align-items: center; gap: 14px; padding: 11px 14px; border-radius: 10px; font-size: 15.5px;
                    color: #c9cedb; transition: color .15s, background .15s; }
        .nav-item:hover { color: #fff; background: rgba(255,255,255,.04); }
        .nav-item[aria-current="page"] { color: #f07155; background: rgba(240, 113, 85, 0.08); }
        .nav-item svg { flex: none; opacity: .9; }

        /* ── Pastilles de la barre du haut ── */
        .pastille { display: flex; align-items: center; height: 48px; border-radius: 999px; background: #1a2134;
                    border: 1px solid rgba(255,255,255,.05); }

        /* ── Carrousels & apparitions ── */
        .no-scrollbar { scrollbar-width: none; }
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .carousel-btn { width: 34px; height: 34px; align-items: center; justify-content: center; border-radius: 50%;
                        border: 1px solid rgba(255,255,255,.12); background: transparent; color: #e8eaf0; cursor: pointer; transition: all .15s; }
        .carousel-btn:hover:not(:disabled) { background: #f07155; border-color: #f07155; color: #fff; }
        .carousel-btn:disabled { opacity: .3; cursor: default; }
        .reveal { opacity: 0; transform: translateY(14px); transition: opacity .6s ease, transform .6s ease; }
        .reveal.is-visible { opacity: 1; transform: none; }

        /* ── LIVRE 3D : dos à gauche, tranche des pages et 4e de couverture en bas ──
           <div class="livre-3d"> <div class="livre-3d__couverture">…<span class="livre-3d__dos"></span></div> <span class="livre-3d__pages"></span> </div>
           Couleur de la 4e de couverture : --dos. */
        .livre-3d { --dos: #2a3350; position: relative; filter: drop-shadow(0 10px 16px rgba(0,0,0,.45)); transition: transform .3s ease, filter .3s ease; }
        .group:hover .livre-3d.anime, .livre-3d.anime:hover { transform: translateY(-5px); filter: drop-shadow(0 18px 24px rgba(0,0,0,.6)); }
        .livre-3d__couverture { position: relative; z-index: 1; overflow: hidden; border-radius: 2px 6px 6px 2px; }
        .livre-3d__dos { position: absolute; inset: 0 auto 0 0; width: 9%; z-index: 3; pointer-events: none;
                         background: linear-gradient(90deg, rgba(0,0,0,.42) 0%, rgba(0,0,0,.18) 55%, rgba(255,255,255,.28) 80%, rgba(0,0,0,.22) 100%); }
        .livre-3d__dos::before, .livre-3d__dos::after { content: ''; position: absolute; left: 0; width: 140%; height: 6%;
                         border-top: 2px solid rgba(255,255,255,.9); border-bottom: 2px solid rgba(255,255,255,.9);
                         border-radius: 0 100% 100% 0 / 0 50% 50% 0; }
        .livre-3d__dos::before { top: 6%; }
        .livre-3d__dos::after { bottom: 6%; }
        .livre-3d__pages { position: relative; display: block; margin: -4% 0 0 2%; padding-top: 9%; background: var(--dos); border-radius: 0 0 6px 7px; }
        .livre-3d__pages::before { content: ''; position: absolute; left: 4%; right: 2.5%; top: 0; height: 62%; border-radius: 0 0 2px 2px;
                                   background: repeating-linear-gradient(180deg, #e9edf0 0 1.5px, #b9c3cc 1.5px 3px); }
        }

        @media (prefers-reduced-motion: reduce) {
            .reveal { opacity: 1; transform: none; transition: none; }
            .livre-3d, .scroll-smooth, .book-cover * { transition: none !important; scroll-behavior: auto !important; }
            .group:hover .livre-3d.anime, .livre-3d.anime:hover { transform: none; }
        }
    </style>
    @stack('styles')
