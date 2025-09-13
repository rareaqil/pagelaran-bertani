<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PostRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // ubah sesuai kebutuhan auth
    }

    public function rules(): array
    {
        // Jika metode PUT/PATCH artinya update
        $isUpdate = $this->isMethod('put') || $this->isMethod('patch');

        return [
            'title' => 'required|string|max:255',
            'body'  => 'required|string',
            // Saat update, image boleh kosong dan tidak wajib upload ulang
            'image' => [
                $isUpdate ? 'nullable' : 'required',
                'image',
                'mimes:jpg,jpeg,png',
                'max:2048',
            ],
        ];
    }
}
