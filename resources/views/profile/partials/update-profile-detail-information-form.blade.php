<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('User Address') }}
        </h2>
        <p class="mt-1 text-sm text-gray-600">
            {{ __('Update your address information.') }}
        </p>
    </header>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="address1" :value="__('Address Line 1')" />
            <x-text-input
                id="address1"
                name="address1"
                type="text"
                class="mt-1 block w-full"
                :value="old('address1', $user->address1)"
            />
            <x-input-error class="mt-2" :messages="$errors->get('address1')" />
        </div>

        <div>
            <x-input-label for="address2" :value="__('Address Line 2')" />
            <x-text-input
                id="address2"
                name="address2"
                type="text"
                class="mt-1 block w-full"
                :value="old('address2', $user->address2)"
            />
            <x-input-error class="mt-2" :messages="$errors->get('address2')" />
        </div>

        <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
            <div>
                <x-input-label for="province_id" :value="__('Province')" />
                <x-text-input
                    id="province_id"
                    name="province_id"
                    type="number"
                    class="mt-1 block w-full"
                    :value="old('province_id', $user->province_id)"
                />
                <x-input-error class="mt-2" :messages="$errors->get('province_id')" />
            </div>

            <div>
                <x-input-label for="city_id" :value="__('City')" />
                <x-text-input
                    id="city_id"
                    name="city_id"
                    type="number"
                    class="mt-1 block w-full"
                    :value="old('city_id', $user->city_id)"
                />
                <x-input-error class="mt-2" :messages="$errors->get('city_id')" />
            </div>

            <div>
                <x-input-label for="postcode" :value="__('Postcode')" />
                <x-text-input
                    id="postcode"
                    name="postcode"
                    type="number"
                    class="mt-1 block w-full"
                    :value="old('postcode', $user->postcode)"
                />
                <x-input-error class="mt-2" :messages="$errors->get('postcode')" />
            </div>
        </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save Address') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => (show = false), 2000)"
                    class="text-sm text-gray-600"
                >
                    {{ __('Saved.') }}
                </p>
            @endif
        </div>
    </form>
</section>
