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
        Schema::create('tbl_withdrawals', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('balance_id');
            $table->decimal('amount', 15, 2);
            $table->enum('status', ['pending', 'completed', 'rejected'])->default('pending');
            $table->text('admin_note')->nullable();
            $table->string('transfer_proof', 255)->nullable();
            $table->timestamps();

            $table->foreign('balance_id')->references('id')->on('tbl_balance')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_withdrawals');
    }
};
