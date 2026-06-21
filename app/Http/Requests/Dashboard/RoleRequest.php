<?php

namespace App\Http\Requests\Dashboard;

use Illuminate\Foundation\Http\FormRequest;

class RoleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name.ar' => 'required|string|max:255',
            'name.en' => 'required|string|max:255',
            'description' => 'nullable|string',
            'store_id' => 'nullable|exists:stores,id',
            'permissions' => 'required|array',
            'permissions.*' => 'exists:permissions,name',
        ];
    }
}
