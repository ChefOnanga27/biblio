@extends('base')

@section('title', 'Bibliothèque')

@section('content')
<section class="relative h-auto py-20 flex items-center justify-center bg-white/50 overflow-hidden border-b border-gray-300 mb-10 shadow-sm">
    <div class="absolute inset-0">
        <img src="/amadou.webp" alt="Background"
             class="w-full h-full object-cover">
        <!-- Overlay blanc pour lisibilité -->
        <div class="absolute inset-0 bg-white/10 backdrop-blur-sm"></div>
    </div>

    <!-- Décor -->
    <div class="absolute -top-20 -left-20 w-72 h-72 bg-indigo-100 rounded-full blur-3xl opacity-40"></div>
    <div class="absolute bottom-0 right-0 w-72 h-72 bg-purple-100 rounded-full blur-3xl opacity-40"></div>

    <!-- Contenu -->
    <div class="relative z-10 w-full max-w-4xl px-4 text-center">

        <!-- Titre -->
        <h1 class="text-4xl md:text-5xl font-bold shadow-slate-50 text-black mb-4">
         Trouvez votre prochain livre
        </h1>

        <p class="text-black mb-10">
            Recherchez parmi des centaines de livres, auteurs et catégories
        </p>

        <!-- FORMULAIRE COMPLET -->
        <form method="GET" action="{{ route('livre.index') }}"
              class="space-y-6">

            <!-- 🔍 Barre de recherche -->
            <div class="flex items-center bg-white shadow-xl rounded-2xl overflow-hidden border border-gray-200">
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="🔍 Rechercher un livre..."
                    class="w-full p-4 text-lg focus:outline-none">

                <button type="submit"
                    class="bg-green-500 text-white px-6 py-4 hover:bg-green-600 transition">
                    Rechercher
                </button>
            </div>

            <!-- 🎯 FILTRES -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">

                <!-- Catégorie -->
                <select name="categorie"
                    class="p-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-indigo-500">
                    <option value="">Catégories</option>
                    @foreach($categories as $categorie)
                        <option value="{{ $categorie->id }}" {{ request('categorie') == $categorie->id ? 'selected' : '' }}>
                            {{ $categorie->nom }}
                        </option>
                    @endforeach
                </select>

                <!-- Genre -->
                <select name="genre"
                    class="p-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-indigo-500">
                    <option value="">Genres</option>
                    @foreach($genres as $genre)
                        <option value="{{ $genre->id }}" {{ request('genre') == $genre->id ? 'selected' : '' }}>
                            {{ $genre->nom }}
                        </option>
                    @endforeach
                </select>

                <!-- Auteur -->
                <select name="auteur"
                    class="p-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-indigo-500">
                    <option value="">Auteurs</option>
                    @foreach($auteurs as $auteur)
                        <option value="{{ $auteur->id }}" {{ request('auteur') == $auteur->id ? 'selected' : '' }}>
                            {{ $auteur->nom }}
                        </option>
                    @endforeach
                </select>

                <!-- Année -->
                <input type="number" name="annee" value="{{ request('annee') }}"
                    placeholder="Année"
                    class="p-3 rounded-xl border border-gray-300 focus:ring-2 focus:ring-indigo-500">
            </div>

        </form>

    </div>

</section>
<section class="max-w-7xl mx-auto px-4">

    <!-- Affichage des livres -->
    <div class="grid sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        @foreach($livres as $livre)
            <div>
                @include('livre.card')
            </div>
        @endforeach
    </div>

    <!-- Pagination -->
    <div class="mt-8">
        {{ $livres->links() }}
    </div>

</section>

@endsection