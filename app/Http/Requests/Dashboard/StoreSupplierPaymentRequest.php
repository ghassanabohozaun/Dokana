<?php

namespace App\Http\Requests\Dashboard;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\StoreBankAccount;
use App\Models\StoreSupplier;
use App\Models\StoreSupplierInvoice;
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
            'amount' => 'required|numeric|min:0.01',
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
            $paymentId = $this->route('store_supplier_payment') ?? $this->route('id') ?? $this->id;
            $payment = $paymentId ? StoreSupplierPayment::find($paymentId) : null;

            // Determine effective store_id
            $effectiveStoreId = $payment ? $payment->store_id : ($this->store_id ?? user()->store_id);

            // Prevent tampering with store_id on update
            if ($payment && $this->filled('store_id') && $this->store_id != $payment->store_id) {
                $validator->errors()->add('store_id', 'لا يمكن تغيير الدكانة بعد إنشاء الحركة المالية.');
                return;
            }

            // 1. Cross-Tenant Validation: Supplier must belong to effective store
            if ($this->store_supplier_id && $effectiveStoreId) {
                $supplier = StoreSupplier::where('id', $this->store_supplier_id)->where('store_id', $effectiveStoreId)->first();
                if (!$supplier) {
                    $validator->errors()->add('store_supplier_id', 'المورد المحدد لا ينتمي إلى الدكانة المختارة.');
                }
            }

            // 2. Cross-Tenant Validation: Bank Account must belong to effective store
            if ($this->store_bank_account_id && $effectiveStoreId) {
                $bankAccount = StoreBankAccount::where('id', $this->store_bank_account_id)->where('store_id', $effectiveStoreId)->first();
                if (!$bankAccount) {
                    $validator->errors()->add('store_bank_account_id', 'الحساب البنكي المحدد لا ينتمي إلى الدكانة المختارة.');
                }
            }

            // 3. Cross-Tenant Validation: Invoice must belong to effective store and supplier
            if ($this->store_supplier_invoice_id && $effectiveStoreId) {
                $invoice = StoreSupplierInvoice::where('id', $this->store_supplier_invoice_id)
                    ->where('store_id', $effectiveStoreId)
                    ->when($this->store_supplier_id, function($q) {
                        return $q->where('store_supplier_id', $this->store_supplier_id);
                    })
                    ->first();
                if (!$invoice) {
                    $validator->errors()->add('store_supplier_invoice_id', 'الفاتورة المحددة غير مطابقة للمورد أو الدكانة.');
                }
            }

            // 4. Bank Account Balance Validation
            if ($this->store_bank_account_id && $this->amount) {
                $bankAccount = StoreBankAccount::find($this->store_bank_account_id);
                if ($bankAccount) {
                    $availableBalance = (float) $bankAccount->current_balance;

                    // If it's an update, add back the old amount of the current payment
                    if ($payment && $payment->store_bank_account_id == $this->store_bank_account_id) {
                        $availableBalance += (float) $payment->amount;
                    }

                    if ((float) $this->amount > $availableBalance) {
                        $validator->errors()->add('amount', __('store_supplier_payments.insufficient_balance', ['balance' => number_format($availableBalance, 2)]));
                    }
                }
            }

            // 5. Invoice Remaining Balance Validation (If invoice is selected)
            if ($this->store_supplier_invoice_id && $this->amount) {
                $invoice = StoreSupplierInvoice::find($this->store_supplier_invoice_id);
                if ($invoice) {
                    $availableInvoiceRemaining = (float) $invoice->remaining_amount;

                    // If it's an update, add back the old amount of the current payment to the remaining amount
                    if ($payment && $payment->store_supplier_invoice_id == $this->store_supplier_invoice_id) {
                        $availableInvoiceRemaining += (float) $payment->amount;
                    }

                    if ((float) $this->amount > $availableInvoiceRemaining) {
                        $validator->errors()->add('amount', __('store_supplier_payments.amount_exceeds_invoice_remaining', [
                            'amount' => number_format((float) $this->amount, 2),
                            'remaining' => number_format($availableInvoiceRemaining, 2)
                        ]));
                    }
                }
            }
        });
    }
}
