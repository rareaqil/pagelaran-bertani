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
            'name' => 'required|string|max:255',
            'content' => 'required|string', // Summernote HTML
            'fruit_type_id'=> 'nullable|exists:fruit_types,id',
            'intro' => 'nullable|string|max:500',
            'type' => 'nullable|string|max:100',
            'status' => 'required|in:draft,published,archived',
            'published_at' => 'nullable|date',
            // Saat update, image boleh kosong dan tidak wajib upload ulang
            'image' => [
                $isUpdate ? 'nullable' : 'required',
                'string',
                'regex:/\.(jpg|jpeg|png)$/i',
                'max:2048', // max 2MB
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama post wajib diisi.',
            'content.required' => 'Konten post wajib diisi.',
            'status.in' => 'Status harus draft, published, atau archived.',
            'image.image' => 'File harus berupa gambar.',
            'image.mimes' => 'Gambar harus berformat jpg, jpeg, atau png.',
            'image.max' => 'Ukuran gambar maksimal 2MB.',
        ];
    }
}
