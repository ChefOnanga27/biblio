<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\Request;
use App\Services\Images;
use Illuminate\Support\Str;

class ArticleController extends Controller
{
    public function index()
    {
        $articles = Article::orderBy('created_at', 'desc')->paginate(20);
        return view('admin.articles.index', compact('articles'));
    }

    public function create()
    {
        return view('admin.articles.form', ['article' => new Article()]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'required|string|max:500',
            'contenu' => 'required|string',
            'image' => 'required|image|mimes:jpg,jpeg,png,webp|max:5120',
            'published_at' => 'nullable|date_format:Y-m-d\TH:i',
        ]);

        $data['slug'] = $this->uniqueSlug($data['titre']);
        $data['user_id'] = auth()->id();

        if ($request->hasFile('image')) {
            $data['image'] = Images::enregistrer($request->file('image'), 'articles', 'illustration');
        }

        Article::create($data);

        return to_route('admin.articles.index')
            ->with('success', 'Article créé avec succès');
    }

    public function edit(Article $article)
    {
        return view('admin.articles.form', compact('article'));
    }

    public function update(Request $request, Article $article)
    {
        $data = $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'required|string|max:500',
            'contenu' => 'required|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
            'published_at' => 'nullable|date_format:Y-m-d\TH:i',
        ]);

        // Le slug (donc l'URL) ne change que si le titre change
        if ($data['titre'] !== $article->titre) {
            $data['slug'] = $this->uniqueSlug($data['titre'], $article->id);
        }

        if ($request->hasFile('image')) {
            $data['image'] = Images::remplacer($article->image, $request->file('image'), 'articles', 'illustration');
        }

        $article->update($data);

        return to_route('admin.articles.index')
            ->with('success', 'Article mis à jour avec succès');
    }

    public function destroy(Article $article)
    {
        $article->delete();

        Images::supprimer($article->image);

        return to_route('admin.articles.index')
            ->with('success', 'Article supprimé avec succès');
    }

    /**
     * Génère un slug unique : "mon-titre", puis "mon-titre-2", "mon-titre-3"…
     */
    private function uniqueSlug(string $titre, ?int $ignoreId = null): string
    {
        $base = Str::slug($titre) ?: 'article';
        $slug = $base;
        $i = 2;

        while (Article::where('slug', $slug)->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))->exists()) {
            $slug = $base . '-' . $i++;
        }

        return $slug;
    }
}
