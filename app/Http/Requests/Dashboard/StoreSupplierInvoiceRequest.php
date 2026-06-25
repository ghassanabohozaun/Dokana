<?php

namespace App\Http\Requests\Dashboard;

use Illuminate\Foundation\Http\FormRequest;

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
            'total_amount' => 'required|numeric|min:0',
            'invoice_date' => 'required|date',
            'notes' => 'nullable|string',
        ];

        if (user()->store_id == 1 || user()->role_id == 1 || user()->id == 1) {
            $rules['store_id'] = 'required|exists:stores,id';
        }

        return $rules;
    }
}
