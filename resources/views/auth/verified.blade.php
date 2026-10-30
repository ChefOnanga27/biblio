@extends('layouts.auth')

@section('title', 'Compte vérifié — ' . $siteNom)
@section('centre', true)
@section('auth-title', 'Vérification')
@section('auth-subtitle', 'Merci !')

@section('auth')
<p class="text-[17px] font-medium text-voile">Votre adresse email est vérifiée</p>

<span class="mx-auto mt-9 w-24 h-24 rounded-full bg-menthe flex items-center justify-center shadow-[0_0_40px_rgba(63,191,106,0.35)]">
    <svg width="46" height="46" fill="none" stroke="#10131c" stroke-width="3.2" viewBox="0 0 24 24" aria-hidden="true">
        <path d="M4.5 12.75l6 6 9-13.5" stroke-linecap="round" stroke-linejoin="round"/>
    </svg>
</span>

<a href="{{ route('home') }}" class="btn w-full !py-4 mt-10 bg-sable text-[#10131c] hover:bg-[#f1dcbe]">Accéder à la bibliothèque</a>
@endsection
