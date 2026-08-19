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
            'phone' => 'nullable|digits:10',
            'logo' => 'nullable|file|mimes:jpeg,png,jpg,webp,svg,gif,bmp,avif,ico|max:5120',
            'delete_logo' => 'nullable',
        ];
    }

}
