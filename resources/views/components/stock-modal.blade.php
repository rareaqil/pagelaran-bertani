<div id="stockModal" class="fixed inset-0 z-50 flex hidden h-screen items-center justify-center bg-black bg-opacity-50">
    <div class="w-80 rounded-lg bg-white p-6">
        <h3 class="mb-4 text-lg font-semibold">
            Tambah Stock:
            <span id="modalProductName"></span>
        </h3>

        <input type="number" id="stockQuantity" placeholder="Jumlah" class="mb-4 w-full rounded border px-3 py-2" />

        <div class="flex justify-end gap-2">
            <button type="button" onclick="closeStockModal()" class="rounded bg-gray-200 px-4 py-2">Batal</button>
            <button type="button" onclick="submitStock()" class="rounded bg-purple-600 px-4 py-2 text-white">
                Simpan
            </button>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        let currentProductId = null;

        function openStockModal(productId, productName) {
            currentProductId = productId;
            document.getElementById('modalProductName').innerText = productName;
            document.getElementById('stockQuantity').value = '';
            document.getElementById('stockModal').classList.remove('hidden');
        }

        function closeStockModal() {
            currentProductId = null;
            document.getElementById('stockModal').classList.add('hidden');
        }

        function submitStock() {
            const qty = document.getElementById('stockQuantity').value;
            if (!qty || qty <= 0) {
                alert('Masukkan jumlah stock yang valid');
                return;
            }

            fetch('{{ route('stock.add') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                },
                body: JSON.stringify({ product_id: currentProductId, quantity: qty }),
            })
                .then((res) => res.json())
                .then((data) => {
                    alert(data.message || 'Stock berhasil ditambahkan');
                    closeStockModal();
                    window.location.reload();
                })
                .catch((err) => {
                    console.error(err);
                    alert('Terjadi kesalahan');
                });
        }
    </script>
@endpush
