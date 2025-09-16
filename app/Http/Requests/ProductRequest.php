<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $isUpdate = $this->isMethod('put') || $this->isMethod('patch');

        return [
            'name'          => 'required|string|max:255',
            'description'   => 'nullable|string',
            'price'         => 'required|numeric|min:0',
            'stock'         => 'required|integer|min:0',
            'status_active' => 'required|boolean',
            'weight'        => 'nullable|numeric|min:0',
            'sku'           => 'nullable|string|max:100|unique:products,sku' . ($isUpdate ? ',' . $this->route('product')->id : ''),
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
            'name.required' => 'Nama produk wajib diisi.',
            'price.required' => 'Harga produk wajib diisi.',
            'stock.required' => 'Stok produk wajib diisi.',
           'image.image' => 'File harus berupa gambar.',
            'image.mimes' => 'Gambar harus berformat jpg, jpeg, atau png.',
            'image.max' => 'Ukuran gambar maksimal 2MB.',

        ];
    }
}
