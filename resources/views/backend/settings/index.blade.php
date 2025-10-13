<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">Settings</h2>
    </x-slot>

    <div class="mt-6 space-y-6 rounded-lg bg-white p-4 shadow sm:rounded-xl sm:p-8">
        @if (session('success'))
            <div class="mb-4 rounded bg-green-100 px-4 py-3 text-green-700">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-4 rounded bg-red-100 px-4 py-3 text-red-700">
                <ul class="list-inside list-disc">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('settings.update') }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            @foreach ($sections as $sectionName => $fields)
                <div class="mb-6">
                    <h3 class="mb-2 text-lg font-bold">{{ $sectionName }}</h3>
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                        @foreach ($fields as $key => $config)
                            <div>
                                     @php
                                        $isRequired = isset($config['rules']) && Str::contains($config['rules'], 'required');
                                    @endphp
                                <x-input-label
                                    :for="$key"
                                    :value="$config['description']"
                                    :required="$isRequired"
                                 />
                                @if ($key === 'midtrans_is_production')
                                    <select
                                        name="{{ $key }}"
                                        id="{{ $key }}"
                                        class="mt-1 block w-full rounded border-gray-300"
                                    >
                                        <option
                                            value="1"
                                            {{ old($key, $settings[$key]->value ?? '') == '1' ? 'selected' : '' }}
                                        >
                                            Aktif
                                        </option>
                                        <option
                                            value="0"
                                            {{ old($key, $settings[$key]->value ?? '') == '0' ? 'selected' : '' }}
                                        >
                                            Sandbox
                                        </option>
                                    </select>
                                @else
                                    <x-text-input
                                        id="{{ $key }}"
                                        name="{{ $key }}"
                                        type="text"
                                        class="mt-1 block w-full"
                                        value="{{ old($key, $settings[$key]->value ?? '') }}"
                                    />
                                @endif
                                <x-input-error class="mt-2" :messages="$errors->get($key)" />
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach

            <div>
                <x-primary-button>Simpan Settings</x-primary-button>
            </div>
        </form>
    </div>
</x-app-layout>
