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
            $table->string('subscription_tier')->default('free');
            $table->string('subscription_status')->default('active');
            $table->decimal('mrr', 10, 2)->default(0.00);
            $table->boolean('allow_support_impersonation')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn(['subscription_tier', 'subscription_status', 'mrr', 'allow_support_impersonation']);
        });
    }
};
