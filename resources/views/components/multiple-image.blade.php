@props([
    'name',
    'label' => 'Gambar Produk',
    'value' => '',
    'max' => 5,
])

@php
    // Pecah string jadi array untuk preview saja
    $urls = old($name, $value ?? '');
    $urls = $urls ? array_filter(array_map('trim', explode(',', $urls))) : [];
@endphp

<div class="space-y-2">
    <x-input-label :for="$name" :value="$label" />

    <div class="flex gap-2">
        <input
            id="{{ $name }}"
            name="{{ $name }}"
            type="text"
            class="flex-1 rounded border-gray-300 p-2"
            value="{{ old($name, $value ?? '') }}"
            placeholder="Pilih gambar..."
            readonly
            hidden
        />
        <button
            id="button-{{ $name }}"
            type="button"
            class="btn btn-outline-info rounded border border-gray-300 px-4 py-2 text-gray-700"
            data-input="{{ $name }}"
        >
            Browse
        </button>
    </div>
    <x-input-error class="mt-2" :messages="$errors->get($name)" />

    {{-- Preview --}}
    <div id="preview-{{ $name }}" class="mt-3 flex flex-wrap gap-3 rounded-lg border border-gray-300 bg-gray-50 p-3">
        @forelse ($urls as $u)
            <div class="h-24 w-24 overflow-hidden rounded border border-gray-300 bg-white shadow">
                <img src="{{ $u }}" class="h-full w-full object-contain" />
            </div>
        @empty
            <div class="text-gray-400">Belum ada gambar</div>
        @endforelse
    </div>
</div>

@pushOnce('scripts')
<script type="module" src="{{ asset('vendor/laravel-filemanager/js/stand-alone-button.js') }}"></script>
@endPushOnce

@push('scripts')
    <script type="module">
        $(function () {
            const input = $('#{{ $name }}');
            const preview = $('#preview-{{ $name }}');
            const max = {{ $max }};

            $('#button-{{ $name }}').filemanager('image', { multiple: true });

            input.on('change', function () {
                let urls = (input.val() || '')
                    .split(',')
                    .map((u) => u.trim())
                    .filter(Boolean);

                if (urls.length > max) {
                    alert(`Maksimal ${max} foto`);
                    urls = urls.slice(0, max);
                    input.val(urls.join(','));
                }

                preview.empty();
                if (urls.length === 0) {
                    preview.append('<div class="text-gray-400">Belum ada gambar</div>');
                    return;
                }

                urls.forEach((u) => {
                    preview.append(`
                  <div class="h-24 w-24 overflow-hidden rounded border border-gray-300 bg-white shadow">
                      <img src="${u}" class="h-full w-full object-contain"/>
                  </div>
                `);
                });
            });
        });
    </script>
@endpush
