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
        Schema::table('store_customers', function (Blueprint $table) {
            $table->decimal('max_debt_limit', 15, 2)->nullable()->after('bypass_debt_limit');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('store_customers', function (Blueprint $table) {
            $table->dropColumn('max_debt_limit');
        });
    }
};
