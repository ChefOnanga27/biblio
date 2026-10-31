@extends('espace.layout')

@section('title', 'Historique — ' . $siteNom)

@section('espace')
<header class="mb-6">
    <h1 class="text-[26px] sm:text-[30px] font-bold text-encre leading-tight">Historique de lecture</h1>
    <p class="text-[14px] text-brume mt-1">Les livres que vous avez consultés, du plus récent au plus ancien.</p>
</header>

@if($lectures->isNotEmpty())
    @php
        $jour = function ($date) {
            if ($date->isToday()) return 'Aujourd\'hui';
            if ($date->isYesterday()) return 'Hier';
            return ucfirst($date->locale('fr')->translatedFormat('l j F Y'));
        };
    @endphp

    @foreach($lectures->groupBy(fn ($lecture) => $lecture->read_at->toDateString()) as $groupe)
        <section class="mb-7">
            <h2 class="text-[12px] font-semibold uppercase tracking-[0.1em] text-voile mb-3">{{ $jour($groupe->first()->read_at) }}</h2>

            <ul class="bg-surface/70 border border-ligne rounded-2xl divide-y divide-ligne">
                @foreach($groupe as $lecture)
                    @php $livre = $lecture->livre; @endphp
                    <li class="flex items-center gap-4 p-3 sm:p-4">
                        <a href="{{ route('livres.show', $livre) }}" class="livre-3d block flex-none w-12 sm:w-14">
                            <span class="livre-3d__couverture block aspect-[2/3] bg-surface-2">
                                @if($livre->image)
                                    <img src="{{ media_url($livre->image) }}" alt="" loading="lazy" class="w-full h-full object-cover">
                                @endif
                                <span class="livre-3d__dos" aria-hidden="true"></span>
                            </span>
                            <span class="livre-3d__pages" aria-hidden="true"></span>
                        </a>

                        <div class="min-w-0 flex-1">
                            <a href="{{ route('livres.show', $livre) }}" class="block text-[14px] font-semibold text-encre hover:text-encre truncate">
                                {{ $livre->titre }}
                            </a>
                            <p class="text-[12px] text-voile truncate">{{ $livre->auteur }}</p>

                            <div class="mt-1.5 flex items-center gap-2.5 text-[11.5px]">
                                @if($lecture->estTerminee())
                                    <span class="font-semibold text-encre">✓ Terminé</span>
                                @elseif($lecture->estEnCours())
                                    <div class="w-24 h-1.5 rounded-full bg-white/10 overflow-hidden">
                                        <div class="h-full bg-corail rounded-full" style="width: {{ $lecture->progression }}%"></div>
                                    </div>
                                    <span class="font-semibold text-brume">{{ $lecture->progression }} %</span>
                                @else
                                    <span class="text-voile">Fiche consultée</span>
                                @endif
                                <span class="text-voile">·</span>
                                <time datetime="{{ $lecture->read_at->toIso8601String() }}" class="text-voile">{{ $lecture->read_at->format('H:i') }}</time>
                            </div>
                        </div>

                        @if($livre->pdf_path)
                            <a href="{{ route('livres.lire', $livre) }}"
                               class="flex-none hidden sm:inline-flex items-center gap-1.5 rounded-full px-4 py-2 text-[12px] font-semibold
                                      border border-white/15 text-encre hover:bg-corail hover:text-white hover:border-corail transition-colors">
                                {{ $lecture->estTerminee() ? 'Relire' : ($lecture->estEnCours() ? 'Continuer' : 'Lire') }}
                            </a>
                        @endif
                    </li>
                @endforeach
            </ul>
        </section>
    @endforeach

    <div class="mt-8">{{ $lectures->links('partials.pagination') }}</div>
@else
    <div class="text-center py-16 px-6 bg-surface/70 border border-ligne rounded-3xl">
        <p class="text-[15px] font-semibold text-encre">Aucun historique pour le moment</p>
        <p class="text-[13px] text-voile mt-1">Les livres que vous ouvrez s'afficheront ici.</p>
        <a href="{{ route('livre.index') }}" class="btn btn-blanc mt-6">Explorer le catalogue</a>
    </div>
@endif
@endsection
