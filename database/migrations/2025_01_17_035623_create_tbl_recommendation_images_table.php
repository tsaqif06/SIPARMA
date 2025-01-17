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
        Schema::create('tbl_recommendation_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('recommendation_id')->constrained('tbl_recommendations');
            $table->text('image_url');
            $table->timestamps(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_recommendation_images');
    }
};
