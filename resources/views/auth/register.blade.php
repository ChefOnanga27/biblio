@extends('layouts.auth')

@section('title', 'Créer un compte — ' . $siteNom)
@section('auth-title', 'Créez votre compte lecteur')
@section('auth-subtitle', 'Remplissez ces quelques champs pour accéder à la bibliothèque. Un code de vérification vous sera envoyé par email.')

@section('auth')
<form method="POST" action="{{ route('register') }}" enctype="multipart/form-data" class="space-y-5"
      x-data="{ fichier: '' }">
    @csrf

    <div>
        <label for="name" class="champ-label">Nom complet</label>
        <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name"
               placeholder="Prénom Nom" class="champ">
        @include('partials.erreur', ['champ' => 'name'])
    </div>

    <div>
        <label for="email" class="champ-label">Email</label>
        <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username"
               placeholder="vous@exemple.com" class="champ">
        @include('partials.erreur', ['champ' => 'email'])
    </div>

    <div>
        <label for="password" class="champ-label">Mot de passe</label>
        @include('partials.champ-mot-de-passe', ['nom' => 'password', 'id' => 'password', 'autocomplete' => 'new-password'])
        @include('partials.erreur', ['champ' => 'password'])
    </div>

    <div>
        <span class="champ-label">Photo de profil <span class="text-voile">(facultatif)</span></span>
        <label for="avatar" class="champ flex items-center justify-center gap-2.5 cursor-pointer hover:bg-[#262e43] text-brume">
            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5"/></svg>
            <span x-text="fichier || 'Choisir une image'">Choisir une image</span>
        </label>
        <input id="avatar" type="file" name="avatar" accept="image/png,image/jpeg,image/webp" class="sr-only"
               @change="fichier = $event.target.files[0]?.name || ''">
        @include('partials.erreur', ['champ' => 'avatar'])
    </div>

    <button type="submit" class="btn btn-blanc w-full !py-4 !mt-8">Créer mon compte</button>

    <p class="text-center text-[14.5px] text-brume">
        Déjà inscrit ?
        <a href="{{ route('login') }}" class="font-semibold text-encre hover:text-corail">Se connecter</a>
    </p>
</form>
@endsection
