<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // pastikan sudah pakai middleware auth + role di route
    }

    public function rules(): array
    {
        $userId = $this->route('user') ? $this->route('user')->id : null;

        return [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name'  => ['required', 'string', 'max:255'],
            'email'      => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($userId),
            ],
            'password'   => $userId ? ['nullable', 'string', 'min:6'] : ['required', 'string', 'min:6'],
            'role'       => ['required', Rule::in(['super_admin','admin','user'])],

            // alamat
            'address1'    => ['nullable','string','max:255'],
            'postcode'    => ['nullable','string','max:10'],
            'province_id' => ['nullable','integer'],
            'regency_id'  => ['nullable','integer'],
            'district_id' => ['nullable','integer'],
            'village_id'  => ['nullable','integer'],
        ];
    }

    public function messages(): array
    {
        return [
            'email.unique' => 'Email sudah digunakan.',
            'role.in'      => 'Role harus salah satu dari super_admin, admin, atau user.',
            'password.min' => 'Password minimal 6 karakter.',
        ];
    }
}
