<?php

namespace Tests\Feature;

use App\Models\Contribution;
use App\Models\Livre;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ContributionTest extends TestCase
{
    use RefreshDatabase;

    private function proposer(User $user, array $donnees = [])
    {
        return $this->actingAs($user)->post('/contribuer', $donnees + [
            'titre' => 'Contes gabonais',
            'auteur' => 'Anonyme',
            'pdf' => UploadedFile::fake()->create('contes.pdf', 200, 'application/pdf'),
            'droits' => '1',
        ]);
    }

    public function test_reader_can_propose_a_book(): void
    {
        Storage::fake('public');
        $user = User::factory()->create();

        $this->proposer($user)->assertRedirect('/contribuer')->assertSessionHasNoErrors();

        $contribution = Contribution::first();
        $this->assertSame('en_attente', $contribution->statut);
        Storage::disk('public')->assertExists($contribution->pdf_path);

        $this->actingAs($user)->get('/contribuer')->assertOk()->assertSee('Contes gabonais')->assertSee('En attente');
    }

    public function test_rights_confirmation_and_pdf_are_required(): void
    {
        Storage::fake('public');

        $this->proposer(User::factory()->create(), ['droits' => null, 'pdf' => null])
            ->assertSessionHasErrors(['droits', 'pdf']);
        $this->assertSame(0, Contribution::count());
    }

    public function test_admin_accepts_contribution_into_catalogue(): void
    {
        Storage::fake('public');
        $this->proposer(User::factory()->create());
        $contribution = Contribution::first();
        $admin = User::factory()->create(['is_admin' => true]);

        $this->actingAs($admin)->post("/admin/contributions/{$contribution->id}/accepter")->assertRedirect();

        $livre = Livre::firstWhere('titre', 'Contes gabonais');
        $this->assertNotNull($livre);
        $this->assertSame('acceptee', $contribution->fresh()->statut);
        $this->assertSame($livre->id, $contribution->fresh()->livre_id);
        Storage::disk('public')->assertExists($livre->pdf_path);
        $this->assertStringStartsWith('livres/pdfs/', $livre->pdf_path);
    }

    public function test_admin_refuses_with_reason_and_file_is_deleted(): void
    {
        Storage::fake('public');
        $this->proposer(User::factory()->create());
        $contribution = Contribution::first();

        $this->actingAs(User::factory()->create(['is_admin' => true]))
            ->post("/admin/contributions/{$contribution->id}/refuser", ['motif_refus' => 'Droits non vérifiables'])
            ->assertRedirect('/admin/contributions');

        $this->assertSame('refusee', $contribution->fresh()->statut);
        Storage::disk('public')->assertMissing($contribution->pdf_path);
    }

    public function test_regular_user_cannot_moderate(): void
    {
        Storage::fake('public');
        $user = User::factory()->create();
        $this->proposer($user);

        $this->actingAs($user)->post('/admin/contributions/' . Contribution::first()->id . '/accepter')->assertForbidden();
    }
}
