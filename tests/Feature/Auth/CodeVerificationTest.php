<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use App\Notifications\CodeVerification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class CodeVerificationTest extends TestCase
{
    use RefreshDatabase;

    /** Inscrit un utilisateur et renvoie le code envoyé par email. */
    private function inscrire(): array
    {
        Notification::fake();

        $this->post('/register', ['name' => 'Awa Ndong', 'email' => 'awa@example.com', 'password' => 'motdepasse']);

        $user = User::firstWhere('email', 'awa@example.com');
        $code = null;
        Notification::assertSentTo($user, CodeVerification::class, function ($notification) use (&$code) {
            $code = $notification->code;
            return true;
        });

        return [$user, $code];
    }

    public function test_registration_sends_a_five_digit_code(): void
    {
        [$user, $code] = $this->inscrire();

        $this->assertMatchesRegularExpression('/^\d{5}$/', $code);
        $this->assertFalse($user->hasVerifiedEmail());
        $this->get('/verify-email')->assertOk()->assertSee('Saisissez votre code ici');
    }

    public function test_unverified_user_is_sent_to_code_screen(): void
    {
        $this->inscrire();

        $this->get('/dashboard')->assertRedirect(route('verification.notice'));
    }

    public function test_correct_code_verifies_the_account(): void
    {
        [$user, $code] = $this->inscrire();

        $this->post('/verify-email/code', ['code' => str_split($code)])
            ->assertRedirect(route('verification.success'));

        $this->assertTrue($user->fresh()->hasVerifiedEmail());
        $this->get(route('verification.success'))->assertOk()->assertSee('Votre adresse email est vérifiée');
    }

    public function test_wrong_code_is_rejected_and_attempts_are_limited(): void
    {
        [$user, $code] = $this->inscrire();
        $faux = $code === '11111' ? '22222' : '11111';

        for ($i = 0; $i < User::OTP_ESSAIS; $i++) {
            $this->post('/verify-email/code', ['code' => str_split($faux)])->assertSessionHasErrors('code');
        }

        // Même le bon code est refusé une fois les essais épuisés
        $this->post('/verify-email/code', ['code' => str_split($code)])->assertSessionHasErrors('code');
        $this->assertFalse($user->fresh()->hasVerifiedEmail());
    }

    public function test_expired_code_is_rejected(): void
    {
        [$user, $code] = $this->inscrire();
        $this->travel(User::OTP_DUREE + 1)->minutes();

        $this->post('/verify-email/code', ['code' => str_split($code)])->assertSessionHasErrors('code');
        $this->assertFalse($user->fresh()->hasVerifiedEmail());
    }
    public function test_mail_failure_does_not_break_registration(): void
    {
        $this->mock(\Illuminate\Notifications\Channels\MailChannel::class)
            ->shouldReceive("send")
            ->andThrow(new \Symfony\Component\Mailer\Exception\TransportException("Serveur injoignable"));

        $this->post("/register", ["name" => "Awa Ndong", "email" => "awa@example.com", "password" => "motdepasse"])
            ->assertRedirect(route("verification.notice"));

        $this->assertAuthenticated();
        $this->get("/verify-email")->assertOk()->assertSee("pas pu être envoyé", false);
    }
}
