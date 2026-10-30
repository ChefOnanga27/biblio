<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Progression de lecture et signets, enregistrés par le lecteur PDF.
     */
    public function up(): void
    {
        Schema::table('lectures', function (Blueprint $table) {
            $table->unsignedInteger('page_actuelle')->nullable()->after('read_at');
            $table->unsignedInteger('pages_total')->nullable()->after('page_actuelle');
            $table->unsignedTinyInteger('progression')->default(0)->after('pages_total');
            $table->timestamp('termine_at')->nullable()->after('progression');
            $table->json('signets')->nullable()->after('termine_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lectures', function (Blueprint $table) {
            $table->dropColumn(['page_actuelle', 'pages_total', 'progression', 'termine_at', 'signets']);
        });
    }
};
