<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/bibliotheque', [App\Http\Controllers\LivreController::class, 'index'])
    ->name('livre.index');

// Ancienne URL du catalogue, conservée pour les liens existants
Route::permanentRedirect('/livres', '/bibliotheque');

Route::get('/livres/{livre}', [App\Http\Controllers\LivreController::class, 'show'])
    ->name('livres.show')
    ->middleware(['auth', 'verified']);

Route::post('/livres/{livre}/favori', [App\Http\Controllers\FavoriController::class, 'toggle'])
    ->name('livres.favori')
    ->middleware(['auth', 'verified']);

Route::post('/livres/{livre}/note', [App\Http\Controllers\NoteController::class, 'store'])
    ->name('livres.note')
    ->middleware(['auth', 'verified']);

// Contribuer : proposer un livre
Route::middleware(['auth', 'verified'])->controller(App\Http\Controllers\ContributionController::class)->group(function () {
    Route::get('/contribuer', 'create')->name('contributions.create');
    Route::post('/contribuer', 'store')->name('contributions.store');
});

// Lecteur PDF intégré
Route::middleware(['auth', 'verified'])->controller(App\Http\Controllers\LecteurController::class)->group(function () {
    Route::get('/livres/{livre}/lire', 'show')->name('livres.lire');
    Route::post('/livres/{livre}/progression', 'progression')->name('livres.progression');
    Route::post('/livres/{livre}/signets', 'signet')->name('livres.signets');
});

Route::get('/categories', [App\Http\Controllers\CategorieController::class, 'index'])
    ->name('categories.index');

Route::get('/categories/{categorie}', [App\Http\Controllers\CategorieController::class, 'show'])
    ->name('categories.show');

Route::get('/blog',[App\Http\Controllers\ArticleController::class, 'index'])
    ->name('articles.index');

Route::get('/blog/{article:slug}', [App\Http\Controllers\ArticleController::class, 'show'])
    ->name('articles.show');

Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
    Route::resource('livres', App\Http\Controllers\Admin\LivreController::class)->except(['show']);
    Route::resource('articles', App\Http\Controllers\Admin\ArticleController::class)->except(['show']);
    Route::get('lectures', [App\Http\Controllers\Admin\LectureController::class, 'index'])->name('lectures.index');
    Route::get('contributions', [App\Http\Controllers\Admin\ContributionController::class, 'index'])->name('contributions.index');
    Route::post('contributions/{contribution}/accepter', [App\Http\Controllers\Admin\ContributionController::class, 'accepter'])->name('contributions.accepter');
    Route::post('contributions/{contribution}/refuser', [App\Http\Controllers\Admin\ContributionController::class, 'refuser'])->name('contributions.refuser');
});

// Espace personnel
Route::middleware(['auth', 'verified'])->controller(App\Http\Controllers\EspaceController::class)->group(function () {
    Route::get('/dashboard', 'dashboard')->name('dashboard');
    Route::get('/ma-bibliotheque', 'bibliotheque')->name('espace.bibliotheque');
    Route::get('/historique', 'historique')->name('espace.historique');
});

Route::get('/a-propos', function () {
    return view('about');
})->name('about');

Route::get('/contact', function () {
    return view('contact');
})->name('contact');

Route::post('/contact', function (\Illuminate\Http\Request $request) {
    $data = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|max:255',
        'message' => 'required|string',
    ]);



    // Ici on peut envoyer un email ou stocker en base.
    // Mail::to(config('mail.from.address'))->send(new ContactMessage($data));

    return back()->with('success', 'Votre message a été envoyé avec succès.');
})->name('contact.submit');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
