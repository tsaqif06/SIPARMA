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
        Schema::create('tbl_places', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('slug', 255);
            $table->text('description')->nullable();
            $table->time('open_time')->nullable();
            $table->time('close_time')->nullable();
            $table->enum('operational_status', ['open', 'closed'])->nullable();
            $table->string('location', 255);
            $table->string('type', 100);
            $table->foreignId('destination_id')->nullable()->constrained('tbl_destinations')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_places');
    }
};
