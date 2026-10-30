<?php

namespace Tests\Feature;

use App\Models\Categorie;
use App\Models\Lecture;
use App\Models\Livre;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomeTest extends TestCase
{
    use RefreshDatabase;

    private function livre(string $titre, ?Categorie $categorie = null): Livre
    {
        return Livre::create([
            'titre' => $titre,
            'auteur' => 'Auteur',
            'isbn' => uniqid('isbn-'),
            'categorie_id' => $categorie?->id,
        ]);
    }

    public function test_empty_home_page_renders(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('Citation du jour')
            ->assertSee("aucun livre n'est encore disponible", false)
            ->assertDontSee('Tendances');
    }

    public function test_most_read_book_is_trending(): void
    {
        $this->livre('Livre calme');
        $star = $this->livre('Livre star');

        foreach (User::factory()->count(2)->create() as $user) {
            Lecture::create(['user_id' => $user->id, 'livre_id' => $star->id, 'read_at' => now()]);
        }

        $response = $this->get('/')->assertOk();

        $this->assertSame('Livre star', $response->viewData('tendances')->first()->titre);
        $response->assertSee('Tendances');
        $response->assertSee('Livres populaires');
        $response->assertSee('Nouveautés');
    }

    public function test_recommendations_use_read_categories_and_skip_read_books(): void
    {
        $roman = Categorie::forceCreate(['nom' => 'Roman']);
        $science = Categorie::forceCreate(['nom' => 'Science']);
        $lu = $this->livre('Déjà lu', $roman);
        $this->livre('Roman suggéré', $roman);
        $this->livre('Hors sujet', $science);

        $user = User::factory()->create();
        Lecture::create(['user_id' => $user->id, 'livre_id' => $lu->id, 'read_at' => now()]);

        $recommandes = $this->actingAs($user)->get('/')->assertOk()->viewData('recommandes');

        $this->assertSame(['Roman suggéré'], $recommandes->pluck('titre')->all());
    }

    public function test_guest_gets_no_recommendations(): void
    {
        $this->livre('Un livre');

        $this->get('/')->assertOk()->assertDontSee('Recommandés pour vous');
    }
    public function test_signed_in_user_sees_recent_readings(): void
    {
        $user = User::factory()->create(["name" => "Awa Ndong"]);
        $livre = $this->livre("Mon dernier livre");
        Lecture::create(["user_id" => $user->id, "livre_id" => $livre->id, "read_at" => now()]);

        $this->actingAs($user)->get("/")
            ->assertOk()
            ->assertSee("Awa")
            ->assertSee("Lectures récentes")
            ->assertSee("Mon dernier livre");
    }
}
