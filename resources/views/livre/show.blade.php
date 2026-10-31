@extends('layouts.site')

@section('title', $livre->titre . ' — ' . $siteNom)

@section('content')
@php
    $date = $livre->date_publication ? \Carbon\Carbon::parse($livre->date_publication) : null;
    $moyenne = $livre->notes_count ? round((float) $livre->notes_avg_note, 1) : null;
    $infos = collect([
        ['Année', $date?->year],
        ['Pages', $livre->nombre_pages ? number_format($livre->nombre_pages, 0, ',', ' ') : null],
        ['Langue', $livre->langue],
        ['Lecteurs', $livre->lectures_count ?: null],
    ])->filter(fn ($info) => $info[1] !== null);

    $fiche = collect([
        'Titre' => $livre->titre,
        'Auteur' => $livre->auteur,
        'Catégorie' => $livre->categorie?->nom,
        'Genre' => $livre->genre?->nom,
        'Date de publication' => $date?->locale('fr')->translatedFormat('j F Y'),
        'Langue' => $livre->langue,
        'Nombre de pages' => $livre->nombre_pages,
        'ISBN' => $livre->isbn,
        'Disponibilité' => $livre->disponible ? 'Disponible' : 'Indisponible',
    ])->filter(fn ($valeur) => filled($valeur));
@endphp

<nav aria-label="Fil d'Ariane" class="flex items-center gap-2 text-[13px] text-voile min-w-0">
    <a href="{{ route('livre.index') }}" class="hover:text-corail transition-colors">Rechercher</a>
    @if($livre->categorie)
        <span aria-hidden="true">›</span>
        <a href="{{ route('livre.index', ['categorie' => $livre->categorie_id]) }}" class="hover:text-corail transition-colors">{{ $livre->categorie->nom }}</a>
    @endif
    <span aria-hidden="true">›</span>
    <span class="text-brume truncate">{{ $livre->titre }}</span>
</nav>

