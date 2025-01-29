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
        Schema::create('tbl_destinations', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('slug', 255);
            $table->enum('type', ['alam', 'wahana']);
            $table->text('description')->nullable();
            $table->string('location', 255);
            $table->time('open_time')->nullable();
            $table->time('close_time')->nullable();
            $table->enum('operational_status', ['open', 'closed', 'holiday'])->nullable();
            $table->decimal('price', 10, 2)->nullable();
            $table->decimal('weekend_price', 10, 2)->nullable();
            $table->decimal('children_price', 10, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_destinations');
    }
};
