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
        Schema::create('tbl_gallery_destinations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('destination_id')->constrained('tbl_destinations');
            $table->string('image_url', 255);
            $table->enum('image_type', ['place', 'promo', 'menu']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_gallery_destinations');
    }
};
