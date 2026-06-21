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
        Schema::create('currencies', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // e.g., QAR, USD
            $table->string('name_ar'); // e.g., ريال قطري
            $table->string('name_en'); // e.g., Qatari Riyal
            $table->string('symbol_ar'); // e.g., ر.ق
            $table->string('symbol_en'); // e.g., QAR
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('currencies');
    }
};
