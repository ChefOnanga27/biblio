@extends('admin.admin')

@section('title', 'Contributions')
@section('page-title', 'Contributions')
@section('page-subtitle', 'Livres proposés par les lecteurs, à valider avant publication')

@section('content')
@php
    $couleurs = [
        'en_attente' => 'background:#fdf0ec; color:#c2410c;',
        'acceptee' => 'background:#ecfdf5; color:#047857;',
        'refusee' => 'background:#fef2f2; color:#b91c1c;',
    ];
@endphp

@if(session('success'))
    <div style="margin-bottom:18px; padding:12px 16px; border-radius:10px; background:#ecfdf5; color:#065f46; font-size:13px;">{{ session('success') }}</div>
@endif

<div style="background:white; border:1px solid #f0ede8; border-radius:16px; overflow:hidden;">
    @forelse($contributions as $contribution)
        <div style="padding:20px 24px; border-bottom:1px solid #f0ede8; display:flex; gap:20px; align-items:flex-start; flex-wrap:wrap;"
             x-data="{ refus: false }">
            <div style="flex:1; min-width:260px;">
                <div style="display:flex; align-items:center; gap:10px; flex-wrap:wrap;">
                    <p style="font-size:15px; font-weight:600; color:#121829;">{{ $contribution->titre }}</p>
                    <span style="font-size:11px; font-weight:600; border-radius:999px; padding:2px 10px; {{ $couleurs[$contribution->statut] ?? '' }}">
                        {{ $contribution->libelleStatut() }}
                    </span>
                </div>
                <p style="font-size:13px; color:#6b7280; margin-top:2px;">
                    {{ $contribution->auteur }}
                    @if($contribution->categorie) · {{ $contribution->categorie->nom }} @endif
                    @if($contribution->langue) · {{ $contribution->langue }} @endif
                    @if($contribution->isbn) · ISBN {{ $contribution->isbn }} @endif
                </p>
                <p style="font-size:12px; color:#9ca3af; margin-top:4px;">
                    Proposé par {{ $contribution->user?->name ?? 'compte supprimé' }} ({{ $contribution->user?->email }}) le {{ $contribution->created_at->format('d/m/Y à H:i') }}
                </p>
                @if($contribution->description)
                    <p style="font-size:13px; color:#374151; margin-top:10px; line-height:1.6;">{{ \Illuminate\Support\Str::limit($contribution->description, 400) }}</p>
                @endif
                @if($contribution->statut === 'refusee' && $contribution->motif_refus)
                    <p style="font-size:12.5px; color:#b91c1c; margin-top:8px;">Motif du refus : {{ $contribution->motif_refus }}</p>
                @endif
            </div>

            <div style="display:flex; flex-direction:column; gap:8px; align-items:flex-end;">
                @if($contribution->statut !== 'refusee')
                    <a href="{{ media_url($contribution->pdf_path) }}" target="_blank" rel="noopener"
                       style="font-size:12.5px; color:#121829; text-decoration:underline;">Ouvrir le PDF</a>
                @endif

                @if($contribution->statut === 'en_attente')
                    <div style="display:flex; gap:8px;">
                        <form method="POST" action="{{ route('admin.contributions.accepter', $contribution) }}">
                            @csrf
                            <button type="submit" style="background:#121829; color:white; border:0; border-radius:9px; padding:8px 16px; font-size:12.5px; font-weight:600; cursor:pointer;">Accepter</button>
                        </form>
                        <button type="button" @click="refus = ! refus" style="background:white; color:#b91c1c; border:1px solid #fecaca; border-radius:9px; padding:8px 16px; font-size:12.5px; font-weight:600; cursor:pointer;">Refuser</button>
                    </div>
                    <form method="POST" action="{{ route('admin.contributions.refuser', $contribution) }}" x-show="refus" x-cloak style="display:flex; flex-direction:column; gap:8px; width:300px;">
                        @csrf
                        <label for="motif-{{ $contribution->id }}" style="font-size:12px; color:#6b7280;">Motif (visible par le lecteur)</label>
                        <textarea id="motif-{{ $contribution->id }}" name="motif_refus" rows="3" required
                                  style="border:1px solid #e5e7eb; border-radius:9px; padding:8px 10px; font-size:13px;"></textarea>
                        <button type="submit" style="background:#b91c1c; color:white; border:0; border-radius:9px; padding:8px 16px; font-size:12.5px; font-weight:600; cursor:pointer;">Confirmer le refus</button>
                    </form>
                @elseif($contribution->livre)
                    <a href="{{ route('admin.livres.edit', $contribution->livre) }}" style="font-size:12.5px; color:#e0603f;">Voir la fiche du livre →</a>
                @endif
            </div>
        </div>
    @empty
        <p style="padding:48px; text-align:center; font-size:14px; color:#9ca3af;">Aucune contribution pour le moment.</p>
    @endforelse
</div>

<div style="margin-top:20px;">{{ $contributions->links() }}</div>
@endsection
