<div id="adjustStockModal"
    class="fixed inset-0 z-50 flex hidden h-screen items-center justify-center bg-black bg-opacity-50">
    <div class="w-80 rounded-lg bg-white p-6">
        <h3 class="mb-4 text-lg font-semibold">
            Adjustment Stock:
            <span id="adjustModalProductName"></span>
        </h3>

        <input type="number" id="adjustStockQuantity" placeholder="Jumlah" class="mb-4 w-full rounded border px-3 py-2"
            min="0" />

        <div class="flex justify-end gap-2">
            <button type="button" onclick="closeAdjustStockModal()"
                class="rounded bg-gray-200 px-4 py-2">Batal</button>
            <button type="button" onclick="submitAdjustStock()"
                class="rounded bg-yellow-600 px-4 py-2 text-white">Simpan</button>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        let currentAdjustProductId = null;

        function openAdjustStockModal(productId, productName, currentStock) {
            currentAdjustProductId = productId;
            document.getElementById('adjustModalProductName').innerText = productName;
            document.getElementById('adjustStockQuantity').value = currentStock;
            document.getElementById('adjustStockModal').classList.remove('hidden');
        }

        function closeAdjustStockModal() {
            currentAdjustProductId = null;
            document.getElementById('adjustStockModal').classList.add('hidden');
        }

        function submitAdjustStock() {
            const qty = parseInt(document.getElementById('adjustStockQuantity').value);
            if (isNaN(qty) || qty < 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Oops...',
                    text: 'Masukkan jumlah stock yang valid',
                });
                return;
            }

            fetch('{{ route('stock.adjust') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                    body: JSON.stringify({
                        product_id: currentAdjustProductId,
                        quantity: qty
                    }),
                })
                .then(res => res.json())
                .then(data => {
                    if (!data.success) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: data.message
                        });
                        return;
                    }
                    Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: data.message
                        })
                        .then(() => {
                            closeAdjustStockModal();
                            window.location.reload();
                        });
                })
                .catch(err => {
                    console.error(err);
                    Swal.fire({
                        icon: 'error',
                        title: 'Terjadi Kesalahan',
                        text: 'Gagal menyesuaikan stock'
                    });
                });
        }
    </script>
@endpush
