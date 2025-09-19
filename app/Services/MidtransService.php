<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use App\Models\Order;
use Exception;
use Midtrans\Config;
use Midtrans\Notification;
use Midtrans\Snap;

class MidtransService
{
    protected string $serverKey;
    protected string $isProduction;
    protected string $isSanitized;
    protected string $is3ds;

    /**
     * MidtransService constructor.
     *
     * Menyiapkan konfigurasi Midtrans berdasarkan pengaturan yang ada di file konfigurasi.
     */
    public function __construct()
    {
        // Konfigurasi server key, environment, dan lainnya
        $this->serverKey = config('midtrans.server_key');
        $this->isProduction = config('midtrans.is_production');
        $this->isSanitized = config('midtrans.is_sanitized');
        $this->is3ds = config('midtrans.is_3ds');

        // Mengatur konfigurasi global Midtrans
        Config::$serverKey = $this->serverKey;
        Config::$isProduction = $this->isProduction;
        Config::$isSanitized = $this->isSanitized;
        Config::$is3ds = $this->is3ds;
    }


    public function notification(): Notification
    {
        return new Notification();
    }

    public function isSignatureValid(Notification $n): bool
    {


        $localKey = hash('sha512',
            $n->order_id.$n->status_code.$n->gross_amount.$this->serverKey
        );
         Log::info('Signature debug', [
            'concat'   => $n->order_id.$n->status_code.$n->gross_amount.$this->serverKey,
            'expected' => $localKey,
            'provided' => $n->signature_key,
        ]);
        return hash_equals($localKey, $n->signature_key);
    }

    public function mapStatus(Notification $n): string
    {
        return match ($n->transaction_status) {
            'capture'    => ($n->fraud_status === 'accept') ? 'success' : 'pending',
            'settlement' => 'success',
            'pending'    => 'pending',
            'deny'       => 'failed',
            'cancel'     => 'cancel',
            'expire'     => 'expire',
            'failure'    => 'failed',
            'refund'     => 'refund',
            'partial_refund' => 'partial_refund',
            'authorize'  => 'authorize',
            default      => 'unknown',
        };
    }

    /**
     * Membuat snap token untuk transaksi berdasarkan data order.
     *
     * @param Order $order Objek order yang berisi informasi transaksi.
     *
     * @return string Snap token yang dapat digunakan di front-end untuk proses pembayaran.
     * @throws Exception Jika terjadi kesalahan saat menghasilkan snap token.
     */
    public function createSnapToken(Order $order): array
    {
        // data transaksi
        $params = [
            'transaction_details' => [
                'order_id' => $order->order_id,
                'gross_amount' => $this->getTotalAmount($order, 2000),
            ],
            'item_details' => $this->mapItemsToDetails($order),
            'customer_details' => $this->getCustomerDetails($order),
        ];

        // dd($params);

        try {
        // Membuat snap token
        $snapToken = Snap::getSnapToken($params);

        // Kembalikan token dan params (untuk debug / frontend)
        return [
            'snap_token' => $snapToken,
            'params' => $params,
        ];
        } catch (Exception $e) {
            // Menangani error jika gagal mendapatkan snap token
            throw new Exception($e->getMessage());
        }
    }

    /**
     * Memvalidasi apakah signature key yang diterima dari Midtrans sesuai dengan signature key yang dihitung di server.
     *
     * @return bool Status apakah signature key valid atau tidak.
     */
    public function isSignatureKeyVerified(): bool
    {
        $notification = new Notification();

        // Membuat signature key lokal dari data notifikasi
        $localSignatureKey = hash('sha512',
            $notification->order_id . $notification->status_code .
            $notification->gross_amount . $this->serverKey);

        // Memeriksa apakah signature key valid
        return $localSignatureKey === $notification->signature_key;
    }

    /**
     * Mendapatkan data order berdasarkan order_id yang ada di notifikasi Midtrans.
     *
     * @return Order Objek order yang sesuai dengan order_id yang diterima.
     */
    public function getOrder(): Order
    {
        $notification = new Notification();

        // Mengambil data order dari database berdasarkan order_id
        return Order::where('order_id', $notification->order_id)->first();
    }

