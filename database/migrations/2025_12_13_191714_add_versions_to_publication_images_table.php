<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('publication_images', function (Blueprint $table) {
            $table->string('thumb')->nullable()->after('path');
            $table->string('medium')->nullable()->after('thumb');
            $table->string('large')->nullable()->after('medium');
        });
    }

    public function down()
    {
        Schema::table('publication_images', function (Blueprint $table) {
            $table->dropColumn(['thumb','medium','large']);
        });
    }

};