{{-- ═════════ EN-TÊTE ═════════ --}}
<section class="relative mt-6 overflow-hidden rounded-[16px] border border-ligne bg-surface/50">
    @if($livre->image)
        <img src="{{ media_url($livre->image) }}" alt="" aria-hidden="true" class="absolute inset-0 w-full h-full object-cover opacity-25 blur-3xl scale-125">
    @endif
    <div class="absolute inset-0 bg-gradient-to-r from-[#070b14]/95 via-[#070b14]/80 to-[#070b14]/60"></div>

    <div class="relative grid md:grid-cols-[minmax(0,260px)_minmax(0,1fr)] gap-8 md:gap-12 p-6 sm:p-10 items-start">
        <div class="mx-auto md:mx-0 w-[190px] sm:w-[230px] md:w-full">
            <div class="livre-3d">
                <div class="livre-3d__couverture aspect-[2/3] bg-surface-2">
                    @if($livre->image)
                        <img src="{{ media_url($livre->image) }}" alt="Couverture de {{ $livre->titre }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex flex-col justify-end p-5 pl-[14%] bg-gradient-to-br from-[#2a3350] via-[#1f2740] to-[#151b2d]">
                            <span class="w-8 h-1 rounded-full bg-corail mb-3"></span>
                            <span class="text-[20px] font-semibold leading-snug">{{ $livre->titre }}</span>
                            <span class="text-[13px] text-brume mt-1">{{ $livre->auteur }}</span>
                        </div>
                    @endif
                    <span class="livre-3d__dos" aria-hidden="true"></span>
                </div>
                <span class="livre-3d__pages" aria-hidden="true"></span>
            </div>
        </div>

        <div class="min-w-0 text-center md:text-left">
            <h1 class="text-[28px] sm:text-[38px] lg:text-[44px] font-semibold leading-[1.1] tracking-tight">{{ $livre->titre }}</h1>
            <p class="mt-2 text-[16px] sm:text-[18px] text-brume">
                par
                @if($livre->auteur_id)
                    <a href="{{ route('livre.index', ['auteur' => $livre->auteur_id]) }}" class="font-medium text-encre hover:text-corail transition-colors">{{ $livre->auteur }}</a>
                @else
                    <span class="font-medium text-encre">{{ $livre->auteur }}</span>
                @endif
            </p>

            {{-- Note moyenne --}}
            <p class="mt-3 flex items-center justify-center md:justify-start gap-2 text-[15px]">
                @if($moyenne)
                    <span class="text-corail" aria-hidden="true">@for($i = 1; $i <= 5; $i++){{ $i <= round($moyenne) ? '★' : '☆' }}@endfor</span>
                    <span class="font-semibold">{{ number_format($moyenne, 1, ',', '') }}</span><span class="text-voile">/5 · {{ $livre->notes_count }} note{{ $livre->notes_count > 1 ? 's' : '' }}</span>
                @else
                    <span class="text-voile">Pas encore noté</span>
                @endif
            </p>

            <div class="mt-5 flex flex-wrap justify-center md:justify-start gap-2">
                @if($livre->categorie)
                    <a href="{{ route('livre.index', ['categorie' => $livre->categorie_id]) }}" class="text-[13px] bg-white/[0.07] hover:bg-white/[0.12] rounded-full px-3.5 py-1.5 transition-colors">{{ $livre->categorie->nom }}</a>
                @endif
                @if($livre->genre)
                    <a href="{{ route('livre.index', ['genre' => $livre->genre_id]) }}" class="text-[13px] italic text-brume bg-white/[0.07] hover:bg-white/[0.12] rounded-full px-3.5 py-1.5 transition-colors">{{ $livre->genre->nom }}</a>
                @endif
                <span class="text-[13px] font-medium rounded px-3 py-1.5 {{ $livre->disponible ? 'bg-menthe text-[#0b1020]' : 'bg-white/10 text-brume' }}">
                    {{ $livre->disponible ? 'Disponible' : 'Indisponible' }}
                </span>
            </div>

            @if($livre->description)
                <p class="mt-6 text-[15.5px] text-brume leading-relaxed max-w-2xl mx-auto md:mx-0 line-clamp-4">{{ $livre->description }}</p>
            @endif

            @if($infos->isNotEmpty())
                <dl class="mt-7 grid grid-cols-2 sm:grid-cols-4 gap-3 max-w-2xl mx-auto md:mx-0 text-left">
                    @foreach($infos as [$libelle, $valeur])
                        <div class="rounded-[10px] bg-white/[0.05] border border-ligne px-4 py-3">
                            <dt class="text-[12px] text-voile">{{ $libelle }}</dt>
                            <dd class="mt-0.5 text-[16px] font-semibold truncate">{{ $valeur }}</dd>
                        </div>
                    @endforeach
                </dl>
            @endif

            {{-- Actions --}}
            <div class="mt-8 flex flex-col sm:flex-row flex-wrap justify-center md:justify-start gap-3"
                 x-data="{ favori: {{ $estFavori ? 'true' : 'false' }}, occupe: false, message: '' }">
                @if($livre->pdf_path)
                    <a href="{{ route('livres.lire', $livre) }}" class="btn btn-blanc !px-8 !py-4 !text-[15px]">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M8 5v14l11-7z"/></svg>
                        @if($lecture?->estEnCours())
                            Continuer la lecture · {{ $lecture->progression }} %
                        @else
                            Lire maintenant
                        @endif
                    </a>
                @else
                    <span class="btn btn-discret !px-8 !py-4 cursor-not-allowed opacity-60" aria-disabled="true">Lecture bientôt disponible</span>
                @endif

                <form method="POST" action="{{ route('livres.favori', $livre) }}"
                      @submit.prevent="
                          occupe = true;
                          fetch($el.action, { method: 'POST', headers: { 'Accept': 'application/json' }, body: new FormData($el) })
                              .then(r => r.ok ? r.json() : Promise.reject())
                              .then(d => { favori = d.favori; message = d.message; setTimeout(() => message = '', 2500); })
                              .catch(() => $el.submit())
                              .finally(() => occupe = false)">
                    @csrf
                    <button type="submit" :disabled="occupe" aria-pressed="{{ $estFavori ? 'true' : 'false' }}" :aria-pressed="favori.toString()"
                            class="btn btn-contour w-full !px-7 !py-4 !text-[15px]">
                        <svg width="17" height="17" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" fill="{{ $estFavori ? 'currentColor' : 'none' }}" :fill="favori ? 'currentColor' : 'none'" aria-hidden="true">
                            <path d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z"/>
                        </svg>
                        <span x-text="favori ? 'Dans mes favoris' : 'Ajouter aux favoris'">{{ $estFavori ? 'Dans mes favoris' : 'Ajouter aux favoris' }}</span>
                    </button>
                </form>
                <p x-show="message" x-text="message" x-transition role="status" class="sm:self-center text-[13px] text-brume" style="display:none"></p>
            </div>
        </div>
    </div>
