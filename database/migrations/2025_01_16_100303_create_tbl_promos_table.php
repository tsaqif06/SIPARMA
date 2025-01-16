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
        Schema::create('tbl_promos', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->text('description');
            $table->date('start_date');
            $table->date('end_date');
            $table->decimal('discount_percentage', 5, 2);
            $table->foreignId('destination_id')->nullable()->constrained('tbl_destinations')->onDelete('set null');
            $table->foreignId('restaurant_id')->nullable()->constrained('tbl_restaurants')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_promos');
    }
};
