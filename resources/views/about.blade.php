@extends('layouts.site')

@section('title', 'À propos — ' . $siteNom)

@section('content')
@php
    $chiffres = [
        [\App\Models\Livre::count(), 'ouvrages disponibles'],
        [\App\Models\Categorie::count(), 'catégories'],
        [\App\Models\User::count(), 'lecteurs inscrits'],
    ];
    $services = [
        ['Catalogue riche', 'Des ouvrages gabonais et africains : romans, essais, poésie et jeunesse.', 'M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25'],
        ['Recherche et filtres', 'Filtrez par catégorie, auteur, genre ou langue pour trouver exactement ce que vous cherchez.', 'M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z'],
        ['Lecture intégrée', 'Lisez en ligne, sans téléchargement, et reprenez là où vous vous êtes arrêté.', 'M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z'],
        ['Espace personnel', 'Favoris, progression, historique et notes : votre bibliothèque vous suit partout.', 'M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z'],
    ];
    $faq = [
        ['Qu\'est-ce que ' . $siteNom . ' ?', $siteNom . ' est une plateforme dédiée à la découverte et à l\'apprentissage à travers des contenus interactifs.'],
        ['À qui s\'adresse ' . $siteNom . ' ?', $siteNom . ' s\'adresse à tous les passionnés de lecture et d\'apprentissage, quel que soit leur niveau.'],
        ['Comment puis-je contribuer ?', 'Vous pouvez proposer un livre depuis la page « Contribuer », partager ' . $siteNom . ' à vos proches ou nous écrire avec vos idées.'],
        ['Quelles œuvres gabonaises sont incluses ?', 'Plusieurs œuvres locales sont intégrées afin de valoriser la richesse culturelle du Gabon.'],
    ];
@endphp

{{-- ═════════ INTRODUCTION ═════════ --}}
<section class="relative overflow-hidden rounded-[14px] px-6 sm:px-10 py-12 sm:py-16 bg-gradient-to-br from-[#d4524a] via-[#b0446b] to-[#7d3a9a]">
    <p class="text-[12px] font-semibold uppercase tracking-[0.14em] text-white/70">À propos · Fondée à Libreville, Gabon</p>
    <h1 class="mt-4 text-[32px] sm:text-[44px] font-semibold leading-[1.1] max-w-3xl">La bibliothèque numérique du patrimoine gabonais</h1>
    <p class="mt-4 text-[16px] text-white/85 max-w-xl leading-relaxed">Un espace dédié à la littérature, à la culture et au savoir d'Afrique centrale — accessible à tous, préservé pour demain.</p>
</section>

<section class="mt-6 grid grid-cols-3 gap-3 sm:gap-5">
    @foreach($chiffres as [$nombre, $libelle])
        <div class="rounded-[12px] bg-surface/60 border border-ligne px-4 sm:px-6 py-6 text-center">
            <p class="text-[28px] sm:text-[38px] font-semibold leading-none">{{ number_format($nombre, 0, ',', ' ') }}</p>
            <p class="mt-2 text-[12px] sm:text-[13.5px] text-brume">{{ $libelle }}</p>
        </div>
    @endforeach
</section>

{{-- ═════════ MISSION ═════════ --}}
<section class="reveal mt-14 grid lg:grid-cols-2 gap-8 lg:gap-12 items-center">
    <div>
        <h2 class="text-[24px] sm:text-[28px] font-semibold text-brume">Notre <span class="text-corail">mission</span></h2>
        <p class="mt-4 text-[15.5px] text-brume leading-relaxed">
            {{ $siteNom }} a été conçue pour préserver et promouvoir la richesse linguistique et culturelle du Gabon.
            Que vous soyez un jeune Gabonais désireux de renouer avec vos racines ou un passionné de lecture
            en quête de nouvelles découvertes, vous êtes au bon endroit.
        </p>
        <p class="mt-3 text-[15.5px] text-brume leading-relaxed">
            Les lecteurs trouvent des ouvrages variés et découvrent de nouveaux titres ; les administrateurs gèrent
            les livres publiés et veillent à une expérience riche et sécurisée.
        </p>
    </div>
    <div class="rounded-[14px] overflow-hidden aspect-video bg-surface-2 border border-ligne">
        <video class="w-full h-full object-cover" autoplay muted loop playsinline>
            <source src="{{ asset('video.webm') }}" type="video/webm">
        </video>
    </div>
</section>

{{-- ═════════ SERVICES ═════════ --}}
<section class="reveal mt-14">
    <h2 class="text-[24px] sm:text-[28px] font-semibold text-brume">Ce que nous offrons</h2>
    <div class="mt-6 grid sm:grid-cols-2 gap-4">
        @foreach($services as [$titre, $texte, $icone])
            <div class="flex gap-4 rounded-[12px] bg-surface/60 border border-ligne p-5">
                <span class="flex-none w-11 h-11 rounded-[10px] bg-corail/15 text-corail flex items-center justify-center">
                    <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 24 24" aria-hidden="true"><path d="{{ $icone }}"/></svg>
                </span>
                <div>
                    <p class="text-[16px] font-semibold">{{ $titre }}</p>
                    <p class="mt-1 text-[14px] text-brume leading-relaxed">{{ $texte }}</p>
                </div>
            </div>
        @endforeach
    </div>
</section>

{{-- ═════════ FAQ ═════════ --}}
<section class="reveal mt-14 max-w-3xl">
    <h2 class="text-[24px] sm:text-[28px] font-semibold text-brume">Questions fréquentes</h2>
    <div class="mt-6 space-y-3">
        @foreach($faq as [$question, $reponse])
            <details class="group rounded-[12px] bg-surface/60 border border-ligne px-5 py-4 open:border-corail/30">
                <summary class="flex justify-between items-center gap-4 cursor-pointer list-none text-[16px] font-medium">
                    {{ $question }}
                    <span class="flex-none w-7 h-7 rounded-full bg-corail/15 text-corail flex items-center justify-center text-lg leading-none transition-transform group-open:rotate-45" aria-hidden="true">+</span>
                </summary>
                <p class="mt-3 text-[14.5px] text-brume leading-relaxed">{{ $reponse }}</p>
            </details>
        @endforeach
    </div>
</section>

@guest
    <section class="reveal mt-14 rounded-[14px] border border-ligne bg-surface/60 px-6 sm:px-9 py-8 flex flex-col md:flex-row md:items-center gap-6">
        <div class="flex-1">
            <h2 class="text-[22px] font-semibold">Rejoignez la communauté {{ $siteNom }}</h2>
            <p class="mt-1.5 text-[14.5px] text-brume">Créez votre compte gratuitement et accédez à tout le catalogue dès aujourd'hui.</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('register') }}" class="btn btn-blanc">S'inscrire gratuitement</a>
            <a href="{{ route('login') }}" class="btn btn-discret">Se connecter</a>
        </div>
    </section>
@endguest
@endsection
