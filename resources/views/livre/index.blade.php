@extends('layouts.site')

@section('title', 'Rechercher — ' . $siteNom)

@section('content')
@php
    // Filtres actifs, affichés en « puces » supprimables
    $puces = collect([
        'search' => request('search') ? '« ' . request('search') . ' »' : null,
        'categorie' => optional($categories->firstWhere('id', request('categorie')))->nom,
        'auteur' => optional($auteurs->firstWhere('id', request('auteur')))->nom,
        'genre' => optional($genres->firstWhere('id', request('genre')))->nom,
        'langue' => request('langue'),
        'annee' => request('annee'),
    ])->filter();

    $selecteur = 'appearance-none h-11 rounded-full bg-surface-2 border border-white/5 pl-5 pr-10 text-[14.5px] text-encre
                  outline-none cursor-pointer transition-colors hover:border-white/15 focus:border-corail/60 disabled:opacity-40 disabled:cursor-default';
    $chevron = '<svg class="pointer-events-none absolute right-4 top-1/2 -translate-y-1/2" width="12" height="12" fill="currentColor" viewBox="0 0 24 24"><path d="M7 10l5 5 5-5z"/></svg>';
@endphp

{{-- ═════════ FILTRES ═════════ --}}
<form id="catalogue-filtres" method="GET" action="{{ route('livre.index') }}" class="relative no-scrollbar -mx-4 px-4 sm:mx-0 sm:px-0 overflow-x-auto">
    <div class="flex items-center gap-3 w-max sm:w-auto sm:flex-wrap pb-1">
        <div class="relative flex-none">
            <label for="filtre-categorie" class="sr-only">Parcourir par catégorie</label>
            <select id="filtre-categorie" name="categorie" class="{{ $selecteur }} !bg-[#232b42] font-medium">
                <option value="" class="bg-surface">Parcourir</option>
                @foreach($categories as $categorie)
                    <option value="{{ $categorie->id }}" @selected((string) request('categorie') === (string) $categorie->id) class="bg-surface">
                        {{ $categorie->nom }} ({{ $categorie->livres_count }})
                    </option>
                @endforeach
            </select>
            {!! $chevron !!}
        </div>

        @foreach([
            ['auteur', 'Auteur', $auteurs->map(fn ($a) => [$a->id, $a->nom])],
            ['genre', 'Genre', $genres->map(fn ($g) => [$g->id, $g->nom])],
            ['langue', 'Langue', $langues->map(fn ($l) => [$l, $l])],
        ] as [$nom, $libelle, $options])
            <div class="relative flex-none">
                <label for="filtre-{{ $nom }}" class="sr-only">{{ $libelle }}</label>
                <select id="filtre-{{ $nom }}" name="{{ $nom }}" class="{{ $selecteur }} max-w-[220px]" @disabled($options->isEmpty())>
                    <option value="" class="bg-surface">{{ $libelle }}</option>
                    @foreach($options as [$valeur, $texte])
                        <option value="{{ $valeur }}" @selected((string) request($nom) === (string) $valeur) class="bg-surface">{{ $texte }}</option>
                    @endforeach
                </select>
                {!! $chevron !!}
            </div>
        @endforeach

        <div class="flex-none">
            <label for="filtre-annee" class="sr-only">Année de publication</label>
            <input id="filtre-annee" type="number" name="annee" value="{{ request('annee') }}" min="1000" max="{{ date('Y') }}" placeholder="Année"
                   class="w-[110px] h-11 rounded-full bg-surface-2 border border-white/5 px-5 text-[14.5px] text-encre placeholder:text-brume outline-none
                          transition-colors hover:border-white/15 focus:border-corail/60 [appearance:textfield] [&::-webkit-inner-spin-button]:appearance-none">
        </div>

        <div class="relative flex-none sm:ml-auto">
            <label for="filtre-tri" class="sr-only">Trier par</label>
            <select id="filtre-tri" name="tri" class="{{ $selecteur }}">
                @foreach($tris as $valeur => $texte)
                    <option value="{{ $valeur }}" @selected($tri === $valeur) class="bg-surface">Trier : {{ $texte }}</option>
                @endforeach
            </select>
            {!! $chevron !!}
        </div>
    </div>
