@extends('layouts.site')

@section('content')
@php
    $liens = [
        ['Mon espace', route('dashboard'), request()->routeIs('dashboard')],
        ['Ma bibliothèque', route('espace.bibliotheque'), request()->routeIs('espace.bibliotheque')],
        ['Historique', route('espace.historique'), request()->routeIs('espace.historique')],
        ['Paramètres', route('profile.edit'), request()->routeIs('profile.*')],
    ];
@endphp

<nav aria-label="Espace personnel" class="relative no-scrollbar -mx-4 px-4 sm:mx-0 sm:px-0 overflow-x-auto">
    <ul class="flex gap-2 w-max">
        @foreach($liens as [$libelle, $url, $actif])
            <li>
                <a href="{{ $url }}" @if($actif) aria-current="page" @endif
                   class="inline-flex items-center h-10 rounded-full px-5 text-[14px] transition-colors
                          {{ $actif ? 'bg-encre text-[#0b1020] font-semibold' : 'bg-surface-2 text-brume hover:text-encre' }}">
                    {{ $libelle }}
                </a>
            </li>
        @endforeach
    </ul>
</nav>

<div class="mt-8">
    @yield('espace')
</div>
@endsection
