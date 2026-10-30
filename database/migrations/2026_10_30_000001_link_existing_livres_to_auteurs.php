<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Rattache les livres existants à un auteur (table auteurs) à partir
     * de la colonne texte `auteur`, pour que le filtre par auteur fonctionne.
     */
    public function up(): void
    {
        $livres = DB::table('livres')
            ->whereNull('auteur_id')
            ->whereNotNull('auteur')
            ->where('auteur', '!=', '')
            ->get(['id', 'auteur']);

        foreach ($livres as $livre) {
            $nom = trim($livre->auteur);

            $auteurId = DB::table('auteurs')->where('nom', $nom)->value('id')
                ?? DB::table('auteurs')->insertGetId([
                    'nom' => $nom,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

            DB::table('livres')->where('id', $livre->id)->update(['auteur_id' => $auteurId]);
        }
    }

    /**
     * Migration de données : rien à annuler.
     */
    public function down(): void
    {
        //
    }
};
