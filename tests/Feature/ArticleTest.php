<?php

namespace Tests\Feature;

use App\Models\Article;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ArticleTest extends TestCase
{
    use RefreshDatabase;

    private function postArticle(User $admin, string $titre)
    {
        return $this->actingAs($admin)->post('/admin/articles', [
            'titre' => $titre,
            'description' => 'Description',
            'contenu' => 'Contenu',
            'image' => UploadedFile::fake()->create('cover.jpg', 100, 'image/jpeg'),
            'published_at' => now()->subDay()->format('Y-m-d\TH:i'),
        ]);
    }

    public function test_articles_with_same_title_get_unique_slugs(): void
    {
        Storage::fake('public');
        $admin = User::factory()->create(['is_admin' => true]);

        $this->postArticle($admin, 'Mon titre')->assertRedirect('/admin/articles');
        $this->postArticle($admin, 'Mon titre')->assertRedirect('/admin/articles');

        $this->assertEqualsCanonicalizing(['mon-titre', 'mon-titre-2'], Article::pluck('slug')->all());
        $this->get('/blog/mon-titre-2')->assertOk();
    }

    public function test_deleting_article_removes_its_image(): void
    {
        Storage::fake('public');
        $admin = User::factory()->create(['is_admin' => true]);

        $this->postArticle($admin, 'À supprimer');
        $article = Article::first();
        Storage::disk('public')->assertExists($article->image);

        $this->actingAs($admin)->delete('/admin/articles/' . $article->id);

        Storage::disk('public')->assertMissing($article->image);
    }
}
