<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Profil (photo, téléphone, bio) et code de vérification envoyé par email.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('avatar')->nullable()->after('email');
            $table->string('telephone', 30)->nullable()->after('avatar');
            $table->text('bio')->nullable()->after('telephone');
            $table->string('otp_code')->nullable()->after('remember_token');
            $table->timestamp('otp_expires_at')->nullable()->after('otp_code');
            $table->unsignedTinyInteger('otp_tentatives')->default(0)->after('otp_expires_at');
        });

        // La vérification devient obligatoire : les comptes existants sont considérés comme vérifiés
        DB::table('users')->whereNull('email_verified_at')->update(['email_verified_at' => now()]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['avatar', 'telephone', 'bio', 'otp_code', 'otp_expires_at', 'otp_tentatives']);
        });
    }
};
