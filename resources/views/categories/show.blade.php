@extends('base')

@section('title', 'Catégorie : ' . $categorie->nom)

@section('content')
    <div class="max-w-7xl mx-auto mt-10">
        <h1 class="text-3xl font-bold mb-4">{{ $categorie->nom }}</h1>

        <p class="text-gray-600 mb-6">{{ $categorie->livres_count }} livre(s) dans cette catégorie</p>

        <div class="grid sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
            @forelse($livres as $livre)
                <div>
                    @include('livre.card')
                </div>
            @empty
                <p class="text-gray-500">Aucun livre dans cette catégorie pour le moment.</p>
            @endforelse
        </div>

        <div class="mt-6">{{ $livres->links() }}</div>
    </div>
@endsection