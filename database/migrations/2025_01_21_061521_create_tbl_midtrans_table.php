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
        Schema::create('tbl_midtrans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('destination_id')->constrained('tbl_destinations')->cascadeOnDelete();
            $table->string('client_key', 255);
            $table->string('server_key', 255);
            $table->boolean('is_active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_midtrans');
    }
};
