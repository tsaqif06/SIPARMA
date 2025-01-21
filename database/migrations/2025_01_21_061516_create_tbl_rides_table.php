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
        Schema::create('tbl_rides', function (Blueprint $table) {
            $table->id();
            $table->foreignId('destination_id')->constrained('tbl_destinations')->cascadeOnDelete();
            $table->string('name', 100);
            $table->string('slug', 255);
            $table->text('description');
            $table->decimal('price', 10, 2);
            $table->decimal('weekend_price', 10, 2);
            $table->decimal('children_price', 10, 2);
            $table->integer('min_age');
            $table->integer('min_height');
            $table->enum('status', ['active', 'inactive']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_rides');
    }
};
