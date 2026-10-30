<?php

namespace App\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CodeVerification extends Notification
{
    public function __construct(public string $code, public int $minutes)
    {
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $site = config('app.site_nom');

        return (new MailMessage)
            ->subject("Votre code de vérification {$site} : {$this->code}")
            ->greeting('Bonjour ' . $notifiable->name . ',')
            ->line("Voici votre code pour vérifier votre adresse email sur {$site} :")
            ->line('**' . implode(' ', str_split($this->code)) . '**')
            ->line("Ce code est valable {$this->minutes} minutes.")
            ->line('Si vous n\'avez pas créé de compte, ignorez simplement ce message.')
            ->salutation('À bientôt sur ' . $site);
    }
}
