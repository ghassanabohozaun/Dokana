<?php

namespace App\Http\Requests\Dashboard;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('store') ?? $this->id;
        
        return [
            'name.ar' => 'required|string|max:255',
            'name.en' => 'required|string|max:255',
            'subscription_plan' => 'required|string',
            'status' => 'required|in:active,inactive',
            'email' => 'nullable|email|max:255',
            'logo' => $this->isMethod('POST') ? 'nullable|image|mimes:jpeg,png,jpg|max:2048' : 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'delete_logo' => 'nullable',
        ];
    }

}
