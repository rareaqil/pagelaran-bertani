<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\StockMovement;
use App\Models\Setting;
use App\Http\Controllers\StockMovementController;
use App\Services\MidtransService;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    // Menampilkan semua order
    public function index()
    {
        $orders = Order::with(['user', 'voucher', 'items', 'payment'])->latest()->get();
        return response()->json($orders);
    }

    public function OrderHistory(Request $request)
    {
        $query = Order::with(['voucher', 'items', 'payment'])
        ->where('user_id', auth()->id());

        if ($request->status) {
            $query->where('status', $request->status);
        }

        $orders = $query->latest()->get();

        return view('frontend.order-history', compact('orders'));
    }

    // Membuat order baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id'        => 'required|exists:users,id',
            'total_amount'   => 'required|numeric|min:0',
            'voucher_id'     => 'nullable|exists:vouchers,id',
            'discount_amount'=> 'nullable|numeric|min:0',
        ]);

        $order = Order::create($validated);

        return response()->json([
            'message' => 'Order created successfully',
            'data'    => $order
        ]);
    }

    // Menampilkan detail order
    public function show(Order $order)
    {
        $order->load(['user', 'voucher', 'items', 'payment']);
        return response()->json($order);
    }

    // Update order (misal update status)
    public function update(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status'          => 'in:' . implode(',', Order::statuses()),
            'total_amount'    => 'numeric|min:0',
            'discount_amount' => 'numeric|min:0',
        ]);

        $order->update($validated);

        return response()->json([
            'message' => 'Order updated successfully',
            'data'    => $order
        ]);
    }

    // Hapus order (soft delete)
    public function destroy(Order $order)
    {
        $order->delete();

        return response()->json(['message' => 'Order deleted successfully']);
    }




    public function indexView(Request $request)
    {
        // Bisa ditambahkan pagination & search
        $sort      = $request->query('sort', 'created_at');
        $direction = $request->query('direction', 'desc');
        $perPage   = $request->query('perPage', 10);

        $orders = Order::with(['user','voucher','items','payment'])
            ->orderBy($sort, $direction)
            ->paginate($perPage)
            ->withQueryString();

        return view('backend.orders.index', compact('orders'));
    }



    public function showView(MidtransService $midtransService, Order $order)
    {
        // Load relasi
        $order->load(['user.primaryAddress', 'items.product', 'voucher', 'payment']);

        // Ambil stock hold movement
        $holdMovements = StockMovement::where('reference_type', 'Order')
            ->where('reference_id', $order->order_id)
            ->where('type', 'hold')
            ->get();

        // Hitung subtotal
        $subtotal = $order->items->sum(fn($item) => $item->price * $item->quantity);

        // Hitung discount
        $discountAmount = 0;
        if($order->voucher !== null) $discountAmount = $order->voucher->getDiscountOnly($subtotal);

        //Admin Fee
        $adminFee = $order->admin_fee ?? 2000;

        // Total
        $total = $subtotal - $discountAmount;


        // --- Midtrans Snap Token ---
        $payment = $order->payment;
        if ($payment === null || $payment->status === 'EXPIRED') {
            $midtransData = $midtransService->createSnapToken($order);
            $snapToken = $midtransData['snap_token'];
            // dd($midtransData);
            // Simpan payment baru
            $order->payment()->create([
                'raw_response'  => json_encode($midtransData['params']),
                'snap_token'    => $snapToken,
                'amount'        => $total, // total yang sudah dihitung
                'status'        => 'PENDING',
                'payment_gateway' => 'midtrans',
            ]);
        } else {
            //  $snapToken = $midtransService->createSnapToken($order);

            // dd($snapToken);
            $snapToken = $payment->snap_token;
        }

        // Normalisasi nomor telepon
        $adminPhoneRaw = Setting::getValue('order_contact_whatsapp', '081234567890');
        $adminPhone = $this->normalizePhone($adminPhoneRaw);

        $userPhone = $this->normalizePhone($order->user->phone ?? '');

        $waUrlAdmin = $this->generateWaUrl($adminPhone, $order, 'admin');
        $waUrlUser  = $this->generateWaUrl($userPhone, $order, 'user');


        // Pilih view sesuai role
        $view = auth()->user()->isAdmin()
                ? 'backend.orders.showAdmin'
                : 'backend.orders.showUser';


        return view($view, compact(
            'order',
            'subtotal',
            'discountAmount',
            'total',
            'holdMovements',
            'snapToken',
            'adminFee',
            'waUrlAdmin',
            'waUrlUser'
        ));
    }


    private function generateWaUrl(string $phone, Order $order, string $type): string
            {
                if (!$phone) return '#';

                // Hitung subtotal & total
                $subtotal = $order->items->sum(fn($i) => $i->price * $i->quantity);
                $discount = $order->voucher ? $order->voucher->getDiscountOnly($subtotal) : 0;
                $adminFee = $order->admin_fee ?? 2000;
                $total = $subtotal - $discount + $adminFee;

                // Format Rupiah
                $subtotalFormatted = number_format($subtotal, 0, ',', '.');
                $discountFormatted = number_format($discount, 0, ',', '.');
                $adminFeeFormatted = number_format($adminFee, 0, ',', '.');
                $totalFormatted = number_format($total, 0, ',', '.');

                // Format item list
                $itemsText = "";
                foreach ($order->items as $item) {
                    $itemName = $item->product->name ?? $item->name;
                    $itemPrice = number_format($item->price, 0, ',', '.');
                    $itemsText .= "- {$itemName} x{$item->quantity} (Rp{$itemPrice})\n";
                }

                if ($type === 'admin') {
                    $message = <<<MSG
                    Halo Admin

                    Saya sudah melakukan pembayaran untuk Order #{$order->order_id}.

                    Nama Pemesan: {$order->user->first_name}
                    Email: {$order->user->email }
                    No Telp: {$order->user->phone}
                    Alamat: {$order->user->primaryAddress->address1}

                    Pesanan:
                    $itemsText
                    Subtotal: Rp$subtotalFormatted
                    Discount: -Rp$discountFormatted
                    Admin Fee: Rp$adminFeeFormatted
                    Total: Rp$totalFormatted

                    Mohon konfirmasi pesanan saya. Terima kasih!
                    MSG;
                } else { // user
                    $message = <<<MSG
                    Halo {$order->user->first_name},

                    Pesanan #{$order->order_id} Anda telah dikonfirmasi oleh Admin.

                    Pesanan Anda:
                    $itemsText
                    Subtotal: Rp$subtotalFormatted
                    Discount: -Rp$discountFormatted
                    Admin Fee: Rp$adminFeeFormatted
                    Total: Rp$totalFormatted

                    Terima kasih telah berbelanja di kami!
                    MSG;
                }

        return "https://wa.me/{$phone}?text=" . urlencode($message);
    }


    private function normalizePhone(string $phone): string
    {
        // Hapus karakter non-digit
        $phone = preg_replace('/\D+/', '', $phone);

        // Jika diawali 0 → ganti dengan 62
        if (str_starts_with($phone, '0')) {
            $phone = '62' . substr($phone, 1);
        }

        // Jika diawali 620 → kemungkinan double 62
        if (str_starts_with($phone, '620')) {
            $phone = '62' . substr($phone, 2);
        }

        return $phone;
    }


   public function setShipment(Request $request, Order $order)
   {
        $data = $request->validate([
            'scheduled_at'     => ['required', 'date'],
            'estimate_minutes' => ['required', 'integer', 'min:1'],
            'tracking_link'    => ['nullable', 'url'],
            'courier'          => ['nullable', 'string', 'max:255'],
        ]);

        // Pastikan tipe data integer
        $estimateMinutes = (int) $data['estimate_minutes'];

        // Konversi jadwal kirim menjadi instance Carbon
        $scheduledAt = Carbon::parse($data['scheduled_at']);

        // Hitung perkiraan waktu tiba
        $estimatedArrival = $scheduledAt->copy()->addMinutes($estimateMinutes);

        $order->update([
            'scheduled_at'      => $scheduledAt,
            'estimate_minutes'  => $estimateMinutes,
            'tracking_link'     => $data['tracking_link'] ?? null,
            'courier'           => $data['courier'] ?? null,
            'estimated_arrival' => $estimatedArrival,
            'status'            => OrderStatus::Shipment->value
        ]);

        return back()->with('success', 'Detail pengiriman berhasil disimpan.');
    }


     public function orderReversal(Order $order){
         // Ubah status order menjadi cancelled
        $order->update(['status' => OrderStatus::Cancelled->value]);

        // Ambil semua hold yang terkait order ini
        $holds = StockMovement::where('reference_type', 'Order')
            ->where('reference_id', $order->order_id)
            ->where('type', 'hold')
            ->get();

        // Instansiasi controller StockMovementController
        $stockCtrl = app(StockMovementController::class);

        // Panggil cancelHold() untuk setiap hold
        foreach ($holds as $hold) {
            // cancelHold() mengembalikan JSON response,
            // kita bisa abaikan return-nya karena kita hanya butuh efeknya
            $stockCtrl->cancelHold($hold->id);
        }

        return back()->with('success', 'Order dibatalkan dan stok yang di-hold telah dilepas.');
     }


     public function confirmReceived(Order $order)
    {
        // Hanya user pemilik pesanan yang boleh konfirmasi atau admin
        abort_unless($order->user_id === auth()->id() || auth()->user()->isAdmin(), 403);

        $order->update(['status' => OrderStatus::Completed->value]);
        return back()->with('success', 'Terima kasih telah mengkonfirmasi penerimaan pesanan.');
    }
}
