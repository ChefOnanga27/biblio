@extends('layouts.site')

@section('title', $article->titre . ' — ' . $siteNom)

@section('content')
@php $date = fn ($d) => $d?->locale('fr')->translatedFormat('j F Y'); @endphp

<nav aria-label="Fil d'Ariane" class="flex items-center gap-2 text-[13px] text-voile min-w-0">
    <a href="{{ route('articles.index') }}" class="hover:text-corail transition-colors">Blog</a>
    <span aria-hidden="true">›</span>
    <span class="text-brume truncate">{{ $article->titre }}</span>
</nav>

<div class="mt-6 grid xl:grid-cols-[minmax(0,1fr)_320px] gap-8 items-start">
    <article class="min-w-0 rounded-[14px] overflow-hidden border border-ligne bg-surface/50">
        @if($article->image)
            <img src="{{ media_url($article->image) }}" alt="" class="w-full max-h-[420px] object-cover">
        @endif
        <div class="p-6 sm:p-10">
            <h1 class="text-[28px] sm:text-[38px] font-semibold leading-[1.15]">{{ $article->titre }}</h1>
            <p class="mt-3 text-[14px] text-voile">
                Par <span class="text-brume">{{ $article->user?->name }}</span> · {{ $date($article->published_at) }}
            </p>
            @if($article->description)
                <p class="mt-6 text-[17px] text-brume leading-relaxed border-l-[3px] border-corail pl-5">{{ $article->description }}</p>
            @endif
            <div class="mt-8 text-[16px] text-encre/90 leading-[1.85] max-w-[70ch]">
                {!! nl2br(e($article->contenu)) !!}
            </div>
        </div>
    </article>

    <aside class="rounded-[14px] border border-ligne bg-surface/50 p-6">
        <h2 class="text-[17px] font-semibold">Autres articles</h2>
        @forelse($otherArticles as $autre)
            <a href="{{ route('articles.show', $autre) }}" class="group flex gap-3 py-4 border-b border-ligne last:border-0">
                <span class="flex-none w-16 h-16 rounded-lg overflow-hidden bg-surface-2">
                    @if($autre->image)
                        <img src="{{ media_url($autre->image) }}" alt="" loading="lazy" class="w-full h-full object-cover">
                    @endif
                </span>
                <span class="min-w-0">
                    <span class="block text-[14px] font-medium leading-snug line-clamp-2 group-hover:text-corail transition-colors">{{ $autre->titre }}</span>
                    <span class="block mt-1 text-[12px] text-voile">{{ $autre->published_at?->locale('fr')->translatedFormat('j M Y') }}</span>
                </span>
            </a>
        @empty
            <p class="mt-3 text-[14px] text-voile">Pas d'autre article pour le moment.</p>
        @endforelse
        <a href="{{ route('articles.index') }}" class="btn btn-discret w-full mt-5">← Tous les articles</a>
    </aside>
</div>
@endsection
