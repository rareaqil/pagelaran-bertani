<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ isset($product) ? 'Edit Produk' : 'Tambah Produk' }}
        </h2>
    </x-slot>

    <div class="mt-6 space-y-6 rounded-lg bg-white p-4 shadow sm:rounded-xl sm:p-8">
        <div class="mx-auto max-w-3xl space-y-6 rounded-xl bg-white p-6 shadow">
            <form
                action="{{ isset($product) ? route('products.update', $product) : route('products.store') }}"
                method="POST"
                enctype="multipart/form-data"
                class="space-y-6"
            >
                @csrf
                @if (isset($product))
                    @method('PUT')
                @endif

                {{-- Nama Produk --}}
                <div>
                    <x-input-label for="name" :value="'Nama Produk'" />
                    <x-text-input
                        id="name"
                        name="name"
                        type="text"
                        class="mt-1 block w-full"
                        value="{{ old('name', $product->name ?? '') }}"
                        required
                    />
                    <x-input-error class="mt-2" :messages="$errors->get('name')" />
                </div>

                {{-- Deskripsi --}}
                <div>
                    <x-input-label for="description" :value="'Deskripsi Produk'" />
                    <textarea
                        id="description"
                        name="description"
                        class="mt-1 block w-full rounded border-gray-300"
                        rows="4"
                        placeholder="Masukkan deskripsi singkat..."
                    >
{{ old('description', $product->description ?? '') }}</textarea
                    >
                    <x-input-error class="mt-2" :messages="$errors->get('description')" />
                </div>

                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                    {{-- Harga --}}
                    <div>
                        <x-input-label for="price" :value="'Harga (Rp)'" />
                        <x-text-input
                            id="price"
                            name="price"
                            type="number"
                            step="0.01"
                            class="mt-1 block w-full"
                            value="{{ old('price', $product->price ?? '') }}"
                            required
                        />
                        <x-input-error class="mt-2" :messages="$errors->get('price')" />
                    </div>

                    {{-- Stok --}}
                    <div class="hidden">
                        <x-input-label for="stock" :value="'Stok'" />
                        <x-text-input
                            id="stock"
                            name="stock"
                            type="number"
                            class="mt-1 block w-full"
                            value="{{ old('stock', $product->stock ?? 0) }}"
                            required
                        />
                        <x-input-error class="mt-2" :messages="$errors->get('stock')" />
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                    {{--
                        Berat
                        <div>
                        <x-input-label for="weight" :value="'Berat (Kg)'" />
                        <x-text-input
                        id="weight"
                        name="weight"
                        type="number"
                        step="0.01"
                        class="mt-1 block w-full"
                        value="{{ old('weight', $product->weight ?? '') }}"
                        />
                        <x-input-error class="mt-2" :messages="$errors->get('weight')" />
                        </div>
                    --}}

                    {{-- SKU --}}
                    <div>
                        <x-input-label for="sku" :value="'SKU / Kode Unik'" />
                        <x-text-input
                            id="sku"
                            name="sku"
                            type="text"
                            class="mt-1 block w-full"
                            value="{{ old('sku', $product->sku ?? '') }}"
                        />
                        <x-input-error class="mt-2" :messages="$errors->get('sku')" />
                    </div>
                </div>

                {{-- Status Aktif --}}
                <div>
                    <x-input-label for="status_active" :value="'Status Produk'" />
                    <select id="status_active" name="status_active" class="mt-1 block w-full rounded border-gray-300">
                        <option
                            value="1"
                            {{ old('status_active', $product->status_active ?? true) ? 'selected' : '' }}
                        >
                            Aktif
                        </option>
                        <option
                            value="0"
                            {{ old('status_active', $product->status_active ?? true) == false ? 'selected' : '' }}
                        >
                            Nonaktif
                        </option>
                    </select>
                    <x-input-error class="mt-2" :messages="$errors->get('status_active')" />
                </div>

                {{-- Upload Gambar Produk --}}

                <x-multiple-image name="image" :label="'Gambar Produk'" :value="$product->image ?? ''" :max="5" />
                <div>
                    <x-primary-button>
                        {{ isset($product) ? 'Update' : 'Simpan' }}
                    </x-primary-button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
