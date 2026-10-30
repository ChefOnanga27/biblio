<?php

namespace Tests\Feature;

use App\Models\Lecture;
use App\Models\Livre;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EspaceTest extends TestCase
{
    use RefreshDatabase;

    private function livre(string $titre, array $attributes = []): Livre
    {
        return Livre::create($attributes + [
            'titre' => $titre,
            'auteur' => 'Auteur',
            'isbn' => uniqid('isbn-'),
            'pdf_path' => 'livres/pdfs/test.pdf',
        ]);
    }

    public function test_pages_require_login(): void
    {
        $this->get('/dashboard')->assertRedirect('/login');
        $this->get('/ma-bibliotheque')->assertRedirect('/login');
        $this->get('/historique')->assertRedirect('/login');
    }

    public function test_dashboard_shows_reading_to_resume(): void
    {
        $user = User::factory()->create(['name' => 'Alec Joraine']);
        $livre = $this->livre('Dune');
        Lecture::create(['user_id' => $user->id, 'livre_id' => $livre->id, 'read_at' => now()])->avancer(37, 100);

        $this->actingAs($user)->get('/dashboard')
            ->assertOk()
            ->assertSeeText('Bonjour Alec')
            ->assertSee('Reprendre la lecture')
            ->assertSee('Page 37 sur 100');
    }

    public function test_library_tabs_filter_books(): void
    {
        $user = User::factory()->create();
        $enCours = $this->livre('En cours');
        $termine = $this->livre('Terminé');
        $favori = $this->livre('Favori');
        $consulte = $this->livre('Seulement consulté');

        Lecture::create(['user_id' => $user->id, 'livre_id' => $enCours->id, 'read_at' => now()])->avancer(10, 100);
        Lecture::create(['user_id' => $user->id, 'livre_id' => $termine->id, 'read_at' => now()])->avancer(50, 50);
        Lecture::create(['user_id' => $user->id, 'livre_id' => $consulte->id, 'read_at' => now()]);
        $user->favoris()->attach($favori);

        $titres = fn ($onglet) => $this->actingAs($user)
            ->get('/ma-bibliotheque' . ($onglet ? "?onglet={$onglet}" : ''))
            ->assertOk()
            ->viewData('livres')->pluck('titre')->sort()->values()->all();

        $this->assertSame(['En cours', 'Favori', 'Seulement consulté', 'Terminé'], $titres(null));
        $this->assertSame(['En cours'], $titres('en-cours'));
        $this->assertSame(['Terminé'], $titres('termines'));
        $this->assertSame(['Favori'], $titres('favoris'));
    }

    public function test_history_lists_readings(): void
    {
        $user = User::factory()->create();
        Lecture::create(['user_id' => $user->id, 'livre_id' => $this->livre('Lu hier')->id, 'read_at' => now()->subDay()]);

        $this->actingAs($user)->get('/historique')
            ->assertOk()
            ->assertSee('Hier')
            ->assertSee('Lu hier');
    }

    public function test_settings_page_uses_personal_layout(): void
    {
        $this->actingAs(User::factory()->create())->get('/profile')
            ->assertOk()
            ->assertSee('Paramètres du compte')
            ->assertSee('Ma bibliothèque');
    }
}
