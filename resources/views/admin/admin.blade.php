<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') — {{ $siteNom }}</title>
    <link rel="icon" href="{{ asset('Digital.png') }}">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.1/dist/cdn.min.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        [x-cloak] { display: none !important; }
        * { font-family: 'IBM Plex Sans', ui-sans-serif, system-ui, sans-serif; }
        .font-display { font-family: 'IBM Plex Sans', sans-serif; }
        body { background: #f4f5f8; color: #121829; margin: 0; }

        .sidebar {
            background: linear-gradient(180deg, #0f1628 0%, #0a0f1b 100%);
            position: fixed; top: 0; left: 0; height: 100vh; width: 248px;
            display: flex; flex-direction: column; z-index: 50;
            transition: transform .3s ease;
        }
        .nav-link {
            display: flex; align-items: center; gap: 12px; padding: 10px 12px; border-radius: 10px;
            font-size: 14px; color: rgba(255,255,255,0.6); text-decoration: none; transition: all .15s ease;
        }
        .nav-link:hover { background: rgba(255,255,255,0.05); color: #fff; }
        .nav-link.active { background: rgba(240,113,85,0.14); color: #ff9a85; }
        .nav-link svg { flex: none; }
        .main-content { margin-left: 248px; min-height: 100vh; padding: 32px 36px; }
        .badge-env { font-size: 10px; font-weight: 600; letter-spacing: .1em; text-transform: uppercase;
                     background: rgba(224,96,63,0.12); color: #c94f31; padding: 3px 9px; border-radius: 20px; }

        @media (max-width: 1023px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.ouverte { transform: none; box-shadow: 0 0 60px rgba(0,0,0,.4); }
            .main-content { margin-left: 0; padding: 20px 16px; }
        }
    </style>
    @stack('styles')
</head>

@php
    $admin = auth()->user();
    $contributionsEnAttente = \App\Models\Contribution::enAttente()->count();
    $liens = [
        ['Tableau de bord', route('admin.dashboard'), request()->routeIs('admin.dashboard'), 'M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z', null],
        ['Livres', route('admin.livres.index'), request()->routeIs('admin.livres.*'), 'M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25', null],
        ['Contributions', route('admin.contributions.index'), request()->routeIs('admin.contributions.*'), 'M12 16.5V9.75m0 0l3 3m-3-3l-3 3M6.75 19.5a4.5 4.5 0 01-1.41-8.775 5.25 5.25 0 0110.233-2.33 3 3 0 013.758 3.848A3.752 3.752 0 0118 19.5H6.75z', $contributionsEnAttente],
        ['Articles du blog', route('admin.articles.index'), request()->routeIs('admin.articles.*'), 'M12 7.5h1.5m-1.5 3h1.5m-7.5 3h7.5m-7.5 3h7.5m3-9h3.375c.621 0 1.125.504 1.125 1.125V18a2.25 2.25 0 01-2.25 2.25M16.5 7.5V18a2.25 2.25 0 002.25 2.25M16.5 7.5V4.875c0-.621-.504-1.125-1.125-1.125H4.125C3.504 3.75 3 4.254 3 4.875V18a2.25 2.25 0 002.25 2.25h13.5M6 7.5h3v3H6v-3z', null],
        ['Lectures', route('admin.lectures.index'), request()->routeIs('admin.lectures.*'), 'M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178zM15 12a3 3 0 11-6 0 3 3 0 016 0z', null],
    ];
@endphp

<body x-data="{ menu: false }" @keydown.escape.window="menu = false">

    <div x-show="menu" x-cloak @click="menu = false" class="fixed inset-0 z-40 bg-black/50 lg:hidden"></div>

    <aside class="sidebar" :class="menu && 'ouverte'" aria-label="Navigation de l'administration">
        <div style="padding: 26px 20px 22px; border-bottom: 1px solid rgba(255,255,255,0.06);">
            <a href="{{ route('admin.dashboard') }}" style="display:flex; align-items:center; gap:11px; text-decoration:none;">
                <img src="{{ asset('Digital.png') }}" alt="" style="width:38px; height:38px; border-radius:50%; object-fit:cover;">
                <div>
                    <p style="font-size:17px; font-weight:600; color:white; line-height:1;">{{ $siteNom }}</p>
                    <p style="font-size:10.5px; color:rgba(255,255,255,0.4); letter-spacing:.1em; text-transform:uppercase; margin-top:4px;">Administration</p>
                </div>
            </a>
        </div>

        <nav style="padding: 18px 12px; flex:1; display:flex; flex-direction:column; gap:3px; overflow-y:auto;">
            @foreach($liens as [$libelle, $url, $actif, $icone, $badge])
                <a href="{{ $url }}" class="nav-link {{ $actif ? 'active' : '' }}" @if($actif) aria-current="page" @endif>
                    <svg width="19" height="19" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 24 24" aria-hidden="true"><path d="{{ $icone }}"/></svg>
                    {{ $libelle }}
                    @if($badge)
                        <span style="margin-left:auto; background:#e0603f; color:white; font-size:11px; font-weight:600; border-radius:999px; padding:1px 8px;"
                              title="{{ $badge }} en attente">{{ $badge }}</span>
                    @endif
                </a>
            @endforeach
        </nav>

        <div style="padding: 14px; border-top: 1px solid rgba(255,255,255,0.06);">
            <div style="display:flex; align-items:center; gap:10px; padding:8px 10px; border-radius:10px; background:rgba(255,255,255,0.04);">
                @if($admin->avatar)
                    <img src="{{ asset('storage/' . $admin->avatar) }}" alt="" style="width:32px; height:32px; border-radius:50%; object-fit:cover;">
                @else
                    <span style="width:32px; height:32px; border-radius:50%; background:rgba(240,113,85,.2); color:#ff9a85; display:flex; align-items:center; justify-content:center; font-size:12px; font-weight:700; flex:none;">
                        {{ mb_strtoupper(mb_substr($admin->name, 0, 1)) }}
                    </span>
                @endif
                <div style="flex:1; min-width:0;">
                    <p style="font-size:13px; font-weight:500; color:rgba(255,255,255,.85); white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ $admin->name }}</p>
                    <p style="font-size:11px; color:rgba(255,255,255,.35);">Administrateur</p>
                </div>
                <form action="{{ route('logout') }}" method="POST" style="margin:0;">
                    @csrf
                    <button type="submit" title="Déconnexion" aria-label="Déconnexion"
                            style="color:rgba(255,255,255,.35); background:none; border:0; cursor:pointer; padding:4px;"
                            onmouseover="this.style.color='#ff9a85'" onmouseout="this.style.color='rgba(255,255,255,.35)'">
                        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 24 24"><path d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75"/></svg>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <main class="main-content">
        <div style="display:flex; align-items:center; justify-content:space-between; gap:16px; margin-bottom:28px; flex-wrap:wrap;">
            <div style="display:flex; align-items:center; gap:12px; min-width:0;">
                <button type="button" @click="menu = true" class="lg:hidden" aria-label="Ouvrir le menu"
                        style="width:40px; height:40px; border-radius:10px; background:white; border:1px solid #e5e7eb; display:flex; align-items:center; justify-content:center; cursor:pointer;">
                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
                <div style="min-width:0;">
                    <h1 style="font-size:24px; font-weight:600; color:#121829; line-height:1.2; margin:0;">@yield('page-title', 'Tableau de bord')</h1>
                    <p style="font-size:13.5px; color:#6b7386; margin-top:3px;">@yield('page-subtitle', 'Bienvenue dans votre espace d\'administration')</p>
                </div>
            </div>
            <div style="display:flex; align-items:center; gap:10px;">
                <span class="badge-env">Admin</span>
                <a href="{{ url('/') }}" target="_blank" rel="noopener"
                   class="hover:!border-[#e0603f] hover:!text-[#e0603f]"
                   style="display:flex; align-items:center; gap:6px; font-size:13px; color:#6b7280; text-decoration:none; background:white; border:1px solid #e5e7eb; padding:8px 14px; border-radius:9px; transition:all .15s;">
                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 24 24"><path d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25"/></svg>
                    Voir le site
                </a>
            </div>
        </div>

        @if(session('success') && ! request()->routeIs('admin.contributions.*', 'admin.articles.*'))
            <div role="status" style="margin-bottom:20px; padding:12px 16px; border-radius:10px; background:#ecfdf5; border:1px solid #a7f3d0; color:#065f46; font-size:13.5px;">{{ session('success') }}</div>
        @endif

        @yield('content')
    </main>

    @stack('scripts')
</body>
</html>
