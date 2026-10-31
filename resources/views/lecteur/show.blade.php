<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $livre->titre }} — Lecture · {{ $siteNom }}</title>
    @include('partials.pwa')
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'IBM Plex Sans', sans-serif; margin: 0; }

        /* Modes de lecture */
        .mode-clair  { --fond: #FAFAF7; --barre: #ffffff; --texte: #0d1322; --doux: #6b7280; --bord: #f0ede8; --filtre: none; }
        .mode-sepia  { --fond: #efe6d2; --barre: #f7f0e1; --texte: #4a3b22; --doux: #7c6a4c; --bord: #e2d6bb; --filtre: sepia(0.35) brightness(0.97); }
        .mode-sombre { --fond: #070b14; --barre: #121829; --texte: #e8eeee; --doux: #a3abbd; --bord: #232a3d; --filtre: invert(0.88) hue-rotate(180deg); }

        .zone-lecture { background: var(--fond); color: var(--texte); transition: background 0.3s, color 0.3s; }
        .barre { background: var(--barre); border-color: var(--bord); color: var(--texte); transition: background 0.3s; }
        .texte-doux { color: var(--doux); }
        #page-canvas { filter: var(--filtre); transition: filter 0.3s, opacity 0.2s; }

        .outil {
            width: 38px; height: 38px; border-radius: 50%;
            display: inline-flex; align-items: center; justify-content: center;
            border: 1px solid var(--bord); color: var(--texte); background: transparent;
            cursor: pointer; transition: all 0.15s; flex: none; font-size: 13px; font-weight: 600;
        }
        .outil:hover:not(:disabled) { border-color: #f07155; color: #f07155; }
        .outil:disabled { opacity: 0.35; cursor: default; }
        .outil[aria-pressed="true"] { background: #f07155; border-color: #f07155; color: white; }

        input[type="range"].progression { accent-color: #f07155; }

        .panneau-lien { display: flex; justify-content: space-between; gap: 10px; width: 100%; text-align: left;
            padding: 8px 12px; border-radius: 10px; font-size: 13px; color: rgba(255,255,255,0.75); cursor: pointer;
            background: transparent; border: 0; transition: background 0.15s, color 0.15s; }
        .panneau-lien:hover { background: rgba(255,255,255,0.06); color: white; }
        .panneau-lien.actif { background: rgba(240,113,85,0.15); color: #ffb3a3; }

        @media (prefers-reduced-motion: reduce) { * { transition: none !important; } }
    </style>
</head>
<body class="mode-clair overflow-hidden">

<div class="h-dvh flex">

    {{-- ═════════ BARRE LATÉRALE ═════════ --}}
    <div id="voile" class="fixed inset-0 z-30 bg-black/40 hidden lg:hidden"></div>

    <aside id="panneau"
           class="fixed lg:static inset-y-0 left-0 z-40 w-[290px] max-w-[85vw] bg-[#0d1322] text-white flex flex-col
                  -translate-x-full lg:translate-x-0 transition-transform duration-300">
        <div class="p-5 border-b border-white/10">
            <a href="{{ route('livres.show', $livre) }}" class="inline-flex items-center gap-2 text-[12px] text-white/60 hover:text-[#f07155] transition-colors">
                ← Retour à la fiche
            </a>

            <div class="mt-5 flex gap-3.5">
                <div class="flex-none w-[60px] aspect-[2/3] rounded-lg overflow-hidden bg-[#1a2134] shadow-lg">
                    @if($livre->image)
                        <img src="{{ media_url($livre->image) }}" alt="" class="w-full h-full object-cover">
                    @endif
                </div>
                <div class="min-w-0">
                    <h1 class="text-[14.5px] font-semibold leading-snug line-clamp-3">{{ $livre->titre }}</h1>
                    <p class="text-[12px] text-white/55 mt-0.5 truncate">{{ $livre->auteur }}</p>
                </div>
            </div>

            <div class="mt-5">
                <div class="flex justify-between text-[11.5px] text-white/60 mb-1.5">
                    <span>Page <span data-page-courante>–</span> / <span data-pages-total>–</span></span>
                    <span class="font-semibold text-white"><span data-pourcentage>0</span> %</span>
                </div>
                <div class="h-1.5 rounded-full bg-white/15 overflow-hidden">
                    <div id="barre-progression" class="h-full rounded-full bg-[#f07155] transition-all duration-300" style="width:0%"></div>
                </div>
            </div>
        </div>

        <div class="flex gap-1 p-2 mx-3 mt-3 bg-white/[0.06] rounded-xl" role="tablist">
            <button type="button" role="tab" data-onglet="sommaire" aria-selected="true"
                    class="flex-1 rounded-lg py-2 text-[12.5px] font-medium cursor-pointer aria-selected:bg-[#f07155] aria-selected:text-white text-white/65 transition-colors">
                Sommaire
            </button>
            <button type="button" role="tab" data-onglet="signets" aria-selected="false"
                    class="flex-1 rounded-lg py-2 text-[12.5px] font-medium cursor-pointer aria-selected:bg-[#f07155] aria-selected:text-white text-white/65 transition-colors">
                Signets
            </button>
        </div>

        <div class="flex-1 overflow-y-auto px-3 py-3">
            <div data-panneau="sommaire" role="tabpanel">
                <ul id="sommaire" class="space-y-0.5"></ul>
                <p id="sommaire-vide" class="hidden px-3 text-[12.5px] text-white/45 leading-relaxed">
                    Ce document ne contient pas de sommaire. Utilisez la barre de progression pour naviguer.
                </p>
            </div>
            <div data-panneau="signets" role="tabpanel" class="hidden">
                <ul id="liste-signets" class="space-y-0.5"></ul>
                <p id="signets-vide" class="px-3 text-[12.5px] text-white/45 leading-relaxed">
                    Aucun signet. Touchez l'icône marque-page en haut de l'écran pour marquer une page.
                </p>
            </div>
        </div>

        <div class="p-4 border-t border-white/10 text-[11px] text-white/35 flex items-center gap-2">
            <span class="w-1.5 h-1.5 rounded-full bg-[#f07155]"></span>
            Votre progression est enregistrée automatiquement
        </div>
    </aside>

    {{-- ═════════ ZONE DE LECTURE ═════════ --}}
    <main class="zone-lecture flex-1 min-w-0 flex flex-col">

        {{-- Barre du haut --}}
        <header class="barre border-b h-14 flex items-center gap-3 px-3 sm:px-5 flex-none">
            <button type="button" id="ouvrir-panneau" class="outil lg:hidden" aria-label="Ouvrir le sommaire">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4 6h16M4 12h16M4 18h16"/></svg>
            </button>
            <p id="titre-section" class="flex-1 min-w-0 truncate text-[13px] font-medium">{{ $livre->titre }}</p>
            <button type="button" id="bouton-signet" class="outil" aria-pressed="false" aria-label="Ajouter un signet sur cette page" title="Signet">
                <svg width="15" height="15" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" fill="none" data-icone-signet>
                    <path d="M17.593 3.322c1.1.128 1.907 1.077 1.907 2.185V21L12 17.25 4.5 21V5.507c0-1.108.806-2.057 1.907-2.185a48.507 48.507 0 0111.186 0z"/>
                </svg>
            </button>
            <button type="button" id="plein-ecran" class="outil hidden sm:inline-flex" aria-label="Plein écran" title="Plein écran">
                <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3.75 3.75v4.5m0-4.5h4.5m-4.5 0L9 9M3.75 20.25v-4.5m0 4.5h4.5m-4.5 0L9 15M20.25 3.75h-4.5m4.5 0v4.5m0-4.5L15 9m5.25 11.25h-4.5m4.5 0v-4.5m0 4.5L15 15"/></svg>
            </button>
        </header>

        {{-- Page --}}
        <div id="conteneur" class="flex-1 overflow-auto relative">
            <div class="min-h-full flex items-start justify-center p-4 sm:p-8">
                <canvas id="page-canvas" class="rounded-md shadow-[0_10px_40px_rgba(0,0,0,0.15)] bg-white max-w-none"></canvas>
            </div>

            <div id="chargement" class="absolute inset-0 flex flex-col items-center justify-center gap-3 texte-doux text-[13px]">
                <span class="w-9 h-9 rounded-full border-[3px] border-[#f07155]/25 border-t-[#f07155] animate-spin"></span>
                Ouverture du livre…
            </div>
            <div id="erreur" class="hidden absolute inset-0 flex flex-col items-center justify-center gap-3 text-center px-6">
                <p class="text-[15px] font-semibold">Impossible d'ouvrir ce livre.</p>
                <a href="{{ media_url($livre->pdf_path) }}" target="_blank" rel="noopener"
                   class="text-[13px] font-semibold text-[#f07155] hover:underline">Ouvrir le PDF dans un nouvel onglet</a>
            </div>
        </div>

        {{-- Barre d'outils --}}
        <footer class="barre border-t flex-none px-3 sm:px-5 py-2.5 flex items-center gap-2 sm:gap-3">
            <div class="flex items-center gap-1.5">
                <button type="button" class="outil" data-zoom="-1" aria-label="Réduire la taille">A−</button>
                <button type="button" class="outil" data-zoom="1" aria-label="Agrandir la taille">A+</button>
            </div>

            <div class="hidden sm:flex items-center gap-1.5 pl-2 sm:pl-3 border-l" style="border-color: var(--bord)" role="group" aria-label="Mode de lecture">
                <button type="button" class="outil" data-mode="clair" aria-label="Mode clair" title="Clair">
                    <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 3v2.25m6.364.386l-1.591 1.591M21 12h-2.25m-.386 6.364l-1.591-1.591M12 18.75V21m-4.773-4.227l-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z"/></svg>
                </button>
                <button type="button" class="outil" data-mode="sepia" aria-label="Mode sépia" title="Sépia">
                    <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25"/></svg>
                </button>
                <button type="button" class="outil" data-mode="sombre" aria-label="Mode sombre" title="Sombre">
                    <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21.752 15.002A9.718 9.718 0 0118 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 003 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 009.002-5.998z"/></svg>
                </button>
            </div>

            <div class="flex-1 flex items-center gap-3 min-w-0 px-1 sm:px-3">
                <label for="curseur" class="sr-only">Aller à la page</label>
                <input id="curseur" type="range" min="1" max="1" value="1" class="progression flex-1 min-w-0 cursor-pointer">
                <span class="flex-none text-[12px] texte-doux tabular-nums"><span data-page-courante>–</span>/<span data-pages-total>–</span></span>
            </div>

            <div class="flex items-center gap-1.5">
                <button type="button" class="outil sm:hidden" id="mode-suivant" aria-label="Changer de mode de lecture">◐</button>
                <button type="button" class="outil" id="precedente" aria-label="Page précédente">
                    <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path d="M15 19l-7-7 7-7"/></svg>
                </button>
                <button type="button" class="outil !bg-[#f07155] !border-[#f07155] !text-white hover:!bg-[#dc5a3e]" id="suivante" aria-label="Page suivante">
                    <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7"/></svg>
                </button>
            </div>
        </footer>
    </main>
</div>

<script>
(function () {
    const CONFIG = {
        pdf: @json(media_url($livre->pdf_path)),
        progression: @json(route('livres.progression', $livre)),
        signets: @json(route('livres.signets', $livre)),
        csrf: @json(csrf_token()),
        pageDepart: @json($lecture->page_actuelle ?: 1),
        signetsInitiaux: @json($lecture->signets ?? []),
        cleProgression: @json('biblio-progression-'.auth()->id().'-'.$livre->id),
        genereeLe: @json(now()->getTimestampMs()),
    };

    // Dernière page lue sur cet appareil. Elle l'emporte si elle n'a pas encore été envoyée (lecture hors connexion),
    // ou si elle est plus récente que cette page, ce qui arrive quand la page vient du cache du service worker.
    const progressionLocale = (() => {
        try { return JSON.parse(localStorage.getItem(CONFIG.cleProgression)); } catch (_) { return null; }
    })();
    if (progressionLocale && progressionLocale.page && (!progressionLocale.envoyee || progressionLocale.date > CONFIG.genereeLe)) {
        CONFIG.pageDepart = progressionLocale.page;
    }

    pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';

    const $ = (s) => document.querySelector(s);
    const $$ = (s) => document.querySelectorAll(s);
    const canvas = $('#page-canvas');
    const contexte = canvas.getContext('2d');
    const conteneur = $('#conteneur');
    const curseur = $('#curseur');

    // Préférences de lecture (zoom, mode), propres à cet appareil
    const preferences = (() => {
        try { return JSON.parse(localStorage.getItem('lecteur-preferences')) || {}; } catch (_) { return {}; }
    })();
    function sauverPreferences() {
        try { localStorage.setItem('lecteur-preferences', JSON.stringify({ zoom: etat.zoom, mode: etat.mode })); } catch (_) {}
    }

    const ZOOMS = [0.6, 0.75, 0.9, 1, 1.15, 1.3, 1.5, 1.75, 2];
    const MODES = ['clair', 'sepia', 'sombre'];
    const etat = {
        pdf: null,
        page: 1,
        total: 1,
        zoom: ZOOMS.includes(preferences.zoom) ? preferences.zoom : 1,
        mode: MODES.includes(preferences.mode) ? preferences.mode : 'clair',
        signets: CONFIG.signetsInitiaux.map(Number),
        sommaire: [],
        rendu: null,
        enAttente: null,
    };

    // ── Rendu d'une page ──
    async function afficher(numero) {
        numero = Math.min(Math.max(1, numero), etat.total);
        etat.page = numero;
        if (etat.rendu) { etat.enAttente = numero; return; }

        const page = await etat.pdf.getPage(numero);
        const largeurDispo = Math.max(240, conteneur.clientWidth - (window.innerWidth < 640 ? 32 : 64));
        const echelleBase = Math.min(largeurDispo / page.getViewport({ scale: 1 }).width, 1.6);
        const vue = page.getViewport({ scale: echelleBase * etat.zoom });
        const ratio = window.devicePixelRatio || 1;

        canvas.width = Math.floor(vue.width * ratio);
        canvas.height = Math.floor(vue.height * ratio);
        canvas.style.width = Math.floor(vue.width) + 'px';
        canvas.style.height = Math.floor(vue.height) + 'px';

        etat.rendu = page.render({ canvasContext: contexte, viewport: vue, transform: ratio !== 1 ? [ratio, 0, 0, ratio, 0, 0] : null });
        try { await etat.rendu.promise; } catch (_) {}
        etat.rendu = null;

        if (etat.enAttente !== null && etat.enAttente !== numero) {
            const suivante = etat.enAttente;
            etat.enAttente = null;
            return afficher(suivante);
        }
        etat.enAttente = null;
        mettreAJourInterface();
        enregistrerProgression();
    }

    function aller(numero) {
        if (numero < 1 || numero > etat.total || numero === etat.page) return;
        conteneur.scrollTo({ top: 0 });
        afficher(numero);
    }

    // ── Interface ──
    function mettreAJourInterface() {
        const pourcentage = Math.round(etat.page / etat.total * 100);
        $$('[data-page-courante]').forEach(el => el.textContent = etat.page);
        $$('[data-pages-total]').forEach(el => el.textContent = etat.total);
        $$('[data-pourcentage]').forEach(el => el.textContent = pourcentage);
        $('#barre-progression').style.width = pourcentage + '%';
        curseur.max = etat.total;
        curseur.value = etat.page;
        $('#precedente').disabled = etat.page <= 1;
        $('#suivante').disabled = etat.page >= etat.total;

        const marque = etat.signets.includes(etat.page);
        const bouton = $('#bouton-signet');
        bouton.setAttribute('aria-pressed', marque ? 'true' : 'false');
        bouton.setAttribute('aria-label', marque ? 'Retirer le signet de cette page' : 'Ajouter un signet sur cette page');
        bouton.querySelector('[data-icone-signet]').setAttribute('fill', marque ? 'currentColor' : 'none');

        // Section du sommaire en cours
        const section = [...etat.sommaire].reverse().find(item => item.page && item.page <= etat.page);
        $('#titre-section').textContent = section ? section.titre : @json($livre->titre);
        $$('#sommaire [data-page]').forEach(el => el.classList.toggle('actif', !!section && Number(el.dataset.page) === section.page));
    }

    function appliquerMode() {
        document.body.classList.remove(...MODES.map(m => 'mode-' + m));
        document.body.classList.add('mode-' + etat.mode);
        $$('[data-mode]').forEach(b => b.setAttribute('aria-pressed', b.dataset.mode === etat.mode ? 'true' : 'false'));
        $$('[data-zoom]').forEach(b => b.disabled = b.dataset.zoom === '1' ? etat.zoom >= ZOOMS.at(-1) : etat.zoom <= ZOOMS[0]);
    }

    // ── Sommaire (plan du PDF) ──
    async function chargerSommaire() {
        const plan = await etat.pdf.getOutline().catch(() => null);
        const liste = $('#sommaire');

        async function pageDe(dest) {
            try {
                const destination = typeof dest === 'string' ? await etat.pdf.getDestination(dest) : dest;
                if (!destination) return null;
                return (await etat.pdf.getPageIndex(destination[0])) + 1;
            } catch (_) { return null; }
        }

        async function ajouter(items, niveau) {
            for (const item of items) {
                const page = await pageDe(item.dest);
                etat.sommaire.push({ titre: item.title, page });
                const li = document.createElement('li');
                const bouton = document.createElement('button');
                bouton.type = 'button';
                bouton.className = 'panneau-lien';
                bouton.style.paddingLeft = (12 + niveau * 14) + 'px';
                bouton.dataset.page = page || '';
                bouton.innerHTML = '<span class="truncate"></span><span class="flex-none text-[11px] opacity-50"></span>';
                bouton.firstChild.textContent = item.title;
                bouton.lastChild.textContent = page || '';
                bouton.disabled = !page;
                bouton.addEventListener('click', () => { aller(page); fermerPanneau(); });
                li.appendChild(bouton);
                liste.appendChild(li);
                if (item.items && item.items.length && niveau < 2) await ajouter(item.items, niveau + 1);
            }
        }

        if (plan && plan.length) {
            await ajouter(plan, 0);
            etat.sommaire.sort((a, b) => (a.page || 0) - (b.page || 0));
        } else {
            $('#sommaire-vide').classList.remove('hidden');
        }
        mettreAJourInterface();
    }

    // ── Signets ──
    function afficherSignets() {
        const liste = $('#liste-signets');
        liste.innerHTML = '';
        $('#signets-vide').classList.toggle('hidden', etat.signets.length > 0);
        etat.signets.forEach(function (page) {
            const li = document.createElement('li');
            li.innerHTML = '<button type="button" class="panneau-lien"><span>Page ' + page + '</span><span class="text-[#f07155]">●</span></button>';
            li.firstChild.addEventListener('click', () => { aller(page); fermerPanneau(); });
            liste.appendChild(li);
        });
    }

    async function basculerSignet() {
        const page = etat.page;
        etat.signets = etat.signets.includes(page) ? etat.signets.filter(p => p !== page) : [...etat.signets, page].sort((a, b) => a - b);
        mettreAJourInterface();
        afficherSignets();
        try {
            const reponse = await envoyer(CONFIG.signets, { page });
            etat.signets = reponse.signets.map(Number);
            afficherSignets();
            mettreAJourInterface();
        } catch (_) {}
    }

    // ── Enregistrement de la progression ──
    // Gardée aussi sur l'appareil, marquée « envoyée » une fois reçue par le serveur, renvoyée au retour de la connexion
    let minuteur = null;
    function enregistrerProgression() {
        clearTimeout(minuteur);
        const locale = { page: etat.page, total: etat.total, date: Date.now(), envoyee: false };
        try { localStorage.setItem(CONFIG.cleProgression, JSON.stringify(locale)); } catch (_) {}
        minuteur = setTimeout(envoyerProgression, 700);
    }

    function envoyerProgression() {
        let locale = null;
        try { locale = JSON.parse(localStorage.getItem(CONFIG.cleProgression)); } catch (_) {}
        if (!locale || locale.envoyee || !navigator.onLine) return;
        envoyer(CONFIG.progression, { page: locale.page, total: locale.total }).then(function () {
            try {
                const actuelle = JSON.parse(localStorage.getItem(CONFIG.cleProgression));
                if (actuelle && actuelle.date === locale.date) localStorage.setItem(CONFIG.cleProgression, JSON.stringify({ ...actuelle, envoyee: true }));
            } catch (_) {}
        }).catch(() => {});
    }
    window.addEventListener('online', envoyerProgression);

    async function envoyer(url, donnees) {
        const reponse = await fetch(url, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': CONFIG.csrf },
            body: JSON.stringify(donnees),
            keepalive: true,
        });
        if (!reponse.ok) throw new Error(reponse.status);
        return reponse.json();
    }

    // ── Panneau latéral (mobile) ──
    function ouvrirPanneau() { $('#panneau').classList.remove('-translate-x-full'); $('#voile').classList.remove('hidden'); }
    function fermerPanneau() { $('#panneau').classList.add('-translate-x-full'); $('#voile').classList.add('hidden'); }

    // ── Événements ──
    $('#precedente').addEventListener('click', () => aller(etat.page - 1));
    $('#suivante').addEventListener('click', () => aller(etat.page + 1));
    curseur.addEventListener('input', () => { $$('[data-page-courante]').forEach(el => el.textContent = curseur.value); });
    curseur.addEventListener('change', () => aller(Number(curseur.value)));
    $('#bouton-signet').addEventListener('click', basculerSignet);
    $('#ouvrir-panneau').addEventListener('click', ouvrirPanneau);
    $('#voile').addEventListener('click', fermerPanneau);

    $$('[data-zoom]').forEach(b => b.addEventListener('click', function () {
        const i = ZOOMS.indexOf(etat.zoom) + Number(b.dataset.zoom);
        etat.zoom = ZOOMS[Math.min(Math.max(0, i), ZOOMS.length - 1)];
        appliquerMode(); sauverPreferences(); afficher(etat.page);
    }));
    $$('[data-mode]').forEach(b => b.addEventListener('click', function () {
        etat.mode = b.dataset.mode; appliquerMode(); sauverPreferences();
    }));
    $('#mode-suivant').addEventListener('click', function () {
        etat.mode = MODES[(MODES.indexOf(etat.mode) + 1) % MODES.length]; appliquerMode(); sauverPreferences();
    });

    $$('[data-onglet]').forEach(b => b.addEventListener('click', function () {
        $$('[data-onglet]').forEach(o => o.setAttribute('aria-selected', o === b ? 'true' : 'false'));
        $$('[data-panneau]').forEach(p => p.classList.toggle('hidden', p.dataset.panneau !== b.dataset.onglet));
    }));

    $('#plein-ecran').addEventListener('click', function () {
        if (document.fullscreenElement) document.exitFullscreen();
        else document.documentElement.requestFullscreen?.();
    });

    document.addEventListener('keydown', function (e) {
        if (e.target.matches('input, textarea')) return;
        if (['ArrowRight', 'PageDown'].includes(e.key)) { e.preventDefault(); aller(etat.page + 1); }
        if (['ArrowLeft', 'PageUp'].includes(e.key)) { e.preventDefault(); aller(etat.page - 1); }
        if (e.key === 'Escape') fermerPanneau();
    });

    // Balayage horizontal sur écran tactile (uniquement sans zoom, pour ne pas gêner le défilement)
    let departX = null;
    conteneur.addEventListener('touchstart', e => { departX = e.touches.length === 1 ? e.touches[0].clientX : null; }, { passive: true });
    conteneur.addEventListener('touchend', function (e) {
        if (departX === null || etat.zoom > 1) return;
        const ecart = e.changedTouches[0].clientX - departX;
        if (Math.abs(ecart) > 60) aller(etat.page + (ecart < 0 ? 1 : -1));
        departX = null;
    }, { passive: true });

    let redimension = null;
    window.addEventListener('resize', () => { clearTimeout(redimension); redimension = setTimeout(() => etat.pdf && afficher(etat.page), 200); });

    // ── Démarrage ──
    appliquerMode();
    afficherSignets();
    // Fichier téléchargé en entier (pas par morceaux) pour que le service worker le garde : le livre reste lisible hors connexion
    pdfjsLib.getDocument({ url: CONFIG.pdf, disableRange: true }).promise.then(async function (pdf) {
        etat.pdf = pdf;
        etat.total = pdf.numPages;
        $('#chargement').classList.add('hidden');
        await afficher(Math.min(CONFIG.pageDepart, pdf.numPages));
        chargerSommaire();
    }).catch(function () {
        $('#chargement').classList.add('hidden');
        $('#erreur').classList.remove('hidden');
    });
})();
</script>
</body>
</html>
