<!DOCTYPE html>
<html lang="fr">
<head>
    @include('partials.head')
</head>

@php
    $user = auth()->user();
    $initiales = $user ? collect(explode(' ', trim($user->name)))->filter()->take(2)->map(fn ($m) => mb_strtoupper(mb_substr($m, 0, 1)))->join('') : '';
    $prenom = $user ? \Illuminate\Support\Str::of($user->name)->trim()->explode(' ')->first() : '';
    $navigation = [
        ['Accueil', route('home'), request()->routeIs('home'), 'M2.25 12l8.954-8.955a1.126 1.126 0 011.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25'],
        ['Rechercher', route('livre.index'), request()->routeIs('livre.index', 'livres.show', 'categories.*'), 'M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z'],
        ['Ma bibliothèque', route('espace.bibliotheque'), request()->routeIs('espace.*', 'dashboard'), 'M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25'],
        ['Blog', route('articles.index'), request()->routeIs('articles.*'), 'M12 7.5h1.5m-1.5 3h1.5m-7.5 3h7.5m-7.5 3h7.5m3-9h3.375c.621 0 1.125.504 1.125 1.125V18a2.25 2.25 0 01-2.25 2.25M16.5 7.5V18a2.25 2.25 0 002.25 2.25M16.5 7.5V4.875c0-.621-.504-1.125-1.125-1.125H4.125C3.504 3.75 3 4.254 3 4.875V18a2.25 2.25 0 002.25 2.25h13.5M6 7.5h3v3H6v-3z'],
    ];
    if (Route::has('contributions.create')) {
        $navigation[] = ['Contribuer', route('contributions.create'), request()->routeIs('contributions.*'), 'M12 16.5V9.75m0 0l3 3m-3-3l-3 3M6.75 19.5a4.5 4.5 0 01-1.41-8.775 5.25 5.25 0 0110.233-2.33 3 3 0 013.758 3.848A3.752 3.752 0 0118 19.5H6.75z'];
    }
@endphp

<body class="font-sans" x-data="{ menu: false }" @keydown.escape.window="menu = false">

