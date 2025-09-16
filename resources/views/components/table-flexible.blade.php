@props([
    'data',
    'columns',
    'actions' => null,
    'maxVisibleColumns' => null,
    'detailColumns' => [],
])

@php
    // Tentukan kolom utama dan kolom detail
    if (! empty($detailColumns)) {
        $mainColumns = array_diff_key($columns, array_flip($detailColumns));
    } elseif ($maxVisibleColumns) {
        $mainColumns = array_slice($columns, 0, $maxVisibleColumns, true);
        $detailColumns = array_slice($columns, $maxVisibleColumns, null, true);
    } else {
        $mainColumns = $columns;
        $detailColumns = [];
    }
@endphp

<div class="mx-auto w-full max-w-full sm:max-w-7xl">
    {{-- Search input --}}

    <div class="mb-4 flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        {{-- Input Search --}}
        <div class="w-full sm:w-auto">
            <input
                type="text"
                id="tableSearch"
                placeholder="Search…"
                class="w-full rounded-md border px-4 py-2 text-sm shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500 sm:w-64"
            />
        </div>

        {{-- Per Page Select --}}
        <div class="flex w-full items-center justify-between sm:w-auto sm:justify-end">
            <label for="perPage" class="mr-2 whitespace-nowrap text-sm text-gray-700">Tampilkan per halaman:</label>
            <select
                id="perPage"
                class="w-24 rounded-md border px-2 py-1 text-sm shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500"
            >
                <option value="10" {{ request('perPage') == 10 ? 'selected' : '' }}>10</option>
                <option value="20" {{ request('perPage') == 20 ? 'selected' : '' }}>20</option>
                <option value="50" {{ request('perPage') == 50 ? 'selected' : '' }}>50</option>
                <option value="100" {{ request('perPage') == 100 ? 'selected' : '' }}>100</option>
            </select>
        </div>
    </div>

    {{-- Desktop Table --}}
    <div class="hidden overflow-x-auto bg-white shadow sm:block sm:rounded-lg">
        <table class="w-full table-fixed divide-y divide-gray-200" id="flexibleTable">
            <thead class="bg-gray-50">
                <tr>
                    @foreach ($mainColumns as $field => $label)
                        @php
                            $isSorted = request()->query('sort') === $field;
                            $direction = request()->query('direction') === 'asc' ? 'desc' : 'asc';
                        @endphp

                        <th class="px-4 py-2 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                            <a
                                href="{{ request()->fullUrlWithQuery(['sort' => $field, 'direction' => $direction]) }}"
                                class="flex items-center gap-1"
                            >
                                {{ $label }}
                                @if ($isSorted)
                                    @if (request()->query('direction') === 'asc')
                                        ▲
                                    @else
                                        ▼
                                    @endif
                                @endif
                            </a>
                        </th>
                    @endforeach

                    @if ($actions)
                        <th
                            class="w-32 px-4 py-2 text-center text-xs font-medium uppercase tracking-wider text-gray-500"
                        >
                            Aksi
                        </th>
                    @endif
                </tr>
            </thead>

            <tbody class="divide-y divide-gray-200 bg-white">
                @forelse ($data as $item)
                    <tr class="hover:bg-gray-50">
                        {{-- Main columns --}}
                        @foreach ($mainColumns as $field => $label)
                            <td class="max-w-[150px] truncate px-4 py-2">
                                @php
                                    $value = $item;
                                    foreach (explode('.', $field) as $f) {
                                        $value = $value->{$f} ?? null;
                                    }
                                @endphp

                                @if ($field === 'image' && $value)
                                    <img src="{{ $value }}" alt="Gambar" class="h-16 w-16 rounded object-cover" />
                                @elseif ($field === 'price' && $value)
                                    {{-- Format harga dengan Rp dan pemisah ribuan --}}
                                    Rp {{ number_format($value, 0, ',', '.') }}
                                @elseif ($field === 'status_active')
                                    {{-- Badge status aktif / nonaktif --}}
                                    @if ($value)
                                        <span
                                            class="inline-block rounded-full bg-green-100 px-2 py-1 text-xs font-semibold text-green-700"
                                        >
                                            Aktif
                                        </span>
                                    @else
                                        <span
                                            class="inline-block rounded-full bg-red-100 px-2 py-1 text-xs font-semibold text-red-700"
                                        >
                                            Nonaktif
                                        </span>
                                    @endif
                                @else
                                    {{ $value ?? '-' }}
                                @endif
                            </td>
                        @endforeach

                        {{-- Actions --}}
                        @if ($actions)
                            <td class="px-4 py-2 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    @if (! empty($detailColumns) || isset($actions['detail']))
                                        <button
                                            type="button"
                                            class="toggle-detail text-blue-600 hover:text-blue-900"
                                            title="Lihat Detail"
                                        >
                                            <svg
                                                xmlns="http://www.w3.org/2000/svg"
                                                class="h-5 w-5"
                                                fill="none"
                                                viewBox="0 0 24 24"
                                                stroke="currentColor"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M15 12H9m0 0l3-3m-3 3l3 3"
                                                />
                                            </svg>
                                        </button>
                                    @endif

                                    @if (isset($actions['show']))
                                        <a
                                            href="{{ route($actions['show'], $item) }}"
                                            class="text-green-600 hover:text-green-900"
                                            title="Lihat Postingan"
                                            target="_blank"
                                        >
                                            <svg
                                                xmlns="http://www.w3.org/2000/svg"
                                                class="h-5 w-5"
                                                fill="none"
                                                viewBox="0 0 24 24"
                                                stroke="currentColor"
                                            >
                                                <!-- icon 'eye' -->
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                                                />
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.522 5 12 5
                                                    c4.478 0 8.268 2.943 9.542 7
                                                    -1.274 4.057-5.064 7-9.542 7
                                                    -4.478 0-8.268-2.943-9.542-7z"
                                                />
                                            </svg>
                                        </a>
                                    @endif

                                    @if (isset($actions['edit']))
                                        <a
                                            href="{{ route($actions['edit'], $item) }}"
                                            class="text-indigo-600 hover:text-indigo-900"
                                            title="Edit"
                                        >
                                            <svg
                                                xmlns="http://www.w3.org/2000/svg"
                                                class="h-5 w-5"
                                                fill="none"
                                                viewBox="0 0 24 24"
                                                stroke="currentColor"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M15.232 5.232l3.536 3.536M9 11l6-6 3 3-6 6H9v-3z"
                                                />
                                            </svg>
                                        </a>
                                    @endif

                                    @if (isset($actions['delete']))
                                        <form
                                            action="{{ route($actions['delete'], $item) }}"
                                            method="POST"
                                            onsubmit="return confirm('Yakin ingin menghapus?');"
                                        >
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900" title="Hapus">
                                                <svg
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    class="h-5 w-5"
                                                    fill="none"
                                                    viewBox="0 0 24 24"
                                                    stroke="currentColor"
                                                >
                                                    <path
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5-4h4m-4 0a1 1 0 00-1 1v0a1 1 0 001 1h4a1 1 0 001-1v0a1 1 0 00-1-1m-4 0V3m0 0h4"
                                                    />
                                                </svg>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                                <div class="flex items-center justify-center gap-2">
                                    @if (isset($actions['addStock']))
                                        <button
                                            type="button"
                                            class="text-purple-600 hover:text-purple-900"
                                            title="Tambah Stock"
                                            onclick="openStockModal({{ $item->id }}, '{{ $item->name }}')"
                                        >
                                            <svg
                                                xmlns="http://www.w3.org/2000/svg"
                                                class="h-5 w-5"
                                                fill="none"
                                                viewBox="0 0 24 24"
                                                stroke="currentColor"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M12 4v16m8-8H4"
                                                />
                                            </svg>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        @endif
                    </tr>

                    {{-- Detail row --}}
                    @if (! empty($detailColumns) || isset($actions['detail']))
                        <tr class="detail-row hidden bg-gray-50">
                            <td
                                colspan="{{ count($mainColumns) + ($actions ? 1 : 0) }}"
                                class="px-4 py-2 text-sm text-gray-700"
                            >
                                @php
                                    $cols = ! empty($detailColumns) ? $detailColumns : $columns;
                                @endphp

                                <div class="grid grid-cols-1 gap-2 sm:grid-cols-2 md:grid-cols-3">
                                    @foreach ($cols as $field => $label)
                                        @php
                                            $value = $item;
                                            foreach (explode('.', $field) as $f) {
                                                $value = $value->{$f} ?? '-';
                                            }
                                        @endphp

                                        <p>
                                            <strong>{{ $label }}:</strong>
                                            {{ $value }}
                                        </p>
                                    @endforeach
                                </div>
                            </td>
                        </tr>
                    @endif
                @empty
                    <tr>
                        <td
                            colspan="{{ count($mainColumns) + ($actions ? 1 : 0) }}"
                            class="px-4 py-2 text-center text-gray-400"
                        >
                            Tidak ada data
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Mobile Cards --}}
    <div class="space-y-4 sm:hidden">
        @forelse ($data as $item)
            <div class="rounded-lg border bg-white p-4 shadow">
                @foreach ($mainColumns as $field => $label)
                    @php
                        $value = $item;
                        foreach (explode('.', $field) as $f) {
                            $value = $value->{$f} ?? null;
                        }
                    @endphp

                    <p class="mb-2 flex flex-wrap items-center justify-between">
                        <strong class="mr-2 text-gray-700">{{ $label }}:</strong>

                        {{-- Kondisi sesuai jenis field --}}

                        @if ($field === 'image' && $value)
                            <img src="{{ $value }}" alt="Gambar" class="mt-1 h-16 w-16 rounded object-cover" />
                        @elseif ($field === 'price' && $value)
                            <span class="font-semibold text-gray-900">
                                Rp {{ number_format($value, 0, ',', '.') }}
                            </span>
                        @elseif ($field === 'status_active')
                            @if ($value)
                                <span
                                    class="inline-block rounded-full bg-green-100 px-2 py-1 text-xs font-semibold text-green-700"
                                >
                                    Aktif
                                </span>
                            @else
                                <span
                                    class="inline-block rounded-full bg-red-100 px-2 py-1 text-xs font-semibold text-red-700"
                                >
                                    Nonaktif
                                </span>
                            @endif
                        @else
                            <span class="text-gray-800">{{ $value ?? '-' }}</span>
                        @endif
                    </p>
                @endforeach

                @if ($actions)
                    <div class="mt-2 flex items-center gap-2">
                        @if (! empty($detailColumns) || isset($actions['detail']))
                            <button
                                type="button"
                                class="toggle-detail text-blue-600 hover:text-blue-900"
                                title="Lihat Detail"
                            >
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="h-5 w-5"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M15 12H9m0 0l3-3m-3 3l3 3"
                                    />
                                </svg>
                            </button>
                        @endif

                        @if (isset($actions['show']))
                            <a
                                href="{{ route($actions['show'], $item) }}"
                                class="text-green-600 hover:text-green-900"
                                title="Lihat Postingan"
                                target="_blank"
                            >
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="h-5 w-5"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                >
                                    <!-- icon 'eye' -->
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                                    />
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.522 5 12 5
                            c4.478 0 8.268 2.943 9.542 7
                            -1.274 4.057-5.064 7-9.542 7
                            -4.478 0-8.268-2.943-9.542-7z"
                                    />
                                </svg>
                            </a>
                        @endif

                        @if (isset($actions['edit']))
                            <a
                                href="{{ route($actions['edit'], $item) }}"
                                class="text-indigo-600 hover:text-indigo-900"
                                title="Edit"
                            >
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    class="h-5 w-5"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M15.232 5.232l3.536 3.536M9 11l6-6 3 3-6 6H9v-3z"
                                    />
                                </svg>
                            </a>
                        @endif

                        @if (isset($actions['delete']))
                            <form
                                action="{{ route($actions['delete'], $item) }}"
                                method="POST"
                                onsubmit="return confirm('Yakin ingin menghapus?');"
                            >
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900" title="Hapus">
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        class="h-5 w-5"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5-4h4m-4 0a1 1 0 00-1 1v0a1 1 0 001 1h4a1 1 0 001-1v0a1 1 0 00-1-1m-4 0V3m0 0h4"
                                        />
                                    </svg>
                                </button>
                            </form>
                        @endif
                    </div>
                @endif

                {{-- Detail --}}
                @if (! empty($detailColumns) || isset($actions['detail']))
                    <div class="detail-row mt-2 hidden border-t pt-2 text-sm text-gray-700">
                        @php
                            $cols = ! empty($detailColumns) ? $detailColumns : $columns;
                        @endphp

                        @foreach ($cols as $field => $label)
                            @php
                                $value = $item;
                                foreach (explode('.', $field) as $f) {
                                    $value = $value->{$f} ?? '-';
                                }
                            @endphp

                            <p>
                                <strong>{{ $label }}:</strong>
                                {{ $value }}
                            </p>
                        @endforeach
                    </div>
                @endif
            </div>
        @empty
            <p class="text-center text-gray-400">Tidak ada data</p>
        @endforelse
    </div>

    {{-- Pagination --}}
    @if ($data instanceof \Illuminate\Pagination\LengthAwarePaginator)
        <div class="p-4">
            {{ $data->links() }}
        </div>
    @endif
