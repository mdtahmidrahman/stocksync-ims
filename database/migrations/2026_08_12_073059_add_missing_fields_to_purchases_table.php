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
        Schema::table('purchases', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->after('company_id')->constrained()->nullOnDelete();
            $table->text('notes')->nullable()->after('total_amount');
            $table->renameColumn('purchase_number', 'reference_number');
        });

        Schema::table('purchase_items', function (Blueprint $table) {
            $table->renameColumn('unit_cost', 'unit_price');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('purchases', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
            $table->dropColumn('notes');
            $table->renameColumn('reference_number', 'purchase_number');
        });

        Schema::table('purchase_items', function (Blueprint $table) {
            $table->renameColumn('unit_price', 'unit_cost');
        });
    }
};
