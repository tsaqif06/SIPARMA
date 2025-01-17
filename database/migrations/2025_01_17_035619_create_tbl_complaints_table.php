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
            $table->foreignId('user_id')->constrained('tbl_users');
            $table->foreignId('destination_id')->nullable()->constrained('tbl_destinations');
            $table->foreignId('restaurant_id')->nullable()->constrained('tbl_restaurants');
            $table->text('complaint_text');
            $table->enum('status', ['new', 'resolved', 'closed']);
            $table->timestamps(0);
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