</form>

{{-- Filtres actifs --}}
<div id="catalogue-chips" data-region class="flex flex-wrap items-center gap-2 {{ $puces->isNotEmpty() ? 'mt-4' : '' }}">
    @foreach($puces as $cle => $libelle)
        <a href="{{ request()->fullUrlWithQuery([$cle => null, 'page' => null]) }}" data-ajax
           class="inline-flex items-center gap-1.5 text-[13px] text-encre bg-white/[0.06] hover:bg-corail hover:text-white rounded-full pl-3.5 pr-2.5 py-1.5 transition-colors">
            {{ $libelle }}
            <span aria-hidden="true" class="text-[15px] leading-none">×</span>
            <span class="sr-only">Retirer ce filtre</span>
        </a>
    @endforeach
    @if($puces->count() > 1)
        <a href="{{ route('livre.index') }}" data-ajax class="text-[13px] text-voile hover:text-corail ml-1">Tout effacer</a>
    @endif
</div>

{{-- ═════════ RÉSULTATS ═════════ --}}
<section id="catalogue-results" data-region aria-live="polite" class="mt-8 transition-opacity duration-200">
    <p class="text-[14px] text-brume mb-2">
        <span class="font-semibold text-encre">{{ $livres->total() }}</span>
        ouvrage{{ $livres->total() > 1 ? 's' : '' }}
        {{ request('search') ? 'pour « ' . request('search') . ' »' : 'dans le catalogue' }}
    </p>

    @if($livres->isNotEmpty())
        <div class="hidden lg:grid grid-cols-[minmax(0,2.6fr)_90px_minmax(0,1.3fr)_minmax(0,1.2fr)_minmax(0,1fr)_44px_104px] gap-x-6
                    pt-6 pb-4 border-b border-ligne text-[17px] text-encre font-medium" aria-hidden="true">
            <span>Titre</span><span>Note</span><span>Catégorie</span><span>Disponibilité</span><span>Statut</span>
        </div>

        <ul aria-label="Résultats de la recherche">
            @foreach($livres as $livre)
                @include('livre.ligne', ['estFavori' => in_array($livre->id, $favorisIds)])
            @endforeach
        </ul>

        <div class="mt-10" data-pagination>
            {{ $livres->onEachSide(1)->links('partials.pagination') }}
        </div>
    @else
        <div class="mt-6 text-center py-20 px-6 rounded-2xl border border-ligne bg-surface/60">
            <span class="mx-auto mb-5 w-16 h-16 rounded-2xl bg-corail/10 text-corail flex items-center justify-center">
                <svg width="28" height="28" fill="none" stroke="currentColor" stroke-width="1.6" viewBox="0 0 24 24"><path d="M21 21l-5.2-5.2M17 10.5a6.5 6.5 0 11-13 0 6.5 6.5 0 0113 0z"/></svg>
            </span>
            <p class="text-[17px] font-semibold">Aucun ouvrage trouvé.</p>
            <p class="text-[14px] text-brume mt-1">Essayez une autre recherche.</p>
            @if($puces->isNotEmpty())
                <a href="{{ route('livre.index') }}" data-ajax class="btn btn-discret mt-6">Réinitialiser les filtres</a>
            @endif
        </div>
    @endif
</section>

<div id="catalogue-toast" role="status" aria-live="polite"
     class="fixed bottom-24 lg:bottom-6 left-1/2 -translate-x-1/2 z-50 bg-encre text-[#0b1020] text-[13.5px] font-medium
            rounded-full px-5 py-2.5 shadow-lg opacity-0 translate-y-2 pointer-events-none transition-all duration-300"></div>
@endsection

