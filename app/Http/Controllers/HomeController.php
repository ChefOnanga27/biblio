<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Categorie;
use App\Models\Livre;
use App\Models\Visitor;

class HomeController extends Controller
{
    private const PAR_CARROUSEL = 12;

    /** « Citation du jour » (texte, auteur, source). */
    private const CITATIONS = [
        ['En Afrique, quand un vieillard meurt, c\'est une bibliothèque qui brûle.', 'Amadou Hampâté Bâ', 'UNESCO, 1960'],
        ['La lecture de tous les bons livres est comme une conversation avec les plus honnêtes gens des siècles passés.', 'René Descartes', 'Discours de la méthode'],
        ['Chaque lecteur est, quand il lit, le propre lecteur de soi-même.', 'Marcel Proust', 'Le Temps retrouvé'],
        ['Je n\'ai jamais eu de chagrin qu\'une heure de lecture ne m\'ait ôté.', 'Montesquieu', 'Mes pensées'],
    ];

    public function index(Request $request)
    {
        $user = $request->user();
        $relations = ['categorie', 'genre'];

        $nouveautes = Livre::with($relations)->latest()->take(self::PAR_CARROUSEL)->get();
        $populaires = Livre::with($relations)->popular()->has('lectures')->take(self::PAR_CARROUSEL)->get();
        $tendances = Livre::with($relations)->trending()->take(self::PAR_CARROUSEL)->get();

        $recommandes = $user
            ? Livre::with($relations)->recommandesPour($user)->take(self::PAR_CARROUSEL)->get()
            : collect();

        $lecturesRecentes = $user
            ? $user->lectures()->with(['livre.categorie', 'livre.genre'])->latest('read_at')->take(self::PAR_CARROUSEL)->get()->pluck('livre')
            : collect();

        // Un carrousel par catégorie (les 3 plus fournies)
        $rangeesCategories = Categorie::has('livres')->withCount('livres')
            ->orderByDesc('livres_count')->take(3)->get()
            ->map(fn ($categorie) => [
                'categorie' => $categorie,
                'livres' => $categorie->livres()->with($relations)->latest()->take(self::PAR_CARROUSEL)->get(),
            ]);

        $this->enregistrerVisite($request);

        return view('home', [
            'citations' => self::CITATIONS,
            'nouveautes' => $nouveautes,
            'recommandes' => $recommandes,
            'lecturesRecentes' => $lecturesRecentes,
            'tendances' => $tendances,
            'populaires' => $populaires,
            'rangeesCategories' => $rangeesCategories,
        ]);
    }

    /**
     * Enregistrement des visiteurs (une ligne par IP et par jour).
     */
    private function enregistrerVisite(Request $request): void
    {
        $dejaVenu = Visitor::where('ip', $request->ip())
            ->whereDate('visited_at', today())
            ->exists();

        if (! $dejaVenu) {
            Visitor::create([
                'ip' => $request->ip(),
                'visited_at' => today(),
                'user_agent' => mb_substr((string) $request->userAgent(), 0, 255),
            ]);
        }
    }
}
