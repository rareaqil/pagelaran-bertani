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
        $isUpdate  = $this->isMethod('put') || $this->isMethod('patch');
        $productId = $this->route('product')?->id;

        return [
            'name'          => 'required|string|max:255',
            'description'   => 'nullable|string',
            'price'         => 'required|numeric|min:0',
            'stock'         => 'required|integer|min:0',
            'status_active' => 'required|boolean',
            'weight'        => 'nullable|numeric|min:0',

            // SKU unik, abaikan milik produk yang sedang di-update
            'sku' => 'nullable|string|max:100|unique:products,sku'
                    . ($isUpdate ? ',' . $productId : ''),

            // sekarang "image" adalah STRING berisi beberapa URL dipisah koma
            'image' => [
                $isUpdate ? 'nullable' : 'required',
                function ($attribute, $value, $fail) {
                    $urls = array_filter(array_map('trim', explode(',', (string) $value)));

                    if (count($urls) > 5) {
                        $fail('Maksimal 5 URL gambar yang diperbolehkan.');
                        return;
                    }

                   foreach ($urls as $url) {
                        // Pisahkan bagian scheme/host dan path
                        $parts = parse_url($url);
                        if (! $parts || empty($parts['scheme']) || empty($parts['host'])) {
                            $fail("URL tidak valid: {$url}");
                            return;
                        }

                        // Encode path agar spasi dsb tidak bikin gagal
                        $path     = isset($parts['path']) ? implode('/', array_map('rawurlencode', explode('/', $parts['path']))) : '';
                        $rebuilt  = $parts['scheme'].'://'.$parts['host'].($path ? $path : '');

                        if (! filter_var($rebuilt, FILTER_VALIDATE_URL)) {
                            $fail("URL tidak valid: {$url}");
                            return;
                        }

                        if (! preg_match('/\.(jpe?g|png)$/i', $parts['path'] ?? '')) {
                            $fail("Gambar harus berformat jpg, jpeg, atau png: {$url}");
                            return;
                        }
                    }

                },
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'   => 'Nama produk wajib diisi.',
            'price.required'  => 'Harga produk wajib diisi.',
            'stock.required'  => 'Stok produk wajib diisi.',
            'status_active.required' => 'Status aktif wajib diisi.',
            'image.required'  => 'Daftar URL gambar wajib diisi.',
            'sku.unique'      => 'SKU sudah terpakai.',
        ];
    }
}
