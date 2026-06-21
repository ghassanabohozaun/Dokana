<?php

namespace App\Http\Requests\Dashboard;

use Illuminate\Foundation\Http\FormRequest;

class SettingRequest extends FormRequest
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
        return [
            'site_name.*' => ['required', 'string', 'max:255'],
            'currency_id' => ['nullable', 'exists:currencies,id'],
            'address.*' => ['nullable', 'string'],
            'description.*' => ['nullable', 'string'],
            'keywords.*' => ['nullable', 'string'],
            'phone' => ['nullable', 'string', 'max:255'],
            'mobile' => ['nullable', 'string', 'max:255'],
            'whatsapp' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'string', 'max:255'],
            'email_support' => ['nullable', 'string', 'max:255'],
            'facebook' => ['nullable', 'url', 'max:255'],
            'twitter' => ['nullable', 'url', 'max:255'],
            'instegram' => ['nullable', 'url', 'max:255'],
            'youtube' => ['nullable', 'url', 'max:255'],
            'logo' => ['nullable', 'image', 'mimes:png,jpg,jpeg,gif,pmp,svg,ico,tiff,webp'],
            'favicon' => ['nullable', 'image', 'mimes:png,jpg,jpeg,gif,pmp,svg,ico,tiff,webp'],

            'auth_welcome_title.*' => ['nullable', 'string', 'max:255'],
            'auth_welcome_desc.*' => ['nullable', 'string'],
            'auth_welcome_badge.*' => ['nullable', 'string', 'max:255'],
            'auth_welcome_footer.*' => ['nullable', 'string', 'max:255'],
        ];
    }
}
