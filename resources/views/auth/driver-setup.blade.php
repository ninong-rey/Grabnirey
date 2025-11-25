<x-guest-layout>
    <form method="POST" action="{{ route('driver.store-vehicle') }}">
        @csrf

        <div class="mb-6">
            <h2 class="text-2xl font-bold text-gray-900">Complete Your Driver Profile</h2>
            <p class="mt-2 text-sm text-gray-600">Please provide your vehicle information to start accepting rides.</p>
        </div>

        <!-- License Number -->
        <div class="mt-4">
            <x-input-label for="license_number" :value="__('Driver License Number')" />
            <x-text-input id="license_number" class="block mt-1 w-full" type="text" name="license_number" :value="old('license_number')" required autofocus />
            <x-input-error :messages="$errors->get('license_number')" class="mt-2" />
        </div>

        <!-- Vehicle Type -->
        <div class="mt-4">
            <x-input-label for="vehicle_type" :value="__('Vehicle Type')" />
            <select id="vehicle_type" name="vehicle_type" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                <option value="">Select Vehicle Type</option>
                <option value="sedan" {{ old('vehicle_type') == 'sedan' ? 'selected' : '' }}>Sedan</option>
                <option value="suv" {{ old('vehicle_type') == 'suv' ? 'selected' : '' }}>SUV</option>
                <option value="motorcycle" {{ old('vehicle_type') == 'motorcycle' ? 'selected' : '' }}>Motorcycle</option>
            </select>
            <x-input-error :messages="$errors->get('vehicle_type')" class="mt-2" />
        </div>

        <!-- Vehicle Plate -->
        <div class="mt-4">
            <x-input-label for="vehicle_plate" :value="__('Vehicle Plate Number')" />
            <x-text-input id="vehicle_plate" class="block mt-1 w-full" type="text" name="vehicle_plate" :value="old('vehicle_plate')" required />
            <x-input-error :messages="$errors->get('vehicle_plate')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-6">
            <x-primary-button class="ms-4">
                {{ __('Complete Registration') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>