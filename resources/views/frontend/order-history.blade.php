@extends('frontend.layouts.main')

@section('content')
    <div class="mx-auto max-w-6xl space-y-6 px-6 py-10 md:px-12">
        <h2 class="mb-6 text-2xl font-bold text-green-700">Riwayat Pesanan</h2>

        {{-- Filter Status --}}
        <div class="mb-6">
            <form method="GET" action="{{ route('order.history') }}" id="filterForm" class="flex items-center gap-3">
                <label for="status" class="font-medium text-gray-700">Filter Status:</label>
                <select
                    name="status"
                    id="status"
                    class="rounded-lg border px-3 py-2"
                    onchange="document.getElementById('filterForm').submit()"
                >
                    <option value="">Semua</option>
                    @foreach (\App\Models\OrderStatus::cases() as $status)
                        <option
                            value="{{ $status->value }}"
                            {{ request('status') === $status->value ? 'selected' : '' }}
                        >
                            {{ $status->value }}
                        </option>
                    @endforeach
                </select>
            </form>
        </div>

        {{-- Loop Pesanan --}}
        @forelse ($orders as $order)
            <div class="space-y-4 rounded-lg border bg-white p-6 shadow" x-data="{ open: false }">
                {{-- Header Pesanan --}}
                <div class="flex items-center justify-between">
                    <p class="text-sm text-gray-500">
                        Order ID:
                        <span class="font-medium">#{{ $order->id }}</span>
                    </p>
                    <p class="{{ $order->status->color() }} text-sm font-semibold">
                        Status: {{ $order->status->value }}
                    </p>
                </div>

                {{-- Detail Transaksi --}}
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <p>
                        Total:
                        <span class="font-semibold">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                    </p>
                    <p>
                        Voucher:
                        <span class="font-semibold">{{ $order->voucher_id ?? '-' }}</span>
                    </p>
                    <p>
                        Diskon:
                        <span class="font-semibold">Rp {{ number_format($order->discount_amount, 0, ',', '.') }}</span>
                    </p>
                    <p>
                        Biaya Admin:
                        <span class="font-semibold">Rp {{ number_format($order->admin_fee, 0, ',', '.') }}</span>
                    </p>
                </div>

                {{-- Informasi Pengiriman (Expandable) --}}
                <div class="border-t pt-4">
                    <button @click="open = !open" class="flex w-full items-center justify-between text-left">
                        <h4 class="font-semibold text-gray-700">Informasi Pengiriman</h4>
                        <span x-text="open ? '▲' : '▼'" class="text-gray-500"></span>
                    </button>

                    <div x-show="open" x-collapse class="mt-3 grid grid-cols-2 gap-4 text-sm">
                        <p>
                            Dijadwalkan:
                            <span class="font-semibold">{{ $order->scheduled_at }}</span>
                        </p>
                        <p>
                            Estimasi (menit):
                            <span class="font-semibold">{{ $order->estimate_minutes }}</span>
                        </p>
                        <p>
                            Kurir:
                            <span class="font-semibold">{{ $order->courier }}</span>
                        </p>
                        <p>
                            Estimasi Tiba:
                            <span class="font-semibold">{{ $order->estimated_arrival }}</span>
                        </p>
                        <p class="col-span-2">
                            Tracking:

                            @if ($order->tracking_link)
                                <a
                                    href="{{ $order->tracking_link }}"
                                    target="_blank"
                                    class="text-blue-600 hover:underline"
                                >
                                    Klik di sini
                                </a>
                            @else
                                <span class="text-gray-500">Tidak tersedia</span>
                            @endif
                        </p>
                    </div>
                </div>

                {{-- Tombol Aksi --}}
                <div class="flex flex-wrap justify-end gap-2 border-t pt-4">
                    <a
                        href="{{ auth()->user()->isAdmin() ? route('orders.showView', $order->order_id) : route('orders.showUserView', $order->order_id) }}"
                        class="rounded-lg bg-green-600 px-4 py-2 font-medium text-white hover:bg-green-700"
                    >
                        Lihat Detail
                    </a>
                </div>
            </div>
        @empty
            <p class="text-gray-500">Belum ada riwayat pesanan.</p>
        @endforelse
    </div>
@endsection
