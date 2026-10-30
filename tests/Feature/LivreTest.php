<?php

namespace Tests\Feature;

use App\Models\Auteur;
use App\Models\Categorie;
use App\Models\Livre;
use App\Models\User;
use App\Models\Visitor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LivreTest extends TestCase
{
    use RefreshDatabase;

    private function categorie(): Categorie
    {
        return Categorie::forceCreate(['nom' => 'Roman']);
    }

    private function livre(array $attributes = []): Livre
    {
        return Livre::create($attributes + [
            'titre' => 'Un livre',
            'auteur' => 'Auteur Test',
            'isbn' => uniqid('isbn-'),
            'categorie_id' => $this->categorie()->id,
        ]);
    }

    public function test_home_page_records_visitor(): void
    {
        $this->get('/')->assertOk();
        $this->get('/')->assertOk();

        $this->assertSame(1, Visitor::count());
    }

    public function test_catalogue_filters_by_year(): void
    {
        $this->livre(['titre' => 'Ancien', 'date_publication' => '1990-05-01']);
        $this->livre(['titre' => 'Récent', 'date_publication' => '2020-05-01']);

        $this->get('/bibliotheque?annee=2020')
            ->assertOk()
            ->assertSee('Récent')
            ->assertDontSee('Ancien');
    }

    public function test_old_catalogue_url_redirects(): void
    {
        $this->get('/livres')->assertRedirect('/bibliotheque');
    }

    public function test_category_pages_render(): void
    {
        $livre = $this->livre(['titre' => 'Dans la catégorie']);

        $this->get('/categories')->assertOk()->assertSee('Roman');
        $this->get('/categories/' . $livre->categorie_id)->assertOk()->assertSee('1 livre(s)');
    }

    public function test_admin_store_links_author_and_saves_availability(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $categorie = $this->categorie();

        $this->actingAs($admin)->post('/admin/livres', [
            'titre' => 'Nouveau',
            'auteur' => '  Mariama Bâ ',
            'isbn' => '978-1',
            'categorie_id' => $categorie->id,
            'disponible' => '0',
        ])->assertRedirect('/admin/livres');

        $livre = Livre::firstWhere('isbn', '978-1');
        $this->assertSame('Mariama Bâ', $livre->auteurRelation->nom);
        $this->assertFalse((bool) $livre->disponible);
        $this->assertSame(1, Auteur::count());
    }

    public function test_admin_store_rejects_duplicate_isbn(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $existant = $this->livre(['isbn' => '978-2']);

        $this->actingAs($admin)->post('/admin/livres', [
            'titre' => 'Doublon',
            'auteur' => 'Quelqu’un',
            'isbn' => '978-2',
            'categorie_id' => $existant->categorie_id,
        ])->assertSessionHasErrors('isbn');
    }
}
