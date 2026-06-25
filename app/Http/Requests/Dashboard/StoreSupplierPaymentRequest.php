<?php

namespace App\Http\Requests\Dashboard;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\StoreBankAccount;
use App\Models\StoreSupplierPayment;
class StoreSupplierPaymentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'store_supplier_id' => 'required|exists:store_suppliers,id',
            'store_supplier_invoice_id' => 'nullable|exists:store_supplier_invoices,id',
            'store_bank_account_id' => 'required|exists:store_bank_accounts,id',
            'amount' => 'required|integer|min:1',
            'payment_date' => 'required|date',
            'notes' => 'nullable|string',
        ];

        if (user()->store_id == 1 || user()->role_id == 1 || user()->id == 1) {
            $rules['store_id'] = 'required|exists:stores,id';
        }

        return $rules;
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $paymentId = $this->route('store_supplier_payment') ?? $this->route('id');

            // 1. Bank Account Balance Validation
            if ($this->store_bank_account_id && $this->amount) {
                $bankAccount = StoreBankAccount::find($this->store_bank_account_id);
                if ($bankAccount) {
                    $availableBalance = (float) $bankAccount->current_balance;

                    // If it's an update, add back the old amount of the current payment
                    if ($paymentId) {
                        $payment = StoreSupplierPayment::find($paymentId);
                        if ($payment && $payment->store_bank_account_id == $this->store_bank_account_id) {
                            $availableBalance += (float) $payment->amount;
                        }
                    }

                    if ((float) $this->amount > $availableBalance) {
                        $validator->errors()->add('amount', __('store_supplier_payments.insufficient_balance', ['balance' => number_format($availableBalance, 0)]));
                    }
                }
            }

            // 2. Invoice Remaining Balance Validation (If invoice is selected)
            if ($this->store_supplier_invoice_id && $this->amount) {
                $invoice = \App\Models\StoreSupplierInvoice::find($this->store_supplier_invoice_id);
                if ($invoice) {
                    $availableInvoiceRemaining = (float) $invoice->remaining_amount;

                    // If it's an update, add back the old amount of the current payment to the remaining amount
                    if ($paymentId) {
                        $payment = StoreSupplierPayment::find($paymentId);
                        if ($payment && $payment->store_supplier_invoice_id == $this->store_supplier_invoice_id) {
                            $availableInvoiceRemaining += (float) $payment->amount;
                        }
                    }

                    if ((float) $this->amount > $availableInvoiceRemaining) {
                        $validator->errors()->add('amount', __('store_supplier_payments.amount_exceeds_invoice_remaining', [
                            'amount' => number_format((float) $this->amount, 0),
                            'remaining' => number_format($availableInvoiceRemaining, 0)
                        ]));
                    }
                }
            }
        });
    }
}
