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
        Schema::create('tbl_balance_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('destination_id');
            $table->decimal('profit', 15, 2);
            $table->year('period_year');
            $table->tinyInteger('period_month'); // Bulan (1-12)
            $table->timestamps();

            $table->foreign('destination_id')->references('id')->on('tbl_destinations')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_balance_logs');
    }
};
