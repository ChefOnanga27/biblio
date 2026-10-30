<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Livre;

class Lecture extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'livre_id',
        'read_at',
        'page_actuelle',
        'pages_total',
        'progression',
        'termine_at',
        'signets',
    ];

    protected $casts = [
        'read_at' => 'datetime',
        'termine_at' => 'datetime',
        'signets' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function livre()
    {
        return $this->belongsTo(Livre::class);
    }

    public function estTerminee(): bool
    {
        return $this->termine_at !== null;
    }

    public function estEnCours(): bool
    {
        return ! $this->estTerminee() && $this->progression > 0;
    }

    /**
     * Enregistre la page atteinte dans le lecteur ; la lecture est terminée à la dernière page.
     */
    public function avancer(int $page, int $total): void
    {
        $total = max(1, $total);
        $page = min(max(1, $page), $total);

        $this->fill([
            'page_actuelle' => $page,
            'pages_total' => $total,
            'progression' => (int) round($page / $total * 100),
            'read_at' => now(),
        ]);

        if ($page >= $total && ! $this->termine_at) {
            $this->termine_at = now();
        }

        $this->save();
    }
}
