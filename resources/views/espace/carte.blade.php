{{--
    Carte « Ma bibliothèque » (maquette Shelf) : couverture à gauche, suivi de lecture à droite.
    Paramètres : $livre, $lecture (Lecture ou null), $estFavori (bool), $ajouteLe (date d'ajout aux favoris ou null)
--}}
@php
    $termine = $lecture?->estTerminee();
    $enCours = $lecture?->estEnCours();
    $action = $termine ? 'Relire' : ($enCours ? 'Continuer' : 'Lire');
    $annee = $livre->date_publication ? \Carbon\Carbon::parse($livre->date_publication)->year : null;
    $date = fn ($d) => $d?->locale('fr')->translatedFormat('j M Y · H:i');
@endphp

<article class="relative grid grid-cols-[112px_minmax(0,1fr)] sm:grid-cols-[124px_minmax(0,1fr)] gap-5 rounded-[10px] bg-surface/70 border border-ligne p-4">
    @if($estFavori)
        <span class="absolute top-3 right-3 text-[#ff4d4d]" title="Favori">
            <svg width="16" height="16" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z"/></svg>
            <span class="sr-only">Favori</span>
        </span>
    @endif

    {{-- Couverture + titre --}}
    <div class="min-w-0">
        <a href="{{ route('livres.show', $livre) }}" class="livre-3d anime block group">
            <span class="livre-3d__couverture block aspect-[2/3] bg-surface-2">
                @if($livre->image)
                    <img src="{{ asset('storage/' . $livre->image) }}" alt="{{ $livre->titre }}" loading="lazy" class="w-full h-full object-cover">
                @else
                    <span class="w-full h-full flex items-end p-2.5 pl-[14%] bg-gradient-to-br from-[#2a3350] to-[#151b2d] text-[12px] font-semibold leading-snug">{{ $livre->titre }}</span>
                @endif
                <span class="livre-3d__dos" aria-hidden="true"></span>
            </span>
            <span class="livre-3d__pages" aria-hidden="true"></span>
        </a>
        <h3 class="mt-3 text-[13px] text-brume leading-snug line-clamp-2">
            <a href="{{ route('livres.show', $livre) }}" class="hover:text-encre">{{ $livre->titre }}</a>
        </h3>
        <p class="text-[11.5px] text-voile truncate">{{ $livre->auteur }}{{ $annee ? ', ' . $annee : '' }}</p>
    </div>

    {{-- Suivi --}}
    <div class="min-w-0 flex flex-col">
        <dl class="space-y-3 text-[12px]">
            <div>
                <dt class="text-[14.5px] text-voile">{{ $lecture ? 'Dernière lecture' : 'Ajouté le' }}</dt>
                <dd class="mt-1 text-encre">{{ $date($lecture?->read_at ?? $ajouteLe) ?? '—' }}</dd>
            </div>
            <div>
                <dt class="text-[14.5px] text-voile">Progression</dt>
                <dd class="mt-1 text-encre">
                    @if($termine)
                        Terminé le {{ $lecture->termine_at->locale('fr')->translatedFormat('j M Y') }}
                    @elseif($enCours)
                        {{ $lecture->progression }} % · page {{ $lecture->page_actuelle }}/{{ $lecture->pages_total }}
                    @else
                        Pas commencé
                    @endif
                </dd>
            </div>
        </dl>

        @if($enCours)
            <div class="mt-3 h-1.5 rounded-full bg-white/10 overflow-hidden" role="progressbar" aria-valuenow="{{ $lecture->progression }}" aria-valuemin="0" aria-valuemax="100" aria-label="Progression">
                <div class="h-full rounded-full bg-corail" style="width: {{ $lecture->progression }}%"></div>
            </div>
        @endif

        <div class="mt-auto pt-4 space-y-2.5">
            <span class="block w-full text-center rounded-[6px] py-2.5 text-[13.5px] font-semibold
                         {{ $termine ? 'bg-white/15 text-brume' : ($livre->pdf_path ? 'bg-menthe text-[#0b1020]' : 'bg-white/10 text-voile') }}">
                {{ $termine ? 'Terminé' : ($livre->pdf_path ? 'E-BOOK' : 'Bientôt disponible') }}
            </span>
            @if($livre->pdf_path)
                <a href="{{ route('livres.lire', $livre) }}" class="btn btn-contour w-full !rounded-[6px] !py-2.5">{{ $action }}</a>
            @else
                <a href="{{ route('livres.show', $livre) }}" class="btn btn-discret w-full !rounded-[6px] !py-2.5">Voir la fiche</a>
            @endif
        </div>
    </div>
</article>
