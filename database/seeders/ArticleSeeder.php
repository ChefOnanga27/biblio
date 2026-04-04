<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Article;
use App\Models\User;

class ArticleSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::first() ?? User::factory()->create();

        $articles = [
            [
                'titre' => 'Comment dépasser la peur de la page blanche ?',
                'slug' => 'comment-depasser-peur-page-blanche',
                'description' => 'Découvrez nos conseils et techniques pour surmonter la peur de la page blanche et commencer à écrire votre livre.',
                'contenu' => "La peur de la page blanche est un phénomène courant qui touche de nombreux écrivains, qu'ils soient débutants ou expérimentés. Voici comment la surmonter :\n\n1. Commencez par une esquisse\nAvant de vous asseoir face à la page blanche, prenez quelques minutes pour esquisser vos idées. Un simple plan ou une liste de points peut suffire à briser la glace.\n\n2. Fixez-vous des objectifs réalistes\nNe visez pas la perfection dès le départ. Écrivez un chapitre, une scène, ou quelques paragraphes chaque jour.\n\n3. Créez une routine d'écriture\nConsacrez une heure chaque jour à l'écriture, au même moment. Cette régularité aidera votre esprit à se préparer.\n\n4. Lisez davantage\nLire des œuvres similaires à celle que vous envisagez d'écrire peut vous inspirer et vous donner confiance.\n\n5. Écrivez sans censurer\nLe brouillon n'a pas besoin d'être parfait. Laissez couler vos idées et corrigez plus tard.\n\nRappelez-vous : chaque écrivain a dû commencer quelque part. La première étape est souvent la plus difficile, mais une fois que vous avez commencé, tout devient plus facile.",
                'published_at' => now()->subDays(5),
            ],
            [
                'titre' => 'Communiquer sur la sortie de son livre sur les réseaux sociaux',
                'slug' => 'communiquer-sortie-livre-reseaux-sociaux',
                'description' => 'Apprenez les meilleures stratégies pour promouvoir votre livre sur les réseaux sociaux et toucher un large public.',
                'contenu' => "Une fois votre livre terminé, vient l'étape cruciale de sa promotion. Les réseaux sociaux sont des outils puissants pour toucher votre audience.\n\n1. Préparez votre campagne en avance\nCommencez à promouvoir votre livre au moins un mois avant sa sortie. Créez de l'anticipation.\n\n2. Utilisez du contenu visuel attrayant\nCreez des images percutantes avec le titre de votre livre, des extraits intéressants, ou des infographies sur votre parcours d'auteur.\n\n3. Engagez votre communauté\nPosez des questions, répondez aux commentaires, et créez des conversations autour de vos thèmes.\n\n4. Collaborez avec d'autres auteurs\nLes partenariats peuvent vous aider à atteindre de nouveaux lecteurs.\n\n5. Utilisez les hashtags\n#SortieDeBook #MonLivre #AuthorLife peuvent aider à augmenter votre visibilité.\n\n6. Organisez des événements virtuels\nDes présentations en direct, des lectures d'extraits ou des questions-réponses peuvent créer du buzz.\n\nLe secret est de rester authentique et transparent avec votre audience. Les lecteurs apprécient les auteurs qui sont vrais et engagés.",
                'published_at' => now()->subDays(10),
            ],
            [
                'titre' => 'Guide complet : mise en page et impression de votre livre',
                'slug' => 'guide-mise-page-impression-livre',
                'description' => 'Découvrez tous les essentiels pour mettre en page votre livre correctement et le faire imprimer profesionnellement.',
                'contenu' => "La mise en page est une étape souvent sous-estimée, mais qui peut grandement affecter la qualité finale de votre livre.\n\n1. Choisissez le bon format\nA5, A4, 13x20 cm ? Déterminez le format en fonction de votre genre et de votre public cible.\n\n2. Configurez les marges\nLaissez au moins 2 cm sur les côtés et 2,5 cm en haut et bas pour les marges de reliure.\n\n3. Sélectionnez une police lisible\nCharybdis, Georgia, ou Times New Roman sont des choix sûrs pour les livres imprimés.\n\n4. Organisez votre table des matières\nUne bonne structure rend votre livre plus professionnel et facile à naviguer.\n\n5. Choisissez un bon imprimeur\nComparez les devis et assurez-vous qu'ils offrent les services de qualité (couleur, finition, etc.).\n\n6. Prévisualisation avant impression\nToujours demander un bon à tirer avant de lancer l'impression définitive.\n\nPrenez le temps de bien faire ces étapes. Un livre bien mis en page et bien imprimé parlera de lui-même.",
                'published_at' => now()->subDays(15),
            ],
        ];

        foreach ($articles as $article) {
            $article['user_id'] = $user->id;
            Article::create($article);
        }
    }
}
