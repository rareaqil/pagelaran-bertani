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
    <input
        type="text"
        class="mb-4 w-full rounded border px-3 py-2 shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
        placeholder="Search..."
        id="tableSearch"
    />

    <div class="overflow-x-auto bg-white shadow sm:rounded-lg">
        <table class="w-full table-fixed divide-y divide-gray-200" id="flexibleTable">
            <thead class="bg-gray-50">
                <tr>
                    @foreach ($mainColumns as $field => $label)
                        <th class="px-4 py-2 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                            {{ $label }}
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
                        {{-- Tampilkan kolom utama --}}
                        @foreach ($mainColumns as $field => $label)
                            <td class="max-w-[150px] truncate px-4 py-2">
                                @php
                                    $value = $item;
                                    foreach (explode('.', $field) as $f) {
                                        $value = $value->{$f} ?? null;
                                    }
                                @endphp

                                {{ $value ?? '-' }}
                            </td>
                        @endforeach

                        {{-- Aksi --}}
                        @if ($actions)
                            <td class="flex items-center justify-center gap-2 px-4 py-2 text-center">
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
                            </td>
                        @endif
                    </tr>

                    {{-- Detail row --}}
                    @if (isset($actions['detail']))
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

        {{-- Pagination --}}
        @if ($data instanceof \Illuminate\Pagination\LengthAwarePaginator)
            <div class="p-4">
                {{ $data->links() }}
            </div>
        @endif
    </div>
</div>

@push('scripts')
    <script>
        // Toggle detail row
        document.querySelectorAll('.toggle-detail').forEach((btn) => {
            btn.addEventListener('click', function () {
                const detailRow = this.closest('tr').nextElementSibling;
                detailRow.classList.toggle('hidden');
            });
        });

        // Search
        const searchInput = document.getElementById('tableSearch');
        const tableRows = document.querySelectorAll('#flexibleTable tbody tr:not(.detail-row)');

        searchInput.addEventListener('keyup', function () {
            const searchValue = this.value.toLowerCase();

            tableRows.forEach((row) => {
                const detailRow = row.nextElementSibling;
                let combinedText = row.textContent.toLowerCase();

                if (detailRow && detailRow.classList.contains('detail-row')) {
                    combinedText += ' ' + detailRow.textContent.toLowerCase();
                }

                if (!searchValue) {
                    // Jika search kosong → tampil row, tutup semua detail
                    row.style.display = '';
                    if (detailRow && detailRow.classList.contains('detail-row')) {
                        detailRow.classList.add('hidden');
                    }
                } else if (combinedText.includes(searchValue)) {
                    // Row match → tampil dan buka detail row
                    row.style.display = '';
                    if (detailRow && detailRow.classList.contains('detail-row')) {
                        detailRow.classList.remove('hidden');
                    }
                } else {
                    // Row tidak match → sembunyikan row dan tutup detail row
                    row.style.display = 'none';
                    if (detailRow && detailRow.classList.contains('detail-row')) {
                        detailRow.classList.add('hidden');
                    }
                }
            });
        });
    </script>
@endpush
