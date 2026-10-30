@extends('admin.admin')

@section('title', 'Tableau de bord')
@section('page-title', 'Tableau de bord')
@section('page-subtitle', 'Vue d\'ensemble de ' . $siteNom . ' sur les ' . $jours . ' derniers jours')

@section('content')
@php
    $nombre = fn ($n) => number_format($n, 0, ',', ' ');
    $tuiles = [
        ['Livres au catalogue', $nombre($indicateurs['livres']), $indicateurs['contributions'] ? $indicateurs['contributions'] . ' contribution(s) à valider' : 'Aucune contribution en attente', route('admin.livres.index')],
        ['Lecteurs inscrits', $nombre($indicateurs['lecteurs']), '+' . $nombre($indicateurs['nouveauxLecteurs']) . ' sur ' . $jours . ' jours', null],
        ['Lectures actives', $nombre($indicateurs['lecturesActives']), $nombre($indicateurs['termines']) . ' livre(s) terminé(s)', route('admin.lectures.index')],
        ['Note moyenne', $indicateurs['nombreNotes'] ? number_format($indicateurs['noteMoyenne'], 1, ',', '') . ' /5' : '—', $nombre($indicateurs['nombreNotes']) . ' note(s) au total', null],
    ];

    // Géométrie du graphique (SVG)
    $largeur = 720; $hauteur = 220; $marge = ['haut' => 12, 'bas' => 28, 'gauche' => 34, 'droite' => 8];
    $max = max(1, $serie->max('valeur'));
    $pas = $max <= 4 ? 1 : (int) ceil($max / 4);
    $plafond = $pas * (int) ceil($max / $pas);
    $zoneL = $largeur - $marge['gauche'] - $marge['droite'];
    $zoneH = $hauteur - $marge['haut'] - $marge['bas'];
    $colonne = $zoneL / $serie->count();
    $barre = max(4, $colonne - 4);
    $y = fn ($v) => $marge['haut'] + $zoneH - ($v / $plafond) * $zoneH;
    $carte = 'background:white; border:1px solid #eceef3; border-radius:16px;';
    $titre = 'font-size:15px; font-weight:600; color:#121829;';
@endphp

{{-- ═════════ INDICATEURS ═════════ --}}
<div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(210px, 1fr)); gap:16px; margin-bottom:20px;">
    @foreach($tuiles as [$libelle, $valeur, $detail, $lien])
        <div style="{{ $carte }} padding:20px 22px;">
            <p style="font-size:12.5px; color:#6b7386;">{{ $libelle }}</p>
            <p style="font-size:32px; font-weight:600; color:#121829; line-height:1.15; margin-top:8px;">{{ $valeur }}</p>
            @if($lien)
                <a href="{{ $lien }}" style="display:inline-block; font-size:12.5px; color:#c94f31; margin-top:6px; text-decoration:none;">{{ $detail }} →</a>
            @else
                <p style="font-size:12.5px; color:#6b7386; margin-top:6px;">{{ $detail }}</p>
            @endif
        </div>
    @endforeach
</div>

