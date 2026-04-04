<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('livres', function (Blueprint $table) {
            if (!Schema::hasColumn('livres', 'categorie_id')) {
                $table->foreignId('categorie_id')->nullable()->constrained('categories')->nullOnDelete();
            }
            if (!Schema::hasColumn('livres', 'genre_id')) {
                $table->foreignId('genre_id')->nullable()->constrained('genres')->nullOnDelete();
            }
            if (!Schema::hasColumn('livres', 'auteur_id')) {
                $table->foreignId('auteur_id')->nullable()->constrained('auteurs')->nullOnDelete();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('livres', function (Blueprint $table) {
            if (Schema::hasColumn('livres', 'auteur_id')) {
                $table->dropForeign(['auteur_id']);
                $table->dropColumn('auteur_id');
            }
            if (Schema::hasColumn('livres', 'genre_id')) {
                $table->dropForeign(['genre_id']);
                $table->dropColumn('genre_id');
            }
            if (Schema::hasColumn('livres', 'categorie_id')) {
                $table->dropForeign(['categorie_id']);
                $table->dropColumn('categorie_id');
            }
        });
    }
};
