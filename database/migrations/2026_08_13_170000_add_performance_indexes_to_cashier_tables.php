<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations to add non-destructive performance indexes.
     */
    public function up(): void
    {
        Schema::table('store_transactions', function (Blueprint $table) {
            $table->index(['store_id', 'type', 'created_at'], 'idx_tx_store_type_created');
            $table->index(['store_id', 'store_customer_id', 'type'], 'idx_tx_store_cust_type');
            $table->index(['store_bank_account_id', 'type'], 'idx_tx_bank_type');
            $table->index(['store_id', 'transaction_date'], 'idx_tx_store_txdate');
        });

        Schema::table('store_customers', function (Blueprint $table) {
            $table->index(['store_id', 'balance'], 'idx_cust_store_balance');
            $table->index(['store_id', 'status'], 'idx_cust_store_status');
        });

        Schema::table('store_withdrawals', function (Blueprint $table) {
            $table->index(['store_id', 'withdrawal_date'], 'idx_with_store_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('store_transactions', function (Blueprint $table) {
            $table->dropIndex('idx_tx_store_type_created');
            $table->dropIndex('idx_tx_store_cust_type');
            $table->dropIndex('idx_tx_bank_type');
            $table->dropIndex('idx_tx_store_txdate');
        });

        Schema::table('store_customers', function (Blueprint $table) {
            $table->dropIndex('idx_cust_store_balance');
            $table->dropIndex('idx_cust_store_status');
        });

        Schema::table('store_withdrawals', function (Blueprint $table) {
            $table->dropIndex('idx_with_store_date');
        });
    }
};
