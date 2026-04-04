<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <title>@yield('title', 'Biblio Admin')</title>
</head>

<body class="bg-gray-100">

    <div class="flex">

        <!-- Sidebar -->
        <aside class="w-64 bg-orange-500 text-white min-h-screen p-4">
            <h2 class="text-xl font-bold mb-6">📚 Admin</h2>

            <nav class="space-y-2">
                <a href="{{ route('admin.dashboard') }}" class="block p-2 rounded hover:bg-gray-700">Dashboard</a>
                <a href="{{ route('admin.livres.index') }}" class="block p-2 rounded hover:bg-gray-700">Livres</a>
                <a href="{{ route('admin.articles.index') }}" class="block p-2 rounded hover:bg-gray-700">Articles du Blog</a>
                <a href="{{ route('admin.lectures.index') }}" class="block p-2 rounded hover:bg-gray-700">Lectures</a>
            </nav>
        </aside>

        <!-- Content -->
        <main class="flex-1 p-6">
            @yield('content')
        </main>

    </div>

</body>
</html>