<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Livres proposés par les lecteurs, validés par un administrateur.
     */
    public function up(): void
    {
        Schema::create('contributions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('titre');
            $table->string('auteur');
            $table->string('isbn', 50)->nullable();
            $table->text('description')->nullable();
            $table->string('langue', 50)->nullable();
            $table->foreignId('categorie_id')->nullable()->constrained('categories')->nullOnDelete();
            $table->string('pdf_path');
            $table->string('image')->nullable();
            $table->string('statut', 20)->default('en_attente')->index();
            $table->text('motif_refus')->nullable();
            $table->foreignId('livre_id')->nullable()->constrained('livres')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contributions');
    }
};
