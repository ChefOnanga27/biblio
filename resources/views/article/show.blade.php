@extends('base')

@section('title', $article->titre . ' - Blog MBULU')

@section('content')

<div class="max-w-5xl mx-auto px-5 py-9 grid grid-cols-1 lg:grid-cols-[1fr_300px]
            gap-7 items-start">

    {{-- ── ARTICLE ── --}}
    <article class="min-w-0">

        {{-- Image hero --}}
        @if($article->image)
            <img src="{{ asset('storage/' . $article->image) }}"
                 alt="{{ $article->titre }}"
                 class="w-full h-80 object-cover rounded-[18px] rounded-b-none">
        @endif

        <div class="bg-white border border-black/[0.07] rounded-[18px]
                    {{ $article->image ? 'rounded-t-none border-t-0' : '' }}
                    px-11 py-10">

            {{-- Eyebrow --}}
            <div class="flex items-center gap-3 mb-5
                        {{ $article->image ? 'pt-7 border-t-[3px] border-[#063537]' : '' }}">
                <span class="text-[10px] font-medium tracking-[0.1em] uppercase text-orange-400">
                    Article
                </span>
                <span class="flex-1 h-px bg-black/[0.08]"></span>
            </div>

            {{-- Titre --}}
            <h1 class="font-sans text-[2rem] font-semibold text-gray-900
                       leading-snug mb-5">
                {{ $article->titre }}
            </h1>

            {{-- Méta --}}
            <div class="flex flex-wrap gap-2.5 pb-6
                        border-b border-black/[0.08] mb-8">
                <span class="inline-flex items-center gap-1.5 bg-[#f4f1ec]
                             rounded-full px-4 py-1.5 text-[12px] text-gray-500">
                    <span class="w-[3px] h-[3px] rounded-full bg-orange-400 flex-shrink-0"></span>
                    <span class="font-medium text-gray-800">{{ $article->user->name }}</span>
                </span>
                <span class="inline-flex items-center gap-1.5 bg-[#f4f1ec]
                             rounded-full px-4 py-1.5 text-[12px] text-gray-500">
                    <span class="w-[3px] h-[3px] rounded-full bg-orange-400 flex-shrink-0"></span>
                    {{ $article->published_at?->format('d F Y') }}
                </span>
            </div>

            {{-- Corps --}}
            <div class="text-[15.5px] text-gray-500 font-light leading-[1.85]
                        [&>p]:mb-5">
                {!! nl2br(e($article->contenu)) !!}
            </div>

            {{-- Footer --}}
            <div class="mt-10 pt-7 border-t border-black/[0.08]">
                <a href="{{ route('articles.index') }}"
                   class="group inline-flex items-center gap-2 text-[13px] text-gray-500
                          border border-black/[0.15] rounded-full px-5 py-2.5
                          hover:bg-[#f0ede8] transition-colors no-underline">
                    <span class="transition-transform group-hover:-translate-x-1">←</span>
                    Retour au blog
                </a>
            </div>

        </div>
    </article>

    {{-- ── SIDEBAR ── --}}
    <aside class="sticky top-6">
        <div class="bg-white rounded-2xl border border-black/[0.07] overflow-hidden">

            <div class="bg-[#063537] px-5 py-4 flex items-center gap-2.5">
                <svg class="w-5 h-5 opacity-60" fill="none" stroke="white"
                     stroke-width="1.5" viewBox="0 0 24 24">
                    <path d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3
                             .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6
                             2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0
                             2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967
                             8.967 0 00-6 2.292m0-14.25v14.25"/>
                </svg>
                <h3 class="font-serif text-[15px] font-semibold text-white">
                    Autres articles
                </h3>
            </div>

            <div class="p-2">
                @forelse($otherArticles as $other)
                    <a href="{{ route('articles.show', $other) }}"
                       class="group flex gap-3 items-start p-3 rounded-xl
                              hover:bg-[#f9f7f4] transition-colors no-underline
                              {{ !$loop->first ? 'border-t border-black/[0.05]' : '' }}">

                        @if($other->image)
                            <img src="{{ asset('storage/' . $other->image) }}"
                                 alt="{{ $other->titre }}"
                                 class="w-13 h-13 rounded-[10px] object-cover flex-shrink-0"
                                 style="width:52px;height:52px">
                        @else
                            <div class="flex-shrink-0 w-[52px] h-[52px] rounded-[10px]
                                        bg-[#063537] flex items-center justify-center">
                                <svg class="w-[18px] h-[18px] opacity-25" fill="none"
                                     stroke="white" stroke-width="1.5" viewBox="0 0 24 24">
                                    <path d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125
                                             1.125 0 0113.5 7.125v-1.5a3.375 3.375 0
                                             00-3.375-3.375H8.25m0 12.75h7.5m-7.5
                                             3H12M10.5 2.25H5.625c-.621
                                             0-1.125.504-1.125 1.125v17.25c0
                                             .621.504 1.125 1.125 1.125h12.75c.621
                                             0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/>
                                </svg>
                            </div>
                        @endif

                        <div class="flex-1 min-w-0">
                            <p class="font-serif text-[12.5px] font-semibold text-gray-800
                                      line-clamp-2 leading-snug mb-1
                                      group-hover:text-[#063537] transition-colors">
                                {{ $other->titre }}
                            </p>
                            <p class="text-[11px] text-gray-300">
                                {{ $other->published_at?->format('d M Y') }}
                            </p>
                        </div>
                    </a>
                @empty
                    <p class="text-center text-[13px] text-gray-300 py-6">
                        Pas d'autres articles pour le moment.
                    </p>
                @endforelse
            </div>
        </div>

        {{-- Watermark --}}
        <p class="text-center mt-7 text-[10px] font-medium tracking-[0.25em]
                  uppercase text-gray-300">
            M <span class="inline-block w-[3px] h-[3px] rounded-full bg-orange-400 mx-1 align-middle"></span>
            B <span class="inline-block w-[3px] h-[3px] rounded-full bg-orange-400 mx-1 align-middle"></span>
            U <span class="inline-block w-[3px] h-[3px] rounded-full bg-orange-400 mx-1 align-middle"></span>
            L <span class="inline-block w-[3px] h-[3px] rounded-full bg-orange-400 mx-1 align-middle"></span>
            U
        </p>
    </aside>

</div>

@endsection