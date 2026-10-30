<?php

namespace App\Http\Controllers;

use App\Models\Categorie;
use Illuminate\Http\Request;

class CategorieController extends Controller
{
    public function index()
    {
        $categories = Categorie::withCount('livres')->orderBy('nom')->get();

        return view('categories.index', compact('categories'));
    }

    public function show(Request $request, Categorie $categorie)
    {
        $categorie->loadCount('livres');
        $livres = $categorie->livres()->with(['categorie', 'genre'])->withCount('lectures')
            ->orderBy('created_at', 'desc')->paginate(24);

        $favorisIds = $request->user()
            ? $request->user()->favoris()->whereIn('livres.id', $livres->pluck('id'))->pluck('livres.id')->all()
            : [];

        return view('categories.show', compact('categorie', 'livres', 'favorisIds'));
    }
}
