<?php

namespace App\Http\Controllers;

use App\Models\Livre;
use App\Models\Note;
use Illuminate\Http\Request;

class NoteController extends Controller
{
    /**
     * Enregistre (ou modifie) la note du lecteur pour ce livre.
     */
    public function store(Request $request, Livre $livre)
    {
        $data = $request->validate(['note' => 'required|integer|min:1|max:5']);

        Note::updateOrCreate(
            ['user_id' => $request->user()->id, 'livre_id' => $livre->id],
            ['note' => $data['note']]
        );

        $livre->loadAvg('notes', 'note')->loadCount('notes');

        if ($request->expectsJson()) {
            return response()->json([
                'note' => (int) $data['note'],
                'moyenne' => round((float) $livre->notes_avg_note, 1),
                'total' => $livre->notes_count,
            ]);
        }

        return back()->with('success', 'Merci, votre note a été enregistrée.');
    }
}
