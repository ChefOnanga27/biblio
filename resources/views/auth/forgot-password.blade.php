@extends('layouts.auth')

@section('title', 'Mot de passe oublié — ' . $siteNom)
@section('auth-title', 'Mot de passe oublié')
@section('auth-subtitle', 'Indiquez votre email : nous vous enverrons un lien pour choisir un nouveau mot de passe.')

@section('auth')
<form method="POST" action="{{ route('password.email') }}" class="space-y-6">
    @csrf

    <div>
        <label for="email" class="champ-label">Email</label>
        <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username"
               placeholder="vous@exemple.com" class="champ">
        @include('partials.erreur', ['champ' => 'email'])
    </div>

    <button type="submit" class="btn btn-blanc w-full !py-4">Envoyer le lien</button>

    <p class="text-center text-[14.5px] text-brume">
        <a href="{{ route('login') }}" class="font-semibold text-encre hover:text-corail">← Retour à la connexion</a>
    </p>
</form>
@endsection
