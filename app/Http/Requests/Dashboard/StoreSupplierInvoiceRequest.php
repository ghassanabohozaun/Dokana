<?php

namespace App\Http\Requests\Dashboard;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\StoreSupplier;
use App\Models\StoreSupplierInvoice;

class StoreSupplierInvoiceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'store_supplier_id' => 'required|exists:store_suppliers,id',
            'invoice_number' => 'required|string|max:255',
            'total_amount' => 'required|numeric|min:0.01',
            'invoice_date' => 'required|date',
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
            $invoiceId = $this->route('store_supplier_invoice') ?? $this->route('id') ?? $this->id;
            $invoice = $invoiceId ? StoreSupplierInvoice::find($invoiceId) : null;

            $effectiveStoreId = $invoice ? $invoice->store_id : ($this->store_id ?? user()->store_id);

            if ($invoice && $this->filled('store_id') && $this->store_id != $invoice->store_id) {
                $validator->errors()->add('store_id', 'لا يمكن تغيير الدكانة بعد إنشاء الفاتورة.');
                return;
            }

            if ($this->store_supplier_id && $effectiveStoreId) {
                $supplier = StoreSupplier::where('id', $this->store_supplier_id)->where('store_id', $effectiveStoreId)->first();
                if (!$supplier) {
                    $validator->errors()->add('store_supplier_id', 'المورد المحدد لا ينتمي إلى الدكانة المختارة.');
                }
            }

            // On update, total_amount cannot be less than already paid amount
            if ($invoice && $this->total_amount) {
                if ((float) $this->total_amount < (float) $invoice->paid_amount) {
                    $validator->errors()->add('total_amount', 'لا يمكن تعديل قيمة الفاتورة لتصبح أقل من المبالغ المسددة مسبقاً (' . number_format($invoice->paid_amount, 2) . ').');
                }
            }
        });
    }
}
