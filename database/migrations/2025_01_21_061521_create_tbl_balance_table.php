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
        Schema::create('tbl_balance', function (Blueprint $table) {
            $table->id();
            $table->foreignId('destination_id')->constrained('tbl_destinations')->cascadeOnDelete();
            $table->decimal('balance', 15, 2)->default(0);
            $table->decimal('total_profit', 15, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_balance');
    }
};
