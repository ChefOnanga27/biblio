<?php

namespace App\Http\Controllers;

use App\Models\Livre;
use Illuminate\Http\Request;

class EspaceController extends Controller
{
    public const ONGLETS = [
        'tous' => 'Tous les livres',
        'en-cours' => 'En cours',
        'termines' => 'Terminés',
        'favoris' => 'Favoris',
    ];

    /**
     * Tableau de bord : reprise de lecture, chiffres et sélections.
     */
    public function dashboard(Request $request)
    {
        $user = $request->user();
        $lectures = $user->lectures()->with('livre.categorie')->latest('read_at')->get();

        $enCours = $lectures->filter->estEnCours()->values();
        $favoris = $user->favoris()->latest('favoris.created_at')->take(12)->get();

        return view('espace.dashboard', [
            'reprise' => $enCours->first(),
            'enCours' => $enCours->take(12),
            'favoris' => $favoris,
            'recommandes' => Livre::recommandesPour($user)->take(12)->get(),
            'stats' => [
                'En cours' => $enCours->count(),
                'Terminés' => $lectures->filter->estTerminee()->count(),
                'Favoris' => $user->favoris()->count(),
                'Consultés' => $lectures->count(),
            ],
        ]);
    }

    /**
     * Ma bibliothèque : livres lus ou mis en favoris, par onglet.
     */
    public function bibliotheque(Request $request)
    {
        $user = $request->user();
        $onglet = array_key_exists($request->onglet, self::ONGLETS) ? $request->onglet : 'tous';

        $lectures = $user->lectures()->get()->keyBy('livre_id');
        $favoris = $user->favoris()->get()
            ->mapWithKeys(fn ($livre) => [$livre->id => $livre->pivot->created_at]);

        // Dernière activité : lecture ou ajout aux favoris
        $activite = fn (Livre $livre) => max(
            optional($lectures->get($livre->id))->read_at?->timestamp ?? 0,
            optional($favoris->get($livre->id))->timestamp ?? 0,
        );

        $livres = Livre::with('categorie')
            ->whereIn('id', $lectures->keys()->merge($favoris->keys())->unique())
            ->get()
            ->sortByDesc($activite)
            ->values();

        $filtres = [
            'tous' => fn () => true,
            'en-cours' => fn ($livre) => $lectures->get($livre->id)?->estEnCours(),
            'termines' => fn ($livre) => $lectures->get($livre->id)?->estTerminee(),
            'favoris' => fn ($livre) => $favoris->has($livre->id),
        ];

        return view('espace.bibliotheque', [
            'onglet' => $onglet,
            'onglets' => self::ONGLETS,
            'compteurs' => collect($filtres)->map(fn ($filtre) => $livres->filter($filtre)->count()),
            'livres' => $livres->filter($filtres[$onglet])->values(),
            'lectures' => $lectures,
            'favorisIds' => $favoris->keys()->all(),
            'favorisDates' => $favoris,
        ]);
    }

    /**
     * Historique de lecture, du plus récent au plus ancien.
     */
    public function historique(Request $request)
    {
        $lectures = $request->user()->lectures()
            ->with('livre.categorie')
            ->latest('read_at')
            ->paginate(30);

        return view('espace.historique', ['lectures' => $lectures]);
    }
}
