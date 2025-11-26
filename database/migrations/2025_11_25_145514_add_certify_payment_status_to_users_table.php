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
        Schema::table('users', function (Blueprint $table) {
            // Ajouter le statut de certification du paiement
            $table->boolean('certify_payment_status')
                  ->default(false)
                  ->after('is_verified')
                  ->comment('Indique si l’utilisateur a un paiement certifié');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('certify_payment_status');
        });
    }
};
