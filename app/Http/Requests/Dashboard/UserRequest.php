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
        $userParam = $this->route('user');
        $id = $userParam instanceof \App\Models\User ? $userParam->id : ($userParam ?? $this->id);

        $rules = [
            'name.ar' => 'required|string|max:255',
            'name.en' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => $id ? 'nullable|min:6' : 'required|min:6',
            'password_confirm' => $id ? 'nullable|required_with:password|same:password' : 'required|same:password',
            'password_confirmation' => $id ? 'nullable|same:password' : 'nullable|same:password',
            'role_id' => 'required|exists:roles,id',
            'mobile' => 'required|string|max:10|unique:users,mobile,' . $id,
            'status' => 'nullable|in:0,1',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'delete_photo' => 'nullable',
        ];

        if (user()->store_id == 1) {
            $rules['store_id'] = 'required|exists:stores,id';
        }

        return $rules;
    }
}
