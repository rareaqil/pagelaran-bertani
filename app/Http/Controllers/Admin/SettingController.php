<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;


class SettingController extends Controller
{
    protected $sections = [
        'General' => [
            'admin_fee' => [
                'description' => 'Biaya admin yang diterapkan untuk setiap transaksi (Rp).',
                'rules' => 'required|numeric|min:0',
            ],
            'order_contact_whatsapp' => [
                'description' => 'Nomor WhatsApp untuk Order (gunakan format internasional, misal: 6281234567890)',
                'rules' => 'required|string',
            ],
        ],
        'Contact Us' => [
            'contact_email' => [
                'description' => 'Alamat email untuk kontak',
                'rules' => 'required|email',
            ],
            'contact_whatsapp' => [
                'description' => 'Nomor WhatsApp untuk kontak',
                'rules' => 'required|string',
            ],
        ],
        'Midtrans' => [
            'midtrans_merchant_id' => [
                'description' => 'Merchant ID Midtrans',
                'rules' => 'string', // validasi wajib hanya jika is_production=true
            ],
            'midtrans_client_key' => [
                'description' => 'Client Key Midtrans',
                'rules' => 'required|string',
            ],
            'midtrans_server_key' => [
                'description' => 'Server Key Midtrans',
                'rules' => 'string', // validasi wajib hanya jika is_production=true
            ],
            'midtrans_is_production' => [
                'description' => 'Apakah menggunakan mode produksi?',
                'rules' => 'required|boolean',
            ],
        ],
    ];

    public function index()
    {
        $allKeys = [];
        foreach ($this->sections as $section) {
            $allKeys = array_merge($allKeys, array_keys($section));
        }

        $settings = Setting::whereIn('key', $allKeys)->get()->keyBy('key');

        return view('backend.settings.index', [
            'sections' => $this->sections,
            'settings' => $settings,
        ]);
    }

    public function update(Request $request)
    {
        $rules = [];

        foreach ($this->sections as $section) {
            foreach ($section as $key => $config) {
                // Kondisional untuk Midtrans production
                if (in_array($key, ['midtrans_merchant_id','midtrans_server_key','midtrans_client_key'])) {
                    $rules[$key] = function ($attribute, $value, $fail) use ($request) {
                        if ($request->input('midtrans_is_production') && (!$value || trim($value) === '')) {
                            $fail("Field {$attribute} wajib diisi jika mode produksi aktif.");
                        }
                    };
                } else {
                    $rules[$key] = $config['rules'];
                }
            }
        }

        $validated = $request->validate($rules);

        foreach ($validated as $key => $value) {
            // Skip null atau kosong
            if ($value === null || trim($value) === '') continue;
            Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }

        return redirect()->back()->with('success', 'Settings berhasil diperbarui.');
    }
}
