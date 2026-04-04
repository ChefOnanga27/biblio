<?php

namespace App\Http\Controllers;

use App\Models\Livre;
use App\Models\Categorie;
use App\Models\Genre;
use App\Models\Auteur;
use Illuminate\Http\Request;

class LivreController extends Controller
{
    public function index(Request $request)
    {
        $query = Livre::query();

        // 🔎 Recherche par titre
        if ($request->filled('search')) {
            $query->where('titre', 'like', '%' . $request->search . '%');
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

        // 📅 Filtre année publication
        if ($request->filled('annee')) {
            $query->whereYear('annee_publication', $request->annee);
        }

        $livres = $query->latest()
                        ->paginate(20)
                        ->withQueryString();

        // Données pour les selects
        $categories = Categorie::all();
        $genres = Genre::all();
        $auteurs = Auteur::all();

        return view('livre.index', compact(
            'livres',
            'categories',
            'genres',
            'auteurs'
        ));
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

        return view('livre.show', compact('livre'));
    }
}