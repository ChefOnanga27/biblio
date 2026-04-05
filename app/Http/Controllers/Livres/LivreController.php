<?php

namespace App\Http\Controllers\Livres;

use App\Http\Controllers\Controller;
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

        if ($request->filled('search')) {
            $query->where('titre', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('categorie')) {
            $query->where('categorie_id', $request->categorie);
        }

        if ($request->filled('genre')) {
            $query->where('genre_id', $request->genre);
        }

        if ($request->filled('auteur')) {
            $query->where('auteur_id', $request->auteur);
        }

        if ($request->filled('annee')) {
            $query->whereYear('annee_publication', $request->annee);
        }

        // 👇 seulement 4 livres pour la page d'accueil
        $livres = $query->latest()->take(4)->get();

        return view('home', compact('livres'));
    }
}