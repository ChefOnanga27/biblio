<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\Request;

class VerificationCodeController extends Controller
{
    /**
     * Vérifie le code à 5 chiffres reçu par email.
     */
    public function store(Request $request)
    {
        $user = $request->user();

        if ($user->hasVerifiedEmail()) {
            return redirect()->intended(RouteServiceProvider::HOME);
        }

        // Les 5 cases du formulaire arrivent en tableau ; on accepte aussi un champ unique
        $code = preg_replace('/\D/', '', implode('', (array) $request->input('code')));

        if (strlen($code) !== 5) {
            return back()->withErrors(['code' => 'Saisissez les 5 chiffres du code.']);
        }

        if ($erreur = $user->verifierCode($code)) {
            return back()->withErrors(['code' => $erreur]);
        }

        event(new Verified($user));

        return to_route('verification.success');
    }

    /**
     * Écran « Vous êtes vérifié ».
     */
    public function success(Request $request)
    {
        if (! $request->user()->hasVerifiedEmail()) {
            return to_route('verification.notice');
        }

        return view('auth.verified');
    }
}
