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
        Schema::create('review_comments', function (Blueprint $table) {
            $table->id();

            $table->foreignId('review_id')->constrained('reviews')->onDelete('cascade');
            // Qui commente (admin ou super-admin) — user_id nullable si on veut permettre réponse système
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');

            $table->text('comment');

            // Si c'est une réponse d'admin
            $table->boolean('is_admin_comment')->default(false);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('review_comments');
    }
};
