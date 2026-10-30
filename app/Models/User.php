<?php

namespace App\Models;

use App\Notifications\CodeVerification;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    /** Durée de validité du code de vérification, en minutes. */
    public const OTP_DUREE = 15;

    /** Nombre d'essais avant qu'un nouveau code soit nécessaire. */
    public const OTP_ESSAIS = 5;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar',
        'telephone',
        'bio',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'otp_code',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'otp_expires_at' => 'datetime',
        'is_admin' => 'boolean',
    ];

    public function lectures()
    {
        return $this->hasMany(\App\Models\Lecture::class);
    }

    public function livres()
    {
        return $this->belongsToMany(\App\Models\Livre::class, 'lectures')->withPivot('read_at')->withTimestamps();
    }

    /**
     * « Ma bibliothèque » : livres mis de côté par l'utilisateur.
     */
    public function favoris()
    {
        return $this->belongsToMany(\App\Models\Livre::class, 'favoris')->withTimestamps();
    }

    /**
     * Livres proposés via « Contribuer ».
     */
    public function contributions()
    {
        return $this->hasMany(\App\Models\Contribution::class);
    }

    /**
     * Vérification par code à 5 chiffres envoyé par email (au lieu du lien de Laravel).
     */
    public function sendEmailVerificationNotification(): void
    {
        $code = (string) random_int(10000, 99999);

        $this->forceFill([
            'otp_code' => Hash::make($code),
            'otp_expires_at' => now()->addMinutes(self::OTP_DUREE),
            'otp_tentatives' => 0,
        ])->save();

        try {
            $this->notify(new CodeVerification($code, self::OTP_DUREE));
        } catch (TransportExceptionInterface $e) {
            // Serveur mail injoignable : on garde la trace et on prévient l'utilisateur au lieu de planter
            report($e);
            session()->flash('error', 'Le code n\'a pas pu être envoyé par email. Réessayez dans un instant avec « Renvoyer un code ».');
        }
    }

    /**
     * Valide le code saisi ; renvoie un message d'erreur, ou null si l'email est vérifié.
     */
    public function verifierCode(string $code): ?string
    {
        if (! $this->otp_code || ! $this->otp_expires_at || $this->otp_expires_at->isPast()) {
            return 'Ce code a expiré. Demandez-en un nouveau.';
        }

        if ($this->otp_tentatives >= self::OTP_ESSAIS) {
            return 'Trop d\'essais. Demandez un nouveau code.';
        }

        if (! Hash::check($code, $this->otp_code)) {
            $this->increment('otp_tentatives');
            return 'Code incorrect. Vérifiez l\'email reçu et réessayez.';
        }

        $this->forceFill(['otp_code' => null, 'otp_expires_at' => null, 'otp_tentatives' => 0])->markEmailAsVerified();

        return null;
    }
}
