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
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();

            // Auteur (utilisateur connecté)
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');

            // Note (1..5)
            $table->tinyInteger('rating')->unsigned()->default(5)->comment('1..5 étoiles');

            // Contenu de l'avis
            $table->text('comment');

            // Visibilité (admin peut masquer)
            $table->boolean('is_visible')->default(true);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
