<?php

namespace Tests\Feature;

use App\Models\Livre;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LecteurTest extends TestCase
{
    use RefreshDatabase;

    private function livre(array $attributes = []): Livre
    {
        return Livre::create($attributes + [
            'titre' => 'Dune',
            'auteur' => 'Frank Herbert',
            'isbn' => uniqid('isbn-'),
            'pdf_path' => 'livres/pdfs/dune.pdf',
        ]);
    }

    public function test_reader_requires_login(): void
    {
        $this->get("/livres/{$this->livre()->id}/lire")->assertRedirect('/login');
    }

    public function test_reader_opens_and_resumes_at_last_page(): void
    {
        $user = User::factory()->create();
        $livre = $this->livre();
        $this->actingAs($user)->postJson("/livres/{$livre->id}/progression", ['page' => 42, 'total' => 120])->assertOk();

        $this->actingAs($user)->get("/livres/{$livre->id}/lire")
            ->assertOk()
            ->assertSee('dune.pdf')
            ->assertSee('pageDepart: 42', false)
            ->assertViewHas('lecture', fn ($lecture) => $lecture->page_actuelle === 42);
    }

    public function test_book_without_pdf_redirects_to_its_page(): void
    {
        $livre = $this->livre(['pdf_path' => null]);

        $this->actingAs(User::factory()->create())->get("/livres/{$livre->id}/lire")
            ->assertRedirect("/livres/{$livre->id}");
    }

    public function test_progress_is_saved_and_last_page_finishes_the_book(): void
    {
        $user = User::factory()->create();
        $livre = $this->livre();

        $this->actingAs($user)->postJson("/livres/{$livre->id}/progression", ['page' => 30, 'total' => 120])
            ->assertOk()->assertJson(['progression' => 25, 'termine' => false]);

        $this->actingAs($user)->postJson("/livres/{$livre->id}/progression", ['page' => 120, 'total' => 120])
            ->assertOk()->assertJson(['progression' => 100, 'termine' => true]);

        $this->assertNotNull($user->lectures()->first()->termine_at);
    }

    public function test_bookmarks_toggle(): void
    {
        $user = User::factory()->create();
        $livre = $this->livre();

        $this->actingAs($user)->postJson("/livres/{$livre->id}/signets", ['page' => 12])->assertJson(['signets' => [12]]);
        $this->actingAs($user)->postJson("/livres/{$livre->id}/signets", ['page' => 5])->assertJson(['signets' => [5, 12]]);
        $this->actingAs($user)->postJson("/livres/{$livre->id}/signets", ['page' => 12])->assertJson(['signets' => [5]]);
    }
}
