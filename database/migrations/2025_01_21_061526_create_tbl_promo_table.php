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
        Schema::create('tbl_promo', function (Blueprint $table) {
            $table->id();
            $table->foreignId('destination_id')->nullable()->constrained('tbl_destinations')->nullOnDelete();
            $table->foreignId('place_id')->nullable()->constrained('tbl_places')->nullOnDelete();
            $table->decimal('discount', 10, 2);
            $table->date('valid_from');
            $table->date('valid_until');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_promo');
    }
};
