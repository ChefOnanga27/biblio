<?php

namespace Tests\Feature;

use App\Models\Categorie;
use App\Models\Lecture;
use App\Models\Livre;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CatalogueTest extends TestCase
{
    use RefreshDatabase;

    private function livre(array $attributes = []): Livre
    {
        return Livre::create($attributes + [
            'titre' => 'Un livre',
            'auteur' => 'Auteur',
            'isbn' => uniqid('isbn-'),
        ]);
    }

    public function test_catalogue_renders_intro_and_categories(): void
    {
        Categorie::forceCreate(['nom' => 'Romans et littérature']);

        $this->get('/bibliotheque')
            ->assertOk()
            ->assertSee('Parcourir')
            ->assertSee('dans le catalogue')
            ->assertSee('Romans et littérature');
    }

    public function test_search_matches_description_keyword(): void
    {
        $this->livre(['titre' => 'Contes du Gabon', 'description' => 'Récits de la forêt équatoriale']);
        $this->livre(['titre' => 'Autre ouvrage', 'description' => 'Rien à voir']);

        $this->get('/bibliotheque?search=forêt')
            ->assertOk()
            ->assertSee('Contes du Gabon')
            ->assertDontSee('Autre ouvrage');
    }

    public function test_sort_by_title_and_by_popularity(): void
    {
        $this->livre(['titre' => 'Zèbre']);
        $populaire = $this->livre(['titre' => 'Mangue']);
        $this->livre(['titre' => 'Ananas']);
        Lecture::create(['user_id' => User::factory()->create()->id, 'livre_id' => $populaire->id, 'read_at' => now()]);

        $this->assertSame(['Ananas', 'Mangue', 'Zèbre'],
            $this->get('/bibliotheque?tri=titre')->viewData('livres')->pluck('titre')->all());

        $this->assertSame('Mangue',
            $this->get('/bibliotheque?tri=populaires')->viewData('livres')->first()->titre);
    }

    public function test_language_filter(): void
    {
        $this->livre(['titre' => 'En fang', 'langue' => 'Fang']);
        $this->livre(['titre' => 'En français', 'langue' => 'Français']);

        $this->get('/bibliotheque?langue=Fang')
            ->assertOk()
            ->assertSee('En fang')
            ->assertDontSee('En français');
    }

    public function test_empty_state_message(): void
    {
        $this->get('/bibliotheque?search=introuvable')
            ->assertOk()
            ->assertSee('Aucun ouvrage trouvé.')
            ->assertSee('Essayez une autre recherche.');
    }

    public function test_favorite_toggle_returns_json_and_persists(): void
    {
        $user = User::factory()->create();
        $livre = $this->livre();

        $this->actingAs($user)->postJson("/livres/{$livre->id}/favori")
            ->assertOk()->assertJson(['favori' => true]);
        $this->assertTrue($user->favoris()->whereKey($livre->id)->exists());

        $this->get('/bibliotheque')->assertSee('aria-pressed="true"', false);

        $this->actingAs($user)->postJson("/livres/{$livre->id}/favori")
            ->assertOk()->assertJson(['favori' => false]);
        $this->assertFalse($user->favoris()->whereKey($livre->id)->exists());
    }

    public function test_guest_cannot_add_favorite(): void
    {
        $livre = $this->livre();

        $this->post("/livres/{$livre->id}/favori")->assertRedirect('/login');
    }
}
