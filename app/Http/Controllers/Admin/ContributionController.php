<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Auteur;
use App\Models\Contribution;
use App\Models\Livre;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ContributionController extends Controller
{
    public function index()
    {
        return view('admin.contributions.index', [
            'contributions' => Contribution::with(['user', 'categorie', 'livre'])
                ->orderByRaw("CASE WHEN statut = 'en_attente' THEN 0 ELSE 1 END")
                ->latest()
                ->paginate(20),
        ]);
    }

    /**
     * Accepte la proposition : le livre est ajouté au catalogue, puis l'admin peut compléter sa fiche.
     */
    public function accepter(Contribution $contribution)
    {
        abort_unless($contribution->statut === 'en_attente', 409);

        $livre = DB::transaction(function () use ($contribution) {
            $isbn = $contribution->isbn && ! Livre::where('isbn', $contribution->isbn)->exists()
                ? $contribution->isbn
                : 'CONTRIB-' . $contribution->id;

            $livre = Livre::create([
                'titre' => $contribution->titre,
                'auteur' => $contribution->auteur,
                'auteur_id' => Auteur::firstOrCreate(['nom' => trim($contribution->auteur)])->id,
                'isbn' => $isbn,
                'description' => $contribution->description,
                'langue' => $contribution->langue,
                'categorie_id' => $contribution->categorie_id,
                'pdf_path' => $this->deplacer($contribution->pdf_path, 'livres/pdfs'),
                'image' => $contribution->image ? $this->deplacer($contribution->image, 'livres') : null,
                'disponible' => true,
            ]);

            $contribution->update(['statut' => 'acceptee', 'livre_id' => $livre->id, 'pdf_path' => $livre->pdf_path, 'image' => $livre->image]);

            return $livre;
        });

        return to_route('admin.livres.edit', $livre)
            ->with('success', 'Contribution acceptée : « ' . $livre->titre . ' » est dans le catalogue. Complétez sa fiche si besoin.');
    }

    public function refuser(Request $request, Contribution $contribution)
    {
        abort_unless($contribution->statut === 'en_attente', 409);

        $data = $request->validate(['motif_refus' => 'required|string|max:1000']);

        Storage::disk('public')->delete(array_filter([$contribution->pdf_path, $contribution->image]));
        $contribution->update(['statut' => 'refusee', 'motif_refus' => $data['motif_refus']]);

        return to_route('admin.contributions.index')->with('success', 'Contribution refusée.');
    }

    private function deplacer(string $chemin, string $dossier): string
    {
        $nouveau = $dossier . '/' . basename($chemin);
        Storage::disk('public')->move($chemin, $nouveau);

        return $nouveau;
    }
}
