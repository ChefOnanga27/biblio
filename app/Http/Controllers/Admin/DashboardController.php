<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contribution;
use App\Models\Lecture;
use App\Models\Livre;
use App\Models\Note;
use App\Models\User;
use App\Models\Visitor;
use Carbon\CarbonPeriod;

class DashboardController extends Controller
{
    private const JOURS = 30;

    public function index()
    {
        $depuis = now()->subDays(self::JOURS - 1)->startOfDay();

        // Visiteurs uniques par jour (une ligne par IP et par jour dans `visitors`)
        $visitesParJour = Visitor::where('visited_at', '>=', $depuis->toDateString())
            ->selectRaw('DATE(visited_at) as jour, COUNT(*) as total')
            ->groupBy('jour')
            ->pluck('total', 'jour');

        $serie = collect(CarbonPeriod::create($depuis, now()->startOfDay()))
            ->map(fn ($jour) => [
                'date' => $jour->toDateString(),
                'libelle' => $jour->locale('fr')->translatedFormat('j M'),
                'valeur' => (int) ($visitesParJour[$jour->toDateString()] ?? 0),
            ])->values();

        return view('admin.dashboard', [
            'jours' => self::JOURS,
            'serie' => $serie,
            'indicateurs' => [
                'livres' => Livre::count(),
                'lecteurs' => User::count(),
                'nouveauxLecteurs' => User::where('created_at', '>=', $depuis)->count(),
                'lecturesActives' => Lecture::where('read_at', '>=', $depuis)->count(),
                'termines' => Lecture::whereNotNull('termine_at')->where('termine_at', '>=', $depuis)->count(),
                'visiteurs' => $serie->sum('valeur'),
                'noteMoyenne' => Note::avg('note'),
                'nombreNotes' => Note::count(),
                'contributions' => Contribution::enAttente()->count(),
            ],
            'aCompleter' => [
                'image' => Livre::whereNull('image')->count(),
                'pdf' => Livre::whereNull('pdf_path')->count(),
                'categorie' => Livre::whereNull('categorie_id')->count(),
            ],
            'plusLus' => Livre::popular()->has('lectures')->take(5)->get(),
            'dernieresLectures' => Lecture::with(['user', 'livre'])->latest('read_at')->take(6)->get(),
            'derniersInscrits' => User::latest()->take(6)->get(),
            'contributions' => Contribution::enAttente()->with('user')->latest()->take(4)->get(),
        ]);
    }
}
