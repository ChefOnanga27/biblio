@extends('layouts.site')

@section('title', 'Support — ' . $siteNom)

@section('content')
<header>
    <h1 class="text-[28px] sm:text-[32px] font-semibold text-brume">Écrivez-<span class="text-corail">nous</span></h1>
    <p class="mt-1.5 text-[15px] text-voile max-w-xl">Pour toute question, suggestion ou demande d'assistance, notre équipe vous répond dans les plus brefs délais.</p>
</header>

<div class="mt-8 grid lg:grid-cols-[minmax(0,1fr)_340px] gap-8 items-start">

    <form action="{{ route('contact.submit') }}" method="POST" class="rounded-[14px] bg-surface/50 border border-ligne p-6 sm:p-8 space-y-6">
        @csrf
        <div class="grid md:grid-cols-2 gap-6">
            <div>
                <label for="name" class="champ-label">Nom complet</label>
                <input type="text" id="name" name="name" value="{{ old('name', auth()->user()?->name) }}" required autocomplete="name" placeholder="Ex : Jean-Baptiste Mba" class="champ">
                @include('partials.erreur', ['champ' => 'name'])
            </div>
            <div>
                <label for="email" class="champ-label">Adresse e-mail</label>
                <input type="email" id="email" name="email" value="{{ old('email', auth()->user()?->email) }}" required autocomplete="email" placeholder="vous@exemple.com" class="champ">
                @include('partials.erreur', ['champ' => 'email'])
            </div>
        </div>
        <div>
            <label for="message" class="champ-label">Message</label>
            <textarea id="message" name="message" rows="7" required placeholder="Décrivez votre demande…" class="champ resize-y">{{ old('message') }}</textarea>
            @include('partials.erreur', ['champ' => 'message'])
        </div>
        <button type="submit" class="btn btn-blanc !px-10 !py-4">Envoyer le message</button>
    </form>

    <aside class="space-y-5">
        <div class="rounded-[14px] bg-surface/50 border border-ligne p-6">
            <h2 class="text-[17px] font-semibold">Nos coordonnées</h2>
            <dl class="mt-4 space-y-4">
                @foreach([
                    ['E-mail', 'contact@biblio.ga', 'M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75'],
                    ['Adresse', 'Libreville, Gabon', 'M15 10.5a3 3 0 11-6 0 3 3 0 016 0zM19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z'],
                    ['Téléphone', '+241 00 00 00 00', 'M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z'],
                ] as [$libelle, $valeur, $icone])
                    <div class="flex items-start gap-3">
                        <span class="flex-none w-9 h-9 rounded-[9px] bg-corail/15 text-corail flex items-center justify-center">
                            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 24 24" aria-hidden="true"><path d="{{ $icone }}"/></svg>
                        </span>
                        <div>
                            <dt class="text-[12px] text-voile">{{ $libelle }}</dt>
                            <dd class="text-[14.5px]">{{ $valeur }}</dd>
                        </div>
                    </div>
                @endforeach
            </dl>
        </div>

        <div class="rounded-[14px] p-6 bg-gradient-to-br from-[#d4524a] via-[#b0446b] to-[#7d3a9a]">
            <h2 class="text-[17px] font-semibold">Horaires du support</h2>
            <dl class="mt-3">
                @foreach([['Lun – Ven', '8h – 18h'], ['Samedi', '9h – 13h'], ['Dimanche', 'Fermé']] as [$jour, $heures])
                    <div class="flex justify-between py-2 text-[14px] {{ ! $loop->last ? 'border-b border-white/15' : '' }}">
                        <dt class="text-white/75">{{ $jour }}</dt><dd>{{ $heures }}</dd>
                    </div>
                @endforeach
            </dl>
        </div>
    </aside>
</div>
@endsection
