<?php

namespace App\Http\Controllers;

use App\Models\Categorie;
use App\Models\Contribution;
use App\Services\Images;
use Illuminate\Http\Request;

class ContributionController extends Controller
{
    /**
     * Formulaire « Contribuer » et suivi des propositions de l'utilisateur.
     */
    public function create(Request $request)
    {
        return view('contributions.create', [
            'categories' => Categorie::orderBy('nom')->get(),
            'contributions' => $request->user()->contributions()->latest()->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'titre' => 'required|string|max:255',
            'auteur' => 'required|string|max:255',
            'isbn' => 'nullable|string|max:50',
            'description' => 'nullable|string|max:5000',
            'langue' => 'nullable|string|max:50',
            'categorie_id' => 'nullable|exists:categories,id',
            'pdf' => 'required|mimes:pdf|max:51200',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'droits' => 'accepted',
        ], [
            'droits.accepted' => 'Vous devez confirmer que ce livre peut être partagé.',
            'pdf.required' => 'Joignez le livre au format PDF.',
        ]);

        Contribution::create([
            ...collect($data)->except(['pdf', 'image', 'droits'])->all(),
            'user_id' => $request->user()->id,
            'pdf_path' => $request->file('pdf')->store('contributions/pdfs', 'public'),
            'image' => $request->hasFile('image') ? Images::enregistrer($request->file('image'), 'contributions/couvertures', 'couverture') : null,
        ]);

        return to_route('contributions.create')
            ->with('success', 'Merci ! Votre proposition a été envoyée : un administrateur va l\'examiner.');
    }
}