<div class="relative z-10 min-h-dvh lg:grid lg:grid-cols-[256px_minmax(0,1fr)]">

    {{-- ═════════ BARRE LATÉRALE ═════════ --}}
    <div x-show="menu" x-transition.opacity x-cloak @click="menu = false" class="fixed inset-0 z-40 bg-black/60 lg:hidden"></div>

    <aside class="fixed lg:sticky top-0 left-0 z-50 h-dvh w-[264px] max-w-[85vw] lg:w-auto flex flex-col px-6 py-8
                  bg-[#0a0f1b]/95 lg:bg-transparent backdrop-blur-xl lg:backdrop-blur-none border-r border-ligne lg:border-0
                  -translate-x-full lg:translate-x-0 transition-transform duration-300"
           :class="menu && '!translate-x-0'" aria-label="Navigation principale">

        <a href="{{ route('home') }}" class="flex items-center gap-3 px-2">
            <img src="{{ asset('Digital.png') }}" alt="" class="w-11 h-11 rounded-full object-cover">
            <span class="font-logo text-[24px] font-medium tracking-wide leading-none text-encre">{{ $siteNom }}</span>
        </a>

        <nav class="mt-14 flex flex-col gap-1.5">
            @foreach($navigation as [$libelle, $url, $actif, $icone])
                <a href="{{ $url }}" class="nav-item" @if($actif) aria-current="page" @endif>
                    <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path d="{{ $icone }}"/></svg>
                    {{ $libelle }}
                </a>
            @endforeach

            @if($user?->is_admin)
                <a href="{{ route('admin.dashboard') }}" class="nav-item mt-4">
                    <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z"/></svg>
                    Administration
                </a>
            @endif
        </nav>

        <nav class="mt-auto flex flex-col gap-3 px-2 text-[13.5px] text-brume" aria-label="Informations">
            <a href="{{ route('about') }}" class="hover:text-encre transition-colors">À propos</a>
            <a href="{{ route('contact') }}" class="hover:text-encre transition-colors">Support</a>
            <p class="text-[11.5px] text-voile mt-2">&copy; {{ date('Y') }} {{ $siteNom }}</p>
        </nav>
    </aside>

    {{-- ═════════ ZONE PRINCIPALE ═════════ --}}
    <div class="min-w-0 flex flex-col">

        {{-- Barre du haut --}}
        <header class="sticky top-0 z-30 px-4 sm:px-6 lg:px-10 pt-4 pb-3 lg:pt-7 lg:pb-4 bg-[#070b14]/80 lg:bg-transparent backdrop-blur-xl lg:backdrop-blur-none">
            <div class="flex items-center gap-3 lg:gap-5">
                <button type="button" @click="menu = true" class="lg:hidden w-11 h-11 flex-none rounded-full bg-surface-2 flex items-center justify-center cursor-pointer" aria-label="Ouvrir le menu">
                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>

                {{-- Recherche par catégorie --}}
                <form id="shell-recherche-form" action="{{ route('livre.index') }}" method="GET" role="search"
                      class="pastille flex-1 min-w-0 lg:max-w-[560px] pl-2 pr-2 focus-within:border-corail/50 transition-colors">
                    <label for="shell-categorie" class="sr-only">Catégorie</label>
                    <div class="relative hidden sm:block flex-none">
                        <select id="shell-categorie" name="categorie"
                                class="appearance-none bg-transparent text-[15px] font-medium text-encre pl-5 pr-8 py-2 outline-none cursor-pointer">
                            <option value="" class="bg-surface">Tout</option>
                            @foreach($shellCategories as $c)
                                <option value="{{ $c->id }}" @selected(request()->query('categorie') == $c->id) class="bg-surface">{{ $c->nom }}</option>
                            @endforeach
                        </select>
                        <svg class="pointer-events-none absolute right-2 top-1/2 -translate-y-1/2" width="12" height="12" fill="currentColor" viewBox="0 0 24 24"><path d="M7 10l5 5 5-5z"/></svg>
                    </div>
                    <span class="hidden sm:block w-px h-6 bg-ligne mx-2"></span>
                    <label for="shell-recherche" class="sr-only">Rechercher un livre</label>
                    <input id="shell-recherche" type="search" name="search" value="{{ request()->routeIs('livre.index') ? request('search') : '' }}"
                           placeholder="Rechercher" autocomplete="off"
                           class="flex-1 min-w-0 bg-transparent px-3 text-[15px] text-encre placeholder:text-brume outline-none">
                    <button type="submit" class="w-10 h-10 flex-none rounded-full flex items-center justify-center text-encre hover:text-corail transition-colors cursor-pointer" aria-label="Lancer la recherche">
                        <svg width="19" height="19" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 21l-5.2-5.2M17 10.5a6.5 6.5 0 11-13 0 6.5 6.5 0 0113 0z"/></svg>
                    </button>
                </form>

                {{-- Langue --}}
                @if($shellLangues->isNotEmpty())
                    <form action="{{ route('livre.index') }}" method="GET" class="pastille hidden md:flex flex-none px-5 gap-2.5">
                        <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 24 24" class="text-brume"><path d="M10.5 21l5.25-11.25L21 21m-9-3h7.5M3 5.621a48.474 48.474 0 016-.371m0 0c1.12 0 2.233.038 3.334.114M9 5.25V3m3.334 2.364C11.176 10.658 7.69 15.08 3 17.502m9.334-12.138c.896.061 1.785.147 2.666.257m-4.589 8.495a18.023 18.023 0 01-3.827-5.802"/></svg>
                        <label for="shell-langue" class="sr-only">Langue</label>
                        <select id="shell-langue" name="langue" onchange="this.form.submit()"
                                class="appearance-none bg-transparent text-[15px] text-encre pr-6 outline-none cursor-pointer max-w-[120px]">
                            <option value="" class="bg-surface">Langue</option>
                            @foreach($shellLangues as $langue)
                                <option value="{{ $langue }}" @selected(request()->query('langue') === $langue) class="bg-surface">{{ $langue }}</option>
                            @endforeach
                        </select>
                    </form>
                @endif

                {{-- Heure et date --}}
                <div class="pastille hidden xl:flex flex-none px-6 gap-7 text-[15px] text-encre tabular-nums" x-data="horloge()" x-init="demarrer()">
                    <span class="flex items-center gap-2.5">
                        <svg width="17" height="17" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" class="text-brume"><path d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <span x-text="heure">{{ now()->format('H:i') }}</span>
                    </span>
                    <span class="flex items-center gap-2.5">
                        <svg width="17" height="17" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" class="text-brume"><path d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/></svg>
                        <span x-text="date">{{ now()->locale('fr')->translatedFormat('j M Y') }}</span>
                    </span>
                </div>

                {{-- Compte --}}
                @auth
                    <div class="relative flex-none" x-data="{ ouvert: false }" @click.outside="ouvert = false">
                        <button type="button" @click="ouvert = ! ouvert" :aria-expanded="ouvert" aria-haspopup="menu"
                                class="pastille pl-1.5 pr-4 gap-3 cursor-pointer hover:border-white/15 transition-colors">
                            @if($user->avatar)
                                <img src="{{ media_url($user->avatar) }}" alt="" class="w-9 h-9 rounded-full object-cover">
                            @else
                                <span class="w-9 h-9 rounded-full bg-corail/20 text-corail text-[13px] font-semibold flex items-center justify-center">{{ $initiales }}</span>
                            @endif
                            <span class="hidden sm:block text-[15px] font-medium max-w-[120px] truncate">{{ $prenom }}</span>
                            <svg width="12" height="12" fill="currentColor" viewBox="0 0 24 24" class="hidden sm:block"><path d="M7 10l5 5 5-5z"/></svg>
                        </button>

                        <div x-show="ouvert" x-transition.origin.top.right x-cloak role="menu"
                             class="absolute right-0 mt-2 w-60 rounded-2xl bg-surface border border-ligne shadow-2xl shadow-black/50 p-2 text-[14px]">
                            <div class="px-3 py-2.5 border-b border-ligne mb-1">
                                <p class="font-semibold truncate">{{ $user->name }}</p>
                                <p class="text-[12px] text-voile truncate">{{ $user->email }}</p>
                            </div>
                            <a href="{{ route('dashboard') }}" role="menuitem" class="block px-3 py-2 rounded-lg hover:bg-white/5">Mon espace</a>
                            <a href="{{ route('espace.bibliotheque') }}" role="menuitem" class="block px-3 py-2 rounded-lg hover:bg-white/5">Ma bibliothèque</a>
                            <a href="{{ route('espace.historique') }}" role="menuitem" class="block px-3 py-2 rounded-lg hover:bg-white/5">Historique</a>
                            <a href="{{ route('profile.edit') }}" role="menuitem" class="block px-3 py-2 rounded-lg hover:bg-white/5">Paramètres du compte</a>
                            <form method="POST" action="{{ route('logout') }}" class="border-t border-ligne mt-1 pt-1">
                                @csrf
                                <button type="submit" role="menuitem" class="w-full text-left px-3 py-2 rounded-lg text-corail hover:bg-corail/10 cursor-pointer">Déconnexion</button>
                            </form>
                        </div>
                    </div>
                @else
                    <div class="flex-none flex items-center gap-2">
                        <a href="{{ route('register') }}" class="hidden sm:inline-flex btn btn-discret !rounded-full !h-12 !px-5">S'inscrire</a>
                        <a href="{{ route('login') }}" class="btn btn-blanc !rounded-full !h-11 sm:!h-12 !px-5">Se connecter</a>
                    </div>
                @endauth
            </div>
        </header>

        {{-- Messages --}}
        @php
            $statuts = [
                'profile-updated' => 'Votre profil a été mis à jour.',
                'password-updated' => 'Votre mot de passe a été modifié.',
                'verification-link-sent' => 'Un nouveau code de vérification vous a été envoyé.',
            ];
            $message = session('error') ?? session('success') ?? (session('status') ? ($statuts[session('status')] ?? session('status')) : null);
        @endphp
        @if($message)
            <div class="px-4 sm:px-6 lg:px-10" x-data="{ visible: true }" x-show="visible" x-init="setTimeout(() => visible = false, 5000)" x-transition>
                <p role="status" class="mt-2 rounded-xl px-4 py-3 text-[14px] border
                          {{ session('error') ? 'bg-red-500/10 border-red-400/25 text-red-200' : 'bg-menthe/10 border-menthe/25 text-green-200' }}">
                    {{ $message }}
                </p>
            </div>
        @endif

        <main id="contenu" class="flex-1 px-4 sm:px-6 lg:px-10 pt-4 pb-28 lg:pb-14">
            @yield('content')
        </main>
    </div>
</div>

{{-- Barre de navigation mobile --}}
<nav class="lg:hidden fixed bottom-0 inset-x-0 z-30 bg-[#0a0f1b]/95 backdrop-blur-xl border-t border-ligne grid grid-cols-4 pb-[env(safe-area-inset-bottom)]" aria-label="Navigation rapide">
    @foreach(array_slice($navigation, 0, 3) as [$libelle, $url, $actif, $icone])
        <a href="{{ $url }}" class="flex flex-col items-center gap-1 py-2.5 text-[11px] {{ $actif ? 'text-corail' : 'text-brume' }}" @if($actif) aria-current="page" @endif>
            <svg width="21" height="21" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path d="{{ $icone }}"/></svg>
            {{ $libelle === 'Ma bibliothèque' ? 'Biblio' : $libelle }}
        </a>
    @endforeach
    <a href="{{ $user ? route('profile.edit') : route('login') }}" class="flex flex-col items-center gap-1 py-2.5 text-[11px] {{ request()->routeIs('profile.*', 'login') ? 'text-corail' : 'text-brume' }}">
        <svg width="21" height="21" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/></svg>
        {{ $user ? 'Compte' : 'Connexion' }}
    </a>
</nav>

<script>
    function horloge() {
        const heure = new Intl.DateTimeFormat('fr-FR', { hour: '2-digit', minute: '2-digit' });
        const date = new Intl.DateTimeFormat('fr-FR', { day: 'numeric', month: 'short', year: 'numeric' });
        return {
            heure: '', date: '',
            demarrer() {
                const maj = () => { const n = new Date(); this.heure = heure.format(n); this.date = date.format(n); };
                maj();
                setInterval(maj, 15000);
            },
        };
    }

    // Carrousels (flèches) et apparition douce des sections. Rappelable sur un fragment : window.initUi(element)
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
        if (!('IntersectionObserver' in window)) { reveals.forEach(el => el.classList.add('is-visible')); return; }
        const observer = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (entry.isIntersecting) { entry.target.classList.add('is-visible'); observer.unobserve(entry.target); }
            });
        }, { rootMargin: '0px 0px -60px 0px' });
        reveals.forEach(el => observer.observe(el));
    };
    document.addEventListener('DOMContentLoaded', () => window.initUi());
</script>
@stack('scripts')
</body>
</html>
