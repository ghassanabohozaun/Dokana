<?php

namespace App\Http\Requests\Dashboard;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('user');

        $rules = [
            'name.ar' => 'required|string|max:255',
            'name.en' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => $id ? 'nullable|min:6' : 'required|min:6',
            'password_confirm' => $id ? 'nullable|same:password' : 'required|same:password',
            'role_id' => 'required|exists:roles,id',
            'mobile' => 'required|string|max:10|unique:users,mobile,' . $id,
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'delete_photo' => 'nullable',
        ];

        if (user()->store_id == 1) {
            $rules['store_id'] = 'required|exists:stores,id';
        }

        return $rules;
    }
}