<div style="display:grid; grid-template-columns:minmax(0, 2fr) minmax(280px, 1fr); gap:20px;" class="max-xl:!grid-cols-1">

    {{-- ═════════ VISITEURS PAR JOUR ═════════ --}}
    <section style="{{ $carte }} padding:22px 24px;" x-data="{ tableau: false }">
        <div style="display:flex; align-items:flex-start; justify-content:space-between; gap:12px; flex-wrap:wrap;">
            <div>
                <h2 style="{{ $titre }}">Visiteurs uniques par jour</h2>
                <p style="font-size:12.5px; color:#6b7386; margin-top:2px;">{{ $nombre($indicateurs['visiteurs']) }} visites sur {{ $jours }} jours · une visite par adresse IP et par jour</p>
            </div>
            <button type="button" @click="tableau = ! tableau" :aria-pressed="tableau.toString()"
                    style="font-size:12px; color:#121829; background:#f4f5f8; border:1px solid #e5e7eb; border-radius:8px; padding:6px 12px; cursor:pointer;"
                    x-text="tableau ? 'Voir le graphique' : 'Voir le tableau'">Voir le tableau</button>
        </div>

        <div x-show="! tableau" style="position:relative; margin-top:16px;" x-data="infobulle()">
            <svg viewBox="0 0 {{ $largeur }} {{ $hauteur }}" style="width:100%; height:auto; display:block; overflow:visible;"
                 role="img" aria-label="Histogramme des visiteurs uniques par jour sur {{ $jours }} jours, maximum {{ $max }}"
                 @mouseleave="masquer()">
                {{-- Grille et graduations --}}
                @for($v = 0; $v <= $plafond; $v += $pas)
                    <line x1="{{ $marge['gauche'] }}" x2="{{ $largeur - $marge['droite'] }}" y1="{{ $y($v) }}" y2="{{ $y($v) }}"
                          stroke="{{ $v === 0 ? '#c9cdd6' : '#eceef3' }}" stroke-width="1"/>
                    <text x="{{ $marge['gauche'] - 8 }}" y="{{ $y($v) + 4 }}" text-anchor="end" font-size="11" fill="#6b7386">{{ $v }}</text>
                @endfor

                @foreach($serie as $i => $point)
                    @php $x = $marge['gauche'] + $i * $colonne + ($colonne - $barre) / 2; @endphp
                    @if($point['valeur'] > 0)
                        @php $h = max(2, $y(0) - $y($point['valeur'])); $r = min(4, $barre / 2, $h); @endphp
                        {{-- Barre arrondie en haut, ancrée à la ligne de base --}}
                        <path d="M{{ $x }},{{ $y(0) }} V{{ $y(0) - $h + $r }} Q{{ $x }},{{ $y(0) - $h }} {{ $x + $r }},{{ $y(0) - $h }} H{{ $x + $barre - $r }} Q{{ $x + $barre }},{{ $y(0) - $h }} {{ $x + $barre }},{{ $y(0) - $h + $r }} V{{ $y(0) }} Z"
                              fill="#e0603f" :opacity="actif === null || actif === {{ $i }} ? 1 : 0.45"/>
                    @endif
                    {{-- Zone de survol plus large que la barre --}}
                    <rect x="{{ $marge['gauche'] + $i * $colonne }}" y="{{ $marge['haut'] }}" width="{{ $colonne }}" height="{{ $zoneH }}" fill="transparent"
                          @mouseenter="montrer({{ $i }}, @js($point['libelle']), {{ $point['valeur'] }}, $event)"/>
                    @if($i % 5 === 0 || $loop->last)
                        <text x="{{ $marge['gauche'] + $i * $colonne + $colonne / 2 }}" y="{{ $hauteur - 8 }}" text-anchor="middle" font-size="11" fill="#6b7386">{{ $point['libelle'] }}</text>
                    @endif
                @endforeach
            </svg>

            <div x-show="actif !== null" x-cloak role="status"
                 :style="`position:absolute; left:${gauche}px; top:-6px; transform:translateX(-50%); pointer-events:none;`"
                 style="background:#121829; color:white; font-size:12px; border-radius:8px; padding:6px 10px; white-space:nowrap; box-shadow:0 6px 16px rgba(0,0,0,.15);">
                <span x-text="date"></span> · <strong x-text="valeur"></strong> <span x-text="valeur > 1 ? 'visiteurs' : 'visiteur'"></span>
            </div>
        </div>

        <div x-show="tableau" x-cloak style="margin-top:14px; max-height:260px; overflow:auto;">
            <table style="width:100%; border-collapse:collapse; font-size:13px;">
                <thead><tr style="text-align:left; color:#6b7386;"><th style="padding:6px 8px; font-weight:500;">Jour</th><th style="padding:6px 8px; font-weight:500; text-align:right;">Visiteurs uniques</th></tr></thead>
                <tbody>
                    @foreach($serie->reverse() as $point)
                        <tr style="border-top:1px solid #f1f2f6;"><td style="padding:6px 8px;">{{ $point['libelle'] }}</td><td style="padding:6px 8px; text-align:right;">{{ $point['valeur'] }}</td></tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>

    {{-- ═════════ À TRAITER ═════════ --}}
    <section style="{{ $carte }} padding:22px 24px;">
        <h2 style="{{ $titre }}">À traiter</h2>

        <a href="{{ route('admin.contributions.index') }}" style="display:flex; align-items:center; justify-content:space-between; margin-top:14px; padding:12px 14px; border-radius:12px; text-decoration:none;
                  {{ $indicateurs['contributions'] ? 'background:#fdf0ec; color:#c94f31;' : 'background:#f4f5f8; color:#6b7386;' }}">
            <span style="font-size:13.5px; font-weight:500;">Contributions en attente</span>
            <span style="font-size:18px; font-weight:600;">{{ $indicateurs['contributions'] }}</span>
        </a>
        @foreach($contributions as $contribution)
            <p style="font-size:12.5px; color:#374151; margin-top:8px; padding-left:4px;">
                « {{ \Illuminate\Support\Str::limit($contribution->titre, 38) }} » <span style="color:#9ca3af;">par {{ $contribution->user?->name }}</span>
            </p>
        @endforeach

        <p style="font-size:12.5px; font-weight:600; color:#6b7386; text-transform:uppercase; letter-spacing:.06em; margin-top:20px;">Catalogue à compléter</p>
        @foreach(['image' => 'Livres sans couverture', 'pdf' => 'Livres sans PDF', 'categorie' => 'Livres sans catégorie'] as $cle => $libelle)
            <a href="{{ route('admin.livres.index', ['manque' => $cle]) }}"
               style="display:flex; justify-content:space-between; padding:10px 4px; border-bottom:1px solid #f1f2f6; font-size:13.5px; text-decoration:none; color:#121829;">
                <span>{{ $libelle }}</span>
                <span style="font-weight:600; color:{{ $aCompleter[$cle] ? '#c94f31' : '#16a34a' }};">{{ $aCompleter[$cle] ?: '✓' }}</span>
            </a>
        @endforeach
    </section>
