<?php

namespace App\Http\Requests\Dashboard;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreBankAccountRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $id = $this->route('bank_account'); // Matches the resource route parameter name
        $storeId = user()->store_id == 1 ? $this->input('store_id') : user()->store_id;

        $rules = [
            'bank_name.ar' => 'required|string|max:255',
            'bank_name.en' => 'required|string|max:255',
            'account_holder_name.ar' => 'required|string|max:255',
            'account_holder_name.en' => 'required|string|max:255',
            'account_number' => [
                'required',
                'digits:13',
                function ($attribute, $value, $fail) use ($id, $storeId) {
                    $bankNameAr = $this->input('bank_name.ar');
                    $bankNameEn = $this->input('bank_name.en');

                    $exists = \DB::table('store_bank_accounts')
                        ->where('store_id', $storeId)
                        ->where('account_number', $value)
                        ->where(function ($query) use ($bankNameAr, $bankNameEn) {
                            $query->where('bank_name->ar', $bankNameAr)
                                  ->orWhere('bank_name->en', $bankNameEn);
                        })
                        ->whereNull('deleted_at')
                        ->when($id, function ($query) use ($id) {
                            $query->where('id', '!=', $id);
                        })
                        ->exists();

                    if ($exists) {
                        $fail(__('validation.unique_bank_account'));
                    }
                }
            ],
            'iban' => ['nullable', 'string', 'max:255', Rule::unique('store_bank_accounts', 'iban')->ignore($id)->whereNull('deleted_at')],
            'is_default' => 'nullable', // Checkbox sends 'on' or nothing
        ];

        // If user is super admin, they must select a store
        if (user()->store_id == 1) {
            $rules['store_id'] = 'required|exists:stores,id';
        }

        return $rules;
    }

}
