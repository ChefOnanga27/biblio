@extends('layouts.auth')

@section('title', 'Connexion — ' . $siteNom)
@section('auth-title', 'Bon retour sur ' . $siteNom)
@section('auth-subtitle', 'Accédez à toute la collection et retrouvez vos lectures.')

@section('auth')
<form method="POST" action="{{ route('login') }}" class="space-y-6">
    @csrf

    <div>
        <label for="email" class="champ-label">Email</label>
        <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username"
               placeholder="vous@exemple.com" class="champ">
        @include('partials.erreur', ['champ' => 'email'])
    </div>

    <div>
        <div class="flex items-center justify-between">
            <label for="password" class="champ-label">Mot de passe</label>
            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="text-[13px] text-brume hover:text-encre mb-2">Mot de passe oublié ?</a>
            @endif
        </div>
        @include('partials.champ-mot-de-passe', ['nom' => 'password', 'id' => 'password'])
        @include('partials.erreur', ['champ' => 'password'])
    </div>

    <label for="remember_me" class="flex items-center gap-2.5 text-[14px] text-brume cursor-pointer">
        <input id="remember_me" type="checkbox" name="remember" class="w-4 h-4 rounded accent-[#f07155]">
        Se souvenir de moi
    </label>

    <button type="submit" class="btn btn-blanc w-full !py-4">Se connecter</button>

    <p class="text-center text-[14.5px] text-brume">
        Pas encore de compte ?
        <a href="{{ route('register') }}" class="font-semibold text-encre hover:text-corail">Inscrivez-vous</a>
    </p>
</form>
@endsection
