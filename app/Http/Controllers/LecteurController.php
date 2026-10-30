<?php

namespace App\Http\Controllers;

use App\Models\Lecture;
use App\Models\Livre;
use Illuminate\Http\Request;

class LecteurController extends Controller
{
    /**
     * Lecteur PDF intégré, repris à la dernière page lue.
     */
    public function show(Request $request, Livre $livre)
    {
        if (! $livre->pdf_path) {
            return to_route('livres.show', $livre)->with('error', 'Ce livre n\'est pas encore disponible en lecture.');
        }

        $lecture = $this->lecture($request, $livre);
        $lecture->update(['read_at' => now()]);

        return view('lecteur.show', ['livre' => $livre, 'lecture' => $lecture]);
    }

    /**
     * Enregistre la page atteinte (appelé par le lecteur pendant la lecture).
     */
    public function progression(Request $request, Livre $livre)
    {
        $data = $request->validate([
            'page' => 'required|integer|min:1',
            'total' => 'required|integer|min:1',
        ]);

        $lecture = $this->lecture($request, $livre);
        $lecture->avancer($data['page'], $data['total']);

        return response()->json([
            'progression' => $lecture->progression,
            'termine' => $lecture->estTerminee(),
        ]);
    }

    /**
     * Ajoute ou retire un signet sur une page.
     */
    public function signet(Request $request, Livre $livre)
    {
        $page = (int) $request->validate(['page' => 'required|integer|min:1'])['page'];

        $lecture = $this->lecture($request, $livre);
        $signets = collect($lecture->signets ?? []);

        $signets = $signets->contains($page)
            ? $signets->reject(fn ($p) => $p === $page)
            : $signets->push($page);

        $lecture->update(['signets' => $signets->unique()->sort()->values()->all()]);

        return response()->json(['signets' => $lecture->signets]);
    }

    private function lecture(Request $request, Livre $livre): Lecture
    {
        return Lecture::firstOrCreate(
            ['user_id' => $request->user()->id, 'livre_id' => $livre->id],
            ['read_at' => now()]
        );
    }
}
