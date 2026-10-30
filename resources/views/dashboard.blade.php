@extends('base')

@section('title', 'Mon espace — MBULU')

@section('content')
    <div class="max-w-3xl mx-auto px-4 py-12">
        <h1 style="font-size:26px; font-weight:700; color:#063537; margin:0 0 24px;">Mon espace</h1>

        <div class="p-6 sm:p-8 bg-white rounded-2xl" style="border:1px solid #f0ede8;">
            <p style="margin:0 0 20px; color:#4a4a45;">Vous êtes connecté, {{ auth()->user()->name }} !</p>

            <div style="display:flex; flex-wrap:wrap; gap:8px;">
                <a href="{{ route('livre.index') }}" class="btn-primary">Parcourir la bibliothèque</a>
                <a href="{{ route('profile.edit') }}" class="btn-ghost">Mon profil</a>
                @if(auth()->user()->is_admin)
                    <a href="{{ route('admin.dashboard') }}" class="btn-ghost">Administration</a>
                @endif
            </div>
        </div>
    </div>
@endsection
