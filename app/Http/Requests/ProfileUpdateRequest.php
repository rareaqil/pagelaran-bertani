<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name'  => ['required', 'string', 'max:255'],
            'email'      => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($this->user()->id),
            ],
            'phone'      => ['nullable', 'string', 'max:20'],
            'address1'   => ['nullable', 'string', 'max:255'],
            'address2'   => ['nullable', 'string', 'max:255'],
            'province_id'=> ['nullable', 'integer'],
            'city_id'    => ['nullable', 'integer'],
            'district_id'=> ['nullable', 'integer'],
            'village_id' => ['nullable', 'integer'],
        ];
    }
}
