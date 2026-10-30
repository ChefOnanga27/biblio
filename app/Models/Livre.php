<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Livre extends Model
{
    use HasFactory;

    protected $fillable = [
        'titre',
        'auteur',
        'isbn',
        'categorie_id',
        'genre_id',
        'auteur_id',
        'description',
        'date_publication',
        'langue',
        'nombre_pages',
        'image',
        'pdf_path',
        'disponible',
    ];
    public function getSlugAttribute()
    {
        return Str::slug($this->titre);
    }

    public function categorie()
    {
        return $this->belongsTo(\App\Models\Categorie::class);
    }

    public function genre()
    {
        return $this->belongsTo(\App\Models\Genre::class);
    }

    public function auteurRelation()
    {
        return $this->belongsTo(\App\Models\Auteur::class, 'auteur_id');
    }

    public function lectures()
    {
        return $this->hasMany(\App\Models\Lecture::class);
    }

    /**
     * Livres les plus lus (toutes périodes confondues).
     */
    public function scopePopular($query)
    {
        return $query->withCount('lectures')
            ->orderByDesc('lectures_count')
            ->latest();
    }

    /**
     * Livres lus récemment, du plus lu au moins lu sur la période.
     */
    public function scopeTrending($query, int $days = 30)
    {
        $recent = fn ($q) => $q->where('read_at', '>=', now()->subDays($days));

        return $query->whereHas('lectures', $recent)
            ->withCount(['lectures as recent_lectures_count' => $recent])
            ->orderByDesc('recent_lectures_count');
    }

    /**
     * Livres non encore lus, dans les catégories que l'utilisateur lit déjà.
     */
    public function scopeRecommandesPour($query, User $user)
    {
        $categoriesLues = $user->livres()->whereNotNull('categorie_id')->pluck('categorie_id')->unique();

        return $query->whereIn('categorie_id', $categoriesLues)
            ->whereDoesntHave('lectures', fn ($q) => $q->where('user_id', $user->id))
            ->popular();
    }

    public function readers()
    {
        return $this->belongsToMany(\App\Models\User::class, 'lectures')->withPivot('read_at')->withTimestamps();
    }

    public function notes()
    {
        return $this->hasMany(\App\Models\Note::class);
    }

    /**
     * Ajoute la note moyenne (notes_avg_note) et le nombre de notes (notes_count).
     */
    public function scopeAvecNotes($query)
    {
        return $query->withAvg('notes', 'note')->withCount('notes');
    }

    public function favorisPar()
    {
        return $this->belongsToMany(\App\Models\User::class, 'favoris')->withTimestamps();
    }
}

    