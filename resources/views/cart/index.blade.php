<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">Cart</h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-4 rounded bg-green-200 p-4 text-green-800">
                    {{ session('success') }}
                </div>
            @endif

            <div class="overflow-hidden bg-white p-6 shadow-sm sm:rounded-lg">
                <h3 class="mb-2 font-bold">Add Product</h3>
                <form action="{{ route('cart.add') }}" method="POST" class="mb-4 flex gap-2">
                    @csrf
                    <select name="id" class="rounded border px-2 py-1">
                        @foreach ($products as $product)
                            <option value="{{ $product->id }}">
                                {{ $product->name }} - {{ number_format($product->price, 0, ',', '.') }}
                            </option>
                        @endforeach
                    </select>
                    <input type="number" name="quantity" value="1" min="1" class="rounded border px-2 py-1" />
                    <button type="submit" class="rounded bg-blue-500 px-4 py-1 text-white">Add to Cart</button>
                </form>

                @if ($items->isEmpty())
                    <p>Your cart is empty.</p>
                @else
                    <table class="w-full table-auto border-collapse">
                        <thead>
                            <tr>
                                <th class="border px-4 py-2">Product</th>
                                <th class="border px-4 py-2">Price</th>
                                <th class="border px-4 py-2">Qty</th>
                                <th class="border px-4 py-2">Subtotal</th>
                                <th class="border px-4 py-2">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($items as $item)
                                <tr>
                                    <td class="border px-4 py-2">{{ $item->itemable->name }}</td>
                                    <td class="border px-4 py-2">
                                        {{ number_format($item->itemable->getPrice(), 0, ',', '.') }}
                                    </td>
                                    <td class="border px-4 py-2">{{ $item->quantity }}</td>
                                    <td class="border px-4 py-2">
                                        {{ number_format($item->itemable->getPrice() * $item->quantity * (1 - $item->discount), 0, ',', '.') }}
                                    </td>
                                    <td class="border px-4 py-2">
                                        <form method="POST" action="{{ route('cart.item.remove', $item->id) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="rounded bg-red-500 px-2 py-1 text-white">
                                                Remove
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div class="mt-4 text-right font-bold">Total: {{ number_format($total, 0, ',', '.') }}</div>

                    <div class="mt-4">
                        <form method="POST" action="{{ route('cart.coupon') }}" class="flex gap-2">
                            @csrf
                            <input
                                type="text"
                                name="discount"
                                placeholder="Discount (0.1 = 10%)"
                                class="rounded border px-2 py-1"
                            />
                            <button type="submit" class="rounded bg-blue-500 px-2 py-1 text-white">Apply Coupon</button>
                        </form>
                    </div>

                    <form method="POST" action="{{ route('cart.clear') }}" class="mt-4">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="rounded bg-gray-700 px-4 py-2 text-white">Clear Cart</button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
