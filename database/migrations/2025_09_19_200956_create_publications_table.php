<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('publications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('country_id')->constrained('countries')->onDelete('cascade');
            $table->foreignId('town_id')->constrained('towns')->onDelete('cascade');
            $table->foreignId('district_id')->constrained('districts')->onDelete('cascade');
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
            $table->foreignId('pub_type_id')->constrained('pub_types')->onDelete('cascade');

            $table->decimal('price', 12, 2)->nullable();
            $table->integer('bathroom')->nullable();
            $table->decimal('surface', 8, 2)->nullable();
            $table->decimal('advance', 12, 2)->nullable();
            $table->decimal('deposit', 12, 2)->nullable();
            $table->text('description')->nullable();
            $table->decimal('visit', 12, 2)->nullable();

            $table->enum('offer_type', ['rent', 'sale'])->default('rent'); // louer/vendre
            $table->boolean('is_active')->default(true); // actif/inactif

            $table->timestamps();
        });

        Schema::create('publication_attribute', function (Blueprint $table) {
            $table->id();
            $table->foreignId('publication_id')->constrained('publications')->onDelete('cascade');
            $table->foreignId('attribute_id')->constrained('attributes')->onDelete('cascade');
        });

        Schema::create('publication_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('publication_id')->constrained('publications')->onDelete('cascade');
            $table->string('path');
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('publication_images');
        Schema::dropIfExists('publication_attribute');
        Schema::dropIfExists('publications');
    }
};