</section>

{{-- ═════════ RÉSUMÉ, NOTE & INFORMATIONS ═════════ --}}
<div class="reveal mt-8 grid lg:grid-cols-[minmax(0,1fr)_380px] gap-6 lg:gap-8 items-start">
    <article class="rounded-[14px] bg-surface/60 border border-ligne p-6 sm:p-8">
        <h2 class="text-[20px] font-semibold">Résumé</h2>
        @if($livre->description)
            <div class="mt-4" x-data="{ ouvert: false, long: false }" x-init="long = $refs.texte.scrollHeight > $refs.texte.clientHeight + 4">
                <p x-ref="texte" class="text-[15.5px] text-brume leading-[1.8] whitespace-pre-line" :class="ouvert ? '' : 'line-clamp-[10]'">{{ $livre->description }}</p>
                <button type="button" x-show="long" @click="ouvert = ! ouvert" style="display:none"
                        class="mt-3 text-[14px] font-semibold text-corail hover:text-encre cursor-pointer" x-text="ouvert ? 'Réduire' : 'Lire la suite'"></button>
            </div>
        @else
            <p class="mt-4 text-[14.5px] text-voile">Aucun résumé n'est encore disponible pour ce livre.</p>
        @endif

        {{-- Note du lecteur --}}
        <form method="POST" action="{{ route('livres.note', $livre) }}" class="mt-8 pt-6 border-t border-ligne"
              x-data="{ note: {{ (int) $maNote }}, survol: 0, message: '' }"
              @submit.prevent="
                  fetch($el.action, { method: 'POST', headers: { 'Accept': 'application/json' }, body: new FormData($el) })
                      .then(r => r.ok ? r.json() : Promise.reject())
                      .then(d => { message = 'Merci ! Moyenne : ' + String(d.moyenne).replace('.', ',') + '/5 (' + d.total + ' note' + (d.total > 1 ? 's' : '') + ')'; })
                      .catch(() => $el.submit())">
            @csrf
            <fieldset>
                <legend class="text-[16px] font-semibold">{{ $maNote ? 'Votre note' : 'Notez ce livre' }}</legend>
                <div class="mt-3 flex items-center gap-1" @mouseleave="survol = 0">
                    @for($i = 1; $i <= 5; $i++)
                        <label class="cursor-pointer" @mouseenter="survol = {{ $i }}">
                            <input type="radio" name="note" value="{{ $i }}" class="sr-only peer" @checked($maNote === $i)
                                   @change="note = {{ $i }}; $nextTick(() => $root.requestSubmit())">
                            <span class="block text-[30px] leading-none transition-colors peer-focus-visible:outline peer-focus-visible:outline-2 peer-focus-visible:outline-corail rounded"
                                  :class="(survol || note) >= {{ $i }} ? 'text-corail' : 'text-white/20'">★</span>
                            <span class="sr-only">{{ $i }} étoile{{ $i > 1 ? 's' : '' }}</span>
                        </label>
                    @endfor
                </div>
            </fieldset>
            <noscript><button type="submit" class="btn btn-discret btn-sm mt-3">Enregistrer ma note</button></noscript>
            <p x-show="message" x-text="message" role="status" class="mt-2 text-[13px] text-brume" style="display:none"></p>
        </form>
    </article>

    <aside class="rounded-[14px] bg-surface/60 border border-ligne p-6 sm:p-7">
        <h2 class="text-[20px] font-semibold mb-3">Informations</h2>
        <dl class="divide-y divide-ligne">
            @foreach($fiche as $libelle => $valeur)
                <div class="flex justify-between gap-4 py-3 text-[14px]">
                    <dt class="text-voile flex-none">{{ $libelle }}</dt>
                    <dd class="text-encre text-right min-w-0 break-words">{{ $valeur }}</dd>
                </div>
            @endforeach
        </dl>
    </aside>
</div>

@include('partials.carrousel', [
    'titre' => 'Vous aimerez aussi',
    'sousTitre' => $livre->categorie ? 'Dans la même catégorie : ' . $livre->categorie->nom : 'Du même auteur',
    'livres' => $similaires,
    'lien' => $livre->categorie_id ? route('livre.index', ['categorie' => $livre->categorie_id]) : null,
])
@endsection
