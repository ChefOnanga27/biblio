<?php

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

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])
    ->name('home');

Route::get('/bibliotheque', [App\Http\Controllers\LivreController::class, 'index'])
    ->name('livre.index');

Route::get('/livres', [App\Http\Controllers\LivreController::class, 'index'])
    ->name('livres');

Route::get('/livres/{livre}', [App\Http\Controllers\LivreController::class, 'show'])
    ->name('livres.show')
    ->middleware('auth');

Route::get('/blog', [App\Http\Controllers\ArticleController::class, 'index'])
    ->name('articles.index');

Route::get('/blog/{article:slug}', [App\Http\Controllers\ArticleController::class, 'show'])
    ->name('articles.show');

Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function () {
    Route::get('dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
    Route::resource('livres', App\Http\Controllers\Admin\LivreController::class)->except(['show']);
    Route::resource('articles', App\Http\Controllers\Admin\ArticleController::class)->except(['show']);
    Route::get('lectures', [App\Http\Controllers\Admin\LectureController::class, 'index'])->name('lectures.index');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

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


Route::get('/', [LivreController::class, 'index'])->name('livre.home');
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
