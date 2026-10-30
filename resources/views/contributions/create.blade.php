@extends('layouts.site')

@section('title', 'Contribuer — ' . $siteNom)

@section('content')
@php
    $badges = ['en_attente' => 'bg-white/10 text-brume', 'acceptee' => 'bg-menthe text-[#0b1020]', 'refusee' => 'bg-red-500/15 text-red-200'];
@endphp

<header>
    <h1 class="text-[28px] sm:text-[32px] font-semibold text-brume">Contribuer à <span class="text-corail">la bibliothèque</span></h1>
    <p class="mt-1.5 text-[15px] text-voile max-w-2xl">Vous avez un livre à partager ? Proposez-le : un administrateur le vérifie avant de l'ajouter au catalogue.</p>
</header>

<div class="mt-8 grid xl:grid-cols-[minmax(0,1fr)_380px] gap-8 items-start">

    {{-- ═════════ FORMULAIRE ═════════ --}}
    <form method="POST" action="{{ route('contributions.store') }}" enctype="multipart/form-data"
          class="rounded-[14px] bg-surface/50 border border-ligne p-6 sm:p-8 space-y-6"
          x-data="{ pdf: '', image: '' }">
        @csrf

        <div class="grid md:grid-cols-2 gap-x-8 gap-y-6">
            <div>
                <label for="titre" class="champ-label">Titre</label>
                <input id="titre" name="titre" type="text" value="{{ old('titre') }}" required class="champ">
                @include('partials.erreur', ['champ' => 'titre'])
            </div>
            <div>
                <label for="auteur" class="champ-label">Auteur</label>
                <input id="auteur" name="auteur" type="text" value="{{ old('auteur') }}" required placeholder="Prénom Nom" class="champ">
                @include('partials.erreur', ['champ' => 'auteur'])
            </div>
            <div>
                <label for="categorie_id" class="champ-label">Catégorie <span class="text-voile">(facultatif)</span></label>
                <select id="categorie_id" name="categorie_id" class="champ cursor-pointer">
                    <option value="">— Je ne sais pas —</option>
                    @foreach($categories as $categorie)
                        <option value="{{ $categorie->id }}" @selected(old('categorie_id') == $categorie->id)>{{ $categorie->nom }}</option>
                    @endforeach
                </select>
                @include('partials.erreur', ['champ' => 'categorie_id'])
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="langue" class="champ-label">Langue</label>
                    <input id="langue" name="langue" type="text" value="{{ old('langue') }}" list="langues-connues" placeholder="Français" class="champ">
                    <datalist id="langues-connues">
                        @foreach(['Français', 'Anglais', 'Fang', 'Myènè', 'Punu', 'Nzebi', 'Téké'] as $langue)<option value="{{ $langue }}">@endforeach
                    </datalist>
                </div>
                <div>
                    <label for="isbn" class="champ-label">ISBN</label>
                    <input id="isbn" name="isbn" type="text" value="{{ old('isbn') }}" placeholder="Facultatif" class="champ">
                </div>
            </div>
        </div>

        <div>
            <label for="description" class="champ-label">Résumé <span class="text-voile">(facultatif)</span></label>
            <textarea id="description" name="description" rows="5" class="champ resize-y">{{ old('description') }}</textarea>
            @include('partials.erreur', ['champ' => 'description'])
        </div>

        <div class="grid md:grid-cols-2 gap-x-8 gap-y-6">
            <div>
                <span class="champ-label">Livre (PDF, 50 Mo max.)</span>
                <label for="pdf" class="champ flex items-center justify-center gap-2.5 cursor-pointer hover:bg-[#262e43] text-brume !py-5 border-dashed !border-white/15">
                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true"><path d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5"/></svg>
                    <span x-text="pdf || 'Choisir le fichier PDF'" class="truncate">Choisir le fichier PDF</span>
                </label>
                <input id="pdf" name="pdf" type="file" accept="application/pdf" required class="sr-only" @change="pdf = $event.target.files[0]?.name || ''">
                @include('partials.erreur', ['champ' => 'pdf'])
            </div>
            <div>
                <span class="champ-label">Couverture <span class="text-voile">(facultatif)</span></span>
                <label for="image" class="champ flex items-center justify-center gap-2.5 cursor-pointer hover:bg-[#262e43] text-brume !py-5 border-dashed !border-white/15">
                    <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true"><path d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909M3.75 21h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v13.5a1.5 1.5 0 001.5 1.5z"/></svg>
                    <span x-text="image || 'Choisir une image'" class="truncate">Choisir une image</span>
                </label>
                <input id="image" name="image" type="file" accept="image/png,image/jpeg,image/webp" class="sr-only" @change="image = $event.target.files[0]?.name || ''">
                @include('partials.erreur', ['champ' => 'image'])
            </div>
        </div>

        <div>
            <label class="flex items-start gap-3 text-[14px] text-brume cursor-pointer">
                <input type="checkbox" name="droits" value="1" @checked(old('droits')) class="mt-1 w-4 h-4 accent-[#f07155]">
                Je confirme que ce livre peut être partagé librement (domaine public, licence libre, ou j'en détiens les droits).
            </label>
            @include('partials.erreur', ['champ' => 'droits'])
        </div>

        <button type="submit" class="btn btn-blanc !px-10 !py-4 !text-[15px]">Envoyer ma proposition</button>
    </form>

    {{-- ═════════ MES PROPOSITIONS ═════════ --}}
    <aside class="rounded-[14px] bg-surface/50 border border-ligne p-6">
        <h2 class="text-[18px] font-semibold">Mes propositions</h2>
        @forelse($contributions as $contribution)
            <div class="py-4 border-b border-ligne last:border-0">
                <div class="flex items-start justify-between gap-3">
                    <div class="min-w-0">
                        <p class="text-[15px] font-medium truncate">{{ $contribution->titre }}</p>
                        <p class="text-[12.5px] text-voile truncate">{{ $contribution->auteur }} · {{ $contribution->created_at->locale('fr')->translatedFormat('j M Y') }}</p>
                    </div>
                    <span class="flex-none rounded px-2.5 py-1 text-[12px] font-medium {{ $badges[$contribution->statut] ?? '' }}">{{ $contribution->libelleStatut() }}</span>
                </div>
                @if($contribution->statut === 'refusee' && $contribution->motif_refus)
                    <p class="mt-2 text-[13px] text-brume"><span class="text-voile">Motif :</span> {{ $contribution->motif_refus }}</p>
                @endif
                @if($contribution->livre)
                    <a href="{{ route('livres.show', $contribution->livre) }}" class="mt-2 inline-block text-[13px] text-corail hover:underline">Voir dans le catalogue →</a>
                @endif
            </div>
        @empty
            <p class="mt-3 text-[14px] text-voile">Vous n'avez encore rien proposé.</p>
        @endforelse
    </aside>
</div>
@endsection
