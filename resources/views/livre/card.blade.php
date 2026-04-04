<div class="bg-white shadow-md hover:shadow-xl transition duration-300 overflow-hidden">

    <!-- Image -->
    <div class="relative">
        <img src="{{ asset('storage/' . $livre->image) }}" 
             alt="{{ $livre->titre }}" 
             class="w-full h-48 object-cover">

        <!-- Badge statut -->
        <span class="absolute top-3 right-3 px-3 py-1 text-xs font-semibold rounded-full
            {{ $livre->disponible ? 'bg-green-500 text-white' : 'bg-red-500 text-white' }}">
            {{ $livre->disponible ? 'Disponible' : 'Indisponible' }}
        </span>
    </div>

    <!-- Contenu -->
    <div class="p-5">

        <!-- Titre -->
        <h2 class="text-lg font-bold text-gray-800 mb-2 line-clamp-1">
            {{ $livre->titre }}
        </h2>

        <!-- Auteur -->
        <p class="text-sm text-gray-500 mb-2">
            ✍️ {{ $livre->auteur }}
        </p>

        <!-- Description -->
        <p class="text-sm text-gray-600 mb-4">
            {{ \Illuminate\Support\Str::limit($livre->description, 100) }}
        </p>

        <!-- Infos -->
        <div class="text-xs text-gray-500 space-y-1 mb-4">
            <p>📚 ISBN : {{ $livre->isbn }}</p>
            <p>📅 Publié le : 
                {{ $livre->date_publication 
                    ? \Carbon\Carbon::parse($livre->date_publication)->format('d/m/Y') 
                    : 'N/A' }}
            </p>
        </div>

        <!-- Bouton -->
        @auth
            <a href="{{ route('livres.show', $livre) }}" 
               class="block w-full text-center bg-orange-500 text-white py-2 rounded-md text-sm font-semibold hover:bg-orange-600 transition">
                voir les détails
            </a>
        @else
            <a href="{{ route('login') }}" 
               class="block w-full text-center bg-gray-700 text-white py-2 rounded-md text-sm font-semibold hover:bg-gray-800 transition">
                Connectez-vous pour lire
            </a>
        @endauth

    </div>

</div>