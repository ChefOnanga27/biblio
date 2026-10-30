{{--
    Carrousel horizontal de livres.
    Paramètres : $titre, $livres, $lien (optionnel, « Tout voir »), $sousTitre (optionnel),
    $conteneur (optionnel, classes du bloc)
--}}
@if($livres->isNotEmpty())
<section class="reveal {{ $conteneur ?? 'mt-12' }}" data-carousel>
    <div class="flex items-end justify-between gap-4 mb-6">
        <div class="min-w-0">
            <h2 class="text-[22px] sm:text-[26px] font-medium text-brume leading-tight">{{ $titre }}</h2>
            @isset($sousTitre)
                <p class="text-[13.5px] text-voile mt-1">{{ $sousTitre }}</p>
            @endisset
        </div>

        <div class="flex items-center gap-2 flex-none">
            @isset($lien)
                <a href="{{ $lien }}" class="text-[15px] sm:text-[17px] text-brume hover:text-corail transition-colors mr-2">Tout voir</a>
            @endisset
            <button type="button" data-prev aria-label="Précédent" class="carousel-btn hidden sm:inline-flex">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M15 19l-7-7 7-7"/></svg>
            </button>
            <button type="button" data-next aria-label="Suivant" class="carousel-btn hidden sm:inline-flex">
                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7"/></svg>
            </button>
        </div>
    </div>

    <div data-track class="no-scrollbar flex gap-6 sm:gap-10 lg:gap-12 overflow-x-auto snap-x snap-mandatory scroll-smooth pb-4 -mx-4 px-4 sm:mx-0 sm:px-0">
        @foreach($livres as $livre)
            @include('livre.cover')
        @endforeach
    </div>
</section>
@endif
