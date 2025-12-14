<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('publication_images', function (Blueprint $table) {
            $table->string('path')->nullable()->change();

            // Si ces colonnes n'existent pas encore
            if (!Schema::hasColumn('publication_images', 'thumb')) {
                $table->string('thumb')->nullable();
                $table->string('medium')->nullable();
                $table->string('large')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('publication_images', function (Blueprint $table) {
            $table->string('path')->nullable(false)->change();
        });
    }
};