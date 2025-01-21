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
        Schema::create('tbl_transaction_tickets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaction_id')->constrained('tbl_transactions')->cascadeOnDelete();
            $table->enum('item_type', ['destination', 'ride', 'bundle']);
            $table->unsignedBigInteger('item_id');
            $table->integer('adult_count');
            $table->integer('children_count');
            $table->decimal('subtotal', 10, 2);
            $table->date('visit_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_transaction_tickets');
    }
};
