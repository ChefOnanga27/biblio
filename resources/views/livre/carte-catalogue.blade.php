{{--
    Carte de la grille du catalogue.
    Paramètres : $livre (avec lectures_count et categorie chargés), $estFavori (bool)
--}}
@php
    $annee = $livre->date_publication ? \Carbon\Carbon::parse($livre->date_publication)->year : null;
    $meta = collect([$annee, $livre->langue, $livre->nombre_pages ? $livre->nombre_pages . ' p.' : null])->filter();
@endphp

<article class="book-cover group relative">

    {{-- Couverture --}}
    <div class="livre-3d anime">
    <div class="livre-3d__couverture aspect-[2/3] bg-[#063537]">

        <a href="{{ route('livres.show', $livre) }}" class="absolute inset-0" tabindex="-1" aria-hidden="true">
            @if($livre->image)
                <img src="{{ asset('storage/' . $livre->image) }}" alt="" loading="lazy"
                     class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
            @else
                <div class="w-full h-full flex flex-col justify-end p-3 pl-[14%] bg-gradient-to-br from-[#063537] via-[#0a4f52] to-[#063537]">
                    <span class="w-6 h-[3px] rounded-full bg-[#f97316] mb-2"></span>
                    <span class="text-white text-[13px] font-semibold leading-snug line-clamp-4">{{ $livre->titre }}</span>
                </div>
            @endif
        </a>

        {{-- Informations complémentaires au survol (écrans avec souris) --}}
        <div class="pointer-events-none absolute inset-0 hidden md:flex flex-col justify-end p-3.5 pl-[14%]
                    bg-gradient-to-t from-[#063537] via-[#063537]/80 to-[#063537]/10
                    opacity-0 transition-opacity duration-300 group-hover:opacity-100 group-focus-within:opacity-100">
            @if($meta->isNotEmpty())
                <p class="text-[10.5px] font-medium text-white/60 mb-1.5">{{ $meta->join(' · ') }}</p>
            @endif
            @if($livre->description)
                <p class="text-[11.5px] text-white/85 leading-relaxed line-clamp-4 mb-3">{{ $livre->description }}</p>
            @endif
            <a href="{{ route('livres.show', $livre) }}"
               class="pointer-events-auto inline-flex items-center justify-center gap-1.5 bg-[#f97316] hover:bg-[#ea580c]
                      text-white text-[12px] font-semibold rounded-full px-3 py-2 transition-colors">
                Voir les détails
            </a>
        </div>

        {{-- Popularité --}}
        @if($livre->lectures_count > 0)
            <span class="absolute top-2 left-[13%] inline-flex items-center gap-1 text-[10.5px] font-semibold
                         bg-white/90 text-[#063537] rounded-full px-2 py-0.5 backdrop-blur-sm"
                  title="{{ $livre->lectures_count }} lecteur{{ $livre->lectures_count > 1 ? 's' : '' }}">
                <svg width="11" height="11" fill="none" stroke="#f97316" stroke-width="2.2" viewBox="0 0 24 24">
                    <path d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                {{ $livre->lectures_count }}
            </span>
        @elseif(! $livre->disponible)
            <span class="absolute top-2 left-[13%] text-[9.5px] font-semibold uppercase tracking-wide
                         bg-white/90 text-gray-600 px-2 py-0.5 rounded-full">Indisponible</span>
        @endif

        {{-- Favori --}}
        @php
            $coeurClass = 'absolute top-2 right-2 w-8 h-8 rounded-full flex items-center justify-center
                           backdrop-blur-sm transition-all duration-200 cursor-pointer';
            $coeurVisible = $estFavori ? '' : 'md:opacity-0 md:group-hover:opacity-100 md:focus-visible:opacity-100';
        @endphp
        @auth
            <form method="POST" action="{{ route('livres.favori', $livre) }}" data-favori>
                @csrf
                <button type="submit" aria-pressed="{{ $estFavori ? 'true' : 'false' }}"
                        aria-label="{{ $estFavori ? 'Retirer des favoris' : 'Ajouter aux favoris' }} : {{ $livre->titre }}"
                        title="{{ $estFavori ? 'Retirer des favoris' : 'Ajouter aux favoris' }}"
                        class="{{ $coeurClass }} {{ $coeurVisible }}
                               bg-white/90 text-[#063537] hover:bg-white hover:scale-110
                               aria-pressed:bg-[#f97316] aria-pressed:text-white aria-pressed:opacity-100">
                    <svg width="15" height="15" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"
                         fill="{{ $estFavori ? 'currentColor' : 'none' }}" data-coeur>
                        <path d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z"/>
                    </svg>
                </button>
            </form>
        @else
            <a href="{{ route('login') }}" aria-label="Connectez-vous pour ajouter aux favoris" title="Ajouter aux favoris"
               class="{{ $coeurClass }} {{ $coeurVisible }} bg-white/90 text-[#063537] hover:bg-white hover:scale-110">
                <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z"/>
                </svg>
            </a>
        @endauth

        <span class="livre-3d__dos" aria-hidden="true"></span>
    </div>
    <span class="livre-3d__pages" aria-hidden="true"></span>
    </div>

    {{-- Texte --}}
    <h3 class="mt-2.5 text-[13px] font-semibold text-gray-900 leading-snug line-clamp-2">
        <a href="{{ route('livres.show', $livre) }}" class="hover:text-[#063537] focus-visible:text-[#063537] outline-none">
            {{ $livre->titre }}
        </a>
    </h3>
    <p class="mt-0.5 text-[11.5px] text-gray-400 truncate">{{ $livre->auteur }}</p>
    @if($livre->categorie)
        <span class="inline-block mt-2 max-w-full truncate text-[10.5px] font-medium text-[#063537]
                     bg-[#f0f4f4] rounded-full px-2.5 py-0.5">{{ $livre->categorie->nom }}</span>
    @endif
</article>
