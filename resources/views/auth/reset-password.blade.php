@extends('layouts.auth')

@section('title', 'Nouveau mot de passe — ' . $siteNom)
@section('auth-title', 'Nouveau mot de passe')
@section('auth-subtitle', 'Choisissez un nouveau mot de passe pour votre compte.')

@section('auth')
<form method="POST" action="{{ route('password.store') }}" class="space-y-5">
    @csrf
    <input type="hidden" name="token" value="{{ $request->route('token') }}">

    <div>
        <label for="email" class="champ-label">Email</label>
        <input id="email" type="email" name="email" value="{{ old('email', $request->email) }}" required autofocus autocomplete="username" class="champ">
        @include('partials.erreur', ['champ' => 'email'])
    </div>

    <div>
        <label for="password" class="champ-label">Nouveau mot de passe</label>
        @include('partials.champ-mot-de-passe', ['nom' => 'password', 'id' => 'password', 'autocomplete' => 'new-password'])
        @include('partials.erreur', ['champ' => 'password'])
    </div>

    <div>
        <label for="password_confirmation" class="champ-label">Confirmer le mot de passe</label>
        @include('partials.champ-mot-de-passe', ['nom' => 'password_confirmation', 'id' => 'password_confirmation', 'autocomplete' => 'new-password', 'placeholder' => 'Le même mot de passe'])
        @include('partials.erreur', ['champ' => 'password_confirmation'])
    </div>

    <button type="submit" class="btn btn-blanc w-full !py-4 !mt-8">Enregistrer le mot de passe</button>
</form>
@endsection
