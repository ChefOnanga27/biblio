@extends('base')

@section('title', 'Accueil')

@section('content')

<!-- HERO SECTION -->
<section class="relative bg-gradient-to-r from-teal-700 to-gray-400 text-white shadow-white mb-10 overflow-hidden">
    <!-- Background Image -->
    <div class="absolute inset-0">
        <img src="{{ asset('hero.jpg') }}" alt="Background"
             class="w-full h-full object-cover">
        <!-- Overlay blanc semi-transparent pour lisibilité -->
        <div class="absolute inset-0 backdrop-blur-sm"></div>
    </div>

    <!-- Contenu -->
    <div class="relative z-10 flex flex-col md:flex-row items-center justify-between max-w-7xl mx-auto px-6 py-20 gap-10">
        <!-- Texte -->
        <div class="max-w-xl text-center md:text-left">
            <h1 class="text-4xl uppercase font-bold mb-4">
                Bienvenue à la Bibliothèque
            </h1>
            <p class="text-lg mb-6 text-black shadow-white">
                Découvrez notre collection de livres et trouvez votre prochain coup de cœur.
            </p>
            <a href="#" class="bg-green-900 text-white px-6 py-3 rounded-full font-semibold hover:bg-green-800 transition">
                Explorer les livres
            </a>
        </div>

        <!-- Illustration -->
        <div class="hidden md:block flex-shrink-0">
            <img src="https://cdn-icons-png.flaticon.com/512/3135/3135755.png" 
                 class="w-64 opacity-90">
        </div>
    </div>
</section>
<section class="max-w-7xl mx-auto px-4 flex flex-col md:flex-row items-center gap-10 my-10">

    <!-- Texte -->
    <div class="md:w-1/2">
        <h2 class="text-2xl md:text-3xl font-bold text-black mb-4 shadow-red-300">
            La Bibliothèque numérique interactive des Gabonais
        </h2>

        <p class="text-black leading-relaxed">
            Bienvenue sur BIBLIO ! Nous sommes ravis de vous accueillir sur le premier site de la bibliothèque
            numérique interactive dédié aux œuvres littéraires. Biblio a été conçu pour préserver et
            promouvoir la richesse linguistique et culturelle du Gabon. Que vous soyez un jeune
            Gabonais désireux de reconnecter avec vos racines ou un passionné de lecture en quête
            de nouvelles découvertes, vous êtes au bon endroit.
        </p>
    </div>

    <!-- Vidéo -->
    <div class="md:w-1/2 w-full h-72 overflow-hidden">
        <video class="w-full h-full object-cover" autoplay muted loop>
            <source src="{{ asset('video.webm') }}" type="video/webm">
            Votre navigateur ne supporte pas la vidéo.
        </video>
    </div>

</section>
<section class="h-72 flex items-center justify-center text-center text-white bg-cover bg-center"
         style="background-image: url('{{ asset('Background.png') }}');">

    <div>
        <h2 class="text-2xl md:text-2xl font-bold text-black shadow-white">
            Obtenez plusieurs livres instantanément et faites-vous plaisir
        </h2>
    </div>

</section>
<section class="max-w-7xl mx-auto px-4 flex flex-col gap-10">

    <h2 class="text-3xl font-bold text-black mb-4 shadow-green-300 mt-10">
     Decouvrez Nos nouveautés
    </h2>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        @forelse($livres as $livre)
            <div class="">
                @include('livre.card')
            </div>
        @empty
            <p class="text-gray-500 col-span-full">Pas encore de nouveautés disponibles.</p>
        @endforelse
    </div>

</section>
<section class="h-100 flex items-center justify-center text-center text-white bg-[#063537] mt-10 p-30  shadow-lg">
    <div class="max-w-2xl m-20">
        <h2 class="text-orange-500 text-2xl font-bold shadow-md mb-10">Un vaste panel de livres accessibles gratuitement et adaptés à tous les profils de lecteurs.</h2>
        <p>Biblio révolutionne l’accès aux livres en combinant <br> lecture, interactivité et technologies modernes, pour une expérience fluide, immersive et accessible à tous, même hors ligne.</p>
    </div>
    <div class="hidden md:block flex-shrink-0 m-20">
        <img src="/icon.svg" alt="Icon"
             class="w-full opacity-90">
    </div>
</section>
<section class="bg-orange-500 py-20 px-4">

    <!-- Titre -->
    <h2 class="text-center text-white text-3xl md:text-4xl font-bold mb-12">
        Questions fréquentes
    </h2>

    <!-- FAQ Container -->
    <div class="max-w-2xl mx-auto space-y-4">

        <!-- Item -->
        <details class="group bg-[#063537] text-white rounded-xl p-5 cursor-pointer">
            <summary class="flex justify-between items-center font-medium list-none">
                Qu'est ce que BIBLIO?
                <span class="text-xl transition-transform group-open:rotate-45">+</span>
            </summary>
            <p class="mt-4 text-gray-300">
                BIBLIO est une plateforme dédiée à la découverte et à l’apprentissage à travers des contenus interactifs.
            </p>
        </details>

        <!-- Item -->
        <details class="group bg-[#063537] text-white rounded-xl p-5 cursor-pointer">
            <summary class="flex justify-between items-center font-medium list-none">
                A qui s'adresse BIBLIO?
                <span class="text-xl transition-transform group-open:rotate-45">+</span>
            </summary>
            <p class="mt-4 text-gray-300">
                BIBLIO s’adresse à tous les passionnés de lecture et d’apprentissage, quel que soit leur niveau.
            </p>
        </details>

        <!-- Item -->
        <details class="group bg-[#063537] text-white rounded-xl p-5 cursor-pointer">
            <summary class="flex justify-between items-center font-medium list-none">
                Comment puis-je contribuer à BIBLIO?
                <span class="text-xl transition-transform group-open:rotate-45">+</span>
            </summary>
            <p class="mt-4 text-gray-300">
                Vous pouvez contribuer en partageant BIBLIO à vos proches, en proposant des idées ou en participant à la communauté.
            </p>
        </details>

        <!-- Item -->
        <details class="group bg-[#063537] text-white rounded-xl p-5 cursor-pointer">
            <summary class="flex justify-between items-center font-medium list-none">
                Quelles oeuvres gabonaises sont incluses dans BIBLIO?
                <span class="text-xl transition-transform group-open:rotate-45">+</span>
            </summary>
            <p class="mt-4 text-gray-300">
                Plusieurs oeuvres locales sont intégrées afin de valoriser la richesse culturelle du Gabon.
            </p>
        </details>

    </div>

</section>
@endsection