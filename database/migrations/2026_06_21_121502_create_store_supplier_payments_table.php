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
        Schema::create('store_supplier_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('store_id')->constrained()->cascadeOnDelete();
            $table->foreignId('store_supplier_id')->constrained('store_suppliers')->cascadeOnDelete();
            $table->foreignId('store_supplier_invoice_id')->nullable()->constrained('store_supplier_invoices')->cascadeOnDelete();
            $table->foreignId('store_bank_account_id')->constrained('store_bank_accounts')->cascadeOnDelete();
            $table->decimal('amount', 12, 2);
            $table->dateTime('payment_date');
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('store_supplier_payments');
    }
};
