@extends('base')

@section('title', 'À propos - MBULU')

@section('content')

{{-- ── HERO ── --}}
<div class="relative overflow-hidden px-8 py-[72px]"
     style="
        background-image: url('https://images.unsplash.com/photo-1524995997946-a1c2e315a42f?auto=format&fit=crop&w=1600&q=80');
        background-size: cover;
        background-position: center;
     ">
    {{-- Overlay sombre --}}
    <div class="absolute inset-0 bg-[#063537]/85 pointer-events-none"></div>

    {{-- Cercles décoratifs --}}
    <div class="absolute -top-20 -right-16 w-96 h-96 rounded-full
                border border-orange-400/[0.12] pointer-events-none"></div>
    <div class="absolute -bottom-12 right-20 w-52 h-52 rounded-full
                border border-white/[0.05] pointer-events-none"></div>
    <div class="absolute top-10 right-32 w-20 h-20 rounded-full
                bg-orange-400/[0.07] pointer-events-none"></div>

    <div class="max-w-3xl mx-auto relative">
        <div class="flex items-center gap-3 mb-5">
            <span class="w-7 h-0.5 bg-orange-400 rounded"></span>
            <span class="text-[11px] font-medium tracking-[0.12em] uppercase
                         text-white/45" style="font-family: 'Poppins', sans-serif;">À propos de MBULU</span>
        </div>

        <h1 class="text-5xl font-bold text-white leading-tight mb-5"
            style="font-family: 'Poppins', sans-serif;">
            La bibliothèque <span class="text-orange-400">numérique</span><br>
            du patrimoine gabonais
        </h1>

        <p class="text-[15px] text-white/55 font-light max-w-lg leading-relaxed"
           style="font-family: 'Poppins', sans-serif;">
            Un espace dédié à la littérature, à la culture et au savoir d'Afrique centrale —
            accessible à tous, préservé pour demain.
        </p>

        <div class="inline-flex items-center gap-2 mt-8 bg-white/[0.06]
                    border border-white/15 rounded-full px-5 py-2 text-[12px]
                    text-white/60" style="font-family: 'Inter', sans-serif;">
            <span class="w-1.5 h-1.5 rounded-full bg-orange-400"></span>
            Fondée à Libreville · Gabon
        </div>
    </div>
</div>

