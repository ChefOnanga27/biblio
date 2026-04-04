<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <title>@yield('title', 'Biblio')</title>
</head>

<body class="flex flex-col min-h-screen">

    <!-- NAVBAR -->
    <nav class="bg-orange-500 px-6 py-4 flex items-center justify-between shadow-md">

        <!-- Logo -->
        <div class="flex items-center space-x-2">
            <img src="{{ asset('Digital.png') }}" alt="Logo" class="w-12 h-12 rounded-full">
        </div>

        <!-- Menu -->
        <ul class="hidden md:flex space-x-8 text-white font-medium">
            <li><a href="{{ route('home') }}" class="hover:text-gray-200">Accueil</a></li>
            <li><a href="{{ route('livre.index') }}" class="hover:text-gray-200">Bibliothèque</a></li>
            <li><a href="{{ route('articles.index') }}" class="hover:text-gray-200">Blog</a></li>
            <li><a href="{{ route('contact') }}" class="hover:text-gray-200">Contact</a></li>
            <li><a href="{{ route('about') }}" class="hover:text-gray-200">A propos</a></li>
        </ul>

        <!-- Actions -->
        <div class="flex items-center space-x-3">
            @auth
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="bg-[#063537] text-white px-4 py-2 rounded-md text-sm font-semibold hover:bg-red-700">
                        Déconnexion
                    </button>
                </form>
            @else
                <a href="{{ route('register') }}"
                   class="bg-cyan-500 text-white px-4 py-2 rounded-md text-sm font-semibold hover:bg-cyan-600">
                    S'inscrire
                </a>
                <a href="{{ route('login') }}"
                   class="bg-[#063537] text-white px-4 py-2 rounded-md text-sm font-semibold hover:bg-green-800">
                    Se connecter
                </a>
            @endauth
        </div>

    </nav>

    <!-- CONTENT -->
    <main class="flex-grow">
        @yield('content')
    </main>

    <!-- FOOTER -->
    <footer class="bg-[#063537] text-white text-center py-6">
    <p class="text-sm">
        &copy; {{ date('Y') }} Biblio. Tous droits réservés.
    </p>
</footer>

</body>
</html>