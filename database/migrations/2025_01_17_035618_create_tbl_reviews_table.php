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
        Schema::create('tbl_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('tbl_users');
            $table->foreignId('destination_id')->nullable()->constrained('tbl_destinations');
            $table->foreignId('restaurant_id')->nullable()->constrained('tbl_restaurants');
            $table->integer('rating');
            $table->text('comment');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_reviews');
    }
};
