@extends('layouts.auth')

@section('title', 'Confirmer le mot de passe — ' . $siteNom)
@section('auth-title', 'Confirmez votre mot de passe')
@section('auth-subtitle', 'Cette zone est protégée : saisissez à nouveau votre mot de passe pour continuer.')

@section('auth')
<form method="POST" action="{{ route('password.confirm') }}" class="space-y-6">
    @csrf

    <div>
        <label for="password" class="champ-label">Mot de passe</label>
        @include('partials.champ-mot-de-passe', ['nom' => 'password', 'id' => 'password'])
        @include('partials.erreur', ['champ' => 'password'])
    </div>

    <button type="submit" class="btn btn-blanc w-full !py-4">Confirmer</button>
</form>
@endsection
