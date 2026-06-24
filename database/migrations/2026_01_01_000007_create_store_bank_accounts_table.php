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
        Schema::disableForeignKeyConstraints();
        Schema::create('store_bank_accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('store_id')->constrained()->cascadeOnDelete();
            $table->enum('account_type', ['bank', 'wallet', 'cash'])->default('bank');
            $table->foreignId('payment_entity_id')->constrained('payment_entities')->restrictOnDelete();
            $table->decimal('current_balance', 12, 2)->default(0);
            $table->string('account_number');
            $table->json('account_holder_name');
            $table->string('iban')->nullable();
            $table->boolean('is_default')->default(0);
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('store_bank_accounts');
    }
};
