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
        Schema::table('companies', function (Blueprint $table) {
            $table->string('timezone')->nullable()->default('Asia/Dhaka');
            $table->string('logo_path')->nullable();
            $table->decimal('pos_tax_rate', 5, 2)->default(0.00);
            $table->text('pos_receipt_footer')->nullable()->default('Thank you for shopping with us!');
            $table->string('printer_type')->default('thermal_80mm');
            $table->boolean('auto_print_receipt')->default(true);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn([
                'timezone',
                'logo_path',
                'pos_tax_rate',
                'pos_receipt_footer',
                'printer_type',
                'auto_print_receipt',
            ]);
        });
    }
};
