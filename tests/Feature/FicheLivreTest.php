<?php

namespace Tests\Feature;

use App\Models\Auteur;
use App\Models\Categorie;
use App\Models\Livre;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FicheLivreTest extends TestCase
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

    public function test_guest_is_redirected_to_login(): void
    {
        $livre = $this->livre();

        $this->get("/livres/{$livre->id}")->assertRedirect('/login');
    }

    public function test_page_shows_bibliographic_information(): void
    {
        $roman = Categorie::forceCreate(['nom' => 'Roman']);
        $livre = $this->livre([
            'titre' => 'Les Bouts de bois',
            'categorie_id' => $roman->id,
            'date_publication' => '1960-03-12',
            'langue' => 'Français',
            'nombre_pages' => 380,
            'pdf_path' => 'livres/pdfs/test.pdf',
            'description' => 'Une grève de cheminots.',
        ]);

        $this->actingAs(User::factory()->create())->get("/livres/{$livre->id}")
            ->assertOk()
            ->assertSee('Les Bouts de bois')
            ->assertSee('Informations')
            ->assertSee('12 mars 1960')
            ->assertSee('380')
            ->assertSee('Français')
            ->assertSee('Lire maintenant')
            ->assertSee('Ajouter aux favoris')
            ->assertSee('Notez ce livre');
    }

    public function test_similar_books_come_from_category_then_author(): void
    {
        $roman = Categorie::forceCreate(['nom' => 'Roman']);
        $auteur = Auteur::create(['nom' => 'Mariama Bâ']);

        $livre = $this->livre(['titre' => 'Principal', 'categorie_id' => $roman->id, 'auteur_id' => $auteur->id]);
        $this->livre(['titre' => 'Même catégorie', 'categorie_id' => $roman->id]);
        $this->livre(['titre' => 'Même auteur', 'auteur_id' => $auteur->id]);
        $this->livre(['titre' => 'Sans rapport']);

        $similaires = $this->actingAs(User::factory()->create())
            ->get("/livres/{$livre->id}")
            ->assertSee('Vous aimerez aussi')
            ->viewData('similaires');

        $this->assertSame(['Même catégorie', 'Même auteur'], $similaires->pluck('titre')->all());
    }

    public function test_favorite_state_is_reflected(): void
    {
        $user = User::factory()->create();
        $livre = $this->livre();
        $user->favoris()->attach($livre);

        $this->actingAs($user)->get("/livres/{$livre->id}")
            ->assertOk()
            ->assertSee('Dans mes favoris');
    }
    public function test_reader_can_rate_a_book_and_update_the_rating(): void
    {
        $user = User::factory()->create();
        $livre = $this->livre();
        $autre = User::factory()->create();
        $this->actingAs($autre)->postJson("/livres/{$livre->id}/note", ["note" => 2])->assertOk();

        $this->actingAs($user)->postJson("/livres/{$livre->id}/note", ["note" => 5])
            ->assertOk()->assertJson(["note" => 5, "moyenne" => 3.5, "total" => 2]);
        $this->actingAs($user)->postJson("/livres/{$livre->id}/note", ["note" => 4])
            ->assertOk()->assertJson(["moyenne" => 3, "total" => 2]);

        $this->actingAs($user)->postJson("/livres/{$livre->id}/note", ["note" => 6])->assertStatus(422);

        $this->actingAs($user)->get("/livres/{$livre->id}")->assertOk()->assertSee("Votre note")->assertSee("3,0");
    }
}
