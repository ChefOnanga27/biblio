<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        
        // Base catégories
        \App\Models\Categorie::upsert([
            ['nom' => 'Science-fiction'],
            ['nom' => 'Fantastique'],
            ['nom' => 'Romance'],
            ['nom' => 'Policier'],
            ['nom' => 'Histoire'],
            ['nom' => 'Développement personnel'],
            ['nom' => 'Informatique'],
        ], ['nom'], ['nom']);

        // Genres exemple
        \App\Models\Genre::upsert([
            ['nom' => 'Roman'],
            ['nom' => 'Nouvelle'],
            ['nom' => 'Essai'],
            ['nom' => 'Biographie'],
            ['nom' => 'Poésie'],
        ], ['nom'], ['nom']);

        // Exemples d'auteurs
        \App\Models\Auteur::upsert([
            ['nom' => 'Jules Verne'],
            ['nom' => 'Agatha Christie'],
            ['nom' => 'Gabriel García Márquez'],
            ['nom' => 'Virginia Woolf'],
            ['nom' => 'Stephen King'],
        ], ['nom'], ['nom']);

        // Exemples de livres si souhaité
        if (\App\Models\Livre::count() === 0) {
            $category = \App\Models\Categorie::where('nom', 'Science-fiction')->first();
            $genre = \App\Models\Genre::where('nom', 'Roman')->first();
            $author = \App\Models\Auteur::where('nom', 'Jules Verne')->first();

            \App\Models\Livre::create([
                'titre' => 'Vingt mille lieues sous les mers',
                'auteur' => $author->nom,
                'isbn' => '978-1234567890',
                'description' => 'Un classique de la science-fiction.',
                'date_publication' => '1870-06-20',
                'categorie_id' => $category->id,
                'genre_id' => $genre->id,
                'auteur_id' => $author->id,
                'disponible' => true,
            ]);
        }

        // Articles du blog
        $this->call(ArticleSeeder::class);
    }
}
