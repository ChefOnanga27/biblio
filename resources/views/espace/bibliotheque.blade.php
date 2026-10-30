@extends('espace.layout')

@section('title', 'Ma bibliothèque — ' . $siteNom)

@section('espace')
@php
    $vides = [
        'tous' => ['Votre bibliothèque est vide', 'Ouvrez un livre ou ajoutez-le à vos favoris pour le retrouver ici.'],
        'en-cours' => ['Aucune lecture en cours', 'Les livres que vous commencez dans le lecteur apparaîtront ici.'],
        'termines' => ['Aucun livre terminé', 'Allez jusqu\'à la dernière page d\'un livre pour le voir ici.'],
        'favoris' => ['Aucun favori', 'Touchez le cœur d\'un livre dans la recherche pour l\'ajouter à vos favoris.'],
    ];
@endphp

<h1 class="text-[28px] sm:text-[32px] font-semibold text-brume">Ma <span class="text-corail">bibliothèque</span></h1>

<nav aria-label="Filtrer ma bibliothèque" class="no-scrollbar mt-6 -mx-4 px-4 sm:mx-0 sm:px-0 overflow-x-auto border-b border-ligne">
    <ul class="flex gap-8 sm:gap-14 w-max">
        @foreach($onglets as $cle => $libelle)
            @php $actif = $onglet === $cle; @endphp
            <li>
                <a href="{{ route('espace.bibliotheque', $cle === 'tous' ? [] : ['onglet' => $cle]) }}" @if($actif) aria-current="page" @endif
                   class="relative inline-flex items-center gap-2 pb-4 text-[17px] sm:text-[19px] font-medium transition-colors
                          {{ $actif ? 'text-encre after:absolute after:inset-x-0 after:-bottom-px after:h-[3px] after:rounded-full after:bg-corail' : 'text-voile hover:text-brume' }}">
                    {{ $libelle }}
                    <span class="text-[12px] font-normal {{ $actif ? 'text-corail' : 'text-voile' }}">{{ $compteurs[$cle] }}</span>
                </a>
            </li>
        @endforeach
    </ul>
</nav>

@if($livres->isNotEmpty())
    <div class="mt-8 grid sm:grid-cols-2 xl:grid-cols-3 2xl:grid-cols-4 gap-5 lg:gap-8">
        @foreach($livres as $livre)
            @include('espace.carte', [
                'lecture' => $lectures->get($livre->id),
                'estFavori' => in_array($livre->id, $favorisIds),
                'ajouteLe' => $favorisDates->get($livre->id),
            ])
        @endforeach
    </div>
@else
    <div class="mt-8 text-center py-16 px-6 rounded-2xl border border-ligne bg-surface/60">
        <p class="text-[17px] font-semibold">{{ $vides[$onglet][0] }}</p>
        <p class="text-[14px] text-brume mt-1">{{ $vides[$onglet][1] }}</p>
        <a href="{{ route('livre.index') }}" class="btn btn-blanc mt-6">Explorer le catalogue</a>
    </div>
@endif
@endsection
