<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Machine') }}
        </h2>
    </x-slot>

    <x-middle>
        <form method="post" action="{{ route('machines.update', ['machine' => $machine->id]) }}" class="space-y-6">
            @csrf
            @method('put')
            <div>
                <x-input-label for="label" :value="__('Label')" />
                <x-text-input id="label" name="label" type="text" class="mt-1 block w-full" :value="old('label', $machine->label)"/>
                <x-input-error class="mt-2" :messages="$errors->get('label')"/>
            </div>
            <div>
                <x-input-label for="latitude" :value="__('Latitude')" />
                <x-text-input id="latitude" name="latitude" type="text" class="mt-1 block w-full" :value="old('latitude', $machine->latitude)" />
                <x-input-error class="mt-2" :messages="$errors->get('latitude')" />
            </div>
            <div>
                <x-input-label for="longitude" :value="__('longitude')" />
                <x-text-input id="longitude" name="longitude" type="text" class="mt-1 block w-full" :value="old('longitude', $machine->longitude)" />
                <x-input-error class="mt-2" :messages="$errors->get('longitude')" />
            </div>
            <x-primary-button>{{ __('Save') }}</x-primary-button>
        </form>
    </x-middle>
</x-app-layout>