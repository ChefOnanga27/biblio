<?php

namespace App\Http\Controllers;

use App\Models\Livre;
use App\Models\Categorie;
use App\Models\Genre;
use App\Models\Auteur;
use Illuminate\Http\Request;

class LivreController extends Controller
{
    public const TRIS = [
        'pertinence' => 'Pertinence',
        'populaires' => 'Popularité',
        'nouveautes' => 'Nouveautés',
        'notes' => 'Mieux notés',
        'titre' => 'Ordre alphabétique',
    ];

    public function index(Request $request)
    {
        $query = Livre::query()->with(['categorie', 'genre'])->withCount('lectures')->avecNotes();

        // 🔎 Recherche par titre, auteur, ISBN ou mot-clé de la description
        $recherche = trim((string) $request->input('search'));
        if ($recherche !== '') {
            $like = '%' . $recherche . '%';
            $query->where(fn ($q) => $q->where('titre', 'like', $like)
                ->orWhere('auteur', 'like', $like)
                ->orWhere('isbn', 'like', $like)
                ->orWhere('description', 'like', $like));
        }

        // 📂 Filtre catégorie
        if ($request->filled('categorie')) {
            $query->where('categorie_id', $request->categorie);
        }

        // 🎭 Filtre genre
        if ($request->filled('genre')) {
            $query->where('genre_id', $request->genre);
        }

        // 👤 Filtre auteur
        if ($request->filled('auteur')) {
            $query->where('auteur_id', $request->auteur);
        }

        // 🌍 Filtre langue
        if ($request->filled('langue')) {
            $query->where('langue', $request->langue);
        }

        // 📅 Filtre année publication
        if ($request->filled('annee')) {
            $query->whereYear('date_publication', $request->annee);
        }

        // ↕️ Tri (pertinence par défaut quand on cherche, nouveautés sinon)
        $tri = array_key_exists($request->tri, self::TRIS) ? $request->tri : ($recherche !== '' ? 'pertinence' : 'nouveautes');

        match ($tri) {
            'pertinence' => $recherche !== ''
                // Les titres qui commencent par la recherche d'abord, puis les plus lus
                ? $query->orderByRaw('CASE WHEN titre LIKE ? THEN 0 ELSE 1 END', [$recherche . '%'])
                        ->orderByDesc('lectures_count')
                : $query->orderByDesc('lectures_count')->latest(),
            'populaires' => $query->orderByDesc('lectures_count')->latest(),
            'notes' => $query->orderByDesc('notes_avg_note')->orderByDesc('notes_count'),
            'titre' => $query->orderBy('titre'),
            default => $query->latest(),
        };

        $livres = $query->paginate(24)->withQueryString();

        // Données pour la barre latérale et les filtres
        $categories = Categorie::withCount('livres')->orderBy('nom')->get();
        $genres = Genre::whereHas('livres')->orderBy('nom')->get();
        $auteurs = Auteur::whereHas('livres')->orderBy('nom')->get();
        $langues = Livre::whereNotNull('langue')->distinct()->orderBy('langue')->pluck('langue');

        return view('livre.index', [
            'livres' => $livres,
            'categories' => $categories,
            'genres' => $genres,
            'auteurs' => $auteurs,
            'langues' => $langues,
            'tri' => $tri,
            'tris' => self::TRIS,
            'totalLivres' => Livre::count(),
            'favorisIds' => $request->user()
                ? $request->user()->favoris()->whereIn('livres.id', $livres->pluck('id'))->pluck('livres.id')->all()
                : [],
        ]);
    }

    public function show(Livre $livre)
    {
        if (auth()->check()) {
            \App\Models\Lecture::updateOrCreate(
                [
                    'user_id' => auth()->id(),
                    'livre_id' => $livre->id,
                ],
                ['read_at' => now()]
            );
        }

        $livre->load(['categorie', 'genre', 'auteurRelation'])->loadCount(['lectures', 'notes'])->loadAvg('notes', 'note');

        return view('livre.show', [
            'livre' => $livre,
            'lecture' => auth()->user()->lectures()->where('livre_id', $livre->id)->first(),
            'similaires' => $this->similaires($livre),
            'estFavori' => auth()->user()->favoris()->whereKey($livre->id)->exists(),
            'maNote' => $livre->notes()->where('user_id', auth()->id())->value('note'),
        ]);
    }

    /**
     * « Vous aimerez aussi » : même catégorie, complétée par le même auteur.
     */
    private function similaires(Livre $livre)
    {
        $similaires = $livre->categorie_id
            ? Livre::with(['categorie', 'genre'])->where('categorie_id', $livre->categorie_id)->whereKeyNot($livre->id)->popular()->take(12)->get()
            : collect();

        if ($similaires->count() < 12 && $livre->auteur_id) {
            $similaires = $similaires->concat(
                Livre::with(['categorie', 'genre'])->where('auteur_id', $livre->auteur_id)
                    ->whereKeyNot($similaires->pluck('id')->push($livre->id)->all())
                    ->latest()
                    ->take(12 - $similaires->count())
                    ->get()
            );
        }

        return $similaires;
    }
}
