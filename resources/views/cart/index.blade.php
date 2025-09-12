<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">Cart</h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white p-6 shadow-sm sm:rounded-lg">
                {{-- Add Product --}}
                <h3 class="mb-2 font-bold">Add Product</h3>
                <div class="mb-4 grid grid-cols-1 gap-2 md:grid-cols-2">
                    @foreach ($products as $product)
                        <div class="flex items-center gap-2 rounded border p-2">
                            <span class="flex-1">
                                {{ $product->name }} - {{ number_format($product->price, 0, ',', '.') }}
                            </span>
                            <button
                                type="button"
                                onclick="changeQty({{ $product->id }}, -1)"
                                class="rounded bg-gray-300 px-2 py-1"
                            >
                                -
                            </button>
                            <input
                                type="number"
                                id="qty-{{ $product->id }}"
                                value="1"
                                min="1"
                                class="w-16 rounded border text-center"
                            />
                            <button
                                type="button"
                                onclick="changeQty({{ $product->id }}, 1)"
                                class="rounded bg-gray-300 px-2 py-1"
                            >
                                +
                            </button>
                            <button
                                type="button"
                                onclick="addToCart({{ $product->id }})"
                                class="rounded bg-blue-500 px-3 py-1 text-white"
                            >
                                Add
                            </button>
                        </div>
                    @endforeach
                </div>

                {{-- Cart Table --}}
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
                    <tbody id="cart-items">
                        @foreach ($items as $item)
                            <tr id="cart-item-{{ $item->id }}">
                                <td class="border px-4 py-2">{{ $item->itemable->name }}</td>
                                <td class="border px-4 py-2">
                                    {{ number_format($item->itemable->getPrice(), 0, ',', '.') }}
                                </td>
                                <td class="flex items-center gap-2 border px-4 py-2">
                                    <button type="button" class="btn-decrease" data-id="{{ $item->id }}">-</button>
                                    <input
                                        type="number"
                                        value="{{ $item->quantity }}"
                                        min="1"
                                        class="qty-input w-12 text-center"
                                        data-id="{{ $item->id }}"
                                    />
                                    <button type="button" class="btn-increase" data-id="{{ $item->id }}">+</button>
                                </td>

                                <td class="border px-4 py-2">
                                    {{ number_format($item->itemable->getPrice() * $item->quantity * (1 - $item->discount), 0, ',', '.') }}
                                </td>
                                <td class="border px-4 py-2">
                                    <button
                                        class="remove-item rounded bg-red-500 px-2 py-1 text-white"
                                        data-id="{{ $item->id }}"
                                    >
                                        Remove
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="mt-4 text-right font-bold">
                    Total:
                    <span id="cart-total">{{ number_format($total, 0, ',', '.') }}</span>
                </div>

                {{-- Apply Coupon --}}
                <div class="mt-4 flex gap-2">
                    <input
                        type="text"
                        id="coupon"
                        placeholder="Discount (0.1 = 10%)"
                        class="rounded border px-2 py-1"
                    />
                    <button type="button" id="apply-coupon" class="rounded bg-blue-500 px-2 py-1 text-white">
                        Apply Coupon
                    </button>
                </div>

                {{-- Clear Cart --}}
                <div class="mt-4">
                    <button type="button" id="clear-cart" class="rounded bg-gray-700 px-4 py-2 text-white">
                        Clear Cart
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Scripts --}}
    @push('scripts')
        <script type="module">
            $(function () {
                function updateCartDOM(cart) {
                    let tbody = $('#cart-items');
                    tbody.empty();
                    let total = 0;
                    cart.items.forEach((item) => {
                        let subtotal = item.price * item.quantity * (1 - item.discount);
                        total += subtotal;
                        tbody.append(`
                    <tr id="cart-item-${item.id}">
                        <td class="border px-4 py-2">${item.name}</td>
                        <td class="border px-4 py-2">${item.price.toLocaleString()}</td>
                        <td class="border px-4 py-2">${item.quantity}</td>
                        <td class="border px-4 py-2">${subtotal.toLocaleString()}</td>
                        <td class="border px-4 py-2">
                            <button class="remove-item px-2 py-1 bg-red-500 text-white rounded" data-id="${item.id}">Remove</button>
                        </td>
                    </tr>
                `);
                    });
                    $('#cart-total').text(total.toLocaleString());
                }

                window.changeQty = function (productId, delta) {
                    const qtyInput = $(`#qty-${productId}`);
                    let newQty = parseInt(qtyInput.val()) + delta;
                    if (newQty < 1) newQty = 1;
                    qtyInput.val(newQty);
                };

                window.addToCart = function (productId) {
                    const qty = parseInt($(`#qty-${productId}`).val()) || 1;
                    const token = '{{ csrf_token() }}';
                    $.ajax({
                        url: '{{ route('cart.add') }}',
                        type: 'POST',
                        data: JSON.stringify({ id: productId, quantity: qty }),
                        headers: { 'X-CSRF-TOKEN': token },
                        contentType: 'application/json',
                        success: function (res) {
                            if (res.success) updateCartDOM(res.cart);
                        },
                    });
                };

                $(document).on('click', '.remove-item', function () {
                    const id = $(this).data('id');
                    const token = '{{ csrf_token() }}';
                    $.ajax({
                        url: `/cart/item/remove/${id}`,
                        type: 'DELETE',
                        headers: { 'X-CSRF-TOKEN': token },
                        success: function (res) {
                            if (res.success) updateCartDOM(res.cart);
                        },
                    });
                });

                $('#apply-coupon').click(function () {
                    const discount = parseFloat($('#coupon').val()) || 0;
                    const token = '{{ csrf_token() }}';
                    $.ajax({
                        url: '{{ route('cart.coupon') }}',
                        type: 'POST',
                        data: JSON.stringify({ discount }),
                        headers: { 'X-CSRF-TOKEN': token },
                        contentType: 'application/json',
                        success: function (res) {
                            if (res.success) updateCartDOM(res.cart);
                        },
                    });
                });

                $('#clear-cart').click(function () {
                    const token = '{{ csrf_token() }}';
                    $.ajax({
                        url: '{{ route('cart.clear') }}',
                        type: 'DELETE',
                        headers: { 'X-CSRF-TOKEN': token },
                        success: function (res) {
                            if (res.success) updateCartDOM(res.cart);
                        },
                    });
                });
            });
        </script>
    @endpush
</x-app-layout>
