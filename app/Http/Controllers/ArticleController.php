<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    public function index()
    {
        $articles = Article::published()->paginate(12);
        return view('article.index', compact('articles'));
    }

    public function show($slug)
    {
        $article = Article::where('slug', $slug)->firstOrFail();

        // Vérifier que l'article est publié
        if (! $article->published_at || $article->published_at > now()) {
            abort(404);
        }

        // Autres articles publiés (ordre de publication)
        $otherArticles = Article::published()
            ->where('id', '!=', $article->id)
            ->limit(5)
            ->get();

        return view('article.show', compact('article', 'otherArticles'));
    }
}