</div>

<div style="display:grid; grid-template-columns:repeat(auto-fit, minmax(300px, 1fr)); gap:20px; margin-top:20px;">

    {{-- ═════════ LES PLUS LUS ═════════ --}}
    <section style="{{ $carte }} padding:22px 24px;">
        <h2 style="{{ $titre }}">Les plus lus</h2>
        @forelse($plusLus as $livre)
            <div style="display:flex; align-items:center; gap:12px; padding:10px 0; border-bottom:1px solid #f1f2f6;">
                <span style="width:22px; font-size:13px; font-weight:600; color:#9ca3af;">{{ $loop->iteration }}</span>
                <span style="width:34px; height:50px; border-radius:4px; overflow:hidden; background:#e5e7eb; flex:none;">
                    @if($livre->image)<img src="{{ asset('storage/' . $livre->image) }}" alt="" style="width:100%; height:100%; object-fit:cover;">@endif
                </span>
                <div style="min-width:0; flex:1;">
                    <a href="{{ route('admin.livres.edit', $livre) }}" style="display:block; font-size:13.5px; font-weight:500; color:#121829; text-decoration:none; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ $livre->titre }}</a>
                    <p style="font-size:12px; color:#6b7386;">{{ $livre->auteur }}</p>
                </div>
                <span style="font-size:13px; font-weight:600; color:#121829;">{{ $livre->lectures_count }}</span>
            </div>
        @empty
            <p style="font-size:13px; color:#9ca3af; margin-top:12px;">Aucune lecture pour le moment.</p>
        @endforelse
    </section>

    {{-- ═════════ DERNIÈRES LECTURES ═════════ --}}
    <section style="{{ $carte }} padding:22px 24px;">
        <div style="display:flex; justify-content:space-between; align-items:baseline;">
            <h2 style="{{ $titre }}">Dernières lectures</h2>
            <a href="{{ route('admin.lectures.index') }}" style="font-size:12.5px; color:#c94f31; text-decoration:none;">Tout voir →</a>
        </div>
        @forelse($dernieresLectures as $lecture)
            <div style="padding:10px 0; border-bottom:1px solid #f1f2f6;">
                <p style="font-size:13.5px; color:#121829;"><strong style="font-weight:500;">{{ $lecture->user?->name }}</strong> <span style="color:#6b7386;">a ouvert</span> {{ \Illuminate\Support\Str::limit($lecture->livre?->titre, 34) }}</p>
                <p style="font-size:12px; color:#9ca3af;">{{ $lecture->read_at?->locale('fr')->diffForHumans() }}{{ $lecture->progression ? ' · ' . $lecture->progression . ' %' : '' }}</p>
            </div>
        @empty
            <p style="font-size:13px; color:#9ca3af; margin-top:12px;">Aucune lecture pour le moment.</p>
        @endforelse
    </section>

    {{-- ═════════ DERNIERS INSCRITS ═════════ --}}
    <section style="{{ $carte }} padding:22px 24px;">
        <h2 style="{{ $titre }}">Derniers inscrits</h2>
        @foreach($derniersInscrits as $user)
            <div style="display:flex; align-items:center; gap:10px; padding:9px 0; border-bottom:1px solid #f1f2f6;">
                @if($user->avatar)
                    <img src="{{ asset('storage/' . $user->avatar) }}" alt="" style="width:32px; height:32px; border-radius:50%; object-fit:cover;">
                @else
                    <span style="width:32px; height:32px; border-radius:50%; background:#fdf0ec; color:#c94f31; display:flex; align-items:center; justify-content:center; font-size:12px; font-weight:600; flex:none;">{{ mb_strtoupper(mb_substr($user->name, 0, 1)) }}</span>
                @endif
                <div style="min-width:0; flex:1;">
                    <p style="font-size:13.5px; color:#121829; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                        {{ $user->name }}
                        @if($user->is_admin)<span style="font-size:10.5px; color:#c94f31; background:#fdf0ec; border-radius:6px; padding:1px 6px; margin-left:4px;">admin</span>@endif
                    </p>
                    <p style="font-size:12px; color:#9ca3af; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ $user->email }}</p>
                </div>
                <span title="{{ $user->hasVerifiedEmail() ? 'Email vérifié' : 'Email non vérifié' }}" style="font-size:11.5px; color:{{ $user->hasVerifiedEmail() ? '#16a34a' : '#9ca3af' }};">
                    {{ $user->hasVerifiedEmail() ? '✓ vérifié' : 'non vérifié' }}
                </span>
            </div>
        @endforeach
    </section>
</div>
@endsection

@push('scripts')
<script>
    function infobulle() {
        return {
            actif: null, date: '', valeur: 0, gauche: 0,
            montrer(i, date, valeur, e) {
                const zone = e.target.getBoundingClientRect();
                const parent = this.$root.getBoundingClientRect();
                this.actif = i; this.date = date; this.valeur = valeur;
                this.gauche = zone.left - parent.left + zone.width / 2;
            },
            masquer() { this.actif = null; },
        };
    }
</script>
@endpush
