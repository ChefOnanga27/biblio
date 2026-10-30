{{-- Carte « livre » des carrousels. Attend $livre (catégorie et genre de préférence chargés). --}}
@php $etiquette = $livre->genre?->nom ?? $livre->categorie?->nom; @endphp
<a href="{{ route('livres.show', $livre) }}"
   class="book-cover group block flex-none w-[128px] sm:w-[150px] lg:w-[160px] snap-start outline-none"
   title="{{ $livre->titre }} — {{ $livre->auteur }}">

    <div class="livre-3d anime">
        <div class="livre-3d__couverture aspect-[2/3] bg-surface-2">
            @if($livre->image)
                <img src="{{ asset('storage/' . $livre->image) }}" alt="{{ $livre->titre }}" loading="lazy"
                     class="w-full h-full object-cover">
            @else
                <div class="w-full h-full flex flex-col justify-end p-3 pl-[14%] bg-gradient-to-br from-[#2a3350] via-[#1f2740] to-[#151b2d]">
                    <span class="w-6 h-[3px] rounded-full bg-corail mb-2"></span>
                    <span class="text-encre text-[13px] font-semibold leading-snug line-clamp-4">{{ $livre->titre }}</span>
                </div>
            @endif

            @unless($livre->disponible)
                <span class="absolute top-2 left-[13%] text-[9.5px] font-semibold uppercase tracking-wide bg-black/60 text-white px-2 py-0.5 rounded-full">
                    Indisponible
                </span>
            @endunless

            <span class="livre-3d__dos" aria-hidden="true"></span>
        </div>
        <span class="livre-3d__pages" aria-hidden="true"></span>
    </div>

    <p class="mt-4 text-[15px] sm:text-[16px] font-semibold text-encre leading-snug line-clamp-2 group-hover:text-corail group-focus-visible:text-corail transition-colors">
        {{ $livre->titre }} <span class="font-normal text-brume">— {{ $livre->auteur }}</span>
    </p>
    @if($etiquette)
        <p class="mt-1 text-[13.5px] italic text-voile truncate">{{ $etiquette }}</p>
    @endif
</a>