    /**
     * Mendapatkan status transaksi berdasarkan status yang diterima dari notifikasi Midtrans.
     *
     * @return string Status transaksi ('success', 'pending', 'expire', 'cancel', 'failed').
     */
    public function getStatus(): string
    {
        $notification = new Notification();
        $transactionStatus = $notification->transaction_status;
        $fraudStatus = $notification->fraud_status;

        return match ($transactionStatus) {
            'capture' => ($fraudStatus == 'accept') ? 'success' : 'pending',
            'settlement' => 'success',
            'deny' => 'failed',
            'cancel' => 'cancel',
            'expire' => 'expire',
            'pending' => 'pending',
            default => 'unknown',
        };
    }

    public function getTotalAmount(Order $order, int $adminFee = 2000): int
    {
        // Hitung subtotal semua item
        $subtotal = $order->items->sum(function ($item) {
            return $item->price * $item->quantity;
        });

        // Hitung diskon voucher
        $discount = 0;
        if ($order->voucher) {
            if ($order->voucher->type === 'percentage') {
                $discount = $subtotal * ($order->voucher->value / 100);
            } else {
                $discount = $order->voucher->value;
            }
        }

        // Total akhir = subtotal - discount + biaya admin, dibulatkan ke integer
        return (int) round($subtotal - $discount + $adminFee);
    }

    /**
     * Memetakan item dalam order menjadi format yang dibutuhkan oleh Midtrans.
     *
     * @param Order $order Objek order yang berisi daftar item.
     * @return array Daftar item yang dipetakan dalam format yang sesuai.
     */
    protected function mapItemsToDetails(Order $order): array
    {
        $items = $order->items()->get()->map(function ($item) {
        return [
                'id' => $item->product->id,          // ID produk
                'price' => (int) $item->price,       // Pastikan integer
                'quantity' => $item->quantity,
                'name' => $item->product->name ?? 'Produk #' . $item->product_id, // Nama produk
            ];
        })->toArray();

        // Tambahkan voucher sebagai item diskon (jika ada)
        if ($order->voucher) {
            $discountAmount = 0;
            if ($order->voucher->type === 'percentage') {
                $subtotal = $order->items->sum(fn($i) => $i->price * $i->quantity);
                $discountAmount = $subtotal * ($order->voucher->value / 100);
            } else {
                $discountAmount = $order->voucher->value;
            }

            if ($discountAmount > 0) {
                $items[] = [
                    'id' => 'voucher-'.$order->voucher->id,
                    'price' => -$discountAmount,
                    'quantity' => 1,
                    'name' => 'Voucher: '.$order->voucher->code,
                ];
            }
        }

        // Tambahkan biaya admin 2000
        $items[] = [
            'id' => 'admin-fee',
            'price' => 2000,
            'quantity' => 1,
            'name' => 'Biaya Admin',
        ];

        return $items;
    }


    /**
     * Mendapatkan informasi customer dari order.
     * Data ini dapat diambil dari relasi dengan tabel lain seperti users atau tabel khusus customer.
     *
     * @param Order $order Objek order yang berisi informasi tentang customer.
     * @return array Data customer yang akan dikirim ke Midtrans.
     */
    // protected function getCustomerDetails(Order $order): array
    // {
    //     // Sesuaikan data customer dengan informasi yang dimiliki oleh aplikasi Anda
    //     return [
    //         'first_name' => 'Nama Customer', // Ganti dengan data nyata
    //         'email' => 'Email@email.com', // Ganti dengan data nyata
    //         'phone' => '081234567890', // Ganti dengan data nyata
    //     ];
    // }

    /**
 * Mendapatkan data customer dari order untuk Midtrans
 *
 * @param Order $order
 * @return array
 */
protected function getCustomerDetails(Order $order): array
{
    return [
        'first_name' => $order->user->first_name ?? 'Customer',
        'last_name'  => $order->user->last_name ?? '',
        'email'      => $order->user->email ?? 'customer@example.com',
        'phone'      => $order->user->phone ?? '081234567890',
        'billing_address' => [
            'first_name' => $order->user->first_name ?? 'Customer',
            'last_name'  => $order->user->last_name ?? '',
            'address'    => $order->user->primaryAddress->address1 ?? '-',
            'city'       => $order->user->primaryAddress->city ?? '-',
            'postal_code'=> $order->user->primaryAddress->postal_code ?? '00000',
            'phone'      => $order->user->phone ?? '081234567890',
            'country_code'=> 'IDN',
        ],
    ];
}

}
