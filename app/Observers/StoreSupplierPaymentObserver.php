<?php

namespace App\Observers;

use App\Models\StoreSupplierPayment;
use App\Models\StoreWithdrawal;
use App\Models\StoreSupplierInvoice;

class StoreSupplierPaymentObserver
{
    public function created(StoreSupplierPayment $payment): void
    {
        // 1. Create a StoreWithdrawal
        $payment->withdrawal()->create([
            'store_id' => $payment->store_id,
            'store_bank_account_id' => $payment->store_bank_account_id,
            'amount' => $payment->amount,
            'reason' => 'دفعة لمورد: ' . $payment->supplier->name . ($payment->invoice ? ' (فاتورة ' . $payment->invoice->invoice_number . ')' : ''),
            'withdrawal_date' => $payment->payment_date,
            'created_by' => $payment->created_by,
        ]);

        // 2. Recalculate Invoice if exists
        $this->recalculateInvoice($payment->store_supplier_invoice_id);
    }

    public function updated(StoreSupplierPayment $payment): void
    {
        // 1. Update the StoreWithdrawal or create if missing
        if ($payment->withdrawal) {
            $payment->withdrawal->update([
                'store_bank_account_id' => $payment->store_bank_account_id,
                'amount' => $payment->amount,
                'reason' => 'دفعة لمورد: ' . $payment->supplier->name . ($payment->invoice ? ' (فاتورة ' . $payment->invoice->invoice_number . ')' : ''),
                'withdrawal_date' => $payment->payment_date,
            ]);
        } else {
            $payment->withdrawal()->create([
                'store_id' => $payment->store_id,
                'store_bank_account_id' => $payment->store_bank_account_id,
                'amount' => $payment->amount,
                'reason' => 'دفعة لمورد: ' . $payment->supplier->name . ($payment->invoice ? ' (فاتورة ' . $payment->invoice->invoice_number . ')' : ''),
                'withdrawal_date' => $payment->payment_date,
                'created_by' => $payment->created_by,
            ]);
        }

        // 2. Recalculate Invoices
        if ($payment->isDirty('store_supplier_invoice_id')) {
            $this->recalculateInvoice($payment->getOriginal('store_supplier_invoice_id'));
        }
        $this->recalculateInvoice($payment->store_supplier_invoice_id);
    }

    public function deleted(StoreSupplierPayment $payment): void
    {
        if ($payment->withdrawal) {
            $payment->withdrawal->delete();
        }
        $this->recalculateInvoice($payment->store_supplier_invoice_id);
    }

    public function restored(StoreSupplierPayment $payment): void
    {
        if ($payment->withdrawal) {
            $payment->withdrawal->restore();
        }
        $this->recalculateInvoice($payment->store_supplier_invoice_id);
    }

    public function forceDeleted(StoreSupplierPayment $payment): void
    {
        if ($payment->withdrawal) {
            $payment->withdrawal->forceDelete();
        }
        $this->recalculateInvoice($payment->store_supplier_invoice_id);
    }

    protected function recalculateInvoice($invoiceId)
    {
        if (!$invoiceId) return;

        $invoice = StoreSupplierInvoice::find($invoiceId);
        if (!$invoice) return;

        $totalPaid = StoreSupplierPayment::where('store_supplier_invoice_id', $invoiceId)->sum('amount');
        
        $remaining = $invoice->total_amount - $totalPaid;
        
        $status = 'unpaid';
        if ($totalPaid >= $invoice->total_amount && $invoice->total_amount > 0) {
            $status = 'paid';
        } elseif ($totalPaid > 0) {
            $status = 'partially_paid';
        }

        $invoice->updateQuietly([
            'paid_amount' => $totalPaid,
            'remaining_amount' => $remaining,
            'status' => $status
        ]);
    }
}
