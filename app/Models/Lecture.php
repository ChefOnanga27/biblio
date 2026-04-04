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
    ];

    protected $casts = [
        'read_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function livre()
    {
        return $this->belongsTo(Livre::class);
    }
}
