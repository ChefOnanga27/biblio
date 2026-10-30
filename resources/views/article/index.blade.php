@extends('layouts.site')

@section('title', 'Blog — ' . $siteNom)

@section('content')
@php
    $date = fn ($d) => $d?->locale('fr')->translatedFormat('j M Y');
    $vedette = $articles->onFirstPage() ? $articles->first() : null;
    $autres = $vedette ? $articles->skip(1) : $articles;
@endphp

<header>
    <h1 class="text-[28px] sm:text-[32px] font-semibold text-brume">L'art du <span class="text-corail">livre</span> en Afrique centrale</h1>
    <p class="mt-1.5 text-[15px] text-voile max-w-2xl">Auto-édition, écriture, publication et mise en avant de votre œuvre : des ressources pour les auteurs gabonais.</p>
</header>

@if($vedette)
    <a href="{{ route('articles.show', $vedette) }}"
       class="group mt-8 grid md:grid-cols-2 rounded-[14px] overflow-hidden border border-ligne bg-surface/60 hover:border-corail/40 transition-colors">
        <div class="relative min-h-[220px] bg-surface-2 overflow-hidden">
            @if($vedette->image)
                <img src="{{ asset('storage/' . $vedette->image) }}" alt="" class="absolute inset-0 w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
            @endif
            <span class="absolute top-4 left-4 text-[11px] font-semibold uppercase tracking-wide bg-corail text-white px-3 py-1 rounded-full">À la une</span>
        </div>
        <div class="p-6 sm:p-9 flex flex-col justify-center">
            <h2 class="text-[22px] sm:text-[26px] font-semibold leading-snug group-hover:text-corail transition-colors">{{ $vedette->titre }}</h2>
            @if($vedette->description)
                <p class="mt-3 text-[15px] text-brume leading-relaxed line-clamp-4">{{ $vedette->description }}</p>
            @endif
            <p class="mt-5 text-[13px] text-voile">{{ $vedette->user?->name }} · {{ $date($vedette->published_at) }}</p>
        </div>
    </a>
@endif

@if($autres->isNotEmpty())
    <div class="mt-8 grid sm:grid-cols-2 xl:grid-cols-3 gap-5">
        @foreach($autres as $article)
            <a href="{{ route('articles.show', $article) }}" class="group rounded-[12px] overflow-hidden border border-ligne bg-surface/60 hover:border-corail/40 transition-colors flex flex-col">
                <div class="aspect-[16/9] bg-surface-2 overflow-hidden">
                    @if($article->image)
                        <img src="{{ asset('storage/' . $article->image) }}" alt="" loading="lazy" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                    @endif
                </div>
                <div class="p-5 flex-1 flex flex-col">
                    <h2 class="text-[17px] font-semibold leading-snug group-hover:text-corail transition-colors">{{ $article->titre }}</h2>
                    @if($article->description)
                        <p class="mt-2 text-[14px] text-brume leading-relaxed line-clamp-3">{{ $article->description }}</p>
                    @endif
                    <p class="mt-auto pt-4 text-[12.5px] text-voile">{{ $article->user?->name }} · {{ $date($article->published_at) }}</p>
                </div>
            </a>
        @endforeach
    </div>
@endif

@if($articles->isEmpty())
    <p class="mt-10 text-[15px] text-voile">Aucun article publié pour le moment.</p>
@endif

<div class="mt-10">{{ $articles->links('partials.pagination') }}</div>
@endsection
