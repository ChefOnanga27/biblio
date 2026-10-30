<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contribution extends Model
{
    public const STATUTS = [
        'en_attente' => 'En attente',
        'acceptee' => 'Acceptée',
        'refusee' => 'Refusée',
    ];

    protected $fillable = [
        'user_id',
        'titre',
        'auteur',
        'isbn',
        'description',
        'langue',
        'categorie_id',
        'pdf_path',
        'image',
        'statut',
        'motif_refus',
        'livre_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function categorie()
    {
        return $this->belongsTo(Categorie::class);
    }

    public function livre()
    {
        return $this->belongsTo(Livre::class);
    }

    public function scopeEnAttente($query)
    {
        return $query->where('statut', 'en_attente');
    }

    public function libelleStatut(): string
    {
        return self::STATUTS[$this->statut] ?? $this->statut;
    }
}