{{-- ── CONTENU ── --}}
<div class="max-w-3xl mx-auto px-5 py-12" style="font-family: 'Poppins', sans-serif;">

    {{-- Intro --}}
    <div class="flex gap-0 mb-12">
        <div class="w-[3px] bg-orange-400 rounded flex-shrink-0"></div>
        <div class="pl-7">
            <p class="text-xl font-semibold text-gray-700 leading-relaxed mb-3">
                Un lieu où les lecteurs trouvent des ouvrages variés, filtrent par catégorie,
                genre, et découvrent de nouveaux titres.
            </p>
            <p class="text-[14.5px] text-gray-500 font-light leading-relaxed">
                Les administrateurs peuvent gérer les livres publiés, suivre qui lit quoi, et garantir
                une expérience riche et sécurisée. Pour lire un livre, inscrivez-vous puis
                connectez-vous à votre espace personnel MBULU.
            </p>
        </div>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-3 gap-4 mb-12">
        @foreach([['500+', 'Ouvrages disponibles'], ['12+', 'Catégories littéraires'], ['2K+', 'Lecteurs inscrits']] as [$num, $label])
        <div class="relative bg-white rounded-2xl border border-black/[0.07] px-6 py-7
                    text-center overflow-hidden">
            <div class="absolute bottom-0 inset-x-0 h-[3px] bg-[#063537]"></div>
            <p class="text-[2.4rem] font-bold text-[#063537] leading-none mb-2">
                {{ $num }}
            </p>
            <p class="text-[11px] font-semibold tracking-wide uppercase text-gray-400">
                {{ $label }}
            </p>
        </div>
        @endforeach
    </div>

    {{-- Features --}}
    <h2 class="text-2xl font-bold text-gray-900 mb-6
               flex items-center gap-4 after:flex-1 after:h-px after:bg-black/10 after:content-['']">
        Ce que nous offrons
    </h2>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-3.5 mb-12">
        @php
        $features = [
            ['Catalogue riche',      'Des centaines d\'ouvrages gabonais et africains — romans, essais, poésie et jeunesse.', 'M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25'],
            ['Recherche & filtres',  'Filtrez par catégorie, auteur ou genre pour trouver exactement ce que vous cherchez.', 'M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 15.803 7.5 7.5 0 0015.803 15.803z'],
            ['Lecture PDF intégrée', 'Accédez aux livres directement en ligne, sans téléchargement, sur tous vos appareils.', 'M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z'],
            ['Espace sécurisé',      'Authentification, gestion des rôles et suivi de lecture pour une expérience personnalisée.', 'M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z'],
        ];
        @endphp

        @foreach($features as [$name, $desc, $path])
        <div class="flex gap-4 items-start bg-white rounded-2xl border border-black/[0.07]
                    p-5 transition-all duration-150
                    hover:-translate-y-0.5 hover:shadow-[0_8px_24px_rgba(6,53,55,0.08)]">
            <div class="w-10 h-10 rounded-[10px] bg-[#063537] flex items-center
                        justify-center flex-shrink-0">
                <svg class="w-[18px] h-[18px] opacity-75" fill="none"
                     stroke="white" stroke-width="1.5" viewBox="0 0 24 24">
                    <path d="{{ $path }}"/>
                </svg>
            </div>
            <div>
                <p class="text-[13.5px] font-semibold text-gray-800 mb-1">{{ $name }}</p>
                <p class="text-[12px] text-gray-400 font-light leading-relaxed">{{ $desc }}</p>
            </div>
        </div>
        @endforeach
    </div>

    {{-- CTA --}}
    <div class="relative bg-[#063537] rounded-2xl px-12 py-11 overflow-hidden
                flex flex-wrap items-center justify-between gap-6">
        <div class="absolute -top-10 -right-10 w-44 h-44 rounded-full
                    border border-orange-400/15 pointer-events-none"></div>
        <div>
            <p class="text-[1.4rem] font-bold text-white mb-2">
                Rejoignez la communauté MBULU
            </p>
            <p class="text-[13px] text-white/50 font-light max-w-xs leading-relaxed">
                Créez votre compte gratuitement et accédez à l'ensemble du catalogue dès aujourd'hui.
            </p>
        </div>
        <div class="flex gap-2.5 flex-shrink-0">
            <a href="{{ route('register') }}"
               class="inline-flex items-center gap-2 bg-orange-500 hover:bg-orange-600
                      text-white text-[13px] font-semibold px-6 py-3 rounded-full
                      transition-colors no-underline whitespace-nowrap">
                S'inscrire gratuitement
            </a>
            <a href="{{ route('login') }}"
               class="inline-flex items-center gap-2 bg-transparent text-white/65
                      text-[13px] font-normal px-5 py-3 rounded-full
                      border border-white/20 hover:bg-white/[0.08]
                      transition-colors no-underline whitespace-nowrap">
                Se connecter
            </a>
        </div>
    </div>

    {{-- Watermark --}}
    <p class="text-center mt-10 text-[10px] font-semibold tracking-[0.25em]
              uppercase text-gray-300">
        B <span class="inline-block w-[3px] h-[3px] rounded-full bg-orange-400 mx-1 align-middle"></span>
        I <span class="inline-block w-[3px] h-[3px] rounded-full bg-orange-400 mx-1 align-middle"></span>
        B <span class="inline-block w-[3px] h-[3px] rounded-full bg-orange-400 mx-1 align-middle"></span>
        L <span class="inline-block w-[3px] h-[3px] rounded-full bg-orange-400 mx-1 align-middle"></span>
        I <span class="inline-block w-[3px] h-[3px] rounded-full bg-orange-400 mx-1 align-middle"></span>
        O <span class="inline-block w-[3px] h-[3px] rounded-full bg-orange-400 mx-1 align-middle"></span>
    </p>

</div>

@endsection