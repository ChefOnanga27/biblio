<?php

namespace App\Providers;

use App\Models\Categorie;
use App\Models\Livre;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::share('siteNom', config('app.site_nom'));

        // Données de la barre du haut (recherche par catégorie, langue)
        View::composer('layouts.site', function ($view) {
            $view->with([
                'shellCategories' => Categorie::orderBy('nom')->get(['id', 'nom']),
                'shellLangues' => Livre::whereNotNull('langue')->distinct()->orderBy('langue')->pluck('langue'),
            ]);
        });
    }
}
