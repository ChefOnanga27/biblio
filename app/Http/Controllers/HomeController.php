<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Livre;
use App\Models\Visitor;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $livres = Livre::orderBy('created_at', 'desc')->limit(6)->get();

        // Enregistrement des visiteurs (par IP/date)
        Visitor::firstOrCreate(
            [
                'ip' => $request->ip(),
                'visited_at' => now()->toDateString(),
            ],
            [
                'user_agent' => $request->userAgent(),
            ]
        );

        return view('home', compact('livres'));
    }
}
