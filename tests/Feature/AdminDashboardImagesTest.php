<?php

namespace Tests\Feature;

use App\Models\Categorie;
use App\Models\Contribution;
use App\Models\Livre;
use App\Models\User;
use App\Models\Visitor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminDashboardImagesTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return User::factory()->create(['is_admin' => true, 'name' => 'Awa Admin']);
    }

    public function test_dashboard_shows_indicators_and_daily_visitors(): void
    {
        Livre::create(['titre' => 'Sans image', 'auteur' => 'A', 'isbn' => 'x1']);
        Visitor::create(['ip' => '1.1.1.1', 'visited_at' => today()]);
        Visitor::create(['ip' => '2.2.2.2', 'visited_at' => today()]);
        Visitor::create(['ip' => '1.1.1.1', 'visited_at' => today()->subDays(3)]);

        $response = $this->actingAs($this->admin())->get('/admin/dashboard')->assertOk();

        $serie = $response->viewData('serie');
        $this->assertCount(30, $serie);
        $this->assertSame(2, $serie->last()['valeur']);
        $this->assertSame(3, $serie->sum('valeur'));
        $this->assertSame(1, $response->viewData('aCompleter')['image']);
        $response->assertSee('Visiteurs uniques par jour')->assertSee('Awa Admin');
    }

    public function test_books_list_can_be_filtered_by_missing_field(): void
    {
        $categorie = Categorie::forceCreate(['nom' => 'Roman']);
        Livre::create(['titre' => 'Complet', 'auteur' => 'A', 'isbn' => 'c1', 'categorie_id' => $categorie->id, 'image' => 'livres/x.webp']);
        Livre::create(['titre' => 'Sans couverture', 'auteur' => 'A', 'isbn' => 'c2', 'categorie_id' => $categorie->id]);

        $livres = $this->actingAs($this->admin())->get('/admin/livres?manque=image')->assertOk()->viewData('livres');

        $this->assertSame(['Sans couverture'], $livres->pluck('titre')->all());
    }

    /** Vraie image PNG (sans l'extension GD) pour passer la règle dimensions. */
    private function png(string $nom, int $l = 200, int $h = 300): UploadedFile
    {
        $chunk = fn ($type, $data) => pack('N', strlen($data)) . $type . $data . pack('N', crc32($type . $data));
        $ligne = "\0" . str_repeat("\xe0\x60\x3f", $l);
        $png = "\x89PNG\r\n\x1a\n"
            . $chunk('IHDR', pack('NNCCCCC', $l, $h, 8, 2, 0, 0, 0))
            . $chunk('IDAT', gzcompress(str_repeat($ligne, $h)))
            . $chunk('IEND', '');
        $chemin = tempnam(sys_get_temp_dir(), 'png');
        file_put_contents($chemin, $png);

        return new UploadedFile($chemin, $nom, 'image/png', null, true);
    }

    public function test_cover_can_be_replaced_and_removed(): void
    {
        Storage::fake('public');
        $admin = $this->admin();
        $categorie = Categorie::forceCreate(['nom' => 'Roman']);
        $donnees = ['titre' => 'Livre', 'auteur' => 'Auteur', 'isbn' => '978-9', 'categorie_id' => $categorie->id];

        $this->actingAs($admin)->post('/admin/livres', $donnees + [
            'image' => $this->png('a.png'),
        ])->assertSessionHasNoErrors();
        $livre = Livre::first();
        $premiere = $livre->image;
        Storage::disk('public')->assertExists($premiere);

        $this->actingAs($admin)->put("/admin/livres/{$livre->id}", $donnees + [
            'image' => $this->png('b.png'),
        ]);
        Storage::disk('public')->assertMissing($premiere);
        Storage::disk('public')->assertExists($livre->fresh()->image);

        $seconde = $livre->fresh()->image;
        $this->actingAs($admin)->put("/admin/livres/{$livre->id}", $donnees + ['supprimer_image' => '1']);
        $this->assertNull($livre->fresh()->image);
        Storage::disk('public')->assertMissing($seconde);
    }

    public function test_pending_contributions_badge_in_admin_menu(): void
    {
        Contribution::create(['user_id' => User::factory()->create()->id, 'titre' => 'T', 'auteur' => 'A', 'pdf_path' => 'x.pdf']);

        $this->actingAs($this->admin())->get('/admin/dashboard')->assertSee('1 en attente');
    }
}