</div>

<x-stock-modal />

@push('scripts')
    <script type="module">
        const searchInput = document.getElementById('tableSearch');
        searchInput?.addEventListener('keyup', function () {
            const value = this.value.toLowerCase();
            document.querySelectorAll('#flexibleTable tbody tr').forEach((row) => {
                row.style.display = row.textContent.toLowerCase().includes(value) ? '' : 'none';
            });
        });
    </script>
    <script type="module">
        const perPageSelect = document.getElementById('perPage');
        perPageSelect.addEventListener('change', function () {
            const url = new URL(window.location.href);
            url.searchParams.set('perPage', this.value);
            window.location.href = url.toString();
        });
    </script>
    <script type="module">
        // Toggle detail row (desktop & mobile)
        document.querySelectorAll('.toggle-detail').forEach((btn) => {
            btn.addEventListener('click', function () {
                const parentRow = this.closest('tr');
                const parentCard = this.closest('div.border');
                const detailRow = parentRow ? parentRow.nextElementSibling : parentCard?.querySelector('.detail-row');
                if (detailRow) {
                    detailRow.classList.toggle('hidden');
                }
            });
        });

        // Search universal
        const searchInput = document.getElementById('tableSearch');

        searchInput.addEventListener('keyup', function () {
            const searchValue = this.value.toLowerCase();

            // Desktop table
            const desktopRows = document.querySelectorAll('#flexibleTable tbody tr:not(.detail-row)');
            desktopRows.forEach((row) => {
                const detailRow = row.nextElementSibling;
                let combinedText = row.textContent.toLowerCase();
                if (detailRow) combinedText += ' ' + detailRow.textContent.toLowerCase();

                if (!searchValue) {
                    row.style.display = '';
                    if (detailRow) detailRow.classList.add('hidden');
                } else if (combinedText.includes(searchValue)) {
                    row.style.display = '';
                    if (detailRow) detailRow.classList.remove('hidden');
                } else {
                    row.style.display = 'none';
                    if (detailRow) detailRow.classList.add('hidden');
                }
            });

            // Mobile cards
            const mobileCards = document.querySelectorAll('.sm\\:hidden > div');
            mobileCards.forEach((card) => {
                const detailRow = card.querySelector('.detail-row');
                let combinedText = card.textContent.toLowerCase();

                if (!searchValue) {
                    card.style.display = '';
                    if (detailRow) detailRow.classList.add('hidden');
                } else if (combinedText.includes(searchValue)) {
                    card.style.display = '';
                    if (detailRow) detailRow.classList.remove('hidden');
                } else {
                    card.style.display = 'none';
                    if (detailRow) detailRow.classList.add('hidden');
                }
            });
        });
    </script>
@endpush
