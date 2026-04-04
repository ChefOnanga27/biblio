@extends('base')

@section('title', 'Catégories de livres')

@section('content')
    <div class="max-w-5xl mx-auto mt-10">
        <h1 class="text-3xl font-bold mb-6">Catégories de livres</h1>

        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4">
            @forelse($categories as $categorie)
                <a href="{{ route('categories.show', $categorie) }}" class="block p-4 bg-white rounded shadow hover:shadow-lg transition">
                    <h2 class="text-xl font-semibold">{{ $categorie->nom }}</h2>
                    <p class="text-black">{{ $categorie->livres_count }} livre(s)</p>
                </a>
            @empty
                <p class="text-gray-500">Aucune catégorie pour le moment.</p>
            @endforelse
        </div>
    </div>
@endsection