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
        Schema::create('tbl_complaints', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('tbl_users')->onDelete('cascade');
            $table->foreignId('destination_id')->constrained('tbl_destinations')->onDelete('cascade');
            $table->foreignId('restaurant_id')->constrained('tbl_restaurants')->onDelete('cascade');
            $table->text('complaint_text');
            $table->enum('status', ['open', 'resolved', 'pending']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_complaints');
    }
};
