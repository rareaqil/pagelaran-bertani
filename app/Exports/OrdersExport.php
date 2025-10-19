<?php

namespace App\Exports;

use App\Models\Order;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class OrdersExport implements FromCollection, WithHeadings
{
    protected $status;
    protected $dateFrom;
    protected $dateTo;

    public function __construct($status = null, $dateFrom = null, $dateTo = null)
    {
        $this->status = $status;
        $this->dateFrom = $dateFrom;
        $this->dateTo = $dateTo;
    }

    public function collection()
    {
        return Order::with(['user', 'voucher'])
            ->when($this->status, fn($query) => $query->where('status', $this->status))
            ->when(
                $this->dateFrom && $this->dateTo,
                fn($query) => $query->whereBetween('created_at', [
                    $this->dateFrom . ' 00:00:00',
                    $this->dateTo . ' 23:59:59',
                ]),
            )
            ->get()
            ->map(
                fn($order) => [
                    'Order ID' => $order->order_id,
                    'Customer' => $order->user?->first_name . ' ' . $order->user?->last_name,
                    'Total Amount' => $order->total_amount,
                    'Status' => $order->status->value ?? $order->status,
                    'Voucher' => $order->voucher?->code ?? '-',
                    'Discount' => $order->discount_amount,
                    'Created At' => $order->created_at->format('Y-m-d H:i'),
                    'Updated At' => $order->updated_at->format('Y-m-d H:i'),
                ],
            );
    }

    public function headings(): array
    {
        return ['Order ID', 'Customer', 'Total Amount', 'Status', 'Voucher', 'Discount', 'Created At', 'Updated At'];
    }
}
