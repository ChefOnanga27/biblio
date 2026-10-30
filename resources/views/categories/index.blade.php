@extends('layouts.site')

@section('title', 'Catégories — ' . $siteNom)

@section('content')
<h1 class="text-[28px] sm:text-[32px] font-semibold text-brume">Catégories</h1>

<div class="mt-8 grid sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
    @forelse($categories as $categorie)
        <a href="{{ route('categories.show', $categorie) }}"
           class="group rounded-[12px] border border-ligne bg-surface/60 p-5 hover:border-corail/40 transition-colors">
            <h2 class="text-[17px] font-semibold group-hover:text-corail transition-colors">{{ $categorie->nom }}</h2>
            <p class="mt-1 text-[13.5px] text-brume">{{ $categorie->livres_count }} livre(s)</p>
        </a>
    @empty
        <p class="text-voile">Aucune catégorie pour le moment.</p>
    @endforelse
</div>
@endsection
