<div>
    <x-jet-form-section submit="submit">
        <x-slot name="title">
            {{ __('Google Analytics') }}
        </x-slot>

        <x-slot name="description">
            {{ __('Enter Google Analytics JS code') }}
        </x-slot>

        <x-slot name="form">
            <div class="col-span-6 sm:col-span-4">
                <x-buk-textarea class="border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm w-full" rows="10" id="googleAnalyticsCode" name="googleAnalyticsCode" wire:model="googleAnalyticsCode"></x-buk-textarea>
                <x-jet-input-error for="googleAnalyticsCode" class="mt-2" />
            </div>

        </x-slot>

        <x-slot name="actions">
            <x-jet-action-message class="mr-3" on="saved">
                {{ __('Saved.') }}
            </x-jet-action-message>

            <x-jet-button>
                {{ __('Save') }}
            </x-jet-button>
        </x-slot>
    </x-jet-form-section>
</div>
