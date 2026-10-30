<?php

namespace App\Http\Controllers;

use App\Models\Livre;
use Illuminate\Http\Request;

class FavoriController extends Controller
{
    /**
     * Ajoute le livre aux favoris (« Ma bibliothèque »), ou l'en retire s'il y est déjà.
     */
    public function toggle(Request $request, Livre $livre)
    {
        $resultat = $request->user()->favoris()->toggle($livre->id);
        $ajoute = ! empty($resultat['attached']);
        $message = $ajoute ? 'Livre ajouté à vos favoris.' : 'Livre retiré de vos favoris.';

        if ($request->expectsJson()) {
            return response()->json(['favori' => $ajoute, 'message' => $message]);
        }

        return back()->with('success', $message);
    }
}
