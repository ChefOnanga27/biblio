<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\Request;
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
            'image' => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'published_at' => 'nullable|date_format:Y-m-d\TH:i',
        ]);

        $data['slug'] = Str::slug($data['titre']);
        $data['user_id'] = auth()->id();

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('articles', 'public');
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
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'published_at' => 'nullable|date_format:Y-m-d\TH:i',
        ]);

        $data['slug'] = Str::slug($data['titre']);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('articles', 'public');
        }

        $article->update($data);

        return to_route('admin.articles.index')
            ->with('success', 'Article mis à jour avec succès');
    }

    public function destroy(Article $article)
    {
        $article->delete();
        return to_route('admin.articles.index')
            ->with('success', 'Article supprimé avec succès');
    }
}
