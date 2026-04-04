@extends('base')

@section('title', 'Blog - MBULU')

@section('content')

{{-- HERO --}}
<div class="relative bg-[#063537] px-8 py-14 overflow-hidden">
    {{-- Cercles décoratifs --}}
    <div class="absolute -top-16 -right-20 w-80 h-80 rounded-full
                border border-orange-400/15 pointer-events-none"></div>
    <div class="absolute -bottom-10 right-16 w-44 h-44 rounded-full
                border border-white/[0.06] pointer-events-none"></div>

    <div class="max-w-4xl mx-auto relative">
        <p class="flex items-center gap-3 text-[11px] font-medium tracking-[0.1em]
                  uppercase text-white/50 mb-5">
            <span class="inline-block w-6 h-px bg-orange-400"></span>
            Blog MBULU
        </p>
        <h1 class="font-serif text-4xl font-semibold text-white leading-tight mb-4">
            L'art du <em class="italic text-orange-400">livre</em><br>en Afrique centrale
        </h1>
        <p class="text-[15px] text-white/55 font-light max-w-lg leading-relaxed">
            Auto-édition, écriture, publication et mise en avant de votre œuvre —
            ressources pour les auteurs gabonais.
        </p>
    </div>
</div>

{{-- CONTENU --}}
<section class="max-w-4xl mx-auto px-5 py-10">

    @php $featured = $articles->first(); $rest = $articles->skip(1); @endphp

    {{-- Article vedette --}}
    @if($featured)
    <a href="{{ route('articles.show', $featured) }}"
       class="group grid grid-cols-2 bg-white rounded-[18px] border border-black/[0.07]
              overflow-hidden mb-8 no-underline
              transition-all duration-200 hover:-translate-y-1
              hover:shadow-[0_14px_40px_rgba(6,53,55,0.10)]">

        <div class="relative overflow-hidden bg-[#063537] min-h-[260px]">
            @if($featured->image)
                <img src="{{ asset('storage/' . $featured->image) }}"
                     alt="{{ $featured->titre }}"
                     class="w-full h-full object-cover opacity-85">
            @endif
            <div class="absolute inset-0 bg-gradient-to-br from-[#063537]/40 to-transparent"></div>
            <span class="absolute top-4 left-4 text-[10px] font-medium tracking-wide
                         uppercase bg-orange-400/90 text-white px-3 py-1 rounded-full">
                À la une
            </span>
        </div>

        <div class="flex flex-col justify-center px-9 py-8">
            <p class="text-[11px] font-medium tracking-wide uppercase text-orange-400 mb-3">
                Article
            </p>
            <h2 class="font-serif text-2xl font-semibold text-gray-900 leading-snug mb-3">
                {{ $featured->titre }}
            </h2>
            <p class="flex items-center gap-1.5 text-[12px] text-gray-300 mb-3.5">
                <span class="w-[3px] h-[3px] rounded-full bg-orange-400"></span>
                {{ $featured->user->name }} &nbsp;·&nbsp;
                {{ $featured->published_at?->format('d M Y') }}
            </p>
            <p class="text-[13.5px] text-gray-500 font-light leading-relaxed line-clamp-3 mb-6">
                {{ $featured->description }}
            </p>
            <span class="inline-flex items-center gap-2 self-start bg-[#063537] text-white
                         text-[13px] font-medium px-5 py-2.5 rounded-full
                         transition-colors group-hover:bg-[#0a4f52]">
                Lire la suite
                <span class="transition-transform group-hover:translate-x-1">→</span>
            </span>
        </div>
    </a>
    @endif

    {{-- Grille --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-[18px]">
        @forelse($rest as $article)
            <article class="group bg-white rounded-2xl border border-black/[0.07] overflow-hidden
                            flex flex-col transition-all duration-200
                            hover:-translate-y-[3px]
                            hover:shadow-[0_10px_28px_rgba(6,53,55,0.09)]">

                @if($article->image)
                    <div class="relative h-40 overflow-hidden bg-[#063537]">
                        <img src="{{ asset('storage/' . $article->image) }}"
                             alt="{{ $article->titre }}"
                             class="w-full h-full object-cover opacity-88
                                    transition-opacity group-hover:opacity-100">
                        <div class="absolute bottom-0 inset-x-0 h-12
                                    bg-gradient-to-t from-[#063537]/50 to-transparent"></div>
                    </div>
                @else
                    <div class="h-40 bg-[#063537] flex items-center justify-center">
                        <svg class="w-10 h-10 opacity-20" fill="none" stroke="white"
                             stroke-width="1.5" viewBox="0 0 24 24">
                            <path d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3
                                     .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6
                                     2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0
                                     2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967
                                     8.967 0 00-6 2.292m0-14.25v14.25"/>
                        </svg>
                    </div>
                @endif

                <div class="h-[3px] bg-[#063537]"></div>

                <div class="flex flex-col flex-1 p-5">
                    <h3 class="font-serif text-[15px] font-semibold text-gray-900
                               leading-snug line-clamp-2 mb-2">
                        {{ $article->titre }}
                    </h3>
                    <p class="flex items-center gap-1.5 text-[11px] text-gray-300 mb-2.5">
                        <span class="w-[3px] h-[3px] rounded-full bg-orange-400 flex-shrink-0"></span>
                        {{ $article->user->name }} &nbsp;·&nbsp;
                        {{ $article->published_at?->format('d M Y') }}
                    </p>
                    <p class="text-[12.5px] text-gray-500 font-light leading-relaxed
                               line-clamp-3 flex-1 mb-4">
                        {{ \Illuminate\Support\Str::limit($article->description, 110) }}
                    </p>
                    <a href="{{ route('articles.show', $article) }}"
                       class="self-start inline-flex items-center gap-1.5 text-[12px]
                              font-medium text-[#063537] px-4 py-1.5 rounded-full
                              border border-[#063537]/30
                              hover:bg-[#063537]/5 transition-colors">
                        Lire la suite →
                    </a>
                </div>
            </article>
        @empty
            <div class="col-span-full text-center py-14 text-gray-400">
                <p class="text-3xl mb-3 opacity-30">📖</p>
                <p class="text-sm">Aucun article publié pour le moment.</p>
            </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    <div class="mt-10">
        {{ $articles->links() }}
    </div>

</section>

@endsection