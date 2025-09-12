<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('towns', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('country_id')
                ->constrained('countries')
                ->onDelete('cascade'); // si le pays est supprimé, toutes les villes le sont
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('towns');
    }
};
