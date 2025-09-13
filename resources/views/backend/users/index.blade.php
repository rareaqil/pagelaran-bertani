<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">Management User</h2>
    </x-slot>

    <div class="py-6">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-4 rounded bg-green-100 p-4 text-green-800">
                    {{ session('success') }}
                </div>
            @endif

            <div class="mb-4 flex items-center justify-between p-4">
                <a
                    href="{{ route('users.create') }}"
                    class="rounded bg-blue-600 px-4 py-2 text-white transition hover:bg-blue-700"
                >
                    Tambah User
                </a>
            </div>

            {{--
                <div class="overflow-x-auto bg-white shadow sm:rounded-lg">
                <table class="min-w-full divide-y divide-gray-200" id="usersTable">
                <thead class="bg-gray-50">
                <tr>
                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                Nama
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                Email
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                Role
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                Alamat
                </th>
                <th
                class="px-6 py-3 text-center text-xs font-medium uppercase tracking-wider text-gray-500"
                >
                Aksi
                </th>
                </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 bg-white" id="usersTbody">
                @foreach ($users as $user)
                <tr class="hover:bg-gray-50">
                <td class="px-6 py-4">{{ $user->first_name }} {{ $user->last_name }}</td>
                <td class="px-6 py-4">{{ $user->email }}</td>
                <td class="px-6 py-4">{{ ucfirst($user->role) }}</td>
                <td class="px-6 py-4">
                @if ($user->primaryAddress)
                {{ $user->primaryAddress->address1 }},
                {{ $user->primaryAddress->district_name }},
                {{ $user->primaryAddress->regency_name }},
                {{ $user->primaryAddress->province_name }},
                {{ $user->primaryAddress->postcode }}
                @else
                -
                @endif
                </td>
                <td class="space-x-2 px-6 py-4 text-center">
                <a
                href="{{ route('users.edit', $user) }}"
                class="text-indigo-600 hover:text-indigo-900"
                >
                Edit
                </a>
                <form
                action="{{ route('users.destroy', $user) }}"
                method="POST"
                class="inline-block"
                onsubmit="return confirm('Yakin ingin menghapus user ini?');"
                >
                @csrf
                @method('DELETE')
                <button type="submit" class="text-red-600 hover:text-red-900">Hapus</button>
                </form>
                </td>
                </tr>
                @endforeach
                </tbody>
                </table>
                
                <!-- Pagination -->
                <div class="p-4">
                {{ $users->links() }}
                </div>
                </div>
            --}}
            {{-- Panggil component table --}}
            <div class="bg-white p-4 shadow sm:rounded-lg sm:p-8">
                <x-table-flexible
                    :data="$users"
                    :columns="[
                        'first_name' => 'Nama Depan',
                        'last_name' => 'Nama Belakang',
                        'email' => 'Email',
                        'phone' => 'Nomor Telepon',
                        'age' => 'Umur',
                        'role' => 'Role',
                        'primaryAddress.address1' => 'Alamat',
                        'primaryAddress.district_name' => 'Kecamatan',
                        'primaryAddress.regency_name' => 'Kabupaten',
                        'primaryAddress.province_name' => 'Provinsi',
                        'primaryAddress.postcode' => 'Kode Pos'
                    ]"
                    :actions="['edit' => 'users.edit', 'delete' => 'users.destroy','detail' => true]"
                    :maxVisibleColumns="6"
                />
            </div>
        </div>
    </div>

    @push('scripts')
        <script type="module">
            // Simple search/filter function
            const searchInput = document.getElementById('searchInput');
            const tableRows = document.querySelectorAll('#usersTbody tr');

            searchInput.addEventListener('keyup', function () {
                const searchValue = this.value.toLowerCase();

                tableRows.forEach((row) => {
                    const rowText = row.textContent.toLowerCase();
                    row.style.display = rowText.includes(searchValue) ? '' : 'none';
                });
            });
        </script>
    @endpush
</x-app-layout>
