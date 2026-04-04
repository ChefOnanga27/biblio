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

    public function readers()
    {
        return $this->belongsToMany(\App\Models\User::class, 'lectures')->withPivot('read_at')->withTimestamps();
    }
}

    