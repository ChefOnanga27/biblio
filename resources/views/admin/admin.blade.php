<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;500;600;700;800&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <title>@yield('title', 'Admin')</title>
    <style>
        * { font-family: 'Poppins', sans-serif; }
        .font-display { font-family: 'Poppins', sans-serif; }

        body { background: #F5F4F0; }

        .sidebar {
            background: #063537;
            position: fixed;
            top: 0; left: 0;
            height: 100vh;
            width: 240px;
            display: flex;
            flex-direction: column;
            z-index: 50;
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 16px;
            border-radius: 10px;
            font-size: 13.5px;
            font-weight: 400;
            color: rgba(255,255,255,0.45);
            text-decoration: none;
            transition: all 0.15s ease;
            letter-spacing: 0.01em;
        }
        .nav-link:hover {
            background: rgba(255,255,255,0.06);
            color: rgba(255,255,255,0.85);
        }
        .nav-link.active {
            background: rgba(249,115,22,0.15);
            color: #fb923c;
        }
        .nav-link .icon {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            background: rgba(255,255,255,0.04);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        .nav-link.active .icon {
            background: rgba(249,115,22,0.2);
        }

        .main-content {
            margin-left: 240px;
            min-height: 100vh;
            padding: 32px 36px;
        }

        .topbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 32px;
        }

        .badge-env {
            font-size: 10px;
            font-weight: 600;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            background: rgba(249,115,22,0.12);
            color: #ea580c;
            padding: 3px 9px;
            border-radius: 20px;
        }
    </style>
</head>

<body>

    <!-- ── SIDEBAR ── -->
    <aside class="sidebar">

        <!-- Logo -->
        <div style="padding: 28px 20px 24px; border-bottom: 1px solid rgba(255,255,255,0.06);">
            <div style="display:flex; align-items:center; gap:10px;">
                <div style="width:34px; height:34px; background:#f97316; border-radius:9px;
                            display:flex; align-items:center; justify-content:center;">
                    <svg width="18" height="18" fill="none" stroke="white" stroke-width="1.8" viewBox="0 0 24 24">
                        <path d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25"/>
                    </svg>
                </div>
                <div>
                    <p class="font-display" style="font-size:15px; font-weight:700; color:white; line-height:1;">BIBLIO</p>
                    <p style="font-size:10px; color:rgba(255,255,255,0.3); letter-spacing:0.08em; text-transform:uppercase; margin-top:2px;">Administration</p>
                </div>
            </div>
        </div>

        <!-- Nav -->
        <nav style="padding: 16px 12px; flex:1; display:flex; flex-direction:column; gap:4px;">

            <p style="font-size:9.5px; font-weight:600; letter-spacing:0.12em; text-transform:uppercase;
                      color:rgba(255,255,255,0.2); padding: 4px 8px 8px;">Navigation</p>

            <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                Dashboard
            </a>

            <a href="{{ route('admin.livres.index') }}" class="nav-link {{ request()->routeIs('admin.livres.*') ? 'active' : '' }}">
                Livres
            </a>

            <a href="{{ route('admin.articles.index') }}" class="nav-link {{ request()->routeIs('admin.articles.*') ? 'active' : '' }}">
                Articles du Blog
            </a>

            <a href="{{ route('admin.lectures.index') }}" class="nav-link {{ request()->routeIs('admin.lectures.*') ? 'active' : '' }}">
                Lectures
            </a>

        </nav>

        <!-- User footer -->
        <div style="padding: 16px; border-top: 1px solid rgba(255,255,255,0.06);">
            <div style="display:flex; align-items:center; gap:10px; padding:8px 10px; border-radius:10px;
                        background:#063537;">
                <div style="width:30px; height:30px; border-radius:8px; background:#f97316;
                            display:flex; align-items:center; justify-content:center;
                            font-size:12px; font-weight:700; color:white; flex-shrink:0;">
                    A
                </div>
                <div style="flex:1; min-width:0;">
                    <p style="font-size:12.5px; font-weight:500; color:rgba(255,255,255,0.75); white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                        Administrateur
                    </p>
                    <p style="font-size:11px; color:rgba(255,255,255,0.3);">Super admin</p>
                </div>
                <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                   style="color:rgba(255,255,255,0.2); transition:color 0.15s;" title="Déconnexion">
                    <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 24 24">
                        <path d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75"/>
                    </svg>
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                    @csrf
                </form>
            </div>
        </div>

    </aside>

    <!-- ── MAIN CONTENT ── -->
    <main class="main-content">

        <!-- Topbar -->
        <div class="topbar">
            <div>
                <p class="font-display" style="font-size:22px; font-weight:700; color:#0C1F1F; line-height:1.2;">
                    @yield('page-title', 'Dashboard')
                </p>
                <p style="font-size:13px; color:#9ca3af; margin-top:3px;">
                    @yield('page-subtitle', 'Bienvenue dans votre espace d\'administration')
                </p>
            </div>
            <div style="display:flex; align-items:center; gap:10px;">
                <span class="badge-env">Admin</span>
                <a href="{{ url('/') }}" target="_blank"
                   style="display:flex; align-items:center; gap:6px; font-size:12.5px; color:#6b7280;
                          text-decoration:none; background:white; border:1px solid #e5e7eb;
                          padding:7px 14px; border-radius:8px; transition:all 0.15s;"
                   onmouseover="this.style.borderColor='#f97316';this.style.color='#f97316'"
                   onmouseout="this.style.borderColor='#e5e7eb';this.style.color='#6b7280'">
                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 24 24">
                        <path d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25"/>
                    </svg>
                    Voir le site
                </a>
            </div>
        </div>

        @yield('content')

    </main>

</body>
</html>