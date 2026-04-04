@extends('base')

@section('title', 'Bibliothèque - Biblio')

@section('content')

{{-- ── HERO ── --}}
<section class="relative overflow-hidden px-8 py-16">

    {{-- Image de fond --}}
    <img src="/amadou.webp" alt=""
         class="absolute inset-0 w-full h-full object-cover">
    <div class="absolute inset-0"></div>

    {{-- Cercles décoratifs --}}
    <div class="absolute -top-20 -right-14 w-96 h-96 rounded-full
                border border-orange-400/[0.13] pointer-events-none"></div>
    <div class="absolute -bottom-12 left-16 w-52 h-52 rounded-full
                border border-white/[0.05] pointer-events-none"></div>
    <div class="absolute top-8 right-36 w-[70px] h-[70px] rounded-full
                bg-orange-400/[0.08] pointer-events-none"></div>

    <div class="relative z-10 max-w-3xl mx-auto text-center">

        {{-- Eyebrow --}}
        <div class="inline-flex items-center gap-2.5 mb-5
                    bg-white/[0.07] border border-white/[0.15] rounded-full px-5 py-1.5">
            <span class="w-[5px] h-[5px] rounded-full bg-orange-400"></span>
            <span class="text-[11px] font-medium tracking-[0.1em] uppercase text-white/55">
                Bibliothèque Biblio
            </span>
        </div>

        <h1 class="font-serif text-5xl font-semibold text-white leading-tight mb-4">
            Trouvez votre prochain<br>
            <em class="italic text-orange-400">livre</em>
        </h1>
        <p class="text-[14.5px] text-white font-light mb-9">
            Recherchez parmi des centaines de livres, auteurs et catégories
        </p>

        {{-- Formulaire --}}
        <form method="GET" action="{{ route('livre.index') }}" class="space-y-3">

            {{-- Barre de recherche --}}
            <div class="flex bg-white rounded-2xl overflow-hidden border border-black/[0.10]
                        shadow-[0_8px_32px_rgba(0,0,0,0.15)]">
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Rechercher un titre, un auteur..."
                       class="flex-1 px-5 py-4 text-[15px] font-light text-gray-800
                              placeholder:text-gray-300 bg-transparent
                              focus:outline-none">
                <button type="submit"
                        class="flex items-center gap-2 bg-orange-500 hover:bg-orange-600
                               text-white px-7 py-4 text-[14px] font-medium
                               transition-colors whitespace-nowrap">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                         stroke-width="2" viewBox="0 0 24 24">
                        <path d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 15.803 7.5 7.5 0 0015.803 15.803z"/>
                    </svg>
                    Rechercher
                </button>
            </div>

            {{-- Filtres --}}
            <div class="grid grid-cols-2 md:grid-cols-4 gap-2.5">

                <select name="categorie"
                        class="w-full bg-white/10 border border-white/20 rounded-xl
                               px-4 py-3 text-[13px] font-light text-white
                               focus:outline-none focus:bg-white/[0.18] focus:border-orange-400/50
                               transition-colors appearance-none">
                    <option value="">Catégories</option>
                    @foreach($categories as $categorie)
                        <option value="{{ $categorie->id }}"
                                {{ request('categorie') == $categorie->id ? 'selected' : '' }}
                                style="background:#063537">
                            {{ $categorie->nom }}
                        </option>
                    @endforeach
                </select>

                <select name="genre"
                        class="w-full bg-white/10 border border-white/20 rounded-xl
                               px-4 py-3 text-[13px] font-light text-white
                               focus:outline-none focus:bg-white/[0.18] focus:border-orange-400/50
                               transition-colors appearance-none">
                    <option value="">Genres</option>
                    @foreach($genres as $genre)
                        <option value="{{ $genre->id }}"
                                {{ request('genre') == $genre->id ? 'selected' : '' }}
                                style="background:#063537">
                            {{ $genre->nom }}
                        </option>
                    @endforeach
                </select>

                <select name="auteur"
                        class="w-full bg-white/10 border border-white/20 rounded-xl
                               px-4 py-3 text-[13px] font-light text-white
                               focus:outline-none focus:bg-white/[0.18] focus:border-orange-400/50
                               transition-colors appearance-none">
                    <option value="">Auteurs</option>
                    @foreach($auteurs as $auteur)
                        <option value="{{ $auteur->id }}"
                                {{ request('auteur') == $auteur->id ? 'selected' : '' }}
                                style="background:#063537">
                            {{ $auteur->nom }}
                        </option>
                    @endforeach
                </select>

                <input type="number" name="annee" value="{{ request('annee') }}"
                       placeholder="Année (ex: 2001)"
                       class="w-full bg-white/10 border border-white/20 rounded-xl
                              px-4 py-3 text-[13px] font-light text-white
                              placeholder:text-white/35
                              focus:outline-none focus:bg-white/[0.18] focus:border-orange-400/50
                              transition-colors [appearance:textfield]">
            </div>

        </form>
    </div>
</section>

{{-- ── CATALOGUE ── --}}
<section class="max-w-5xl mx-auto px-5 py-9">

    <div class="flex items-center justify-between mb-6">
        <h2 class="font-serif text-[1.4rem] font-semibold text-gray-900
                   flex items-center gap-3
                   after:content-[''] after:inline-block after:w-10
                   after:h-px after:bg-black/15">
            Catalogue
        </h2>
        <span class="text-[12px] text-gray-400">
            {{ $livres->total() }} ouvrage{{ $livres->total() > 1 ? 's' : '' }}
        </span>
    </div>

    {{-- Grille livres --}}
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
        @forelse($livres as $livre)
            @include('livre.card')
        @empty
            <div class="col-span-full text-center py-16">
                <div class="text-5xl mb-4 opacity-20">📚</div>
                <p class="text-[14px] text-gray-400">Aucun livre ne correspond à votre recherche.</p>
                <a href="{{ route('livre.index') }}"
                   class="inline-flex items-center gap-2 mt-4 text-[13px] text-[#063537]
                          border border-[#063537]/30 rounded-full px-5 py-2
                          hover:bg-[#063537]/5 transition-colors">
                    Réinitialiser les filtres
                </a>
            </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    <div class="mt-8">
        {{ $livres->links() }}
    </div>

    {{-- Watermark --}}
    <p class="text-center mt-10 text-[10px] font-medium tracking-[0.25em]
              uppercase text-gray-300">
        B <span class="inline-block w-[3px] h-[3px] rounded-full bg-orange-400 mx-1 align-middle"></span>
        I <span class="inline-block w-[3px] h-[3px] rounded-full bg-orange-400 mx-1 align-middle"></span>
        B <span class="inline-block w-[3px] h-[3px] rounded-full bg-orange-400 mx-1 align-middle"></span>
        L <span class="inline-block w-[3px] h-[3px] rounded-full bg-orange-400 mx-1 align-middle"></span>
        I <span class="inline-block w-[3px] h-[3px] rounded-full bg-orange-400 mx-1 align-middle"></span>
        O <span class="inline-block w-[3px] h-[3px] rounded-full bg-orange-400 mx-1 align-middle"></span>
    </p>

</section>

@endsection