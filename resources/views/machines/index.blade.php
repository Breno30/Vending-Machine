<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Machines') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="p-6 text-gray-900 dark:text-gray-100">
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                    @foreach ($machines as $machine)
                    <div class="bg-white dark:bg-gray-800 hover:opacity-80 overflow-hidden shadow-sm sm:rounded-lg p-4  place-self-center w-fit">
                        <a href="{{ route('machines.show', ['machine' => $machine->id]) }}" class="">
                            <img src="{{ asset('images/icon-vending-machine.png') }}" alt="Machine 1">
                            <h4 class="text-md font-semibold text-center">{{ $machine->label }}</h4>
                            <p class="text-sm text-center">{{ __('Description of Machine 1') }}</p>
                        </a>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</x-app-layout>