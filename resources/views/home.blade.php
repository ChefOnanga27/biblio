@extends('layouts.site')

@section('title', 'Accueil — ' . $siteNom)

@section('content')
@php $prenom = auth()->check() ? \Illuminate\Support\Str::of(auth()->user()->name)->trim()->explode(' ')->first() : null; @endphp

{{-- ═════════ CITATION DU JOUR + NOUVEAUTÉS ═════════ --}}
<div class="grid xl:grid-cols-[minmax(0,0.85fr)_minmax(0,1.3fr)] gap-6 lg:gap-10">

    <section class="relative overflow-hidden rounded-[10px] px-7 sm:px-9 py-8 min-h-[232px] flex flex-col
                    bg-gradient-to-br from-[#d4524a] via-[#b0446b] to-[#7d3a9a] shadow-[0_20px_50px_rgba(125,58,154,0.25)]"
             x-data="{ i: 0, total: {{ count($citations) }}, suivante() { this.i = (this.i + 1) % this.total } }"
             x-init="setInterval(() => suivante(), 9000)" aria-roledescription="carrousel" aria-label="Citation du jour">
        <h2 class="text-[24px] sm:text-[26px] font-medium">Citation du jour</h2>

        <div class="relative flex-1 mt-4 grid">
            @foreach($citations as $index => [$texte, $auteur, $source])
                <figure class="[grid-area:1/1] transition-opacity duration-500"
                        x-show="i === {{ $index }}" x-transition.opacity @if($index > 0) x-cloak @endif>
                    <blockquote class="text-[17px] sm:text-[19px] leading-relaxed text-white/95">« {{ $texte }} »</blockquote>
                    <figcaption class="mt-4 text-right text-[16px] sm:text-[18px] text-white/90">
                        — {{ $auteur }} <span class="block text-[12px] text-white/60 italic">{{ $source }}</span>
                    </figcaption>
                </figure>
            @endforeach
        </div>

        <div class="mt-4 flex gap-2.5" role="tablist" aria-label="Choisir une citation">
            @foreach($citations as $index => $citation)
                <button type="button" role="tab" @click="i = {{ $index }}" :aria-selected="(i === {{ $index }}).toString()"
                        aria-label="Citation {{ $index + 1 }}"
                        class="w-3 h-3 rounded-full border-[1.5px] border-white cursor-pointer transition-colors"
                        :class="i === {{ $index }} ? 'bg-white' : 'bg-transparent'"></button>
            @endforeach
        </div>
    </section>

    @if($nouveautes->isNotEmpty())
        <section class="reveal is-visible relative overflow-hidden rounded-[10px] border border-[#c24b6b]/60 flex min-h-[232px]" data-carousel aria-label="Nouveautés">
            <a href="{{ route('livre.index', ['tri' => 'nouveautes']) }}"
               class="flex-none w-[52px] sm:w-[58px] bg-gradient-to-b from-[#d4524a] to-[#7d3a9a] flex items-center justify-center hover:brightness-110 transition">
                <span class="[writing-mode:vertical-rl] rotate-180 text-[20px] sm:text-[23px] font-medium tracking-wide whitespace-nowrap">Nouveautés</span>
            </a>
            <div class="relative min-w-0 flex-1">
                <div data-track class="no-scrollbar h-full flex items-center gap-6 sm:gap-9 overflow-x-auto snap-x scroll-smooth px-5 py-4">
                    @foreach($nouveautes as $livre)
                        <a href="{{ route('livres.show', $livre) }}" title="{{ $livre->titre }} — {{ $livre->auteur }}"
                           class="flex-none w-[104px] sm:w-[128px] snap-start group">
                            <div class="livre-3d anime">
                                <div class="livre-3d__couverture aspect-[2/3] bg-surface-2">
                                    @if($livre->image)
                                        <img src="{{ asset('storage/' . $livre->image) }}" alt="{{ $livre->titre }}" loading="lazy" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-end p-2.5 pl-[14%] bg-gradient-to-br from-[#2a3350] to-[#151b2d] text-[12px] font-semibold leading-snug">{{ $livre->titre }}</div>
                                    @endif
                                    <span class="livre-3d__dos" aria-hidden="true"></span>
                                </div>
                                <span class="livre-3d__pages" aria-hidden="true"></span>
                            </div>
                        </a>
                    @endforeach
                </div>
                <button type="button" data-prev aria-label="Nouveautés précédentes" class="carousel-btn hidden sm:inline-flex absolute left-2 top-1/2 -translate-y-1/2 bg-black/50">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M15 19l-7-7 7-7"/></svg>
                </button>
                <button type="button" data-next aria-label="Nouveautés suivantes" class="carousel-btn hidden sm:inline-flex absolute right-2 top-1/2 -translate-y-1/2 bg-black/50">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7"/></svg>
                </button>
            </div>
        </section>
    @endif
</div>

{{-- ═════════ SALUTATION ═════════ --}}
<h1 class="mt-12 text-[30px] sm:text-[36px] font-semibold text-brume"
    x-data="{ moment: 'Bonjour' }"
    x-init="const h = new Date().getHours(); moment = h >= 18 || h < 4 ? 'Bonsoir' : 'Bonjour'">
    <span x-text="moment">Bonjour</span>{{ $prenom ? ' ' . $prenom : '' }}
</h1>

@if($nouveautes->isEmpty())
    <p class="mt-6 text-[15px] text-voile">La collection arrive bientôt : aucun livre n'est encore disponible.</p>
@endif

{{-- ═════════ CARROUSELS ═════════ --}}
@include('partials.carrousel', [
    'titre' => $recommandes->isNotEmpty() ? 'Recommandés pour vous' : 'Livres populaires',
    'livres' => $recommandes->isNotEmpty() ? $recommandes : $populaires,
    'lien' => route('livre.index', ['tri' => 'populaires']),
    'conteneur' => 'mt-6',
])

@include('partials.carrousel', [
    'titre' => 'Lectures récentes',
    'livres' => $lecturesRecentes,
    'lien' => route('espace.historique'),
])

@include('partials.carrousel', [
    'titre' => 'Tendances',
    'sousTitre' => 'Les plus lus ces 30 derniers jours',
    'livres' => $tendances,
])

@if($recommandes->isNotEmpty())
    @include('partials.carrousel', [
        'titre' => 'Livres populaires',
        'livres' => $populaires,
        'lien' => route('livre.index', ['tri' => 'populaires']),
    ])
@endif

@foreach($rangeesCategories as $rangee)
    @include('partials.carrousel', [
        'titre' => $rangee['categorie']->nom,
        'livres' => $rangee['livres'],
        'lien' => route('livre.index', ['categorie' => $rangee['categorie']->id]),
    ])
@endforeach

@guest
    <section class="reveal mt-16 rounded-[14px] border border-ligne bg-surface/70 px-6 sm:px-9 py-8 flex flex-col md:flex-row md:items-center gap-6">
        <div class="flex-1">
            <h2 class="text-[22px] font-semibold">Rejoignez la communauté des lecteurs</h2>
            <p class="mt-1.5 text-[14.5px] text-brume max-w-xl">Créez un compte gratuit pour lire en ligne, garder vos favoris et reprendre vos livres là où vous les avez laissés.</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('register') }}" class="btn btn-blanc">Créer un compte</a>
            <a href="{{ route('login') }}" class="btn btn-discret">Se connecter</a>
        </div>
    </section>
@endguest

@endsection
