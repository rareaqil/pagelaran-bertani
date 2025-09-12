<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">Cart</h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white p-6 shadow-sm sm:rounded-lg">
                {{-- Search Product --}}
                <h3 class="mb-2 font-bold">Search Product</h3>
                <select id="product-search" class="mb-4 w-full rounded border p-2"></select>

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
                                    <button
                                        type="button"
                                        class="btn-decrease rounded bg-gray-300 px-2 py-1"
                                        data-id="{{ $item->id }}"
                                    >
                                        -
                                    </button>
                                    <input
                                        type="number"
                                        value="{{ $item->quantity }}"
                                        min="1"
                                        class="qty-input w-16 rounded border text-center"
                                        data-id="{{ $item->id }}"
                                    />
                                    <button
                                        type="button"
                                        class="btn-increase rounded bg-gray-300 px-2 py-1"
                                        data-id="{{ $item->id }}"
                                    >
                                        +
                                    </button>
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

    @push('scripts')
        <script type="module">
            $(function () {
                // Initialize Select2 for product search
                $('#product-search')
                    .select2({
                        placeholder: 'Search Product',
                        ajax: {
                            url: '/api/products',
                            dataType: 'json',
                            processResults: function (data) {
                                return {
                                    results: data.map((p) => ({ id: p.id, text: p.name + ' - ' + p.price })),
                                };
                            },
                        },
                    })
                    .on('select2:select', function (e) {
                        addToCart(e.params.data.id);
                        $(this).val(null).trigger('change'); // reset after select
                    });

                // Update cart DOM
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
                                <td class="flex items-center gap-2 border px-4 py-2">
                                    <button type="button" class="btn-decrease rounded bg-gray-300 px-2 py-1" data-id="${item.id}">-</button>
                                    <input type="number" value="${item.quantity}" min="1" class="w-16 rounded border text-center qty-input" data-id="${item.id}"/>
                                    <button type="button" class="btn-increase rounded bg-gray-300 px-2 py-1" data-id="${item.id}">+</button>
                                </td>
                                <td class="border px-4 py-2">${subtotal.toLocaleString()}</td>
                                <td class="border px-4 py-2">
                                    <button class="remove-item rounded bg-red-500 px-2 py-1 text-white" data-id="${item.id}">Remove</button>
                                </td>
                            </tr>
                        `);
                    });
                    $('#cart-total').text(total.toLocaleString());
                }

                // Add Product Qty + / -
                window.changeQty = function (productId, delta) {
                    const input = $(`#qty-${productId}`);
                    let val = parseInt(input.val()) + delta;
                    if (val < 1) val = 1;
                    input.val(val);
                };

                // Add to cart
                window.addToCart = function (productId) {
                    const qty = parseInt($(`#qty-${productId}`).val()) || 1;
                    $.ajax({
                        url: '{{ route('cart.add') }}',
                        type: 'POST',
                        data: JSON.stringify({ id: productId, quantity: qty }),
                        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                        contentType: 'application/json',
                        success: function (res) {
                            if (res.success) updateCartDOM(res.cart);
                        },
                    });
                };

                // Remove item
                $(document).on('click', '.remove-item', function () {
                    const id = $(this).data('id');
                    $.ajax({
                        url: `/cart/item/remove/${id}`,
                        type: 'DELETE',
                        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                        success: function (res) {
                            if (res.success) updateCartDOM(res.cart);
                        },
                    });
                });

                // Update qty input
                $(document).on('change', '.qty-input', function () {
                    const id = $(this).data('id');
                    const quantity = parseInt($(this).val());
                    $.ajax({
                        url: `/cart/item/${id}`,
                        type: 'PATCH',
                        data: JSON.stringify({ quantity }),
                        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                        contentType: 'application/json',
                        success: function (res) {
                            if (res.success) updateCartDOM(res.cart);
                        },
                    });
                });

                // Cart table +/- buttons
                $(document).on('click', '.btn-decrease', function () {
                    const id = $(this).data('id');
                    const input = $(`.qty-input[data-id="${id}"]`);
                    input.val(Math.max(parseInt(input.val()) - 1, 1)).trigger('change');
                });
                $(document).on('click', '.btn-increase', function () {
                    const id = $(this).data('id');
                    const input = $(`.qty-input[data-id="${id}"]`);
                    input.val(parseInt(input.val()) + 1).trigger('change');
                });

                // Apply coupon
                $('#apply-coupon').click(function () {
                    const discount = parseFloat($('#coupon').val()) || 0;
                    $.ajax({
                        url: '{{ route('cart.coupon') }}',
                        type: 'POST',
                        data: JSON.stringify({ discount }),
                        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                        contentType: 'application/json',
                        success: function (res) {
                            if (res.success) updateCartDOM(res.cart);
                        },
                    });
                });

                // Clear cart
                $('#clear-cart').click(function () {
                    $.ajax({
                        url: '{{ route('cart.clear') }}',
                        type: 'DELETE',
                        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                        success: function (res) {
                            if (res.success) updateCartDOM(res.cart);
                        },
                    });
                });
            });
        </script>
    @endpush
</x-app-layout>
