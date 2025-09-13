{{--
    @props([
    'data',      // data koleksi / paginator
    'columns',   // array kolom ['field' => 'Label', ...]
    'actions' => null // opsional, array aksi ['edit' => route, 'delete' => route, ...]
    ])
--}}

@props([
    'data',
    'columns',           // semua kolom
    'actions' => null,
    'maxVisibleColumns' => null, // optional, default null = semua kolom tampil
    'detailColumns' => [],       // optional, jika diisi, override maxVisibleColumns
])
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
                    @foreach ($columns as $field => $label)
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
                        @foreach ($columns as $field => $label)
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

                        @if ($actions)
                            <td
                                class="flex flex-col items-center justify-center gap-1 px-4 py-2 text-center sm:flex-row"
                            >
                                @if (isset($actions['edit']))
                                    <a
                                        href="{{ route($actions['edit'], $item) }}"
                                        class="text-indigo-600 hover:text-indigo-900"
                                    >
                                        Edit
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
                                        <button type="submit" class="text-red-600 hover:text-red-900">Hapus</button>
                                    </form>
                                @endif

                                @if (isset($actions['detail']))
                                    <button type="button" class="toggle-detail text-blue-600 hover:text-blue-900">
                                        Lihat Detail
                                    </button>
                                @endif
                            </td>
                        @endif
                    </tr>

                    {{-- Detail row --}}
                    {{-- Expandable row --}}
                    @if (isset($actions['detail']))
                        <tr class="hidden" x-transition class="bg-gray-100">
                            <td colspan="{{ count($columns) + 1 }}" class="px-4 py-2">
                                <div class="grid grid-cols-1 gap-2 sm:grid-cols-2 md:grid-cols-3">
                                    @foreach ($columns as $field => $label)
                                        <p>
                                            <strong>{{ $label }}:</strong>
                                            @php
                                                $value = $item;
                                                foreach (explode('.', $field) as $f) {
                                                    $value = $value->{$f} ?? null;
                                                }
                                            @endphp

                                            {{ $value ?? '-' }}
                                        </p>
                                    @endforeach
                                </div>
                            </td>
                        </tr>
                    @endif
                @empty
                    <tr>
                        <td
                            colspan="{{ count($columns) + ($actions ? 1 : 0) }}"
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
    <script type="module">
        // Hide all detail rows by default (double check)
        document.querySelectorAll('.detail-row').forEach((row) => row.classList.add('hidden'));

        // Toggle detail
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
                const rowText = row.textContent.toLowerCase();
                row.style.display = rowText.includes(searchValue) ? '' : 'none';

                // hide corresponding detail row if parent row hidden
                const detailRow = row.nextElementSibling;
                if (detailRow && detailRow.classList.contains('detail-row')) {
                    detailRow.style.display = row.style.display;
                }
            });
        });
    </script>
@endpush
