@extends('frontend.layouts.main')

@section('content')
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">Cart</h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white p-6 shadow-sm sm:rounded-lg">
                {{-- Search Product --}}
                <h3 class="mb-2 font-bold">Search Product</h3>
                <select id="product-search" class="mb-4 w-full rounded border p-2"></select>

                {{-- Add Product (DITUTUP SEMENTARA SUPAYA TIDAK BIAS) --}}
                {{-- <h3 class="mb-2 font-bold">Add Product</h3>
                <div class="mb-4 grid grid-cols-1 gap-2 md:grid-cols-2">
                    @foreach ($products as $product)
                        <div class="flex items-center gap-2 rounded border p-2">
                            <span class="flex-1">
                                {{ $product->name }} - {{ number_format($product->price, 0, ',', '.') }}
                            </span>
                            <button type="button" onclick="changeQty({{ $product->id }}, -1)"
                                class="rounded bg-gray-300 px-2 py-1">
                                -
                            </button>
                            <input type="number" id="qty-{{ $product->id }}" value="1" min="1"
                                class="w-16 rounded border text-center" readonly />
                            <button type="button" onclick="changeQty({{ $product->id }}, 1)"
                                class="rounded bg-gray-300 px-2 py-1">
                                +
                            </button>
                            <button type="button" onclick="addToCart({{ $product->id }})"
                                class="rounded bg-blue-500 px-3 py-1 text-white">
                                Add
                            </button>
                        </div>
                    @endforeach
                </div> --}}

                {{-- Cart Table --}}
                <div class="overflow-x-auto ">
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
                                        <button type="button" class="btn-decrease rounded bg-gray-300 px-2 py-1"
                                            data-id="{{ $item->id }}">
                                            -
                                        </button>
                                        <input type="number" value="{{ $item->quantity }}" min="1"
                                            class="qty-input w-16 rounded border text-center" data-id="{{ $item->id }}"
                                            data-available-stock="{{ $item->itemable->available_stock }}" readonly />
                                        <button type="button" class="btn-increase rounded bg-gray-300 px-2 py-1"
                                            data-id="{{ $item->id }}">
                                            +
                                        </button>
                                    </td>
                                    <td class="border px-4 py-2">
                                        {{ number_format($item->itemable->getPrice() * $item->quantity * (1 - $item->discount), 0, ',', '.') }}
                                    </td>
                                    <td class="border px-4 py-2">
                                        <button class="remove-item rounded bg-red-500 px-2 py-1 text-white"
                                            data-id="{{ $item->id }}">
                                            Remove
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>


                {{-- Cart Summary --}}
                <div class="mt-4 text-right font-bold" id="cart-summary">
                    <div>
                        Subtotal:
                        <span id="cart-subtotal">{{ number_format($total, 0, ',', '.') }}</span>
                    </div>
                    <div id="cart-discount" class="text-green-600" style="display: none">
                        Discount:
                        <span id="cart-discount-amount">0</span>
                    </div>
                    <div>
                        Total:
                        <span id="cart-total">{{ number_format($total, 0, ',', '.') }}</span>
                    </div>
                    <p class="text-sm text-gray-600 mt-2 italic">
                        *Total transaksi tidak termasuk ongkir.
                    </p>
                </div>

                {{-- Apply Coupon --}}
                <div class="mt-4 flex items-center gap-2">
                    <input type="text" id="coupon" placeholder="Put Voucher Code Here"
                        class="rounded border border-gray-300 px-3 py-1 focus:border-blue-400 focus:outline-none" />

                    <!-- Tombol Apply -->
                    <button type="button" id="apply-coupon"
                        class="rounded bg-blue-600 px-4 py-1 text-white transition hover:bg-blue-700">
                        Apply
                    </button>

                    <!-- Tombol Remove (awal disembunyikan) -->
                    <button type="button" id="remove-coupon"
                        class="hidden rounded bg-red-600 px-4 py-1 text-white transition hover:bg-red-700">
                        Remove
                    </button>
                </div>

                {{-- Clear Cart --}}
                <div class="mt-4">
                    <button type="button" id="clear-cart" class="rounded bg-gray-700 px-4 py-2 text-white">
                        Clear Cart
                    </button>
                </div>
                {{-- Checkout --}}
                <div class="mt-4 text-right">
                    <button type="button" id="checkout" class="rounded bg-green-600 px-4 py-2 text-white">
                        Checkout
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    let currentVoucher = null;

    $(function() {
        // Initialize Select2 for product search
        $('#product-search')
            .select2({
                placeholder: 'Search Product',
                ajax: {
                    url: '/api/products',
                    dataType: 'json',
                    processResults: function(data) {
                        return {
                            results: data.map((p) => ({
                                id: p.id,
                                text: p.name + ' - ' + p.price,
                            })),
                        };
                    },
                },
            })
            .on('select2:select', function(e) {
                addToCart(e.params.data.id);
                $(this).val(null).trigger('change'); // reset after select
            });

        // Update cart DOM
        function updateCartDOM(cart, voucher = 0) {
            let tbody = $('#cart-items');
            tbody.empty();
            console.log('cart data:', cart);
            console.log('voucher:', voucher);
            let subtotal = 0;
            cart.items.forEach((item) => {
                let itemSubtotal = item.price * item.quantity * (1 - (item.discount ?? 0));
                subtotal += itemSubtotal;
                tbody.append(`
                <tr id="cart-item-${item.id}">
                    <td class="border px-4 py-2">${item.name}</td>
                    <td class="border px-4 py-2">${item.price.toLocaleString()}</td>
                    <td class="flex items-center gap-2 border px-4 py-2">
                        <button type="button" class="btn-decrease rounded bg-gray-300 px-2 py-1" data-id="${item.id}">-</button>
                        <input type="number" value="${item.quantity}" min="1" class="w-16 rounded border text-center qty-input" readonly data-id="${item.id}" data-available-stock="${item.itemable?.available_stock ?? 9999}"/>
                        <button type="button" class="btn-increase rounded bg-gray-300 px-2 py-1" data-id="${item.id}">+</button>
                    </td>
                    <td class="border px-4 py-2">${itemSubtotal.toLocaleString()}</td>
                    <td class="border px-4 py-2">
                        <button class="remove-item rounded bg-red-500 px-2 py-1 text-white" data-id="${item.id}">Remove</button>
                    </td>
                </tr>
            `);
            });

            // Update subtotal
            $('#cart-subtotal').text(subtotal.toLocaleString());

            // Update voucher / discount
            if (voucher) {
                $('#cart-discount').show();
                $('#cart-discount-amount').text(voucher.discount.toLocaleString());
                $('#cart-total').text((subtotal - voucher.discount).toLocaleString());
            } else {
                $('#cart-discount').hide();
                $('#cart-total').text(subtotal.toLocaleString());
            }
        }

        window.changeQty = function(productId, delta) {
            const input = $(`#qty-${productId}`);
            let val = parseInt(input.val()) + delta;
            if (val < 1) val = 1;
            input.val(val);
        };

        // Fungsi baru: otomatis apply voucher jika ada
        function autoApplyVoucher(cart) {
            if (!currentVoucher) {
                updateCartDOM(cart);
                return;
            }

            $.ajax({
                url: '{{ route('cart.coupon') }}',
                type: 'POST',
                data: JSON.stringify({
                    code: currentVoucher.code,
                }),
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                },
                contentType: 'application/json',
                success: function(res) {
                    if (res.success) {
                        currentVoucher = res.voucher;
                        updateCartDOM(res.cart, currentVoucher);
                    } else {
                        currentVoucher = null; // voucher invalid / subtotal tidak terpenuhi
                        updateCartDOM(cart);
                    }
                },
            });
        }

        // Add to cart
        window.addToCart = function(productId) {
            const qty = parseInt($(`#qty-${productId}`).val()) || 1;
            $.ajax({
                url: '{{ route('cart.add') }}',
                type: 'POST',
                data: JSON.stringify({
                    id: productId,
                    quantity: qty,
                }),
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                },
                contentType: 'application/json',
                success: function(res) {
                    if (res.success) {
                        autoApplyVoucher(res.cart);
                    } else {
                        Swal.fire({
                            title: 'Gagal',
                            text: res.message || 'Gagal menambahkan item',
                            icon: 'error',
                            confirmButtonText: 'OK',
                        });
                    }
                },
            });
        };

        // Remove item
        $(document).on('click', '.remove-item', function() {
            const id = $(this).data('id');
            $.ajax({
                url: `/cart/item/remove/${id}`,
                type: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                },
                success: function(res) {
                    if (res.success) autoApplyVoucher(res.cart);
                },
            });
        });

        // Update qty input
        $(document).on('change', '.qty-input', function() {
            const id = $(this).data('id');
            const quantity = parseInt($(this).val());
            $.ajax({
                url: `/cart/item/${id}`,
                type: 'PATCH',
                data: JSON.stringify({
                    quantity,
                }),
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                },
                contentType: 'application/json',
                success: function(res) {
                    if (res.success) autoApplyVoucher(res.cart);
                },
            });
        });

        // Cart table +/- buttons
        $(document).on('click', '.btn-decrease', function() {
            const id = $(this).data('id');
            const input = $(`.qty-input[data-id="${id}"]`);
            let val = Math.max(parseInt(input.val()) - 1, 1);
            input.val(val);

            $.ajax({
                url: `/cart/item/${id}`,
                type: 'PATCH',
                data: JSON.stringify({
                    quantity: val,
                }),
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                },
                contentType: 'application/json',
                success: function(res) {
                    autoApplyVoucher(res.cart);
                },
            });
        });

        $(document).on('click', '.btn-increase', function() {
            const id = $(this).data('id');
            const input = $(`.qty-input[data-id="${id}"]`);
            let val = parseInt(input.val()) + 1;

            const availableStock = parseInt(input.data('available-stock')) || 9999;

            if (val > availableStock) {
                Swal.fire({
                    title: 'Stok Habis',
                    text: `Maksimum stok tersedia: ${availableStock}`,
                    icon: 'warning',
                    confirmButtonText: 'OK',
                });
                val = availableStock;
            }

            input.val(val);

            $.ajax({
                url: `/cart/item/${id}`,
                type: 'PATCH',
                data: JSON.stringify({
                    quantity: val,
                }),
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                },
                contentType: 'application/json',
                success: function(res) {
                    autoApplyVoucher(res.cart);
                },
            });
        });

        // Apply coupon manual (tetap bisa)
        $('#apply-coupon').click(function() {
            const code = $('#coupon').val();
            $.ajax({
                url: '{{ route('cart.coupon') }}',
                type: 'POST',
                data: JSON.stringify({
                    code,
                }),
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                },
                contentType: 'application/json',
                success: function(res) {
                    if (res.success) {
                        currentVoucher = res.voucher;
                        updateCartDOM(res.cart, currentVoucher);

                        $('#remove-coupon').removeClass('hidden');
                    } else {
                        Swal.fire({
                            title: 'Voucher Tidak Valid',
                            text: res.message || 'Coupon tidak valid',
                            icon: 'error',
                            confirmButtonText: 'OK',
                        });

                        currentVoucher = null;

                        $('#remove-coupon').addClass('hidden');
                    }
                },
            });
        });

        $('#remove-coupon').click(function() {
            currentVoucher = null;
            $('#coupon').val('');
            $('#cart-discount').hide();

            // reset total = subtotal
            const subtotal =
                parseInt(
                    $('#cart-subtotal').text().replace(/[^\d]/g, ''), // hapus semua non-digit
                    10,
                ) || 0;
            $('#cart-total').text(subtotal.toLocaleString('id-ID'));
            console.log('subtotal:', subtotal);

            // Sembunyikan tombol setelah dihapus
            $(this).addClass('hidden');
        });

        $('#checkout').click(function() {
            Swal.fire({
                title: 'Konfirmasi Pembayaran',
                text: 'Apakah Anda yakin ingin melakukan pembayaran?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, bayar!',
                cancelButtonText: 'Batal',
            }).then((result) => {
                if (result.isConfirmed) {
                    // ✅ Jalankan AJAX checkout kalau user setuju
                    doCheckout();
                }
            });
        });

        function doCheckout() {
            $.ajax({
                url: '{{ route('cart.checkout') }}',
                type: 'POST',
                data: JSON.stringify({
                    voucher: currentVoucher,
                }),
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                },
                contentType: 'application/json',
                success: function(res) {
                    if (res.success) {
                        Swal.fire({
                            title: 'Sukses!',
                            text: 'Order berhasil dibuat dengan ID: ' + res.order_id,
                            icon: 'success',
                            confirmButtonText: 'Lihat Pesanan',
                        }).then(() => {
                            window.location.href = res.redirect;
                        });
                    } else {
                        if (res.redirect) {
                            Swal.fire({
                                title: 'Lengkapi Profil',
                                text: res.message,
                                icon: 'warning',
                                showCancelButton: true,
                                confirmButtonText: 'Ke Profil',
                                cancelButtonText: 'Batal',
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    window.location.href = res.redirect;
                                }
                            });
                        } else {
                            Swal.fire({
                                title: 'Gagal',
                                text: res.message || 'Checkout gagal',
                                icon: 'error',
                                confirmButtonText: 'OK',
                            });
                        }
                    }
                },
            });
        }

        // Clear cart
        $('#clear-cart').click(function() {
            $.ajax({
                url: '{{ route('cart.clear') }}',
                type: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                },
                success: function(res) {
                    if (res.success) autoApplyVoucher(res.cart);
                },
            });
        });
    });
</script>

<style>
    /* Tambahan styling khusus untuk cart page */
</style>
