<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\LivreFormRequest;
use App\Models\Auteur;
use App\Models\Categorie;
use App\Models\Genre;
use App\Models\Livre;
use App\Services\Images;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LivreController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Filtre « à compléter » : livres sans couverture, sans PDF ou sans catégorie
        $colonnes = ['image' => 'image', 'pdf' => 'pdf_path', 'categorie' => 'categorie_id'];
        $manque = array_key_exists($request->manque, $colonnes) ? $request->manque : null;

        return view('admin.livres.index', [
            'livres' => Livre::when($manque, fn ($q) => $q->whereNull($colonnes[$manque]))
                ->orderBy('created_at', 'desc')
                ->paginate(30)
                ->withQueryString(),
            'manque' => $manque,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //Creer un nouveau livre vide pour le formulaire
        return view('admin.livres.form', $this->formData(new Livre()));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(LivreFormRequest $request)
    {
        $livre = new Livre();
        $livre->fill($this->validatedData($request, $livre))->save();

        return to_route('admin.livres.index')
            ->with('success', 'Livre créé avec succès');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Livre $livre)
    {
        return view('admin.livres.form', $this->formData($livre));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(LivreFormRequest $request, Livre $livre)
    {
        $livre->update($this->validatedData($request, $livre));

        return to_route('admin.livres.index')
            ->with('success', 'Livre mis à jour avec succès');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Livre $livre)
    {
        $livre->delete();
        Storage::disk('public')->delete(array_filter([$livre->image, $livre->pdf_path]));

        return to_route('admin.livres.index')
            ->with('success', 'Livre supprimé avec succès');
    }

    private function formData(Livre $livre): array
    {
        return [
            'livre' => $livre,
            'categories' => Categorie::orderBy('nom')->get(),
            'genres' => Genre::orderBy('nom')->get(),
        ];
    }

    /**
     * Prépare les données validées : auteur lié, disponibilité, fichiers uploadés
     * (l'ancien fichier est supprimé lorsqu'il est remplacé).
     */
    private function validatedData(LivreFormRequest $request, Livre $livre): array
    {
        $data = $request->safe()->except(['image', 'pdf', 'supprimer_image']);

        $data['auteur'] = trim($data['auteur']);
        $data['auteur_id'] = Auteur::firstOrCreate(['nom' => $data['auteur']])->id;
        $data['disponible'] = $request->boolean('disponible');

        if ($request->hasFile('image')) {
            $data['image'] = Images::remplacer($livre->image, $request->file('image'), 'livres', 'couverture');
        } elseif ($request->boolean('supprimer_image') && $livre->image) {
            Images::supprimer($livre->image);
            $data['image'] = null;
        }

        if ($request->hasFile('pdf')) {
            if ($livre->pdf_path) {
                Storage::disk('public')->delete($livre->pdf_path);
            }
            $data['pdf_path'] = $request->file('pdf')->store('livres/pdfs', 'public');
        }

        return $data;
    }
}
