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
        Schema::table('tbl_destinations', function (Blueprint $table) {
            $table->string('account_number', 50)->nullable()->after('children_price');
            $table->string('bank_name', 100)->nullable()->after('account_number');
            $table->string('account_name', 100)->nullable()->after('bank_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tbl_destinations', function (Blueprint $table) {
            $table->dropColumn(['account_number', 'bank_name', 'account_name']);
        });
    }
};
