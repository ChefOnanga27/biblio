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

    public function show(Categorie $categorie)
    {
        $livres = $categorie->livres()->orderBy('created_at', 'desc')->paginate(20);

        return view('categories.show', compact('categorie', 'livres'));
    }
}
