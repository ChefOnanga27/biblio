@extends('layouts.site')

@section('title', $categorie->nom . ' — ' . $siteNom)

@section('content')
<nav aria-label="Fil d'Ariane" class="flex items-center gap-2 text-[13px] text-voile">
    <a href="{{ route('categories.index') }}" class="hover:text-corail transition-colors">Catégories</a>
    <span aria-hidden="true">›</span>
    <span class="text-brume">{{ $categorie->nom }}</span>
</nav>

<h1 class="mt-4 text-[28px] sm:text-[32px] font-semibold">{{ $categorie->nom }}</h1>
<p class="mt-1 text-[14.5px] text-brume">{{ $categorie->livres_count }} livre(s) dans cette catégorie</p>

@if($livres->isNotEmpty())
    <div class="mt-8 grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 xl:grid-cols-6 gap-x-6 gap-y-10">
        @foreach($livres as $livre)
            @include('livre.cover')
        @endforeach
    </div>
@else
    <p class="mt-8 text-voile">Aucun livre dans cette catégorie pour le moment.</p>
@endif

<div class="mt-10">{{ $livres->links('partials.pagination') }}</div>
@endsection
