@extends('espace.layout')

@section('title', 'Mon espace — ' . $siteNom)

@section('espace')
@php $prenom = \Illuminate\Support\Str::of(auth()->user()->name)->trim()->explode(' ')->first(); @endphp

<header class="mb-7">
    <h1 class="text-[28px] sm:text-[32px] font-semibold text-brume">Bonjour <span class="text-encre">{{ $prenom }}</span></h1>
    <p class="text-[15px] text-voile mt-1">Bonne lecture ! Retrouvez ici vos livres et votre progression.</p>
</header>

{{-- ═════════ REPRENDRE LA LECTURE ═════════ --}}
@if($reprise)
    @php $livre = $reprise->livre; @endphp
    <section class="relative overflow-hidden rounded-[12px] p-6 sm:p-8 flex gap-6 sm:gap-9 items-center
                    bg-gradient-to-br from-[#d4524a] via-[#b0446b] to-[#7d3a9a] shadow-[0_20px_50px_rgba(125,58,154,0.25)]">
        <a href="{{ route('livres.show', $livre) }}" class="livre-3d block flex-none w-[96px] sm:w-[124px]">
            <span class="livre-3d__couverture block aspect-[2/3] bg-surface-2">
                @if($livre->image)
                    <img src="{{ media_url($livre->image) }}" alt="{{ $livre->titre }}" class="w-full h-full object-cover">
                @endif
                <span class="livre-3d__dos" aria-hidden="true"></span>
            </span>
            <span class="livre-3d__pages" aria-hidden="true"></span>
        </a>

        <div class="min-w-0 flex-1">
            <p class="text-[12px] font-semibold uppercase tracking-[0.14em] text-white/70">Reprendre la lecture</p>
            <h2 class="mt-2 text-[20px] sm:text-[24px] font-semibold leading-snug line-clamp-2">{{ $livre->titre }}</h2>
            <p class="text-[14px] text-white/75 truncate">{{ $livre->auteur }}</p>

            <div class="mt-5 max-w-md">
                <div class="flex justify-between text-[12.5px] text-white/75 mb-1.5">
                    <span>Page {{ $reprise->page_actuelle }} sur {{ $reprise->pages_total }}</span>
                    <span class="font-semibold text-white">{{ $reprise->progression }} %</span>
                </div>
                <div class="h-1.5 rounded-full bg-white/25 overflow-hidden">
                    <div class="h-full rounded-full bg-white" style="width: {{ $reprise->progression }}%"></div>
                </div>
            </div>

            @if($livre->pdf_path)
                <a href="{{ route('livres.lire', $livre) }}" class="btn btn-blanc mt-6">
                    <svg width="11" height="11" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M8 5v14l11-7z"/></svg>
                    Continuer
                </a>
            @endif
        </div>
    </section>
@else
    <section class="rounded-[12px] p-6 sm:p-8 bg-gradient-to-br from-[#d4524a] via-[#b0446b] to-[#7d3a9a]">
        <h2 class="text-[22px] font-semibold">Aucune lecture en cours</h2>
        <p class="mt-1 text-[14.5px] text-white/80 max-w-md">Parcourez le catalogue et ouvrez un livre : votre progression apparaîtra ici.</p>
        <a href="{{ route('livre.index') }}" class="btn btn-blanc mt-6">Explorer le catalogue</a>
    </section>
@endif

{{-- ═════════ CHIFFRES ═════════ --}}
<section class="mt-6 grid grid-cols-2 sm:grid-cols-4 gap-3 sm:gap-4">
    @foreach($stats as $libelle => $valeur)
        @php
            $lien = match ($libelle) {
                'En cours' => route('espace.bibliotheque', ['onglet' => 'en-cours']),
                'Terminés' => route('espace.bibliotheque', ['onglet' => 'termines']),
                'Favoris' => route('espace.bibliotheque', ['onglet' => 'favoris']),
                default => route('espace.historique'),
            };
        @endphp
        <a href="{{ $lien }}" class="rounded-[10px] bg-surface/70 border border-ligne p-5 hover:border-corail/40 transition-colors">
            <p class="text-[28px] font-semibold leading-none">{{ $valeur }}</p>
            <p class="text-[13.5px] text-brume mt-2">{{ $libelle }}</p>
        </a>
    @endforeach
</section>

@include('partials.carrousel', ['titre' => 'En cours de lecture', 'livres' => $enCours->pluck('livre'), 'lien' => route('espace.bibliotheque', ['onglet' => 'en-cours'])])
@include('partials.carrousel', ['titre' => 'Mes favoris', 'livres' => $favoris, 'lien' => route('espace.bibliotheque', ['onglet' => 'favoris'])])
@include('partials.carrousel', ['titre' => 'Recommandés pour vous', 'sousTitre' => 'D\'après les catégories que vous lisez', 'livres' => $recommandes])
@endsection
