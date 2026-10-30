{{--
    Ligne de résultat de la page Rechercher.
    Paramètres : $livre (categorie, genre, lectures_count, notes_avg_note, notes_count), $estFavori
--}}
@php
    $annee = $livre->date_publication ? \Carbon\Carbon::parse($livre->date_publication)->year : null;
    $moyenne = $livre->notes_count ? number_format((float) $livre->notes_avg_note, 1, ',', '') : null;
    $ok = '<svg width="16" height="16" viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="10" fill="#3fbf6a"/><path d="M7.5 12.5l3 3 6-6.5" fill="none" stroke="#0b1020" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"/></svg>';
    $non = '<svg width="16" height="16" viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="12" r="10" fill="#4b5368"/><path d="M9 9l6 6M15 9l-6 6" stroke="#0b1020" stroke-width="2.4" stroke-linecap="round"/></svg>';
@endphp

<li class="group grid grid-cols-[72px_minmax(0,1fr)] lg:grid-cols-[minmax(0,2.6fr)_90px_minmax(0,1.3fr)_minmax(0,1.2fr)_minmax(0,1fr)_44px_104px]
           gap-x-4 lg:gap-x-6 gap-y-3 items-center py-5 border-b border-ligne">

    {{-- Titre --}}
    <div class="contents lg:flex lg:items-center lg:gap-6 lg:min-w-0">
        <a href="{{ route('livres.show', $livre) }}" class="livre-3d anime block w-[72px] flex-none row-span-4 lg:row-span-1 self-start lg:self-center" tabindex="-1" aria-hidden="true">
            <span class="livre-3d__couverture block aspect-[2/3] bg-surface-2">
                @if($livre->image)
                    <img src="{{ asset('storage/' . $livre->image) }}" alt="" loading="lazy" class="w-full h-full object-cover">
                @endif
                <span class="livre-3d__dos"></span>
            </span>
            <span class="livre-3d__pages"></span>
        </a>
        <div class="min-w-0">
            <h3 class="text-[17px] sm:text-[19px] leading-snug text-encre line-clamp-2">
                <a href="{{ route('livres.show', $livre) }}" class="hover:text-corail transition-colors">{{ $livre->titre }}</a>
            </h3>
            <p class="mt-1 text-[14px] text-brume truncate">{{ $livre->auteur }}{{ $annee ? ', ' . $annee : '' }}</p>
            @if($livre->langue || $livre->nombre_pages)
                <p class="mt-0.5 text-[11.5px] text-voile">{{ collect([$livre->langue, $livre->nombre_pages ? $livre->nombre_pages . ' pages' : null])->filter()->join(' · ') }}</p>
            @endif
        </div>
    </div>

    {{-- Note --}}
    <div class="col-start-2 lg:col-start-auto text-[13px] lg:text-[17px] text-encre">
        @if($moyenne)
            <span title="{{ $livre->notes_count }} note{{ $livre->notes_count > 1 ? 's' : '' }}">
                <span class="lg:hidden text-corail">★</span> {{ $moyenne }}<span class="text-voile text-[12px] lg:text-[13px]">/5</span>
            </span>
        @else
            <span class="text-voile text-[13px]">Pas encore noté</span>
        @endif
    </div>

    {{-- Catégorie --}}
    <div class="hidden lg:block min-w-0">
        <p class="text-[16px] text-encre truncate">{{ $livre->categorie?->nom ?? '—' }}</p>
        @if($livre->genre)
            <p class="mt-1 text-[13.5px] text-brume truncate">{{ $livre->genre->nom }}</p>
        @endif
    </div>

    {{-- Disponibilité --}}
    <div class="hidden lg:flex flex-col gap-1.5 text-[13.5px] text-brume">
        <span class="flex items-center gap-2">{!! $livre->pdf_path ? $ok : $non !!} E-book PDF</span>
        <span class="flex items-center gap-2">{!! $livre->pdf_path ? $ok : $non !!} Lecture en ligne</span>
    </div>

    {{-- Statut --}}
    <div class="col-start-2 lg:col-start-auto flex flex-wrap items-center gap-x-3 gap-y-1.5 lg:block">
        <span class="inline-block rounded px-2.5 py-1 text-[12.5px] font-medium {{ $livre->disponible ? 'bg-menthe text-[#0b1020]' : 'bg-white/10 text-brume' }}">
            {{ $livre->disponible ? 'Disponible' : 'Indisponible' }}
        </span>
        <p class="lg:mt-2 flex items-center gap-1.5 text-[12.5px] text-brume">
            <svg width="13" height="13" fill="none" stroke="#f07155" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            {{ $livre->lectures_count }} lecteur{{ $livre->lectures_count > 1 ? 's' : '' }}
        </p>
    </div>

    {{-- Favori + Aperçu --}}
    <div class="col-start-2 lg:col-start-auto lg:col-span-2 flex items-center gap-3 lg:gap-6 lg:justify-end">
        @auth
            <form method="POST" action="{{ route('livres.favori', $livre) }}" data-favori>
                @csrf
                <button type="submit" aria-pressed="{{ $estFavori ? 'true' : 'false' }}"
                        aria-label="{{ $estFavori ? 'Retirer des favoris' : 'Ajouter aux favoris' }} : {{ $livre->titre }}"
                        title="{{ $estFavori ? 'Retirer des favoris' : 'Ajouter aux favoris' }}"
                        class="w-10 h-10 rounded-full flex items-center justify-center cursor-pointer transition-transform hover:scale-110
                               text-brume aria-pressed:text-[#ff4d4d]">
                    <svg width="24" height="24" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" fill="{{ $estFavori ? 'currentColor' : 'none' }}" data-coeur>
                        <path d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z"/>
                    </svg>
                </button>
            </form>
        @else
            <a href="{{ route('login') }}" aria-label="Connectez-vous pour ajouter aux favoris" title="Ajouter aux favoris"
               class="w-10 h-10 rounded-full flex items-center justify-center text-brume hover:text-[#ff4d4d] transition-colors">
                <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z"/></svg>
            </a>
        @endauth
        <a href="{{ route('livres.show', $livre) }}" class="btn btn-contour btn-sm !px-5">Aperçu</a>
    </div>
</li>
