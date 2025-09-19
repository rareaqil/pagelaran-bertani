<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">Order #{{ $order->order_id }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white p-6 shadow-sm sm:rounded-lg">
                {{-- Order Info --}}
                <div class="mb-6 grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <p>
                            <strong>Order ID:</strong>
                            {{ $order->order_id }}
                        </p>
                        <p>
                            <strong>Tanggal:</strong>
                            {{ $order->created_at->format('d-m-Y H:i') }}
                        </p>
                        <p>
                            <strong>Status:</strong>
                            {{ ucfirst($order->status) }}
                        </p>
                    </div>
                    <div>
                        <p>
                            <strong>Nama Pemesan:</strong>
                            {{ $order->user->first_name ?? '-' }}
                        </p>
                        <p>
                            <strong>Email:</strong>
                            {{ $order->user->email ?? '-' }}
                        </p>
                        <p>
                            <strong>No Telp:</strong>
                            {{ $order->user->phone ?? '-' }}
                        </p>
                        <p>
                            <strong>Alamat:</strong>
                            {{ $order->user->primaryAddress->address1 ?? '-' }}
                        </p>
                    </div>
                </div>

                {{-- Items Table --}}
                <div class="overflow-x-auto">
                    <table class="w-full table-auto border-collapse border border-gray-200">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="border px-4 py-2 text-left">Produk</th>
                                <th class="border px-4 py-2 text-center">Qty</th>
                                <th class="border px-4 py-2 text-right">Harga</th>
                                <th class="border px-4 py-2 text-right">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($order->items as $item)
                                <tr>
                                    <td class="border px-4 py-2">{{ $item->product->name ?? $item->name }}</td>
                                    <td class="border px-4 py-2 text-center">{{ $item->quantity }}</td>
                                    <td class="border px-4 py-2 text-right">
                                        {{ number_format($item->price, 0, ',', '.') }}
                                    </td>
                                    <td class="border px-4 py-2 text-right">
                                        {{ number_format($item->price * $item->quantity, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="font-bold">
                                <td colspan="3" class="border px-4 py-2 text-right">Subtotal</td>
                                <td class="border px-4 py-2 text-right">
                                    {{ number_format($subtotal, 0, ',', '.') }}
                                </td>
                            </tr>

                            @if ($order->voucher)
                                <tr class="text-green-700">
                                    <td colspan="3" class="border px-4 py-2 text-right">
                                        Discount (Voucher: {{ $order->voucher->code }})
                                        @if ($order->voucher->type === 'percentage')
                                            ({{ $order->voucher->value }}%)
                                        @endif
                                    </td>
                                    <td class="border px-4 py-2 text-right">
                                        -{{ number_format($discountAmount, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @endif

                            <tr class="font-bold">
                                <td colspan="3" class="border px-4 py-2 text-right">Total</td>
                                <td class="border px-4 py-2 text-right">{{ number_format($total, 0, ',', '.') }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                {{-- Back button --}}
                <div class="mt-6">
                    <a href="{{ url()->previous() }}" class="rounded bg-gray-300 px-4 py-2 hover:bg-gray-400">
                        Kembali
                    </a>
                </div>
                <div class="mt-6">
                    @foreach ($holdMovements as $movement)
                        <form
                            action="{{ route('stock.confirmPayment', $movement->id) }}"
                            method="POST"
                            style="display: inline"
                        >
                            @csrf
                            <button type="submit" class="btn btn-success">
                                Confirm Payment ({{ $movement->quantity }} pcs)
                            </button>
                        </form>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