@push('scripts')
<script>
// Recherche dynamique : la barre du haut et les filtres mettent à jour les résultats sans recharger la page.
// Sans JavaScript, tout fonctionne avec des liens et formulaires classiques.
(function () {
    const base = @json(route('livre.index'));
    const formulaires = ['shell-recherche-form', 'catalogue-filtres'].map(id => document.getElementById(id)).filter(Boolean);
    const champs = () => formulaires.flatMap(f => [...f.querySelectorAll('input[name], select[name]')]);
    let minuteur = null, requete = null;

    function urlCourante() {
        const params = new URLSearchParams();
        champs().forEach(c => { if (c.value) params.set(c.name, c.value); });
        const q = params.toString();
        return base + (q ? '?' + q : '');
    }

    function synchroniser(url) {
        const params = new URL(url, location.href).searchParams;
        champs().forEach(function (c) {
            if (c === document.activeElement && c.type === 'search') return;
            if (c.name === 'tri' && !params.get('tri')) return;
            c.value = params.get(c.name) || '';
        });
    }

    async function charger(url, historique = true) {
        if (requete) requete.abort();
        requete = new AbortController();
        document.getElementById('catalogue-results').style.opacity = '0.45';
        try {
            const reponse = await fetch(url, { headers: { 'X-Requested-With': 'fetch' }, signal: requete.signal });
            const page = new DOMParser().parseFromString(await reponse.text(), 'text/html');
            document.querySelectorAll('[data-region]').forEach(function (region) {
                const nouvelle = page.getElementById(region.id);
                if (nouvelle) region.replaceWith(nouvelle);
            });
            if (historique) history.pushState({}, '', url);
            synchroniser(url);
        } catch (e) {
            if (e.name !== 'AbortError') location.href = url;
        } finally {
            document.getElementById('catalogue-results').style.opacity = '';
        }
    }

    formulaires.forEach(function (form) {
        form.addEventListener('submit', e => { e.preventDefault(); clearTimeout(minuteur); charger(urlCourante()); });
        form.addEventListener('input', function (e) {
            // Les deux sélecteurs de catégorie (barre du haut, filtres) restent alignés
            if (e.target.name === 'categorie') champs().filter(c => c.name === 'categorie').forEach(c => c.value = e.target.value);
            clearTimeout(minuteur);
            const delai = ['search', 'number'].includes(e.target.type) ? 350 : 0;
            minuteur = setTimeout(() => charger(urlCourante()), delai);
        });
    });

    document.addEventListener('click', function (e) {
        const lien = e.target.closest('a[data-ajax], [data-pagination] a');
        if (!lien || e.metaKey || e.ctrlKey || e.shiftKey) return;
        e.preventDefault();
        charger(lien.href);
        if (lien.closest('[data-pagination]')) document.getElementById('catalogue-results').scrollIntoView({ behavior: 'smooth', block: 'start' });
    });

    window.addEventListener('popstate', () => charger(location.href, false));

    // Favoris sans rechargement
    const toast = document.getElementById('catalogue-toast');
    function afficher(message) {
        toast.textContent = message;
        toast.classList.remove('opacity-0', 'translate-y-2');
        clearTimeout(toast._t);
        toast._t = setTimeout(() => toast.classList.add('opacity-0', 'translate-y-2'), 2200);
    }

    document.addEventListener('submit', async function (e) {
        const form = e.target.closest('form[data-favori]');
        if (!form) return;
        e.preventDefault();
        const bouton = form.querySelector('button');
        bouton.disabled = true;
        try {
            const reponse = await fetch(form.action, { method: 'POST', headers: { 'Accept': 'application/json' }, body: new FormData(form) });
            if (!reponse.ok) throw new Error();
            const data = await reponse.json();
            const titre = bouton.getAttribute('aria-label').split(' : ').slice(1).join(' : ');
            const libelle = data.favori ? 'Retirer des favoris' : 'Ajouter aux favoris';
            bouton.setAttribute('aria-pressed', data.favori ? 'true' : 'false');
            bouton.setAttribute('aria-label', libelle + ' : ' + titre);
            bouton.title = libelle;
            bouton.querySelector('[data-coeur]').setAttribute('fill', data.favori ? 'currentColor' : 'none');
            afficher(data.message);
        } catch (_) {
            form.submit();
        } finally {
            bouton.disabled = false;
        }
    });
})();
</script>
@endpush
