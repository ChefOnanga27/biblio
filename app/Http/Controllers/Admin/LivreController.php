<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Livre;
use Illuminate\Http\Request;

class LivreController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        return view('admin.livres.index', [ 
            'livres' => Livre:: OrderBy('created_at', 'desc')->paginate(30)
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //Creer un nouveau livre vide pour le formulaire
        return view('admin.livres.form', [
            'livre' => new Livre(),
            'categories' => \App\Models\Categorie::orderBy('nom')->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
    $data = $request->validate([
        'titre' => 'required|string|max:255',
        'auteur' => 'required|string|max:255',
        'isbn' => 'required|string|max:50',
        'description' => 'nullable|string',
        'date_publication' => 'nullable|date',
        'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        'pdf' => 'nullable|mimes:pdf|max:512000',
        'categorie_id' => 'required|exists:categories,id',
    ]);

    // Checkbox (important)
    $data['disponible'] = $request->has('disponible');

    if ($request->hasFile('pdf')) {
        $data['pdf_path'] = $request->file('pdf')->store('livres/pdfs', 'public');
    }


    // Upload image
    if ($request->hasFile('image')) {
        $data['image'] = $request->file('image')->store('livres', 'public');
    }

    Livre::create($data);

    return to_route('admin.livres.index')
        ->with('success', 'Livre créé avec succès');
}
    /**
     * Display the specified resource.
     */
    public function show(livre $livre)
    {
        //
        return view('admin.livres.show', [
            'livre' => $livre
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(livre $livre)
    {
        return view('admin.livres.form', [
            'livre' => $livre,
            'categories' => \App\Models\Categorie::orderBy('nom')->get(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, livre $livre)
    {
        $data = $request->validate([
            'titre' => 'required|string|max:255',
            'auteur' => 'required|string|max:255',
            'isbn' => 'required|string|max:50|unique:livres,isbn,'.$livre->id,
            'description' => 'nullable|string',
            'date_publication' => 'nullable|date',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        'pdf' => 'nullable|mimes:pdf|max:512000',
        'categorie_id' => 'required|exists:categories,id',
    ]);

        // Checkbox (important)
        $data['disponible'] = $request->has('disponible');

        if ($request->hasFile('pdf')) {
            $data['pdf_path'] = $request->file('pdf')->store('livres/pdfs', 'public');
        }
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('livres', 'public');
        }

        $livre->update($data);

        return to_route('admin.livres.index')
            ->with('success', 'Livre mis à jour avec succès');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(livre $livre)
    {
        $livre->delete();
        return to_route('admin.livres.index')
            ->with('success', 'Livre supprimé avec succès');

    }
}
